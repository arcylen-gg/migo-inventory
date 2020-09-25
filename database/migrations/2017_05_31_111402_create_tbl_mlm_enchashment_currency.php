<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMlmEnchashmentCurrency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_mlm_encashment_currency', function (Blueprint $table) 
        {
            $table->increments('en_cu_id');
            $table->integer('en_cu_shop_id')->default(0);
            $table->double('en_cu_convertion')->default(0);
            $table->integer('en_cu_active')->default(0);
            $table->string('iso')->default(0);
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
