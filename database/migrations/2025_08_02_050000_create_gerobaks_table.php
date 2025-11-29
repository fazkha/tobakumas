<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gerobaks', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->nullable();
            $table->string('nama');
            $table->tinyInteger('isactive')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gerobaks');
    }
};
