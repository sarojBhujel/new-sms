<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecialization extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'specialization_name' => 'required|string|unique:specializations,specialization_name,' . $this->id,
            'specialization_code' => 'nullable|string|unique:specializations,specialization_code,' . $this->id,
            'description' => 'nullable|string',
            'status' => 'boolean',
        ];
    }
}
