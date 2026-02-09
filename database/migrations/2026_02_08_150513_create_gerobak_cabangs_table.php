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
        Schema::create('gerobak_cabangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('gerobak_id')->constrained()->onUpdate('cascade');
            $table->foreignId('mitra_id')->constrained()->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerobak_cabangs');
    }
};
