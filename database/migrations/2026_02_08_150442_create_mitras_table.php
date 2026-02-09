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
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gerobak_id')->constrained()->onUpdate('cascade');
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->string('nik', 50)->nullable();
            $table->string('alamat_tinggal');
            $table->string('telpon');
            $table->date('tanggal_lahir')->nullable();
            $table->char('kelamin', 1)->default('L');
            $table->string('keterangan')->nullable();
            $table->string('email')->nullable();
            $table->tinyInteger('isactive')->default(0);
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
        Schema::dropIfExists('mitras');
    }
};
