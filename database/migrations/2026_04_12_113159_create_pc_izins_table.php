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
        Schema::create('pc_permintaan_izins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('pegawai_id')->constrained()->onUpdate('cascade');
            $table->foreignId('jenis_izin_pegawai_id')->constrained()->onUpdate('cascade');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->string('keterangan', 255)->nullable();
            $table->unsignedTinyInteger('approved_hrd')->default(0);
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
        Schema::dropIfExists('pc_permintaan_izins');
    }
};
