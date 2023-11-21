<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAssetDataChangeTypeUmur extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_data', function (Blueprint $table) {
            $table->decimal('umur_manfaat_fisikal', 6, 2)->change();
            $table->decimal('umur_manfaat_komersial', 6, 2)->change();
            $table->date('tanggal_awal_depresiasi')->default(now());
            $table->date('tanggal_akhir_depresiasi')->default(now());
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
