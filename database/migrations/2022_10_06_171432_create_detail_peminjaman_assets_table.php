<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPeminjamanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_peminjaman_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_peminjaman_asset')->references('id')->on('peminjaman_assets');
            $table->foreignUuid('id_asset')->references('id')->on('asset_data');
            $table->text('json_asset_data');
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
        Schema::dropIfExists('detail_peminjaman_assets');
    }
}
