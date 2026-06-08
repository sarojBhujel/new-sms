<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudents extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:students,email,'.$this->id,
            'password' => 'required|string|min:6|max:64',
            'gender_id' => 'required',
            'nationalitie_id' => 'required',
            'blood_id' => 'required',
            'Date_Birth' => 'required|date|date_format:Y-m-d',
            'Grade_id' => 'required',
            'Classroom_id' => 'required',
            'section_id' => 'required',
            'faculty_id' => 'nullable|exists:faculties,id',
            'parent_id' => 'required',
            'fiscal_year_id' => 'nullable|exists:fiscal_years,id',
            'admission_no' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date|date_format:Y-m-d',
            'roll_no' => 'nullable|string|max:255',
        ];
    }
}
