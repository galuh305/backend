<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Mingguan</title>
    <style>
        body {
            background-color: #fff;
            color: #000;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .report-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-header h1 {
            font-size: 20px;
            margin: 0;
        }
        .report-header p {
            font-size: 14px;
            margin: 5px 0;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .report-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>LAPORAN MINGGUAN</h1>
        <p>Periode: {{ $startOfWeek->format('Y-m-d') }} - {{ $endOfWeek->format('Y-m-d') }}</p>
    </div>

    <table class="report-table">
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
</body>
</html>
