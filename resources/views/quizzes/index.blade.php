@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Quiz</h2>
        <a href="{{ route('quizzes.create') }}" class="primary-button">Buat Quiz Baru</a>
    </div>
    <div class="neo-card">
        @if ($quizzes->isEmpty())
            <p class="text-gray-600 p-6">Belum ada quiz yang tersedia.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-3">Nama Quiz</th>
                            <th class="p-3">Institusi</th>
                            <th class="p-3">Durasi</th>
                            <th class="p-3">Waktu Mulai</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quizzes as $quiz)
                            <tr class="border-t border-neo-light">
                                <td class="p-3">{{ $quiz->name }}</td>
                                <td class="p-3">{{ $quiz->institution->name }}</td>
                                <td class="p-3">{{ $quiz->duration }} menit</td>
                                <td class="p-3">{{ $quiz->start_time ? $quiz->start_time: '-' }}</td>
                                <td class="p-3">
                                    @if ($quiz->is_active)
                                        <span class="text-green-600">Aktif</span>
                                    @else
                                        <span class="text-red-600">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="p-3 flex gap-2">
                                    <a href="{{ route('quizzes.edit', $quiz) }}"
                                        class="secondary-button inline-block">Edit</a>
                                    <button onclick="openDeleteModal({{ $quiz->id }})"
                                        class="danger-button inline-block">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="neo-card p-6 max-w-sm w-full">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Konfirmasi Hapus Quiz</h3>
            <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus quiz ini? Tindakan ini tidak dapat dibatalkan.</p>
            <form id="delete-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeDeleteModal()" class="secondary-button">Batal</button>
                    <button type="submit" class="danger-button">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(quizId) {
            const modal = document.getElementById('delete-modal');
            const form = document.getElementById('delete-form');
            form.action = `/quizzes/${quizId}`; // Set action URL dynamically
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
        }

        // Tutup modal jika klik di luar modal
        document.getElementById('delete-modal').addEventListener('click', (e) => {
            if (e.target === document.getElementById('delete-modal')) {
                closeDeleteModal();
            }
        });
    </script>
@endsection