<?php

namespace App\Http\Controllers;

use App\Imports\UsersImport;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = User::query();

        // Pencarian berdasarkan nama atau email
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filter berdasarkan peran
        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        // Filter berdasarkan instansi
        if ($institution_id = $request->input('institution_id')) {
            $query->where('institution_id', $institution_id);
        }

        $users = $query->with('institution')->paginate(10); // Paginasi, 10 per halaman
        $institutions = Institution::all();

        return view('users.index', compact('users', 'institutions'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return redirect()->route('users.index')->with('success', 'Pengguna berhasil diimpor.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $row = $failure->row();
                $errors = $failure->errors();
                $errorMessages[] = "Baris {$row}: " . implode(', ', $errors);
            }
            return redirect()->route('users.index')->with('error', implode(' | ', $errorMessages));
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Gagal mengimpor: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $institutions = Institution::all();
        return view('users.create', compact('institutions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,student',
            'institution_id' => 'nullable|exists:institutions,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'institution_id' => $request->institution_id,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $institutions = Institution::all();
        return view('users.edit', compact('user', 'institutions'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,student',
            'institution_id' => 'nullable|exists:institutions,id',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'institution_id' => $request->institution_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}