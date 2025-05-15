@extends('layouts.app')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Pengguna</h2>
    <div class="neo-card">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-600 mb-2">Nama</label>
                <input type="text" name="name" id="name" class="neo-input w-full @error('name') is-invalid @enderror"
                    value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-600 mb-2">Email</label>
                <input type="email" name="email" id="email" class="neo-input w-full @error('email') is-invalid @enderror"
                    value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-600 mb-2">Kata Sandi (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" id="password"
                    class="neo-input w-full @error('password') is-invalid @enderror">
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-600 mb-2">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="neo-input w-full">
            </div>
            <div class="mb-4">
                <label for="role" class="block text-gray-600 mb-2">Peran</label>
                <select name="role" id="role" class="neo-input w-full @error('role') is-invalid @enderror" required>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Siswa</option>
                </select>
                @error('role')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="institution_id" class="block text-gray-600 mb-2">Instansi</label>
                <select name="institution_id" id="institution_id"
                    class="neo-input w-full @error('institution_id') is-invalid @enderror">
                    <option value="">Tidak ada instansi</option>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}"
                            {{ old('institution_id', $user->institution_id) == $institution->id ? 'selected' : '' }}>
                            {{ $institution->name }}
                        </option>
                    @endforeach
                </select>
                @error('institution_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="flex space-x-4">
                <button type="submit" class="primary-button">Simpan</button>
                <a href="{{ route('users.index') }}" class="secondary-button">Batal</a>
            </div>
        </form>
    </div>
@endsection