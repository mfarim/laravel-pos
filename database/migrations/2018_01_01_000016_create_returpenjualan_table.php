<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturpenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returpenjualan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_retur');
            $table->date('tanggal');
            $table->string('nama_penjaga');
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('returpenjualan');
    }
}
