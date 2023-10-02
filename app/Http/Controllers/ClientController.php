<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ClientResource;
use App\Http\Responses\ApiResponse;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index(): \Illuminate\Http\JSONResponse
    {
        return ApiResponse::success(Client::allWithUser());
    }

    /**
     * Display the specified Client.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function show($id)
    {
        return response()->json(Client::findWithUser($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Http\Requests\UpdateClientRequest  $request
     * @return \Illuminate\Http\JSONResponse
     */
    public function update(UpdateUserRequest $userRequest, UpdateClientRequest $clientRequest)
    {
        $user = Auth::user();
        if ($userRequest->validated()) {
            $user->name = $userRequest->input('name');
            $user->save();

        }
        $message = 'Información actualizada correctamente.';
        return response()->json(['message' => $message, 'user' => $user], 201);
    }

// /**
//  * Remove the specified resource from storage.
//  *
//  * @param  \App\Models\Client  $client
//  * @return \Illuminate\Http\Response
//  */
// public function destroy(Client $client)
// {
//     //
// }
}
