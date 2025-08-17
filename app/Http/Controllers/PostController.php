<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index() {
        $posts = Post::with('category')->get(); 
        return view('posts.index', compact('posts'));
    }

    public function create() {
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function save(Request $request) {
        $validated = $request->validate([
            'title' => 'required|max:50',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => Auth::id(), 
            'is_active' => 'Yes'
        ]);

        return redirect('/admin/posts');
    }

    
    public function edit(Post $post) {
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }


    public function delete(Post $post) {
        $post->delete();
        return redirect('/admin/posts');
    }
}