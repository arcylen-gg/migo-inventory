<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCustomerEstimateV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_estimate', function (Blueprint $table) {
            $table->increments('est_id');
            $table->integer('est_shop_id');
            $table->integer('est_customer_id');
            $table->string('est_customer_email');
            $table->string('est_customer_billing_address');
            $table->tinyInteger('est_terms_id');
            $table->date('est_date');
            $table->date('est_exp_date');
            $table->text('est_message');
            $table->text('est_memo');
            $table->string('est_discount_type');
            $table->double('est_discount_value');
            $table->double('ewt');
            $table->tinyInteger('taxable');
            $table->double('est_subtotal_price');
            $table->double('est_overall_price');
            $table->datetime('date_created');
            $table->integer('copy_to_inv_id');
            $table->tinyInteger('is_sales_order');
        });
         Schema::create('tbl_customer_estimate_line', function (Blueprint $table) {
            $table->increments('estline_id');
            $table->integer('estline_est_id')->unsigned();
            $table->date('estline_service_date');
            $table->integer('estline_item_id');
            $table->text('estline_description');
            $table->integer('estline_um');
            $table->integer('estline_qty');
            $table->double('estline_rate');
            $table->tinyInteger('taxable');
            $table->double('estline_discount');
            $table->string('estline_discount_type');
            $table->string('estline_discount_remark');
            $table->double('estline_amount');
            $table->datetime('date_created');

            $table->foreign("estline_est_id")->references("est_id")->on("tbl_customer_estimate")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_customer_estimate');
    }
}
