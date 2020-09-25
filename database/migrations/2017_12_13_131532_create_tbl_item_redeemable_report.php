<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblItemRedeemableReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_item_redeemable_report', function (Blueprint $table) {
            $table->increments('redeemable_report_id');
            $table->integer('slot_id');
            $table->integer('shop_id');
            $table->integer('amount');
            $table->string('log_type');
            $table->string('log');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_item_redeemable_report');
    }
}
