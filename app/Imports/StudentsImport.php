<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements
ToModel,
WithHeadingRow,
WithValidation,
WithBatchInserts,
WithChunkReading
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function rules(): array
    {
        return [
            '*.surname' => ['required', 'string'],
            '*.firstname' => ['required', 'string', 'max:255'],
            '*.middlename' => ['required', 'string', 'max:255'],
            '*.admission_number' => ['required', 'string', 'max:255', 'unique:students,admission_number'],
            '*.username' => ['string', 'max:255'],
            '*.genotype' => ['required', 'string', 'max:255'],
            '*.blood_group' => ['required', 'string', 'max:255'],
            '*.gender' => ['required', 'string', 'max:255'],
            '*.dob' => ['required', 'max:255'],
            '*.nationality' => ['required', 'string', 'max:255'],
            '*.state' => ['required', 'string', 'max:255'],
            '*.session_admitted' => ['required', 'string', 'max:255'],
            '*.class' => ['required', 'string', 'max:255'],
            '*.present_class' => ['required', 'string', 'max:255'],
            '*.sub_class' => ['required', 'string', 'max:255'],
            '*.home_address' => ['required', 'string', 'max:255'],
            '*.phone_number' => ['required', 'max:255'],
            '*.email_address' => ['required', 'string', 'max:255', 'unique:students,email_address'],
        ];
    }
    
    public function model(array $row)
    {
        $user = Auth::user();

        return new Student([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'designation_id' => $user->designation_id,
            'surname' => $row['surname'],
            'firstname' => $row['firstname'],
            'middlename' => $row['middlename'],
            'admission_number' => $row['admission_number'],
            'username' => $row['username'],
            'password' => Hash::make('12345678'),
            'pass_word' => '12345678',
            'genotype' => $row['genotype'],
            'blood_group' => $row['blood_group'],
            'gender' => $row['gender'],
            'dob' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['dob']),
            'nationality' => $row['nationality'],
            'state' => $row['state'],
            'session_admitted' => $row['session_admitted'],
            'class' => $row['class'],
            'class_sub_class' => '',
            'present_class' => $row['present_class'],
            'sub_class' => $row['sub_class'],
            'image' => '',
            'home_address' => $row['home_address'],
            'phone_number' => $row['phone_number'],
            'email_address' => $row['email_address'],
            'status' => 'active',
            'created_by' => $user->surname .' '. $user->firstname .' '. $user->middlename,
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    
}
