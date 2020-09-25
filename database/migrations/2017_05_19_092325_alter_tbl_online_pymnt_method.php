<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblOnlinePymntMethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_online_pymnt_method', function (Blueprint $table) {
            $table->integer('method_shop_id')->after('method_id');
            $table->dateTime('method_date_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_online_pymnt_method', function (Blueprint $table) {
            //
        });
    }
}
