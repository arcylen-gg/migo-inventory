<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblPayrollLeaveSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table){
        $sql = "ALTER TABLE `tbl_payroll_leave_schedule` CHANGE `leave_hours` `leave_hours` time NOT NULL DEFAULT '00:00' AFTER `shop_id`";
        DB::connection()->getPdo()->exec($sql);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
