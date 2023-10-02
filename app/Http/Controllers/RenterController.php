<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRenterRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateRenterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Post;
use App\Models\Renter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class RenterController extends Controller
{
    /**
     * Display a listing of Renters.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index(): \Illuminate\Http\JSONResponse
    {
        return ApiResponse::success(Renter::all());
    }

    /**
     * Store a new Renter.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $userRequest, StoreRenterRequest $renterRequest): \Illuminate\Http\JSONResponse
    {
        if (Auth::user()->cannot('create', Renter::class)) {
            return ApiResponse::warning(null, null, 403);
        } else {
            $userRequest->validated();
            $renterRequest->validated();
            $auxRenter = Renter::createRenter(($userRequest->all()));
            $renter = Renter::findWithUser($auxRenter->renter_id);

            $message = 'Inmobiliaria creada correctamente';
            return ApiResponse::success($renter, $message, 201);
        }
    }

    /**
     * Display the specified Renter.
     *
     * @param  integer $renter_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($renter_id): \Illuminate\Http\JSONResponse
    {
        // Query
        $renter = Renter::select(
            'address',
            'renter_id',
            'commercial_email as email',
            'image',
            \DB::raw("CONCAT('storage/images/renters/', renter.image) AS image"),
            'estate_agent',
            'phone',
            'whatsapp_phone'
        )->find($renter_id);
        if ($renter == null)
            return ApiResponse::warning(null, 'Parece que no existe el recurso solicitado', 404);

        $renterData = $renter->toArray();
        $renterData['posts'] = $renter->postsFormatted();

        return ApiResponse::success($renterData);
    }

    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateUserRequest $userRequest
     * @param  \App\Http\Requests\UpdateRenterRequest $renterRequest
     * @param  integer $renter_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $userRequest, UpdateRenterRequest $renterRequest, $renter_id): \Illuminate\Http\JSONResponse
    {
        if ($userRequest->validated()) {
            // Find models
            $renter = $this->findOrFail($renter_id);
            $user = $renter->user;

            // Update User model
            $user->name = $userRequest->input('name');
            $user->save();

            // Update Renter model
            $renter->phone = $userRequest->input('phone');
            $renter->whatsapp_phone = $userRequest->input('whatsapp_phone');
            $renter->address = $userRequest->input('address');
            if ($userRequest->input('commercial_email')) {
                $renter->commercial_email = $userRequest->input('commercial_email');
            }
            $renter->save();
        }

        $renter = Renter::findWithUser($renter_id);

        $message = 'Información actualizada correctamente.';
        return ApiResponse::success($renter, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Renter $renter
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($renter_id): \Illuminate\Http\JSONResponse
    {
        $renter = $this->findOrFail($renter_id);

        $this->deleteRenterProperties($renter);
        $this->deleteRenterPosts($renter);

        $user = $renter->user;
        $renter->delete();
        $user->delete();

        $message = 'La inmobiliaria ha sido eliminado correctamente.';
        $data = ['submessages' => 'Tienes 30 días a partir de ahora para restaurar esta cuenta. De lo contrario será eliminada para siempre.'];
        return ApiResponse::success($data, $message);
    }

    /**
     * Find resource or throw exception.
     *
     * @param  integer $renter_id
     * @return mixed
     */private function findOrFail($renter_id): mixed
    {
        try {
            return Renter::findOrFail($renter_id);
        } catch (ModelNotFoundException $e) {
            $message = 'Recurso no encontrado.';
            return ApiResponse::warning(null, $message, 404);
        }
    }

    /**
     * Delete all renter properties and posts.
     *
     * @param  \App\Models\Renter $renter
     * @return void
     */
    private function deleteRenterProperties($renter): void
    {
        foreach ($renter->properties as $property) {
            $property->delete();
            foreach ($property->property_images as $image) {
                $image->delete();
            }
        }
    }

    /**
     * Delete all renter properties and posts.
     *
     * @param  \App\Models\Renter $renter
     * @return void
     */
    private function deleteRenterPosts($renter): void
    {
        foreach ($renter->posts as $post) {
            $post->delete();
        }
    }

    /**
     * Display the month featured posts.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function featured()
    {
        $renters = Renter::inRandomOrder()
            ->select(
                'renter.renter_id',
                'renter.address',
                'renter.commercial_email',
                'renter.estate_agent',
                'renter.phone',
                'renter.whatsapp_phone',
                \DB::raw("CONCAT('storage/images/renters/', renter.image) AS image"),
            )
            ->limit(4)
            ->get();

        // Api resonse
        return ApiResponse::success($renters, null, 200);
    }
}
