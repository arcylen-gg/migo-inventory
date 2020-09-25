<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;

use Carbon\carbon;
use DB;

/**
 * Chart of Account Module - all account related module
 *
 * @author Bryan Kier Aradanas
 */

class account
{

    /**
     * Create a Default Chart of Account General
     *
     * @return  status
     */
    public static function put_default_account($shop)
    {
        
        $is_account_has_data = Tbl_chart_of_account::accountInfo($shop)->count();
        $count_defualt       = Tbl_default_chart_account::count();
        $shop_id             = Tbl_shop::where("shop_id", $shop)->orWhere("shop_key")->value("shop_id");
        
        $default_account = Tbl_default_chart_account::get();
        
        foreach($default_account as $default)
        {
            $curr = Tbl_chart_of_account::where("account_shop_id",$shop)->where("account_name",$default->default_name)->first();
            if(!$curr)
            {
                $insert["account_shop_id"]          = $shop_id;
                $insert["account_type_id"]          = $default->default_type_id;
                $insert["account_number"]           = $default->default_number;
                $insert["account_name"]             = $default->default_name;
                $insert["account_description"]      = $default->default_description;
                $insert["account_code"]             = $default->default_for_code;
                $insert['account_timecreated']      = Carbon::now();
                
                Tbl_chart_of_account::insert($insert);                    
            }
        }
    }

    /**
     * Create a Default Chart of Account for Payroll Journal Entries
     *
     * @return  status
     */
    public static function put_default_account_payroll($shop_id)
    {
        // SALARIES AND WAGES
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-salaries")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 13;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Salaries and Wages";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-salaries";
            
            Tbl_chart_of_account::insert($insert);
        }

        // OVERTIME PAY
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-overtime")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 13;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Overtime Pay";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-overtime";
            
            Tbl_chart_of_account::insert($insert);
        }

        // HOLIDAY PAY
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-holiday")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 13;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Holiday Pay";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-holiday";
            
            Tbl_chart_of_account::insert($insert);
        }

        // NIGHT DIFFERENTIAL PAY
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-night-differential")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 13;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Night Differential Pay";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-night-differential";
            
            Tbl_chart_of_account::insert($insert);
        }

        // DE MINIMIS BENEFITS
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-benefits")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 13;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "De Minimis Benefits";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-benefits";
            
            Tbl_chart_of_account::insert($insert);
        }

        // OTHER NON TAXABLE BENEFITS (13TH MONTH)
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-other")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 13;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Other Non Taxable Benefits";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-other";
            
            Tbl_chart_of_account::insert($insert);
        }

        // PHIC PREMIUM PAYABLE
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-phic")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 8;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "PHIC Premiums Payable";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-phic-premium";
            
            Tbl_chart_of_account::insert($insert);
        }


        // SSS PREMIUM PAYABLE
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-sss")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 8;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "SSS Premiums Payable ";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-sss-premium";
            
            Tbl_chart_of_account::insert($insert);
        }

        // PAGBIBIG PREMIUM PAYABLE
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-hdmif-premium")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 8;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Pag-ibig Premiums Payable";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-hdmif-premium";
            
            Tbl_chart_of_account::insert($insert);
        }

        // PAGIBIG LOANS PAYABLE
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-hdmif-loan")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 8;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Pag-ibig Loans Payable";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-hdmif-loan";
            
            Tbl_chart_of_account::insert($insert);
        }

        // SSS LOANS PAYABLE
        $exist_account = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_code", "payroll-sss-loan")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = $shop_id;
            $insert["account_type_id"]          = 8;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "SSS Loans Payable";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-sss-loan-";
            
            Tbl_chart_of_account::insert($insert);
        }
    }
}