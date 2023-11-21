<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogPengaduanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_pengaduan_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_pengaduan');
            $table->foreign('id_pengaduan')->references('id')->on('pengaduans');
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
        Schema::dropIfExists('log_pengaduan_assets');
    }
}
