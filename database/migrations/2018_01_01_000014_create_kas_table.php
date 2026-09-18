<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_kas')->nullable();
            $table->unsignedInteger('id_penjualan')->nullable();
            $table->unsignedInteger('id_pembelian')->nullable();
            $table->date('tanggal');
            $table->string('keterangan')->nullable();
            $table->string('ref')->nullable();
            $table->integer('debit')->default(0);
            $table->integer('kredit')->default(0);
            $table->integer('saldo')->default(0);
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
        Schema::dropIfExists('kas');
    }
}
