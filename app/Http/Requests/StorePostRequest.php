<?php

namespace App\Http\Requests;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission(Permission::PostStore);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:150',
            'value' => 'required|numeric|min:0',
            'expenses' => 'required|numeric|min:0',
            'property_id' => 'required|numeric|exists:property,property_id',
            'rental_type_id' => 'required|numeric|exists:rental_type,rental_type_id',
            'value_currency_id' => 'required|numeric|exists:currency,currency_id',
            'expenses_currency_id' => 'required|numeric|exists:currency,currency_id',
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
            'title.required' => 'El título es requerido.',
            'title.string' => 'El título debe ser una cadena de caracteres.',
            'title.max' => 'El título no debe superar los 150 caracteres.',

            'value.required' => 'El valor es requerido.',
            'value.numeric' => 'El valor debe ser numérico.',
            'value.min' => 'El valor debe ser mayor o igual a 0.',

            'expenses.required' => 'Los gastos son requeridos.',
            'expenses.numeric' => 'Los gastos deben ser numéricos.',
            'expenses.min' => 'Los gastos deben ser mayores o iguales a 0.',

            'property_id.required' => 'El id de propiedad es requerido.',
            'property_id.numeric' => 'El id de propiedad debe ser numérico.',
            'property_id.exists' => 'El id de propiedad no existe en la tabla de propiedades.',

            'rental_type_id.required' => 'El id de tipo de alquiler es requerido.',
            'rental_type_id.numeric' => 'El id de tipo de alquiler debe ser numérico.',
            'rental_type_id.exists' => 'El id de tipo de alquiler no existe en la tabla de tipos de alquiler.',

            'value_currency_id.required' => 'El id de moneda de valor es requerido.',
            'value_currency_id.numeric' => 'El id de moneda de valor debe ser numérico.',
            'value_currency_id.exists' => 'El id de moneda de valor no existe en la tabla de monedas.',

            'expenses_currency_id.required' => 'El id de moneda de gastos es requerido.',
            'expenses_currency_id.numeric' => 'El id de moneda de gastos debe ser numérico.',
            'expenses_currency_id.exists' => 'El id de moneda de gastos no existe en la tabla de monedas.',
        ];
    }
}
