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
use App\Globals\CustomerWIS;
use App\Models\Tbl_warehouse;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\carbon;

/**
 * Accounting Module - all accounting related module -accounts
 *
 * @author Bryan Kier Aradanas - cc
 */
class Accounting
{
	public static function getShopId()
	{
		$shop_id = Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');

		if(Tablet_global::getShopId() == $shop_id || $shop_id == null)
		{
			$shop_id = Tablet_global::getShopId();
		}

		return $shop_id;
	}
	public static function getAccountTransaction($filter = 'all', $parent_id = null, $type = null)
	{	
		$shop = Accounting::getShopId();	
		if($parent_id)
		{
			$sublevel 	= Tbl_chart_of_account::where("account_parent_id", $parent_id)->value("account_sublevel");
		}
		else
		{
			$sublevel 	= 0;
			$parent_id 	= null;
		}

		$result = Accounting::checkAccount($shop, $parent_id, $sublevel, $filter, null);

		return $result;
	}

	/**
	 * Getting all the list of accounts including sub-accounts
	 *
	 * @param string  	$filter 	(all, active, inactive)
	 * @param integer  	$parent_id  Id of the Chart of Accoutn where will it start
	 * @param array  	$type      	Filter of type of Chart of Account (eg: Accounts Payable)
	 * @param $search 	$string 	If there is specific account name / number
	 * @param boolean  	$balance    (always true)
	 */
	public static function getAllAccount($filter = 'all', $parent_id = null, $type = null, $search = null, $balance = false, $for_tablet = false)
	{
		$shop = Accounting::getShopId();
		if($for_tablet == true)
        {
            $shop = Tablet_global::getShopId();
        }

		if($parent_id)
		{
			$sublevel 	= Tbl_chart_of_account::where("account_parent_id", $parent_id)->value("account_sublevel");
		}
		else
		{
			$sublevel 	= 0;
			$parent_id 	= null;
		}

		$result = Accounting::checkAccount($shop, $parent_id, $sublevel, $filter, $type, $search);

		return $result;
	}
	public static function get_default_coa($account_code)
	{
		$shop_id = Accounting::getShopId();
		$return_id = Tbl_chart_of_account::where("account_code", $account_code)
									->where("account_shop_id", $shop_id)->value("account_id");
		return $return_id;
	}
	public static function checkAccount($shop, $parent_id, $sublevel, $filter, $type = null, $search = null)
	{
		$query = Tbl_chart_of_account::accountInfo($shop)->balance()->where("account_parent_id", $parent_id)->where("account_sublevel", $sublevel)->orderBy("chart_type_id");

		switch($filter)
		{
			case 'active':
				$query->where("tbl_chart_of_account.archived", 0);
				break;
			case 'inactive':
				$query->where("tbl_chart_of_account.archived", 1);
				break;
		}

		if($type != null) $query->whereIn("chart_type_name", $type);
		if($search != null) $query->where("account_name","like","%".$search."%");

		$query = $query->get();
		$result = array();
		foreach($query as $key => $item)
		{
			$result[$key]["account_id"] 			= $item->account_id;
			$result[$key]["account_number"]			= $item->account_number;
			$result[$key]["account_name"]			= $item->account_name;
			$result[$key]["account_type"] 			= $item->chart_type_name;
			$result[$key]["account_description"] 	= $item->account_description;
			$result[$key]["account_parent_id"] 		= $item->account_parent_id;
			$result[$key]["account_sublevel"] 		= $item->account_sublevel;
			$result[$key]["account_balance"] 		= $item->balance;
			$sub_query = Tbl_chart_of_account::where("account_parent_id", $item->account_id)->first();
			
			if($sub_query)
			{
				$result[$key]["is_sub_count"] = 1;
				$result[$key]["sub_account"] = Accounting::checkAccount($shop, $item->account_id, $sublevel + 1, $filter, $type, $search);
				$result[$key]["account_new_balance"] = $item->balance + collect($result[$key]["sub_account"])->sum('account_new_balance');
			}
			else
			{
				$result[$key]["is_sub_count"] = 0;
				$result[$key]["sub_account"] = null;
				$result[$key]["account_new_balance"] = $item->balance;
			}
		}

		if($query->count() > 0) return $result;
		else 					return $result;
	}

	/**
	 *
	 * @param string  	$account_id 	account id of specific account
	 */
	public static function getAccount($account_id)
	{
		$shop = Accounting::getShopId();

		$result = Tbl_chart_of_account::accountInfo($shop)->where("account_id",$account_id)->first(['account_id', 'account_name', 'chart_type_name', 'account_description'])->toArray();
		// dd($result);
		return json_encode($result);
	}

	public static function getItemAccount($item_id)
	{
		$asset 		= Tbl_item::where("item_id", $item_id)->accountAsset()
								->first(['account_id','account_name','chart_type_name','account_description'])->toArray();
		$income 	= Tbl_item::where("item_id", $item_id)->accountIncome()
								->first(['account_id','account_name','chart_type_name','account_description'])->toArray();
		$expense 	= Tbl_item::where("item_id", $item_id)->accountExpense()
								->first(['account_id','account_name','chart_type_name','account_description'])->toArray();
		$result["income"] 				= $income;
		$result["asset"] 				= $asset;
		$result["cost_of_good_sold"] 	= $expense;

		dd($result);
	}
	
	/**
	 * Create a journal entry for the transaction 
	 *
	 * @param array  	$entry 			$entry["reference_module"] , $entry["reference_id"] , $entry["name_id"], $entry["name_reference"] $entry["total"] , 
	 *									$entry["vatable"] , $entry["discount"] , $entry["ewt"], $entry["account_id"]
	 * @param array  	$entry_data     $entry_data[0]['item_id'] or $entry_data[0]['account_id'], $entry_data[0]['vatable']
	 *									$entry_data[0]['discount'] , $entry_data[0]['entry_amount'] , $entry_data[0]['entry_desription']
	 * @param boolean  	$remarks   		Description of the journal entry
	 */
	public static function postJournalEntry($entry, $entry_data, $remarks = '', $for_tablet = false)
	{
		$shop_id = Accounting::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
		/* GETTING THE DEFAULT ACCOUNTS RECEIVABLE AND ACCOUNTS PAYABLE */
		$account_receivable			= Tbl_chart_of_account::accountInfo($shop_id)->where("account_code","accounting-receivable")->value("account_id");
		$account_payable			= Tbl_chart_of_account::accountInfo($shop_id)->where("account_code","accounting-payable")->value("account_id");
		$account_cash				= Accounting::getCashInBank();
		$account_inventory_asset	= Tbl_chart_of_account::accountInfo($shop_id)->where("account_code","accounting-inventory-asset")->value("account_id");
		$account_opening_balance_equity	= Tbl_chart_of_account::accountInfo($shop_id)->where("account_code","accounting-open-balance-equity")->value("account_id");

		/* FOR OLD DATABASE - CHECKING IF THERE IS ALREADY AN ACCOUNT CODE*/
		if(!$account_receivable)
		{
			Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_name", "Accounts Receivable")->update(['account_code'=>"accounting-receivable"]);
			$account_receivable	= Tbl_chart_of_account::accountInfo($shop_id)->where("account_code","accounting-receivable")->value("account_id");
		}
		if(!$account_payable)
		{
			Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_name", "Accounts Payable")->update(['account_code'=>"accounting-payable"]);
			$account_payable	= Tbl_chart_of_account::accountInfo($shop_id)->where("account_code","accounting-payable")->value("account_id");
		}
		/* END */

		/* IF THERE IS A SPECIFIED ACCOUNT ID FOR THE MAIN ACCOUNT (ACCOUNT THAT IS SELECTED IN THE TRANSACTION | OVERWRITE THE DEFAULT VALUE OF ACCOUNTS RECEIVABLE OR PAYABLE) */ /* !!!! FOR NOW IT IS FOR CASH ONLY */ 
		if(isset($entry["account_id"]))
		{
			$account_cash = $entry["account_id"];
		}

		/* INSERT JOURNAL ENTRY */
		$journal_entry['je_shop_id'] 			= $shop_id;
		$journal_entry['je_reference_module'] 	= $entry["reference_module"];
		$journal_entry['je_reference_id'] 		= $entry["reference_id"];
		$journal_entry['je_entry_date'] 		= isset($entry['txn_date']) ? $entry['txn_date'] : carbon::now();
		$journal_entry['je_remarks']			= $remarks;
		// dd($entry, $entry_data);
		/* CHECK IF THE TRANSACTION JOURNAL IS ALREADY EXIST - USE IF NEW OR UPDATE TRANSACTION */
		$exist_journal = Tbl_journal_entry::where("je_reference_module", $journal_entry['je_reference_module'])->where("je_reference_id", $journal_entry['je_reference_id'])->first();
		if(!$exist_journal)
		{
			$journal_entry['created_at']	= carbon::now();
			$line_data["je_id"] 			= Tbl_journal_entry::insertGetId($journal_entry);
		}
		else
		{
			// unset($journal_entry['je_entry_date']);
			$journal_entry['updated_at']	= carbon::now();
			Tbl_journal_entry_line::where("jline_je_id", $exist_journal->je_id)->delete();
			Tbl_journal_entry::where("je_id", $exist_journal->je_id)->update($journal_entry);
			$line_data["je_id"] = $exist_journal->je_id;
		}

		$line_data["item_id"]				= '';
		if(isset($entry["name_reference"]))	$line_data["jline_name_reference"] = $entry["name_reference"];
		else   $line_data["jline_name_reference"] 	= Accounting::checkTransaction($entry["reference_module"])['name'];
		$line_data["jline_name_id"]			= $entry["name_id"];

		/* RECIVABLE OR PAYABLE OR CASH */
		$main_account 		= Accounting::checkTransaction($entry["reference_module"])['main_account'];
		$newNormalBalance 	= Accounting::checkTransaction($entry["reference_module"])['newNormalJournal'];
		$newContraBalance 	= Accounting::checkTransaction($entry["reference_module"])['newContraJournal'];

		if($main_account == 'receivable' || $main_account == 'cash-r')
		{
			if($main_account == 'receivable') $main_account_id = $account_receivable;
			elseif($main_account == 'cash-r') $main_account_id = $account_cash;

			$line_data["entry_amount"]	= $entry["total"];
			$line_data["entry_type"] 	= Accounting::$newNormalBalance($main_account_id);
			$line_data["account_id"] 	= $main_account_id;
			Accounting::insertJournalLine($line_data);
			/* DISCOUNT AS WHOLE */
			if(isset($entry["discount"]))
			{
				if($entry["discount"] > 0)
				{
					$line_data["entry_amount"]	= $entry["discount"];
					$line_data["entry_type"] 	= Accounting::$newContraBalance(Accounting::getDiscountSale());
					$line_data["account_id"] 	= Accounting::getDiscountSale();
					Accounting::insertJournalLine($line_data);
				}
			}

			/* VATABLE AS WHOLE */
			if(isset($entry["vatable"]))
			{
				if($entry["vatable"] > 0)
				{
					$line_data["entry_amount"]	= $entry["vatable"];
					$line_data["entry_type"] 	= Accounting::$newNormalBalance(Accounting::getOutputVatPayable());
					$line_data["account_id"] 	= Accounting::getOutputVatPayable();
					Accounting::insertJournalLine($line_data);
				}
			}

			/* EWT AS WHOLE */
			if(isset($entry["ewt"]))
			{
				if($entry["ewt"] > 0)
				{
					$line_data["entry_amount"]	= $entry["ewt"];
					$line_data["entry_type"] 	= Accounting::$newNormalBalance(Accounting::getWitholdingTax());
					$line_data["account_id"] 	= Accounting::getWitholdingTax();
					Accounting::insertJournalLine($line_data);
				}
			}
		}
		elseif($main_account == 'payable' /*|| $main_account == 'cash-p'*/)
		{
			if($main_account == 'payable') $main_account_id = $account_payable;
			/*elseif($main_account == 'cash-p') $main_account_id = $account_cash;*/

			$line_data["entry_amount"]	= $entry["total"];
			//die(var_dump($line_data["entry_amount"]));
			$line_data["entry_type"] 	= Accounting::$newNormalBalance($main_account_id);
			$line_data["account_id"] 	= $main_account_id;
			Accounting::insertJournalLine($line_data);
			//dd($line_data);
			/* DISCOUNT AS WHOLE */
			if(isset($entry["discount"]))
			{
				if($entry["discount"] > 0)
				{
					$line_data["entry_amount"]	= $entry["discount"];
					$line_data["entry_type"] 	= Accounting::$newNormalBalance(Accounting::getDiscountPurchase());
					$line_data["account_id"] 	= Accounting::getDiscountPurchase();
					Accounting::insertJournalLine($line_data);
				}
			}

			/* VATABLE AS WHOLE */
			if(isset($entry["vatable"]))
			{
				if($entry["vatable"] > 0)
				{
					$line_data["entry_amount"]	= $entry["vatable"];
					$line_data["entry_type"] 	= Accounting::$newNormalBalance(Accounting::getInputVatPayable());
					$line_data["account_id"] 	= Accounting::getInputVatPayable();
					//die(var_dump($line_date));
					Accounting::insertJournalLine($line_data);
				}
			}

			/* EWT AS WHOLE */
			if(isset($entry["ewt"]))
			{
				if($entry["ewt"] > 0)
				{
					$line_data["entry_amount"]	= $entry["ewt"];
					$line_data["entry_type"] 	= Accounting::$newNormalBalance(Accounting::getWitholdingTax());
					$line_data["account_id"] 	= Accounting::getWitholdingTax();
					Accounting::insertJournalLine($line_data);
				}
			}
		}
		elseif($main_account == 'cash-p')
		{
			if(($entry['name_reference']) == 'vendor')
			{
				if($main_account == 'cash-p') $main_account_id = $account_cash;

				$line_data["entry_amount"]	= $entry["total"];
				$line_data["entry_type"] 	= Accounting::$newNormalBalance($main_account_id);
				$line_data["account_id"] 	= $main_account_id;
				Accounting::insertJournalLine($line_data);
				
				/* DISCOUNT AS WHOLE */
				if(isset($entry["discount"]))
				{
					if($entry["discount"] > 0)
					{
						$line_data["entry_amount"]	= $entry["discount"];
						$line_data["entry_type"] 	= Accounting::$newContraBalance(Accounting::getDiscountPurchase());
						$line_data["account_id"] 	= Accounting::getDiscountPurchase();
						Accounting::insertJournalLine($line_data);
					}
				}

				/* VATABLE AS WHOLE */
				if(isset($entry["vatable"]))
				{
					if($entry["vatable"] > 0)
					{
						$line_data["entry_amount"]	= $entry["vatable"];
						$line_data["entry_type"] 	= Accounting::$newContraBalance(Accounting::getInputVatPayable());
						$line_data["account_id"] 	= Accounting::getInputVatPayable();
						//die(var_dump($line_date));
						Accounting::insertJournalLine($line_data);
					}
				}
			}
			elseif(($entry['name_reference']) == 'customer')
			{
				if($main_account == 'cash-p') $main_account_id = $account_cash;

				$line_data["entry_amount"]	= $entry["total"];
				$line_data["entry_type"] 	= Accounting::$newNormalBalance($main_account_id);
				$line_data["account_id"] 	= $main_account_id;
				Accounting::insertJournalLine($line_data);
				/* DISCOUNT AS WHOLE */
				if(isset($entry["discount"]))
				{
					if($entry["discount"] > 0)
					{
						$line_data["entry_amount"]	= $entry["discount"];
						$line_data["entry_type"] 	= Accounting::$newNormalBalance(Accounting::getDiscountSale());
						$line_data["account_id"] 	= Accounting::getDiscountSale();
						Accounting::insertJournalLine($line_data);
					}
				}

				/* VATABLE AS WHOLE */
				if(isset($entry["vatable"]))
				{
					if($entry["vatable"] > 0)
					{
						$line_data["entry_amount"]	= $entry["vatable"];
						$line_data["entry_type"] 	= Accounting::$newContraBalance(Accounting::getOutputVatPayable());
						$line_data["account_id"] 	= Accounting::getOutputVatPayable();
						Accounting::insertJournalLine($line_data);
					}
				}
			}
		}
		elseif($main_account == 'inventory-asset')
		{
			$line_data["entry_amount"]	= $entry["total"];
			$line_data["entry_type"] 	= Accounting::$newNormalBalance($account_inventory_asset);
			$line_data["account_id"] 	= $account_inventory_asset;
			Accounting::insertJournalLine($line_data);
		}
		elseif($main_account == 'accounting-open-balance-equity')
		{
			$line_data["entry_amount"]	= $entry["total"];
			$line_data["entry_type"] 	= Accounting::$newNormalBalance($account_opening_balance_equity);
			$line_data["account_id"] 	= $account_opening_balance_equity;

			Accounting::insertJournalLine($line_data);

			if(isset($entry_data["item_id"]))
			{
				$item = Tbl_item::where("item_id", $entry_data["item_id"])->first();
				$line_data["item_id"] = $item->item_id;
				/* GETTING CHART OF ACCOUNTS THAT TAGGED ON THE ITEM */
				$account_asset 		= Tbl_item::where("item_id", $entry_data["item_id"])->value("item_asset_account_id");   //Inventory 
			}
			if($item->item_type_id == 1) // INVENTORY TYPE
			{
				$line_data["entry_amount"]	= $entry_data["entry_amount"];
				$line_data["entry_type"] 	= Accounting::normalBalance($account_asset);
				$line_data["account_id"] 	= $account_asset;
				Accounting::insertJournalLine($line_data);
			}
		}

		foreach($entry_data as $entry_line)
		{
			/* IF ITEM ID OR ACCOUNT ID */
			if(isset($entry_line["item_id"]))
			{
				$item = Tbl_item::where("item_id", $entry_line["item_id"])->first();
				$line_data["item_id"] = $entry_line["item_id"];

				/* GETTING CHART OF ACCOUNTS THAT TAGGED ON THE ITEM */
				$account_asset 		= Tbl_item::where("item_id", $entry_line["item_id"])->value("item_asset_account_id");   //Inventory 
				$account_income 	= Tbl_item::where("item_id", $entry_line["item_id"])->value("item_income_account_id");  //Sales
				$account_expense 	= Tbl_item::where("item_id", $entry_line["item_id"])->value("item_expense_account_id"); //Cost of Good Sold

			}
			elseif(isset($entry_line["account_id"]))
			{
				$account = Tbl_chart_of_account::type()->where("account_id", $entry_line["account_id"])->first();
			}

			/* ENTRY DESCRIPTION */
			$line_data["entry_description"] = isset($entry_line["entry_description"]) ? $entry_line["entry_description"] : '';
			
			// if($item->item_type_id != 4) // ITEM IS NOT A BUNDLE
			// {
				switch($entry["reference_module"])
				{
					case "item":
						break;
					case "estimate": // NON-POSTING
						break;
					case "sales-order": // NON-POSTING
						break;
					case "mlm-product-repurchase":
						break;
					case "product-order":
						break;
					case "sales-receipt":
						/* INCOME ACCOUNT */
						$line_data["entry_amount"]	= $entry_line["entry_amount"];
						$line_data["entry_type"] 	= Accounting::normalBalance($account_income);
						$line_data["account_id"]	= $account_income;
						Accounting::insertJournalLine($line_data);

						if(CustomerWIS::settings($shop_id) == 0)
						{
							if($item->item_type_id == 1) // INVENTORY TYPE
							{
								/* EXPENSE ACCOUNT */
								$line_data["entry_amount"]	= $entry_line["entry_amount"];
								$line_data["entry_type"] 	= Accounting::normalBalance($account_expense);
								$line_data["account_id"] 	= $account_expense;
								Accounting::insertJournalLine($line_data);

								/* ASSET ACCOUNT */
								$line_data["entry_amount"]	= $entry_line["entry_amount"];
								$line_data["entry_type"] 	= Accounting::contraAccount($account_asset);
								$line_data["account_id"] 	= $account_asset;
								Accounting::insertJournalLine($line_data);
							}							
						}

						if(isset($entry_line["discount"]))
						{
							if($entry_line["discount"] > 0)
							{
								$line_data["entry_amount"]	= $entry_line["discount"];
								$line_data["entry_type"] 	= Accounting::contraAccount(Accounting::getDiscountSale());
								$line_data["account_id"] 	= Accounting::getDiscountSale();
								Accounting::insertJournalLine($line_data);
							}
						}
						break;
					case "warehouse-issuance-slip":
						if(CustomerWIS::settings($shop_id) == 1)
						{
							if($item->item_type_id == 1) // INVENTORY TYPE
							{
								/* EXPENSE ACCOUNT */
								$line_data["entry_amount"]	= $entry_line["entry_amount"];
								$line_data["entry_type"] 	= Accounting::normalBalance($account_expense);
								$line_data["account_id"] 	= $account_expense;
								if($line_data["account_id"] > 0)
								{
									Accounting::insertJournalLine($line_data);
								}

								/* ASSET ACCOUNT */
								// $line_data["entry_amount"]	= $item->item_cost;
								// $line_data["entry_type"] 	= Accounting::contraAccount($account_asset);
								// $line_data["account_id"] 	= $account_asset;
								// Accounting::insertJournalLine($line_data);
							}
						}
						break;
					case "invoice":
						/* INCOME ACCOUNT */
						$line_data["entry_amount"]	= $entry_line["entry_amount"];
						$line_data["entry_type"] 	= Accounting::normalBalance($account_income);
						$line_data["account_id"]	= $account_income;
						Accounting::insertJournalLine($line_data);

						if(CustomerWIS::settings($shop_id) == 0)
						{
							if($item->item_type_id == 1) // INVENTORY TYPE
							{
								/* EXPENSE ACCOUNT */
								$line_data["entry_amount"]	= $entry_line["entry_amount"];
								$line_data["entry_type"] 	= Accounting::normalBalance($account_expense);
								$line_data["account_id"] 	= $account_expense;
								Accounting::insertJournalLine($line_data);

								/* ASSET ACCOUNT */
								$line_data["entry_amount"]	= $entry_line["entry_amount"];
								$line_data["entry_type"] 	= Accounting::contraAccount($account_asset);
								$line_data["account_id"] 	= $account_asset;
								Accounting::insertJournalLine($line_data);
							}							
						}
						if(isset($entry_line["discount"]))
						{
							if($entry_line["discount"] > 0)
							{
								$line_data["entry_amount"]	= $entry_line["discount"];
								$line_data["entry_type"] 	= Accounting::contraAccount(Accounting::getDiscountSale());
								$line_data["account_id"] 	= Accounting::getDiscountSale();
								Accounting::insertJournalLine($line_data);
							}
						}

						break;
					case "receive-payment":
						/* CASH ACCOUNT - BANK */
						$line_data["entry_amount"]	= $entry_line["entry_amount"];
						$line_data["entry_type"] 	= Accounting::normalBalance($account->account_id);
						$line_data["account_id"] 	= $account->account_id;
						Accounting::insertJournalLine($line_data);
						break;
					case "bill-payment":
						/* CASH ACCOUNT - BANK */
						$line_data["entry_amount"]	= $entry_line["entry_amount"];
						$line_data["entry_type"] 	= Accounting::contraAccount($account->account_id);
						$line_data["account_id"] 	= $account->account_id;
						Accounting::insertJournalLine($line_data);
						break;
					case "purchase-order": // NON-POSTING
						break;
					case "write-check":
						if(($entry['name_reference']) == 'vendor')
						{
							if(isset($item->item_type_id) ) // INVENTORY TYPE
							{
								if($item->item_type_id == 1)
								{
									/* ASSET ACCOUNT */
									$line_data["entry_amount"]	= $entry_line["entry_amount"];
									$line_data["entry_type"] 	= Accounting::normalBalance($account_asset);
									$line_data["account_id"] 	= $account_asset;
									Accounting::insertJournalLine($line_data);								
								}
								else
								{
									/* EXPENSE ACCOUNT */
									$line_data["entry_amount"]	= $entry_line["entry_amount"];
									$line_data["entry_type"] 	= Accounting::normalBalance($account_expense);
									$line_data["account_id"] 	= $account_expense;
									Accounting::insertJournalLine($line_data);
								}
							}
							else
							{
								/* EXPENSE ACCOUNT */
								$line_data["entry_amount"]	= $entry_line["entry_amount"];
								$line_data["entry_type"] 	= Accounting::normalBalance($account->account_id);
								$line_data["account_id"] 	= $account->account_id;
								Accounting::insertJournalLine($line_data);							
							}
						}
						elseif(($entry['name_reference']) == 'customer')
						{
							/*dd(23);
							die(var_dump(1230))*/
							if(isset($item->item_type_id) ) // INVENTORY TYPE
							{
								if($item->item_type_id == 1)
								{
									/* ASSET ACCOUNT */
									$line_data["entry_amount"]	= $entry_line["entry_amount"];
									$line_data["entry_type"] 	= Accounting::contraAccount($account_asset);
									$line_data["account_id"] 	= $account_asset;

									Accounting::insertJournalLine($line_data);								
								}
								else
								{
									/* EXPENSE ACCOUNT */
									$line_data["entry_amount"]	= $entry_line["entry_amount"];
									/*die(var_dump($account_expense));*/
									$line_data["entry_type"] 	= Accounting::normalBalance($account_expense);
									$line_data["account_id"] 	= $account_expense;
									Accounting::insertJournalLine($line_data);
								}
							}
							else
							{
								/* EXPENSE ACCOUNT */
								$line_data["entry_amount"]	= $entry_line["entry_amount"];
								$line_data["entry_type"] 	= Accounting::normalBalance($account->account_id);
								$line_data["account_id"] 	= $account->account_id;
								Accounting::insertJournalLine($line_data);					
							}
						}
						
						//write-check
						//$entry['reference_module'] = "write-check";
						break;
					case "bill":
						if(isset($item->item_type_id)) // INVENTORY TYPE
						{
							if($item->item_type_id == 1)
							{
								/* ASSET ACCOUNT */
								$line_data["entry_amount"]	= $entry_line["entry_amount"];
								$line_data["entry_type"] 	= Accounting::normalBalance($account_asset);
								$line_data["account_id"] 	= $account_asset;
								Accounting::insertJournalLine($line_data);								
							}
							else
							{
								/* EXPENSE ACCOUNT */
								$line_data["entry_amount"]	= $entry_line["entry_amount"];
								$line_data["entry_type"] 	= Accounting::normalBalance($account_expense);
								$line_data["account_id"] 	= $account_expense;
								Accounting::insertJournalLine($line_data);
							}
						}
						else
						{
							/* EXPENSE ACCOUNT */
							$line_data["entry_amount"]	= $entry_line["entry_amount"];
							$line_data["entry_type"] 	= Accounting::normalBalance($account->account_id);
							$line_data["account_id"] 	= $account->account_id;
							Accounting::insertJournalLine($line_data);							
						}
						break;
					case "debit-memo":
						if($item->item_type_id == 1) // INVENTORY TYPE
						{
							/* ASSET ACCOUNT */
							$line_data["entry_amount"]	= $entry_line["entry_amount"];
							$line_data["entry_type"] 	= Accounting::contraAccount($account_asset);
							$line_data["account_id"] 	= $account_asset;
							Accounting::insertJournalLine($line_data);
						}
						else
						{
							/* EXPENSE ACCOUNT */
							$line_data["entry_amount"]	= $entry_line["entry_amount"];
							$line_data["entry_type"] 	= Accounting::contraAccount($account_expense);
							$line_data["account_id"] 	= $account_expense;
							Accounting::insertJournalLine($line_data);
						}
						break;
					case "credit-memo":
						/* INCOME ACCOUNT */
						$line_data["entry_amount"]	= $entry_line["entry_amount"];
						$line_data["entry_type"] 	= Accounting::contraAccount($account_income);
						$line_data["account_id"] 	= $account_income;
						Accounting::insertJournalLine($line_data);

						if($item->item_type_id == 1)
						{
							/* EXPENSE ACCOUNT */
							$line_data["entry_amount"]	= $item->item_cost;
							$line_data["entry_type"] 	= Accounting::contraAccount($account_expense);
							$line_data["account_id"] 	= $account_expense;
							Accounting::insertJournalLine($line_data);

							/* ASSET ACCOUNT */
							$line_data["entry_amount"]	= $item->item_cost;
							$line_data["entry_type"] 	= Accounting::normalBalance($account_asset);
							$line_data["account_id"] 	= $account_asset;
							Accounting::insertJournalLine($line_data);
						}
						break;
					case "deposit":
						/* OPENING BALANCE EQUITY */
						$account ? $account : $account = Accounting::getOpenBalanceEquity();

						$line_data["entry_amount"]	= $entry_line["entry_amount"];
						$line_data["entry_type"] 	= Accounting::normalBalance($account);
						$line_data["account_id"] 	= $account;
						Accounting::insertJournalLine($line_data);
						break;	
					// SO ON
				}
			// }
		}
		//die(var_dump($line_data["je_id"]));
		return $line_data["je_id"];
	}

	/**
	 * Create a manual journal entry for the transaction type "manual"
	 *
	 * @param array  	$entry 			$entry['entry_date'], $entry['je_id'] (NULL or not null)
	 * @param array  	$entry_data     $entry_data[0]['account_id'], $entry_data[0]['type'] , 
	 *									$entry_data[0]['entry_amount'], $entry_data[0]['name_id'], $entry_data[0]['name_reference']
	 * @param boolean  	$remarks   		Description of the journal entry	
	 */
	public static function postManualJournalEntry($entry, $entry_data, $remarks = '')
	{
		/* INSERT JOURNAL ENTRY */
		$journal_entry['je_shop_id'] 			= Accounting::getShopId();
		$journal_entry['je_reference_module'] 	= "journal-entry";
		$journal_entry['je_entry_date'] 		= $entry['entry_date'];
		$journal_entry['created_at'] 			= Carbon::now();
		$journal_entry['je_remarks']			= $remarks;
		
		/* CHECK IF JOURNAL EXIST - IF THERE IS A JOURNAL ID */
		if(!$entry['je_id'])
		{
			$line_data["je_id"] 	= Tbl_journal_entry::insertGetId($journal_entry);
			Tbl_journal_entry::where("je_id", $line_data["je_id"])->update(['je_reference_id'=>$line_data["je_id"]]);

			$old_transaction_data = AccountingTransaction::audit_trail(Accounting::getShopId(), $line_data["je_id"], 'manual_journal_entry');
			AuditTrail::record_logs('Added', 'manual_journal_entry', $line_data["je_id"], "", serialize($old_transaction_data));
		}
		else
		{
			$old_transaction = AccountingTransaction::audit_trail(Accounting::getShopId(), $entry['je_id'], 'manual_journal_entry');
			Tbl_journal_entry_line::where("jline_je_id", $entry['je_id'])->delete();
			Tbl_journal_entry::where("je_id", $entry['je_id'])->update($journal_entry);
			$line_data["je_id"] = $entry['je_id'];

			$transaction_data = AccountingTransaction::audit_trail(Accounting::getShopId(), $entry['je_id'], 'manual_journal_entry');
            AuditTrail::record_logs('Edited', 'manual_journal_entry', $entry['je_id'], serialize($old_transaction), serialize($transaction_data));
		}
		
		foreach($entry_data as $line)
		{
			$line_data["jline_name_id"]			= $line["name_id"];
			$line_data["jline_name_reference"]	= $line["name_reference"];
			$line_data["item_id"]				= 0;
			$line_data["account_id"]			= $line["account_id"];
			$line_data["entry_type"]			= $line["type"];
			$line_data["entry_amount"]			= convertToNumber($line["entry_amount"]);

			Accounting::insertJournalLine($line_data);
		}

		return $line_data["je_id"];
	}
	
	public static function insertJournalLine($line)
	{
		$journal_line['jline_je_id']			= $line["je_id"];
		$journal_line['jline_name_id']			= $line["jline_name_id"];
		$journal_line['jline_name_reference']	= $line["jline_name_reference"];
		$journal_line['jline_item_id'] 			= $line["item_id"];
		$journal_line['jline_account_id'] 		= $line["account_id"];
		if($line["entry_type"] == null){ $line["entry_type"] = ''; }
		$journal_line['jline_type'] 			= $line["entry_type"];
		$journal_line['jline_amount'] 			= $line["entry_amount"];
		$journal_line['jline_description'] 		= isset($line["entry_description"]) ? $line["entry_description"] : '';
		$journal_line["created_at"]				= Carbon::now();
		$journal_line['jline_warehouse_id'] 	= Accounting::getWarehouse($line["item_id"], $line["je_id"]);
		$jline_id = Tbl_journal_entry_line::insertGetId($journal_line);
		
	}


	/**
	 * Check if the has warehouse is an inventory type
	 *
	 * @param 	integer  	$item_id 		id of item
	 * @author 	LUKE
	 */
	public static function getWarehouse($item_id, $je_id)
	{
		$item = Tbl_item::where('item_id', $item_id)->first();

		if($item)
		{
			switch ($item->item_type_id) {
				case 1:
					$j_e = Tbl_journal_entry::where('je_id', $je_id)->first();
					if($j_e)
					{
						if($j_e->je_reference_module == 'product-order')
						{
							$warehouse = Tbl_warehouse::where('warehouse_shop_id', $item->shop_id)
							->where('main_warehouse', 2)
							->first();
							if($warehouse)
							{
								return $warehouse->warehouse_id;
							}
							else
							{
								return 0;
							}
						}
						else
						{
							$session_warehouse = session("warehouse_id_".$item->shop_id);
							if($session_warehouse){ return $session_warehouse; }
							else{ return 0;}
						}
					}
					else
					{
						return 0;
					}
					
				break;
				
				default:
						return 0;
				break;
			}
		}
		else{ return 0; }
		
	}
	/**
	 * Check transaction whether it is customer or vendor type; normal balace or contra account; receivable or payable;
	 *
	 * @param 	string  	$type 		Type of a transaction
	 * @return 	array[3]	is_receivable | name | journal	
	 */
	public static function checkTransaction($type)
	{
		switch($type)
		{
			case 'estimate':
			case 'sales-order':
			case "item":
				$data["main_account"]		= 'accounting-open-balance-equity';
				$data["name"] 				= 'item';
				$data["newNormalJournal"] 	= 'normalBalance';
				$data["newContraJournal"] 	= 'contraAccount';
				return $data;
				break;
			case 'warehouse-issuance-slip':
				$data["main_account"]		= 'inventory-asset';
				$data["name"] 				= 'customer';
				$data["newNormalJournal"] 	= 'contraAccount';
				$data["newContraJournal"] 	= 'normalBalance';
				return $data;
				break;
			case 'invoice':
				$data["main_account"]		= 'receivable';
				$data["name"] 				= 'customer';
				$data["newNormalJournal"] 	= 'normalBalance';
				$data["newContraJournal"] 	= 'contraAccount';
				return $data;
				break;
			case 'receive-payment':
			case 'credit-memo':
				$data["main_account"]		= 'receivable';
				$data["name"] 				= 'customer';
				$data["newNormalJournal"] 	= 'contraAccount';
				$data["newContraJournal"] 	= 'normalBalance';
				return $data;
				break;
			case 'mlm-product-repurchase':
			case 'sales-receipt':
			case 'product-order':
				$data["main_account"]		= 'cash-r';
				$data["name"] 				= 'customer';
				$data["newNormalJournal"] 	= 'normalBalance';
				$data["newContraJournal"] 	= 'contraAccount';
				return $data;
				break;
			case 'purchase-order':
			case 'bill':
				$data["main_account"]		= 'payable';
				$data["name"] 				= '';
				$data["newNormalJournal"] 	= 'normalBalance';
				$data["newContraJournal"] 	= 'contraAccount';
				return $data;
				break;
			case 'write-check':
				$data["main_account"]		= 'cash-p';
				$data["name"] 				= '';
				$data["newNormalJournal"] 	= 'contraAccount';
				$data["newContraJournal"] 	= 'normalBalance';
				return $data;
				break;
			case 'debit-memo':
			case 'bill-payment':
				$data["main_account"]		= 'payable';
				$data["name"] 				= 'vendor';
				$data["newNormalJournal"] 	= 'contraAccount';
				$data["newContraJournal"] 	= 'normalBalance';
				return $data;
				break;
			case 'deposit':
				$data["main_account"]		= 'cash-r'; //CASH - RECEIVABLE
				$data["name"] 				= '';
				$data["newNormalJournal"] 	= 'normalBalance';
				$data["newContraJournal"] 	= 'contraAccount';
				return $data;
				break;
			default:
				$data = null;
		}
	}

	/**
	 * Getting normal balance of the given account
	 *
	 * @param 	string  	$account_id 	account id of specific account
	 * @return 	string 						Credit or Debit
	 */
	public static function normalBalance($account_id)
	{
		$balance = Tbl_chart_of_account::type()->where("account_id", $account_id)->value("normal_balance");
		if($balance == "credit") 	return 'Credit';
		elseif($balance == "debit") return 'Debit';
	}

	/**
	 * Getting reverse normal balance of the given account
	 *
	 * @param 	string  	$account_id 	account id of specific account
	 * @return 	string 						Credit or Debit
	 */
	public static function contraAccount($account_id)
	{
		$balance = Tbl_chart_of_account::type()->where("account_id", $account_id)->value("normal_balance");
		if($balance == "credit") 	return 'Debit';
		elseif($balance == "debit") return 'Credit';
	}

	/**
	 * Check what table reference and type ( !!! CURRENTLY FUNCTION NOT IN USE !!! )
	 *
	 * @param 	string  	$type 		Type of a transaction
	 * @return 	array[2] 	$table_name | id_name
	 */
	public static function checkReferenceId($type)
	{
		switch($type)
		{
			case 'invoice':
				$data["table_txn_reference"] 	= 'Tbl_customer_invoice';
				$data["txn_id"]					= 'je_id';			
				$data["table_name_reference"]	= 'Tbl_customer';
				$data["name_id"]				= 'customer_id';
				break;
			case 'purchase_order':
				$data["table_txn_reference"] 	= 'Tbl_purchase_order';
				$data["txn_id"]					= 'je_id';			
				$data["table_name_reference"]	= 'Tbl_vendor';
				$data["name_id"]				= 'vendor_id';
				break;
			case 'receive-payment':
				break;
			default:
				$data = null;
		}

		return $data;
	}

	/**
	 * Get Chart of Account - Output Vat Payable for Vatable transaction
	 *
	 * @return 	int 	ID
	 */
	public static function getOutputVatPayable()
	{
		$exist_account = Tbl_chart_of_account::where("account_shop_id", Accounting::getShopId())->where("account_code", "tax-output-vat-payable")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 8;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Output Vat Payable";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "tax-output-vat-payable";
            
            return Tbl_chart_of_account::insertGetId($insert);
        }

        return $exist_account->account_id;
	}
	public static function getInputVatPayable()
	{
		$exist_account = Tbl_chart_of_account::where("account_shop_id", Accounting::getShopId())->where("account_code", "tax-input-vat-payable")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 3;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Input Vat";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "tax-input-vat-payable";
            
            return Tbl_chart_of_account::insertGetId($insert);
        }

        return $exist_account->account_id;
	}

	public static function getFreightOut()
	{
		$exist_account = Tbl_chart_of_account::where("account_shop_id", Accounting::getShopId())->where("account_code", "freight-out")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 13; // Expense
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Freight-out";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "freight-out";
            
            return Tbl_chart_of_account::insertGetId($insert);
        }

        return $exist_account->account_id;
	}

	/**
	 * Get Chart of Account - Creditable witholding tax for Witholding transaction
	 *
	 * @return 	int 	ID
	 */
	public static function getWitholdingTax()
	{
		$exist_account = Tbl_chart_of_account::where("account_shop_id", Accounting::getShopId())->where("account_code", "tax-credit-tax-1")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 3;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Creditable Withholding Tax - 1%";
            $insert["account_description"]      = "";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "tax-credit-tax-1";
            
            return Tbl_chart_of_account::insertGetId($insert);
        }

        return $exist_account->account_id;
	}

	/**
	 * Get Chart of Account - Discount for Discounted Sale transaction
	 *
	 * @return 	int 	ID
	 */
	public static function getDiscountSale()
	{
		$exist_account = Tbl_chart_of_account::where("account_shop_id", Accounting::getShopId())->where("account_code", "discount-sale")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 11;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Sale Discount";
            $insert["account_description"]      = "Sale Discount";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "discount-sale";
            
            return Tbl_chart_of_account::insertGetId($insert);
        }

        return $exist_account->account_id;
	}

	/**
	 * Get Chart of Account - Discount for Discounted Purchase transaction
	 *
	 * @return 	int 	ID
	 */
	public static function getDiscountPurchase()
	{
		$exist_account = Tbl_chart_of_account::where("account_shop_id", Accounting::getShopId())->where("account_code", "discount-purchase")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 16;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Purchase Discount";
            $insert["account_description"]      = "Purchase Discount";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "discount-purchase";
            
            return Tbl_chart_of_account::insertGetId($insert);
        }

        return $exist_account->account_id;
	}

	/**
	 * Get Chart of Account - Cash In Bank for transaction like sales receipt
	 *
	 * @return 	int 	ID
	 */
	public static function getCashInBank()
	{
		$exist_account = Tbl_chart_of_account::where("account_shop_id", Accounting::getShopId())->where("account_code", "accounting-cash-in-bank")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 1;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Cash In Bank";
            $insert["account_description"]      = "Default Bank";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "accounting-cash-in-bank";
            
            return Tbl_chart_of_account::insertGetId($insert);
        }

        return $exist_account->account_id;
	}

	/**
	 * Get Chart of Account - For All Opening Balance Equity
	 *
	 * @return 	int 	ID
	 */
	public static function getOpenBalanceEquity()
	{
		$exist_account = Tbl_chart_of_account::where("account_shop_id", Accounting::getShopId())->where("account_code", "accounting-open-balance-equity")->first();
        if(!$exist_account)
        {
            $insert["account_shop_id"]          = Accounting::getShopId();
            $insert["account_type_id"]          = 10;
            $insert["account_number"]           = "00000";
            $insert["account_name"]             = "Opening Balance Equity";
            $insert["account_description"]      = "Opening Balance";
            $insert["account_protected"]        = 1;
            $insert["account_code"]             = "accounting-open-balance-equity";
            
            return Tbl_chart_of_account::insertGetId($insert);
        }

        return $exist_account->account_id;
	}

	/* REFERENCE OF THE NAMING OF THE CHART OF ACCOUNT TABLE */

	// Output Vat Payable 						= tax-output-vat-payable
	// Creditable Withholding Tax - 1%			= tax-credit-tax-1
	// Discount									= discount-sale
	// Discount									= discount-purchase

	public static function getTotalAccount()
	{
		$data["accounts_receivable"] = collect(Tbl_customer::balanceJournal()->get())->sum("balance");
		$data["accoutns_payable"]	 = collect(Tbl_vendor::balanceJournal()->get())->sum("balance");		

		return $data;
	}
}