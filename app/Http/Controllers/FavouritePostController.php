<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFavouritePostRequest;
// use App\Http\Requests\UpdateFavouritePostRequest;
use App\Http\Responses\ApiResponse;
use App\Models\FavouritePost;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class FavouritePostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function index()
    {
        if (!Auth::user()->hasRole(Role::RoleClient)) {
            abort(403);
        }

        $data = Auth::user()->client->favourite_posts;
        return ApiResponse::success($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFavouritePostRequest  $request
     * @return \Illuminate\Http\JSONResponse
     */
    public function store(StoreFavouritePostRequest $request)
    {
        if (!Auth::user()->hasRole(Role::RoleClient)) {
            return ApiResponse::warning(null, null, 403);
        }

        if ($request->validated()) {

            $existing = FavouritePost::where([
                ['post_id', '=', $request->input('post_id')],
                ['client_id', '=', Auth::user()->client->client_id],
            ])->get();

            // Post has not been added yet to favourites.
            if (count($existing) == 0) {
                $dataToStore = [
                    'client_id' => Auth::user()->client->client_id,
                    'post_id' => $request->input('post_id'),
                    'collection_count' => 0,
                ];

                $favouritePost = FavouritePost::create($dataToStore);

                $message = 'Guardaste la publicación en favoritos.';
                return ApiResponse::success($favouritePost, $message, 201);
            } else {
                $message = 'Ya añadiste esta publicación a favoritos.';
                return ApiResponse::warning(null, $message, 401);
            }
        } else {
            return ApiResponse::warning(null, null, 404);
        }
    }

    /**
     * Display the specified FavouritePost.
     *
     * No lo utilizamos hasta ahora porque no vemos diferencia entre utilizar
     * show de post.
     *
     * @param integer  $favourite_post_id
     * @return \Illuminate\Http\JsonResponse
     */
    // public function show($favourite_post_id)
    // {
    //     $favouritePost = $this->findOrFail($favourite_post_id);

    //     if (Auth::user()->cannot('view', $favouritePost)) {
    //         abort(403);
    //     }

    //     return ApiResponse::success($favouritePost);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $favourite_post_id
     * @return \Illuminate\Http\JSONResponse
     */
    public function forceDelete($favourite_post_id)
    {
        $favouritePost = $this->findOrFail($favourite_post_id);

        if (Auth::user()->cannot('forceDelete', $favouritePost)) {
            abort(403);
        }

        $favouritePost->forceDelete();
        return ApiResponse::success(null, 'Eliminaste la publicación de favoritos.', 200);
    }

    /**
     * Try to find a resource or fail with 404 error.
     *
     * @param  integer $favourite_post_id
     */
    private function findOrFail($favourite_post_id)
    {
        try {
            return FavouritePost::where([
                ['favourite_post_id', '=', $favourite_post_id],
                ['client_id', '=', Auth::user()->client->client_id],
            ])->first();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::warning(null, 'Recurso no encontrado', 404);
        }
    }
}
