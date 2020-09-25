<?php

use Illuminate\Database\Seeder;

class tbl_payroll_bank_convertion extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_payroll_bank_convertion')->truncate();
        $statement = "INSERT INTO `tbl_payroll_bank_convertion` (`payroll_bank_convertion_id`,`bank_name`) VALUES 
        	(1, 'BDO'),
        	(2, 'Metro Bank')";
       	DB::statement($statement);
    }
}
