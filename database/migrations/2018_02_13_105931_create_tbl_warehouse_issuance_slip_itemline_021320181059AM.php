<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblWarehouseIssuanceSlipItemline021320181059AM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_warehouse_issuance_report', function (Blueprint $table) {
            $table->date("wis_delivery_date")->after("wis_remarks");
        });

        Schema::table('tbl_warehouse_receiving_report', function (Blueprint $table) {
            $table->date("rr_date_received")->after("rr_remarks");
        });
        Schema::create('tbl_warehouse_issuance_report_itemline', function (Blueprint $table) {
            $table->increments("wt_id");
            $table->integer("wt_item_id")->unsigned();
            $table->integer("wt_wis_id")->unsigned();
            $table->text("wt_description");
            $table->integer("wt_um");
            $table->double("wt_qty");
            $table->double("wt_orig_qty");
            $table->double("wt_rate");
            $table->double("wt_amount");
            $table->string("wt_refname");
            $table->integer("wt_refid");

            $table->foreign("wt_item_id")->references("item_id")->on("tbl_item")->onDelete("cascade");
            $table->foreign("wt_wis_id")->references("wis_id")->on("tbl_warehouse_issuance_report")->onDelete("cascade");
        });
        Schema::create('tbl_warehouse_receiving_report_itemline', function (Blueprint $table) {
            $table->increments("rrline_id");
            $table->integer("rr_item_id")->unsigned();
            $table->integer("rr_id")->unsigned();
            $table->text("rr_description");
            $table->integer("rr_um");
            $table->double("rr_qty");
            $table->double("rr_orig_qty");
            $table->double("rr_rate");
            $table->double("rr_amount");
            $table->string("rr_refname");
            $table->integer("rr_refid");

            $table->foreign("rr_item_id")->references("item_id")->on("tbl_item")->onDelete("cascade");
            $table->foreign("rr_id")->references("rr_id")->on("tbl_warehouse_receiving_report")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_warehouse_issuance_slip_itemline', function (Blueprint $table) {
            //
        });
    }
}
