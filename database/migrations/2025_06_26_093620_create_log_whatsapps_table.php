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
        Schema::create('log_whatsapps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rekmed_umum_id')->nullable(); // opsional, relasi ke rekam medis
            $table->unsignedBigInteger('rekmed_bc_id')->nullable(); // opsional, relasi ke rekam medis
            $table->string('nama_pasien');
            $table->string('nomor_wa');
            $table->text('pesan');
            $table->timestamp('waktu_kirim')->nullable();
            $table->enum('status', ['sukses', 'gagal']);
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('rekmed_umum_id')->references('id')->on('rekmed_umums')->onDelete('cascade');
            $table->foreign('rekmed_bc_id')->references('id')->on('rekmed_beautycares')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_whatsapps');
    }
};
