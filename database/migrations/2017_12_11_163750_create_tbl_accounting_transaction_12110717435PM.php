<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblAccountingTransaction12110717435PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_acctg_transaction', function (Blueprint $table) {
            $table->increments('acctg_transaction_id');
            $table->integer('shop_id')->unsigned();
            $table->string('transaction_number');

            $table->foreign('shop_id')->references('shop_id')->on("tbl_shop")->onDelete('cascade');
        });
         Schema::create('tbl_acctg_transaction_list', function (Blueprint $table) {
            $table->increments('acctg_transaction_list_id');
            $table->integer('acctg_transaction_id')->unsigned();
            $table->string('transaction_ref_name');
            $table->integer('transaction_ref_id');
            $table->string('transaction_list_number');
            $table->date('transaction_date');
            $table->datetime('date_created');
            $table->binary('transaction_history');
            
            $table->foreign('acctg_transaction_id')->references('acctg_transaction_id')->on("tbl_acctg_transaction")->onDelete('cascade');
        });

         Schema::create('tbl_acctg_transaction_item', function (Blueprint $table) {
            $table->increments('acctg_transaction_item_id');
            $table->integer('acctg_transaction_id')->unsigned();
            $table->integer('itemline_item_id')->unsigned();
            $table->integer('itemline_item_um')->nullable()->unsigned();
            $table->text('itemline_item_description');
            $table->double('itemline_item_qty');
            $table->double('itemline_item_rate');
            $table->tinyInteger('itemline_item_taxable');
            $table->double('itemline_item_discount');
            $table->string('itemline_item_discount_type');
            $table->string('itemline_item_discount_remarks');
            $table->double('itemline_item_amount');
            $table->datetime('date_created');
            
            $table->foreign('acctg_transaction_id')->references('acctg_transaction_id')->on("tbl_acctg_transaction")->onDelete('cascade');
            $table->foreign('itemline_item_id')->references('item_id')->on("tbl_item")->onDelete('cascade');
            $table->foreign('itemline_item_um')->references('multi_id')->on("tbl_unit_measurement_multi")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_acctg_transaction');
    }
}
