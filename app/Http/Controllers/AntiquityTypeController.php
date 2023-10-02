<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAntiquityTypeRequest;
use App\Http\Requests\UpdateAntiquityTypeRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Property;
use App\Models\AntiquityType;
use Illuminate\Support\Facades\Auth;

class AntiquityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(AntiquityType::all()->select('antiquity_type_id', 'name'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAntiquityTypeRequest $request)
    {
        // Authorization
        if ($request->user()->cannot('create', AntiquityType::class)) {
            abort(403);
        }

        // Data validation
        $request->validated();

        // Data store
        $antiquityType = AntiquityType::create(($request->all()));

        // Api response
        $message = 'Tipo de antigüedad creado correctamente.';
        return ApiResponse::success($antiquityType, $message, 201);
    }

    /**
     * Display the specified antiquityType.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($antiquity_type_id)
    {
        // Permission validation
        if (Auth::user()->cannot('view', AntiquityType::class)) {
            abort(403);
        }

        // Find resource
        $antiquityType = $this->findOrFail($antiquity_type_id);

        // Api response
        return ApiResponse::success($antiquityType);
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateUserRequest $userRequest
     * @param  integer $antiquity_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAntiquityTypeRequest $antiquityTypeRequest, $antiquity_type_id)
    {
        // Find resource
        $antiquityType = $this->findOrFail($antiquity_type_id);

        // Authorization
        if (Auth::user()->cannot('update', $antiquityType)) {
            abort(403);
        }

        // Data validation
        if ($antiquityTypeRequest->validated()) {
            // Update resource
            $antiquityType = $this->findOrFail($antiquity_type_id);
            $antiquityType->name = $antiquityTypeRequest->input('name');
            $antiquityType->save();
        }

        // Api response
        $message = 'Tipo de antigüedad actualizado correctamente.';
        return ApiResponse::success($antiquityType, $message, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $antiquity_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($antiquity_type_id)
    {
        // Find resource
        $antiquityType = $this->findOrFail($antiquity_type_id);

        // Authorization
        if (Auth::user()->cannot('forceDelete', $antiquityType)) {
            abort(403);
        }

        // Check if it is unused and delete resource
        $properties = Property::where('antiquity_type_id', '=', $antiquity_type_id)->get();
        if (count($properties) == 0) {
            $antiquityType->delete();
            $message = 'El tipo de antigüedad ha sido eliminado correctamente.';
        } else {
            $message = 'El tipo de antigüedad no puede eliminarse porque esta siendo usado por propiedades publicadas.';
        }

        return ApiResponse::success(null, $message, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $antiquity_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($antiquity_type_id)
    {
        // Find resource
        $antiquityType = AntiquityType::withTrashed()->find($antiquity_type_id);

        // Authorization
        if (Auth::user()->cannot('restore', $antiquityType)) {
            abort(403);
        }

        // Check if it is trashed and restore
        if ($antiquityType->trashed()) {
            $antiquityType->restore();
            $message = 'El tipo de antigüedad ha sido restablecido correctamente.';
        } else {
            abort(403);
        }

        // Api response
        return ApiResponse::success(null, $message, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $antiquity_type_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($antiquity_type_id)
    {
        // Find resource
        $antiquityType = AntiquityType::withTrashed()->find($antiquity_type_id);

        // Authorization
        if (Auth::user()->cannot('forceDelete', $antiquityType)) {
            abort(403);
        }

        // Check if it is trashed and delete permanently
        if ($antiquityType->trashed()) {
            $antiquityType->forceDelete();
            $message = 'El tipo de antigüedad ha sido eliminado permanentemente.';
        } else {
            abort(403);
        }

        // Api response
        return ApiResponse::success(null, $message, 200);
    }

    private function findOrFail($antiquity_type_id)
    {
        try {
            return AntiquityType::findOrFail($antiquity_type_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::warning(null, 'Recurso no encontrado', 404);
        }
    }
}
