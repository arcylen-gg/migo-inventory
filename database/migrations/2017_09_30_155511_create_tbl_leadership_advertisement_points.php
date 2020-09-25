<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblLeadershipAdvertisementPoints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_leadership_advertisement_points', function (Blueprint $table) 
        {
            $table->increments('points_log_id');
            $table->double('points_amount')->default(0);
            $table->string('position');
            $table->string('log');
            $table->integer('slot_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->integer('reason_slot')->unsigned();
            $table->dateTime('date_created');
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
