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
        Schema::create('t_stok', function (Blueprint $table) {
            $table->id('stok_id', 20);
            $table->unsignedBigInteger('barang_id')->index(); //indexing untuk ForeignKey
            $table->unsignedBigInteger('user_id')->index(); //indexing untuk ForeignKey
            $table->unsignedBigInteger('supplier_id')->index(); // Menambahkan supplier_id
            $table->dateTime('stok_tanggal');
            $table->integer('stok_jumlah');
            $table->timestamps();

            // Definisi Foreign Keys
            $table->foreign('barang_id')->references('barang_id')->on('m_barang');
            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('supplier_id')->references('supplier_id')->on('m_supplier'); // Menambahkan foreign key supplier_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_stok');
    }
};
