<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMembershipCodePriceAndId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_membership_code_invoice', function (Blueprint $table) 
        {
            $table->integer('customer_id')->unsigned();
        });
        Schema::table('tbl_membership_code', function (Blueprint $table) 
        {
            $table->double('membership_code_price');
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
