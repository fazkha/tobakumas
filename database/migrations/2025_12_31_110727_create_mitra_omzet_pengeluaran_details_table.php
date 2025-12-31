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
        Schema::create('mitra_op_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_omzet_pengeluaran_id')->constrained()->onUpdate('cascade');
            $table->string('keterangan')->nullable();
            $table->unsignedBigInteger('harga')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_omzet_pengeluaran_details');
    }
};
