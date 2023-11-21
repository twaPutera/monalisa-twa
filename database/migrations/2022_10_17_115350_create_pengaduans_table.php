<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengaduansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_asset_data');
            $table->foreign('id_asset_data')->references('id')->on('asset_data');
            $table->date('tanggal_pengaduan');
            $table->text('catatan_pengaduan');
            $table->text('catatan_admin')->nullable();
            $table->string('status_pengaduan');
            $table->string('created_by');
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
        Schema::dropIfExists('pengaduans');
    }
}
