@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">{{ $quiz->name }}</h2>
    </div>
    <div class="neo-card p-6">
        <p class="text-gray-600 mb-2"><strong>Durasi:</strong> {{ $quiz->duration }} menit</p>
        <p class="text-gray-600 mb-2"><strong>Status:</strong> {{ $quiz->is_active ? 'Aktif' : 'Tidak Aktif' }}</p>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Daftar Soal</h3>
        @if ($questions->isEmpty())
            <p class="text-gray-600">Belum ada soal.</p>
        @else
            <ol class="list-decimal list-inside">
                @foreach ($questions as $question)
                    <li class="mb-2">
                        <p class="text-gray-600">{{ $question->content }}</p>
                        <ul class="list-disc list-inside ml-4">
                            @foreach ($question->options as $option)
                                <li class="{{ $option->is_correct ? 'text-green-600' : 'text-gray-600' }}">{{ $option->content }}</li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
@endsection