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
        Schema::create('resigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->date('tanggal');
            $table->string('keterangan', 255)->nullable();
            $table->string('tanggapan_pc', 255)->nullable();
            $table->string('tanggapan_hrd', 255)->nullable();
            $table->unsignedTinyInteger('approved_hrd')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resigns');
    }
};
