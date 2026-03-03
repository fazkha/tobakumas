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
        Schema::create('pc_petty_cashes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()->onUpdate('cascade');
            $table->unsignedBigInteger('dropping_id')->nullable();
            $table->date('tanggal');
            $table->unsignedBigInteger('nominal')->nullable();
            $table->unsignedTinyInteger('inout')->default(1);
            $table->unsignedTinyInteger('approved_ma')->default(0);
            $table->unsignedTinyInteger('approved_fin')->default(0);
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
        Schema::dropIfExists('pc_petty_cashes');
    }
};
