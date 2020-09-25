<?php

use Illuminate\Database\Seeder;

class tbl_online_pymnt_method extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_online_pymnt_method')->truncate();
        $statement = "INSERT INTO `tbl_online_pymnt_method` (`method_id`,`method_name`, `method_code_name`, `method_gateway_accepted`) VALUES
            (1, 'Credit Card', 'credit-card', '1,4,6'),
            (2, 'Paypal', 'paypal', '1'),
            (3, 'Metro Bank', 'metrobank', '1,2,3,4,5,6'),
            (4, 'BDO', 'bdo', '1,2,3,4,5,6'),
            (5, 'BPI', 'bpi', '1,2,3,4,5,6'),
            (7, 'Palawan Express', 'remittance-1', '5'),
            (9, 'LBC Express', 'remittance-2', '5'),
            (10, 'M. Lhuillier', 'remittance-3', '5'),
            (11, 'Cebuana Lhuillier', 'remittance-4', '5'),
            (12, 'Rd Pawnshop', 'remittance-5', '5'),
            (13, 'Western Union', 'remittance-6', '5')
            ";

        DB::statement($statement);
    }
}