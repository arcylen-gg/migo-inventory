<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefaultChartAccount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_default_chart_account', function (Blueprint $table) {
            $table->increments('default_id');
            $table->integer('default_type_id')->unssigned();
            $table->integer('default_number');
            $table->string('default_name');
            $table->string('default_description');
            $table->integer('default_parent_id');
            $table->integer('default_sublevel');
            $table->double('default_balance');
            $table->double('default_open_balance');
            $table->date('default_open_balance_date');
            $table->tinyinteger('is_tax_account');
            $table->integer('account_tax_code_id');
            $table->tinyinteger('default_for_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_default_chart_account');
    }
}
