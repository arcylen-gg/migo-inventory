<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblForMuliplePaymentMethodSales06212019941am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_estimate_pm', function (Blueprint $table) {
            $table->increments('estimate_pm_id');
            $table->integer('estimate_id')->unsigned();
            $table->integer('est_pm_id')->unsigned();
            $table->string('estimate_reference_num');
            $table->double('estimate_amount')->default(0);
            
            $table->foreign("estimate_id")->references("est_id")->on("tbl_customer_estimate")->onDelete("cascade");
            $table->foreign("est_pm_id")->references("payment_method_id")->on("tbl_payment_method")->onDelete("cascade");
        });

        Schema::create('tbl_customer_invoice_pm', function (Blueprint $table) {
            $table->increments('invoice_pm_id');
            $table->integer('invoice_id')->unsigned();
            $table->integer('inv_pm_id')->unsigned();
            $table->string('invoice_reference_num');
            $table->double('invoice_amount')->default(0);

            $table->foreign("invoice_id")->references("inv_id")->on("tbl_customer_invoice")->onDelete("cascade");
            $table->foreign("inv_pm_id")->references("payment_method_id")->on("tbl_payment_method")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_customer_estimate_pm');
    }
}
