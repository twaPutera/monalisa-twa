<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerencanaanServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perencanaan_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_asset_data');
            $table->foreign('id_asset_data')->references('id')->on('asset_data');
            $table->date('tanggal_perencanaan');
            $table->string('status', 100);
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
        Schema::dropIfExists('perencanaan_services');
    }
}
