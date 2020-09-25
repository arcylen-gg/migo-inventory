<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemRedeemablePoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_redeemable_points', function (Blueprint $table)
        {
            $table->increments("redeemable_points_id");
            $table->double("amount");
            $table->integer("shop_id");
            $table->integer("slot_id");
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
