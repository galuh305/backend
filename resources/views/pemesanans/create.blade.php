@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Tambah Pemesanan</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif
    <form action="{{ route('pemesanans.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control" required>
                <option value="">Pilih User</option>
                @foreach(App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Lapangan</label>
            <div class="input-group">
                <select name="lapangan_id" id="lapangan_id" class="form-control" required onchange="updateHarga()">
                    <option value="">Pilih Lapangan</option>
                    @foreach(App\Models\Lapangan::all() as $lapangan)
                        <option value="{{ $lapangan->id }}" data-harga="{{ $lapangan->harga }}" data-nama="{{ $lapangan->nama }}" data-tipe="{{ $lapangan->tipe }}" data-aktif="{{ $lapangan->aktif }}">{{ $lapangan->nama }} ({{ ucfirst($lapangan->tipe) }})</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button type="button" class="btn btn-info" onclick="showLapanganDetail()">Detail</button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" id="harga" name="harga" class="form-control" value="" readonly>
        </div>
        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Jam Mulai</label>
            <input type="time" name="jam_mulai" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Jam Selesai</label>
            <input type="time" name="jam_selesai" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div class="form-group">
            <label>Total Harga</label>
            <input type="number" id="total_harga" name="total_harga" class="form-control" value="" readonly>
        </div>
        <div class="form-group">
            <label>Bukti Transfer (gambar)</label>
            <input type="file" name="bukti_tf" class="form-control-file" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<!-- Modal Detail Lapangan -->
<div class="modal fade" id="lapanganModal" tabindex="-1" role="dialog" aria-labelledby="lapanganModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lapanganModalLabel">Detail Lapangan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="lapanganDetailBody">
        <!-- detail lapangan akan diisi JS -->
      </div>
    </div>
  </div>
</div>

<script>
function updateHarga() {
    var select = document.getElementById('lapangan_id');
    var harga = select.options[select.selectedIndex].getAttribute('data-harga');
    document.getElementById('harga').value = harga || '';
    updateTotalHarga();
}
function updateTotalHarga() {
    var harga = parseInt(document.getElementById('harga').value) || 0;
    var jamMulai = document.querySelector('input[name=jam_mulai]').value;
    var jamSelesai = document.querySelector('input[name=jam_selesai]').value;
    if (jamMulai && jamSelesai && harga) {
        var start = jamMulai.split(':');
        var end = jamSelesai.split(':');
        var durasi = (parseInt(end[0])*60+parseInt(end[1]) - (parseInt(start[0])*60+parseInt(start[1]))) / 60;
        document.getElementById('total_harga').value = durasi > 0 ? harga * durasi : '';
    } else {
        document.getElementById('total_harga').value = '';
    }
}
document.querySelector('input[name=jam_mulai]').addEventListener('change', updateTotalHarga);
document.querySelector('input[name=jam_selesai]').addEventListener('change', updateTotalHarga);
function showLapanganDetail() {
    var select = document.getElementById('lapangan_id');
    var option = select.options[select.selectedIndex];
    var nama = option.getAttribute('data-nama');
    var tipe = option.getAttribute('data-tipe');
    var harga = option.getAttribute('data-harga');
    var aktif = option.getAttribute('data-aktif') == '1' ? 'Ya' : 'Tidak';
    var html = '<b>Nama:</b> ' + nama + '<br>' +
               '<b>Tipe:</b> ' + tipe + '<br>' +
               '<b>Harga:</b> Rp. ' + (harga ? parseInt(harga).toLocaleString('id-ID') : '-') + '<br>' +
               '<b>Aktif:</b> ' + aktif;
    document.getElementById('lapanganDetailBody').innerHTML = html;
    $('#lapanganModal').modal('show');
}
</script>
@endsection
