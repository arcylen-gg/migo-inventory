<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsTblPayrollRegisterColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('tbl_payroll_register_columns', function (Blueprint $table) {
            $table->tinyInteger('position');
            $table->tinyInteger('taxstatus');
            $table->tinyInteger('dailyrate');
            $table->tinyInteger('monthlybasic');
            $table->tinyInteger('semimonthlybasic');
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
