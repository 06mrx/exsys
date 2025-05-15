@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
@endphp
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Ujian</h2>
        @if (auth()->user()->is_admin)
            <a href="{{ route('exams.create') }}" class="primary-button">
                Buat Ujian
            </a>
        @endif
    </div>
    <div class="neo-card">
        @if ($exams->isEmpty())
            <p class="text-gray-600">Belum ada ujian.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-3">Nama Ujian</th>
                            <th class="p-3">Durasi (menit)</th>
                            <th class="p-3">Jumlah Soal</th>
                            <th class="p-3">Waktu Mulai</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exams as $exam)
                            <tr class="border-t border-neo-light">
                                <td class="p-3">{{ $exam->name }}</td>
                                <td class="p-3">{{ $exam->duration }}</td>
                                <td class="p-3">{{ $exam->questions_count }}</td>
                                <td class="p-3">{{ $exam->start_time ? Carbon::parse($exam->start_time)->format('d M Y H:i') : 'Belum ditentukan' }}</td>
                                <td class="p-3">{{ $exam->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                                <td class="p-3 flex space-x-2">
                                    @if (auth()->user()->is_admin)
                                        <a href="{{ route('exams.show', $exam) }}" class="secondary-button text-sm">Lihat Detail</a>
                                        <a href="{{ route('exams.edit', $exam) }}" class="secondary-button text-sm">Edit</a>
                                        <button type="button" class="tertiary-button text-red-600 text-sm" onclick="openDeleteModal({{ $exam->id }})">Hapus</button>
                                    @else
                                        @if ($exam->is_active && (!$exam->start_time || Carbon\Carbon::now('Asia/Makassar')->gte($exam->start_time)))
                                            <a href="{{ route('exams.start', $exam) }}" class="primary-button text-sm">Mulai Ujian</a>
                                        @else
                                            <span class="text-gray-500">Tidak Tersedia</span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-end">
                {{ $exams->links() }}
            </div>
        @endif

        @if (auth()->user()->is_admin)
            <div id="delete-modal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
                <div class="neo-card max-w-md w-full p-6 relative">
                    <button id="delete-modal-close" class="absolute top-4 right-4 neo-button h-10 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Konfirmasi Hapus</h3>
                    <p class="text-gray-600 mb-6">Yakin ingin menghapus ujian ini? Tindakan ini tidak dapat dibatalkan.</p>
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
        @endif
    </div>
@endsection