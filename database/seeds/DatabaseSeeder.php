<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);
        $this->call(tbl_delivery_method::class);
        $this->call(tbl_payroll_employment_status::class);
        $this->call(tbl_payroll_civil_status::class);
        $this->call(tbl_payroll_tax_status::class);
        $this->call(tbl_payroll_rdo::class);
        $this->call(tbl_payroll_tax_period::class);
        $this->call(tbl_payroll_tax_default::class);
        $this->call(tbl_payroll_sss_default::class);
        $this->call(tbl_payroll_philhealth_default::class);
        $this->call(tbl_payroll_over_time_rate_default::class);
        $this->call(tbl_payroll_pagibig_default::class);
        $this->call(tbl_payroll_bank_convertion::class);
        $this->call(tbl_sms_default_key::class);
        $this->call(tbl_online_pymnt_gateway::class);
        $this->call(tbl_locale::class);
        $this->call(tbl_press_release_recipients::class);
        
        Model::reguard();
    }
}
