@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Soal</h2>
    </div>
    <div class="neo-card">
        <form method="POST" action="{{ route('questions.update', $question) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="content" class="block text-gray-600 mb-2">Pertanyaan</label>
                <textarea name="content" id="content" class="neo-input w-full" rows="3" required>{{ old('content', $question->content) }}</textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- <div class="mb-4">
                <label for="category" class="block text-gray-600 mb-2">Kategori</label>
                <input type="text" name="category" id="category" class="neo-input w-full" value="{{ old('category', $question->category) }}"
                    placeholder="Masukkan kategori (opsional)">
                @error('category')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div> --}}
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_quiz" value="1"
                        {{ old('is_quiz', $question->is_quiz) ? 'checked' : '' }} class="neo-input">
                    <span class="ml-2 text-gray-600">Soal untuk Quiz</span>
                </label>
            </div>
            <div class="mb-4">
                <label for="institution_id" class="block text-gray-600 mb-2">Instansi</label>
                <select name="institution_id" id="institution_id" class="neo-input w-full">
                    <option value="">Tidak ada instansi</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}"
                            {{ old('institution_id', $question->institution_id) == $institution->id ? 'selected' : '' }}>
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
                @if ($question->image_path)
                    <div class="mt-2">
                        <img src="{{ asset($question->image_path) }}" alt="Gambar Saat Ini"
                            class="w-32 h-32 object-cover rounded-md mt-2">
                        <p class="text-gray-600 text-sm mt-1">Biarkan kosong untuk mempertahankan gambar ini.</p>
                    </div>
                @endif
                @error('image')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-gray-600 mb-2">Opsi Jawaban</label>
                @foreach ($question->options as $index => $option)
                    <div class="flex items-center mb-2">
                        <input type="text" name="options[{{ $index }}][content]" class="neo-input w-full mr-2"
                            value="{{ old('options.' . $index . '.content', $option->content) }}"
                            placeholder="Opsi {{ chr(65 + $index) }}" required>
                        <label class="flex items-center">
                            <input type="radio" name="correct_option" value="{{ $index }}"
                                {{ old('correct_option', $option->is_correct ? $index : null) == $index ? 'checked' : '' }} required>
                            <span class="ml-2 text-gray-600">Benar</span>
                        </label>
                    </div>
                    @error('options.' . $index . '.content')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                @endforeach
                @error('correct_option')
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