<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMembershipCodeRecreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('tbl_membership_code');
        Schema::create('tbl_membership_code_invoice', function (Blueprint $table) 
        {
            $table->increments('membership_code_invoice_id');
            $table->string('membership_code_invoice_number');
            $table->string('membership_code_customer_email');
            $table->tinyInteger('membership_code_paid');
            $table->tinyInteger('membership_code_product_issued');
            $table->string('membership_code_message_on_invoice');
            $table->string('membership_code_statement_memo');
        });
        
        Schema::create('tbl_membership_code', function (Blueprint $table) 
        {
            $table->increments('membership_code_id');
            $table->string('membership_activation_code');
            $table->integer('customer_id')->unsigned();
            $table->integer('membership_package_id')->unsigned();
            $table->integer('membership_code_invoice_id')->unsigned();
            $table->tinyInteger('used');
            $table->tinyInteger('blocked');
            $table->tinyInteger('archived');
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
