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
        Schema::create('konversis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('satuan_id')->constrained()->onUpdate('cascade');
            $table->unsignedBigInteger('satuan2_id');
            $table->foreign('satuan2_id')->references('id')->on('satuans')->onUpdate('cascade');
            $table->char('operator', 1)->default(1);
            $table->integer('bilangan')->default(0);
            $table->tinyInteger('isactive')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konversis');
    }
};
