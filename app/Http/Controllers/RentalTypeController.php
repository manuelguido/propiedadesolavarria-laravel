<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRentalTypeRequest;
use App\Http\Requests\UpdateRentalTypeRequest;
use App\Http\Resources\RentalTypeResource;
use App\Models\Post;
use App\Models\RentalType;

class RentalTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = RentalType::all();
        $resource = new RentalTypeResource($collection->toArray());
        return $resource->collectionToJson($collection);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRentalTypeRequest $request)
    {
        if ($request->validated()) {
            $rentalType = RentalType::create(($request->all()));
        }
        $message = 'Tipo de transacción creado correctamente.';
        return response()->json(['message' => $message, 'rental_type' => $rentalType], 201);
    }

    /**
     * Display the specified RentalType.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($rental_type_id)
    {
        $rentalType = RentalType::find($rental_type_id);
        return response()->json($rentalType);
        // $resource = new RentalTypeResource($rentalType->toArray());
        // return $resource->toArray($rentalType);
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateUserRequest $userRequest
     * @param  integer $rental_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRentalTypeRequest $rentalTypeRequest, $rental_type_id)
    {
        if ($rentalTypeRequest->validated()) {
            $rentalType = $this->findOrFail($rental_type_id);
            $rentalType->name = $rentalTypeRequest->input('name');
            $rentalType->save();
        }

        $message = 'Tipo de transacción actualizada correctamente.';
        return response()->json(['message' => $message, 'rental_type' => $rentalType], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $rental_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($rental_type_id)
    {
        $rentalType = $this->findOrFail($rental_type_id);

        $posts = Post::where('rental_type_id', '=', $rental_type_id)->get();
        if (count($posts) == 0) {
            $rentalType->delete();
            $message = 'El tipo de renta ha sido eliminado correctamente.';
        } else {
            $message = 'El tipo de renta no puede eliminarse porque esta siendo usado por publicaciones activas.';
        }

        return response()->json(['message' => $message], 201);
    }

    private function findOrFail($rental_type_id)
    {
        try {
            return RentalType::findOrFail($rental_type_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Recurso no encontrado'], 404);
        }
    }
}
