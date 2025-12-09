<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaffsResource;
use App\Http\Resources\StudentResource;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return $this->error(null, 'Unauthenticated.', 401);
        }

        $designationId = (int) ($user->designation_id ?? 0);

        if ($designationId === 7) {
            $resource = new StudentResource($user);
        } elseif ($designationId > 0) {
            $resource = new StaffsResource($user);
        } else {
            return $this->error('Designation not set for this user.', 422);
        }

        return $this->success($resource, 'Profile Details');
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();

        if ($request->filled('image')) {
            $imageUpload = $this->handleImagekitUpload($request->image, $user, $user->file_id, $user->designation_id == '7' ? 'student' : 'staff');
        }

        if ($user->designation_id != '7' && $request->filled('signature')) {
            $signatureUpload = $this->handleImagekitUpload($request->signature, $user, $user->sig_id, 'signature');
        }

        $updateData = [
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'username' => $request->username,
            'address' => $request->address,
            'image' => $imageUpload['url'] ?? $user->image,
            'file_id' => $imageUpload['fileId'] ?? $user->file_id,
            'signature' => $signatureUpload['url'] ?? $user->signature,
            'sig_id' => $signatureUpload['fileId'] ?? $user->sig_id,
        ];

        if ($user->designation_id != '7') {
            $updateData['department'] = $request->department;
            $updateData['phoneno'] = $request->phoneno;
            $updateData['email'] = $request->email;
        } else {
            $updateData['phone_number'] = $request->phoneno;
            $updateData['email_address'] = $request->email;
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

    private function handleImagekitUpload($file, $user, $fileId, $folder)
    {
        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
        return uploadImage($file, $folder, $cleanSchId, $fileId);
    }
}
