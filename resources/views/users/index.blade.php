@extends('layouts.app')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Pengguna</h2>
        <div class="flex space-x-4">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="file" class="neo-button cursor-pointer">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 0117 8a5 5 0 014 4.9 4 4 0 01-2 3.46M12 4v12m-4-4h8"></path>
                    </svg>
                    Pilih Excel
                    <input type="file" id="file" name="file" accept=".xlsx,.xls" class="hidden" required>
                </label>
                <button type="submit" class="primary-button"> Import</button>
            </form>
            <a href="{{ route('users.create') }}" class="primary-button">
                Tambah Pengguna
            </a>
        </div>
    </div>
    <div class="neo-card mb-6">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" class="neo-input w-full"
                    placeholder="Cari nama atau email...">
            </div>
            <div class="flex-1">
                <select name="role" class="neo-input w-full">
                    <option value="">Semua Peran</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Siswa</option>
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
        @if ($users->isEmpty())
            <p class="text-gray-600">Belum ada pengguna.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[600px]">
                    <thead>
                        <tr class="text-gray-600">
                            <th class="p-3">Nama</th>
                            <th class="p-3">Email</th>
                            <th class="p-3">Peran</th>
                            <th class="p-3">Instansi</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-t border-neo-light">
                                <td class="p-3">{{ $user->name }}</td>
                                <td class="p-3">{{ $user->email }}</td>
                                <td class="p-3">{{ $user->is_admin ? 'Admin' : 'Siswa' }}</td>
                                <td class="p-3">{{ $user->institution ? $user->institution->name : '-' }}</td>
                                <td class="p-3 flex space-x-2">
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="secondary-button text-sm">Edit</a>
                                    <button type="button" class="tertiary-button text-red-600 text-sm"
                                        onclick="openDeleteModal({{ $user->id }})">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Navigasi Paginasi -->
            <div class="mt-6 flex justify-end">
                {{ $users->appends(request()->query())->links() }}
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
            <p class="text-gray-600 mb-6">Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
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