<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCommission102717121PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_commission', function (Blueprint $table) {
            $table->increments('commission_id');
            $table->integer('shop_id')->unsigned();
            $table->integer('customer_id')->unsigned();
            $table->string('customer_email');
            $table->integer('agent_id')->unsigned();
            $table->date('date');
            $table->date('due_date');
            $table->double('total_selling_price');
            $table->double('total_contract_price');
            $table->double('total_commission');
            $table->double('loanable_amount');
            $table->datetime('date_created');
            $table->tinyInteger('is_fulfilled')->default(0);


            $table->foreign('customer_id')->references('customer_id')->on('tbl_customer')->onDelete('cascade');
            $table->foreign('agent_id')->references('employee_id')->on('tbl_employee')->onDelete('cascade');
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });
        Schema::create('tbl_commission_item', function (Blueprint $table) {
            $table->increments('commission_item_id');
            $table->integer('commission_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->double('downpayment_percent');
            $table->double('discount');
            $table->integer('monthly_amort');
            $table->double('misceleneous_fee_percent');
            $table->double('ndp_commission');
            $table->double('tcp_commission');

            $table->foreign('commission_id')->references('commission_id')->on('tbl_commission')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('tbl_item')->onDelete('cascade');
        });
        Schema::create('tbl_commission_invoice', function (Blueprint $table) {
            $table->increments('comm_inv_id');
            $table->integer('invoice_id')->unsigned();
            $table->integer('commission_id')->unsigned();
            $table->string('commission_type')->comment('NDPC or TCPC');
            $table->string('payment_ref_name')->nullable();
            $table->integer('payment_ref_id')->default(0);
            $table->double('commission_amount');
            $table->tinyInteger('is_released')->default(0);
            $table->tinyInteger('invoice_is_paid')->default(0);


            $table->foreign('commission_id')->references('commission_id')->on('tbl_commission')->onDelete('cascade');
            $table->foreign('invoice_id')->references('inv_id')->on('tbl_customer_invoice')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_commission');
    }
}
