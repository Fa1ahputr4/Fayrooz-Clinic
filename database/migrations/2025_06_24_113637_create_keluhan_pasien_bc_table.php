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
        Schema::create('keluhan_pasien_bc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rekmed_bc_id');
            $table->unsignedBigInteger('keluhan_id');
            $table->timestamps();

            $table->foreign('rekmed_bc_id')->references('id')->on('rekmed_beautycares')->onDelete('cascade');
            $table->foreign('keluhan_id')->references('id')->on('keluhans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluhan_pasien_bc');
    }
};
