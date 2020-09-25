<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMembershipCodeItemHas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_membership_code_item_has', function (Blueprint $table) {
            $table->increments('membership_code_item_has_id');
            $table->integer("membership_code_invoice_id")->unsigned();
            $table->integer("membership_code_id")->unsigned();
            $table->integer("shop_id")->unsigned();
            $table->integer("item_id")->unsigned();
            $table->string("membership_code_item_has_name");
            $table->string("membership_code_item_has_quantity");
            $table->double("membership_code_item_has_price");
            
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
