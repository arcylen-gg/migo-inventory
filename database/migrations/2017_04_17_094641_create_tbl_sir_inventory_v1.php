<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSirInventoryV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sir_inventory', function (Blueprint $table) {
            $table->increments('sir_inventory_id');
            $table->integer("sir_item_id")->unsigned();
            $table->integer("inventory_sir_id")->unsigned();
            $table->integer("sir_inventory_count");
            $table->string("sir_inventory_ref_name");
            $table->integer("sir_inventory_ref_id");

            $table->foreign("sir_item_id")->references("item_id")->on("tbl_item")->onDelete("cascade");
            $table->foreign("inventory_sir_id")->references("sir_id")->on("tbl_sir")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_sir_inventory');
    }
}
