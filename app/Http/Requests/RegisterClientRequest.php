<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterClientRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:user,email|max:100',
            // 'password' => 'required|string|min:8|max:100',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Por favor, ingresa tu nombre.',
            'name.string' => 'El nombre debe ser una cadena de caracteres.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'email.required' => 'Por favor, ingresa tu correo electrónico.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'email.max' => 'El correo electrónico no puede tener más de :max caracteres.',
            'password.required' => 'Por favor, ingresa una contraseña.',
            'password.string' => 'La contraseña debe ser una cadena de caracteres.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.max' => 'La contraseña no puede tener más de :max caracteres.',
        ];

    }
}
