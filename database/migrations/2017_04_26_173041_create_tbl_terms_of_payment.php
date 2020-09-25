<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTermsOfPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_terms', function (Blueprint $table) {
            $table->increments('terms_id');
            $table->integer("terms_shop_id");
            $table->string("terms_name");
            $table->integer("terms_no_of_days")->comment("Due in fixed number of days");
            $table->tinyInteger("archived");
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
        Schema::drop('tbl_terms_of_payment');
    }
}
