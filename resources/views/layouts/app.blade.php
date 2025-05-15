<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Finance App') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-neo-bg text-gray-800">
    <div class="flex">
        <div class="w-64">
            <!-- Sidebar -->
            <aside id="sidebar" class="neo-sidebar overflow-y-auto -translate-x-full md:translate-x-0 z-50">
                <div class="p-6">
                    <!-- Tombol Close (hanya di mobile) -->
                    <button id="sidebar-close"
                        class="neo-button absolute top-4 right-4 md:hidden flex items-center justify-center h-12 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"
                            width="32" height="32"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m10 15.4l-5.9 5.9q-.275.275-.7.275t-.7-.275t-.275-.7t.275-.7L8.6 14H5q-.425 0-.712-.288T4 13t.288-.712T5 12h6q.425 0 .713.288T12 13v6q0 .425-.288.713T11 20t-.712-.288T10 19zm5.4-5.4H19q.425 0 .713.288T20 11t-.288.713T19 12h-6q-.425 0-.712-.288T12 11V5q0-.425.288-.712T13 4t.713.288T14 5v3.6l5.9-5.9q.275-.275.7-.275t.7.275t.275.7t-.275.7z" />
                        </svg>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 mb-6">Menu</h2>
                    <nav class="space-y-4">
                        @auth
                            @if (auth()->user()->is_admin)
                                <a href="{{ route('dashboard') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7m-7 0v10m-7-7h14"></path>
                                    </svg>
                                    Dashboard
                                </a>
                                <h3 class="text-sm font-semibold text-gray-600 mb-6">Pengguna</h3>
                                <a href="{{ route('users.index') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'users.index' ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    Pengguna
                                </a>

                                <h3 class="text-sm font-semibold text-gray-600 mb-6">Manajemen Ujian</h3>
                                <a href="{{ route('questions.index') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'questions.index' ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Soal   
                                </a>
                                <a href="{{ route('exams.index') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'exams.index' ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ujian   
                                </a>
                                <a href="{{ route('exams.results') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'exams.results' ? 'active' : '' }}">
                                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Hasil   
                                </a>
                                <h3 class="text-sm font-semibold text-gray-600 mb-6">Manajemen Quiz</h3>
                                <a href="{{ route('quizzes.index') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'quizzes.index' ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Quiz
                                </a>
                                <a href="{{ route('quizzes.results') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'quizzes.results' ? 'active' : '' }}">
                                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Hasil
                                </a>

                                <h3 class="text-sm font-semibold text-gray-600 mb-6">Referensi</h3>
                                <a href="{{ route('institutions.index') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'institutions.index' ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    Instansi
                                </a>
                                
                                {{-- <a href="{{ route('admin.users.create') }}" class="block neo-button">Add Employee/HR</a> --}}
                            @elseif (!auth()->user()->is_admin)
                                <a href="{{ route('dashboard') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7m-7 0v10m-7-7h14"></path>
                                    </svg>
                                    Dashboard
                                </a>
                                <h3 class="text-sm font-semibold text-gray-600 mb-6">Manajemen Ujian</h3>
                                <a href="{{ route('exams-student') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'exams-student' ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ujian   
                                </a>
                                <a href="{{ route('quizzes.student') }}"
                                    class="block neo-button {{ Route::currentRouteName() == 'quizzes.student' ? 'active' : '' }}">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Quiz
                                </a>
                            @else
                                
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left neo-button">
                                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block neo-button {{ Route::currentRouteName() == 'login' ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="block neo-button {{ Route::currentRouteName() == 'register' ? 'active' : '' }}">
                                <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                    </path>
                                </svg>
                                Register
                            </a>
                        @endauth
                    </nav>
                </div>
            </aside>
        </div>
    </div>
    <div class="flex">
        <div class="hidden md:block md:w-64 md:ml-10"></div>
        <div class="min-h-screen flex flex-col w-full">
            <!-- Header -->
            <header class="neo-card py-4 px-6">
                <div class="container mx-auto flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <!-- Tombol Toggle Sidebar -->
                        <button id="sidebar-toggle" class="neo-button md:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                        </button>
                        <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800">
                            {{ config('app.name', 'Finance App') }}
                        </a>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-grow container mx-auto px-6 py-8 ">
                <div class="neo-card">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="neo-card py-4 px-6 text-center text-gray-600">
                <p>© {{ date('Y') }} {{ config('app.name', 'Finance App') }}. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <!-- Modal untuk Success/Error Message -->
    @if (session('success') || session('error') || $errors->any())
        <div id="message-modal" class="fixed inset-0  flex items-center justify-center z-50 bg-black bg-opacity-50">
            <div class="neo-card max-w-md w-full p-6 relative ">
                <!-- Tombol Close -->
                <button id="modal-close"
                    class="absolute top-4 right-4 neo-button h-10 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <!-- Pesan -->
                @if ($errors->any())
                    <h3 class="text-lg font-bold text-red-800 mb-4">Input Error</h3>
                    <ul class="text-gray-800 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @elseif (session('success'))
                    <h3 class="text-lg font-bold text-green-800 mb-4">Success</h3>
                    <p class="text-gray-800">{{ session('success') }}</p>
                @elseif (session('error'))
                    <h3 class="text-lg font-bold text-red-800 mb-4">Error</h3>
                    <p class="text-gray-800">{{ session('error') }}</p>
                @endif
            </div>
        </div>
    @endif

</body>

</html>