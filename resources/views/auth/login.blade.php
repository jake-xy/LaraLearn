@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-header">
    <h1>E-Learning System</h1>
    <p>Sign in to your account</p>
</div>

@if ($errors->any())
    <div class="auth-error">
        {{ $errors->first() }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form id="loginForm" action="{{ route('login') }}" method="POST" class="auth-form">
    @csrf
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
    </div>
    
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Enter your password">
        @error('password')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>
    
    <div class="form-options">
        <label class="checkbox-label">
            <input type="checkbox" name="remember">
            <span>Remember me</span>
        </label>
        <!-- Removed undefined password.request route link -->
    </div>
    
    <button type="submit" class="btn btn-primary btn-full">Sign In</button>
</form>

<div class="auth-footer">
    <p>Don't have an account? <a href="{{ route('register') }}" class="link">Register here</a></p>
</div>
@endsection
