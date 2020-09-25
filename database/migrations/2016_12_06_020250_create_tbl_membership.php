<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMembership extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_membership', function (Blueprint $table) {
            $table->increments('membership_id');
        	$table->string('membership_name', 100);
        	$table->integer('shop_id')->unsigned();
        	$table->double('membership_price')->default(0);
        	$table->tinyinteger('membership_archive');
        	$table->datetime('membership_date_created');
        });
        Schema::table('tbl_membership', function($table) {
          $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_membership');
    }
}
