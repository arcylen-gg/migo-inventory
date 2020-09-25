<?php
namespace App\Globals\ReferenceNumberFormatter;

use App\Models\Tbl_acctg_transaction;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_wis;
use App\Models\Tbl_warehouse_issuance_report;
use App\Models\Tbl_warehouse_receiving_report;
use App\Models\Tbl_requisition_slip;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_receive_inventory;
use App\Models\Tbl_bill;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_write_check;
use App\Models\Tbl_debit_memo;
use App\Models\Tbl_inventory_adjustment;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_transaction_ref_number;
use App\Globals\Warehouse2;

use App\Globals\AccountingTransaction;

use App\Globals\ReferenceNumberFormatter\ReferenceNumberValidation;

class ReferenceNumberFormat
{
	public static function GenerateReferenceNumber($shop_id, $transaction_type)
	{
		// $generated_ref_number = Self::GenerateDefaultReferenceNumber($shop_id, $transaction_type); 
		$generated_ref_number = Self::get_last_count_transaction($shop_id, $transaction_type);

		return $generated_ref_number;
	}

	public static function GenerateDefaultReferenceNumber($shop_id, $transaction_type)
	{
		$return = null;
		$get = Tbl_transaction_ref_number::where('shop_id', $shop_id)->where('key', $transaction_type)->first();
		// dd($get);
		if($get)
		{
			$date = explode('/', $get->other);
			if(isset($date[2]))
			{
				$branches_initials = AccountingTransaction::warehouse_initials($shop_id);
				$datetoday = date($date[0]).date($date[1]).date($date[2]);
				$string = $get->prefix.$datetoday.$branches_initials;
				$ctr = sprintf("%'.04d", Self::get_last_count_transaction($shop_id, $transaction_type));
				$return = $string.$get->separator.$ctr;
			} 
		}

		return $return;
	}



	public static function get_last_count_transaction($shop_id,$transaction_type)
	{
		$warehouse_id = Warehouse2::get_current_warehouse($shop_id);
		$settings = AccountingTransaction::settings($shop_id, "transaction_number");
		$get = Self::case_transaction_type($shop_id, $transaction_type, $warehouse_id, $settings);
		$get = Self::result_switch_get($get);
		$string = Tbl_transaction_ref_number::where('shop_id', $shop_id)->where('key', $transaction_type)->first();
		if($string)
		{
			// $get = $string->prefix.sprintf("%04d",$get);
		}

		// dd($get);
		return $get;
	}

	public static function result_switch_get($get)
	{
		$return = 1;
		if($get)
		{
			// $perday = AccountingTransaction::settings($shop_id, "per_day_reset");
			// $number = explode("$separator", $get->transaction_refnum);
			// dd($get->transaction_refnum);
			$return = Self::getReferenceNumber($get->transaction_refnum);

			// dd($get->transaction_number,$number,$perday,$string);
			// if(isset($number[1]))
			// {
			// 	if($perday)
			// 	{
			// 		if($number[0] == $string)
			// 		{
			// 			$return = (int)$number[1] + 1;
			// 		}
			// 	}
			// 	else
			// 	{
			// 		$return = (int)$number[1] + 1;
			// 	}
			// }
		}
		return $return;
	}

	public static function case_transaction_type($shop_id,$transaction_type,$warehouse_id,$settings)
	{
		$get = null;
		switch ($transaction_type) 
		{
			case 'accounting_transaction':
				$get = Tbl_acctg_transaction::where('shop_id', $shop_id)->orderBy("acctg_transaction_id", "DESC")->first();
				if($get)
				{
					$get->transaction_refnum = $get->transaction_number;
				}
			break;

			case 'sales_invoice':
				$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('is_sales_receipt',0)->orderBy('inv_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->acctg_trans("sales_invoice")->where('is_sales_receipt',0)->orderBy('inv_id','DESC')->where("transaction_warehouse_id", $warehouse_id)->first();
				}

			break;

			case 'sales_receipt':
				$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('is_sales_receipt',1)->orderBy('inv_id','DESC')->first();

				if(is_numeric($settings))
				{
					$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->acctg_trans("sales_receipt")->where('is_sales_receipt',1)->orderBy('inv_id','DESC')->where("transaction_warehouse_id", $warehouse_id)->first();
				}

			break;

			case 'credit_memo':
				$get = Tbl_credit_memo::where('cm_shop_id', $shop_id)->orderBy('cm_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_credit_memo::where('cm_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('cm_id','DESC')->first();
				}

			break;

			case 'estimate_quotation':
				$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('is_sales_order',0)->orderBy('est_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get =Tbl_customer_estimate::where('est_shop_id', $shop_id)->acctg_trans("estimate_quotation")->where('is_sales_order',0)->where("transaction_warehouse_id", $warehouse_id)->orderBy('est_id','DESC')->first();
				}

			break;

			case 'sales_order':
				$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('is_sales_order',1)->orderBy('est_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get =Tbl_customer_estimate::where('est_shop_id', $shop_id)->acctg_trans("sales_order")->where('is_sales_order',1)->where("transaction_warehouse_id", $warehouse_id)->orderBy('est_id','DESC')->first();
				}

			break;

			case 'warehouse_issuance_slip':
				$get = Tbl_customer_wis::where('cust_wis_shop_id', $shop_id)->orderBy('cust_wis_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_customer_wis::where('cust_wis_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('cust_wis_id','DESC')->first();
				}

			break;

			case 'warehouse_transfer':
				$get = Tbl_warehouse_issuance_report::where('wis_shop_id', $shop_id)->orderBy('wis_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get =  Tbl_warehouse_issuance_report::where('wis_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('wis_id','DESC')->first();
				}
				if($get)
				{
					$get->transaction_refnum = $get->wis_number;
				}

			break;

			case 'receiving_report':
				$get = Tbl_warehouse_receiving_report::where('rr_shop_id', $shop_id)->orderBy('rr_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get =  Tbl_warehouse_receiving_report::where('rr_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('rr_id','DESC')->first();
				}
				if($get)
				{
					$get->transaction_refnum = $get->rr_number;
				}

			break;

			case 'purchase_requisition':
				$get = Tbl_requisition_slip::where('tbl_requisition_slip.shop_id', $shop_id)->orderBy('requisition_slip_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_requisition_slip::where('tbl_requisition_slip.shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('requisition_slip_id','DESC')->first();
				}

			break;

			case 'purchase_order':
				$get = Tbl_purchase_order::where('po_shop_id', $shop_id)->orderBy('po_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_purchase_order::where('po_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('po_id','DESC')->first();
				}

			break;

			case 'received_inventory':
				$get = Tbl_receive_inventory::where('ri_shop_id', $shop_id)->orderBy('ri_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_receive_inventory::where('ri_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('ri_id','DESC')->first();
				}

			break;

			case 'enter_bills':
				$get = Tbl_bill::where('bill_shop_id', $shop_id)->orderBy('bill_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_bill::where('bill_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('bill_id','DESC')->first();
				}

			break;

			case 'pay_bill':
				$get = Tbl_pay_bill::where('paybill_shop_id', $shop_id)->orderBy('paybill_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_pay_bill::where('paybill_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('paybill_id','DESC')->first();
				}

			break;

			case 'write_check':
				$get = Tbl_write_check::where('wc_shop_id', $shop_id)->orderBy('wc_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_write_check::where('wc_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('wc_id','DESC')->first();
				}

			break;

			case 'debit_memo':
				$get = Tbl_debit_memo::where('db_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('db_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_debit_memo::where('db_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('db_id','DESC')->first();
				}
			break;

			case 'inventory_adjustment':
				$get = Tbl_inventory_adjustment::where('adj_shop_id', $shop_id)->orderBy('inventory_adjustment_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_inventory_adjustment::where('adj_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('inventory_adjustment_id','DESC')->first();
				}

			break;

			case 'received_payment':
				$get = Tbl_receive_payment::where('rp_shop_id', $shop_id)->orderBy('rp_id','DESC')->first();
				if(is_numeric($settings))
				{
					$get = Tbl_receive_payment::where('rp_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('rp_id','DESC')->first();
				}

			break;

			default:
				# code...
				break;
		}
		return $get;
	}

	public static function getReferenceNumber($transaction_ref_number)
    {
    	// dd($transaction_ref_number);
        $return_ref_num = "";

        if($transaction_ref_number == "" || $transaction_ref_number == null) // check if $count_vendor->paybill_ref_num have value
        {
            $return_ref_num = date('Ymd').'-1';
        }
        else
        {
            $split_transaction_ref_number = str_split($transaction_ref_number);
            // $split_transaction_ref_number = str_split("edrich");

            $ref_number_add = "";
            foreach ($split_transaction_ref_number as $value) 
            {
                if(is_numeric($value)) //check if value is a number
                {
                    $ref_number_add .= $value;
                }
                else
                {
                     $ref_number_add = 0;
                }
            }

             $ref_number_add += 1;   //add 1 to current reference number
             $return_ref_num = substr($transaction_ref_number,-strlen($transaction_ref_number) ,-strlen($ref_number_add)).$ref_number_add;

            // if($ref_number_add != 0)
            // {
            //     $ref_number_add += 1;   //add 1 to current reference number
            //     $return_ref_num = substr($transaction_ref_number,-strlen($transaction_ref_number) ,-strlen($ref_number_add)).$ref_number_add;
            // }
            // else
            // {
            //     $return_ref_num .= 1;
            // }
        }

        return $return_ref_num;
    }
}