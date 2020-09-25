<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblShopEventReserved101017456PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_shop_event_reserved', function (Blueprint $table) {
            $table->increments('reservation_id');
            $table->integer('event_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->date('reverse_date');

            $table->foreign('event_id')->references('event_id')->on('tbl_shop_event');
            $table->foreign('customer_id')->references('customer_id')->on('tbl_customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_shop_event_reserved');
    }
}
