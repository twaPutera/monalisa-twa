<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailInventoriDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_inventori_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_inventori');
            $table->foreign('id_inventori')->references('id')->on('inventori_data');
            $table->foreignUuid('id_lokasi');
            $table->foreign('id_lokasi')->references('id')->on('lokasis');
            $table->integer('stok');
            $table->text('keterangan');
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
        Schema::dropIfExists('detail_inventori_data');
    }
}
