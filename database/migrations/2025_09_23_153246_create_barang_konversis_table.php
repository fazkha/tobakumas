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
        Schema::create('barang_konversis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained()->onUpdate('cascade');
            $table->char('operator', 1)->default(1);
            $table->integer('bilangan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_konversis');
    }
};
