<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableDropUniqueMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_kategori_assets', function (Blueprint $table) {
            $table->dropUnique(['kode_group'])->change();
        });

        Schema::table('kategori_assets', function (Blueprint $table) {
            $table->dropUnique(['kode_kategori']);
        });

        Schema::table('kategori_inventories', function (Blueprint $table) {
            $table->dropUnique(['kode_kategori']);
        });

        Schema::table('kategori_services', function (Blueprint $table) {
            $table->dropUnique(['kode_service']);
        });

        Schema::table('kelas_assets', function (Blueprint $table) {
            $table->dropUnique(['no_akun']);
        });

        Schema::table('lokasis', function (Blueprint $table) {
            $table->dropUnique(['kode_lokasi']);
        });

        Schema::table('satuan_assets', function (Blueprint $table) {
            $table->dropUnique(['kode_satuan']);
        });

        Schema::table('satuan_inventories', function (Blueprint $table) {
            $table->dropUnique(['kode_satuan']);
        });

        Schema::table('vendors', function (Blueprint $table) {
            $table->dropUnique(['kode_vendor']);
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
