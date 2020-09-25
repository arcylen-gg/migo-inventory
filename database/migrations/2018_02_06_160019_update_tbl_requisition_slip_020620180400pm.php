<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblRequisitionSlip020620180400pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_requisition_slip', function (Blueprint $table) {
            $table->double('total_amount')->after('requisition_slip_status');
            $table->string('requisition_slip_memo')->after('requisition_slip_remarks');
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
            
        });
    }
}
