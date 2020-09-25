<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemRedeemable1114171139 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_redeemable', function (Blueprint $table)
        {
            $table->increments("item_redeemable_id");
            $table->integer("item_id")->unsigned();
            $table->string("item_name");
            $table->text("item_description");
            $table->double("redeemable_points");
            $table->dateTime("date_created");
            $table->foreign("item_id")->references("item_id")->on("tbl_item")->onDelete("cascade");
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
