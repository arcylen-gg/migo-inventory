<?php

use Illuminate\Database\Seeder;

class tbl_payroll_civil_status extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_payroll_civil_status')->truncate();

        $statement = "INSERT INTO `tbl_payroll_civil_status` (`payroll_civil_status_id`, `payroll_civil_status_name`) VALUES 
        	(1, 'Single'),
        	(2, 'Married'),
        	(3, 'Divorced'),
        	(4, 'Separated'),
        	(5, 'Widowed')";
       DB::statement($statement);
    }
}
