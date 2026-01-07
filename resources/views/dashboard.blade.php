@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total User</h5>
                    <p class="card-text" style="font-size:2em">{{ $userCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Lapangan</h5>
                    <p class="card-text" style="font-size:2em">{{ $lapanganCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Pemesanan</h5>
                    <p class="card-text" style="font-size:2em">{{ $pemesananCount }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <h4>User Terbaru</h4>
            <ul class="list-group">
                @foreach($latestUsers as $user)
                    <li class="list-group-item">{{ $user->name }} ({{ $user->email }})</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            <h4>Lapangan Terbaru</h4>
            <ul class="list-group">
                @foreach($latestLapangans as $lapangan)
                    <li class="list-group-item">{{ $lapangan->nama }} ({{ $lapangan->tipe }})</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            <h4>Pemesanan Terbaru</h4>
            <ul class="list-group">
                @foreach($latestPemesanans as $p)
                    <li class="list-group-item">
                        {{ $p->user->name ?? '-' }} - {{ $p->lapangan->nama ?? '-' }}<br>
                        {{ $p->tanggal }} ({{ $p->jam_mulai }} - {{ $p->jam_selesai }})
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-8">
            <h4>Statistik Pemesanan per Bulan</h4>
            <canvas id="chartPemesanan"></canvas>
        </div>
        <div class="col-md-4">
            <h4>Kalender</h4>
            <div id="calendar"></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
// Statistik chart
fetch('/dashboard/statistik')
    .then(res => res.json())
    .then(data => {
        new Chart(document.getElementById('chartPemesanan').getContext('2d'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Jumlah Pemesanan',
                    data: data.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)'
                }]
            }
        });
    });
// Kalender
fetch('/dashboard/events')
    .then(res => res.json())
    .then(events => {
        var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            events: events
        });
        calendar.render();
    });
</script>
@endsection
