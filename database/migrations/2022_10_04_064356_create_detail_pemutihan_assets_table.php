<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPemutihanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pemutihan_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_pemutihan_asset');
            $table->foreign('id_pemutihan_asset')->references('id')->on('pemutihan_assets');
            $table->uuid('id_asset_data');
            $table->foreign('id_asset_data')->references('id')->on('asset_data');
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
        Schema::dropIfExists('detail_pemutihan_assets');
    }
}
