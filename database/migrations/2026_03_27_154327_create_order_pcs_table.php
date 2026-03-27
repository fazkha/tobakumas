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
        Schema::create('order_pcs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pc_id')->constrained('users');
            $table->foreignId('branch_id')->constrained();
            $table->unsignedTinyInteger('hke');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            // $table->foreignId('barang_id')->constrained();
            // $table->foreignId('satuan_id')->constrained();
            // $table->decimal('qty', 8, 2)->default(0);
            // $table->string('keterangan', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_pcs');
    }
};
