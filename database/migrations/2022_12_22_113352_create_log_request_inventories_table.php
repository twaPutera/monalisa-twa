<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogRequestInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_request_inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('request_inventori_id');
            $table->foreign('request_inventori_id')->references('id')->on('request_inventories');
            $table->text('message');
            $table->string('created_by');
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
        Schema::dropIfExists('log_request_inventories');
    }
}
