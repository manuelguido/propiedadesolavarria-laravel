<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSurfaceMeasurementTypeRequest extends FormRequest
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
                Rule::unique('surface_measurement_type', 'name')->ignore($this->surface_measurement_type_id, 'surface_measurement_type_id')
            ],
            'short_name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('surface_measurement_type', 'short_name')->ignore($this->surface_measurement_type_id, 'surface_measurement_type_id')
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
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser solo texto.',
            'name.max' => 'El nombre no debe exceder los 50 caracteres.',
            'short_name.required' => 'La abreviación es obligatoria.',
            'short_name.string' => 'La abreviación debe ser solo texto.',
            'short_name.max' => 'La abreviación no debe exceder los 20 caracteres.',
        ];
    }
}
