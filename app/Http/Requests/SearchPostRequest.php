<?php

namespace App\Http\Requests;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class SearchPostRequest extends FormRequest
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
            'property_type_id' => 'required|numeric|min:1|exists:property_type,property_type_id',
            'rental_type_id' => 'required|numeric|min:1|exists:rental_type,rental_type_id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        // return [
        //     'property_type_id.required' => 'El campo property_type_id es obligatorio.',
        //     'property_type_id.numeric' => 'El campo property_type_id debe ser numérico.',
        //     'property_type_id.exists' => 'El valor del campo property_type_id no es válido.',

        //     'rental_type_id.required' => 'El campo rental_type_id es obligatorio.',
        //     'rental_type_id.numeric' => 'El campo rental_type_id debe ser numérico.',
        //     'rental_type_id.exists' => 'El valor del campo rental_type_id no es válido.',
        // ];
        return [
            'property_type_id.required' => 'No ingreses valores no permitidos.',
            'property_type_id.numeric' => 'No ingreses valores no permitidos.',
            'property_type_id.exists' => 'No ingreses valores no permitidos.',

            'rental_type_id.required' => 'No ingreses valores no permitidos.',
            'rental_type_id.numeric' => 'No ingreses valores no permitidos.',
            'rental_type_id.exists' => 'No ingreses valores no permitidos.',
        ];
    }
}
