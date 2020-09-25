<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCustomerOtherInfoRev2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_other_info', function (Blueprint $table) {
            $table->tinyInteger('IsSubCustomer')->after('customer_print_name');
            $table->text('customer_notes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_other_info', function (Blueprint $table) {
            //
        });
    }
}
