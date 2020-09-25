<?php

use Illuminate\Database\Seeder;

class tbl_sms_default_key extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_sms_default_key')->truncate();
        $statement = "INSERT INTO `tbl_sms_default_key` (`sms_default_id`,`sms_default_key`) VALUES
            (1, 'success_register'),
            (2, 'membership_code_purchase'),
            (3, 'discount_card_purchase'),
            (4, 'e_wallet_transfer'),
            (5, 'merchant_registration'),
            (6, 'merchant_product_registration'),
            (7, 'e_wallet_refill'),
            (8, 'inquire_current_points'),
            (9, 'redeem_points'),
            (10, 'password_reset')
            ";

        DB::statement($statement);
    }
}
