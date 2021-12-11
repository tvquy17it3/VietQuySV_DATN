<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'name' => 'required|max:200|string',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|max:20',
            'gender'=> 'in:M,F',
            'birth_date'=> 'required|date',
            'from_date'=> 'required|date',
            'salary' => 'required|numeric|between:0,1000000000',
            'department_id'=> 'required|integer',
            'position_id' => 'required|integer',
        ];
    }
}


//https://hocwebchuan.com/tutorial/laravel/laravel_validate_values.php
