<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_asset_data');
            $table->foreign('id_asset_data')->references('id')->on('asset_data');
            $table->foreignUuid('id_service');
            $table->foreign('id_service')->references('id')->on('services');
            $table->text('kondisi_asset_sebelum');
            $table->text('kondisi_asset_sesudah')->nullable();
            $table->decimal('biaya_service', 12, 2);
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
        Schema::dropIfExists('detail_services');
    }
}
