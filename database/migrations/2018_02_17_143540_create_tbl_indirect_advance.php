<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblIndirectAdvance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_indirect_advance', function (Blueprint $table)
        {
            $table->increments('indirect_advance_id');
            $table->integer('indirect_membership_parent')->unsigned();
            $table->integer('indirect_membership_new_entry')->unsigned();
            $table->double('indirect_advance_bonus');
            $table->foreign("indirect_membership_parent")->references("membership_id")->on("tbl_membership")->onDelete("cascade");
            $table->foreign("indirect_membership_new_entry")->references("membership_id")->on("tbl_membership")->onDelete("cascade");
            $table->double('indirect_level');

            $table->integer('shop_id')->unsigned();
            $table->foreign("shop_id")->references("shop_id")->on("tbl_shop")->onDelete("cascade");
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
