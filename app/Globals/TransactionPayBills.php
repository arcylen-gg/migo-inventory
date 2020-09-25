<?php
namespace App\Globals;

use App\Models\Tbl_bill_account_line;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_pay_bill_line;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_bill;
use App\Models\Tbl_write_check_account_line;
use App\Models\Tbl_acctg_transaction_list;
use App\Models\Tbl_acctg_transaction;

use App\Globals\AccountingTransaction;
use App\Globals\WriteCheck;
use Carbon\Carbon;

use Validator;
use DB;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionPayBills
{
    public static function getReferenceNumber($shop_id,$vendor_id)
    {
        $return_ref_num = "";

        $count_vendor = tbl_pay_bill::where('paybill_shop_id',$shop_id)->where('paybill_vendor_id',$vendor_id)->orderBy('paybill_id','DESC')->first();

            $chk_refnum = $count_vendor['paybill_ref_num'];

            if($chk_refnum == "" || $chk_refnum == null) // check if $count_vendor->paybill_ref_num have value
            {
                $return_ref_num = date('Ymd').'-'.$vendor_id.'-1';
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
                    $ref_number_add += 1;   //add 1 to current reference number
                    $return_ref_num = substr($chk_refnum,-strlen($chk_refnum) ,-strlen($ref_number_add)).$ref_number_add;

                }
                else
                {
                    $return_ref_num .= 1;
                }
            }

        return $return_ref_num;
    }
    public static function get_ap_amount($shop_id)
    {
        $price = 0;
        $amount = 0;
        $bill = Tbl_bill::where("bill_shop_id",$shop_id)
                        ->where("bill_is_paid",0)->get();
        if(isset($bill))
        {
            foreach ($bill as $key => $value) 
            {
                $amount = $value->bill_total_amount - $value->bill_applied_payment;
                $price += $amount;
            }            
        }

        return $price;
    }
    public static function getBalance($shop_id, $bill_id, $overall_amount = 0)
    { 
        $details = Tbl_pay_bill::pbline()->where('paybill_shop_id', $shop_id)->where("pbline_reference_name","bill")->where("pbline_reference_id",$bill_id)->first();
        $amount_paid = 0;
        $balance = $overall_amount;
        if($details)
        {
            $balance = ($balance - $details->pbline_amount);
        }
        return $balance;

    }
    public static function count_ap($shop_id)
    {
         return Tbl_bill::where("bill_shop_id", $shop_id)->where("bill_is_paid",0)->count();
    }
    public static function get_eb_amount_perwh($shop_id, $warehouse_id)
    {
        $price = 0;
        $amount = 0;
        $bill = Tbl_bill::billwarehouse($shop_id)->where("bill_is_paid",0)->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->get();
        if(isset($bill))
        {
            foreach ($bill as $key => $value) 
            {
                $amount = $value->bill_total_amount - $value->bill_applied_payment;
                $price += $amount;
            }            
        }
        return $price;
    }
    public static function count_eb_perwh($shop_id, $warehouse_id)
    {
         return Tbl_bill::billwarehouse($shop_id)->where("bill_is_paid",0)->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->count();
    }
    public static function get_receive_amount_perwh($shop_id, $warehouse_id)
    {
        $price = 0;
        $amount = 0;
        $bill = Tbl_bill::receivewarehouse($shop_id)->where("bill_is_paid",0)->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->get();
        if(isset($bill))
        {
            foreach ($bill as $key => $value) 
            {
                $amount = $value->bill_total_amount - $value->bill_applied_payment;
                $price += $amount;
            }            
        }
        return $price;
    }
    public static function count_receive_perwh($shop_id, $warehouse_id)
    {
         return Tbl_bill::receivewarehouse($shop_id)->where("bill_is_paid",0)->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->count();
    }
	public static function info($shop_id, $paybill_id)
    {
        return Tbl_pay_bill::vendor()->method()->coa()->where("paybill_shop_id", $shop_id)->where("paybill_id", $paybill_id)->first();
    }
    public static function info_line($paybill_id)
    {
        return Tbl_pay_bill_line::selectRaw('*,tbl_bill.transaction_refnum as bill_refnum, tbl_receive_inventory.transaction_refnum as ri_refnum')->bill()->where("pbline_pb_id", $paybill_id)->get();
    }
    public static function bill($paybill_id)
    {
        return Tbl_pay_bill_line::where("pbline_pb_id", $paybill_id)->get();
    }
    public static function info_item($shop_id, $vendor_id, $paybill_id)
    {
        $bill_in_paybill = Tbl_pay_bill_line::select("pbline_reference_id")->where("pbline_reference_name", 'bill')->where("pbline_pb_id", $paybill_id)->get()->toArray();

        $data = Tbl_bill::selectRaw('*,tbl_bill.transaction_refnum as bill_refnum, tbl_receive_inventory.transaction_refnum as ri_refnum')->receive()->appliedPayment($shop_id)->byVendor($shop_id, $vendor_id)->billwarehouse()->payBill($paybill_id, $bill_in_paybill);
        $per_warehouse = AccountingTransaction::settings($shop_id, 'allow_transaction');
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
        if($per_warehouse)
        {   
            $data = $data->where('transaction_warehouse_id', $warehouse_id);
        }
        $data = $data->orderBy("bill_id")->get()->toArray();

        return $data;
    }
    public static function getAllBillByVendor($shop_id, $vendor_id, $pb_id = null)
    {
        $per_warehouse = AccountingTransaction::settings($shop_id, 'allow_transaction');
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
        if($pb_id)
        {
            $data =  Tbl_bill::selectRaw('*,tbl_bill.transaction_refnum as bill_refnum, tbl_receive_inventory.transaction_refnum as ri_refnum')->receive()->appliedPayment($shop_id)->byPayBill($shop_id, $pb_id)->orwhere("bill_is_paid", 0)->byVendor($shop_id, $vendor_id)->billwarehouse()->where('tbl_bill.inventory_only',0);
            
            if($per_warehouse)
            {   
                $data = $data->where('transaction_warehouse_id', $warehouse_id);
            }
            $data = $data->get()->toArray();
        }
        else
        {
            $data =  Tbl_bill::selectRaw('*,tbl_bill.transaction_refnum as bill_refnum, tbl_receive_inventory.transaction_refnum as ri_refnum')->receive()->appliedPayment($shop_id)->byVendor($shop_id, $vendor_id)->where("bill_is_paid", 0)->where('tbl_bill.inventory_only',0);
            // if($per_warehouse)
            // {   
            //     $data = $data->where('transaction_warehouse_id', $warehouse_id);
            // }
            $data = $data->get()->toArray();
        }
        
        return $data;
    }

	public static function get($shop_id, $paginate = null, $search_keyword = null)
	{
		$data = Tbl_pay_bill::vendor()->where('paybill_shop_id', $shop_id)->groupBy("paybill_id")->orderBy("paybill_date","desc");

        $data = AccountingTransaction::acctg_trans($shop_id, $data);
		if($search_keyword)
        {
            $data->where(function($q) use ($search_keyword)
            {   
                $q->orWhere("vendor_company", "LIKE", "%$search_keyword%");
                $q->orWhere("vendor_first_name", "LIKE", "%$search_keyword%");
                $q->orWhere("vendor_middle_name", "LIKE", "%$search_keyword%");
                $q->orWhere("vendor_last_name", "LIKE", "%$search_keyword%");
                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("paybill_id", "LIKE", "%$search_keyword%");
                $q->orWhere("paybill_total_amount", "LIKE", "%$search_keyword%");
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

	public static function postInsert($shop_id, $insert, $insert_item)
	{      
        $return = null;
		$val = Self::payBillsValidation($insert, $insert_item, 'enter_bills');

		if(!$val)
		{
			$ins["paybill_shop_id"]			  = $shop_id;
			$ins["paybill_vendor_id"]         = $insert['vendor_id'];
			$ins["transaction_refnum"]        = $insert['transaction_refnumber'];
	        $ins["paybill_ap_id"]             = $insert['paybill_ap_id'];
	        $ins["paybill_date"]              = date('Y-m-d', strtotime($insert['paybill_date']));
	        $ins["paybill_payment_method"]    = $insert['paybill_payment_method'];
	        $ins["paybill_ref_num"]    		  = $insert['paybill_ref_num'];
	        $ins["paybill_memo"]              = $insert['paybill_memo'];
	        $ins["paybill_date_created"]      = Carbon::now();

	        /*TOTAL*/
	        $total = collect($insert_item)->sum('item_amount');
	        $ins['paybill_total_amount'] = $total;

	        /*INSERT PB HERE*/
	        $pay_bill_id = Tbl_pay_bill::insertGetId($ins);

            /* Transaction Journal */
            $entry["reference_module"]  = "bill-payment";
            $entry["reference_id"]      = $pay_bill_id;
            $entry["name_id"]           = $insert['vendor_id'];
            $entry["total"]             = $total;
            $entry["vatable"]           = '';
            $entry["discount"]          = '';
            $entry["ewt"]               = '';

            Self::insertLine($pay_bill_id, $shop_id, $insert_item, $entry);
            WriteCheck::create_check_from_paybill($pay_bill_id);
	        $return = $pay_bill_id;
		}
		else
		{
			$return = $val;
		}
		return $return;
	}
	public static function postUpdate($pay_bill_id, $shop_id, $insert, $insert_item)
	{
        // dd($pay_bill_id, $shop_id, $insert, $insert_item);
		$val = Self::payBillsValidation($insert, $insert_item, 'update');

		if(!$val)
		{ 
			$ins["paybill_shop_id"]			  = $shop_id;
			$ins["paybill_vendor_id"]         = $insert['vendor_id'];
			$ins["transaction_refnum"]        = $insert['transaction_refnumber'];
	        $ins["paybill_ap_id"]             = $insert['paybill_ap_id'];
	        $ins["paybill_date"]              = date('Y-m-d', strtotime($insert['paybill_date']));
	        $ins["paybill_payment_method"]    = $insert['paybill_payment_method'];
	        $ins["paybill_ref_num"]    		  = $insert['paybill_ref_num'];
	        $ins["paybill_memo"]              = $insert['paybill_memo'];
	        $ins["paybill_date_created"]      = Carbon::now();

            /*TOTAL*/
            $total = collect($insert_item)->sum('item_amount');
	        $ins['paybill_total_amount'] = $total;

	        /*INSERT PB HERE*/
	        Tbl_pay_bill::where('paybill_id', $pay_bill_id)->update($ins);
	               
	        /* Transaction Journal */
	        $entry["reference_module"]  = "bill-payment";
	        $entry["reference_id"]      = $pay_bill_id;
	        $entry["name_id"]           = $insert['vendor_id'];
	        $entry["total"]             = $total;
	        $entry["vatable"]           = '';
	        $entry["discount"]          = '';
	        $entry["ewt"]               = '';

			Tbl_pay_bill_line::where('pbline_pb_id', $pay_bill_id)->delete();

	        $return = Self::insertLine($pay_bill_id, $shop_id, $insert_item, $entry);

	        $return = $pay_bill_id;

	        WriteCheck::delete_bill_in_check($pay_bill_id);
	        WriteCheck::delete_check_acct($pay_bill_id);
 			WriteCheck::update_check_from_paybill($pay_bill_id);
		}
		else
		{
			$return = $val;
		}

		return $return;
	}
	public static function payBillsValidation($insert, $insert_item, $InsertOrUpdate)
	{
		$return = null;
        if(!$insert["vendor_id"])
        {
            $return .= '<li style="list-style:none">Please Select Vendor.</li>';          
        }
        if(!$insert['paybill_ap_id'])
        {
            $return .= '<li style="list-style:none">Please Select Payment Account.</li>';          
        }

        if(DB::table('tbl_payment_method')->where('payment_method_id',$insert['paybill_payment_method'])->first()->payment_name == 'Cheque') // if cheque is selected
        {
            if(!$insert['paybill_ref_num'])//if cheque is selected and null paybill_ref_num
            {
                $return .= '<li style="list-style:none">Please Input Cheque Reference Number.</li>';          
            }

            if($InsertOrUpdate == 'insert')//if insert function
            {
                if(Tbl_pay_bill::where('paybill_ref_num',$insert['paybill_ref_num'])->count() >= 1) //if cheque is selected and paybill_ref_num >= 1
                {
                    $return .= '<li style="list-style:none">Duplicate Cheque Reference Number.</li>';   
                }
            }
            
        }
        

		$rules['transaction_refnumber'] = 'required';

        $validator = Validator::make($insert, $rules);
        if($validator->fails())
        {
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li><br>') as $keys => $message)
            {
                $return .= $message;
            }
        }
        return $return;
	}

	public static function insertLine($pay_bill_id, $shop_id, $insert_item, $entry)
    {
    	$data  = Tbl_pay_bill::where('paybill_id', $pay_bill_id)->get();

    	$entry_data = null;
    	foreach ($data as $key => $value)
	    {
	    	$entry_data['a'.$key]['account_id']        = $value->paybill_ap_id;
            $entry_data['a'.$key]['entry_description'] = 0;
            $entry_data['a'.$key]['entry_amount']      = $value->paybill_total_amount;
            $entry_data['a'.$key]['vatable']           = 0;
            $entry_data['a'.$key]['discount']          = 0;
	    	
	    }
    	
    	if(count($insert_item) > 0)
    	{
    		$itemline = null;
	        foreach ($insert_item as $key => $value) 
	        {   
	        	if($value["line_is_checked"] == 1)
	        	{
		        	$itemline["pbline_pb_id"]            = $pay_bill_id;
		            $itemline["pbline_reference_name"]   = $value['pbline_reference_name'];
		            $itemline["pbline_reference_id"]     = $value['pbline_reference_id'];
		            $itemline["pbline_amount"]           = $value['item_amount'];

		            Tbl_pay_bill_line::insert($itemline);

			        if($itemline["pbline_reference_name"] == 'bill')
			        {
			        	Self::updateAppliedAmount($itemline["pbline_reference_id"], $shop_id);
			        }
				}
	        }
	    }

	    Accounting::postJournalEntry($entry, $entry_data);
        
        $return = $pay_bill_id;

        return $return;
    }

    public static function updateAppliedAmount($bill_id, $shop_id)
    {

    	$payment_applied = Tbl_bill::appliedPayment($shop_id)->where("bill_id",$bill_id)->value("amount_applied");

    	$update['bill_applied_payment'] = $payment_applied;
 
    	Tbl_bill::where('bill_id', $bill_id)->update($update);

    	$check_bill_amount = Tbl_bill::where('bill_id',$bill_id)->value('bill_total_amount');
    	$check_applied_payment = Tbl_bill::where('bill_id',$bill_id)->value('bill_applied_payment');
    	

    	if($check_applied_payment == $check_bill_amount)
    	{
    		$update['bill_is_paid'] = 1;
    	}
    	else
    	{
    		$update['bill_is_paid'] = 0;
    	} 	

    	Tbl_bill::where('bill_id', $bill_id)->update($update);
    }
    public static function insert_acctg_transaction($shop_id, $transaction_id)
    {
        $get_transaction = Tbl_pay_bill::where("paybill_shop_id", $shop_id)->where("paybill_id", $transaction_id)->first();
        
        $transaction_data = null;
        if($get_transaction)
        {
            $transaction_data['transaction_ref_name'] = "pay_bills";
            $transaction_data['transaction_ref_id'] = $transaction_id;
            $transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
            $transaction_data['transaction_date'] = $get_transaction->paybill_date;

            /*$attached_transaction_data = null;
            if(count($applied_transaction) > 0)
            {
                foreach ($applied_transaction as $key => $value) 
                {
                    $get_data = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("est_id", $key)->first();
                    if($get_data)
                    {
                        $attached_transaction_data[$key]['transaction_ref_name'] = "sales_order";
                        $attached_transaction_data[$key]['transaction_ref_id'] = $key;
                        $attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
                        $attached_transaction_data[$key]['transaction_date'] = $get_data->est_date;
                    }
                }
            }*/
        }

        if($transaction_data)
        {
            AccountingTransaction::postTransaction($shop_id, $transaction_data, null);
        }
    }

}