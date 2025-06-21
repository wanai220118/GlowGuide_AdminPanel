<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GlowGuide</title>
    <link rel="icon" type="image/png" href="{{ asset('images/FI3.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('{{ asset('images/bg-soft-purple.png') }}');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .button {
            border: 2px solid;
            padding: 8px 20px;
            font-size: 15px;
            border-radius: 6px;
            transition: 0.3s ease;
        }

        .button1 { border-color: #7304aa; color: #7304aa; }
        .button1:hover { background-color: #7304aa; color: white; }

        .button2 { border-color: #d552b9; color: #d552b9; }
        .button2:hover { background-color: #d552b9; color: white; }

        .button3 { border-color: #821e46; color: #821e46; }
        .button3:hover { background-color: #821e46; color: white; }

        nav, footer {
            background-color: #f3e8ff;
        }

        footer {
            padding: 12px;
            text-align: center;
            font-size: 14px;
            color: #4a5568;
            margin-top: auto;
        }

        .nav-container {
            max-width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: nowrap;
        }

        .logo-container img {
            max-height: 80px;
            width: auto;
            display: block;
        }

        h1 {
            color: #4a5568;
            font-size: 300%;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            padding: 20px;
        }

        p {
            color: #4a5568;
            font-size: 200%;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            padding: 20px;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="shadow px-6 py-4 bg-[#f3e8ff]">
    <div class="nav-container">
        <!-- Left: Logo -->
        <div class="flex items-center space-x-3 logo-container">
            <img src="{{ asset('images/LD.png') }}" alt="Logo">
            <span class="text-xl font-semibold text-gray-800 whitespace-nowrap"></span>
        </div>

        <!-- Right: Buttons -->
        <div class="flex items-center space-x-4">
            <a href="{{ url('/') }}" class="button button1">Home</a>
            <a href="{{ route('filament.admin.auth.login') }}" class="button button2">Login</a>
            <a href="{{ url('/admin/register') }}" class="button button3">Signup</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<main class="px-6 py-20 text-center">
    <h1 class="mb-4 text-4xl font-bold text-black md:text-5xl">
        Welcome to the GlowGuide Admin Panel
    </h1>
    <p class="text-xl text-gray-800">
        Manage consultations, products, and clients with confidence.
    </p>
</main>

<!-- Footer -->
<footer>
    © {{ date('Y') }} GlowGuide. All rights reserved.
</footer>

</body>
</html>
