<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RegisterClientRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Client;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
use Validator;

class RegisterController extends BaseController
{
    /**
     * Register API
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:200',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|string|min:8|max:200',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser una cadena de caracteres.',
                'name.max' => 'El nombre no debe exceder los 200 caracteres.',
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe ser una dirección válida.',
                'email.unique' => 'El correo electrónico ya está en uso.',
                'password.required' => 'La contraseña es obligatoria.',
                'password.string' => 'La contraseña debe ser una cadena de caracteres.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.max' => 'La contraseña no debe exceder los 200 caracteres.',
            ]
        );

        if ($validator->fails()) {
            return $this->sendError('Parece que hay inconvenientes con los datos que ingresaste.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $client = Client::createClient($input);

        $user = $client->user;
        $user->sendEmailVerificationNotification();

        $data = $this->generateUserData($user);
        return ApiResponse::success($data, 'Usuario registrado con éxito.');
    }

    /**
     * Login API
     *
     * @return \Illuminate\Http\JSONResponse
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::attempt(['email' => $email, 'password' => $password])) {

            $data = $this->generateUserData(Auth::user());
            return ApiResponse::success($data, 'Usuario loggeado con éxito.');
        } else {
            return ApiResponse::warning(null, 'Ingresaste un email y/o contraseña incorrecto/s.');
        }
    }

    private function generateUserData($user)
    {
        $data['token'] = $user->createToken('Propiedades Olavarría')->plainTextToken;
        $data['user']['information'] = $user;
        $data['user']['roles'] = $user->roles->pluck('name')->flatten();
        $data['user']['web_routes'] = $user->getWebRoutes();
        unset($user->email_verified_at, $user->roles);
        return $data;
    }
}
