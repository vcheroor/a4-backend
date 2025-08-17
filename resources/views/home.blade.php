@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Posts</h2>
        <a href="#" class="btn btn-primary">+ Create New Post</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Content</th>
                <th>Category</th>
                <th>Created At</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 1; $i <= 10; $i++)
                <tr>
                    <td>{{ $i }}</td>
                    <td>Sample Post Title {{ $i }}</td>
                    <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit. {{ $i }}</td>
                    <td>Lorem {{ $i }}</td>
                    <td>{{ now()->subDays(10 - $i)->format('d M Y') }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-warning">Edit</a>
                        <form action="#" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
@endsection
