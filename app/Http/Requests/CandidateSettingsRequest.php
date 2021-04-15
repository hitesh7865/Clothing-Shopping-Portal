<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidateSettingsRequest extends FormRequest
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
         'send_reminder_email_days' => [
             'integer'
         ]
        ];
    }
    public function messages()
    {
        return [
          'send_reminder_email_days.integer' => "Please provide days in numeric e.g 1"
        ];
    }
}
