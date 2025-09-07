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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade')->onDelete('set null');
            $table->string('tanggal', 20)->nullable();
            $table->string('waktu')->nullable();
            $table->integer('waktu_detik')->default(0);
            $table->string('jarak')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->unique(['branch_id', 'user_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
