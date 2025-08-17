<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    public function create() {
        return view('categories.create');
    }


    public function save(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:50',
            'content' => 'nullable'
        ]);

        Category::create($validated);
        return redirect('/admin/categories');
    }

    public function edit(Category $category) {
        return view('categories.edit', compact('category'));
    }

    public function delete(Category $category) {
        $category->delete();
        return redirect('/admin/categories');
    }
}
