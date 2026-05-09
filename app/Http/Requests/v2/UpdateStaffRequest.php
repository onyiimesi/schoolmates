<?php

namespace App\Http\Requests\v2;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'designation_id' => ['sometimes', 'string'],
            'department' => ['sometimes', 'string', 'max:255'],
            'surname' => ['sometimes', 'string', 'max:255'],
            'firstname' => ['sometimes', 'string', 'max:255'],
            'middlename' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255'],
            'gender' => ['sometimes', 'string', Rule::in(['male', 'female'])],
            'phoneno' => ['sometimes', 'string', 'max:20'],
            'address' => ['sometimes', 'string', 'max:500'],
            'image' => ['nullable', 'string'],
            'signature' => ['nullable', 'string'],
            'teacher_type' => ['sometimes', 'string', Rule::in(['class teacher', 'subject teacher'])],
            'class_assigned' => [
                'nullable',
                'string',
                Rule::requiredIf(fn() => $this->teacher_type === 'class teacher'),
            ],
            'subject_assignments' => [
                'nullable',
                'array',
                'min:1',
                Rule::requiredIf(fn() => $this->teacher_type === 'subject teacher'),
            ],
            'subject_assignments.*.class_id' => ['required_with:subject_assignments', 'string', 'exists:classes,id'],
            'subject_assignments.*.subjects' => ['required_with:subject_assignments', 'array', 'min:1'],
            'subject_assignments.*.subjects.*.name' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'subject_assignments.required' => 'Subject assignments are required for a subject teacher.',
            'subject_assignments.*.class_id.required_with' => 'Each subject assignment must include a class ID.',
            'subject_assignments.*.class_id.exists' => 'One or more selected classes do not exist.',
            'subject_assignments.*.subjects.required_with' => 'Each class assignment must include at least one subject.',
            'subject_assignments.*.subjects.*.name.required' => 'Each subject must have a name.',
            'class_assigned.required' => 'A class must be assigned for a class teacher.',
        ];
    }
}
