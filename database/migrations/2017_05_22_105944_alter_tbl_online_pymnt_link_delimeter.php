<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblOnlinePymntLinkDelimeter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_online_pymnt_link', function (Blueprint $table) {
            $table->string('link_delimeter')->after('link_img_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_online_pymnt_link', function (Blueprint $table) {
            //
        });
    }
}
