<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblBillV99 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_bill', function (Blueprint $table) {
            $table->tinyInteger("bill_is_paid")->default(0);
            $table->tinyInteger("inventory_only")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_bill', function (Blueprint $table) {
            //
        });
    }
}
