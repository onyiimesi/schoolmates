<?php

namespace App\Services;

use App\Models\Campus;
use App\Traits\StaffTrait;
use Illuminate\Support\Facades\DB;

class StaffService
{
    use StaffTrait;
    public function addStaff($request)
    {
        $user = userAuth();
        $campus = Campus::where('sch_id', $user->sch_id)
            ->where('name', $request->campus)
            ->first();

        $validationError = $this->validatePreCreationConstraints($user, $campus, $request);

        if ($validationError) {
            return $validationError;
        }

        return DB::transaction(fn() => $this->performStaffCreation($request, $user, $campus));
    }

    public function updateStaff($request, $staff)
    {
        $user = userAuth();

        $validationError = $this->validateUpdateConstraints($user, $staff, $request);

        if ($validationError) {
            return $validationError;
        }

        return DB::transaction(fn() => $this->performStaffUpdate($request, $user, $staff));
    }
}
