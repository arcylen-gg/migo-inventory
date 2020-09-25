<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblVoucerItemBundledItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_voucher_item', function (Blueprint $table) {
            //
            $table->integer("voucher_is_bundle")->default(1);
            $table->string('item_name')->nullable();
            $table->double('item_price')->default(0);
            $table->double('item_quantity')->default(0);
            $table->string('item_serial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_voucher_item', function (Blueprint $table) {
            //
        });
    }
}
