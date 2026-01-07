<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relasi ke users
            $table->foreignId('lapangan_id')->constrained('lapangans')->onDelete('cascade');
            $table->date('tanggal'); // Tanggal booking
            $table->time('jam_mulai'); // Jam mulai booking
            $table->time('jam_selesai');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('confirmed');
            $table->integer('harga')->nullable();
            $table->integer('total_harga')->nullable();
            $table->string('bukti_tf')->nullable(); // Tambahan keterangan jika diperlukan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
