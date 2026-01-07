@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Mingguan</h1>

    <form method="GET" action="">
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    @php
        $startOfWeek = request('date') ? \Carbon\Carbon::parse(request('date'))->startOfWeek() : null;
        $endOfWeek = request('date') ? \Carbon\Carbon::parse(request('date'))->endOfWeek() : null;
    @endphp

    @if($startOfWeek && $endOfWeek)
        <p>Showing results from {{ $startOfWeek->format('Y-m-d') }} to {{ $endOfWeek->format('Y-m-d') }}</p>
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

    <a href="{{ route('laporan.mingguan.pdf', ['date' => request('date')]) }}" class="btn btn-danger">Cetak PDF</a>
</div>
@endsection