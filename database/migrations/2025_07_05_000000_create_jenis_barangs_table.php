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
        Schema::create('jenis_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('keterangan')->nullable();
            $table->tinyInteger('sisa_produksi')->default(0);
            $table->tinyInteger('isactive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_barangs');
    }
};
