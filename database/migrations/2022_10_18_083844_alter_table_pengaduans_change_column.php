<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablePengaduansChangeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->foreignUuid('id_lokasi')->after('id_asset_data');
            $table->foreign('id_lokasi')->references('id')->on('lokasis');
            $table->uuid('id_asset_data')->nullable()->change();
        });
        Schema::dropIfExists('approval_pemutihan_assets');
        Schema::dropIfExists('approval_pemindahan_assets');
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
