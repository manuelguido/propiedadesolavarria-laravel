<?php

namespace App\Http\Controllers;

use App\Models\SurfaceType;

class SurfaceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index()
    {
        return response()->json(SurfaceType::all());
    }

    /**
     * Display the specified SurfaceMeasurementType.
     *
     * @param integer  $surface_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($surface_type_id)
    {
        return response()->json(SurfaceType::find($surface_type_id));
    }
}
