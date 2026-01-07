<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanans = Pemesanan::all();
        return view('pemesanans.index', compact('pemesanans'));
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        return view('pemesanans.show', compact('pemesanan'));
    }

    public function create()
    {
        return view('pemesanans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lapangan_id' => 'required|exists:lapangans,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'status' => 'in:pending,confirmed,cancelled',
            'bukti_tf' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        // Validasi booking bentrok
        $exists = \App\Models\Pemesanan::where('lapangan_id', $validated['lapangan_id'])
            ->where('tanggal', $validated['tanggal'])
            ->where('status', 'confirmed')
            ->where(function($q) use ($validated) {
                $q->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                  ->orWhere(function($q2) use ($validated) {
                      $q2->where('jam_mulai', '<', $validated['jam_mulai'])
                         ->where('jam_selesai', '>', $validated['jam_selesai']);
                  });
            })->exists();
        if ($exists) {
            return back()->withErrors(['lapangan_id' => 'Lapangan sudah terbooking silahkan pilih jam lain'])->withInput();
        }
        $lapangan = \App\Models\Lapangan::findOrFail($validated['lapangan_id']);
        $validated['harga'] = $lapangan->harga;
        $start = strtotime($validated['jam_mulai']);
        $end = strtotime($validated['jam_selesai']);
        $durasi = ($end - $start) / 3600;
        $validated['total_harga'] = $lapangan->harga * $durasi;
        if ($request->hasFile('bukti_tf')) {
            $file = $request->file('bukti_tf');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_tf'), $filename);
            $validated['bukti_tf'] = 'uploads/bukti_tf/'.$filename;
        }
        \App\Models\Pemesanan::create($validated);
        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        return view('pemesanans.edit', compact('pemesanan'));
    }

    public function update(Request $request, $id)
    {
        $pemesanan = \App\Models\Pemesanan::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'lapangan_id' => 'sometimes|exists:lapangans,id',
            'tanggal' => 'sometimes|date',
            'jam_mulai' => 'sometimes',
            'jam_selesai' => 'sometimes',
            'status' => 'sometimes|in:pending,confirmed,cancelled',
            'bukti_tf' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        // Validasi booking bentrok
        $lapangan_id = $validated['lapangan_id'] ?? $pemesanan->lapangan_id;
        $tanggal = $validated['tanggal'] ?? $pemesanan->tanggal;
        $jam_mulai = $validated['jam_mulai'] ?? $pemesanan->jam_mulai;
        $jam_selesai = $validated['jam_selesai'] ?? $pemesanan->jam_selesai;
        $exists = \App\Models\Pemesanan::where('lapangan_id', $lapangan_id)
            ->where('tanggal', $tanggal)
            ->where('status', 'confirmed')
            ->where('id', '!=', $id)
            ->where(function($q) use ($jam_mulai, $jam_selesai) {
                $q->whereBetween('jam_mulai', [$jam_mulai, $jam_selesai])
                  ->orWhereBetween('jam_selesai', [$jam_mulai, $jam_selesai])
                  ->orWhere(function($q2) use ($jam_mulai, $jam_selesai) {
                      $q2->where('jam_mulai', '<', $jam_mulai)
                         ->where('jam_selesai', '>', $jam_selesai);
                  });
            })->exists();
        if ($exists) {
            return back()->withErrors(['lapangan_id' => 'Lapangan sudah terbooking silahkan pilih jam lain'])->withInput();
        }
        $lapangan = \App\Models\Lapangan::findOrFail($lapangan_id);
        $validated['harga'] = $lapangan->harga;
        $start = strtotime($jam_mulai);
        $end = strtotime($jam_selesai);
        $durasi = ($end - $start) / 3600;
        $validated['total_harga'] = $lapangan->harga * $durasi;
        if ($request->hasFile('bukti_tf')) {
            $file = $request->file('bukti_tf');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads/bukti_tf'), $filename);
            $validated['bukti_tf'] = 'uploads/bukti_tf/'.$filename;
        }
        $pemesanan->update($validated);
        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil diupdate');
    }

    public function destroy($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->delete();
        return redirect()->route('pemesanans.index')->with('success', 'Pemesanan berhasil dihapus');
    }

    public function updateStatus(Request $request, $id)
    {
        $pemesanan = \App\Models\Pemesanan::findOrFail($id);
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);
        $pemesanan->status = $request->status;
        $pemesanan->save();
        return redirect()->route('pemesanans.index')->with('success', 'Status pemesanan berhasil diubah');
    }
}
