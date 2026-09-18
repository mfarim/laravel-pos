<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbsPenjualanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbs_penjualan_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_penjualan')->nullable();
            $table->unsignedInteger('id_barang');
            $table->integer('harga_jual_akhir')->nullable();
            $table->integer('jumlah_jual');
            $table->integer('sub_total_harga');
            $table->string('status')->nullable();
            $table->unsignedInteger('id_supplier')->nullable();
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
        Schema::dropIfExists('tbs_penjualan_details');
    }
}
