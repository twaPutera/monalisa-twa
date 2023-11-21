<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAssetDataSetNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_data', function (Blueprint $table) {
            $table->uuid('id_vendor')->nullable()->change();
            $table->uuid('id_lokasi')->nullable()->change();
            $table->uuid('ownership')->nullable()->change();
            $table->uuid('id_kelas_asset')->nullable()->change();
            $table->string('no_seri')->nullable()->change();
            $table->string('status_akunting', 50)->nullable();
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
