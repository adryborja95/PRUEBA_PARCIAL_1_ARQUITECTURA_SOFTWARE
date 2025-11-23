<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * LISTAR TODOS LOS POSTS
     * GET /api/posts
     */
    public function index()
    {
        $posts = Post::all();

        return response()->json($posts, 200);
    }

    /**
     * CREAR UN NUEVO POST
     * POST /api/posts
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title'   => 'required|string|max:150',
                'content' => 'nullable|string',
            ],
            [
                'title.required' => 'El título es obligatorio.',
                'title.string'   => 'El título debe ser una cadena de texto.',
                'title.max'      => 'El título no puede superar los 150 caracteres.',
                'content.string' => 'El contenido debe ser una cadena de texto.',
            ]
        );

        // Usuario autenticado que viene del microservicio de autenticación
        $authUser = $request->get('auth_user');

        $post = Post::create([
            'title'   => $validated['title'],
            'content' => $validated['content'] ?? null,
            'user_id' => $authUser['id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Post creado correctamente.',
            'post'    => $post,
        ], 201);
    }

    /**
     * MOSTRAR UN POST POR ID
     * GET /api/posts/{id}
     */
    public function show(string $id)
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'message' => 'Post no encontrado.',
            ], 404);
        }

        return response()->json($post, 200);
    }

    /**
     * ACTUALIZAR UN POST
     * PUT /api/posts/{id}
     */
    public function update(Request $request, string $id)
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'message' => 'Post no encontrado.',
            ], 404);
        }

        $validated = $request->validate(
            [
                'title'   => 'sometimes|string|max:150',
                'content' => 'nullable|string',
            ],
            [
                'title.string'   => 'El título debe ser una cadena de texto.',
                'title.max'      => 'El título no puede superar los 150 caracteres.',
                'content.string' => 'El contenido debe ser una cadena de texto.',
            ]
        );

        $post->update($validated);

        return response()->json([
            'message' => 'Post actualizado correctamente.',
            'post'    => $post,
        ], 200);
    }

    /**
     * ELIMINAR UN POST
     * DELETE /api/posts/{id}
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (! $post) {
            return response()->json([
                'message' => 'Post no encontrado.',
            ], 404);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post eliminado correctamente.',
        ], 200);
    }
}