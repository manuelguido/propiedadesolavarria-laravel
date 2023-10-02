<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyImageRequest;
use App\Http\Requests\UpdatePropertyImageRequest;
use App\Models\PropertyImage;

class PropertyImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePropertyImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePropertyImageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PropertyImage  $propertyImage
     * @return \Illuminate\Http\Response
     */
    public function show(PropertyImage $propertyImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePropertyImageRequest  $request
     * @param  \App\Models\PropertyImage  $propertyImage
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePropertyImageRequest $request, PropertyImage $propertyImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PropertyImage  $propertyImage
     * @return \Illuminate\Http\Response
     */
    public function destroy(PropertyImage $propertyImage)
    {
        //
    }
}
