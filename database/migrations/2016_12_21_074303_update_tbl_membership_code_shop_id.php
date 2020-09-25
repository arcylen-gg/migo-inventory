<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMembershipCodeShopId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_membership_code', function (Blueprint $table) 
        {
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            $table->string('membership_code_pin');
        }); 
        
        Schema::table('tbl_membership_code_invoice', function (Blueprint $table) 
        {
            $table->integer('shop_id')->unsigned();
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
        //
    }
}
