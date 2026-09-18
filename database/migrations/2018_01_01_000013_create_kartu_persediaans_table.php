<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKartuPersediaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kartu_persediaans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_barang');
            $table->string('keterangan')->nullable();
            $table->string('no_transaksi')->nullable();
            $table->date('tanggal');
            $table->integer('masuk')->default(0);
            $table->integer('keluar')->default(0);
            $table->integer('sisa')->default(0);
            $table->string('createdby')->nullable();
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
        Schema::dropIfExists('kartu_persediaans');
    }
}
