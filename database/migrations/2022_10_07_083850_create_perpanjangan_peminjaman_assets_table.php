<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerpanjanganPeminjamanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perpanjangan_peminjaman_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_peminjaman_asset')->references('id')->on('peminjaman_assets');
            $table->date('tanggal_expired_sebelumnya');
            $table->date('tanggal_expired_perpanjangan');
            $table->text('alasan_perpanjangan')->nullable();
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
        Schema::dropIfExists('perpanjangan_peminjaman_assets');
    }
}
