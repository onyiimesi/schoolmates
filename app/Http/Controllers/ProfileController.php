<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaffsResource;
use App\Http\Resources\StudentResource;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ImageKit\ImageKit;

class ProfileController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $stud = Auth::user();

        if($stud->designation_id){

            $staffs = new StaffsResource($stud);

            return [
                'status' => 'true',
                'message' => 'Profile Details',
                'data' => $staffs
            ];

        }else if($stud->designation_id == '7'){

            $studs = new StudentResource($stud);

            return [
                'status' => 'true',
                'message' => 'Profile Details',
                'data' => $studs
            ];
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL_ENDPOINT')
        );

        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        $imageUrl = $user->image;
        $imageFileId = $user->file_id;

        if ($request->filled('image')) {
            $imageUpload = $this->handleImagekitUpload($request->image, $imageKit, $user->file_id, $cleanSchId, $user->designation_id == '7' ? 'student' : 'staff');
            $imageUrl = $imageUpload['url'];
            $imageFileId = $imageUpload['fileId'];
        }

        $signatureUrl = $user->signature;
        $signatureFileId = $user->sig_id;

        if ($user->designation_id != '7' && $request->filled('signature')) {
            $signatureUpload = $this->handleImagekitUpload($request->signature, $imageKit, $user->sig_id, $cleanSchId, 'signature');
            $signatureUrl = $signatureUpload['url'];
            $signatureFileId = $signatureUpload['fileId'];
        }

        $updateData = [
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'username' => $request->username,
            'email' => $request->email,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'image' => $imageUrl,
            'file_id' => $imageFileId,
            'signature' => $user->designation_id != '7' ? $signatureUrl : null,
            'sig_id' => $user->designation_id != '7' ? $signatureFileId : null,
        ];

        if ($user->designation_id != '7') {
            $updateData['department'] = $request->department;
        }

        $user->update($updateData);

        return $this->success($updateData, 'Profile updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function handleImagekitUpload($base64File, $imageKit, $existingFileId, $schoolId, $folder)
    {
        if ($existingFileId) {
            $imageKit->deleteFile($existingFileId);
        }

        $extension = explode('/', explode(':', substr($base64File, 0, strpos($base64File, ';')))[1])[1];
        $fileName = uniqid() . '.' . $extension;
        $folderPath = $folder . '/' . $schoolId;

        $upload = $imageKit->uploadFile([
            'file' => $base64File,
            'fileName' => $fileName,
            'folder' => $folderPath
        ]);

        return [
            'url' => $upload->result->url,
            'fileId' => $upload->result->fileId
        ];
    }

}
