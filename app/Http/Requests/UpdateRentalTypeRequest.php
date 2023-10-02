<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRentalTypeRequest extends FormRequest
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
                'max:80',
                Rule::unique('rental_type', 'name')->ignore($this->rental_type_id, 'rental_type_id')
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
            'name.required' => 'El nombre es obligatorio',
            'name.string' => 'El nombre debe ser solo texto',
            'name.max' => 'El nombre no debe exceder los 80 caracteres',
        ];
    }
}
