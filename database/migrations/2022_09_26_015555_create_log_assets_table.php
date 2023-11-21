<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('asset_id');
            $table->foreign('asset_id')->references('id')->on('asset_data');
            $table->text('log');
            $table->string('created_by');
            $table->timestamps();
        });

        Schema::table('asset_data', function (Blueprint $table) {
            $table->tinyInteger('is_sparepart')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_assets');
    }
}
