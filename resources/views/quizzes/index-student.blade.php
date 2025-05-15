@extends('layouts.student')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Quiz</h2>
    </div>
    <div class="neo-card">
        @if ($quizzes->isEmpty())
            <p class="text-gray-600 p-6">Belum ada quiz yang tersedia.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-3">Nama Quiz</th>
                            <th class="p-3">Institusi</th>
                            <th class="p-3">Durasi</th>
                            <th class="p-3">Waktu Mulai</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quizzes as $quiz)
                            <tr class="border-t border-neo-light">
                                <td class="p-3">{{ $quiz->name }}</td>
                                <td class="p-3">{{ $quiz->institution->name }}</td>
                                <td class="p-3">{{ $quiz->duration }} menit</td>
                                <td class="p-3">{{ $quiz->start_time ? $quiz->start_time : '-' }}</td>
                                <td class="p-3">
                                    @if ($quiz->is_active)
                                        <span class="text-green-600">Aktif</span>
                                    @else
                                        <span class="text-red-600">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    @php
                                        $currentTime = Carbon\Carbon::now('Asia/Makassar');
                                        $canStart = $quiz->start_time && $currentTime->gte($quiz->start_time);
                                    @endphp
                                    @if ($quiz->is_active && $canStart)
                                        <a href="{{ route('quizzes.start', $quiz) }}"
                                            class="primary-button inline-block">Mulai</a>
                                    @else
                                        <span class="text-gray-600">Belum Waktu Mulai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection