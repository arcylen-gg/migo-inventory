<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblItemRepurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_item', function (Blueprint $table) 
        {
            $table->double("item_unilevel_points");
            $table->double("item_binary_points");
            $table->double("item_upgrade_points");
        });

        Schema::table('tbl_mlm_slot', function (Blueprint $table) 
        {
            $table->double("slot_personal_points");
            $table->double("slot_group_points");
            $table->double("slot_upgrade_points");
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
