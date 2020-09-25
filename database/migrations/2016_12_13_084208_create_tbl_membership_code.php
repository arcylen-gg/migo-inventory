<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMembershipCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_membership_code', function (Blueprint $table) {
            $table->increments('membership_code_id');
            $table->string('membership_code_invoice_number');
            $table->string('membership_code_customer_email');
            $table->tinyInteger('membership_code_paid');
            $table->tinyInteger('membership_code_product_issued');
            $table->string('membership_code_message_on_invoice');
            $table->string('membership_code_statement_memo');
        });
        // tbl_membership_code
        // membership_code_id
        // membership_code_invoice_number
        // membership_code_customer_email
        // membership_code_paid
        // membership_code_product_issued
        // membership_code_message_on_invoice
        // membership_code_statement_memo
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
