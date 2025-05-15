@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Hasil Ujian: {{ $exam->name }} - {{ $user->name }}</h2>
        <a href="{{ route('exams.results') }}" class="mt-4 inline-block secondary-button">Kembali</a>
    </div>
    <div class="neo-card">
        @if ($results->isEmpty())
            <p class="text-gray-600">Tidak ada data jawaban untuk ujian ini.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-3">No</th>
                            <th class="p-3">Soal</th>
                            <th class="p-3">Jawaban Peserta</th>
                            <th class="p-3">Benar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $index => $result)
                            <tr class="border-t border-neo-light">
                                <td class="p-3">{{ $index + 1 }}</td>
                                <td class="p-3">{{ $result->question->content }}</td>
                                <td class="p-3">{{ $result->option->content ?? 'Tidak dijawab' }}</td>
                                <td class="p-3">
                                    <span class="{{ $result->is_correct ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $result->is_correct ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection