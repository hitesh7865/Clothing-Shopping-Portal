<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
         'email' => [
             'required',
             'string',
             'email',
             Rule::exists('users')->where(function ($query) {
                 $query->where('status', 1);
             }),
         ],
         'password' => 'required|string'
        ];
    }
    public function messages()
    {
        return [
          'email.required' => "Please provide a email",
          'email.string' =>"Please provide a email",
          'email' => "Please provide a valid email",
          'exists' => "An account with given email and password does not exists"
        ];
    }
}
