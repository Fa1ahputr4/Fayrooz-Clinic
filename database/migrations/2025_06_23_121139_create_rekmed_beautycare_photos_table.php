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
        Schema::create('rekmed_beautycare_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('history_id')->constrained('rekmed_beautycares')->onDelete('cascade');
            $table->enum('tipe', ['before', 'after']);
            $table->string('file_path');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekmed_beautycare_photos');
    }
};
