<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_token', function (Blueprint $table) {
            $table->increments('item_token_id');
            $table->integer('item_id')->unsigned();
            $table->integer('token_id');
            $table->double('amount');
            $table->foreign("item_id")->references("item_id")->on("tbl_item")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item_token');
    }
}
