@extends('layouts.app')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 bg-gradient-to-r from-blue-600 to-sky-600 bg-clip-text text-transparent">Dashboard Admin Exsys</h1>
            <p class="mt-2 text-sm text-gray-600">Selamat datang, Admin! Kelola quiz dan ujian Anda dengan mudah.</p>
        </div>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card: Total Quiz -->
            <div class="bg-white rounded-lg shadow-md p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-lg text-center">
                <div class="text-blue-600 text-4xl mb-4">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Total Quiz</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $totalQuizzes }}</p>
            </div>

            <!-- Card: Total Ujian -->
            <div class="bg-white rounded-lg shadow-md p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-lg text-center">
                <div class="text-sky-600 text-4xl mb-4">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Total Ujian</h3>
                <p class="text-2xl font-bold text-sky-600">{{ $totalExams }}</p>
            </div>

            <!-- Card: Total Pengguna -->
            <div class="bg-white rounded-lg shadow-md p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-lg text-center">
                <div class="text-blue-600 text-4xl mb-4">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Total Pengguna</h3>
                <p class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</p>
            </div>

            <!-- Card: Hasil Ujian -->
            <div class="bg-white rounded-lg shadow-md p-6 transform transition-all duration-300 hover:scale-105 hover:shadow-lg text-center">
                <div class="text-sky-600 text-4xl mb-4">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Hasil Ujian</h3>
                <p class="text-2xl font-bold text-sky-600">{{ $totalExamResults }}</p>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b text-gray-600">
                            <th class="py-3 px-4">Judul</th>
                            <th class="py-3 px-4">Jenis</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentActivities as $activity)
                            <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-3 px-4">{{ $activity->title }}</td>
                                <td class="py-3 px-4">{{ $activity->type }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($activity->created_at)->format('d M Y') }}</td>
                                <td class="py-3 px-4">
                                    @if ($activity->type === 'Quiz')
                                        <a href="{{ route('quizzes.show', $activity->id) }}" class="text-blue-600 hover:text-blue-700">Lihat</a>
                                    @else
                                        <a href="{{ route('exams.show', $activity->id) }}" class="text-blue-600 hover:text-blue-700">Lihat</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Aksi Cepat -->
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="{{ route('quizzes.create') }}" class="bg-gradient-to-r from-blue-600 to-sky-600 hover:from-blue-700 hover:to-sky-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                Buat Quiz Baru
            </a>
            <a href="{{ route('users.index') }}" class="bg-gradient-to-r from-blue-600 to-sky-600 hover:from-blue-700 hover:to-sky-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                Kelola Pengguna
            </a>
            <a href="{{ route('exams.results') }}" class="bg-gradient-to-r from-blue-600 to-sky-600 hover:from-blue-700 hover:to-sky-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                Lihat Hasil Ujian
            </a>
        </div>
    </div>
</div>

<!-- Font Awesome untuk ikon -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
@endsection