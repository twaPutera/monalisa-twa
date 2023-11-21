<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOpnameAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_asset_opnames', function (Blueprint $table) {
            $table->string('kode_opname')->after('tanggal_opname');
            $table->uuid('lokasi_sebelumnya')->after('tanggal_opname');
            $table->foreignUuid('id_lokasi')->after('tanggal_opname');
            $table->foreign('id_lokasi')->references('id')->on('lokasis');
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
