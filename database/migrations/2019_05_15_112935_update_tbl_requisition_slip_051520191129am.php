<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblRequisitionSlip051520191129am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_requisition_slip_item', function (Blueprint $table) {
            $table->double("rs_rem_qty")->after('rs_item_um');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_requisition_slip', function (Blueprint $table) {
            //
        });
    }
}
