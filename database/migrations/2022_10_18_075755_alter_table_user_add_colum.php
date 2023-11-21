<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUserAddColum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->uuid('id')->primary()->change();
            // $table->uuid('guid')->after('email')->nullable();
            // $table->string('username_sso')->nullable()->after('guid');
            // $table->string('role', 50)->default('user')->after('email');
            // $table->string('is_active', 2)->default('1')->after('role');
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
