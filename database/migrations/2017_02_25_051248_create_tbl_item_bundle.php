<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemBundle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_bundle', function (Blueprint $table) {
            $table->increments('bundle_id');
            $table->integer('bundle_bundle_id')->unsigned();
            $table->integer('bundle_item_id')->unsigned();
            $table->integer('bundle_um_id')->unsigned();
            $table->float('bundle_qty');
            $table->float('bundle_display_components');

            $table->foreign('bundle_bundle_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
            $table->foreign('bundle_item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_item_bundle');
    }
}
