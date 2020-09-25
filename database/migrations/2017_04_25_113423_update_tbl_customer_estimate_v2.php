<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCustomerEstimateV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_estimate', function (Blueprint $table) {
           $table->string("est_status")->default('pending');
           $table->string("est_accepted_by");
           $table->datetime("est_accepted_date");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_estimate', function (Blueprint $table) {
            //
        });
    }
}
