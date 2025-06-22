<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Exsys Examination System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /* Tambahan custom styles jika diperlukan */
            .gradient-bg {
                background: linear-gradient(135deg, #f4f7fa 0%, #e0e7ff 100%);
            }
            .hover-lift:hover {
                transform: translateY(-0.5rem);
                transition: transform 0.3s ease;
            }
        </style>
    @endif
</head>
<body class="gradient-bg min-h-screen flex flex-col font-sans">
    <!-- Header -->
    <header class="bg-gray-900 text-white py-4 border-b-4 border-blue-500">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="text-2xl font-semibold">Exsys</div>
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="text-white hover:text-blue-300">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-full font-medium transition-colors duration-200">Daftar</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="bg-white rounded-xl shadow-lg p-8 max-w-md text-center hover-lift">
            <div class="text-blue-600 text-4xl mb-4">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="text-3xl font-semibold text-gray-900 mb-4">Selamat Datang di Exsys Examination System</h1>
            <p class="text-gray-600 mb-6">
                Optimalkan pengelolaan ujian dan quiz Anda dengan Exsys! Buat soal interaktif, atur jadwal ujian, acak opsi secara cerdas, dan pantau hasil secara real-time. Solusi ideal untuk institusi pendidikan dan pelatihan profesional.
            </p>
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-medium transition-colors duration-200">Mulai Sekarang</a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-4 mt-auto">
        <div class="container mx-auto px-6 text-center">
            <p>© 2025 Exsys Examination System. Semua hak dilindungi.</p>
        </div>
    </footer>

    <!-- Font Awesome untuk ikon -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>