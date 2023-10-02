<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyTypeRequest;
use App\Http\Requests\UpdatePropertyTypeRequest;
use App\Models\Property;
use App\Models\PropertyType;

class PropertyTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index()
    {
        return response()->json(PropertyType::all());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePropertyTypeRequest $request)
    {
        if ($request->validated()) {
            $propertyType = PropertyType::createPropertyType(($request->all()));
        }
        $message = 'Tipo de propiedad creado correctamente.';
        return response()->json(['message' => $message, 'property_type' => $propertyType], 201);
    }

    /**
     * Display the specified PropertyType.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($property_type_id)
    {
        $propertyType = PropertyType::find($property_type_id);
        return response()->json($propertyType);
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateUserRequest $userRequest
     * @param  integer $property_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdatePropertyTypeRequest $propertyTypeRequest, $property_type_id)
    {
        if ($propertyTypeRequest->validated()) {
            $propertyType = $this->findOrFail($property_type_id);
            $propertyType->name = $propertyTypeRequest->input('name');
            $propertyType->save();
        }

        $message = 'Tipo de propiedad actualizada correctamente.';
        return response()->json(['message' => $message, 'property_type' => $propertyType], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $property_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($property_type_id)
    {
        $propertyType = $this->findOrFail($property_type_id);

        $properties = Property::where('property_type_id', '=', $property_type_id)->get();
        if (count($properties) == 0) {
            $propertyType->delete();
            $message = 'El tipo de propiedad ha sido eliminado correctamente.';
        } else {
            $message = 'El tipo de propiedad no puede eliminarse porque esta siendo usado por publicaciones activas.';
        }

        return response()->json(['message' => $message], 201);
    }

    private function findOrFail($property_type_id)
    {
        try {
            return PropertyType::findOrFail($property_type_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Recurso no encontrado'], 404);
        }
    }
}
