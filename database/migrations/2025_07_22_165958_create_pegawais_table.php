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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->string('nik', 50)->nullable();
            $table->string('nip', 50)->nullable();
            $table->string('alamat_asal')->nullable();
            $table->string('alamat_tinggal');
            $table->string('telpon');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->char('kelamin', 1)->default('L');
            $table->string('keterangan')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('isactive')->default(0);
            $table->string('gambar_1_lokasi')->nullable();
            $table->string('gambar_1_nama')->nullable();
            $table->string('gambar_2_lokasi')->nullable();
            $table->string('gambar_2_nama')->nullable();
            $table->string('gambar_3_lokasi')->nullable();
            $table->string('gambar_3_nama')->nullable();
            $table->string('gambar_4_lokasi')->nullable();
            $table->string('gambar_4_nama')->nullable();
            $table->string('gambar_5_lokasi')->nullable();
            $table->string('gambar_5_nama')->nullable();
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
        Schema::dropIfExists('pegawais');
    }
};
