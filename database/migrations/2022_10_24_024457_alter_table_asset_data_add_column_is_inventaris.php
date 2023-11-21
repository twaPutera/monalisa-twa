<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAssetDataAddColumnIsInventaris extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset_data', function (Blueprint $table) {
            $table->boolean('is_inventaris')->default(0);
            $table->decimal('nilai_perolehan', 20, 2)->default(1)->change();
            $table->decimal('nilai_buku_asset', 20, 2)->default(1)->change();
            $table->decimal('nilai_depresiasi', 20, 2)->default(1)->change();
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
