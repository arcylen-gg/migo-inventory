<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblWarehouseIssuanceReport0928171147AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_warehouse_issuance_report', function (Blueprint $table) {
            $table->increments('wis_id');
            $table->integer('wis_shop_id')->unsigned();
            $table->string('wis_number');
            $table->integer('wis_from_warehouse')->unsigned();
            $table->longtext('wis_remarks');
            $table->string('wis_status')->default('pending');
            $table->text('confirm_image');
            $table->string('receiver_code');
            $table->string('ref_name');
            $table->integer('ref_id');
            $table->timestamps();

            $table->foreign('wis_from_warehouse')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
            $table->foreign('wis_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });
        Schema::create('tbl_warehouse_issuance_report_item', function (Blueprint $table) {
            $table->increments('wis_item_id');
            $table->integer('wis_id')->unsigned();
            $table->integer('record_log_item_id');

            $table->foreign('wis_id')->references('wis_id')->on('tbl_warehouse_issuance_report')->onDelete('cascade');
        });

         Schema::create('tbl_warehouse_receiving_report', function (Blueprint $table) {
            $table->increments('rr_id');
            $table->integer('rr_shop_id')->unsigned();
            $table->string('rr_number');
            $table->integer('wis_id');
            $table->integer('warehouse_id')->unsigned();
            $table->longtext('rr_remarks');
            $table->string('rr_status')->default('pending');
            $table->text('confirm_image');
            $table->timestamps();

            $table->foreign('warehouse_id')->references('warehouse_id')->on('tbl_warehouse')->onDelete('cascade');
            $table->foreign('rr_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });
        Schema::create('tbl_warehouse_receiving_report_item', function (Blueprint $table) {
            $table->increments('rr_item_id');
            $table->integer('rr_id')->unsigned();
            $table->integer('record_log_item_id');

            $table->foreign('rr_id')->references('rr_id')->on('tbl_warehouse_receiving_report')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_warehouse_issuance_report');
        Schema::dropIfExists('tbl_warehouse_receiving_report');
    }
}
