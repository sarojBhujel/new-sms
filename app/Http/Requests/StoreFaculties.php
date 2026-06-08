<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaculties extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'faculty_name' => 'required|string|max:255',
            'faculty_code' => 'required|string|max:255|unique:faculties,faculty_code,' . $this->id,
            'grade_id' => 'required|exists:grades,id',
            'status' => 'sometimes|boolean',
        ];
    }

    public function messages()
    {
        return [
            'faculty_name.required' => 'Faculty name is required.',
            'faculty_code.required' => 'Faculty code is required.',
            'faculty_code.unique' => 'Faculty code must be unique.',
            'grade_id.required' => 'Grade is required.',
            'grade_id.exists' => 'Selected grade does not exist.',
        ];
    }
}
