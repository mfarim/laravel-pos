<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenjualanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_penjualan');
            $table->unsignedInteger('id_barang');
            $table->integer('jumlah_jual');
            $table->integer('harga_jual_akhir');
            $table->integer('sub_total_harga');
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
        Schema::dropIfExists('penjualan_details');
    }
}
