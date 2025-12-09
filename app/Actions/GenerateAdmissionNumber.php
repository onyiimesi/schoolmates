<?php

namespace App\Actions;

use App\Models\AdmissionNumber;
use Illuminate\Support\Facades\Date;

final readonly class GenerateAdmissionNumber
{
    public function handle(string $schoolInitial): string
    {
        $monthYear = Date::now()->format('mY');

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
