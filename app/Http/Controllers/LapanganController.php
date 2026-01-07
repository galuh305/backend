<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangans = Lapangan::all();
        return view('lapangans.index', compact('lapangans'));
    }

    public function show($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        return view('lapangans.show', compact('lapangan'));
    }

    public function create()
    {
        return view('lapangans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'jenis' => 'required|string',
            'tipe' => 'required|in:karpet,biasa',
            'harga' => 'nullable|integer',
            'aktif' => 'boolean',
        ]);
        if (!isset($validated['harga'])) {
            $validated['harga'] = $validated['tipe'] === 'karpet' ? 50000 : 40000;
        }
        \App\Models\Lapangan::create($validated);
        return redirect()->route('lapangans.index')->with('success', 'Lapangan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $lapangan = \App\Models\Lapangan::findOrFail($id);
        return view('lapangans.edit', compact('lapangan'));
    }

    public function update(Request $request, $id)
    {
        $lapangan = \App\Models\Lapangan::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|string',
            'jenis' => 'sometimes|string',
            'tipe' => 'sometimes|in:karpet,biasa',
            'harga' => 'nullable|integer',
            'aktif' => 'sometimes|boolean',
        ]);
        if (!isset($validated['harga'])) {
            $validated['harga'] = isset($validated['tipe']) ? ($validated['tipe'] === 'karpet' ? 50000 : 40000) : $lapangan->harga;
        }
        $lapangan->update($validated);
        return redirect()->route('lapangans.index')->with('success', 'Lapangan berhasil diupdate');
    }

    public function destroy($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $lapangan->delete();
        return redirect()->route('lapangans.index')->with('success', 'Lapangan berhasil dihapus');
    }
}
