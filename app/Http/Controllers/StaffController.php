<?php

namespace App\Http\Controllers;

use App\Enum\StaffStatus;
use App\Http\Requests\StaffRequest;
use App\Http\Resources\StaffsResource;
use App\Mail\StaffWelcomeMail;
use App\Models\Campus;
use App\Models\Staff;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = userAuth();

        $search = request()->query('search');

        $staff = Staff::with([
                'school.schoolPayment',
                'school.pricing',
                'designation',
                'subjectteacher',
            ])
            ->where('sch_id', $user->sch_id)
            ->when($user->designation_id != 6, fn($query) => $query->where('campus', $user->campus))
            ->when($search, fn($query) => $query->where(function ($query) use ($search) {
                $query->where('firstname', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('class_assigned', 'like', "%{$search}%");
            }))
            ->paginate(25);

        $staffCollection = StaffsResource::collection($staff);

        return $this->withPagination($staffCollection, "Staff list");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StaffRequest $request)
    {
        $user = userAuth();

        $campus = Campus::where('sch_id', $user->sch_id)
            ->where('name', $request->campus)
            ->first();

        if (!$campus) {
            return $this->error(null, 'Campus does not exist', 404);
        }

        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if ($request->image) {
            $imagePath = uploadImage($request->image, 'staff', $cleanSchId);
        }

        if ($request->signature) {
            $signaturePath = uploadSignature($request->signature, 'signature', $cleanSchId);
        }

        $type = !empty($request->teacher_type) ? $request->teacher_type : null;

        $username = Staff::generateUsername($request->firstname, $request->surname);

        $staff = Staff::create([
            'sch_id' => $user->sch_id,
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type,
            'designation_id' => $request->designation_id,
            'department' => $request->department,
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'username' => $username,
            'email' => $request->email,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'class_assigned' => $request->class_assigned,
            'image' => $imagePath['url'] ?? null,
            'signature' => $signaturePath['url'] ?? null,
            'teacher_type' => $type,
            'is_preschool' => $campus->is_preschool,
            'file_id' => $imagePath['file_id'] ?? null,
            'sig_id' => $signaturePath['file_id'] ?? null,
            'password' => bcrypt($request->password),
            'pass_word' => $request->password,
            'status' => StaffStatus::ACTIVE,
        ]);

        defer_email($request->email, new StaffWelcomeMail($staff));

        return $this->success($staff, 'Staff Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Staff $staff)
    {
        $staffs = new StaffsResource($staff);
        return $this->success($staffs, 'Staff Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Staff $staff)
    {
        $user = userAuth();

        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        $imagePath = uploadImage($request->image, 'staff', $cleanSchId, $staff->file_id);
        $signaturePath = uploadSignature($request->signature, 'signature', $cleanSchId, $staff->sig_id);

        $type = !empty($request->teacher_type) ? $request->teacher_type : null;

        $campus = Campus::where('name', $request->campus)->first();

        $staff->update([
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type ?? null,
            'designation_id' => $request->designation_id,
            'department' => $request->department,
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'email' => $request->email,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'class_assigned' => $request->class_assigned,
            'is_preschool' => $campus->is_preschool,
            'image' => $imagePath['url'] ?? ($request->image ?: $staff->image),
            'signature' => $signaturePath['url'] ?? ($request->signature ?: $staff->signature),
            'teacher_type' => $type,
            'file_id' => $imagePath['file_id'] ?? $staff->file_id,
            'sig_id' => $signaturePath['file_id'] ?? $staff->sig_id,
        ]);

        return $this->success(null, 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return response(null, 204);
    }
}
