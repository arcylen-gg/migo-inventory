<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCustomerWis011020180941AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_wis', function (Blueprint $table) {
            $table->string('cust_email')->after('cust_wis_from_warehouse');
            $table->float('total_amount')->after('cust_wis_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_wis', function (Blueprint $table) {
            //
        });
    }
}
