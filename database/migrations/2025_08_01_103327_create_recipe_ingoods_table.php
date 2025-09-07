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
        Schema::create('recipe_ingoods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('barang_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('satuan_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->decimal('kuantiti', 10, 2)->default(0.00);
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
        Schema::dropIfExists('recipe_ingoods');
    }
};
