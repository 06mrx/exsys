<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Institution;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new User([
            'name' => $row['name'],
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'institution_id' => $row['institution_id'] ?: null,
            'role' => 'student', // Default role
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'institution_id' => 'nullable|exists:institutions,id',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Kolom nama wajib diisi.',
            'email.required' => 'Kolom email wajib diisi.',
            'email.email' => 'Email harus valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kolom kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'institution_id.exists' => 'Instansi tidak ditemukan.',
        ];
    }
}