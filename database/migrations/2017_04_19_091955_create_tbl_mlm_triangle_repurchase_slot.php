<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmTriangleRepurchaseSlot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_mlm_triangle_repurchase_slot', function (Blueprint $table) {
            $table->increments('repurchase_slot_id');
            $table->integer('repurchase_slot_no')->default(0);
            $table->integer('repurchase_slot_owner')->default(0);
            $table->integer('repurchase_slot_slot_id')->default(0);
            $table->integer('repurchase_slot_invoice_id')->default(0);

            $table->string('repurchase_slot_position')->default('left');
            $table->integer('repurchase_slot_placement')->default(0);

            $table->double('repurchase_slot_amount')->default(0);
            $table->integer('repurchase_slot_shop_id')->default(0);
        });

        Schema::create('tbl_mlm_triangle_repurchase_tree', function (Blueprint $table) {
            $table->integer('tree_repurchase_id');
            $table->integer('tree_repurchase_shop_id')->default(0);
            $table->integer('tree_repurchase_slot_sponsor')->default(0);
            $table->integer('tree_repurchase_slot_child')->default(0);
            $table->integer('tree_repurchase_tree_level')->default(0);
            $table->string('tree_repurchase_tree_position')->default('left');
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
