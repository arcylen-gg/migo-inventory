<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemRedeemableRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_redeemable_request', function (Blueprint $table)
        {
            $table->increments("redeemable_request_id");
            $table->integer("item_redeemable_id")->unsigned();
            $table->double("amount");
            $table->integer("shop_id");
            $table->integer("slot_id");
            $table->string("status")->default("PENDING");
            $table->dateTime("date_created");
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
