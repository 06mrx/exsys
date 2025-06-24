@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Hasil Ujian: {{ $exam->name }} - {{ $user->name }}</h2>
        <a href="{{ route('exams.results') }}" class="mt-4 inline-block secondary-button">Kembali</a>
    </div>
    <div class="neo-card">
        @if ($results->isEmpty())
            <p class="text-gray-600">Tidak ada data jawaban untuk ujian ini.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-3">No</th>
                            <th class="p-3">Soal</th>
                            <th class="p-3">Jawaban Peserta</th>
                            <th class="p-3">Benar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $index => $result)
                            {{-- @dd($result->getOPtion()[0]->content) --}}
                            <tr class="border-t border-neo-light">
                                <td class="p-3">{{ $index + 1 }}</td>
                                <td class="p-3">{{ $result->question->content }}</td>
                                <td class="p-3">
                                    @forelse ($result->getOPtion() as $option)
                                        <div class="mb-2">
                                            <strong>{{ $option->content }}</strong>
                                            {{-- @if ($option->image)
                                                <img src="{{ asset('storage/' . $option->image) }}" alt="Gambar Soal" class="mt-2 max-h-32">
                                            @endif --}}
                                        </div>
                                    @empty
                                        <span class="text-gray-500">Tidak ada jawaban</span>
                                    @endforelse
                                </td>
                                <td class="p-3">
                                    <span class="{{ $result->is_correct ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $result->is_correct ? 'Ya' : 'Tidak' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
