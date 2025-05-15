@extends('layouts.student')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard Siswa</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card Daftar Ujian -->
        <div class="neo-card p-6 text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Daftar Ujian</h3>
            <p class="text-gray-600 mb-4">Lihat dan ikuti ujian yang tersedia.</p>
            <a href="{{ route('exams.student-index') }}" class="primary-button">Lihat Ujian</a>
        </div>

        <!-- Card Ikuti Kuis -->
        <div class="neo-card p-6 text-center">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Ikuti Kuis</h3>
            <p class="text-gray-600 mb-4">Mulai kuis pendek yang tersedia.</p>
            <a href="{{ route('quizzes.student') }}" class="primary-button">Mulai Kuis</a>
        </div>
    </div>
@endsection