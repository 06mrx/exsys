@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Instansi</h2>
        <a href="{{ route('institutions.create') }}" class="primary-button">
            Tambah Instansi
        </a>
    </div>
    <div class="neo-card">
        @if ($institutions->isEmpty())
            <p class="text-gray-600">Belum ada instansi.</p>
        @else
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-600">
                        <th class="p-4">Nama Instansi</th>
                        <th class="p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($institutions as $institution)
                        <tr class="border-t border-neo-light">
                            <td class="p-4">{{ $institution->name }}</td>
                            <td class="p-4 flex space-x-2">
                                <a href="{{ route('institutions.edit', $institution) }}"
                                    class="secondary-button">Edit</a>
                                <form action="{{ route('institutions.destroy', $institution) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus instansi ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="tertiary-button text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection