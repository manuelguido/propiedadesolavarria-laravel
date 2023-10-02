<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Moderator;
use Illuminate\Support\Facades\Auth;

class ModeratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index(): \Illuminate\Http\JSONResponse
    {
        if (Auth::user()->cannot('viewAny', Moderator::class)) {
            abort(403);
        }
        return ApiResponse::success(Moderator::allWithUser());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $userRequest)
    {
        if ($userRequest->user()->cannot('create', Moderator::class)) {
            abort(403);
        }

        $userRequest->validated();
        $moderator = Moderator::createModerator($userRequest->all());
        $user = Moderator::findWithUser($moderator->moderator_id);

        $message = 'Moderador creado correctamente';
        return ApiResponse::success($user, $message, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $moderator_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($moderator_id): \Illuminate\Http\JSONResponse
    {
        // Find resource
        $moderator = Moderator::find($moderator_id);

        // Permission validation
        if (Auth::user()->cannot('view', $moderator)) {
            abort(403);
        }

        // Api response
        return ApiResponse::success(Moderator::findWithUser($moderator_id));
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateUserRequest $userRequest
     * @param  integer $moderator_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $userRequest, $moderator_id)
    {
        // Find resource
        $moderator = $this->findOrFail($moderator_id);

        // Permission validation
        if (Auth::user()->cannot('update', $moderator)) {
            abort(403);
        }

        // Resource update
        $userRequest->validated();
        $user = $moderator->user;
        $user->name = $userRequest->input('name');
        $user->save();

        $userToReturn = Moderator::findWithUser($moderator_id);

        // Api response
        $message = 'Información actualizada correctamente.';
        return ApiResponse::success($userToReturn, $message, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $moderator_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($moderator_id)
    {
        // Find resource
        $moderator = $this->findOrFail($moderator_id);

        // Find resource or fail
        if (Auth::user()->cannot('delete', $moderator)) {
            abort(403);
        }

        // Deletes resource
        $user = $moderator->user;
        $moderator->delete();
        $user->delete();

        // Api response
        $message = 'El moderador ha sido eliminado correctamente.';
        return ApiResponse::success(null, $message);
    }

    /**
     * Find resource or throw exception.
     *
     * @param  integer $moderator_id
     * @return mixed
     */
    private function findOrFail($moderator_id): mixed
    {
        try {
            return Moderator::findOrFail($moderator_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $message = 'Recurso no encontrado.';
            return ApiResponse::warning(null, $message, 404);
        }
    }
}
