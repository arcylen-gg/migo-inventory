<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblReceiveInventoryLine010820180424PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_receive_inventory_line', function (Blueprint $table) {
            $table->increments('riline_id');
            $table->integer('riline_ri_id')->unsigned();
            $table->integer('riline_item_id');
            $table->string('riline_ref_name');
            $table->integer('riline_ref_id');
            $table->text('riline_description');
            $table->integer('riline_um');
            $table->integer('riline_qty');
            $table->double('riline_rate');
            $table->double('riline_amount');

            $table->foreign("riline_ri_id")->references("ri_id")->on("tbl_receive_inventory")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_receive_inventory_line');
    }
}
