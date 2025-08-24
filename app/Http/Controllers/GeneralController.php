<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Campus;
use App\Models\Student;
use App\Enum\StaffStatus;
use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class GeneralController extends Controller
{
    use HttpResponses;

    public function enableCampus($id)
    {
        $campus = Campus::find($id);

        if (! $campus) {
            return $this->error('', 'Campus does not exist', 400);
        }

        $campus->update(['status' => 'active']);

        return $this->success(null, 'Enabled Successfully');
    }

    public function disableCampus($id)
    {
        $campus = Campus::find($id);

        if (! $campus) {
            return $this->error('', 'Campus does not exist', 400);
        }

        $campus->update(['status' => 'disabled']);

        return $this->success(null, 'Disabled Successfully');
    }

    public function enableStaff($id)
    {
        $staff = Staff::find($id);

        if (! $staff) {
            return $this->error(null, 'Staff does not exist', 400);
        }

        $staff->update(['status' => StaffStatus::ACTIVE]);

        return $this->success(null, 'Staff enabled successfully');
    }

    public function disableStaff($id)
    {
        $staff = Staff::find($id);

        if(! $staff){
            return $this->error('', 'Staff does not exist', 400);
        }

        $staff->update(['status' => StaffStatus::DISABLED]);

        return $this->success(null, 'Staff disabled successfully');
    }

    public function enableStudent($id)
    {
        $student = Student::find($id);

        if (! $student){
            return $this->error(null, 'Student does not exist', 400);
        }

        $student->update(['status' => 'active']);

        return $this->success(null, 'Account Enabled Successfully');
    }

    public function disableStudent($id)
    {
        $student = Student::find($id);

        if (! $student) {
            return $this->error(null, 'Student does not exist', 400);
        }

        $student->update(['status' => 'disabled']);

        return $this->success(null, 'Account Disabled Successfully');
    }

    public function getAnnouncements()
    {
        $schoolId = request()->query('sch_id');

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
