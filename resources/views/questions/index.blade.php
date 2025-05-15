@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Soal</h2>
        <a href="{{ route('questions.create') }}" class="primary-button">
            Tambah Soal
        </a>
    </div>
    <div class="neo-card mb-6">
        <form method="GET" action="{{ route('questions.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" class="neo-input w-full"
                    placeholder="Cari soal atau kategori...">
            </div>
            <div class="flex-1">
                <select name="category" class="neo-input w-full">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1">
                <select name="institution_id" class="neo-input w-full">
                    <option value="">Semua Instansi</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}"
                            {{ request('institution_id') == $institution->id ? 'selected' : '' }}>
                            {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="primary-button">Filter</button>
        </form>
    </div>
    <div class="neo-card">
        @if ($questions->isEmpty())
            <p class="text-gray-600">Belum ada soal.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-3">Pertanyaan</th>
                            <th class="p-3">Gambar</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Instansi</th>
                            <th class="p-3">Jawaban Benar</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $question)
                            <tr class="border-t border-neo-light">
                                <td class="p-3">{{ Str::limit($question->content, 50) }}</td>
                                <td class="p-3">
                                    @if ($question->image_path)
                                        <img src="{{ Storage::url($question->image_path) }}" alt="Gambar Soal"
                                            class="w-16 h-16 object-cover rounded-md">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="p-3">{{ $question->is_quiz ? 'Quiz' : 'Ujian' }}</td>
                                <td class="p-3">{{ $question->institution ? $question->institution->name : '-' }}</td>
                                <td class="p-3">{{ $question->correctOption ? $question->correctOption->content : '-' }}</td>
                                <td class="p-3 flex space-x-2">
                                    <a href="{{ route('questions.edit', $question) }}"
                                        class="secondary-button text-sm">Edit</a>
                                    <button type="button" class="tertiary-button text-red-600 text-sm"
                                        onclick="openDeleteModal({{ $question->id }})">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-end">
                {{ $questions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="delete-modal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="neo-card max-w-md w-full p-6 relative">
            <button id="delete-modal-close"
                class="absolute top-4 right-4 neo-button h-10 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <h3 class="text-lg font-bold text-gray-800 mb-4">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mb-6">Yakin ingin menghapus soal ini? Tindakan ini tidak dapat dibatalkan.</p>
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex justify-end space-x-4">
                    <button type="button" id="delete-modal-cancel" class="secondary-button">Batal</button>
                    <button type="submit" class="primary-button text-red-600">Hapus</button>
                </div>
            </form>
        </div>
    </div>
@endsection