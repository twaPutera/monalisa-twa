<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogPenambahanInventoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('detail_inventori_data');
        Schema::table('inventori_data', function (Blueprint $table) {
            $table->dropColumn(['harga_beli']);
        });
        Schema::create('log_penambahan_inventori', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_inventori');
            $table->foreign('id_inventori')->references('id')->on('inventori_data');
            $table->integer('jumlah');
            $table->date('tanggal');
            $table->decimal('harga_beli', 12, 2);
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
        Schema::dropIfExists('log_penambahan_inventori');
    }
}
