<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollGroupV7 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_group', function (Blueprint $table) {
            $table->time('late_grace_time')->default('00:00:00');
            $table->string('grace_time_rule_late')->default('first')->comment('per_shift, accumulative, first, last');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_group', function (Blueprint $table) {
            //
        });
    }
}
