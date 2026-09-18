<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePembelianDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_pembelian');
            $table->unsignedInteger('id_barang');
            $table->date('tanggal')->nullable();
            $table->integer('jumlah_beli');
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
        Schema::dropIfExists('pembelian_details');
    }
}
