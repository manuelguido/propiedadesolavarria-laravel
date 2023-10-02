<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Administrator;
use App\Http\Responses\ApiResponse;
use Auth;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index(): \Illuminate\Http\JSONResponse
    {
        if (Auth::user()->cannot('viewAny', Administrator::class)) {
            abort(403);
        }
        return ApiResponse::success(Administrator::allWithUser());
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\StoreUserRequest $userRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $userRequest)
    {
        if (Auth::user()->cannot('create', Administrator::class)) {
            abort(403);
        }

        $userRequest->validated();
        $administrator = Administrator::createAdministrator($userRequest->all());
        $user = Administrator::findWithUser($administrator->administrator_id);

        $message = 'Administrador creado correctamente';
        return ApiResponse::success($user, $message, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $administrator_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $administrator_id): \Illuminate\Http\JSONResponse
    {
        // Find resource
        $administrator = Administrator::find($administrator_id);

        // Permission validation
        if (Auth::user()->cannot('view', $administrator)) {
            abort(403);
        }

        // Api response
        return ApiResponse::success(Administrator::findWithUser($administrator_id));
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateUserRequest $userRequest
     * @param  integer $administrator_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $userRequest, $administrator_id)
    {
        // Find resource
        $administrator = $this->findOrFail($administrator_id);

        // Permission validation
        if (Auth::user()->cannot('update', $administrator)) {
            abort(403);
        }

        // Resource update
        $userRequest->validated();
        $user = $administrator->user;
        $user->name = $userRequest->input('name');
        $user->save();

        $userToReturn = Administrator::findWithUser($administrator_id);

        // Api response
        $message = 'Información actualizada correctamente.';
        return ApiResponse::success($userToReturn, $message, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $administrator_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($administrator_id)
    {
        // Find resource
        $administrator = $this->findOrFail($administrator_id);

        // Find resource or fail
        if (Auth::user()->cannot('delete', $administrator)) {
            abort(403);
        }

        // Deletes resource
        $user = $administrator->user;
        $administrator->delete();
        $user->delete();

        // Api response
        $message = 'El administrador ha sido eliminado correctamente.';
        return ApiResponse::success(null, $message);
    }

    /**
     * Find resource or throw exception.
     *
     * @param  integer $administrator_id
     * @return mixed
     */
    private function findOrFail($administrator_id): mixed
    {
        try {
            return Administrator::findOrFail($administrator_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $message = 'Recurso no encontrado.';
            return ApiResponse::warning(null, $message, 404);
        }
    }
}
