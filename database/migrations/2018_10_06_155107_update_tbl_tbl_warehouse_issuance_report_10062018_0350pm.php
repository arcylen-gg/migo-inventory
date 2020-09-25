<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblTblWarehouseIssuanceReport100620180350pm extends Migration
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
            $table->double('wis_total_amount')->after('wis_status')->default(0);
            $table->string('wis_issued_by')->after('wis_total_amount')->nullable();
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
