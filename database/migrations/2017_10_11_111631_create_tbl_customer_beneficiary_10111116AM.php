<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCustomerBeneficiary10111116AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customer_beneficiary', function (Blueprint $table) {
            $table->increments('beneficiary_id');
            $table->integer('customer_id');
            $table->string('beneficiary_fname');
            $table->string('beneficiary_mname');
            $table->string('beneficiary_lname');
            $table->string('beneficiary_contact_no');
            $table->string('beneficiary_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_customer_beneficiary');
    }
}
