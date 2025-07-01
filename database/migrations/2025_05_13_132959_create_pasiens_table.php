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
        Schema::create('pasien', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_rm')->unique();
            $table->string('nik', 16)->nullable();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->integer('usia')->nullable();
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', '-'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->enum('status_pernikahan', ['belum_menikah', 'menikah', 'cerai_hidup', 'cerai_mati'])->nullable();
            $table->text('catatan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();         
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
