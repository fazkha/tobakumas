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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('gudang_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('petugas_1_id')->nullable();
            $table->unsignedBigInteger('petugas_2_id')->nullable();
            $table->unsignedBigInteger('tanggungjawab_id')->nullable();
            $table->string('keterangan')->nullable();
            $table->unsignedTinyInteger('approved')->default(0);
            $table->string('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
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
        Schema::dropIfExists('stock_opnames');
    }
};
