<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblLocale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_locale', function (Blueprint $table) {
            $table->increments('locale_id');
            $table->string('locale_name');
            $table->integer('locale_parent');
            $table->integer('level_type');
            $table->float('locale_shipping_fee');
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
        Schema::drop('tbl_locale');
    }
}
