<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblMonitoringInventory090320181019am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_monitoring_inventory', function (Blueprint $table) {
            $table->datetime('invty_transaction_date')->after('invty_total_sales_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_monitoring_inventory', function (Blueprint $table) {
            //
        });
    }
}
