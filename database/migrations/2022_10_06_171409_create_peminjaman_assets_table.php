<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeminjamanAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjaman_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('guid_peminjam_asset');
            $table->text('json_peminjam_asset');
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_pengembalian');
            $table->text('alasan_peminjaman');
            $table->string('status', 50);
            $table->uuid('created_by');
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
        Schema::dropIfExists('peminjaman_assets');
    }
}
