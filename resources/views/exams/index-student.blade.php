@extends('layouts.student')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Ujian</h2>
    </div>
    <div class="neo-card">
        @if ($exams->isEmpty())
            <p class="text-gray-600">Belum ada ujian untuk institusi Anda.</p>
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
                                <td class="p-3">{{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->format('d M Y H:i') : 'Belum ditentukan' }}</td>
                                <td class="p-3">{{ $exam->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
                                <td class="p-3">
                                    @if ($exam->is_active && (!$exam->start_time || Carbon\Carbon::now('Asia/Makassar')->gte($exam->start_time)))
                                        <a href="{{ route('exams.start', $exam) }}" class="primary-button text-sm">Mulai Ujian</a>
                                    @else
                                        <span class="text-gray-500">Tidak Tersedia</span>
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
    </div>
@endsection