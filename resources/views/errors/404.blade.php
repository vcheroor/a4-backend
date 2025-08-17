@extends('layouts.app')

@section('title', '404 Not Found')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-4 text-danger">404 - Page Not Found</h1>
    <p class="lead">Oops! The page you're looking for doesn't exist or has been moved.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">Go Back to Home</a>
</div>
@endsection
