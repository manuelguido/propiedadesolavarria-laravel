<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data = null, $message = 'Successful Request', $status = 200)
    {
        return new JsonResponse([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function warning($data = null, $message = null, $status = 400)
    {
        if ($message == null and $status == 403) {
            $message = 'No tienes permiso para realizar esta acción.';
        }
        return new JsonResponse([
            'status' => 'warning',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error($data = null, $message = null, $status = 500)
    {
        return new JsonResponse([
            'status' => 'error',
            'message' => $message,
            'data' => $data
        ], $status);
    }
}
