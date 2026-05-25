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
        Schema::create('pc_target_bonuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('tipegaji')->comment('1: Gaji 2500, 2: Gaji 3000, 3: Gaji 2500 Romadon, 4: Gaji 3000 Romadon');
            $table->double('hpp');
            $table->unsignedSmallInteger('r2omzet');
            $table->unsignedMediumInteger('omzet');
            $table->unsignedSmallInteger('bonus');
            $table->unsignedTinyInteger('isactive')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pc_target_bonuses');
    }
};
