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
        Schema::create('rekmed_umums', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_pendaftaran');
            $table->unsignedBigInteger('id_layanan')->nullable();

            //anamnesis
            $table->text('keterangan_keluhan')->nullable();
            $table->text('alergi')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('riwayat_sosial')->nullable();
            $table->text('riwayat_keluarga')->nullable();
            //pemeriksaan
            $table->string('sistolik')->nullable();
            $table->string('diastolik')->nullable();
            $table->string('suhu')->nullable();
            $table->string('bb')->nullable(); // berat badan
            $table->string('tb')->nullable(); // tinggi badan
            $table->string('laju_nadi')->nullable();
            $table->string('laju_nafas')->nullable();
            $table->text('pemeriksaan_umum')->nullable();
            $table->text('pemeriksaan_khusus')->nullable();
            //diagnosis
            $table->unsignedBigInteger('id_diagnosa')->nullable();
            $table->text('keterangan_diagnosa_utama')->nullable();
            $table->text('keterangan_diagnosa_tambahan')->nullable();
            $table->string('keparahan')->nullable();
            //tindakan&plan
            $table->text('tindakan')->nullable();
            $table->boolean('kontrol_ulang')->default(false);
            $table->date('jadwal_kontrol')->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->text('catatan_jadwal')->nullable();

            $table->timestamps(); // includes created_at and updated_at

            // Foreign keys (optional, sesuaikan dengan tabel relasinya)
            $table->foreign('id_pasien')->references('id')->on('pasiens')->onDelete('cascade');
            $table->foreign('id_pendaftaran')->references('id')->on('pendaftarans')->onDelete('cascade');
            $table->foreign('id_layanan')->references('id')->on('layanan')->onDelete('set null');
            $table->foreign('id_diagnosa')->references('id')->on('diagnosas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekmed_umums');
    }
};
