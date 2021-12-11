<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
            'email' => 'required|string|email|max:250|min:3|unique:users',
            'password' => 'required|string|confirmed|min:6|max:200',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:3|max:20',
            'address' => 'required|max:250|string',
            'gender'=> 'in:M,F',
            'birth_date'=> 'required|date',
            'from_date'=> 'required|date',
            'salary' => 'required|numeric|between:0,1000000000',
            'department_id'=> 'required|integer',
            'position_id' => 'required|integer',
        ];
    }
}
