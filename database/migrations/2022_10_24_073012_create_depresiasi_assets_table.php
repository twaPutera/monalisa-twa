<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepresiasiAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depresiasi_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_asset_data');
            $table->date('tanggal_depresiasi');
            $table->decimal('nilai_depresiasi', 20, 2)->default(0);
            $table->decimal('nilai_buku_awal', 20, 2)->default(0);
            $table->decimal('nilai_buku_akhir', 20, 2)->default(0);
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
        Schema::dropIfExists('depresiasi_assets');
    }
}
