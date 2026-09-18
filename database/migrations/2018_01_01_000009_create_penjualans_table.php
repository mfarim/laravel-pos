<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenjualansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_penjualan');
            $table->string('pelanggan')->nullable();
            $table->date('tanggal');
            $table->integer('total_harga_jual');
            $table->integer('total_bayar')->nullable();
            $table->integer('total_kembalian')->nullable();
            $table->string('tipe_pembayaran')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('penjualans');
    }
}
