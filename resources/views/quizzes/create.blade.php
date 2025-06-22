@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Quiz Baru</h2>
    </div>
    <div class="neo-card p-6">
        <form method="POST" action="{{ route('quizzes.store') }}">
            @csrf
            <div class="mb-4">
                <label for="institution_id" class="block text-gray-600 mb-2">Institusi</label>
                <select name="institution_id" id="institution_id" class="neo-input w-full">
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-600 mb-2">Nama Quiz</label>
                <input type="text" name="name" id="name" class="neo-input w-full" value="{{ old('name') }}">
            </div>
            <div class="mb-4">
                <label for="duration" class="block text-gray-600 mb-2">Durasi (menit)</label>
                <input type="number" name="duration" id="duration" class="neo-input w-full" value="{{ old('duration') }}">
            </div>
            <div class="mb-4">
                <label for="start_time" class="block text-gray-600 mb-2">Waktu Mulai</label>
                <input type="datetime-local" name="start_time" id="start_time" value="{{ old('start_time') }}"
                    class="neo-input w-full @error('start_time') border-red-500 @enderror" required>
                @error('start_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" class="neo-input" {{ old('is_active') ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-600">Aktif</span>
                </label>
            </div>
            <div>
                <label for="questions" class="block text-gray-600 mb-2">Pilih Soal</label>
                <div class="mb-2">
                    <button type="button" id="select-all" class="secondary-button">Pilih Semua</button>
                    <button type="button" id="deselect-all" class="secondary-button">Batal Pilih Semua</button>
                </div>
                @foreach ($questions as $question)
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="questions[]" value="{{ $question->id }}" id="question_{{ $question->id }}" class="neo-input">
                        <label for="question_{{ $question->id }}" class="ml-2 text-gray-600">{{ $question->content }}</label>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-end">
                <button type="submit" class="primary-button">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            document.querySelectorAll('input[name="questions[]"]').forEach(checkbox => {
                checkbox.checked = true;
            });
        });

        document.getElementById('deselect-all').addEventListener('click', function() {
            document.querySelectorAll('input[name="questions[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        });
    </script>
@endsection