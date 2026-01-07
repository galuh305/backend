@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Bulanan</h1>

    <form method="GET" action="">
        <div class="form-group">
            <label for="month">Month:</label>
            <input type="month" name="month" id="month" class="form-control" value="{{ request('month') }}">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    @php
        $startOfMonth = request('month') ? \Carbon\Carbon::parse(request('month'))->startOfMonth() : null;
        $endOfMonth = request('month') ? \Carbon\Carbon::parse(request('month'))->endOfMonth() : null;
    @endphp

    @if($startOfMonth && $endOfMonth)
        <p>Showing results for {{ $startOfMonth->format('F Y') }}</p>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Lapangan</th>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Status</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporan as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->lapangan->nama }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->jam_mulai }}</td>
                <td>{{ $item->jam_selesai }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->harga }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('laporan.bulanan.pdf', ['month' => request('month')]) }}" class="btn btn-danger">Cetak PDF</a>
</div>
@endsection