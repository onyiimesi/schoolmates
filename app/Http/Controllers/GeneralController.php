<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Campus;
use App\Models\Student;
use App\Enum\StaffStatus;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;

class GeneralController extends Controller
{
    use HttpResponses;

    public function enableCampus(int $id): JsonResponse
    {
        $campus = Campus::query()->find($id);

        if (! $campus) {
            return $this->error(null, 'Campus does not exist', 400);
        }

        $campus->update(['status' => 'active']);

        return $this->success(null, 'Enabled Successfully');
    }

    public function disableCampus(int $id): JsonResponse
    {
        $campus = Campus::query()->find($id);

        if (! $campus) {
            return $this->error(null, 'Campus does not exist', 400);
        }

        $campus->update(['status' => 'disabled']);

        return $this->success(null, 'Disabled Successfully');
    }

    public function enableStaff(int $id): JsonResponse
    {
        $staff = Staff::query()->find($id);

        if (! $staff) {
            return $this->error(null, 'Staff does not exist', 400);
        }

        $staff->update(['status' => StaffStatus::ACTIVE]);

        return $this->success(null, 'Staff enabled successfully');
    }

    public function disableStaff(int $id): JsonResponse
    {
        $staff = Staff::query()->find($id);

        if(! $staff) {
            return $this->error(null, 'Staff does not exist', 400);
        }

        $staff->update(['status' => StaffStatus::DISABLED]);

        return $this->success(null, 'Staff disabled successfully');
    }

    public function enableStudent(int $id): JsonResponse
    {
        $student = Student::query()->find($id);

        if (! $student){
            return $this->error(null, 'Student does not exist', 400);
        }

        $student->update(['status' => 'active']);

        return $this->success(null, 'Account Enabled Successfully');
    }

    public function disableStudent(int $id): JsonResponse
    {
        $student = Student::query()->find($id);

        if (! $student) {
            return $this->error(null, 'Student does not exist', 400);
        }

        $student->update(['status' => 'disabled']);

        return $this->success(null, 'Account Disabled Successfully');
    }

    public function getAnnouncements(Request $request): JsonResponse
    {
        $schoolId = $request->query('sch_id');

        if (! $schoolId) {
            return $this->error(null, 'School ID is required', 400);
        }

        $announcements = Announcement::query()
            ->where(function ($q) use ($schoolId) {
                $q->whereJsonContains('schools', 'all')
                ->orWhereJsonContains('schools', $schoolId);
            })
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                ->orWhere('expiry_date', '>=', now());
            })
            ->where('status', 'active')
            ->latest()
            ->get();

        return $this->success($announcements, 'Announcements retrieved successfully');
    }
}
