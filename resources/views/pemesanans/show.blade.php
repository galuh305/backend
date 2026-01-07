@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Detail Pemesanan</h1>
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th>ID</th><td>{{ $pemesanan->id }}</td></tr>
                <tr><th>User</th><td>{{ $pemesanan->user->name ?? '-' }} ({{ $pemesanan->user->email ?? '-' }})</td></tr>
                <tr><th>Lapangan</th><td>{{ $pemesanan->lapangan->nama ?? '-' }} ({{ $pemesanan->lapangan->tipe ?? '-' }})</td></tr>
                <tr><th>Tanggal</th><td>{{ $pemesanan->tanggal }}</td></tr>
                <tr><th>Jam Mulai</th><td>{{ $pemesanan->jam_mulai }}</td></tr>
                <tr><th>Jam Selesai</th><td>{{ $pemesanan->jam_selesai }}</td></tr>
                <tr><th>Status</th><td>{{ $pemesanan->status }}</td></tr>
                <tr><th>Harga</th><td>{{ $pemesanan->harga ? 'Rp. ' . number_format($pemesanan->harga, 0, ',', '.') : '-' }}</td></tr>
                <tr><th>Total Harga</th><td>{{ $pemesanan->total_harga ? 'Rp. ' . number_format($pemesanan->total_harga, 0, ',', '.') : '-' }}</td></tr>
                <tr><th>Bukti Transfer</th><td>
                    @if($pemesanan->bukti_tf)
                        <img src="/{{ $pemesanan->bukti_tf }}" alt="Bukti TF" style="max-width:200px;max-height:200px;">
                    @else
                        -
                    @endif
                </td></tr>
            </table>
            <a href="{{ route('pemesanans.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
