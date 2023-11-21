<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailRequestInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_request_inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('request_inventori_id');
            $table->foreign('request_inventori_id')->references('id')->on('request_inventories');
            $table->foreignUuid('inventori_id');
            $table->foreign('inventori_id')->references('id')->on('inventori_data');
            $table->integer('qty');
            $table->integer('realisasi');
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
        Schema::dropIfExists('detail_request_inventories');
    }
}
