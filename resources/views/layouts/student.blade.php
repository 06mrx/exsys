<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .neo-bg {
            background: linear-gradient(145deg, #e6e6e6, #ffffff);
        }
        .neo-card {
            background: linear-gradient(145deg, #e6e6e6, #ffffff);
            box-shadow: 5px 5px 15px #d1d1d1, -5px -5px 15px #ffffff;
            border-radius: 10px;
        }
        .neo-button {
            background: linear-gradient(145deg, #e6e6e6, #ffffff);
            box-shadow: 3px 3px 8px #d1d1d1, -3px -3px 8px #ffffff;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .neo-button:hover {
            background: linear-gradient(145deg, #d1d1d1, #f2f2f2);
            box-shadow: inset 3px 3px 8px #d1d1d1, inset -3px -3px 8px #ffffff;
        }
        .primary-button {
            background: linear-gradient(145deg, #4a90e2, #63a4ff);
            color: white;
            box-shadow: 3px 3px 8px #d1d1d1, -3px -3px 8px #ffffff;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }
        .primary-button:hover {
            background: linear-gradient(145deg, #3a80d2, #5394ef);
            box-shadow: inset 3px 3px 8px #d1d1d1, inset -3px -3px 8px #ffffff;
        }
        .secondary-button {
            background: linear-gradient(145deg, #e6e6e6, #ffffff);
            color: #4a4a4a;
            box-shadow: 3px 3px 8px #d1d1d1, -3px -3px 8px #ffffff;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }
        .secondary-button:hover {
            background: linear-gradient(145deg, #d1d1d1, #f2f2f2);
            box-shadow: inset 3px 3px 8px #d1d1d1, inset -3px -3px 8px #ffffff;
        }
        .border-neo-light {
            border-color: #e0e0e0;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-neo-bg shadow-neo p-4 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
                <span class="text-gray-600">(Siswa)</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="neo-button flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </button>
            </form>
        </header>

        <!-- Content -->
        <main class="p-6">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg neo-card">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg neo-card">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>