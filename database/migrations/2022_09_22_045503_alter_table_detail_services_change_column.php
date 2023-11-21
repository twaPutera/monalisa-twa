<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDetailServicesChangeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_services', function (Blueprint $table) {
            $table->dropColumn(['biaya_service']);
            $table->renameColumn('kondisi_asset_sebelum', 'permasalahan');
            $table->renameColumn('kondisi_asset_sesudah', 'tindakan');
            $table->after('kondisi_asset_sesudah', function ($table) {
                $table->text('catatan')->nullable();
            });
            $table->after('id_asset_data', function ($table) {
                $table->foreignUuid('id_lokasi');
                $table->foreign('id_lokasi')->references('id')->on('lokasis');
            });
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
