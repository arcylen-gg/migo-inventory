<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemTokenLog0205180416pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_token_log', function (Blueprint $table) {
            $table->increments('token_log_id');
            $table->integer('shop_id');
            $table->integer('token_log_slot_owner')->unsigned();
            $table->dateTime('token_log_date_created');
            $table->integer('token_id')->unsigned();
            $table->double('amount');
            $table->foreign("token_log_slot_owner")->references("customer_id")->on("tbl_customer")->onDelete("cascade");
            $table->foreign("token_id")->references("token_id")->on("tbl_token_list")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item_token_log');
    }
}
