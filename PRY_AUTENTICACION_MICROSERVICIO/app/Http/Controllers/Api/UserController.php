<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * LISTAR USUARIOS
     * GET /api/users
     */
    public function index()
    {
        $usuarios = User::all();

        return response()->json($usuarios, 200);
    }

    /**
     * CREAR USUARIO
     * POST /api/users
     */
    public function store(Request $request)
    {
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

        $usuario = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'usuario' => $usuario,
        ], 201);
    }

    /**
     * MOSTRAR UN USUARIO POR ID
     * GET /api/users/{id}
     */
    public function show($id)
    {
        $usuario = User::find($id);

        if (! $usuario) {
            return response()->json([
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        return response()->json($usuario, 200);
    }

    /**
     * ACTUALIZAR USUARIO
     * PUT /api/users/{id}
     */
    public function update(Request $request, $id)
    {
        $usuario = User::find($id);

        if (! $usuario) {
            return response()->json([
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        $validated = $request->validate(
            [
                'name'     => 'sometimes|string|max:255',
                'email'    => [
                    'sometimes',
                    'email',
                    Rule::unique('users', 'email')->ignore($usuario->id),
                ],
                'password' => 'sometimes|string|min:6',
            ],
            [
                'name.string'       => 'El nombre debe ser una cadena de texto.',
                'name.max'          => 'El nombre no puede tener más de 255 caracteres.',

                'email.email'       => 'El formato del correo electrónico no es válido.',
                'email.unique'      => 'El correo electrónico ya está registrado.',

                'password.string'   => 'La contraseña debe ser una cadena de texto.',
                'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
            ]
        );

   
        $usuario->update($validated);

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'usuario' => $usuario,
        ], 200);
    }

    /**
     * ELIMINAR USUARIO
     * DELETE /api/users/{id}
     */
    public function destroy($id)
    {
        $usuario = User::find($id);

        if (! $usuario) {
            return response()->json([
                'message' => 'Usuario no encontrado.',
            ], 404);
        }

        $usuario->delete();

        return response()->json([
            'message' => 'Usuario eliminado correctamente.',
        ], 200);
    }
}