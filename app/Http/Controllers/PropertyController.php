<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Http\Resources\BaseResource;
use App\Http\Responses\ApiResponse;
use App\Models\Property;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;


class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index()
    {
        // User
        $user = Auth::user();

        // Authorization
        if ($user->cannot('viewAny', Property::class)) {
            return ApiResponse::warning(null, 'No tienes permiso para realizar esta acción', 403);
        }

        // Property corresponding selection
        if ($user->hasRole(Role::RoleRenter)) {
            $properties = Property::where('renter_id', '=', $user->renter->renter_id)
                ->orderBy('created_at')
                ->paginate(10);

        } else {
            $properties = Property::orderBy('created_at')->paginate(10);
        }

        // Data formating
        $resource = new BaseResource($properties);
        $data = $resource->collectionToJson($resource);

        // Api resonse
        return ApiResponse::success($properties, null, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePropertyRequest  $request
     * @return \Illuminate\Http\JSONResponse
     */
    public function store(StorePropertyRequest $request)
    {
        // Authorization
        if ($request->user()->cannot('create', Property::class)) {
            return ApiResponse::warning(null, null, 403);
        }

        // Validate data
        $request->validated();

        // Store data
        $dataToStore = $request->all();
        $dataToStore['renter_id'] = Auth::user()->renter->renter_id;

        // Store property
        $property = Property::createProperty($dataToStore);

        // Data
        if ($property == null) {
            ApiResponse::warning(null, 'error', 404);
        }

        $data = $property->toArray();
        $data['images'] = $property->property_images;

        // Api response
        return ApiResponse::success($data, 'Propiedad creada con éxito', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $property_id
     * @return \Illuminate\Http\JSONResponse
     */
    public function show($property_id)
    {
        // Property find
        $property = Property::find($property_id);

        // Authorization
        if (Auth::user()->cannot('view', $property)) {
            return ApiResponse::warning(null, null, 403);
        }

        // Api response
        return ApiResponse::success($property);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePropertyRequest  $request
     * @param  integer  $property_id
     * @return \Illuminate\Http\JSONResponse
     */
    public function update(UpdatePropertyRequest $request, $property_id)
    {
        // Find resource
        $property = Property::find($property_id);

        // Authorization
        if ($request->user()->cannot('update', $property)) {
            return ApiResponse::warning(null, null, 403);
        } else {
            // Validate data
            $request->validated();

            // Store data
            $dataToStore = $request->all();
            $dataToStore['renter_id'] = Auth::user()->renter->renter_id;
            $result = $property->updateProperty($dataToStore);
            if ($result == null) {
                ApiResponse::warning(null, 'error', 404);
            }

            $freshProperty = $property->fresh();
            $returnData = $freshProperty->toArray();
            $returnData['property_images'] = $freshProperty->property_images;

            // Api response
            return ApiResponse::success($returnData, 'Propiedad actualizada con éxito', 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $property_id
     * @return \Illuminate\Http\JSONResponse
     */
    public function delete($property_id)
    {
        // Find resource
        $property = $this->findOrFail($property_id);

        // Find resource or fail
        if (Auth::user()->cannot('delete', $property)) {
            abort(403);
        }

        // Deletes resource
        $property->delete();

        // Api response
        $message = 'La propiedad ha sido eliminada correctamente';
        return ApiResponse::success(null, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $property_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore($property_id)
    {
        // Find resource
        $property = Property::withTrashed()->find($property_id);

        // Authorization
        if (Auth::user()->cannot('restore', $property)) {
            return ApiResponse::success(null, null, 403);
        }

        // Check if it is trashed and restore
        if ($property->trashed()) {
            $property->restore();
            $message = 'La propiedad ha sido restablecida correctamente';
        } else {
            return ApiResponse::success(null, null, 403);
        }

        // Api response
        return ApiResponse::success(null, $message, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $property_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($property_id)
    {
        // Find resource
        $property = Property::withTrashed()->find($property_id);

        // Authorization
        if (Auth::user()->cannot('forceDelete', $property)) {
            return ApiResponse::warning(null, null, 403);
        }

        // Check if it is trashed and delete permanently
        if ($property->trashed()) {
            $result = $property->forceDelete();
            if ($result)
                $message = 'La propiedad ha sido eliminado permanentemente.';
            else
                return ApiResponse::warning(null, null, 403);
        } else {
            return ApiResponse::warning(null, null, 403);
        }

        // Api response
        return ApiResponse::success(null, $message, 200);
    }

    /**
     * Find resource or throw exception.
     *
     * @param  integer $property_id
     * @return mixed
     */
    private function findOrFail($property_id): mixed
    {
        try {
            return Property::findOrFail($property_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::warning(null, 'Recurso no encontrado.', 404);
        }
    }
}
