<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblVendorItem4567 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vendor_item', function (Blueprint $table) {
            $table->increments('vendor_item_id');
            $table->integer("tag_vendor_id")->unsigned();
            $table->integer("tag_item_id")->unsigned();

            $table->foreign("tag_vendor_id")->references("vendor_id")->on("tbl_vendor")->onDelete('cascade');
            $table->foreign("tag_item_id")->references("item_id")->on("tbl_item")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_vendor_item');
    }
}
