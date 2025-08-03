<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Null_;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade');
            $table->foreignId('customer_id')->constrained()->onUpdate('cascade');
            $table->date('tanggal')->nullable();
            $table->string('no_order')->nullable();
            $table->tinyInteger('hke')->nullable();
            $table->integer('biaya_angkutan')->default(0);
            $table->integer('total_harga')->default(0);
            $table->decimal('pajak', 5, 2)->default(0);
            $table->tinyInteger('tunai')->default(0);
            $table->tinyInteger('isactive')->default(0);
            $table->text('kalimat')->nullable();
            $table->tinyInteger('approved')->default(0);
            $table->string('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
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
        Schema::dropIfExists('sale_orders');
    }
};
