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
        Schema::create('mitra_kritik_sarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->date('tanggal');
            $table->string('jenis', 10)->default('Kritik');
            $table->string('judul', 200)->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->date('tanggal_jawab')->nullable();
            $table->string('keterangan_jawab', 200)->nullable();
            $table->unsignedTinyInteger('isactive')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_kritik_sarans');
    }
};
