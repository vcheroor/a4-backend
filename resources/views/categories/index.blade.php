@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Categories</h2>
    <a href="/admin/categories/create" class="btn btn-primary mb-3">Create Category</a>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Content</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->content }}</td>
                <td>
                    <a href="/admin/categories/edit/{{ $category->id }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="/admin/categories/delete/{{ $category->id }}" class="btn btn-sm btn-danger">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
