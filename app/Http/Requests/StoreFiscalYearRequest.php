<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFiscalYearRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:fiscal_years,name',
            'start_date_np' => 'required|string|max:255',
            'end_date_np' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'sometimes|boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = $this->input('start_date_np');
            $end = $this->input('end_date_np');

            if ($start && $end && $end <= $start) {
                $validator->errors()->add('end_date_np', 'End Date (NP) must be after Start Date (NP).');
            }
        });
    }
}
