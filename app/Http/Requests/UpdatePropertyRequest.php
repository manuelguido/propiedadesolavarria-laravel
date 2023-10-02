<?php

namespace App\Http\Requests;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->hasPermission(Permission::PropertyStore);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'enviroments' => 'required|numeric|min:0',
            'bathrooms' => 'required|numeric|min:0',
            'bedrooms' => 'required|numeric|min:0',
            'garages' => 'required|numeric|min:0',
            'total_surface' => 'required|numeric|min:0',
            'covered_surface' => 'required|numeric|min:0',
            'surface_measurement_type_id' => 'required|numeric|exists:surface_measurement_type,surface_measurement_type_id',
            'antiquity_type_id' => 'required|numeric|exists:antiquity_type,antiquity_type_id',
            'images' => 'required',
            'images.*.file' => 'required|file|mimes:jpeg,png|max:4096',
            'images.*.order' => [
                'required',
                'integer',
                'min:1',
            ],
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
            'max' => [
                'name' => 'El nombre debe tener como máximo :max caracteres',
            ],
            'required' => [
                'name' => 'El nombre es obligatorio',
                'enviroments' => 'El número de ambientes es obligatorio',
                'bathrooms' => 'El número de baños es obligatorio',
                'bedrooms' => 'El número de dormitorios es obligatorio',
                'garages' => 'El número de garages es obligatorio',
                'total_surface' => 'El número de superficie total es obligatorio',
                'covered_surface' => 'El número de superficie cubierta es obligatorio',
                'surface_measurement_type_id' => 'El campo de tipo de medida de superficie es obligatorio',
                'antiquity_type_id' => 'El campo de tipo de antigüedad es obligatorio',
            ],
            'numeric' => [
                'enviroments' => 'El número de ambientes debe ser un número',
                'bathrooms' => 'El número de baños debe ser un número',
                'bedrooms' => 'El número de dormitorios debe ser un número',
                'garages' => 'El número de garages debe ser un número',
                'total_surface' => 'El número de superficie total debe ser un número',
                'covered_surface' => 'El número de superficie cubierta debe ser un número',
                'surface_measurement_type_id' => 'El campo de tipo de medida de superficie debe ser un número',
                'antiquity_type_id' => 'El campo de tipo de antigüedad debe ser un número',
            ],
            'min' => [
                'enviroments' => 'El campo de ambientes debe tener un valor mínimo de :min',
                'bathrooms' => 'El campo de baños debe tener un valor mínimo de :min',
                'bedrooms' => 'El campo de dormitorios debe tener un valor mínimo de :min',
                'garages' => 'El campo de garages debe tener un valor mínimo de :min',
                'total_surface' => 'El campo de superficie total debe tener un valor mínimo de :min',
                'covered_surface' => 'El campo de superficie cubierta debe tener un valor mínimo de :min',
            ],
            'exists' => [
                'surface_measurement_type_id' => 'El tipo de medida de superficie seleccionado no existe',
                'antiquity_type_id' => 'El tipo de antigüedad seleccionado no existe',
            ],
        ];
    }
}
