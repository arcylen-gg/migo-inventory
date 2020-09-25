<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWarehouseIssuanceReport10171200pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_warehouse_issuance_report', function (Blueprint $table) 
        {
            $table->text('destination_warehouse_address')->after('wis_number');
            $table->integer('destination_warehouse_id')->nullable()->unsigned()->after('wis_number');

            $table->foreign('destination_warehouse_id')->references('warehouse_id')->on('tbl_warehouse');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_warehouse_issuance_report', function (Blueprint $table) {
            //
        });
    }
}
