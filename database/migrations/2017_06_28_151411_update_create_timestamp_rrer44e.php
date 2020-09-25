<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCreatetimestampRrer44e extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_category', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_chart_account_type', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_country', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_credit_memo', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_credit_memo_line', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_customer', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_customer_address', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_customer_attachment', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_customer_invoice', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('tbl_customer_invoice_line', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_default_chart_account', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_employee', function (Blueprint $table) {
            $table->renameColumn("created_at","date_created");
        });
        Schema::table('tbl_employee', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_image', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_inventory_serial_number', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_inventory_slip', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('tbl_item', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_item_bundle', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_item_discount', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_item_multiple_price', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_item_type', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_manual_credit_memo', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_manual_invoice', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_manual_receive_payment', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_manufacturer', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_position', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_receive_payment', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_receive_payment_line', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_settings', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_sir', function (Blueprint $table) {
            $table->renameColumn('created_at','date_created');
        });
        Schema::table('tbl_sir', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_sir_cm_item', function (Blueprint $table) {
            $table->timestamps();
        });
        
        Schema::table('tbl_sir_inventory', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_sir_item', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_sir_sales_report', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_truck', function (Blueprint $table) {
            $table->renameColumn("created_at","date_created");
        });
        Schema::table('tbl_truck', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_um', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_unit_measurement', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_unit_measurement_multi', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('tbl_user', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_category', function (Blueprint $table) {
            //
        });
    }
}
