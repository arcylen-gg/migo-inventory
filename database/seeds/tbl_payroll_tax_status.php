<?php

use Illuminate\Database\Seeder;

class tbl_payroll_tax_status extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_payroll_tax_status')->truncate();
        $statement = "INSERT INTO `tbl_payroll_tax_status` (`payroll_tax_status_id`,`payroll_tax_status_name`) VALUES
        	(1, 'Z'),
        	(2, 'S/ME'),
        	(3, 'S1/ME1'),
        	(4, 'S2/ME2'),
        	(5, 'S3/ME3'),
        	(6, 'S4/ME4')";

        DB::statement($statement);
    }
}
