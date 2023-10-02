<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your api!
|
*/

Route::prefix('/v1')->group(function () {

    require __DIR__ . '/api-v1/administrator.php';
    require __DIR__ . '/api-v1/antiquity-type.php';
    require __DIR__ . '/api-v1/authentication.php';
    require __DIR__ . '/api-v1/client.php';
    require __DIR__ . '/api-v1/currency.php';
    require __DIR__ . '/api-v1/favourite-post.php';
    require __DIR__ . '/api-v1/moderator.php';
    require __DIR__ . '/api-v1/post.php';
    require __DIR__ . '/api-v1/property-type.php';
    require __DIR__ . '/api-v1/property.php';
    require __DIR__ . '/api-v1/public.php';
    require __DIR__ . '/api-v1/rental-type.php';
    require __DIR__ . '/api-v1/renter.php';
    require __DIR__ . '/api-v1/search.php';
    require __DIR__ . '/api-v1/staff.php';
    require __DIR__ . '/api-v1/surface-measurement-type.php';
    require __DIR__ . '/api-v1/user.php';

});
