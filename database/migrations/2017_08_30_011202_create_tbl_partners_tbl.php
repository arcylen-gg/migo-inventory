<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPartnersTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_partners', function (Blueprint $table) {
            $table->increments('company_id');
            $table->string('company_logo');
            $table->string('company_name');
            $table->string('company_owner');
            $table->string('company_contactnumber');
            $table->string('company_address');
            $table->string('company_location');
           
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
