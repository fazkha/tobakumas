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
        Schema::create('delivery_order_mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('delivery_order_id')->constrained()->onUpdate('cascade');
            $table->foreignId('sale_order_id')->constrained()->onUpdate('cascade');
            $table->foreignId('sale_order_mitra_id')->constrained()->onUpdate('cascade');
            $table->foreignId('paket_id')->constrained()->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained()->onUpdate('cascade');
            $table->foreignId('satuan_id')->constrained()->onUpdate('cascade');
            $table->unsignedTinyInteger('kuantiti')->default(0);
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
        Schema::dropIfExists('delivery_order_mitras');
    }
};
