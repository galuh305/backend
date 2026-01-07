<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lapangan;
use App\Models\Pemesanan;

class HomeController extends Controller
{
    public function dashboard()
    {
        $userCount = User::count();
        $lapanganCount = Lapangan::count();
        $pemesananCount = Pemesanan::count();
        $latestUsers = User::latest()->take(5)->get();
        $latestLapangans = Lapangan::latest()->take(5)->get();
        $latestPemesanans = Pemesanan::latest()->take(5)->get();
        return view('dashboard', compact('userCount', 'lapanganCount', 'pemesananCount', 'latestUsers', 'latestLapangans', 'latestPemesanans'));
    }

    public function statistik()
    {
        $data = \App\Models\Pemesanan::selectRaw('DATE_FORMAT(tanggal, "%Y-%m") as bulan, COUNT(*) as jumlah')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
        $labels = $data->pluck('bulan');
        $jumlah = $data->pluck('jumlah');
        return response()->json([
            'labels' => $labels,
            'data' => $jumlah
        ]);
    }

    public function events()
    {
        $events = \App\Models\Pemesanan::all()->map(function($p) {
            return [
                'title' => ($p->user->name ?? '-') . ' - ' . ($p->lapangan->nama ?? '-'),
                'start' => $p->tanggal . 'T' . $p->jam_mulai,
                'end' => $p->tanggal . 'T' . $p->jam_selesai,
            ];
        });
        return response()->json($events);
    }
}
