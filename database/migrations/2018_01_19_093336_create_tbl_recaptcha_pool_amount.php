<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblRecaptchaPoolAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_recaptcha_pool_amount', function (Blueprint $table) {
            $table->increments('recaptcha_pool_amount_id');
            $table->integer('shop_id');
            $table->double('amount')->default(0);
            $table->dateTime('date_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_recaptcha_pool_amount');
    }
}
