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
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id'); // FK ke barang
            $table->unsignedBigInteger('stok_rak_id')->nullable(); // NULL jika tidak dari rak
            $table->unsignedBigInteger('history_id')->nullable();  // NULL jika bukan dari resep
            $table->enum('alasan', ['resep', 'expired', 'rusak', 'retur', 'lainnya']);
            $table->text('keterangan')->nullable();
            $table->integer('jumlah');
            $table->date('tgl_keluar');
            $table->timestamps();

            $table->foreign('barang_id')->references('id')->on('barangs');
            $table->foreign('stok_rak_id')->references('id')->on('stok_rak');
            $table->foreign('history_id')->references('id')->on('histories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluars');
    }
};
