<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollGroupV8 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_group', function (Blueprint $table) {
            $table->string('tax_reference')->default('declared')->comment('param[declared, gross_basic, net_basic]');
            $table->string('sss_reference')->default('declared')->comment('param[declared, gross_basic, net_basic]');
            $table->string('philhealth_reference')->default('declared')->comment('param[declared, gross_basic, net_basic]');
            $table->string('pagibig_reference')->default('declared')->comment('param[declared, gross_basic, net_basic]');
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
