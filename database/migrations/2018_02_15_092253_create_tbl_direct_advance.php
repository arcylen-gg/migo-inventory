<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblDirectAdvance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_direct_advance', function (Blueprint $table)
        {
            $table->increments('direct_advance_id');
            $table->integer('direct_membership_parent')->unsigned();
            $table->integer('direct_membership_new_entry')->unsigned();
            $table->double('direct_advance_bonus');
            $table->foreign("direct_membership_parent")->references("membership_id")->on("tbl_membership")->onDelete("cascade");
            $table->foreign("direct_membership_new_entry")->references("membership_id")->on("tbl_membership")->onDelete("cascade");
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