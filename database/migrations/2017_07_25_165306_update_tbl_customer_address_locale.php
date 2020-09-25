<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCustomerAddressLocale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_address', function (Blueprint $table) 
        {
            $table->integer("state_id")->unsigned()->nullable();
            $table->integer("city_id")->unsigned()->nullable();
            $table->integer("barangay_id")->unsigned()->nullable();

            $table->foreign('state_id')
                  ->references('locale_id')->on('tbl_locale')
                  ->onDelete('cascade');
            $table->foreign('city_id')
                  ->references('locale_id')->on('tbl_locale')
                  ->onDelete('cascade');
            $table->foreign('barangay_id')
                  ->references('locale_id')->on('tbl_locale')
                  ->onDelete('cascade');
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
