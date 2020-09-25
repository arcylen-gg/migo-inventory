<?php
namespace App\Globals;

use App\Models\Tbl_credit_memo;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_item;
use App\Models\Tbl_receive_payment_credit;
use App\Models\Tbl_credit_memo_line;
use Carbon\Carbon;
use DB;
use App\Globals\AccountingTransaction;
use App\Globals\Warehouse2;
use App\Globals\TransactionSalesInvoice;
/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionCreditMemo
{

	public static function check_credit_memo($shop_id, $cm_id)
	{
		/*CHECK IF CUSTOMER HAVE INVOICE --- CHECK CREDIT MEMO IS OPEN*/
		$cmdata = Self::info($shop_id, $cm_id);
		$return = null;
		if($cmdata)
		{
			$invoice = TransactionSalesInvoice::get_customer_invoice($shop_id, $cmdata->cm_customer_id);
			if(count($invoice) > 0)
			{
				$return++;
			}
			if($cmdata->cm_status == 1)
			{
				$return = null;
			}
		}
		return $return;
	}
	public static function get_total_amount($shop_id, $tab)
    {
    	$check_allow_transaction = AccountingTransaction::settings($shop_id, 'allow_transaction');
    	$cm = Tbl_credit_memo::where("cm_shop_id",$shop_id);

        $total = 0;
        if($tab == 'open')
        {
            $cm = $cm->where('cm_status', 0)->get();
        }
        elseif($tab == 'closed')
        {
            $cm = $cm->where('cm_status', 1)->get();
        }
        else
        {
            $cm = $cm->get();
            
        }
        if(count($cm) > 0)
        {
            foreach ($cm as $key => $value) 
            {
               $total += $value->cm_amount;

            }            
        }
        if($check_allow_transaction == 1)
        {
            $cm = Tbl_credit_memo::where("cm_shop_id",$shop_id);
            $cm = AccountingTransaction::acctg_trans($shop_id, $cm);

            $data = null;
	        $total = null;
	        if($tab == 'open')
	        {
	            $cm = $cm->where('cm_status', 0)->get();
	        }
	        elseif($tab == 'closed')
	        {
	            
	            $cm = $cm->where('cm_status', 1)->get();
	        }
	        else
	        {
	            $cm = $cm->get();
	        }
	        if(count($cm) > 0)
	        {
	        	foreach ($cm as $key_cm => $value_cm)
	            {
	                $data[$value_cm->cm_id] = $value_cm->cm_amount;
	            }
	            foreach ($data as $key => $value)
	            {
	                $total += $value;
	            }    
	        }
        }
        return $total;
    }
	public static function infoline($shop_id, $transactionline)
    {
        foreach($transactionline as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->cmline_um);

            $total_qty = $value->cmline_qty * $qty;
            $transactionline[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->cmline_um);
        }
        return $transactionline;
    }
	public static function loadAvailableCredit($shop_id, $customer_id)
	{
		return Tbl_credit_memo::applied()->where("cm_shop_id", $shop_id)->where("cm_customer_id", $customer_id)->where("cm_type",1)->where("cm_used_ref_name","retain_credit")->where("cm_status",0)->get();
	}

	public static function update_cm_status($shop_id, $cm_id)
	{
		$getcm = Self::info($shop_id, $cm_id);
		if($getcm)
		{
			$getcm_applied = Tbl_receive_payment_credit::rp()->where("rp_shop_id", $shop_id)->where("credit_reference_id", $cm_id)->sum("credit_amount");

			if($getcm_applied >= $getcm->cm_amount)
			{
				$update['cm_status'] = 1;
				Tbl_credit_memo::where("cm_shop_id", $shop_id)->where("cm_id", $cm_id)->update($update);
			}
			else
			{
				$update['cm_status'] = 0;
				Tbl_credit_memo::where("cm_shop_id", $shop_id)->where("cm_id", $cm_id)->update($update);				
			}
		}
	}
	public static function get_applied_cm($shop_id, $cm_id)
	{
		return Tbl_receive_payment_credit::rp()->where("rp_shop_id", $shop_id)->where("credit_reference_id", $cm_id)->sum("credit_amount");
	}
	public static function get_applied_cm_rp_info($shop_id, $rp_id)
	{
		$data = Tbl_receive_payment_credit::rp()->cm()->where("rp_shop_id", $shop_id)->where("tbl_receive_payment_credit.rp_id", $rp_id)->get();
		foreach ($data as $key => $value) 
		{
			$data[$key] = $value;
			$data[$key]->cm_amount = Self::get_applied_cm_rp($shop_id, $value->cm_id, $rp_id);
		}

		return $data;
	}
	public static function get_applied_cm_rp($shop_id, $cm_id, $rp_id)
	{
		return Tbl_receive_payment_credit::rp()->where("tbl_receive_payment_credit.rp_id", $rp_id)->where("rp_shop_id", $shop_id)->where("credit_reference_id", $cm_id)->sum("credit_amount");
	}
	public static function info($shop_id, $credit_memo_id)
	{
		return Tbl_credit_memo::applied()->customer()->where("cm_shop_id", $shop_id)->where("cm_id", $credit_memo_id)->first();
	}
	public static function info_item($credit_memo_id)
	{
		$data = Tbl_credit_memo_line::cm_item()->um()->where("cmline_cm_id", $credit_memo_id)->get();

		return $data;
	}
	public static function postUpdate($cm_id, $shop_id, $insert, $insert_item = array())
	{
		$val = AccountingTransaction::customer_validation($insert, $insert_item);
		if(!$val)
		{
	        /* SUBTOTAL */
	        $subtotal_price = collect($insert_item)->sum('item_amount');
	        /* OVERALL TOTAL */
	        $overall_price  = convertToNumber($subtotal_price); 

			$return  = null; 
			$ins['cm_shop_id']                  = $shop_id;  
			$ins['cm_customer_id']              = $insert['customer_id'];  
			$ins['transaction_refnum']	 		= $insert['transaction_refnum'];   
	        $ins['cm_customer_email']           = $insert['customer_email'];
	        $ins['cm_customer_billing_address'] = $insert['customer_address'];
	        $ins['cm_date']                     = date("Y-m-d", strtotime($insert['transaction_date']));
	        $ins['cm_message']                  = $insert['customer_message'];
	        $ins['cm_memo']                     = $insert['customer_memo'];
	        $ins['cm_used_ref_name']            = $insert['cm_used_ref_name'];
	        $ins['cm_type']            			= 1;
	        $ins['cm_amount']                   = $overall_price;
	        $ins['date_created']                = Carbon::now();

	        /* INSERT CREDIT MEMO HERE */
	        Tbl_credit_memo::where('cm_id', $cm_id)->update($ins);;

	        /* Transaction Journal */
	        $entry["reference_module"]  = "credit-memo";
	        $entry["reference_id"]      = $cm_id;
	        $entry["name_id"]           = $insert['customer_id'];
	        $entry["total"]             = $overall_price;
	        $entry["vatable"]           = '';
	        $entry["discount"]          = '';
	        $entry["ewt"]               = '';


	        Tbl_credit_memo_line::where('cmline_cm_id', $cm_id)->delete();
	        $return = Self::insertline($cm_id, $insert_item, $entry);
			$warehouse_id = Warehouse2::get_current_warehouse($shop_id);
			/* UPDATE INVENTORY HERE */
			/*AccountingTransaction::inventory_refill_update($shop_id, $warehouse_id, $insert_item, 'credit_memo', $cm_id); 
			AccountingTransaction::refill_inventory($shop_id, $warehouse_id, $insert_item, 'credit_memo', $cm_id, 'Refill upon creating CREDIT MEMO '.$ins['transaction_refnum']);*/
			Warehouse2::inventory_get_consume_data($shop_id, $warehouse_id, $insert_item, 'credit_memo', $cm_id, 'Refill upon creating CREDIT MEMO '.$ins['transaction_refnum']);
		}
		else
		{
			$return = $val;
		}		

        return $return; 
	}	
	public static function postInsert($shop_id, $insert, $insert_item = array())
	{
		$val = AccountingTransaction::customer_validation($insert, $insert_item);
		if(!$val)
		{
	        /* SUBTOTAL */
	        $subtotal_price = collect($insert_item)->sum('item_amount');
	        /* OVERALL TOTAL */
	        $overall_price  = convertToNumber($subtotal_price); 

			$return  = null; 
			$ins['cm_shop_id']                  = $shop_id;  
			$ins['cm_customer_id']              = $insert['customer_id'];  
			$ins['transaction_refnum']	 		= $insert['transaction_refnum'];   
	        $ins['cm_customer_email']           = $insert['customer_email'];
	        $ins['cm_customer_billing_address'] = $insert['customer_address'];
	        $ins['cm_date']                     = date("Y-m-d", strtotime($insert['transaction_date']));
	        $ins['cm_message']                  = $insert['customer_message'];
	        $ins['cm_used_ref_name']            = $insert['cm_used_ref_name'];
	        $ins['cm_used_ref_id']            	= isset($insert['cm_used_ref_id']) ? $insert['cm_used_ref_id'] : 0;
	        $ins['cm_type']            			= 1;
	        $ins['cm_memo']                     = $insert['customer_memo'];
	        $ins['cm_amount']                   = $overall_price;
	        $ins['date_created']                = Carbon::now();

	        /* INSERT CREDIT MEMO HERE */
	        $cm_id = Tbl_credit_memo::insertGetId($ins);

	        /* Transaction Journal */
	        $entry["reference_module"]  = "credit-memo";
	        $entry["reference_id"]      = $cm_id;
	        $entry["name_id"]           = $insert['customer_id'];
	        $entry["total"]             = $overall_price;
	        $entry["vatable"]           = '';
	        $entry["discount"]          = '';
	        $entry["ewt"]               = '';

	        $return = Self::insertline($cm_id, $insert_item, $entry);

			$warehouse_id = Warehouse2::get_current_warehouse($shop_id);
			AccountingTransaction::refill_inventory($shop_id, $warehouse_id, $insert_item, 'credit_memo', $cm_id, 'Refill upon creating CREDIT MEMO '.$ins['transaction_refnum']);
		}
		else
		{
			$return = $val;
		}		

        return $return; 
	}
	public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
    	$get_transaction = Tbl_credit_memo::where("cm_shop_id", $shop_id)->where("cm_id", $transaction_id)->first();
    	$transaction_data = null;
    	if($get_transaction)
    	{
    		$transaction_data['transaction_ref_name'] = "credit_memo";
		 	$transaction_data['transaction_ref_id'] = $transaction_id;
		 	$transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
		 	$transaction_data['transaction_date'] = $get_transaction->cm_date;

		 	$attached_transaction_data = null;
		 	if(count($applied_transaction) > 0)
		 	{
			 	foreach ($applied_transaction as $key => $value) 
			 	{
			 		$get_data = Tbl_receive_payment::where("rp_shop_id", $shop_id)->where("rp_id", $key)->first();
			 		if($get_data)
			 		{
				 		$attached_transaction_data[$key]['transaction_ref_name'] = "receive_payment";
					 	$attached_transaction_data[$key]['transaction_ref_id'] = $key;
					 	$attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
					 	$attached_transaction_data[$key]['transaction_date'] = $get_data->rp_date;
			 		}
			 	}
		 	}
    	}

    	if($transaction_data)
		{
			AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
		}
    }
	public static function get($shop_id, $paginate = null, $search_keyword = null, $status = null)
	{
		$data = Tbl_credit_memo::customer()->where('cm_shop_id', $shop_id)->groupBy("cm_id")->orderBy("cm_date","desc");
		
        $data = AccountingTransaction::acctg_trans($shop_id, $data);
        
		if($search_keyword)
		{
			$data->where(function($q) use ($search_keyword)
            {
                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("cm_id", "LIKE", "%$search_keyword%");
                $q->orWhere("company", "LIKE", "%$search_keyword%");
                $q->orWhere("first_name", "LIKE", "%$search_keyword%");
                $q->orWhere("middle_name", "LIKE", "%$search_keyword%");
                $q->orWhere("last_name", "LIKE", "%$search_keyword%");
            });
		}

		if($status != 'all')
		{
			$tab = 0;
			if($status == 'open')
			{
				$tab = 0;
			}
			if($status == 'closed')
			{
				$tab = 1;
			}
			$data->where('cm_status',$tab);
		}
		if($paginate)
		{
			$data = $data->paginate($paginate);
		}
		else
		{
			$data = $data->get();
		}

		return $data;
	}
	public static function insertline($cm_id, $insert_item, $entry)
	{
		$itemline = null;
		$return = null;
		foreach ($insert_item as $key => $value) 
		{	
			$itemline[$key]['cmline_cm_id'] 			= $cm_id;
			$itemline[$key]['cmline_service_date'] 		= isset($value['item_servicedate']) ? $value['item_servicedate'] : '';
			$itemline[$key]['cmline_item_id'] 			= $value['item_id'];
			$itemline[$key]['cmline_description'] 		= $value['item_description'];
			$itemline[$key]['cmline_um'] 				= $value['item_um'];
			$itemline[$key]['cmline_qty'] 				= $value['item_qty'];
			$itemline[$key]['cmline_rate'] 				= $value['item_rate'];
			$itemline[$key]['cmline_amount'] 			= $value['item_amount'];
			$itemline[$key]['created_at'] 				= Carbon::now();
            $itemline[$key]['cmline_sub_wh_id']         = $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;
           
		}
		if(count($itemline) > 0)
		{
			Tbl_credit_memo_line::insert($itemline);
			$return = AccountingTransaction::entry_data($entry, $insert_item);
			$return = $cm_id;
		}

		return $return;
	}
	public static function auto_create_cm($shop_id, $rp_id, $cm_amount)
	{
		$get_rp = TransactionReceivePayment::info($shop_id, $rp_id);
		if($get_rp && $cm_amount > 0)
		{
			$ins_cm['transaction_refnum'] 	 = AccountingTransaction::get_ref_num($shop_id, 'credit_memo');
			$ins_cm['customer_id'] 			 = $get_rp->rp_customer_id;
			$ins_cm['customer_email']        = $get_rp->rp_customer_email;
			$ins_cm['customer_address']      = '';
			$ins_cm['transaction_date']      = $get_rp->rp_date;
			$ins_cm['customer_memo']         = $get_rp->rp_memo;
	        $ins_cm['cm_used_ref_name'] 	 = "retain_credit";
	        $ins_cm['cm_used_ref_id']		 = $rp_id;
	        $ins_cm['customer_message'] 	 = "";
	        $ins_cm['cm_amount'] 			 = $cm_amount;
	        $item = self::balance_type_item_id($shop_id);
	        if($item)
	        {
		        $ins_item[0]['item_id'] = $item->item_id;
				$ins_item[0]['item_servicedate'] = '';
				$ins_item[0]['item_description'] = $item->item_sales_information;
				$ins_item[0]['item_um'] = '';
				$ins_item[0]['item_qty'] = 1;
				$ins_item[0]['item_rate'] = $cm_amount;
				$ins_item[0]['item_amount'] = $cm_amount;
				$ins_item[0]['item_sub_warehouse'] = '';

				$cm_id = self::postInsert($shop_id, $ins_cm, $ins_item);
	        }
	        $rp[$rp_id] = $rp_id;
	        //RP attached transaction
			self::insert_acctg_transaction($shop_id, $cm_id, $rp);
		}
	}

	public static function auto_update_cm($shop_id, $rp_id, $cm_amount)
	{
		$get_rp = TransactionReceivePayment::info($shop_id, $rp_id);
		$cm_id = Tbl_credit_memo::where("cm_used_ref_id", $rp_id)->value("cm_id");
		if($get_rp && $cm_amount > 0)
		{
			$ins_cm['transaction_refnum'] 	 = AccountingTransaction::get_ref_num($shop_id, 'credit_memo');
			$ins_cm['customer_id'] 			 = $get_rp->rp_customer_id;
			$ins_cm['customer_email']        = $get_rp->rp_customer_email;
			$ins_cm['customer_address']      = '';
			$ins_cm['transaction_date']      = $get_rp->rp_date;
			$ins_cm['customer_memo']         = $get_rp->rp_memo;
	        $ins_cm['cm_used_ref_name'] 	 = "retain_credit";
	        $ins_cm['cm_used_ref_id']		 = $rp_id;
	        $ins_cm['customer_message'] 	 = "";
	        $ins_cm['cm_amount'] 			 = $cm_amount;
	        $item = self::balance_type_item_id($shop_id);
	        if($item)
	        {
		        $ins_item[0]['item_id'] = $item->item_id;
				$ins_item[0]['item_servicedate'] = '';
				$ins_item[0]['item_description'] = $item->item_sales_information;
				$ins_item[0]['item_um'] = '';
				$ins_item[0]['item_qty'] = 1;
				$ins_item[0]['item_rate'] = $cm_amount;
				$ins_item[0]['item_amount'] = $cm_amount;
				$ins_item[0]['item_sub_warehouse'] = '';
				TransactionCreditMemo::postUpdate($cm_id, $shop_id, $ins_cm, $ins_item);
	        }
	        $rp[$rp_id] = $rp_id;
			self::insert_acctg_transaction($shop_id, $cm_id, $rp);
		}
	}
	public static function balance_type_item_id($shop_id)
	{
		$item = Tbl_item::where("shop_id", $shop_id)->where("item_type_id", 3)->first();
		if(!$item)
		{
			$insert['item_name'] = 'Balance';
			$insert['item_sku'] = 'balance';
			$insert['item_sales_information'] = 'Balance';
			$insert['item_type_id'] = 3;
			$insert['shop_id'] = $shop_id;
            $insert['item_expense_account_id'] = Accounting::get_default_coa("accounting-expense");
            $insert['item_income_account_id'] = Accounting::get_default_coa("accounting-sales");
            $insert['item_asset_account_id'] = Accounting::get_default_coa("accounting-inventory-asset");
            $itemid = Tbl_item::insertGetId($insert);
            $item = Tbl_item::where("item_id", $itemid)->first();
		}
		return $item;
	}
}