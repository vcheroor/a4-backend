@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Category</h2>

    <form action="/admin/categories/save" method="POST">
        @csrf

        <input type="hidden" name="id" value="{{ $category->id }}">

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Content</label>
            <textarea name="content" class="form-control" rows="4">{{ $category->content }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
