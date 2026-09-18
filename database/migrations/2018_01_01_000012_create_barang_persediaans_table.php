<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarangPersediaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang_persediaans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_barang');
            $table->unsignedInteger('id_supplier')->nullable();
            $table->string('detail')->nullable();
            $table->date('tanggal_beli')->nullable();
            $table->date('tanggal_jual')->nullable();
            $table->integer('stok_beli')->default(0);
            $table->integer('stok_jual')->default(0);
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
        Schema::dropIfExists('barang_persediaans');
    }
}
