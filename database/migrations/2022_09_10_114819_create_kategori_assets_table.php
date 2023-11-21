<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKategoriAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_group_kategori_asset');
            $table->foreign('id_group_kategori_asset')->references('id')->on('group_kategori_assets');
            $table->string('kode_kategori')->unique();
            $table->string('nama_kategori');
            $table->smallInteger('umur_asset');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori_assets');
    }
}
