<?php

use Illuminate\Database\Seeder;

class tbl_payroll_tax_period extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_payroll_tax_period_default')->truncate();
        $statement = "INSERT INTO `tbl_payroll_tax_period_default` (`payroll_tax_period_default_id`,`payroll_tax_period`) vALUES
        	(1, 'Daily'),
        	(2, 'Weekly'),
        	(3, 'Semi-monthly'),
        	(4, 'Monthly')";

        DB::statement($statement);

    }
}
