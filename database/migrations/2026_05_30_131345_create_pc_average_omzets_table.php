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
        Schema::create('pc_average_omzets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->unsignedBigInteger('target_id');
            $table->foreign('target_id')->references('id')->on('pc_target_bonuses')->onUpdate('cascade');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedSmallInteger('bulan');
            $table->decimal('hpp', 8, 4)->default(0);
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
        Schema::dropIfExists('pc_average_omzets');
    }
};
