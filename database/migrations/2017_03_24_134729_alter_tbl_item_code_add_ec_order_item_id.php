<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblItemCodeAddEcOrderItemId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item_code', function (Blueprint $table) {
           $table->integer('ec_order_id')->unsigned()->default(0);
           $table->integer('ec_order_item_id')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_item_code', function (Blueprint $table) {
            //
        });
    }
}
