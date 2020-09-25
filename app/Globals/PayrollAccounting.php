<?php
namespace App\Globals;
use DB;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_chart_account_type;
use App\Models\Tbl_journal_entry;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Models\Tbl_customer;
use App\Models\Tbl_vendor;
use App\Globals\Tablet_global;
use App\Models\Tbl_warehouse;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\carbon;

/**
 * Accounting Module FOR PAYROLL - all accounting related module -accounts
 *
 * @author Arcylen Gutierrez
 */
class PayrollAccounting
{
	public static function postPayrollManualJournalEntries($shop_id ,$date = '',$entry_data = array())
	{
		$return = null;
		if(count($entry_data) > 0)
		{
			$total_credit = 0;
			$total_debit = 0;
			foreach ($entry_data as $key_check => $value_check) 
			{
				if($value_check['account_type'] == 'Credit')
				{
					$total_credit += $value_check['amount'];
				}
				elseif($value_check['account_type'] == 'Debit')
				{
					$total_debit += $value_check['amount'];
				}
			}
			if($total_debit == $total_credit)
			{
				$account_type_string = '';
				foreach ($entry_data as $key => $value) 
				{
					if($value['account_id'])
					{
						$account_type_id = Tbl_chart_of_account::accountInfo($shop_id)->where("account_id", $value['account_id'])->value("chart_type_id");
						$account_type_id == 2 ? $account_type_string.='2' : ($account_type_id == 6 ? $account_type_string.='6' : '');

						$entry["entry_date"] = $date;
						$entry["je_id"]		 = "";
						
						$entry_data[$key]["account_id"] 	= $value['account_id'];
						$entry_data[$key]["type"] 			= $value['account_type'];
						$entry_data[$key]["entry_amount"] 	= $value['amount'];
						$entry_data[$key]["name_id"] 		= "";
						$entry_data[$key]["name_reference"] = "";

					}
				}
				if((strpos($account_type_string, '2') >= 0 && strpos($account_type_string, '2') !== false) && (strpos($account_type_string, '6') >= 0 && strpos($account_type_string, '6') !== false))
				{
					$json["status"]	= "error";
					$json["message"] = "There must be only one Accounts Receivable and/or Accounts Payable";
		    	}
		    	else
		    	{
		    		$je_id = Accounting::postManualJournalEntry($entry, $entry_data, "");

		    		$return = $je_id;
				}
			}
			else
			{
				$return .= "Please balance debits and credits.";
			}
		}
		else
		{
			$return .= "Please fill entry.";
		}
		return $return;
	}
}