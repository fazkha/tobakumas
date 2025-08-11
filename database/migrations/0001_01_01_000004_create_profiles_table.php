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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('nohp')->nullable();
            $table->string('noktp')->nullable();
            $table->string('alamat')->nullable();
            $table->string('daerah_asal')->nullable();
            $table->timestamp('tanggal_lahir')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('profile_image')->nullable();
            $table->tinyInteger('islead')->default(0);
            $table->tinyInteger('iscolead')->default(0);
            $table->tinyInteger('isactive')->default(0);
            $table->string('app_version')->nullable();
            $table->timestamp('tanggal_gabung')->nullable();
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
        Schema::dropIfExists('profiles');
    }
};
