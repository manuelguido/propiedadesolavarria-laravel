<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropertyTypeRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('property_type', 'name')->ignore($this->property_type_id, 'property_type_id')
            ]
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
            'name.required' => 'El tipo de propiedad es obligatorio',
            'name.string' => 'El tipo de propiedad debe ser solo texto',
            'name.max' => 'El tipo de propiedad no debe exceder los 50 caracteres',
            'name.unique' => 'El tipo de propiedad ya existe',
        ];
    }
}
