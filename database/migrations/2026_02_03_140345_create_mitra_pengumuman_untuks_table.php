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
        Schema::create('mitra_pengumuman_untuks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mitra_pengumuman_id');
            $table->foreign('mitra_pengumuman_id')->references('id')->on('mitra_pengumumans')->onUpdate('cascade');
            $table->foreignId('jabatan_id')->constrained()->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_pengumuman_untuks');
    }
};
