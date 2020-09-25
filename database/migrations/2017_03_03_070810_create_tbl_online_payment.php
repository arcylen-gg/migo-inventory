<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblOnlinePayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tbl_online_pymnt_gateway', function (Blueprint $table) {
            $table->increments('gateway_id');
            $table->string('gateway_name');
            $table->string('gateway_code_name');
        });

        Schema::create('tbl_online_pymnt_method', function (Blueprint $table) {
            $table->increments('method_id');
            $table->string("method_name");
            $table->string("method_code_name");
            $table->string("method_gateway_accepted")->comment("array([0],[1],[2])");
        });

        Schema::create('tbl_online_pymnt_api', function (Blueprint $table) {
            $table->increments('api_id');
            $table->integer('api_shop_id')->unsigned();
            $table->integer('api_gateway_id')->unsigned();
            $table->longText('api_client_id');
            $table->longText('api_secret_id');

            $table->foreign('api_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('api_gateway_id')->references('gateway_id')->on('tbl_online_pymnt_gateway')->onDelete('cascade');
        });

        Schema::create('tbl_online_pymnt_bank', function (Blueprint $table) {
            $table->increments('bank_id');
            $table->integer('bank_shop_id')->unsigned();
            $table->integer('bank_gateway_id')->unsigned(); 
            $table->string('bank_name');
            $table->integer('bank_account_name');
            $table->integer('bank_account_number');

            $table->foreign('bank_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('bank_gateway_id')->references('gateway_id')->on('tbl_online_pymnt_gateway')->onDelete('cascade');
        });

        Schema::create('tbl_online_pymnt_link', function (Blueprint $table) {
            $table->increments('link_id');
            $table->integer('link_shop_id')->unsigned();
            $table->integer('link_method_id')->unsigned();
            $table->string('link_reference_name');
            $table->string('link_reference_id') ;
            $table->integer('link_img_id');
            $table->tinyInteger('link_is_enabled');
            $table->datetime('link_date_created');

            $table->foreign('link_shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->foreign('link_method_id')->references('method_id')->on('tbl_online_pymnt_method')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_online_payment');
    }
}
