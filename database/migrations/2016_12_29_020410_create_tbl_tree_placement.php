<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTreePlacement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_tree_placement', function (Blueprint $table) {
            $table->increments('placement_tree_id');
            
            $table->integer('shop_id')->unsigned()->nullable();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            
            $table->integer('placement_tree_parent_id')->unsigned()->nullable();
            $table->foreign('placement_tree_parent_id')->references('slot_id')->on('tbl_mlm_slot');
            
            $table->integer('placement_tree_child_id')->unsigned()->nullable();
            $table->foreign('placement_tree_child_id')->references('slot_id')->on('tbl_mlm_slot');
            
            $table->integer('placement_tree_level')->default(0);
            $table->string('placement_tree_position')->default('left');
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
