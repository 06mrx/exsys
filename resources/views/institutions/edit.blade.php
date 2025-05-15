@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Instansi</h2>
    <div class="neo-card">
        <form action="{{ route('institutions.update', $institution) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-600 mb-2">Nama Instansi</label>
                <input type="text" name="name" id="name" class="neo-input w-full @error('name') is-invalid @enderror"
                    value="{{ old('name', $institution->name) }}" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="primary-button">Simpan</button>
                <a href="{{ route('institutions.index') }}" class="secondary-button">Batal</a>
            </div>
        </form>
    </div>
@endsection