<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Pastebin') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.23.0/themes/prism.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        header {
            margin-bottom: 20px;
        }
        header a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<header>
    <a href="{{ url('/') }}">Home</a>
    @if (Auth::check())
        <a href="{{ url('/dashboard') }}">My Pastes</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" style="background: none; border: none; color: blue; text-decoration: underline; cursor: pointer;">Logout</button>
        </form>
    @else
        <a href="{{ url('/login') }}">Login</a>
        <a href="{{ url('/register') }}">Register</a>
    @endif
</header>
<main>
    @yield('content')
</main>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.23.0/prism.min.js"></script>
</body>
</html>
