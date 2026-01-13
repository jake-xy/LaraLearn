@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-header">
    <h1>Create Account</h1>
    <p>Register for E-Learning System</p>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form id="registerForm" action="{{ route('register') }}" method="POST" class="auth-form">
    @csrf
    <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your full name">
        @error('name')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>
    
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
        @error('email')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>
    
    <div class="form-group">
        <label for="role">Register As</label>
        <select id="role" name="role" required>
            <option value="">Select role</option>
            <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
        </select>
        @error('role')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>
    
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required placeholder="Create a password">
        @error('password')
            <span class="error-text">{{ $message }}</span>
        @enderror
    </div>
    
    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
    </div>
    
    <button type="submit" class="btn btn-primary btn-full">Create Account</button>
</form>

<div class="auth-footer">
    <p>Already have an account? <a href="{{ route('login') }}" class="link">Sign in</a></p>
</div>
@endsection
