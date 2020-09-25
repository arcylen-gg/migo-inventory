<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollGroupV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_group', function (Blueprint $table) {
            $table->time('overtime_grace_time')->default('00:00:00');
            $table->string('grace_time_rule_overtime')->default('accumulative')->comment('per_shift, accumulative');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
