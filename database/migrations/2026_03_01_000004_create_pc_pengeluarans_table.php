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
        Schema::create('pc_pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->foreignId('jenis_pengeluaran_cabang_id')->constrained()->onUpdate('cascade');
            $table->date('tanggal');
            $table->unsignedBigInteger('harga')->nullable();
            $table->string('image_lokasi', 200)->nullable();
            $table->string('image_nama', 100)->nullable();
            $table->string('image_type', 50)->nullable();
            $table->unsignedTinyInteger('approved')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_pengeluarans');
    }
};
