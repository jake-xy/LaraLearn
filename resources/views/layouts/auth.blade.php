<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - E-Learning System</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <!--logo -->
                <img src="{{ asset('images/logo.png') }}" 
                alt="System Logo"
                class="auth-logo">
            @yield('content')
        </div>
    </div>
    
</body>
</html>
