<?php

use Illuminate\Database\Seeder;

class tbl_payroll_employment_status extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_payroll_employment_status')->truncate();

        $statement = "INSERT INTO `tbl_payroll_employment_status` (`payroll_employment_status_id`, `employment_status`) VALUES
				(1,	'Regular'),
				(2,	'Trainee'),
				(3,	'Probationary'),
				(4,	'OJT'),
				(5,	'Part Time'),
				(6,	'Consultant'),
				(7,	'Contractual'),
				(8,	'Resigned'),
				(9,	'AWOL');";
		DB::statement($statement);
    }
}
