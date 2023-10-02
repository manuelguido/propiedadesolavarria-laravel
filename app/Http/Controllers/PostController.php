<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\BaseResource;
use App\Http\Responses\ApiResponse;
use App\Models\Post;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index(Request $request)
    {
        // User
        $user = $request->user();

        // Authorization
        if ($user->cannot('viewAny', Post::class)) {
            return ApiResponse::warning(null, 'No tienes permiso para realizar esta acción', 403);
        }

        // Post corresponding selection
        $posts = ($user->hasRole(Role::RoleRenter))
            ? Post::where('renter_id', '=', $user->renter->renter_id)->orderBy('created_at')->paginate(10)
            : Post::orderBy('created_at')->paginate(10);

        // Data formating
        $resource = new BaseResource($posts);
        $data = $resource->collectionToJson($resource);

        // Api resonse
        return ApiResponse::success($data, null, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\JSONResponse
     */
    public function store(StorePostRequest $request)
    {
        // Authorization
        if ($request->user()->cannot('create', Post::class)) {
            return ApiResponse::warning(null, null, 403);
        }

        // Validate data
        $request->validated();

        // Verify there is not already existent post with same rental_type
        $propertyId = $request->input('property_id');
        $rentalTypeId = $request->input('rental_type_id');

        $existentPosts = Post::where([['property_id', $propertyId], ['rental_type_id', $rentalTypeId]])->exists();
        if ($existentPosts) {
            return ApiResponse::warning(null, null, 401);
        }

        // Store data
        $dataToStore = $request->all();
        $dataToStore['renter_id'] = Auth::user()->renter->renter_id;
        $post = Post::create($dataToStore);

        // Api response
        return ApiResponse::success($post, 'Propiedad creada con éxito', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $post_id
     * @return \Illuminate\Http\JSONResponse
     */
    public function show($post_id)
    {
        // Query
        $post = Post::join('property', 'property.property_id', '=', 'post.property_id')
            ->join('renter', 'renter.renter_id', '=', 'post.renter_id')
            ->join('rental_type', 'rental_type.rental_type_id', '=', 'post.rental_type_id')
            ->join('currency as v_currency', 'v_currency.currency_id', '=', 'post.value_currency_id')
            ->join('currency as e_currency', 'e_currency.currency_id', '=', 'post.expenses_currency_id')
            ->join('surface_measurement_type', 'surface_measurement_type.surface_measurement_type_id', '=', 'property.surface_measurement_type_id')
            ->join('property_type', 'property_type.property_type_id', '=', 'property.property_type_id')
            ->join('antiquity_type', 'antiquity_type.antiquity_type_id', '=', 'property.antiquity_type_id')
            ->join('neighborhood', 'neighborhood.neighborhood_id', '=', 'property.neighborhood_id')
            ->select(
                'antiquity_type.name as antiquity_type',
                'e_currency.short_name as expenses_currency',
                'neighborhood.name as neighborhood',
                'post.post_id',
                'post.title',
                'post.value',
                'post.expenses',
                'property.enviroments',
                'property.bathrooms',
                'property.bedrooms',
                'property.garages',
                'property.total_surface',
                'property.covered_surface',
                'property_type.name as property_type',
                'rental_type.name as rental_type',
                'renter.address',
                'renter.commercial_email',
                'renter.phone',
                'renter.whatsapp_phone',
                'surface_measurement_type.short_name as surface_measurement_type',
                'v_currency.short_name as value_currency',
            )->find($post_id)->toArray();

        if ($post) {
            // Add images
            $auxPost = Post::with('property.property_images')->find($post_id);
            $renter = $auxPost->renter;
            $images = $auxPost->property->property_images;

            $finalImages = [];
            foreach ($images as $image) {
                $finalImages[] = [
                    'name' => 'storage/images/properties/' . $auxPost->property_id . '/' . $image->name,
                    'order' => $image->order,
                ];
            }

            $post['images'] = $finalImages;

            // Resets some data
            $post['price'] = ['value' => $post['value'], 'currency' => $post['value_currency']];
            $post['expenses'] = ['value' => $post['expenses'], 'currency' => $post['expenses_currency']];
            $post['amenities'] = [
                'bathrooms' => $post['bathrooms'],
                'bedrooms' => $post['bedrooms'],
                'covered_surface' => $post['covered_surface'] . ' ' . $post['surface_measurement_type'],
                'enviroments' => $post['enviroments'],
                'garages' => $post['garages'],
                'total_surface' => $post['total_surface'] . ' ' . $post['surface_measurement_type'],
            ];

            // Adds company data
            $post['company'] = [
                'address' => $post['address'],
                'email' => $post['commercial_email'],
                'image' => 'storage/images/renters/'.$renter->image,
                'estate_agent' => $renter['estate_agent'],
                'phone' => $post['phone'],
                'renter_id' => $renter['renter_id'],
                'whatsapp_phone' => $post['whatsapp_phone'],
            ];

            // Unset unnecesary data
            unset(
                $post['value'], $post['value_currency'], $post['expenses_currency'], $post['surface_measurement_type'], $post['bathrooms'],
                $post['bedrooms'], $post['covered_surface'], $post['enviroments'], $post['garages'], $post['total_surface'],
                $post['renter.address'], $post['renter.commercial_email'], $post['renter.phone'], $post['renter.whatsapp_phone'],
            );

            // Sort alphabetically
            ksort($post);

            // Response
            return ApiResponse::success($post);
        } else {
            return ApiResponse::warning(null, 'La publicación no existe');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  integer  $post_id
     * @return \Illuminate\Http\JSONResponse
     */
    public function update(UpdatePostRequest $request, $post_id)
    {
        // Find resource
        $post = Post::find($post_id);

        // Authorization
        if (Auth::user()->cannot('update', $post)) {
            return ApiResponse::warning(null, null, 401);
        }

        // Validate data
        $request->validated();

        // Store data
        $dataToStore = $request->all();
        $dataToStore['renter_id'] = Auth::user()->renter->renter_id;
        $post->update($dataToStore);
        $post->fresh();

        // Api response
        return ApiResponse::success($post, 'La publicación ha actualizada con éxito', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $post_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDelete($post_id)
    {
        // Find resource
        $post = Post::withTrashed()->find($post_id);

        // Authorization
        if (Auth::user()->cannot('forceDelete', $post)) {
            return ApiResponse::success(null, null, 403);
        }

        // Delete resource permanently
        $post->forceDelete();
        $message = 'La publicación ha sido eliminada permanentemente';

        // Api response
        return ApiResponse::success($post, $message, 200);
    }

    /**
     * Find resource or throw exception.
     *
     * @param  integer $post_id
     * @return mixed
     */
    private function findOrFail($post_id): mixed
    {
        try {
            return Post::findOrFail($post_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::warning(null, 'Recurso no encontrado', 404);
        }
    }

    /**
     * Display the month featured posts.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function featured()
    {
        $posts = Post::where('post.featured', '=', true)
            ->join('property', 'property.property_id', '=', 'post.property_id')
            ->join('rental_type', 'rental_type.rental_type_id', '=', 'post.rental_type_id')
            ->join('currency as v_currency', 'v_currency.currency_id', '=', 'post.value_currency_id')
            ->join('currency as e_currency', 'e_currency.currency_id', '=', 'post.expenses_currency_id')
            ->join('property_image', function ($join) {
                $join->on('property_image.property_id', '=', 'post.property_id')
                    ->where('property_image.order', '=', '1');
            })
            ->join('neighborhood', 'neighborhood.neighborhood_id', '=', 'property.neighborhood_id')
            ->join('property_type', 'property.property_type_id', '=', 'property_type.property_type_id')
            ->select(
                'post.post_id',
                'post.property_id',
                'post.title',
                'post.value',
                'post.expenses',
                'rental_type.name as rental_type',
                'v_currency.short_name as value_currency',
                'e_currency.short_name as expenses_currency',
                'neighborhood.name as neighborhood',
                \DB::raw("CONCAT('storage/images/properties/', post.property_id, '/', property_image.name) AS image")
            )
            ->get();

        $posts->each(function ($post) {
            $post['price'] = ['value' => $post['value'], 'currency' => $post['value_currency']];
            $post['expenses'] = ['value' => $post['expenses'], 'currency' => $post['expenses_currency']];
            unset(
                $post['value'], $post['value_currency'], $post['expenses_currency'], $post['property_id']
            );

        });

        // Api resonse
        return ApiResponse::success($posts, null, 200);
    }

    /**
     * Display related posts by post_id.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function relatedPosts($post_id)
    {
        // Query
        $post = Post::find($post_id);
        $responseData = [
            'related_posts' => $post->relatedPosts(),
            'related_company_posts' => $post->relatedRenterPosts(),
        ];

        // Api resonse
        return ApiResponse::success($responseData, null, 200);
    }
}
