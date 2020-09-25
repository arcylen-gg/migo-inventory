<?php
namespace App\Globals;
use DB;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_chart_account_type;
use App\Models\Tbl_journal_entry;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Models\Tbl_payroll_record;
use App\Models\Tbl_payroll_period;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_deduction_employee;
use App\Models\Tbl_payroll_entity;
use App\Models\Tbl_payroll_journal_tag;

use App\Globals\payroll;

use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\carbon;

/**
 * Tentative Payroll Module - all tentative payroll journal entries related module
 * Journal Entries Like report - not detailed
 * 
 * @author Bryan Kier Aradanas
 */
class PayrollJournalEntries
{
	public static function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

	/**
	 * Generate a report for the journal entries in payroll
	 *
	 */
	public static function payroll_summary($start_date, $end_date)
	{
		// $start_date 	= "01-26-2017";
		// $end_date		= "02-10-2017";

		/* GET ALL TOTALS OF EACH EMPLOYEE */
		$_record = Payroll::record_by_date(PayrollJournalEntries::getShopId(), $start_date, $end_date);

		// dd($_record);

		/* INSERT ACCOUNT ID FOR EACH ENTITY PER EMPLOYEE - SET DEFAULT IF NOT SET*/
		foreach($_record as $key=>$record)
		{
			$_record[$key]['accounts'] = PayrollJournalEntries::check_payroll_entity_account_id($record['payroll_employee_id']);
		}


		$_journal = [];

		/* GET ALL PAYROLL ENTOTY */
		$_payroll_entity = Tbl_payroll_entity::get();
		foreach($_payroll_entity as $key=>$entity)
		{
			/* GROUP EMPLOYEE BY GIVEN ENTITY */
			$temp_journal = collect($_record)->groupBy( function($item) use($entity)
			{
				return $item['accounts'][$entity->entity_name];
			});

			/* AFTER GROUPING INTO EACH ENTITY, GET TOTAL OF EACH PAYROLL ENTITY */
			foreach($temp_journal as $key2=>$tjournal)
			{
				$total = collect($tjournal)->sum($entity->entity_name);

				/* GET ALL ACCOUNT DETAILS AND ADD THE TOTAL AMOUNT */
				$account 			= Tbl_chart_of_account::accountType()->where("account_id", $key2)->first()->toArray();
				$account['total'] 	= $total;
				array_push($_journal, $account);
			}
		}

		return collect($_journal)->sortByDesc('normal_balance')->toArray();
	}

	/**
	 * Check the default or settled account id for specific entity name
	 *
	 */
	public static function check_payroll_entity_account_id($employee_id)
	{
		$_entity = Tbl_payroll_entity::get();
		foreach($_entity as $key=>$entity)
		{
			// $data[$entity->entity_name] = Tbl_payroll_journal_tag::where("shop_id", PayrollJournalEntries::getShopId())->where("payroll_entity_id", $entity->payroll_entity_id)->tagEntity()->tagEmployee($employee_id)->account()->first();
			$data[$entity->entity_name] = Tbl_payroll_journal_tag::where("shop_id", PayrollJournalEntries::getShopId())->where("payroll_entity_id", $entity->payroll_entity_id)->tagEntity()->tagEmployee($employee_id)->value("account_id");
			if(!$data[$entity->entity_name])
			{
				$data[$entity->entity_name] = PayrollJournalEntries::get_default_entity($entity->entity_category);
			}
		}

		return collect($data)->toArray();
	}

	public static function get_default_entity($entity_category)
	{
		switch($entity_category)
		{
			case "basic":
				return PayrollJournalEntries::getOtherExpense();
				break;
			case "deminimis":
				return PayrollJournalEntries::getOtherExpense();
				break;
			case "goverment":
				return PayrollJournalEntries::getOtherLiability();
				break;
			case "deductions":
				return PayrollJournalEntries::getOtherExpense();
				break;
		}
	}

	/**
	 * Get Chart of Account - Default for Other Payroll Expense
	 *
	 * @return 	int 	ID
	 */
	public static function getOtherExpense()
	{
		$exist_account = Tbl_chart_of_account::accountType()->where("account_shop_id", Accounting::getShopId())->where("account_code", "payroll-other-expense")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 13;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Other Expense - Payroll";
            $insert["account_description"]      = "Default for Payroll";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-other-expense";
            
            return Tbl_chart_of_account::insertGetId($insert);
            // $id = Tbl_chart_of_account::insertGetId($insert);
            // return Tbl_chart_of_account::accountType()->where("account_id", $id)->first();
        }

        return $exist_account->account_id;
	}

	/**
	 * Get Chart of Account - Default for Other Payroll Asset
	 *
	 * @return 	int 	ID
	 */
	public static function getOtherAsset()
	{
		$exist_account = Tbl_chart_of_account::accountType()->where("account_shop_id", Accounting::getShopId())->where("account_code", "payroll-other-asset")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 3;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Other Current Asset - Payroll";
            $insert["account_description"]      = "Default for Payroll";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-other-asset";
            
            return Tbl_chart_of_account::insertGetId($insert);
            // $id = Tbl_chart_of_account::insertGetId($insert);
            // return Tbl_chart_of_account::accountType()->where("account_id", $id)->first();
        }

        return $exist_account->account_id;
	}

	/**
	 * Get Chart of Account - Default for Othe Payroll Liability
	 *
	 * @return 	int 	ID
	 */
	public static function getOtherLiability()
	{
		$exist_account = Tbl_chart_of_account::accountType()->where("account_shop_id", Accounting::getShopId())->where("account_code", "payroll-other-liability")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 8;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Other Current Liability - Payroll";
            $insert["account_description"]      = "Default for Payroll";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "payroll-other-liability";
            
            return Tbl_chart_of_account::insertGetId($insert);
            // $id = Tbl_chart_of_account::insertGetId($insert);
            // return Tbl_chart_of_account::accountType()->where("account_id", $id)->first();
        }

        return $exist_account->account_id;
	}
}