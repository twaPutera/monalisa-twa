<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogServiceAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_service_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_service');
            $table->foreign('id_service')->references('id')->on('services');
            $table->string('message_log');
            $table->string('status');
            $table->uuid('created_by');
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
        Schema::dropIfExists('log_service_assets');
    }
}
