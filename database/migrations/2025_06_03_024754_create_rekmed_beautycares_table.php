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
        Schema::create('rekmed_beautycares', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_pendaftaran');
            $table->unsignedBigInteger('id_layanan')->nullable();
            
            // Pemeriksaan section
            $table->text('keterangan_keluhan')->nullable();
            $table->enum('warna_kulit', ['Sangat Terang', 'Terang', 'Sedang', 'Sawo Matang', 'Gelap'])->nullable();
            $table->enum('jenis_kulit', ['Normal', 'Berminyak', 'Kering', 'Kombinasi', 'Sensitif'])->nullable();
            $table->enum('tekstur_kulit', ['Halus', 'Sedikit Kasar', 'Kasar', 'Bergelombang'])->nullable();
            $table->enum('kelembapan_kulit', ['sangat_kering', 'kering', 'normal'])->nullable();
            
            // Jerawat section
            $table->boolean('memiliki_jerawat')->default(false);
            $table->json('jenis_jerawat')->nullable();
            $table->enum('tingkat_jerawat', ['Ringan', 'Sedang', 'Parah', 'Meradang'])->nullable();
            
            // Additional skin info
            $table->enum('flek_hiperpigmentasi', ['tidak_ada', 'ringan', 'sedang'])->nullable();
            $table->boolean('memiliki_alergi')->default(false);
            $table->text('riwayat_alergi')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->text('produk_kosmetik_terakhir')->nullable();
            
            // Diagnosis section
            $table->text('keterangan_diagnosis')->nullable();
            $table->enum('tingkat_keparahan', ['ringan', 'sedang', 'parah'])->nullable();
            
            // Tindakan & Saran section
            $table->json('tindakan_dilakukan')->nullable();
            $table->text('keterangan_tindakan')->nullable();
            $table->text('saran_treatment')->nullable();
            
            // Kontrol ulang
            $table->boolean('kontrol_ulang')->default(false);
            $table->dateTime('jadwal_kontrol_ulang')->nullable();
            $table->string('nomor_kontrol', 20)->nullable();
            $table->text('catatan_kontrol')->nullable();;
                        
            $table->timestamps();

            $table->foreign('id_pasien')->references('id')->on('pasiens')->onDelete('cascade');
            $table->foreign('id_pendaftaran')->references('id')->on('pendaftarans')->onDelete('cascade');
            $table->foreign('id_layanan')->references('id')->on('layanan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekmed_beautycares');
    }
};
