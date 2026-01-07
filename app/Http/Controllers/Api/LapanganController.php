<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lapangan;

class LapanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Lapangan::all());
    }

    /**
     * Store a newly created resource in storage.
     */
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
        $lapangan = Lapangan::create($validated);
        return response()->json($lapangan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lapangan = Lapangan::findOrFail($id);
        return response()->json($lapangan);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|string',
            'jenis' => 'required|string',
            'tipe' => 'sometimes|in:karpet,biasa',
            'harga' => 'nullable|integer',
            'aktif' => 'sometimes|boolean',
        ]);
        if (!isset($validated['harga'])) {
            $validated['harga'] = isset($validated['tipe']) ? ($validated['tipe'] === 'karpet' ? 50000 : 40000) : $lapangan->harga;
        }
        $lapangan->update($validated);
        return response()->json($lapangan);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $lapangan->delete();
        return response()->json(['message' => 'Lapangan deleted']);
    }
}
