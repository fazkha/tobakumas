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
        Schema::create('prod_order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prod_order_id')->constrained()->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained()->onUpdate('cascade');
            $table->foreignId('satuan_id')->constrained()->onUpdate('cascade');
            $table->decimal('kuantiti', 10, 2)->default(0.00);
            $table->tinyInteger('approved')->default(0);
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
        Schema::dropIfExists('prod_detail_orders');
    }
};
