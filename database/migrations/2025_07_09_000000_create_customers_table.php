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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('customer_group_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('propinsi_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('kabupaten_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('kecamatan_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->unsignedBigInteger('branch_link_id')->nullable();
            $table->string('kode');
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->date('tanggal_gabung')->nullable();
            $table->string('kontak_nama')->nullable();
            $table->string('kontak_telpon')->nullable();
            $table->string('keterangan')->nullable();
            $table->tinyInteger('isactive')->default(0);
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
        Schema::dropIfExists('customers');
    }
};
