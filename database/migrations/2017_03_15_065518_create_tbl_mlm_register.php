<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mlm_register', function (Blueprint $table) {
            $table->integer('register_id');
            $table->integer('customer_id');
            $table->integer('register_payment_type');
            $table->string('register_bank_name');
            $table->string('register_account_number');
            $table->string('register_membership_code');
            $table->integer('register_membership_pin');
            $table->string('register_delivery_first_name');
            $table->string('register_delivery_last_name');
            $table->string('register_delivery_contact');
            $table->string('register_delivery_contact_other');
            $table->string('register_shipping_method');
            $table->string('register_shipping_address');
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
