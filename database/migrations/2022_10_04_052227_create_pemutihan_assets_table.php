<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePemutihanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemutihan_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('guid_manager');
            $table->text('json_manager');
            $table->date('tanggal');
            $table->string('no_memo', 50)->nullable();
            $table->string('status');
            $table->string('created_by', 50);
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
        Schema::dropIfExists('pemutihan_assets');
    }
}
