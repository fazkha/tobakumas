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
        Schema::create('mitra_pengumumans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('judul', 200)->nullable();
            $table->string('keterangan', 200)->nullable();
            $table->string('lokasi')->nullable();
            $table->string('gambar')->nullable();
            $table->unsignedTinyInteger('isactive')->default(0);
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
        Schema::dropIfExists('mitra_pengumumans');
    }
};
