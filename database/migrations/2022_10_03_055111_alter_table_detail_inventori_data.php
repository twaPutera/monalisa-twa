<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDetailInventoriData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_inventori_data', function (Blueprint $table) {
            $table->dropForeign('detail_inventori_data_id_lokasi_foreign');
            $table->dropColumn(['id_lokasi', 'stok', 'keterangan']);
        });

        Schema::table('detail_inventori_data', function (Blueprint $table) {
            $table->string('no_memo', 100)->nullable();
            $table->integer('jumlah');
            $table->string('status', 50);
            $table->date('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
