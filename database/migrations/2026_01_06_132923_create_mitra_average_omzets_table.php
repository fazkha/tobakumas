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
        Schema::create('mitra_average_omzets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->string('minggu', 10);
            $table->unsignedInteger('rata2')->default(0);
            $table->string('trend', 10)->nullable();
            $table->unsignedInteger('pct')->nullable();
            $table->unsignedInteger('bonus')->default(0);
            $table->string('trend_bonus', 10)->nullable();
            $table->unsignedInteger('pct_bonus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mitra_average_omzets');
    }
};
