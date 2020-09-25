<?php

use Illuminate\Database\Seeder;

class tbl_online_pymnt_gateway extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_online_pymnt_gateway')->truncate();
        $statement = "INSERT INTO `tbl_online_pymnt_gateway` (`gateway_id`,`gateway_name`, `gateway_code_name`) VALUES
            (1, 'Paypal', 'paypal2'),
            (2, 'Paymaya', 'paymaya'),
            (3, 'Paynammics', 'paynamics'),
            (4, 'Dragon Pay', 'dragonpay'),
            (5, 'Other', 'other'),
            (6, 'IPay88', 'ipay88'),
            (7, 'E-wallet', 'e_wallet'),   
            (8, 'Cash on delivery', 'cashondelivery'),
            (9, 'Manual1', 'manual1'),
            (10, 'Manual2', 'manual2')
            ";

        DB::statement($statement);

        // ,
            // (7, 'E-wallet', 'ipay88')     
    }
}