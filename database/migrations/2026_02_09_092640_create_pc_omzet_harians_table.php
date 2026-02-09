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
        Schema::create('pc_omzet_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained()->onUpdate('cascade');
            $table->date('tanggal');
            $table->unsignedBigInteger('omzet')->nullable();
            $table->decimal('sisa_adonan', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_omzet_harians');
    }
};
