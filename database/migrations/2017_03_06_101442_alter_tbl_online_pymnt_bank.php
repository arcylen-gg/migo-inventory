<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblOnlinePymntBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('tbl_online_pymnt_bank');

        Schema::create('tbl_online_pymnt_other', function (Blueprint $table) {
            $table->increments('other_id');
            $table->integer('other_shop_id')->unsigned();
            $table->integer('other_gateway_id')->unsigned();
            $table->string('other_name');
            $table->longText('other_description');

            $table->foreign('other_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('other_gateway_id')->references('gateway_id')->on('tbl_online_pymnt_gateway')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_online_pymnt_bank', function (Blueprint $table) {
            //
        });
    }
}
