<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::with(['category', 'user'])
            ->orderByDesc('id')
            ->get();
    }

   
    public function show(Post $post)
    {
        $post->load(['category', 'user']);
        return $post;
    }

   
    protected function normalizeIsActive($value): string
    {
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }
        $v = strtolower((string) $value);
        if (in_array($v, ['1', 'true', 'yes'], true)) {
            return 'Yes';
        }
        if (in_array($v, ['0', 'false', 'no'], true)) {
            return 'No';
        }
        return 'Yes';
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'is_active'   => 'sometimes', 
        ]);

        $data['is_active'] = array_key_exists('is_active', $data)
            ? $this->normalizeIsActive($data['is_active'])
            : 'Yes';

       
        $post = $request->user()->posts()->create($data);

        return response()->json($post->load(['category', 'user']), 201);
    }


    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'category_id' => 'nullable|integer|exists:categories,id',
            'is_active'   => 'sometimes',
        ]);

        if (array_key_exists('is_active', $data)) {
            $data['is_active'] = $this->normalizeIsActive($data['is_active']);
        }

        $post->update($data);

        return response()->json($post->fresh()->load(['category', 'user']));
    }

    public function destroy(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $post->delete();

        return response()->noContent(); 
    }
}
