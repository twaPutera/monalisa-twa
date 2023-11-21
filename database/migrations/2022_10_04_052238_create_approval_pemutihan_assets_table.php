<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalPemutihanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_pemutihan_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_pemutihan_asset');
            $table->foreign('id_pemutihan_asset')->references('id')->on('pemutihan_assets');
            $table->uuid('guid_approval');
            $table->date('tanggal_approval');
            $table->string('status_approval');
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('approval_pemutihan_assets');
    }
}
