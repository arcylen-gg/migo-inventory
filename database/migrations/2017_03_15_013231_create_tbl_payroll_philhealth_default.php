<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollPhilhealthDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_philhealth_default', function (Blueprint $table) {
            $table->increments('payroll_philhealth_default_id');
            $table->double('payroll_philhealth_min', 18, 2);
            $table->double('payroll_philhealth_max', 18, 2);
            $table->double('payroll_philhealth_base', 18, 2);
            $table->double('payroll_philhealth_premium', 18, 2);
            $table->double('payroll_philhealth_ee_share', 18, 2);
            $table->double('payroll_philhealth_er_share', 18, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_philhealth_default');
    }
}
