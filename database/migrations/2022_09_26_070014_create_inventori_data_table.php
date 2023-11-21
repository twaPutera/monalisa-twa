<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoriDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventori_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_kategori_inventori');
            $table->foreign('id_kategori_inventori')->references('id')->on('kategori_inventories');
            $table->foreignUuid('id_satuan_inventori');
            $table->foreign('id_satuan_inventori')->references('id')->on('satuan_inventories');
            $table->string('kode_inventori', 50)->unique();
            $table->string('nama_inventori');
            $table->decimal('stok', 10, 5);
            $table->text('deskripsi_inventori');
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
        Schema::dropIfExists('inventori_data');
    }
}
