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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('gudang_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('jenis_barang_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('subjenis_barang_id')->constrained()->onUpdate('cascade')->onDelete('set null');
            $table->unsignedBigInteger('satuan_beli_id')->nullable();
            $table->unsignedBigInteger('satuan_jual_id')->nullable();
            $table->unsignedBigInteger('satuan_stock_id')->nullable();
            $table->foreign('satuan_beli_id')->references('id')->on('satuans')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('satuan_jual_id')->references('id')->on('satuans')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('satuan_stock_id')->references('id')->on('satuans')->onUpdate('cascade')->onDelete('set null');
            $table->string('nama')->nullable();
            $table->string('merk')->nullable();
            $table->string('keterangan')->nullable();
            $table->integer('harga_satuan')->nullable();
            $table->integer('harga_satuan_jual')->nullable();
            $table->decimal('stock', 10, 2)->default(0);
            $table->decimal('minstock', 10, 2)->default(0);
            $table->string('lokasi')->nullable();
            $table->string('gambar')->nullable();
            $table->string('gambar_nama_awal')->nullable();
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
        Schema::dropIfExists('barangs');
    }
};
