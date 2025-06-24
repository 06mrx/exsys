@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Hasil Ujian Peserta</h2>
    </div>
    <div class="neo-card mb-6 p-4">
        <form method="GET" action="{{ route('exams.results') }}" class="flex flex-col lg:flex-row lg:space-x-4">
            <div class="mb-4 lg:mb-0 lg:flex-1">
                <label for="institution_id" class="block text-gray-600 mb-2">Filter Institusi</label>
                <select name="institution_id" id="institution_id" class="neo-input w-full">
                    <option value="">Semua Institusi</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}" {{ request('institution_id') == $institution->id ? 'selected' : '' }}>
                            {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4 lg:mb-0 lg:flex-1">
                <label for="exam_id" class="block text-gray-600 mb-2">Filter Ujian</label>
                <select name="exam_id" id="exam_id" class="neo-input w-full">
                    <option value="">Semua Ujian</option>
                    @foreach ($exams as $exam)
                        <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                            {{ $exam->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4 lg:mb-0 lg:flex-1">
                <label for="search" class="block text-gray-600 mb-2">Cari Nama Ujian atau Peserta</label>
                <input type="text" name="search" id="search" class="neo-input w-full" placeholder="Masukkan kata kunci..." value="{{ request('search') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="primary-button">Filter</button>
            </div>
        </form>
    </div>
    <div class="neo-card">
        @if ($results->isEmpty())
            <p class="text-gray-600">Belum ada hasil ujian.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-3">Nama Ujian</th>
                            <th class="p-3">Nama Peserta</th>
                            <th class="p-3">Skor</th>
                            <th class="p-3">Persentase</th>
                            {{-- <th class="p-3">Selesai Pada</th> --}}
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)
                            <tr class="border-t border-neo-light">
                                <td class="p-3">{{ $result['exam_name'] }}</td>
                                <td class="p-3">{{ $result['user_name'] }}</td>
                                <td class="p-3">{{ $result['score'] }}</td>
                                <td class="p-3">{{ $result['percentage'] }}</td>
                                {{-- <td class="p-3">{{ $result['completed_at'] }}</td> --}}
                                <td class="p-3">
                                    <a href="{{ route('exams.result-detail', ['exam' => $result['exam_id'], 'user' => $result['user_id']]) }}"
                                        class="secondary-button text-sm">Lihat Detail</a>
                                        {{-- reset button --}}
                                    <form action="{{ route('exams.reset', ['exam' => $result['exam_id'], 'user' => $result['user_id']]) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="primary-button text-red-600 hover:text-red-800 text-sm ml-2">
                                            Reset Hasil
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex justify-end">
                {{-- {{ $results->links() }} --}}
            </div>
        @endif
    </div>
@endsection