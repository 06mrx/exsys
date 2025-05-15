<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller; // Pastikan ini diimpor

class InstitutionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $institutions = Institution::all();
        return view('institutions.index', compact('institutions'));
    }

    public function create()
    {
        return view('institutions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:institutions',
        ]);

        Institution::create($request->only('name'));

        return redirect()->route('institutions.index')->with('success', 'Instansi berhasil ditambahkan.');
    }

    public function edit(Institution $institution)
    {
        return view('institutions.edit', compact('institution'));
    }

    public function update(Request $request, Institution $institution)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:institutions,name,' . $institution->id,
        ]);

        $institution->update($request->only('name'));

        return redirect()->route('institutions.index')->with('success', 'Instansi berhasil diperbarui.');
    }

    public function destroy(Institution $institution)
    {
        $institution->delete();
        return redirect()->route('institutions.index')->with('success', 'Instansi berhasil dihapus.');
    }
}