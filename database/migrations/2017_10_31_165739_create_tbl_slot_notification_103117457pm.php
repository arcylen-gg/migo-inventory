<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblSlotNotification103117457pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_slot_notification', function (Blueprint $table) {
            $table->increments('notification_id');
            $table->integer('shop_id');
            $table->integer('customer_id');
            $table->text('remarks');
            $table->datetime('created_date');
            $table->tinyInteger('has_been_seen')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_slot_notification');
    }
}
