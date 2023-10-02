<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index(): \Illuminate\Http\JSONResponse
    {
        if (Auth::user()->cannot('viewAny', Staff::class)) {
            abort(403);
        }
        return ApiResponse::success(Staff::allWithUser());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $userRequest)
    {
        if ($userRequest->user()->cannot('create', Staff::class)) {
            abort(403);
        }

        $userRequest->validated();
        $staff = Staff::createStaff($userRequest->all());
        $user = Staff::findWithUser($staff->staff_id);

        $message = 'Staff creado correctamente';
        return ApiResponse::success($user, $message, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $staff_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($staff_id): \Illuminate\Http\JSONResponse
    {
        // Find resource
        $staff = Staff::find($staff_id);

        // Permission validation
        if (Auth::user()->cannot('view', $staff)) {
            abort(403);
        }

        // Api response
        return ApiResponse::success(Staff::findWithUser($staff_id));
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateUserRequest $userRequest
     * @param  integer $staff_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $userRequest, $staff_id)
    {
        // Find resource
        $staff = $this->findOrFail($staff_id);

        // Permission validation
        if (Auth::user()->cannot('update', $staff)) {
            abort(403);
        }

        // Resource update
        $userRequest->validated();
        $user = $staff->user;
        $user->name = $userRequest->input('name');
        $user->save();

        $userToReturn = Staff::findWithUser($staff_id);

        // Api response
        $message = 'Información actualizada correctamente.';
        return ApiResponse::success($userToReturn, $message, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $staff_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($staff_id)
    {
        // Find resource
        $staff = $this->findOrFail($staff_id);

        // Find resource or fail
        if (Auth::user()->cannot('delete', $staff)) {
            abort(403);
        }

        // Deletes resource
        $user = $staff->user;
        $staff->delete();
        $user->delete();

        // Api response
        $message = 'El staff ha sido eliminado correctamente.';
        return ApiResponse::success(null, $message);
    }

    /**
     * Find resource or throw exception.
     *
     * @param  integer $staff_id
     * @return mixed
     */
    private function findOrFail($staff_id): mixed
    {
        try {
            return Staff::findOrFail($staff_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $message = 'Recurso no encontrado.';
            return ApiResponse::warning(null, $message, 404);
        }
    }
}
