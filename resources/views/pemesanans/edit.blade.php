@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Edit Pemesanan</h1>
    <form action="{{ route('pemesanans.update', $pemesanan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>User</label>
            <input type="number" name="user_id" class="form-control" value="{{ $pemesanan->user_id }}" required>
        </div>
        <div class="form-group">
            <label>Lapangan</label>
            <input type="number" name="lapangan_id" class="form-control" value="{{ $pemesanan->lapangan_id }}" required>
        </div>
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $pemesanan->tanggal }}" required>
        </div>
        <div class="form-group">
            <label>Jam Mulai</label>
            <input type="time" name="jam_mulai" class="form-control" value="{{ $pemesanan->jam_mulai }}" required>
        </div>
        <div class="form-group">
            <label>Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control" value="{{ $pemesanan->jam_selesai }}" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="pending" {{ $pemesanan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $pemesanan->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="cancelled" {{ $pemesanan->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" class="form-control" value="{{ $pemesanan->harga ?? '-' }}" readonly>
        </div>
        <div class="form-group">
            <label>Total Harga</label>
            <input type="number" class="form-control" value="{{ $pemesanan->total_harga ?? '-' }}" readonly>
        </div>
        <div class="form-group">
            <label>Bukti Transfer (gambar)</label>
            <input type="file" name="bukti_tf" class="form-control-file" accept="image/*">
            @if($pemesanan->bukti_tf)
                <br><img src="/{{ $pemesanan->bukti_tf }}" alt="Bukti Transfer" style="max-width:200px;max-height:200px;">
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
