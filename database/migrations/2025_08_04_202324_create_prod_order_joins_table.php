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
        Schema::create('prod_order_joins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prod_order_id')->constrained()->onUpdate('cascade');
            $table->foreignId('sale_order_id')->constrained()->onUpdate('cascade');
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod_order_joins');
    }
};
