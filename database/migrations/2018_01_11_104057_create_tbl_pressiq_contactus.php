<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPressiqContactus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_pressiq_contact_us', function (Blueprint $table) {
            $table->increments('contact_us_id'); 
            $table->string('contactus_first_name'); 
            $table->string('contactus_last_name'); 
            $table->string('contactus_phone_number'); 
            $table->string('contactus_email'); 
            $table->string('contactus_subject'); 
            $table->string('contactus_message'); 
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
