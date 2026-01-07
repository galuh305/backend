<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanans';
    protected $fillable = [
        'user_id',
        'lapangan_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'status',
        'harga',
        'total_harga',
        'bukti_tf',
        'gateway',
        'gateway_order_id',
        'gateway_transaction_id',
        'gateway_payment_type',
        'paid_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(\App\Models\Lapangan::class, 'lapangan_id');
    }
}
