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
        Schema::create('delivery_officer_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_officer_id')->constrained()->onUpdate('cascade');
            $table->foreignId('delivery_order_id')->constrained()->onUpdate('cascade');
            $table->foreignId('sale_order_id')->constrained()->onUpdate('cascade');
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
