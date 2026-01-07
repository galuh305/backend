<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use \Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;


class LaporanController extends Controller
{
    public function laporanMingguan(Request $request)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        if ($request->has('date')) {
            $startOfWeek = Carbon::parse($request->date)->startOfWeek();
            $endOfWeek = Carbon::parse($request->date)->endOfWeek();
        }

        $laporan = Pemesanan::whereBetween('tanggal', [$startOfWeek, $endOfWeek])->get();

        return view('laporan.mingguan', compact('laporan'));
    }

    public function laporanBulanan(Request $request)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        if ($request->has('month')) {
            $startOfMonth = Carbon::parse($request->month)->startOfMonth();
            $endOfMonth = Carbon::parse($request->month)->endOfMonth();
        }

        $laporan = Pemesanan::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->get();

        return view('laporan.bulanan', compact('laporan'));
    }

    public function cetakMingguan(Request $request)
{
    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    if ($request->has('date')) {
        $startOfWeek = Carbon::parse($request->date)->startOfWeek();
        $endOfWeek = Carbon::parse($request->date)->endOfWeek();
    }

    $laporan = Pemesanan::with(['user', 'lapangan'])
                ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
                ->get();

    $pdf = Pdf::loadView('laporan.pdf.mingguan', compact('laporan', 'startOfWeek', 'endOfWeek'));
    return $pdf->stream('laporan-mingguan.pdf');
}


    public function cetakBulanan(Request $request)
{
    $startOfMonth = Carbon::now()->startOfMonth();
    $endOfMonth = Carbon::now()->endOfMonth();

    if ($request->has('month')) {
        $startOfMonth = Carbon::parse($request->month)->startOfMonth();
        $endOfMonth = Carbon::parse($request->month)->endOfMonth();
    }

    $laporan = Pemesanan::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->get();

    $pdf = Pdf::loadView('laporan.pdf.bulanan', compact('laporan', 'startOfMonth', 'endOfMonth'));
    return $pdf->stream('laporan-bulanan.pdf');
}

}