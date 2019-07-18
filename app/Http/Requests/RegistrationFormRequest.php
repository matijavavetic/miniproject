<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationFormRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ];
    }

    /**
     * Validating form data for newly registered user
     *
     * @throws \Exception
     * @return array
     */
    public function validateData()
    {
        $input = [
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'password' => $this->input('password')
        ];

        return $input;
    }
}
