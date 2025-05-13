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
        Schema::create('stok_umum', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('barang_masuk_id');
            $table->integer('jumlah_tersedia')->default(0);
            $table->boolean('is_delete')->default(0);
            $table->timestamps();

            $table->foreign('barang_masuk_id')
                  ->references('id')->on('barang_masuk')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_umum');
    }
};
