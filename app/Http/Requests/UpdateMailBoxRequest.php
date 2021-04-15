<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateMailBoxRequest extends FormRequest
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
           'imap_name' =>'required',
           'imap_host' => 'required',
           'imap_user' => 'required',
           'imap_password' => 'required'
        ];
    }
    public function messages()
    {
        return [
          'imap_name.required' => "Please provide IMAP name",
          'imap_host.required' =>"Please provide a HOST for IMAP",
          'imap_user.required' => "Please provide a valid user",
          'imap_password.required' => "Please provide a valid password"
        ];
    }
}
