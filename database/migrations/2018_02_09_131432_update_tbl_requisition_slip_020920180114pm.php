<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblRequisitionSlip020920180114pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_requisition_slip', function (Blueprint $table) {
            $table->date('requisition_slip_date')->after('requisition_slip_status');
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
