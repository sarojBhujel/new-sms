<?php

namespace App\Http\Requests;

use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeacher extends FormRequest
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
        $existingTeacher = $isUpdate ? Teacher::find($this->id) : null;
        $hasExistingPassword = $existingTeacher && !empty($existingTeacher->password);

        $emailRule = $createLoginCredentials
            ? 'required|email|unique:teachers,email,' . $this->id
            : 'nullable|email|unique:teachers,email,' . $this->id;

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
            'Name' => 'required|string|max:255',
            'Email' => $emailRule,
            'Password' => $passwordRule,
            'Specialization_id' => 'required|exists:specializations,id',
            'Gender_id' => 'required|exists:genders,id',
            'Joining_Date' => 'required|date|date_format:Y-m-d',
            'Address' => 'nullable|string|max:255',
        ];
    }
}
