@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Quiz</h2>
    </div>
    <div class="neo-card p-6">
        <form method="POST" action="{{ route('quizzes.update', $quiz) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="institution_id" class="block text-gray-600 mb-2">Institusi</label>
                <select name="institution_id" id="institution_id" class="neo-input w-full">
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}" {{ $quiz->institution_id == $institution->id ? 'selected' : '' }}>{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-600 mb-2">Nama Quiz</label>
                <input type="text" name="name" id="name" class="neo-input w-full" value="{{ $quiz->name }}">
            </div>
            <div class="mb-4">
                <label for="duration" class="block text-gray-600 mb-2">Durasi (menit)</label>
                <input type="number" name="duration" id="duration" class="neo-input w-full" value="{{ $quiz->duration }}">
            </div>
            <div class="mb-4">
                <label for="start_time" class="block text-gray-600 mb-2">Waktu Mulai</label>
                <input type="datetime-local" name="start_time" id="start_time"
                    value="{{ old('start_time', $quiz->start_time ? $quiz->start_time->format('Y-m-d\TH:i') : '') }}"
                    class="neo-input w-full @error('start_time') border-red-500 @enderror" required>
                @error('start_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" class="neo-input" {{ $quiz->is_active ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-600">Aktif</span>
                </label>
            </div>
            <div class="mb-4">
                <label for="questions" class="block text-gray-600 mb-2">Pilih Soal</label>
                <select name="questions[]" id="questions" class="neo-input w-full" multiple>
                    @foreach ($questions as $question)
                        <option value="{{ $question->id }}" {{ in_array($question->id, $selectedQuestions) ? 'selected' : '' }}>{{ $question->content }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="primary-button">Simpan</button>
            </div>
        </form>
    </div>
@endsection