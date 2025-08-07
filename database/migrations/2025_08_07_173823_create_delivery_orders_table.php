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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_order_id')->constrained()->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->date('tanggal')->nullable();
            $table->string('alamat')->nullable();
            $table->unsignedBigInteger('petugas_1_id')->nullable();
            $table->unsignedBigInteger('petugas_2_id')->nullable();
            $table->unsignedBigInteger('pengirim_id')->nullable();
            $table->unsignedBigInteger('tanggungjawab_id')->nullable();
            $table->string('keterangan')->nullable();
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
        Schema::dropIfExists('delivery_orders');
    }
};
