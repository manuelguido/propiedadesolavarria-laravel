<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavouriteCollectionRequest;
use App\Http\Requests\UpdateFavouriteCollectionRequest;
use App\Models\FavouriteCollection;

class FavouriteCollectionController extends Controller
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
     * @param  \App\Http\Requests\StoreFavouriteCollectionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFavouriteCollectionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FavouriteCollection  $favouriteCollection
     * @return \Illuminate\Http\Response
     */
    public function show(FavouriteCollection $favouriteCollection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFavouriteCollectionRequest  $request
     * @param  \App\Models\FavouriteCollection  $favouriteCollection
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFavouriteCollectionRequest $request, FavouriteCollection $favouriteCollection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FavouriteCollection  $favouriteCollection
     * @return \Illuminate\Http\Response
     */
    public function destroy(FavouriteCollection $favouriteCollection)
    {
        //
    }
}
