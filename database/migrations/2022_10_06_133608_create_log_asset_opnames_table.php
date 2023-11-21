<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogAssetOpnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_asset_opnames', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_asset_data');
            $table->foreign('id_asset_data')->references('id')->on('asset_data');
            $table->date('tanggal_opname');
            $table->string('status_awal');
            $table->string('status_akhir');
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
        Schema::dropIfExists('log_asset_opnames');
    }
}
