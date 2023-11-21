<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApprovalPemindahanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approval_pemindahan_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_pemindahan_asset')->references('id')->on('pemindahan_assets');
            $table->uuid('guid_approver');
            $table->date('tanggal_approval');
            $table->string('is_approve', 2)->nullable();
            $table->text('keterangan');
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
        Schema::dropIfExists('approval_pemindahan_assets');
    }
}
