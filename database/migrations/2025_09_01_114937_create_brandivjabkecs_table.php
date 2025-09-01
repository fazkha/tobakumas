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
        Schema::create('brandivjabkecs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brandivjab_id')->constrained()->onUpdate('cascade');
            $table->foreignId('propinsi_id')->constrained()->onUpdate('cascade');
            $table->foreignId('kabupaten_id')->constrained()->onUpdate('cascade');
            $table->foreignId('kecamatan_id')->constrained()->onUpdate('cascade');
            $table->string('keterangan')->nullable();
            $table->unsignedTinyInteger('isactive')->default(0);
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
        Schema::dropIfExists('brandivjabkecs');
    }
};
