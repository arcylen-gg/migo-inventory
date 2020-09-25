<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblMembershipCodeInvoiceAddName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_membership_code_invoice', function (Blueprint $table) 
        {
            $table->string("membership_code_invoice_f_name");
            $table->string("membership_code_invoice_m_name");
            $table->string("membership_code_invoice_l_name");
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
