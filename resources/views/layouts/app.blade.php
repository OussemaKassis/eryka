<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Shop') }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-container">
            <a href="{{ url('/') }}" class="logo">Shop</a>
        </div>
    </nav>

    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>