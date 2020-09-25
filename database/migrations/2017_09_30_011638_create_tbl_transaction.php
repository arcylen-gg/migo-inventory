<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transaction', function (Blueprint $table)
        {
            $table->increments('transaction_id');
            $table->integer('shop_id')->unsigned();
            $table->integer('transaction_id_shop')->comment('Transaction ID of specific SHOP');;
            $table->string('transaction_reference_table')->comment('reference table can be tbl_customer, tbl_vendor, etc.');
            $table->integer('transaction_reference_id')->comment('This refers to the specific ID in a table.');
            $table->double('transaction_balance')->default(0)->comment('If there is a balance - then transaction is still open.');
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
