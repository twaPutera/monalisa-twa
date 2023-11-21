<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePerencanaanServicesAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perencanaan_services', function (Blueprint $table) {
            $table->string('keterangan')->after('tanggal_perencanaan');
            $table->foreignUuid('id_log_opname')->after('tanggal_perencanaan');
            $table->foreign('id_log_opname')->references('id')->on('log_asset_opnames');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
