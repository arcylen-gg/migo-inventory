<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemCodeItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_code_item', function (Blueprint $table) {
            $table->increments('item_code_item_id');
            $table->integer('item_code_invoice_id')->unsigned();
            $table->integer('item_code_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->string('item_name');
            $table->double('item_price');
            $table->double('item_quantity')->default(0);
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
