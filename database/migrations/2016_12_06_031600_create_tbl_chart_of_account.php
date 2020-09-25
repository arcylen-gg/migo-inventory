<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblChartOfAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_chart_of_account', function (Blueprint $table) 
        {
            $table->increments('account_id');
            $table->integer('account_shop_id')->unsigned();
            $table->integer('account_type_id')->unsigned();
            $table->integer('account_number');
            $table->string('account_name');
            $table->string('account_full_name');
            $table->string('account_description');
            $table->integer('account_parent_id')->nullable();
            $table->integer('account_sublevel');
            $table->double('account_balance');
            $table->double('account_open_balance');
            $table->date('account_open_balance_date');
            $table->tinyinteger('is_tax_account');
            $table->integer('account_tax_code_id');
            $table->tinyinteger('archived');
            $table->datetime('account_timecreated');
            
            $table->foreign('account_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('account_type_id')->references('chart_type_id')->on('tbl_chart_account_type')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_chart_of_account');
    }
}
