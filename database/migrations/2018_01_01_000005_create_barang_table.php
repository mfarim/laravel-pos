<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->unsignedInteger('idbarang_kategori');
            $table->unsignedInteger('idbarang_unit');
            $table->integer('stok')->default(0);
            $table->integer('diskon')->default(0);
            $table->integer('harga_jual_akhir');
            $table->string('createdby')->nullable();
            $table->string('updatedby')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang');
    }
}
