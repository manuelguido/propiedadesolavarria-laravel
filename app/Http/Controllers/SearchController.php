<?php

namespace App\Http\Controllers;

use App\Http\Resources\BaseResource;
use App\Http\Responses\ApiResponse;
use App\Models\Post;
use App\Models\PropertyType;
use App\Models\RentalType;
use Illuminate\Http\Request;
use Validator;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function searchParameters(): \Illuminate\Http\JSONResponse
    {
        $data = [
            'rental_types' => RentalType::select('rental_type_id as id', 'name')->get(),
            'property_types' => PropertyType::select('property_type_id as id', 'name')->get(),
        ];

        return ApiResponse::success($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function searchPosts(Request $request): \Illuminate\Http\JSONResponse
    {
        $validator = Validator::make($request->all(), [
            'property_type_id' => 'required|numeric|min:1|exists:property_type,property_type_id',
            'rental_type_id' => 'required|numeric|min:1|exists:rental_type,rental_type_id',
        ]);

        if ($validator->fails()) {
            return ApiResponse::warning($validator->errors(), 'No ingreses valores no permitidos');
        }

        // Amount of pages
        $perPage = 10;

        // Query
        $posts = Post::with('property')
            ->join('property', 'property.property_id', '=', 'post.property_id')
            ->join('rental_type', 'rental_type.rental_type_id', '=', 'post.rental_type_id')
            ->join('currency as v_currency', 'v_currency.currency_id', '=', 'post.value_currency_id')
            ->join('currency as e_currency', 'e_currency.currency_id', '=', 'post.expenses_currency_id')
            ->join('property_type', 'property_type.property_type_id', '=', 'property.property_type_id')
            ->join('neighborhood', 'neighborhood.neighborhood_id', '=', 'property.neighborhood_id')
            ->join('renter', 'renter.renter_id', '=', 'post.renter_id')
            ->select(
                'e_currency.short_name as expenses_currency',
                'neighborhood.name as neighborhood',
                'post.post_id',
                'post.title',
                'post.value',
                'post.expenses',
                'property.property_id', // Sin esto ::with('property') no funciona
                'property_type.name as property_type',
                'rental_type.name as rental_type',
                'renter.phone as company_phone',
                'renter.whatsapp_phone as company_whatsapp_phone',
                'renter.commercial_email as company_commercial_email',
                'renter.renter_id',
                'v_currency.short_name as value_currency',
                \DB::raw("CONCAT('storage/images/renters/', renter.image) AS company_image"),
            )
            ->where([
                ['property_type.property_type_id', '=', $request->property_type_id],
                ['rental_type.rental_type_id', '=', $request->rental_type_id]
            ]);

        // Get filters
        if ($posts->count() > 0) {
            $filters = [
                'rental_type' => $posts->first()->rental_type,
                'property_type' => $posts->first()->property_type
            ];
        } else {
            $filters = [
                'rental_type' => RentalType::find($request->rental_type_id),
                'property_type' => PropertyType::find($request->property_type_id),
            ];
        }

        // Paginar los resultados de la consulta
        $postsPaginated = $posts->paginate($perPage);

        // Transformar cada post en la colección de resultados
        $postsPaginated->getCollection()->transform(function ($post) {

            // Add images
            foreach ($post->property->property_images as $image) {
                $finalImages[] = [
                    'name' => 'storage/images/properties/' . $post->property_id . '/' . $image->name,
                    'order' => $image->order,
                ];
            }
            $post->images = $finalImages;


            // Set Company
            $post->company = [
                'email' => $post->company_commercial_email,
                'image' => $post->company_image,
                'phone' => $post->company_phone,
                'renter_id' => $post->renter_id,
                'whatsapp_phone' => $post->company_whatsapp_phone,
            ];
            // Set price
            $post->price = [
                'value' => $post->value,
                'currency' => $post->value_currency
            ];
            // Set expenses
            $post->expenses = [
                'value' => $post->expenses,
                'currency' => $post->expenses_currency
            ];
            // Set amenities
            $post->amenities = [
                'bathrooms' => $post->property->bathrooms,
                'bedrooms' => $post->property->bedrooms,
                'covered_surface' => $post->property->covered_surface . ' ' . $post->property->surface_measurement_type,
                'enviroments' => $post->property->enviroments,
                'garages' => $post->property->garages,
                'total_surface' => $post->property->total_surface . ' ' . $post->property->surface_measurement_type,
            ];

            unset(
                $post->value,
                $post->renter_id,
                $post->company_phone,
                $post->company_image,
                $post->value_currency,
                $post->expenses_currency,
                $post->company_whatsapp_phone,
                $post->company_commercial_email,
                $post->property_id,
                $post->property,
                );

            return $post;
        });

        $customCollection = collect(['filters' => $filters]);

        $responseData = $customCollection->merge($postsPaginated);

        return ApiResponse::success($responseData);
    }
}
