<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubjectNameRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'name' => ['required', 'array', 'min:1'],
            'name.*' => [
                'required',
                'string',
                'max:255',
                'distinct',
                Rule::unique('subject_names', 'name'),
            ],
            'code' => ['required', 'array', 'min:1'],
            'code.*' => [
                'required',
                'string',
                'max:50',
                'distinct',
                Rule::unique('subject_names', 'code'),
            ],
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
            'name.*.required' => 'The subject name is required.',
            'name.*.distinct' => 'Each subject name must be unique within the request.',
            'code.*.required' => 'The subject code is required.',
            'code.*.distinct' => 'Each subject code must be unique within the request.',
            'teacher_id.*.required' => 'Please select a teacher for every selected classroom.',
        ];
    }
}
