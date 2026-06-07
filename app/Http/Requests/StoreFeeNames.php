<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeeNames extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $type = $this->input('type');
        $feeNameId = optional($this->route('fee_name'))->id;

        $rules = [
            'name' => 'required|string|max:255|unique:fee_names,name,' . $feeNameId,
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:monthly,yearly,custom',
            'classroom_id' => 'required|array',
            'classroom_id.*' => 'required|integer|exists:classrooms,id',
            'class_amount' => 'required|array',
            'class_amount.*' => 'nullable|numeric|min:0',
            'class_remarks' => 'nullable|array',
            'class_remarks.*' => 'nullable|string|max:255',
        ];

        if ($type === 'yearly') {
            $rules['months'] = 'required|array|size:1';
            $rules['months.*'] = 'required|string';
        }

        if ($type === 'custom') {
            $rules['months'] = 'required|array|min:1';
            $rules['months.*'] = 'required|string';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => trans('validation.required'),
            'name.unique' => trans('validation.unique'),
            'amount.required' => trans('validation.required'),
            'amount.numeric' => trans('validation.numeric'),
            'type.required' => trans('validation.required'),
            'type.in' => trans('validation.in'),
            'classroom_id.required' => trans('validation.required'),
            'classroom_id.array' => trans('validation.array'),
            'classroom_id.*.exists' => trans('validation.exists'),
            'class_amount.required' => trans('validation.required'),
            'class_amount.*.numeric' => trans('validation.numeric'),
            'months.required' => trans('validation.required'),
            'months.size' => trans('validation.size'),
            'months.min' => trans('validation.min'),
        ];
    }
}
