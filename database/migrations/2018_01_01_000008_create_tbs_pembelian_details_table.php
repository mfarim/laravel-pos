<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbsPembelianDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbs_pembelian_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_pembelian')->nullable();
            $table->unsignedInteger('id_barang');
            $table->integer('jumlah_beli');
            $table->integer('sub_total_harga');
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
        Schema::dropIfExists('tbs_pembelian_details');
    }
}
