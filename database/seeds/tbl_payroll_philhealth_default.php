<?php

use Illuminate\Database\Seeder;

class tbl_payroll_philhealth_default extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_payroll_philhealth_default')->truncate();
        $statement = "INSERT INTO `tbl_payroll_philhealth_default` (`payroll_philhealth_default_id`, `payroll_philhealth_min`, `payroll_philhealth_max`, `payroll_philhealth_base`, `payroll_philhealth_premium`, `payroll_philhealth_ee_share`, `payroll_philhealth_er_share`) VALUES
			(1,	0.00,	8999.99,	8000.00,	200.00,	100.00,	100.00),
			(2,	9000.00,	9999.99,	9000.00,	225.00,	112.50,	112.50),
			(3,	10000.00,	10999.99,	10000.00,	250.00,	125.00,	125.00),
			(4,	11000.00,	11999.99,	11000.00,	275.00,	137.50,	137.50),
			(5,	12000.00,	12999.99,	12000.00,	300.00,	150.00,	150.00),
			(6,	13000.00,	13999.99,	13000.00,	325.00,	162.50,	162.50),
			(7,	14000.00,	14999.99,	14000.00,	350.00,	175.00,	175.00),
			(8,	15000.00,	15999.99,	15000.00,	375.00,	187.50,	187.50),
			(9,	16000.00,	16999.99,	16000.00,	400.00,	200.00,	200.00),
			(10,	17000.00,	17999.99,	17000.00,	425.00,	212.50,	212.50),
			(11,	18000.00,	18999.99,	18000.00,	450.00,	225.00,	225.00),
			(12,	19000.00,	19999.99,	19000.00,	475.00,	237.50,	237.50),
			(13,	20000.00,	20999.99,	20000.00,	500.00,	250.00,	250.00),
			(14,	21000.00,	21999.99,	21000.00,	525.00,	262.50,	262.50),
			(15,	22000.00,	22999.99,	22000.00,	550.00,	275.00,	275.00),
			(16,	23000.00,	23999.99,	23000.00,	575.00,	287.50,	287.50),
			(17,	24000.00,	24999.99,	24000.00,	600.00,	300.00,	300.00),
			(18,	25000.00,	25999.99,	25000.00,	625.00,	312.50,	312.50),
			(19,	26000.00,	26999.99,	26000.00,	650.00,	325.00,	325.00),
			(20,	27000.00,	27999.99,	27000.00,	675.00,	337.50,	337.50),
			(21,	28000.00,	28999.99,	28000.00,	700.00,	350.00,	350.00),
			(22,	29000.00,	29999.99,	29000.00,	725.00,	362.50,	362.50),
			(23,	30000.00,	30999.99,	30000.00,	750.00,	375.00,	375.00),
			(24,	31000.00,	31999.99,	31000.00,	775.00,	387.50,	387.50),
			(25,	32000.00,	32999.99,	32000.00,	800.00,	400.00,	400.00),
			(26,	33000.00,	33999.99,	33000.00,	825.00,	412.50,	412.50),
			(27,	34000.00,	34999.99,	34000.00,	850.00,	425.00,	425.00),
			(28,	35000.00,	0.00,	35000.00,	875.00,	437.50,	437.50);";

		DB::statement($statement);
    }
}
