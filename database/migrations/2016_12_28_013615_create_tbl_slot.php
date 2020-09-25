<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSlot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_slot', function (Blueprint $table) {
            $table->increments('slot_id');
            $table->string('slot_no')->default('0');
            
            $table->integer('shop_id')->unsigned()->nullable();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop');
            $table->integer('slot_owner')->unsigned()->nullable();
            $table->foreign('slot_owner')->references('customer_id')->on('tbl_customer');
            $table->integer('slot_membership')->unsigned()->nullable();
            $table->foreign('slot_membership')->references('membership_id')->on('tbl_membership');
            $table->integer('slot_sponsor')->unsigned()->nullable();
            $table->foreign('slot_sponsor')->references('slot_id')->on('tbl_mlm_slot');
            
            $table->datetime('slot_created_date');
            $table->string('slot_status')->default('');
            $table->integer('slot_rank')->default(0);
            
            $table->integer('slot_placement')->default(0)->unsigned()->nullable();
            $table->string('slot_position')->default('left');
            $table->integer('slot_binary_left')->default(0);
            $table->integer('slot_binary_right')->default(0);
            
            $table->integer('slot_wallet_all')->default(0);
            $table->integer('slot_wallet_withdraw')->default(0);
            $table->integer('slot_wallet_current')->default(0);
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
