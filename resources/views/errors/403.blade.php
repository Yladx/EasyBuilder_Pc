@extends('layouts.error')

@section('title', 'Access Denied')

@section('styles')
<style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background: #f4f6f8;
        display: flex;
        justify-content: center;
        align-items: center;
        font-family: Arial, sans-serif;
    }
    .error-container {
        text-align: center;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        max-width: 500px;
        width: 90%;
    }
    h1 {
        color: #dc3545;
        margin-bottom: 1rem;
    }
    p {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
    .btn {
        background: #007bff;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn:hover {
        background: #0056b3;
    }
</style>
@endsection

@section('content')
<div class="error-container">
    <h1>403 - Access Denied</h1>
    <p>Sorry, you don't have permission to access this page.</p>
    <a href="{{ url('/') }}" class="btn">Return to Home</a>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        title: 'Access Denied',
        text: 'You do not have permission to access this page.',
        icon: 'error',
        confirmButtonText: 'OK'
    });
</script>
@endsection
