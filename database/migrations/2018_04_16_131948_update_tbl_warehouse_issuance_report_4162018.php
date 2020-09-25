<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblWarehouseIssuanceReport4162018 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_truck', function (Blueprint $table) {
            $table->dropForeign('tbl_truck_warehouse_id_foreign');
            $table->dropColumn("warehouse_id");
        });
        Schema::table('tbl_truck', function (Blueprint $table) {
            $table->integer("warehouse_id")->nullable()->unsigned();
            $table->foreign("warehouse_id")->references("warehouse_id")->on("tbl_warehouse")->onDelete("cascade");
        });
        Schema::table('tbl_warehouse_issuance_report', function (Blueprint $table) {
            $table->integer("wis_truck_id")->nullable()->unsigned();

            $table->foreign("wis_truck_id")->references("truck_id")->on("tbl_truck")->onDelete("cascade");
        });
        Schema::table('tbl_customer_wis', function (Blueprint $table) {
            $table->integer("cust_wis_truck_id")->nullable()->unsigned();

            $table->foreign("cust_wis_truck_id")->references("truck_id")->on("tbl_truck")->onDelete("cascade");
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
