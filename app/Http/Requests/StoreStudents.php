<?php

namespace App\Http\Requests;

use App\Models\Student;
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
        $createLoginCredentials = $this->boolean('create_login_credentials');
        $isUpdate = $this->filled('id');
        $existingStudent = $isUpdate ? Student::find($this->id) : null;
        $hasExistingPassword = $existingStudent && !empty($existingStudent->password);

        $emailRule = $createLoginCredentials
            ? 'required|email|unique:students,email,' . $this->id
            : 'nullable|email|unique:students,email,' . $this->id;

        $passwordRule = 'nullable|string|min:6|max:64';

        if ($createLoginCredentials) {
            if ($isUpdate) {
                $passwordRule = $hasExistingPassword
                    ? 'nullable|string|min:6|max:64'
                    : 'required|string|min:6|max:64';
            } else {
                $passwordRule = 'required|string|min:6|max:64';
            }
        }

        return [
            'name' => 'required',
            'email' => $emailRule,
            'password' => $passwordRule,
            'phone' => 'nullable|string|max:50',
            'gender_id' => 'required',
            'nationalitie_id' => 'required',
            'blood_id' => 'nullable|exists:type__bloods,id',
            'Date_Birth' => 'required|date|date_format:Y-m-d',
            'Grade_id' => 'required',
            'Classroom_id' => 'required',
            'section_id' => 'nullable|exists:sections,id',
            'faculty_id' => 'nullable|exists:faculties,id',
            'parent_id' => 'required',
            'fiscal_year_id' => 'nullable|exists:fiscal_years,id',
            'admission_no' => 'nullable|string|max:255',
            'admission_date' => 'nullable|date|date_format:Y-m-d',
            'roll_no' => 'nullable|string|max:255',
        ];
    }
}
