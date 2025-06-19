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
        Schema::create('resep_pasiens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('history_id');         // atau patient_id sesuai strukturmu
            $table->unsignedBigInteger('barang_id');
            $table->integer('jumlah');
            $table->string('aturan_pakai')->nullable();
            $table->enum('status', ['permintaan', 'dikonfirmasi', 'ditolak'])->default('permintaan');
            $table->timestamps();

            $table->foreign('history_id')->references('id')->on('rekmed_umums')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resep_pasiens');
    }
};
