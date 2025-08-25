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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('purchase_order_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained()->onUpdate('cascade');
            $table->foreignId('satuan_id')->constrained()->onUpdate('cascade');
            $table->integer('harga_satuan')->default(0);
            $table->decimal('kuantiti', 10, 2)->default(0.00);
            $table->decimal('pajak', 5, 2)->default(0);
            $table->string('keterangan')->nullable();
            $table->tinyInteger('approved')->default(0);
            $table->unsignedTinyInteger('isaccepted')->default(0);
            $table->unsignedTinyInteger('isreturned')->default(0);
            $table->unsignedBigInteger('satuan_terima_id')->nullable();
            $table->decimal('kuantiti_terima', 10, 2)->nullable();
            $table->string('keterangan_terima')->nullable();
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
        Schema::dropIfExists('purchase_order_details');
    }
};
