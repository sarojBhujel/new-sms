<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuickCreateParentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'Name_Father' => 'required|string|max:255',
            'Name_Mother' => 'required|string|max:255',
            'Job_Father' => 'nullable|string|max:255',
            'Job_Mother' => 'nullable|string|max:255',
            'Phone_Father' => 'nullable|string|regex:/^([0-9\\s\\-\\+\\(\\)]*)$/|min:6',
            'Phone_Mother' => 'nullable|string|regex:/^([0-9\\s\\-\\+\\(\\)]*)$/|min:6',
            'Address_Father' => 'nullable|string|max:500',
            'National_ID_Father' => 'nullable|string|max:50',
            // create_user toggle: when present and ==1, email/password are required
            'create_user' => 'nullable|in:0,1',
            'email' => 'nullable|email|unique:my__parents,email|required_if:create_user,1',
            'password' => 'nullable|string|min:6|required_if:create_user,1',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'Name_Father.required' => 'Father name is required.',
            'Name_Father.string' => 'Father name must be a string.',
            'Name_Father.max' => 'Father name must not exceed 255 characters.',
            'Name_Mother.required' => 'Mother name is required.',
            'Phone_Father.regex' => 'Father phone number format is invalid.',
            'Phone_Father.min' => 'Father phone number must be at least 6 characters.',
            'Phone_Mother.regex' => 'Mother phone number format is invalid.',
            'Phone_Mother.min' => 'Mother phone number must be at least 6 characters.',
            'email.required_if' => 'Email is required when creating a user account.',
            'email.email' => 'Email format is invalid.',
            'email.unique' => 'This email is already registered.',
            'password.required_if' => 'Password is required when creating a user account.',
            'password.min' => 'Password must be at least 6 characters.',
        ];
    }
}
