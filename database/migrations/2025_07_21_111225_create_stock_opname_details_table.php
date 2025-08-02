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
        Schema::create('stock_opname_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('stock_opname_id')->constrained()->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained()->onUpdate('cascade');
            $table->foreignId('satuan_id')->constrained()->onUpdate('cascade');
            $table->decimal('stock', 10, 2)->default(0);
            $table->decimal('minstock', 10, 2)->default(0);
            $table->decimal('before_stock', 10, 2)->nullable()->default(0);
            $table->decimal('before_minstock', 10, 2)->nullable()->default(0);
            $table->unsignedBigInteger('before_satuan_id')->nullable();
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
        Schema::dropIfExists('stock_opnames');
    }
};
