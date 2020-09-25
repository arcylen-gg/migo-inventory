<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblRequisitionSlip120517438PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_requisition_slip', function (Blueprint $table) {
            $table->increments('requisition_slip_id');
            $table->integer('shop_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('requisition_slip_number');
            $table->string('requisition_slip_status')->default('open')->comment('open/closed/void');
            $table->text('requisition_slip_remarks');
            $table->datetime('requisition_slip_date_created');

            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('tbl_user')->onDelete('cascade');
        });
        Schema::create('tbl_requisition_slip_item', function (Blueprint $table) {
            $table->increments('rs_itemline_id');
            $table->integer('rs_id')->unsigned();
            $table->integer('rs_item_id')->unsigned();
            $table->text('rs_item_description');
            $table->integer('rs_item_um');
            $table->double('rs_item_qty');
            $table->double('rs_item_rate');
            $table->double('rs_item_amount');
            $table->integer('rs_vendor_id')->unsigned()->nullable();

            $table->foreign('rs_id')->references('requisition_slip_id')->on('tbl_requisition_slip')->onDelete('cascade');
            $table->foreign('rs_item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
            $table->foreign('rs_vendor_id')->references('vendor_id')->on('tbl_vendor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_requisition_slip');
    }
}
