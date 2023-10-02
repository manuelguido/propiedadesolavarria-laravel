<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     * @param  \App\Http\Requests\UpdateUserRequest $userRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $userRequest)
    {
        $user = Auth::user();

        // Resource update
        $userRequest->validated();
        $user->name = $userRequest->input('name');
        $user->save();

        // Api response
        $message = 'Perfil actualizado correctamente.';
        return ApiResponse::success($user, $message, 201);
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JSONResponse
     */
    public function passwordUpdate(Request $request)
    {
        $user = $request->user();
        $current_password = $request->input('current_password');
        $new_password = $request->input('password');

        // Verificar si la contraseña actual es correcta
        if (!Hash::check($current_password, $user->getHashedPassword())) {
            return response()->json(['error' => 'La contraseña actual es incorrecta.'], 422);
        }

        // Validar las nuevas contraseñas
        $request->validate([
            'current_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Actualizar la contraseña del usuario
        $user->password = Hash::make($new_password);
        $user->save();

        return response()->json(['message' => 'Contraseña actualizada correctamente.'], 201);
    }
}
