@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Tambah Lapangan</h1>
    <form action="{{ route('lapangans.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Jenis</label>
            <input type="text" name="jenis" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tipe</label>
            <select name="tipe" class="form-control" required>
                <option value="karpet">Karpet</option>
                <option value="biasa">Biasa</option>
            </select>
        </div>
        <div class="form-group">
            <label>Harga (kosongkan untuk otomatis)</label>
            <input type="number" name="harga" class="form-control" placeholder="Harga lapangan">
        </div>
        <div class="form-group">
            <label>Aktif</label>
            <select name="aktif" class="form-control">
                <option value="1">Ya</option>
                <option value="0">Tidak</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
