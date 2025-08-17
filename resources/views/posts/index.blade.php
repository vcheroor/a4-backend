@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Posts</h2>
    <a href="/admin/posts/create" class="btn btn-primary mb-3">Create Post</a>

    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>Active?</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
                <td>{{ $post->category->name }}</td>
                <td>{{ $post->user->name }}</td>
                <td>{{ $post->is_active }}</td>
                <td>
                    <a href="/admin/posts/edit/{{ $post->id }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="/admin/posts/delete/{{ $post->id }}" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
