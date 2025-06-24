@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Soal</h2>
    </div>
    <div class="neo-card">
        <form method="POST" action="{{ route('questions.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="content" class="block text-gray-600 mb-2">Pertanyaan</label>
                <textarea name="content" id="content" class="neo-input w-full" rows="3" required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="question_type" class="block text-gray-600 mb-2">Jenis Soal</label>
                <select name="question_type" id="question_type" class="neo-input w-full" required>
                    <option value="single" {{ old('question_type') === 'single' ? 'selected' : '' }}>Pilihan Tunggal
                    </option>
                    <option value="multiple" {{ old('question_type') === 'multiple' ? 'selected' : '' }}>Pilihan Ganda
                    </option>
                </select>
                @error('question_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_quiz" value="1" {{ old('is_quiz') ? 'checked' : '' }}
                        class="neo-input">
                    <span class="ml-2 text-gray-600">Soal untuk Quiz</span>
                </label>
            </div>
            <div class="mb-4">
                <label for="institution_id" class="block text-gray-600 mb-2">Instansi</label>
                <select name="institution_id" id="institution_id" class="neo-input w-full">
                    <option value="">Tidak ada instansi</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}"
                            {{ old('institution_id') == $institution->id ? 'selected' : '' }}>
                            {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
                @error('institution_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-600 mb-2">Gambar (Opsional)</label>
                <input type="file" name="image" id="image" class="neo-input w-full" accept="image/*">
                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-600 mb-2">Opsi Jawaban</label>
                @for ($i = 0; $i < 10; $i++)
                    <div class="flex items-center mb-2">
                        <input type="text" name="options[{{ $i }}][content]" class="neo-input w-full mr-2"
                            value="{{ old('options.' . $i . '.content') }}" placeholder="Opsi {{ chr(65 + $i) }}"
                            >
                        <label class="flex items-center">
                            <input type="checkbox" name="correct_options[]" value="{{ $i }}"
                                {{ in_array($i, old('correct_options', [])) ? 'checked' : '' }} class="neo-input"
                                data-question-type="multiple">
                            <span class="ml-2 text-gray-600">Benar</span>
                        </label>
                    </div>
                    @error('options.' . $i . '.content')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @endfor
                @error('correct_options')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex justify-end space-x-4">
                <a href="{{ route('questions.index') }}" class="secondary-button">Batal</a>
                <button type="submit" class="primary-button">Simpan</button>
            </div>
        </form>
    </div>
@endsection
