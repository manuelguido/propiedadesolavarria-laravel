<?php

namespace App\Http\Controllers;

use App\Models\Currency;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index()
    {
        return response()->json(Currency::all());
    }

    /**
     * Display the specified SurfaceMeasurementType.
     *
     * @param integer  $currency_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($currency_id)
    {
        return response()->json(Currency::find($currency_id));
    }
}
