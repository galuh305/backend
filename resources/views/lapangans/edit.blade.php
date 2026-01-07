@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Edit Lapangan</h1>
    <form action="{{ route('lapangans.update', $lapangan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $lapangan->nama }}" required>
        </div>
        <div class="form-group">
            <label>Jenis</label>
            <input type="text" name="jenis" class="form-control" value="{{ $lapangan->jenis }}" required>
        </div>
        <div class="form-group">
            <label>Tipe</label>
            <select name="tipe" class="form-control" required>
                <option value="karpet" {{ $lapangan->tipe == 'karpet' ? 'selected' : '' }}>Karpet</option>
                <option value="biasa" {{ $lapangan->tipe == 'biasa' ? 'selected' : '' }}>Biasa</option>
            </select>
        </div>
        <div class="form-group">
            <label>Harga (kosongkan untuk otomatis)</label>
            <input type="number" name="harga" class="form-control" value="{{ $lapangan->harga }}" placeholder="Harga lapangan">
        </div>
        <div class="form-group">
            <label>Aktif</label>
            <select name="aktif" class="form-control">
                <option value="1" {{ $lapangan->aktif ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ !$lapangan->aktif ? 'selected' : '' }}>Tidak</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
