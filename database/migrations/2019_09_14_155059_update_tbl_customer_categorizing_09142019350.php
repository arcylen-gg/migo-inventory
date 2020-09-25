<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCustomerCategorizing09142019350 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer', function (Blueprint $table) {
            $table->string("customer_category")->nullable()->default("new-client")->comment("new-client, regular, former, employee, do-not-call");
            $table->string("customer_category_type")->nullable()->default("non-vip")->comment("non-vip, vip");
            $table->binary("customer_category_history")->nullable();
            $table->binary("customer_type_history")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer', function (Blueprint $table) {
            //
        });
    }
}
