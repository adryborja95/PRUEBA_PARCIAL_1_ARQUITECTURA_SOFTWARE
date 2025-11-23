<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthController extends Controller
{
    /**
     * REGISTRO DE USUARIO (opcional)
     * Crea un usuario y genera un token automáticamente.
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate(
                [
                    'name'     => 'required|string|max:255',
                    'email'    => 'required|email|unique:users,email',
                    'password' => 'required|string|min:6',
                ],
                [
                    'name.required'     => 'El nombre es obligatorio.',
                    'name.string'       => 'El nombre debe ser una cadena de texto.',
                    'name.max'          => 'El nombre no puede tener más de 255 caracteres.',

                    'email.required'    => 'El correo electrónico es obligatorio.',
                    'email.email'       => 'El formato del correo electrónico no es válido.',
                    'email.unique'      => 'El correo electrónico ya está registrado.',

                    'password.required' => 'La contraseña es obligatoria.',
                    'password.string'   => 'La contraseña debe ser una cadena de texto.',
                    'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
                ]
            );

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => $validated['password'],
                'email_verified_at' => now(), 
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Usuario registrado correctamente.',
                'user'    => $user,
                'token'   => $token,
            ], 201);

        } catch (ValidationException $e) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Error en el registro. Verifique los datos ingresados.',
                    'errors'  => $e->errors(),
                ], 422)
            );
        }
    }

    /**
     * LOGIN DE USUARIO
     * Genera un token de acceso si las credenciales son válidas.
     * Endpoint: /api/login
     */
    public function login(Request $request)
    {
        $request->validate(
            [
                'email'    => 'required|email',
                'password' => 'required|string',
            ],
            [
                'email.required'    => 'Debe ingresar un correo electrónico.',
                'email.email'       => 'El formato del correo electrónico no es válido.',
                'password.required' => 'Debe ingresar una contraseña.',
                'password.string'   => 'La contraseña debe ser una cadena de texto.',
            ]
        );

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Las credenciales no son válidas. Verifique su correo y contraseña.',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'user'    => $user,
            'token'   => $token,
        ], 200);
    }

    /**
     * VALIDAR TOKEN
     * Endpoint que consumirá el microservicio de Posts.
     *
     * GET /api/validate-token
     */
    public function validateToken(Request $request)
    {
        $user = $request->user(); 

        return response()->json([
            'message' => 'Token Válido',
            'valid' => true,
            'user'  => $user,
        ], 200);
    }

    /**
     * LOGOUT
     * Elimina todos los tokens del usuario autenticado.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente. Todos los tokens fueron eliminados.',
        ], 200);
    }
}