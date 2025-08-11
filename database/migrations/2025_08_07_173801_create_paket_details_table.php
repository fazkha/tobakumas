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
        Schema::create('paket_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained()->onUpdate('cascade');
            $table->foreignId('barang_id')->constrained()->onUpdate('cascade');
            $table->foreignId('satuan_id')->constrained()->onUpdate('cascade');
            $table->decimal('kuantiti')->default(0);
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
        Schema::dropIfExists('paket_details');
    }
};
