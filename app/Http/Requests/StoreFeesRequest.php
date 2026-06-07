<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeesRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            // 'Grade_id' => 'required|integer',
            'Classroom_id' => 'required|integer',
            'year' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' =>'Title is required',
            'Password.required' => trans('validation.required'),
            'amount.required' => trans('validation.required'),
            'amount.numeric' => trans('validation.numeric'),
            // 'Grade_id.required' => trans('validation.required'),
            'Classroom_id.required' => trans('validation.required'),
            'year.required' => trans('validation.required'),
        ];
    }
}
