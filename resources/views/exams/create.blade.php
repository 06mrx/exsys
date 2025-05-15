@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Buat Ujian Baru</h2>
        <a href="{{ route('exams.index') }}" class="secondary-button">Kembali</a>
    </div>
    <div class="neo-card p-6">
        <form method="POST" action="{{ route('exams.store') }}" class="space-y-6">
            @csrf
            <div>
                <label for="institution_id" class="block text-gray-600 mb-2">Institusi</label>
                <select name="institution_id" id="institution_id" class="neo-input w-full" required>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="name" class="block text-gray-600 mb-2">Nama Ujian</label>
                <input type="text" name="name" id="name" class="neo-input w-full" value="{{ old('name') }}" required>
            </div>
            <div>
                <label for="duration" class="block text-gray-600 mb-2">Durasi (menit)</label>
                <input type="number" name="duration" id="duration" class="neo-input w-full" value="{{ old('duration') }}" required>
            </div>
            <div>
                <label for="shuffle_questions" class="block text-gray-600 mb-2">Acak Soal</label>
                <input type="checkbox" name="shuffle_questions" id="shuffle_questions" class="neo-input">
            </div>
            <div>
                <label for="shuffle_options" class="block text-gray-600 mb-2">Acak Opsi</label>
                <input type="checkbox" name="shuffle_options" id="shuffle_options" class="neo-input">
            </div>
            <div>
                <label for="start_time" class="block text-gray-600 mb-2">Waktu Mulai (opsional)</label>
                <input type="datetime-local" name="start_time" id="start_time" class="neo-input w-full" value="{{ old('start_time') }}">
            </div>
            <div>
                <label for="is_active" class="block text-gray-600 mb-2">Aktif</label>
                <input type="checkbox" name="is_active" id="is_active" class="neo-input" checked>
            </div>
            <div>
                <label for="questions" class="block text-gray-600 mb-2">Pilih Soal</label>
                @foreach ($questions as $question)
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="questions[]" value="{{ $question->id }}" id="question_{{ $question->id }}" class="neo-input">
                        <label for="question_{{ $question->id }}" class="ml-2 text-gray-600">{{ $question->content }}</label>
                    </div>
                @endforeach
            </div>
            <button type="submit" class="primary-button">Simpan</button>
        </form>
    </div>
@endsection