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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propinsi_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('kabupaten_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('kecamatan_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->string('kode');
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('kodepos', 50)->nullable();
            $table->string('keterangan')->nullable();
            $table->string('email')->nullable();
            $table->string('latitude', 100)->nullable();
            $table->string('longitude', 100)->nullable();
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
        Schema::dropIfExists('branch');
    }
};
