@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Daftar Lapangan</h1>
    <a href="{{ route('lapangans.create') }}" class="btn btn-primary mb-2">Tambah Lapangan</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Jenis lapangan</th>
                <th>Tipe</th>
                <th>Harga</th>
                <th>Aktif</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lapangans as $lapangan)
            <tr>
                <td>{{ $lapangan->id }}</td>
                <td>{{ $lapangan->nama }}</td>
                <td>{{ $lapangan->jenis }}</td>
                <td>{{ $lapangan->tipe }}</td>
                <td>{{ $lapangan->harga ? 'Rp. ' . number_format($lapangan->harga, 0, ',', '.') : 'Otomatis' }}</td>
                <td>{{ $lapangan->aktif ? 'Ya' : 'Tidak' }}</td>
                <td>
                    <a href="{{ route('lapangans.show', $lapangan->id) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('lapangans.edit', $lapangan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('lapangans.destroy', $lapangan->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
