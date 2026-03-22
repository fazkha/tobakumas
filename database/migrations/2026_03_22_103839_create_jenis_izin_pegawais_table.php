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
        Schema::create('jenis_izin_pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->nullable();
            $table->string('nama', 50);
            $table->string('keterangan', 200)->nullable();
            $table->unsignedTinyInteger('isactive')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_izin_pegawais');
    }
};
