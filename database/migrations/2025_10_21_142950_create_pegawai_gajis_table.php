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
        Schema::create('pegawai_gajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained()->onUpdate('cascade');
            $table->unsignedInteger('gaji_pokok')->nullable();
            $table->string('t1_keterangan', 50)->nullable();
            $table->unsignedInteger('t1_gaji')->nullable();
            $table->string('t2_keterangan', 50)->nullable();
            $table->unsignedInteger('t2_gaji')->nullable();
            $table->string('t3_keterangan', 50)->nullable();
            $table->unsignedInteger('t3_gaji')->nullable();
            $table->string('rek_nama_bank', 50)->nullable();
            $table->string('rek_nomor', 50)->nullable();
            $table->string('rek_nama_pemilik', 50)->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_gajis');
    }
};
