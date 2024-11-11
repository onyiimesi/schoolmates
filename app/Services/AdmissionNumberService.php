<?php

namespace App\Services;

use App\Models\AdmissionNumber;
use Carbon\Carbon;

class AdmissionNumberService
{
    public function generateUniqueAdmissionNumber($schoolInitial)
    {
        $monthYear = Carbon::now()->format('mY');

        $lastNumber = AdmissionNumber::where('admission_number', 'LIKE', "$schoolInitial/$monthYear/%")
                        ->orderBy('admission_number', 'desc')
                        ->first();

        $nextSequence = $lastNumber ? intval(substr($lastNumber->admission_number, -5)) + 1 : 1;
        $sequence = str_pad($nextSequence, 5, '0', STR_PAD_LEFT);

        $admissionNumber = "$schoolInitial/$monthYear/$sequence";

        AdmissionNumber::create(['admission_number' => $admissionNumber]);

        return $admissionNumber;
    }
}








