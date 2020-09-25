<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblTransactionList1019171137 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_transaction_list', function (Blueprint $table) {
            $table->integer('transaction_sales_person')->unsigned()->nullable()->after('shop_id');
            
            $table->foreign('transaction_sales_person')->references('user_id')->on('tbl_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_transaction_list', function (Blueprint $table) {
            //
        });
    }
}
