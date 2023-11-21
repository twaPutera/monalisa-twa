<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_data', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_vendor')->nullable();
            $table->foreign('id_vendor')->references('id')->on('vendors');
            $table->foreignUuid('id_lokasi');
            $table->foreign('id_lokasi')->references('id')->on('lokasis');
            $table->foreignUuid('id_kelas_asset');
            $table->foreign('id_kelas_asset')->references('id')->on('kelas_assets');
            $table->foreignUuid('id_kategori_asset');
            $table->foreign('id_kategori_asset')->references('id')->on('kategori_assets');
            $table->foreignUuid('id_satuan_asset');
            $table->foreign('id_satuan_asset')->references('id')->on('satuan_assets');
            $table->string('kode_asset');
            $table->string('deskripsi', 300);
            $table->date('tanggal_perolehan');
            $table->integer('nilai_perolehan');
            $table->string('jenis_penerimaan');
            $table->string('ownership')->nullable();
            $table->date('tgl_register');
            $table->string('register_oleh');
            $table->string('no_memo_surat', 50)->nullable();
            $table->string('no_po', 50)->nullable();
            $table->string('no_sp3', 50)->nullable();
            $table->string('status_kondisi', 50)->nullable();
            $table->string('no_seri', 50)->nullable();
            $table->text('spesifikasi');
            $table->integer('nilai_buku_asset');
            $table->enum('status_peminjaman', [0, 1])->nullable();
            $table->string('nama_peminjam')->nullable();
            $table->integer('nilai_depresiasi');
            $table->smallInteger('umur_manfaat_fisikal')->nullable();
            $table->smallInteger('umur_manfaat_komersial')->nullable();
            $table->char('created_by', 36);
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
        Schema::dropIfExists('asset_data');
    }
}
