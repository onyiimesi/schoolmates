<?php

namespace App\Http\Requests\v2;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class AddStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'designation_id' => ['required', 'string'],
            'department' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'campus' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('staff', 'email')],
            'gender' => ['required', 'string', Rule::in(['male', 'female'])],
            'phoneno' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'string'],
            'signature' => ['nullable', 'string'],
            'password' => ['required', 'string', Rules\Password::defaults()],
            'teacher_type' => [
                Rule::requiredIf(fn() => $this->input('designation_id') == 4),
                'exclude_unless:designation_id,4',
                'string',
                Rule::in(['class teacher', 'subject teacher']),
            ],
            // Only validated when teacher_type is "class teacher" — ignored otherwise
            'class_assigned' => [
                'exclude_unless:designation_id,4',
                'exclude_unless:teacher_type,class teacher',
                'required',
                'string',
            ],
            // Only validated when teacher_type is "subject teacher" — ignored otherwise
            // exclude_unless cascades to all subject_assignments.* children automatically
            'subject_assignments' => [
                'exclude_unless:designation_id,4',
                'exclude_unless:teacher_type,subject teacher',
                'required',
                'array',
                'min:1',
            ],
            'subject_assignments.*.class_id' => [
                'required',
                'string',
                'exists:class_models,id',
            ],
            'subject_assignments.*.subjects' => [
                'required',
                'array',
                'min:1',
            ],
            'subject_assignments.*.subjects.*.name' => [
                'required',
                'string',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'class_assigned.required' => 'A class must be assigned for a class teacher.',
            'subject_assignments.required' => 'Subject assignments are required for a subject teacher.',
            'subject_assignments.min' => 'At least one subject assignment is required.',
            'subject_assignments.*.class_id.required' => 'Each subject assignment must include a class ID.',
            'subject_assignments.*.class_id.exists' => 'One or more selected classes do not exist.',
            'subject_assignments.*.subjects.required' => 'Each class assignment must include at least one subject.',
            'subject_assignments.*.subjects.*.name.required' => 'Each subject must have a name.',
        ];
    }
}
