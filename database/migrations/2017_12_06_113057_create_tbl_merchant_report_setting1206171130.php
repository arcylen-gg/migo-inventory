<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMerchantReportSetting1206171130 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_merchant_commission_report_setting', function (Blueprint $table) {
            $table->increments('merchant_report_id');
            $table->integer('merchant_commission_warehouse_id')->unsigned();
            $table->string('merchant_commission_warehouse_name');
            $table->integer('merchant_commission_shop_id');
            $table->integer('merchant_commission_percentage');
            // $table->foreign("merchant_warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_merchant_commission_report_setting');
    }
}
