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
        Schema::create('subjenis_barangs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_barang_id')->nullable();
            $table->string('nama')->nullable();
            $table->string('keterangan')->nullable();
            $table->tinyInteger('isactive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjenis_barangs');
    }
};
