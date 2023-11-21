<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemindahanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemindahan_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('guid_penerima_asset');
            $table->uuid('guid_penyerah_asset');
            $table->text('json_penerima_asset');
            $table->text('json_penyerah_asset');
            $table->date('tanggal_pemindahan');
            $table->string('status', 50);
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
        Schema::dropIfExists('pemindahan_assets');
    }
}
