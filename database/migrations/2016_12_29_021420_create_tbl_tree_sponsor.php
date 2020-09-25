<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTreeSponsor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_tree_sponsor', function (Blueprint $table) {
            $table->increments('sponsor_tree_id');
            
            $table->integer('shop_id')->unsigned()->nullable();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            
            $table->integer('sponsor_tree_parent_id')->unsigned()->nullable();
            $table->foreign('sponsor_tree_parent_id')->references('slot_id')->on('tbl_mlm_slot');
            
            $table->integer('sponsor_tree_child_id')->unsigned()->nullable();
            $table->foreign('sponsor_tree_child_id')->references('slot_id')->on('tbl_mlm_slot');
            
            $table->integer('sponsor_tree_level')->default(0);
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
