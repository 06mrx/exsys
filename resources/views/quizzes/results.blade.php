@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Hasil Quiz</h2>
    </div>
    <div class="neo-card p-6 mb-6">
        <form method="GET" action="{{ route('quizzes.results') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="quiz_id" class="block text-gray-600 mb-2">Pilih Quiz</label>
                <select name="quiz_id" id="quiz_id" class="neo-input w-full">
                    <option value="">Semua Quiz</option>
                    @foreach ($quizzes as $quiz)
                        <option value="{{ $quiz->id }}" {{ request('quiz_id') == $quiz->id ? 'selected' : '' }}>{{ $quiz->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="institution_id" class="block text-gray-600 mb-2">Pilih Institusi</label>
                <select name="institution_id" id="institution_id" class="neo-input w-full">
                    <option value="">Semua Institusi</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}" {{ request('institution_id') == $institution->id ? 'selected' : '' }}>{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="primary-button w-full">Filter</button>
            </div>
        </form>
    </div>
    <div class="neo-card">
        @if ($results->isEmpty())
            <p class="text-gray-600 p-6">Belum ada hasil quiz.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[700px]">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-3">Nama Siswa</th>
                            <th class="p-3">Quiz</th>
                            <th class="p-3">Institusi</th>
                            <th class="p-3">Skor</th>
                            <th class="p-3">Waktu Mulai</th>
                            <th class="p-3">Waktu Selesai</th>
                            <th class="p-3">Durasi Pengerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)
                            <tr class="border-t border-neo-light">
                                <td class="p-3">{{ $result['user']->name }}</td>
                                <td class="p-3">{{ $result['quiz']->name }}</td>
                                <td class="p-3">{{ $result['user']->institution->name }}</td>
                                <td class="p-3">{{ $result['score'] }}/{{ $result['total_questions'] }}</td>
                                <td class="p-3">{{ $result['start_time'] ? Carbon\Carbon::parse($result['start_time'], 'Asia/Makassar')->format('d M Y H:i') : '-' }}</td>
                                <td class="p-3">{{ $result['completed_at'] ? Carbon\Carbon::parse($result['completed_at'], 'Asia/Makassar')->format('d M Y H:i') : '-' }}</td>
                                <td class="p-3">{{ $result['completion_time'] ? gmdate('H:i:s', $result['completion_time']) : '-' }} detik</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection