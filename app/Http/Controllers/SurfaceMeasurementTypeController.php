<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurfaceMeasurementTypeRequest;
use App\Http\Requests\UpdateSurfaceMeasurementTypeRequest;
use App\Http\Resources\SurfaceMeasurementTypeResource;
use App\Models\Post;
use App\Models\Property;
use App\Models\SurfaceMeasurementType;

class SurfaceMeasurementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index()
    {
        return response()->json(SurfaceMeasurementType::all());
    }

    /**
     * Store a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSurfaceMeasurementTypeRequest $request)
    {
        if ($request->validated()) {
            $surfaceMeasurementType = SurfaceMeasurementType::createSurfaceMeasurementType(($request->all()));
        }
        $message = 'Medida de superficie creada correctamente.';
        return response()->json(['message' => $message, 'surface_measurement_type' => $surfaceMeasurementType], 201);
    }

    /**
     * Display the specified SurfaceMeasurementType.
     *
     * @param integer  $surface_measurement_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($surface_measurement_type_id)
    {
        return response()->json(SurfaceMeasurementType::find($surface_measurement_type_id));
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateSurfaceMeasurementTypeRequest $request
     * @param  integer $surface_measurement_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSurfaceMeasurementTypeRequest $request, $surface_measurement_type_id)
    {
        if ($request->validated()) {
            $surfaceMeasurementType = $this->findOrFail($surface_measurement_type_id);
            $surfaceMeasurementType->name = $request->input('name');
            $surfaceMeasurementType->short_name = $request->input('short_name');
            $surfaceMeasurementType->save();
        }

        $message = 'La medida de superficie fue actualizada correctamente.';
        return response()->json(['message' => $message, 'surface_measurement_type' => $surfaceMeasurementType], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $surface_measurement_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($surface_measurement_type_id)
    {
        $surfaceMeasurementType = $this->findOrFail($surface_measurement_type_id);

        $posts = Property::where('surface_measurement_type_id', '=', $surface_measurement_type_id)->get();
        if (count($posts) == 0) {
            $surfaceMeasurementType->delete();
            $message = 'La medida de superficie ha sido eliminado correctamente.';
        } else {
            $message = 'La medida de superficie no puede eliminarse porque esta siendo usado por propiedades existentes.';
        }

        return response()->json(['message' => $message], 201);
    }

    private function findOrFail($surface_measurement_type_id)
    {
        try {
            return SurfaceMeasurementType::findOrFail($surface_measurement_type_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Recurso no encontrado'], 404);
        }
    }
}
