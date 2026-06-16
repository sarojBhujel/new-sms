<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectNameRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $subjectNameId = optional($this->route('subject_name'))->id;

        $rules = [
            'name' => ['required', 'string', 'max:255', Rule::unique('subject_names', 'name')->ignore($subjectNameId)],
            'code' => ['required', 'string', 'max:50', Rule::unique('subject_names', 'code')->ignore($subjectNameId)],
            'classroom_ids' => ['nullable', 'array'],
            'classroom_ids.*' => ['integer', 'exists:classrooms,id'],
            'grade_ids' => ['nullable', 'array'],
            'grade_ids.*' => ['integer', 'exists:grades,id'],
            'teacher_id' => ['nullable', 'array'],
        ];

        foreach ($this->input('classroom_ids', []) as $classroomId) {
            $rules["teacher_id.{$classroomId}"] = ['required', 'integer', 'exists:teachers,id'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The subject name is required.',
            'name.unique' => 'The subject name must be unique.',
            'code.required' => 'The subject code is required.',
            'code.unique' => 'The subject code must be unique.',
            'teacher_id.*.required' => 'Please select a teacher for every selected classroom.',
        ];
    }
}
