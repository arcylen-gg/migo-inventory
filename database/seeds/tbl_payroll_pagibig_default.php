<?php

use Illuminate\Database\Seeder;

class tbl_payroll_pagibig_default extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_payroll_pagibig_default')->truncate();
        $statement = "INSERT INTO `tbl_payroll_pagibig_default` (`payroll_pagibig_default_id`, `payroll_pagibig_percent`) VALUES
			(1,	2);";
		DB::statement($statement);
    }
}
