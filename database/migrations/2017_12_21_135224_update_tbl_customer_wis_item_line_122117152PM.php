<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblCustomerWisItemLine122117152PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_customer_wis_item_line', function (Blueprint $table) {
            $table->double("itemline_rate")->after("itemline_qty");
            $table->integer("itemline_wis_id")->unsigned()->nullable()->after("itemline_item_id");

            $table->foreign("itemline_wis_id")->references("cust_wis_id")->on("tbl_customer_wis")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_customer_wis_item_line', function (Blueprint $table) {
            //
        });
    }
}
