<?php
namespace App\Globals;

use App\Models\Tbl_credit_memo;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_credit;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_chart_of_account;
use Carbon\Carbon;
use DB;
use App\Globals\AccountingTransaction;
use App\Globals\TransactionCreditMemo;
use App\Globals\Invoice;
use App\Globals\Accounting;
use Request;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */

class TransactionReceivePayment
{
	public static function countAvailableCredit($shop_id, $customer_id)
	{
		return Tbl_credit_memo::where("cm_shop_id", $shop_id)->where("cm_customer_id", $customer_id)->where("cm_type",1)->where("cm_used_ref_name","retain_credit")->where('cm_status',0)->count();
	}

	public static function getReferenceNumber($shop_id, $customer_id)
	{
		$return_ref_num = "";

		$count_customer = Tbl_receive_payment::where('rp_shop_id',$shop_id)->where('rp_customer_id',$customer_id)->orderBy('rp_id','DESC')->first();

		//$check_rp_id = $count_customer['rp_id']; //check rp_id to avoid incrementing of ref_num

		// if($check_rp_id == '' || $check_rp_id == null)
		// { 
			$chk_refnum = $count_customer['rp_payment_ref_no'];
			

			if($chk_refnum == "" || $chk_refnum == null) // check if $count_customer->rp_payment_ref_no have value
			{
				$return_ref_num = date('Ymd').'-'.$customer_id.'-1';
			}
			else
			{
				$split_chk_refnum = str_split($chk_refnum);

				$ref_number_add = "";
				foreach ($split_chk_refnum as $value) 
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

				if($ref_number_add != 0)
				{
					$ref_number_add += 1;	//add 1 to current reference number
					$return_ref_num = substr($chk_refnum,-strlen($chk_refnum) ,-strlen($ref_number_add)).$ref_number_add;

				}
				else
				{
					$return_ref_num .= 1;
				}
			}
		// }
		// else
		// {
		// 	$return_ref_num = $count_customer->rp_payment_ref_no; //if rp_id exists show rp_payment_ref_no
		// }

		return $return_ref_num;
	}

	public static function auto_undeposit_acc($shop_id)
	{
		$data = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_name", 'Undeposited Funds')->where('account_code', 'accounting-undeposit-funds')->first();
		return $data->account_id; 
	}
	//public static function get($shop_id, $paginate = null, $search_keyword = null)
	public static function get($shop_id, $paginate = null, $search_keyword = null, $is_customer = null)
	{
		$data = Tbl_receive_payment::customer()->where('rp_shop_id', $shop_id)->groupBy("rp_id")->orderBy("tbl_receive_payment.date_created","desc");

        $data = AccountingTransaction::acctg_trans($shop_id, $data);
        
		if($search_keyword)
		{
			$data->where(function($q) use ($search_keyword)
            {
                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("rp_id", "LIKE", "%$search_keyword%");
                $q->orWhere("company", "LIKE", "%$search_keyword%");
                $q->orWhere("first_name", "LIKE", "%$search_keyword%");
                $q->orWhere("middle_name", "LIKE", "%$search_keyword%");
                $q->orWhere("last_name", "LIKE", "%$search_keyword%");
            });
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

	public static function info($shop_id, $rp_id)
	{
		return Tbl_receive_payment::customer()->method()->coa()->where("rp_shop_id", $shop_id)->where("rp_id", $rp_id)->first();
	}
	public static function info_item($rp_id)
	{
		return Tbl_receive_payment_line::invoice()->where("rpline_rp_id", $rp_id)->get();		
	}
	public static function info_credit($rp_id)
	{
		return Tbl_receive_payment_credit::cm()->where("rp_id", $rp_id)->get();
	}
	public static function postInsert($shop_id, $insert, $insert_item = array())
	{
		$insert['function_do'] = 'insert'; //what function will do
		$insert['tbl_name'] = 'tbl_receive_payment'; //table affected
		$insert['column_for_reference_number'] = 'rp_payment_ref_no'; //column of table affected
		$val = AccountingTransaction::customer_validation($insert, $insert_item , 'receive_payment');
		/*if(!$val)
		{
			if(!$insert['rp_ar_account'])
			{
				$val = 'Select where to deposit payment';
			}
		}*/
		if(!$val)
		{
			$ins["rp_shop_id"]           = $shop_id;
	        $ins["rp_customer_id"]       = $insert['customer_id']; 
	        $ins["transaction_refnum"]   = $insert['transaction_refnum'];
	        $ins["rp_customer_email"]    = $insert['customer_email'];
	        $ins["rp_ar_account"]        = $insert['rp_ar_account'];
	        $ins["rp_date"]              = $insert['transaction_date'];
	        $ins["rp_total_amount"]      = $insert['rp_total_amount'];
	        $ins["rp_payment_method"]    = $insert['transaction_payment_method'];
	        $ins["rp_payment_ref_no"]	 = $insert['transaction_ref_no'];
	        $ins["rp_memo"]              = $insert['customer_memo'];
	        $ins["date_created"]         = Carbon::now();


        	$rcvpayment_id  = Tbl_receive_payment::insertGetId($ins);

        	$val = Self::insertline($rcvpayment_id, $insert_item);

        	/* Transaction Journal */
	        $entry["reference_module"]      = "receive-payment";
	        $entry["reference_id"]          = $rcvpayment_id;
	        $entry["name_id"]               = $ins["rp_customer_id"];
	        $entry["total"]                 = $ins["rp_total_amount"];
	        $entry_data[0]['account_id']    = $ins["rp_ar_account"];
	        $entry_data[0]['vatable']       = 0;
	        $entry_data[0]['discount']      = 0;
	        $entry_data[0]['entry_amount']  = $ins["rp_total_amount"];

   	        $entry_journal = Accounting::postJournalEntry($entry, $entry_data);
	        
   	        $return = $val;
		}
		else
		{
			$return = $val;
		}
		return $return;
	}

	public static function postUpdate($rp_id, $shop_id, $insert, $insert_item = array())
	{
		$val = AccountingTransaction::customer_validation($insert, $insert_item);
		// if(!$val)
		// {
		// 	if(!$insert['rp_ar_account'])
		// 	{
		// 		$val = 'Select where to deposit payment';
		// 	}
		// }
		if(!$val)
		{
			$ins["rp_shop_id"]           = $shop_id;
	        $ins["rp_customer_id"]       = $insert['customer_id']; 
	        $ins["transaction_refnum"]   = $insert['transaction_refnum'];
	        $ins["rp_customer_email"]    = $insert['customer_email'];
	        $ins["rp_ar_account"]        = $insert['rp_ar_account'];
	        $ins["rp_date"]              = $insert['transaction_date'];
	        $ins["rp_total_amount"]      = $insert['rp_total_amount'];
	        $ins["rp_payment_method"]    = $insert['transaction_payment_method'];
	        $ins["rp_payment_ref_no"]	 = $insert['transaction_ref_no'];
	        $ins["rp_memo"]              = $insert['customer_memo'];
	        $ins["date_created"]         = Carbon::now();

        	Tbl_receive_payment::where('rp_id', $rp_id)->update($ins);

        	/* INSERT CODE HERE THAT WILL RETURN THE PAYMENT ON THE INVOICE */
        	Self::return_payment($rp_id);

        	Tbl_receive_payment_line::where('rpline_rp_id', $rp_id)->delete();
        	$val = Self::insertline($rp_id, $insert_item);

        	/* Transaction Journal */
	        $entry["reference_module"]      = "receive-payment";
	        $entry["reference_id"]          = $rp_id;
	        $entry["name_id"]               = $ins["rp_customer_id"];
	        $entry["total"]                 = $ins["rp_total_amount"];
	        $entry_data[0]['account_id']    = $ins["rp_ar_account"];
	        $entry_data[0]['vatable']       = 0;
	        $entry_data[0]['discount']      = 0;
	        $entry_data[0]['entry_amount']  = $ins["rp_total_amount"];

   	        $entry_journal = Accounting::postJournalEntry($entry, $entry_data);
   	        $return = $val;
		}
		else
		{
			$return = $val;
		}
		return $return;
	}

    public static function applied_transaction($shop_id, $transaction_id = 0, $cm_data = array())
    {
    	$applied_transaction = null;
    	Tbl_receive_payment_credit::where("rp_id", $transaction_id)->delete();
    	if(count($cm_data) > 0)
    	{
    		$date = Carbon::now();
    		foreach ($cm_data as $key => $value) 
    		{
    			$applied_transaction[$key] = $key;

    			$ins['rp_id']				  = $transaction_id;
    			$ins['credit_reference_name'] = "credit_memo";
    			$ins['credit_reference_id']	  = $key;
    			$ins['credit_amount']		  = $value;
    			$ins['date_created'] 		  = $date;
    			Tbl_receive_payment_credit::insert($ins);

    			TransactionCreditMemo::update_cm_status($shop_id, $key);
    		}
    	}

        Self::insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction);
    }

    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
    	$get_transaction = Tbl_receive_payment::where("rp_shop_id", $shop_id)->where("rp_id", $transaction_id)->first();
    	$transaction_data = null;
    	if($get_transaction)
    	{
    		$transaction_data['transaction_ref_name'] = "receive_payment";
		 	$transaction_data['transaction_ref_id'] = $transaction_id;
		 	$transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
		 	$transaction_data['transaction_date'] = $get_transaction->rp_date;

		 	$attached_transaction_data = null;
		 	if(count($applied_transaction) > 0)
		 	{
		 		foreach ($applied_transaction as $key => $value) 
			 	{
			 		$get_data = Tbl_credit_memo::where("cm_shop_id", $shop_id)->where("cm_id", $key)->first();
			 		if($get_data)
			 		{
				 		$attached_transaction_data[$key]['transaction_ref_name'] = "credit_memo";
					 	$attached_transaction_data[$key]['transaction_ref_id'] = $key;
					 	$attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
					 	$attached_transaction_data[$key]['transaction_date'] = $get_data->cm_date;
			 		}
			 	}
		 	}
    	}

    	if($transaction_data)
		{
			AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
		}
    }
	public static function return_payment($rp_id)
	{
      	$_inv = Tbl_receive_payment_line::where('rpline_rp_id', $rp_id)->get();
      	if(count($_inv) > 0)
      	{
      		foreach ($_inv as $key => $value) 
      		{
      			$inv = Tbl_customer_invoice::where('inv_id', $value->rpline_reference_id)->first();
      			$up['inv_payment_applied'] = $inv->inv_payment_applied - $value->rpline_amount;
      			Tbl_customer_invoice::where('inv_id', $value->rpline_reference_id)->update($up);
    			// Invoice::updateAmountApplied($value->rpline_reference_id);
    			Invoice::updateIsPaid($value->rpline_reference_id);
      		}
      	}
	}
	public static function insertline($rcvpayment_id, $insert_item)
	{
		foreach ($insert_item as $key => $value) 
		{
            $insert_line[$key]["rpline_rp_id"]            = $rcvpayment_id;
            $insert_line[$key]["rpline_reference_name"]   = $value['rpline_reference_name'];
            $insert_line[$key]["rpline_reference_id"]     = $value['rpline_reference_id'];
            $insert_line[$key]["rpline_amount"]    		  = $value['rpline_amount'];
		}
		$return = null;
		if(count($insert_line) > 0)
		{			
			$return = $rcvpayment_id;
            Tbl_receive_payment_line::insert($insert_line);


			foreach ($insert_item as $key => $value) 
			{   
	            if($value["rpline_reference_name"] == 'invoice')
	            {
	                $ret = Invoice::updateAmountApplied($value["rpline_reference_id"]);
	            }
	        }
		}
		return $return;
	}
}