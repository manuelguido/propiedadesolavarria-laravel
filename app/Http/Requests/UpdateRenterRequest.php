<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRenterRequest extends FormRequest
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
            'phone' => 'required|numeric|digits_between:7,20',
            'whatsapp_phone' => 'required|numeric|digits_between:7,20',
            'commercial_email' => 'string|max:150',
            'address' => 'required|string|max:100',
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
            'phone.required' => 'Debes ingresar una número de teléfono.',
            'phone.numeric' => 'Ingresá sólo números.',
            'phone.digits_between' => 'Ingresá un número de teléfono entre :min y :max dígitos.',
            'whatsapp_phone.required' => 'Debes ingresar una número de whatsapp.',
            'whatsapp_phone.numeric' => 'Ingresá sólo números.',
            'whatsapp_phone.digits_between' => 'Ingresá un número de whatsapp entre :min y :max dígitos.',
            'commercial_email.string' => 'El email tiene que estar en formato texto.',
            'commercial_email.max' => 'El email commercial no debe tener más de 150 caractéres.',
            'address.required' => 'Debes ingresar una dirección.',
            'address.string' => 'La dirección tiene que estar en formato texto.',
            'address.max' => 'La dirección no debe tener más de 100 caractéres.',
        ];
    }
}
