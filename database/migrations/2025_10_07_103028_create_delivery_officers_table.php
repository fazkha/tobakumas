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
        Schema::create('delivery_officers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('pegawai_id')->constrained()->onUpdate('cascade');
            $table->date('tanggal')->nullable();
            $table->string('no_order')->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedTinyInteger('isdone')->default(0);
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
        Schema::dropIfExists('delivery_officers');
    }
};
