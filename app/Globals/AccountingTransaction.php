<?php
namespace App\Globals;
use App\Models\Tbl_acctg_transaction;
use App\Models\Tbl_acctg_transaction_list;
use App\Models\Tbl_acctg_transaction_item;
use App\Models\Tbl_transaction_ref_number;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_requisition_slip;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_receive_inventory;
use App\Models\Tbl_customer_wis;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_bill;
use App\Models\Tbl_write_check;
use App\Models\Tbl_debit_memo;
use App\Models\Tbl_warehouse_issuance_report;
use App\Models\Tbl_warehouse_receiving_report;
use App\Models\Tbl_inventory_adjustment;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_user;
use App\Models\Tbl_settings;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_journal_entry;
use App\Models\Tbl_item;
use App\Models\Tbl_requisition_slip_item;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_receive_inventory_line;
use App\Models\Tbl_bill_item_line;
use App\Models\Tbl_bill_account_line;
use App\Models\Tbl_write_check_line;
use App\Models\Tbl_write_check_account_line;
use App\Models\Tbl_pay_bill_line;
use App\Models\Tbl_debit_memo_line;
use App\Models\Tbl_customer_estimate_line;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_customer_wis_item_line;
use App\Models\Tbl_customer_wis_budget;
use App\Models\Tbl_customer_wis_budgetline;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_receive_payment_credit;
use App\Models\Tbl_credit_memo_line;
use App\Models\Tbl_warehouse_issuance_report_itemline;
use App\Models\Tbl_warehouse_receiving_report_itemline;
use App\Models\Tbl_inventory_adjustment_line;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_quantity_monitoring;
use App\Models\Tbl_item_range_sales_discount;

use Carbon\Carbon;
use Validator;
use DB;
use App\Globals\Accounting;
use App\Globals\CustomerWIS;
use App\Globals\Item;
use App\Globals\Warehouse2;
use App\Globals\Warehouse;
use App\Globals\AuditTrail;

use App\Globals\ReferenceNumberFormatter\ReferenceNumberFormat;
use stdClass;
/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */

class AccountingTransaction
{
	
	public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
    
	public static function checkItemLineQty($bill_id,$transaction_id,$for_update)
	{
		// dd($bill_id,$transaction_id,$for_update);

		$item_line = Tbl_bill_item_line::where('itemline_bill_id',$bill_id)->get();
		$ctr = 0;
		foreach ($item_line as $key => $value) 
		{
 			$monitoring_qty = Tbl_quantity_monitoring::where('qty_transaction_id', $transaction_id)->where('qty_item_id', $value->itemline_item_id)->where('qty_ref_name', 'enter_bills')->where('qty_ref_id',$value->itemline_id)->first();

			$remaining = $value->itemline_qty - $monitoring_qty->qty_new;

			if($for_update)
            {
                $remaining = $value->itemline_qty - ($monitoring_qty->qty_new - $monitoring_qty->qty_old);
            }
            $update['itemline_qty'] = $remaining;   
            Tbl_bill_item_line::where('itemline_id',$value->itemline_id)->update($update);

            if($update['itemline_qty'] <= 0)
            {
                $ctr++;
            }
            if($value->itemline_qty < $value->itemline_orig_qty)
            {
                $update_bill['bill_is_paid'] = 0;
                Tbl_bill::where('bill_id',$bill_id)->update($update_bill);
            }
		}
		if($ctr >= count($item_line))
        {
            $updates["bill_is_paid"] = $transaction_id;
            Tbl_bill::where('bill_id',$bill_id)->update($updates);
        }
	}
	public static function checkPolineQty($po_id, $transaction_id, $for_update)
    {
        $poline = Tbl_purchase_order_line::where('poline_po_id', $po_id)->get();
        $ctr = 0;
        foreach ($poline as $key => $value)
        {
            $monitoring_qty = Tbl_quantity_monitoring::where('qty_transaction_id', $transaction_id)->where('qty_item_id', $value->poline_item_id)->where('qty_ref_name', 'purchase_order')->where('qty_ref_id',$po_id)->first();

            if($monitoring_qty)
           	{
	            $remaining = $value->poline_qty - $monitoring_qty->qty_new;
	            if($for_update)
	            {
	                $remaining = $value->poline_qty - ($monitoring_qty->qty_new - $monitoring_qty->qty_old);
	            }
	            $update['poline_qty'] = $remaining;   
	            $update['poline_received_qty'] = $value->poline_qty - $remaining;  
	            Tbl_purchase_order_line::where('poline_id', $value->poline_id)->update($update);    
	            if($update['poline_qty'] <= 0)
	            {
	                $ctr++;
	            }
	            if($value->poline_qty < $value->poline_orig_qty)
	            {
	                $update_po['po_is_billed'] = 0;
	                Tbl_purchase_order::where("po_id",$po_id)->update($update_po);
	            }
           	}

        }
        if($ctr >= count($poline))
        {
            $updates["po_is_billed"] = $transaction_id;
            Tbl_purchase_order::where("po_id",$po_id)->update($updates);

            foreach ($poline as $key_line => $value_line)
            {
            	$update_status['poline_item_status'] = 1;
            	Tbl_purchase_order_line::where('poline_po_id', $po_id)->update($update_status);
            }

        }
    }
    public static function checkSolineQty($so_id, $transaction_id, $for_update)
    {
        $soline = Tbl_customer_estimate_line::where('estline_est_id', $so_id)->get();
        $ctr = 0;
        foreach ($soline as $key => $value)
        {
        	$so = Tbl_customer_estimate::where('est_id', $value->estline_est_id)->first();
        	if($so)
        	{
        		$monitoring_qty = Tbl_quantity_monitoring::where('qty_transaction_id', $transaction_id)->where('qty_item_id', $value->estline_item_id)->where('qty_ref_id', $so_id);
        		if($so->is_sales_order == 1)
        		{
        			$monitoring_qty = $monitoring_qty->where('qty_ref_name', 'sales_order');
        		}
        		else
        		{
        			$monitoring_qty = $monitoring_qty->where('qty_ref_name', 'estimate_quotation');
        		}

        		$monitoring_qty = $monitoring_qty->first();
        		
		        if($monitoring_qty)
		        {
		            $remaining = $value->estline_qty - $monitoring_qty->qty_new;
		            if($for_update)
		            {	
		                $remaining = $value->estline_qty - ($monitoring_qty->qty_new - $monitoring_qty->qty_old);
		            }
		            $update['estline_qty'] = $remaining;  
		            $update['estline_received_qty'] = $value->estline_orig_qty - $remaining;  
		            Tbl_customer_estimate_line::where('estline_id', $value->estline_id)->update($update);    

		            if($update['estline_qty'] <= 0)
		            {
		                $ctr++;
		            }
		            if($value->estline_qty < $value->estline_orig_qty)
		            {
		                $update_so['est_status'] = 'accepted';
			        	Tbl_customer_estimate::where("est_id",$so_id)->update($update_so);
		            }
		        }
        	}
        }
        if($ctr >= count($soline))
        {
            $updates["est_status"] = "closed";
            Tbl_customer_estimate::where("est_id",$so_id)->update($updates);

            foreach ($soline as $key_line => $value_line)
            {
            	$update_status['estline_status'] = 1;
            	Tbl_customer_estimate_line::where('estline_est_id', $so_id)->update($update_status);
            }
        }
    }
    public static function checkInvlineQty($inv_id, $transaction_id, $for_update = null)
    {
        $invline = Tbl_customer_invoice_line::where('invline_inv_id',  $inv_id)->get();
        $ctr = 0;
        foreach ($invline as $key => $value)
        {
            $monitoring_qty = Tbl_quantity_monitoring::where('qty_transaction_id', $transaction_id)->where('qty_item_id', $value->invline_item_id)->where('qty_ref_name', 'sales_invoice')->where('qty_ref_id',  $inv_id)->first();
            //dd($monitoring_qty);
            $remaining = $value->invline_qty;
            if($monitoring_qty)
            {
            	$remaining = $value->invline_qty - $monitoring_qty->qty_new;
            }
            if($for_update && $monitoring_qty)
            {
                $remaining = $value->invline_qty - ($monitoring_qty->qty_new - $monitoring_qty->qty_old);
            }
            $update['invline_qty'] = $remaining;   
            Tbl_customer_invoice_line::where('invline_id', $value->invline_id)->update($update);    

            if($update['invline_qty'] <= 0)
            {
                $ctr++;
            }
            if($value->invline_qty < $value->invline_orig_qty)
            {
                $update_si['item_delivered'] = 0;
                Tbl_customer_invoice::where("inv_id", $inv_id)->update($update_si);
            }
        }
        if($ctr >= count($invline))
        {
            $updates["item_delivered"] = 1;
            Tbl_customer_invoice::where("inv_id", $inv_id)->update($updates);
        }
    }
	public static function check_coa_exist($shop_id, $account_number, $account_name)
	{
		$check = Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_name", $account_name)->first();
		$return = null;
		if($check)
		{
			$up['account_number'] = $account_number;
			Tbl_chart_of_account::where("account_shop_id", $shop_id)->where("account_name", $account_name)->update($up);
			$return = $check->account_id;
		}
		else
		{
			$ins['account_shop_id'] = $shop_id;
			$ins['account_name'] = $account_name;
			$ins['account_number'] = $account_number;
			$ins['account_code'] = 'accounting-receivable';
			$ins['account_type_id'] = 2;
			$return = Tbl_chart_of_account::insertGetId($ins);
		}
		return $return;
	}
	public static function acctg_trans($shop_id, $data, $type = '')
	{
		$settings = AccountingTransaction::settings($shop_id, "allow_transaction");
        if(is_numeric($settings))
        {
            $data = $data->acctg_trans($type)->where("transaction_warehouse_id", Warehouse2::get_current_warehouse($shop_id));
        }  
        return $data;
	}
	public static function print_format($shop_id, $key)
	{
		$val = Self::settings_value($shop_id, $key);

		$return['size'] = "A4";
		$return['width'] = "100";

		$ex = explode("-", $val);
		if(isset($ex[1]))
		{
			$return['size'] = $ex[0];
			$return['width'] = $ex[1];			
		}

		return $return; 
	}
	public static function settings_value($shop_id, $key)
	{
		$get_user = Self::get_user_data();
		$return = null;
		$get = Tbl_settings::where("shop_id", $shop_id)->where("settings_key", $key)->first();
		if($get)
		{
			$return = $get->settings_value;
		}	
		return $return;
	}
	public static function settings($shop_id, $key)
	{
		$get_user = Self::get_user_data();
		$return = null;
		$get = Tbl_settings::where("shop_id", $shop_id)->where("settings_key", $key)->first();
		if($get)
		{
			if($get->settings_value && $get->settings_value != 0)
			{
				$return = $get->settings_value;
			}
		}
		// if($get_user)
		// {
		// 	if($get_user->user_level != 1)
		// 	{
		// 		$return = null;
		// 	}
		// }		
		return $return;
	}
	/**
	  * @param  
		$trans_data = [
		transaction_ref_name
		transaction_ref_id
		transaction_list_number	
		transaction_date]

		$trans_item = [
			itemline_item_id	int(10) unsigned	 
			itemline_item_um	int(10) unsigned NULL	 
			itemline_item_description	text	 
			itemline_item_qty	double	 
			itemline_item_rate	double	 
			itemline_item_taxable	tinyint(4)	 
			itemline_item_discount	double	 
			itemline_item_discount_type	varchar(255)	 
			itemline_item_discount_remarks	varchar(255)	 
			itemline_item_amount double	
		]
	  */
	public static function get_transaction_list($shop_id, $ref_name = '', $ref_id = 0)
	{
		$get = Tbl_acctg_transaction_list::acctgTransaction()
										 ->where("tbl_acctg_transaction.shop_id", $shop_id)
										 ->where("transaction_ref_name", $ref_name)
										 ->where("transaction_ref_id", $ref_id)
										 ->orderBy("acctg_transaction_list_id",'ASC')
										 ->first();
		//dd($get);
		return $get;
	}
	public static function insertTransaction($shop_id, $trans_data = array())
	{
		$insert_trans['shop_id'] = $shop_id;
		$insert_trans['transaction_number'] = Self::get_ref_num($shop_id, 'accounting_transaction'); // MUST BE AUTO GENERATED
		$insert_trans['transaction_user_id'] = Self::getUserid();
		$insert_trans['transaction_warehouse_id'] = Warehouse2::get_current_warehouse($shop_id);
		$insert_trans['transaction_created_at'] = Carbon::now();

		$acctg_trans_id = Tbl_acctg_transaction::insertGetId($insert_trans);

		$trans_data['acctg_transaction_id'] = $acctg_trans_id;
		$trans_data['date_created'] = Carbon::now();
		Tbl_acctg_transaction_list::insert($trans_data);
		return $acctg_trans_id;
	}

	public static function updateTransaction($acctg_trans_id, $trans_data = array())
	{
		$trans_data['acctg_transaction_id'] = $acctg_trans_id;
		$trans_data['date_created'] = Carbon::now();

		Tbl_acctg_transaction_list::insert($trans_data);

		$get = Tbl_acctg_transaction::where("acctg_transaction_id", $acctg_trans_id)->first();

		if($get)
		{
			$datenow = $get->transaction_created_at;
			if($get->acctg_transaction_history)
			{
				// $getval = serialize([]);
    //             $serialize = unserialize($get->acctg_transaction_history);
				// $serialize[$datenow] = collect($get)->toArray();

    //             $update['acctg_transaction_history'] = serialize($serialize);
    //             Tbl_acctg_transaction::where("acctg_transaction_id", $acctg_trans_id)->update($update);
			}
			else
			{
				// $serialize[$datenow] = collect($get)->toArray();
    //             $update['acctg_transaction_history'] = serialize($serialize);
    //             Tbl_acctg_transaction::where("acctg_transaction_id", $acctg_trans_id)->update($update);
			}
			$update_trans['transaction_user_id'] = Self::getUserid();
			$update_trans['transaction_warehouse_id'] = $get->transaction_warehouse_id;
			$update_trans['transaction_created_at'] = Carbon::now();
			Tbl_acctg_transaction::where("acctg_transaction_id", $acctg_trans_id)->update($update_trans);
		}


		return $acctg_trans_id;
	}
	 /* 
	 	<-- PARAMS -->
	 	$transaction_data['transaction_ref_name'] - Reference Name
	 	$transaction_data['transaction_ref_id'] - Reference ID
	 	$transaction_data['transaction_list_number'] - Reference Number
	 	$transaction_data['transaction_date'] - Date

	 	$attached_transaction_data[0]['transaction_ref_name'] - Reference Name
	 	$attached_transaction_data[0]['transaction_ref_id'] - Reference ID
	 	$attached_transaction_data[0]['transaction_list_number'] - Reference Number
	 	$attached_transaction_data[0]['transaction_date'] - Date
			
	 */
	public static function get_attached_transaction($txn_name, $txn_id, $attached_txn_name)
	{
		$get_acctg_txn_id = Tbl_acctg_transaction_list::where("transaction_ref_name", $txn_name)->where("transaction_ref_id", $txn_id)->orderBy("acctg_transaction_list_id","DESC")->value("acctg_transaction_id");
		$get_attached_txn_id = null;
		if($get_acctg_txn_id)
		{
			$get_attached_txn_id = Tbl_acctg_transaction_list::where("acctg_transaction_id", $get_acctg_txn_id)
															 ->where("transaction_ref_name", $attached_txn_name)
															 ->get();
		}
		return $get_attached_txn_id;
	}
    public static function get_user_data()
    {
        $user_data = Tbl_user::where("user_email", session('user_email'))->shop()->first();
        return $user_data;
    }
    public static function getUserid()
    {
        $user_id = 0;
        $user_data = Tbl_user::where("user_email", session('user_email'))->shop()->value('user_id');
        if($user_data)
        {
            $user_id = $user_data;
        }
        return $user_id;
    }
	public static function postTransaction($shop_id, $transaction_data, $attached_transaction_data = array())
	{
		$check = Self::check_transaction($shop_id, $transaction_data['transaction_ref_name'], $transaction_data['transaction_ref_id']);
		if(!$check)
		{
			$acctg_trans_id = Self::insertTransaction($shop_id, $transaction_data);
		}
		else
		{
			$acctg_trans_id = $check;
			Tbl_acctg_transaction_list::where("acctg_transaction_id", $acctg_trans_id)->delete();
			Self::updateTransaction($acctg_trans_id, $transaction_data);
		}

		if(is_numeric($acctg_trans_id))
		{
			Self::attached_transaction($acctg_trans_id, $attached_transaction_data);			
		}
		return $acctg_trans_id;
	}
	public static function attached_transaction($acctg_trans_id, $attached_transaction_data = array())
	{
		$date =  Carbon::now();
		if(count($attached_transaction_data) > 0)
		{
			$list = null;
			foreach ($attached_transaction_data as $key => $value) 
			{
				$list[$key] = $value;
				$list[$key]['acctg_transaction_id'] = $acctg_trans_id;
				$list[$key]['date_created'] = $date;
			}
			if(count($list) > 0)
			{
				Self::insertTransactionList($list);
			}
		}
	}
	public static function insertTransactionList($transaction_data)
	{
		Tbl_acctg_transaction_list::insert($transaction_data);
	}
	public static function check_transaction($shop_id, $transaction_name = '', $transaction_id = 0)
	{
		$check = Tbl_acctg_transaction_list::acctgTransaction()
											->where("tbl_acctg_transaction.shop_id", $shop_id)
											->where("transaction_ref_name", $transaction_name)
											->where("transaction_ref_id", $transaction_id)->orderBy("acctg_transaction_list_id","ASC")
											->first();
		
		$return = null;
		if($check)
		{
			$return = $check->acctg_transaction_id;
		}
		return $return;
	}
	public static function insertItemline($acctg_trans_id, $trans_item)
	{
		if(count($trans_item) > 0)
		{
			foreach ($trans_item as $key => $value)
			{
				$ins['acctg_transaction_id'] 		  	= $acctg_trans_id;
				$ins['itemline_item_id'] 			  	= isset($value['item_id']) ? $value['item_id'] : '';
				$ins['itemline_item_um'] 			  	= isset($value['item_um']) ? $value['item_um'] : '';
				$ins['itemline_item_description']     	= isset($value['item_description']) ? $value['item_description'] : '';
				$ins['itemline_item_qty'] 			  	= isset($value['item_qty']) ? $value['item_qty'] : '';
				$ins['itemline_item_rate'] 			  	= isset($value['item_rate']) ? $value['item_rate'] : '';
				$ins['itemline_item_taxable'] 		  	= isset($value['item_taxable']) ? $value['item_taxable'] : '';
				$ins['itemline_item_discount'] 		  	= isset($value['item_discount']) ? $value['item_discount'] : '';
				$ins['itemline_item_discount_type'] 	= isset($value['item_discount_type']) ? $value['item_discount_type'] : '';
				$ins['itemline_item_discount_remarks'] 	= isset($value['item_discount_remarks']) ? $value['item_discount_remarks'] : '';
				$ins['itemline_item_amount'] 			= isset($value['item_amount']) ? $value['item_amount'] : '';

				Tbl_acctg_transaction_item::insert($ins);
			}
		}
	}
	public static function vendorValidation($insert, $insert_item, $transaction_type = '', $transaction_id = '')
	{
		$return = null;
        if(count($insert_item) <= 0)
        {
            $return .= '<li style="list-style:none">Please Select Item.</li>';
        }
        if($transaction_type == 'receive_inventory')
        {
        	$return .= Self::check_transaction_ref_number(Self::shop_id(), $insert['transaction_refnumber'], $transaction_type);
    		/*if(count($insert_item) > 0)
    		{
        		foreach ($insert_item as $key => $value)
        		{
		        	if($transaction_id)
		        	{
		        		$receivedline = Tbl_receive_inventory_line::where('riline_ri_id', $transaction_id)->where('riline_ref_name', 'purchase_order')->where('riline_item_id', $value['item_id'])->where('riline_ref_id',$value['item_ref_id'])->first();
		        		dd($receivedline);
	        			$poline = Tbl_purchase_order_line::where('poline_po_id', $value['item_ref_id'])->where("poline_item_id", $value['item_id'])->first();
	        			dd(($value['item_qty']." ".
	        				$receivedline->riline_qty)." ".
	        			$poline->poline_qty);
        				if(($value['item_qty'] - $receivedline->riline_qty) > $poline->poline_qty)
        				{
        					$return .= "<li style='list-style:none'>Item ". $value['item_description']."'s quantity not match to PO!</li>";
        				}
	        		}
	        		else
	        		{
	        			$poline = Tbl_purchase_order_line::where('poline_po_id', $value['item_ref_id'])->where("poline_item_id", $value['item_id'])->first();
				        $poline_qty = 0;
				        if($poline)
				        {
				            $poline_qty = UnitMeasurement::get_umqty($poline->poline_um_id) * $poline->poline_qty;
				            if($value['item_qty'] > $poline_qty)
	        				{
	        					$return .= "<li style='list-style:none'>Item ". $value['item_description']."'s quantity not match to PO!</li>";
	        				}
				        }
	        		}
	        	}
        	}*/
        }


        if(!$insert['vendor_id'])
        {
            $return .= '<li style="list-style:none">Please Select Vendor.</li>';          
        }

        if($transaction_type)
        {
        	$return .= Self::check_transaction_ref_number(Self::shop_id(), $insert['transaction_refnumber'], $transaction_type);
        }

		$rules['transaction_refnumber'] = 'required';
        $rules['vendor_email']    		= 'email';

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
	public static function shop_id()
	{
		$shop_id = Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');

		return $shop_id;
	}
	
	public static function customer_validation($insert, $insert_item, $transaction_type = '')
	{
		
		// dd($insert, $insert_item, $transaction_type);
		$return = null;
        if(count($insert_item) <= 0)
        {
            $return .= '<li style="list-style:none">Please Select Item.</li>';
        }

		if(!$insert['customer_id'])
        {
        	$return .= '<li style="list-style:none">Please select customer.</li>';        	
        }
        if($transaction_type)
        {
        	$return .= Self::check_transaction_ref_number(Self::shop_id(), $insert['transaction_refnum'], $transaction_type);
        }

        // if(isset($insert['transaction_payment_method']))
        // {
	       //  //checking for duplicate cheque ref number
	       //  if(DB::table('tbl_payment_method')->where('payment_method_id',$insert['transaction_payment_method'])->first()->payment_name == 'Cheque') // if cheque is selected
	       //  {
	       //      if(!$insert['transaction_ref_no'])//if cheque is selected and null transaction_ref_no
	       //      {
	       //          $return .= '<li style="list-style:none">Please Input Cheque Reference Number.</li>';          
	       //      }
	       //      if(isset($insert['function_do']))
	       //      {
		      //       if($insert['function_do'] == 'insert')//if insert function
		      //       {
		      //           if(DB::table($insert['tbl_name'])->where($insert['column_for_reference_number'],$insert['transaction_ref_no'])->count() >= 1) //if cheque is selected and transaction_ref_no >= 1
		      //           {
		      //               $return .= '<li style="list-style:none">Duplicate Cheque Reference Number.</li>';   
		      //           }
		      //       }
	       //      }
	            
	       //  }
        // }
        
        $rules['transaction_refnum'] = 'required';
        $rules['customer_email'] = 'email';

        $validator = Validator::make($insert, $rules);
        if($validator->fails())
        {
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $return .= $message;
            }
        }
        return $return;
	}
	public static function check_transaction_ref_number($shop_id, $transaction_refnum, $transaction_type)
	{
		$return = null;
		$get = null;

		if($transaction_type == 'sales_invoice')
		{
			$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->where('is_sales_receipt',0)->first();
		}
		if($transaction_type == 'sales_receipt')
		{
			$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->where('is_sales_receipt',1)->first();
		}
		if($transaction_type == 'credit_memo')
		{
			$get = Tbl_credit_memo::where('cm_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($transaction_type == 'estimate_quotation')
		{
			$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->where('is_sales_order',0)->first();
		}
		if($transaction_type == 'sales_order')
		{
			$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->where('is_sales_order', 1)->first();
		}
		if($transaction_type == 'warehouse_issuance_slip')
		{
			$get = Tbl_customer_wis::where('cust_wis_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($transaction_type == 'warehouse_transfer')
		{
			$get = Tbl_warehouse_issuance_report::where('wis_shop_id', $shop_id)->where('wis_number', $transaction_refnum)->first();
		}
		if($transaction_type == 'receiving_report')
		{
			$get = Tbl_warehouse_receiving_report::where('rr_shop_id', $shop_id)->where('rr_number', $transaction_refnum)->first();
		}
		if($transaction_type == 'purchase_requisition')
		{
			$get = Tbl_requisition_slip::where('shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($transaction_type == 'purchase_order')
		{
			$get = Tbl_purchase_order::where('po_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($transaction_type == 'received_inventory')
		{
			$get = Tbl_receive_inventory::where('ri_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			//die(var_dump($get));
		}
		if($transaction_type == 'enter_bills')
		{
			$get = Tbl_bill::where('bill_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($transaction_type == 'pay_bill')
		{
			$get = Tbl_pay_bill::where('paybill_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($transaction_type == 'write_check')
		{
			$get = Tbl_write_check::where('wc_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($transaction_type == 'debit_memo')
		{
			$get = Tbl_debit_memo::where('db_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($transaction_type == 'inventory_adjustment')
		{
			$get = Tbl_inventory_adjustment::where('adj_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($transaction_type == 'received_payment')
		{
			$get = Tbl_receive_payment::where('rp_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
		}
		if($get)
		{
			$return = "Duplicate transaction number <br>";
		}

		return $return;

	}
	public static function get_transaction($shop_id, $transaction_id, $transaction_type)
	{
		//dd($shop_id." ".$transaction_id." ".$transaction_type);
		$return = null;
		$get = null;

		if($transaction_type == 'purchase_requisition')
		{
			$get = Tbl_requisition_slip::where('shop_id', $shop_id)->where('requisition_slip_id', $transaction_id);
		}
		if($transaction_type == 'purchase_order')
		{
			$get = Tbl_purchase_order::where('po_shop_id', $shop_id)->where('po_id', $transaction_id);
		}
		if($transaction_type == 'receive_inventory')
		{
			$get = Tbl_receive_inventory::where('ri_shop_id', $shop_id)->where('ri_id', $transaction_id);
		}
		if($transaction_type == 'enter_bills')
		{
			$get = Tbl_bill::where('bill_shop_id', $shop_id)->where('bill_id', $transaction_id);
		}
		if($transaction_type == 'write_check')
		{
			$get = Tbl_write_check::where('wc_shop_id', $shop_id)->where('wc_id', $transaction_id);
		}
		if($transaction_type == 'pay_bill')
		{
			$get = Tbl_pay_bill::where('paybill_shop_id', $shop_id)->where('paybill_id', $transaction_id);
		}
		if($transaction_type == 'debit_memo')
		{
			$get = Tbl_debit_memo::where('db_shop_id', $shop_id)->where('db_id', $transaction_id);
		}
		if($transaction_type == 'estimate_quotation')
		{
			$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('est_id', $transaction_id)->where('is_sales_order',0);
		}
		if($transaction_type == 'sales_order')
		{
			$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('est_id', $transaction_id)->where('is_sales_order',1);
		}
		if($transaction_type == 'sales_invoice')
		{
			$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('inv_id', $transaction_id)->where('is_sales_receipt',0);
		}
		if($transaction_type == 'sales_receipt')
		{
			$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('inv_id', $transaction_id)->where('is_sales_receipt',1);
		}
		if($transaction_type == 'warehouse_issuance_slip')
		{
			$get = Tbl_customer_wis::where('cust_wis_shop_id', $shop_id)->where('cust_wis_id', $transaction_id);
		}
		if($transaction_type == 'received_payment')
		{
			$get = Tbl_receive_payment::where('rp_shop_id', $shop_id)->where('rp_id', $transaction_id);
		}
		if($transaction_type == 'credit_memo')
		{
			$get = Tbl_credit_memo::where('cm_shop_id', $shop_id)->where('cm_id', $transaction_id);
		}
		if($transaction_type == 'warehouse_transfer')
		{
			$get = Tbl_warehouse_issuance_report::where('wis_shop_id', $shop_id)->where('wis_id', $transaction_id);
		}
		if($transaction_type == 'receiving_report')
		{
			$get = Tbl_warehouse_receiving_report::where('rr_shop_id', $shop_id)->where('rr_id', $transaction_id);
		}
		if($transaction_type == 'inventory_adjustment')
		{
			$get = Tbl_inventory_adjustment::where('adj_shop_id', $shop_id)->where('inventory_adjustment_id', $transaction_id);
		}
		if($transaction_type == 'manual_journal_entry')
		{
			$get = Tbl_journal_entry::where('je_shop_id', $shop_id)->where('je_id', $transaction_id);
		}

		return $get;

	}
	public static function get_transaction_line($transaction_id, $transaction_type)
	{
		$return = null;
		$get = null;

		
		if($transaction_type == 'purchase_requisition')
		{
			$get = Tbl_requisition_slip_item::where('rs_id', $transaction_id);
		}
		if($transaction_type == 'purchase_order')
		{
			$get = Tbl_purchase_order_line::where('poline_po_id', $transaction_id);
		}
		if($transaction_type == 'receive_inventory')
		{
			$get = Tbl_receive_inventory_line::where('riline_ri_id', $transaction_id);
			//die(var_dump($get));
		}
		if($transaction_type == 'enter_bills')
		{
			$get = Tbl_bill_item_line::where('itemline_bill_id', $transaction_id);
		}
		if($transaction_type == 'write_check')
		{
			$get = Tbl_write_check_line::where('wcline_wc_id', $transaction_id);
		}
		if($transaction_type == 'pay_bill')
		{
			$get = Tbl_pay_bill_line::where('pbline_pb_id', $transaction_id);
		}
		if($transaction_type == 'debit_memo')
		{
			$get = Tbl_debit_memo_line::where('dbline_db_id', $transaction_id);
		}
		if($transaction_type == 'estimate_quotation')
		{
			$get = Tbl_customer_estimate_line::where('estline_est_id', $transaction_id);
		}
		if($transaction_type == 'sales_order')
		{
			$get = Tbl_customer_estimate_line::where('estline_est_id', $transaction_id);
		}
		if($transaction_type == 'sales_invoice')
		{
			$get = Tbl_customer_invoice_line::where('invline_inv_id', $transaction_id);
		}
		if($transaction_type == 'sales_receipt')
		{
			$get = Tbl_customer_invoice_line::where('invline_inv_id', $transaction_id);
		}
		if($transaction_type == 'warehouse_issuance_slip')
		{
			$get = Tbl_customer_wis_item_line::where('itemline_wis_id', $transaction_id);
		}
		if($transaction_type == 'received_payment')
		{
			$get = Tbl_receive_payment_line::where('rpline_rp_id', $transaction_id);
		}
		if($transaction_type == 'credit_memo')
		{
			$get = Tbl_credit_memo_line::where('cmline_cm_id', $transaction_id);
		}
		if($transaction_type == 'warehouse_transfer')
		{
			$get = Tbl_warehouse_issuance_report_itemline::where('wt_wis_id', $transaction_id);
		}
		if($transaction_type == 'receiving_report')
		{
			$get = Tbl_warehouse_receiving_report_itemline::where('rr_id', $transaction_id);
		}
		if($transaction_type == 'inventory_adjustment')
		{
			$get = tbl_inventory_adjustment_line::where('itemline_ia_id', $transaction_id);
		}
		if($transaction_type == 'manual_journal_entry')
		{
			$get = Tbl_journal_entry_line::where('jline_je_id', $transaction_id);
		}
		

		return $get;

	}
	public static function get_transaction_acc_line($transaction_id, $transaction_type)
	{
		$get = null;

		if($transaction_type == 'enter_bills')
		{
			$get = Tbl_bill_account_line::where('accline_bill_id', $transaction_id);
		}
		if($transaction_type == 'write_check')
		{
			$get = Tbl_write_check_account_line::where('accline_wc_id', $transaction_id);
		}

		return $get;

	}
	public static function audit_trail($shop_id, $transaction_id, $transaction_type)
	{
		//dd($shop_id." ".$transaction_id." ".$transaction_type);
		$transaction = Self::get_transaction($shop_id, $transaction_id, $transaction_type)->first()->toArray();
        $transaction_line = Self::get_transaction_line($transaction_id, $transaction_type)->get()->toArray();
        if($transaction_type == 'enter_bills' || $transaction_type == 'write_check')
        {
       		$transaction_acc_line = Self::get_transaction_acc_line($transaction_id, $transaction_type)->get()->toArray();
        }
        if($transaction_type == 'warehouse_issuance_slip')
        {
        	$transaction_montly_budget = Tbl_customer_wis_budget::where('budget_wis_id', $transaction_id)->first();/*->toArray();*/
        	if($transaction_montly_budget || $transaction_montly_budget != null)
        	{
        		$transaction_montly_budget_line = Tbl_customer_wis_budgetline::where('budgetline_id', $transaction_montly_budget['wis_budget_id'])->get()->toArray();
        	}
        }
        if($transaction_type == 'received_payment')
        {
        	$transaction_payment_credit = Tbl_receive_payment_credit::where('rp_id', $transaction_id)->get()->toArray();
        }
        $transaction_data = null;
        foreach ($transaction_line as $key => $value)
        {
            $transaction_data['transaction'] = $transaction;
            $transaction_data['transaction_line'] = $transaction_line;
            if($transaction_type == 'enter_bills' || $transaction_type == 'write_check')
	        {
	        	if(count($transaction_acc_line) > 0 || $transaction_acc_line != null)
	        	{
	        		$transaction_data['transaction_acc_line'] = $transaction_acc_line;
	        	}
	        }
	        if($transaction_type == 'warehouse_issuance_slip')
	        {
	        	if(count($transaction_montly_budget) > 0)
	        	{
	        		$transaction_data['transaction_montly_budget'] = $transaction_montly_budget;
	        		if(count($transaction_montly_budget_line) > 0)
	        		{
	        			$transaction_data['transaction_montly_budget_line'] = $transaction_montly_budget_line;
	        		}
	        	}
	        }
        	if($transaction_type == 'received_payment')
	        {
	        	if(count($transaction_payment_credit) > 0)
	        	{
	        		$transaction_data['transaction_payment_credit'] = $transaction_payment_credit;
	        	}
	        }
        }         
        return $transaction_data;
	}
	public static function entry_data($entry, $insert_item, $for_review = false)
	{
		$overall_disc = 0;
		foreach ($insert_item as $key => $value) 
		{
			/* DISCOUNT PER LINE */
	        $discount       = isset($value['item_discount']) ? $value['item_discount'] : 0;
	        $discount_type  = 'fixed';
	        if(strpos($discount, '%'))
            {
            	$discount       = str_replace("%", "", $discount) / 100;
                $discount_type  = 'percent';
                $discount 		= ($value['item_rate'] * $value['item_qty'])  * $discount; 
            }
            $value['amount'] = $value['item_rate'];
            if($for_review)
            {
            	if($value['item_discount_type'] == 'percent')
            	{
	            	$item_sub_total = ($value['item_amount'] / (1 - $discount));
	            	$value['amount'] = $item_sub_total;
	                $discount_type  = 'percent';
	                $discount 		= ($value['item_amount'] / (1 - $discount)) - $value['item_amount'];
            	}
            }
			$item_type = Item::get_item_type($value['item_id']);
            /* TRANSACTION JOURNAL */  
            if($item_type != 4 && $item_type != 5)
            {
                $entry_data[$key]['item_id']            = $value['item_id'];
                $entry_data[$key]['entry_qty']          = $value['item_qty'] ;
                $entry_data[$key]['vatable']            = 0;
                $entry_data[$key]['discount']           = $discount;
                $entry_data[$key]['entry_amount']       = $value['item_amount'] * $value['item_qty'];
                $entry_data[$key]['entry_description']  = $value['item_description'];
                                    
            }
            else
            {
                $item_bundle = Item::get_item_in_bundle($value['item_id']);
                if(count($item_bundle) > 0)
                {
                    foreach ($item_bundle as $key_bundle => $value_bundle) 
                    {
                        $item_data = Item::get_item_details($value_bundle->bundle_item_id);
                        $entry_data['b'.$key.$key_bundle]['item_id']            = $value_bundle->bundle_item_id;
                        $entry_data['b'.$key.$key_bundle]['entry_qty']          = $value['item_qty'] * (UnitMeasurement::get_umqty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty);
                        $entry_data['b'.$key.$key_bundle]['vatable']            = 0;
                        $entry_data['b'.$key.$key_bundle]['discount']           = 0;
                        $entry_data['b'.$key.$key_bundle]['entry_amount']       = $item_data->item_price * $entry_data['b'.$key.$key_bundle]['entry_qty'];
                        $entry_data['b'.$key.$key_bundle]['entry_description']  = $item_data->item_sales_information; 
                    }
                }
            }
            $entry_data[$key]['entry_amount'] = $value['amount'] * $value['item_qty'];
           	if($for_review)
           	{
            	$entry_data[$key]['entry_amount'] = $value['amount'];
            	$overall_disc += $discount;
           	}
		}
		$entry['total'] = $entry['total'] + $overall_disc;
        $inv_journal = Accounting::postJournalEntry($entry, $entry_data);
        return $inv_journal;
	}
	public static function get_redirect($transaction_type, $transaction_id, $btn_action = 'sclose')
	{
		$return = null;
		$return = '/member/transaction/'.$transaction_type;
		if($btn_action == 'sclose')
		{
			$return = '/member/transaction/'.$transaction_type;
		}
		elseif($btn_action == 'sedit')
		{
			$return = '/member/transaction/'.$transaction_type.'/create?id='.$transaction_id;
		}
		elseif($btn_action == 'sprint')
		{
			$return = '/member/transaction/'.$transaction_type.'/print?id='.$transaction_id;
		}
		elseif($btn_action == 'snew')
		{
			$return = '/member/transaction/'.$transaction_type.'/create';
		}
		elseif($btn_action == 'swis')
		{
			$return = '/member/transaction/wis/create?si_id='.$transaction_id;
		}

		return $return;	
	}
	public static function get_user_id()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_id');

	}
	public static function warehouse_initials($shop_id)
	{
		$return = '';
		$user_id = Self::get_user_id();

		$get = Self::settings($shop_id, 'transaction_number');
		if(is_numeric($get))
		{
			$initials = '';
			$warehouse = Tbl_warehouse::where("warehouse_id", Warehouse2::get_current_warehouse($shop_id))->first();
			if($warehouse)
			{
				$string = explode(" ", $warehouse->warehouse_name);
				if(count($string) > 0)
				{
					foreach ($string as $key => $value) 
					{
						$initials .= $value[0]; 
					}
					$return = $initials.Warehouse::getUserid();
				}
			}
		}
		return $return;
	}
	public static function get_ref_num($shop_id, $transaction_type)
	{
		// $return = null;
		// if($transaction_type)
		// {
		// 	$get = Tbl_transaction_ref_number::where('shop_id', $shop_id)->where('key', $transaction_type)->first();
		// 	if($get)
		// 	{
		// 		$date = explode('/', $get->other);
		// 		if(isset($date[2]))
		// 		{
		// 			$branches_initials = Self::warehouse_initials($shop_id);
		// 			$datetoday = date($date[0]).date($date[1]).date($date[2]);
		// 			$string = $get->prefix.$datetoday.$branches_initials;
		// 			$ctr = sprintf("%'.04d", Self::get_count_last_transaction($shop_id, $transaction_type, $get->separator, $string));
		// 			$return = $string.$get->separator.$ctr;
		// 		} 
		// 	}
		// }
		$return = ReferenceNumberFormat::GenerateReferenceNumber($shop_id, $transaction_type);
	
		return $return;
	}
	public static function get_count_last_transaction($shop_id, $transaction_type, $separator, $string = '')
	{
		$warehouse_id = Warehouse2::get_current_warehouse($shop_id);
		$settings = Self::settings($shop_id, "transaction_number");
		$return = 1;
		$get = null;
		if($transaction_type == 'accounting_transaction')
		{
			$get = Tbl_acctg_transaction::where('shop_id', $shop_id)->orderBy("acctg_transaction_id", "DESC")->first();
			if($get)
			{
				$get->transaction_refnum = $get->transaction_number;
			}
		}
		if($transaction_type == 'sales_invoice')
		{
			$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('is_sales_receipt',0)->orderBy('inv_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->acctg_trans("sales_invoice")->where('is_sales_receipt',0)->orderBy('inv_id','DESC')->where("transaction_warehouse_id", $warehouse_id)->first();
			}
		}
		if($transaction_type == 'sales_receipt')
		{
			$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('is_sales_receipt',1)->orderBy('inv_id','DESC')->first();

			if(is_numeric($settings))
			{
				$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->acctg_trans("sales_receipt")->where('is_sales_receipt',1)->orderBy('inv_id','DESC')->where("transaction_warehouse_id", $warehouse_id)->first();
			}
		}
		if($transaction_type == 'credit_memo')
		{
			$get = Tbl_credit_memo::where('cm_shop_id', $shop_id)->orderBy('cm_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_credit_memo::where('cm_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('cm_id','DESC')->first();
			}
		}
		if($transaction_type == 'estimate_quotation')
		{
			$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('is_sales_order',0)->orderBy('est_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get =Tbl_customer_estimate::where('est_shop_id', $shop_id)->acctg_trans("estimate_quotation")->where('is_sales_order',0)->where("transaction_warehouse_id", $warehouse_id)->orderBy('est_id','DESC')->first();
			}
		}
		if($transaction_type == 'sales_order')
		{
			$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('is_sales_order',1)->orderBy('est_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get =Tbl_customer_estimate::where('est_shop_id', $shop_id)->acctg_trans("sales_order")->where('is_sales_order',1)->where("transaction_warehouse_id", $warehouse_id)->orderBy('est_id','DESC')->first();
			}
		}
		if($transaction_type == 'warehouse_issuance_slip')
		{
			$get = Tbl_customer_wis::where('cust_wis_shop_id', $shop_id)->orderBy('cust_wis_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_customer_wis::where('cust_wis_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('cust_wis_id','DESC')->first();
			}
		}
		if($transaction_type == 'warehouse_transfer')
		{
			$get = Tbl_warehouse_issuance_report::where('wis_shop_id', $shop_id)->orderBy('wis_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get =  Tbl_warehouse_issuance_report::where('wis_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('wis_id','DESC')->first();
			}
			if($get)
			{
				$get->transaction_refnum = $get->wis_number;
			}
		}
		if($transaction_type == 'receiving_report')
		{
			$get = Tbl_warehouse_receiving_report::where('rr_shop_id', $shop_id)->orderBy('rr_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get =  Tbl_warehouse_receiving_report::where('rr_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('rr_id','DESC')->first();
			}
			if($get)
			{
				$get->transaction_refnum = $get->rr_number;
			}
		}
		if($transaction_type == 'purchase_requisition')
		{
			$get = Tbl_requisition_slip::where('tbl_requisition_slip.shop_id', $shop_id)->orderBy('requisition_slip_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_requisition_slip::where('tbl_requisition_slip.shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('requisition_slip_id','DESC')->first();
			}
		}
		if($transaction_type == 'purchase_order')
		{
			$get = Tbl_purchase_order::where('po_shop_id', $shop_id)->orderBy('po_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_purchase_order::where('po_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('po_id','DESC')->first();
			}
		}
		if($transaction_type == 'received_inventory')
		{
			$get = Tbl_receive_inventory::where('ri_shop_id', $shop_id)->orderBy('ri_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_receive_inventory::where('ri_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('ri_id','DESC')->first();
			}
		}
		if($transaction_type == 'enter_bills')
		{
			$get = Tbl_bill::where('bill_shop_id', $shop_id)->orderBy('bill_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_bill::where('bill_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('bill_id','DESC')->first();
			}
		}
		if($transaction_type == 'pay_bill')
		{
			$get = Tbl_pay_bill::where('paybill_shop_id', $shop_id)->orderBy('paybill_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_pay_bill::where('paybill_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('paybill_id','DESC')->first();
			}
		}
		if($transaction_type == 'write_check')
		{
			$get = Tbl_write_check::where('wc_shop_id', $shop_id)->orderBy('wc_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_write_check::where('wc_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('wc_id','DESC')->first();
			}
		}
		if($transaction_type == 'debit_memo')
		{
			$get = Tbl_debit_memo::where('db_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('db_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_debit_memo::where('db_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('db_id','DESC')->first();
			}
		}
		if($transaction_type == 'inventory_adjustment')
		{
			$get = Tbl_inventory_adjustment::where('adj_shop_id', $shop_id)->orderBy('inventory_adjustment_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_inventory_adjustment::where('adj_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('inventory_adjustment_id','DESC')->first();
			}
		}
		if($transaction_type == 'received_payment')
		{
			$get = Tbl_receive_payment::where('rp_shop_id', $shop_id)->orderBy('rp_id','DESC')->first();
			if(is_numeric($settings))
			{
				$get = Tbl_receive_payment::where('rp_shop_id', $shop_id)->acctg_trans()->where("transaction_warehouse_id", $warehouse_id)->orderBy('rp_id','DESC')->first();
			}
		}

		if($get)
		{
			$perday = Self::settings($shop_id, "per_day_reset");
			$number = explode("$separator", $get->transaction_refnum);
			if(isset($number[1]))
			{
				if($perday)
				{
					if($number[0] == $string)
					{
						$return = (int)$number[1] + 1;
					}
				}
				else
				{
					$return = (int)$number[1] + 1;
				}
			}
		}
		return $return;
	}
	public static function refill_inventory($shop_id, $warehouse_id , $item_info, $ref_name = '', $ref_id = 0, $remarks = '')
	{
		$return = null;

		if(count($item_info) > 0)
		{
			$_item = null;
			foreach ($item_info as $key => $value) 
			{
				$item_type = Item::get_item_type($value['item_id']);
				if($item_type == 1)
				{
					if($value['item_um'] != '')
					{
						$qty = $value['item_qty'] * UnitMeasurement::get_umqty($value['item_um']);
					}
					else
					{
						$qty = $value['item_qty'];
					}
					$_item[$key]['item_um'] = $value['item_um'];
					$_item[$key]['item_id'] = $value['item_id'];
			        $_item[$key]['quantity'] = $qty;
			        $_item[$key]['remarks'] = $value['item_description'];
			        if($ref_name != 'adjust_inventory')
			        {
			        	//$_item[$key]['quantity'] = $qty * -1;
			        	$_item[$key]['item_rate'] = $value['item_rate'];
			        	$_item[$key]['bin_location'] = $value['bin_location'];
			        }
				}
				elseif($item_type == 5 || $item_type == 4)
				{
					$bundle_list = Item::get_bundle_list($value['item_id']);
					if(count($bundle_list) > 0)
					{
						foreach ($bundle_list as $key_bundle => $value_bundle) 
						{
							$qty = $value['item_qty'] * ($value_bundle->bundle_qty * UnitMeasurement::get_umqty($value_bundle->bundle_um_id));
							$_item[$key.'b'.$key_bundle]['item_id'] = $value_bundle->bundle_item_id;
							$_item[$key.'b'.$key_bundle]['quantity'] = $qty;
							$_item[$key.'b'.$key_bundle]['remarks'] = $value_bundle->item_sales_information;
							if($ref_name != 'adjust_inventory')
			        		{
			        			//$_item[$key.'b'.$key_bundle]['quantity'] = $qty * -1;
			        			$_item[$key.'b'.$key_bundle]['item_rate'] = $value['item_rate'];
			        			$_item[$key.'b'.$key_bundle]['bin_location'] = $value['bin_location'];
			        		}
						}
					}
				}
			}
			if(count($_item) > 0)
			{
				$return = Warehouse2::refill_bulk($shop_id, $warehouse_id, $ref_name, $ref_id, $remarks, $_item);

				foreach ($item_info as $key => $value) 
				{
					$item_type = Item::get_item_type($value['item_id']);
					if($item_type == 5 || $item_type == 4)
					{
						if($ref_name != 'adjust_inventory')
			        	{
							Warehouse2::refill_bundling_item($shop_id, $warehouse_id, $value['item_id'], $value['item_rate'], $value['item_qty'], $ref_name, $ref_id, $value['bin_location']);
						}
						else
						{
							Warehouse2::refill_bundling_item($shop_id, $warehouse_id, $value['item_id'], $value['item_rate'], $value['item_qty'], $ref_name, $ref_id, null);
						}
					}
				}
			}
		}
		return $return;
	}
	public static function inventory_refill_update($shop_id, $warehouse_id,  $item_info, $ref_name, $ref_id)
	{
		Warehouse2::inventory_delete_inventory_refill($shop_id, $warehouse_id, $ref_name, $ref_id, $item_info);
	}

	public static function inventory_consume_update($shop_id, $warehouse_id, $ref_name, $ref_id)
	{
		Warehouse2::update_inventory_consume($shop_id, $warehouse_id, $ref_name, $ref_id);
	}
	public static function inventory_validation($type = 'refill', $shop_id, $warehouse_id, $item_info, $remarks = '', $refname = null, $refid = null)
	{
		$return = null;
		$_item = null;
		$activate = false;
		if(count($item_info) > 0)
		{
			foreach ($item_info as $key => $value) 
			{
				$item_type = Item::get_item_type($value['item_id']);
				if($item_type == 1 || $item_type == 5 || $item_type == 4)
				{
					$um = $value['item_um'] != '' ? $value['item_um'] : 0 ;
					$qty = $value['item_qty'];
					if($um != 0)
					{
						$qty = $value['item_qty'] * UnitMeasurement::get_umqty($um);
					}
					$_item[$key]['item_id'] = $value['item_id'];
			        $_item[$key]['quantity'] = $qty;
			        $_item[$key]['remarks'] = $value['item_description'];
			        $_item[$key]['item_sub_warehouse'] = isset($value['item_sub_warehouse']) ? $value['item_sub_warehouse'] : null;
				}
				else if($item_type == 2 || $item_type == 3 || $item_type == 6)
				{
					$activate = true;
				}

			}
		}
		if(count($_item) > 0)
		{
			foreach ($_item as $key => $value) 
			{
				if($type == 'refill')
				{
					$return .= Warehouse2::refill_validation($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks']);
				}
				if($type == 'consume')
				{
					$return .= Warehouse2::consume_validation($shop_id, $warehouse_id, $value['item_id'], $value['quantity'], $value['remarks'],null,null, $value['item_sub_warehouse'], $refname, $refid);
				}
			}
		}
		else
		{
			if($activate == false)
			{
				$return = "Please select item";
			}
		}

		return $return;
	}
	public static function consume_inventory($shop_id, $warehouse_id , $item_info, $ref_name = '', $ref_id = 0, $remarks = '')
	{
		$return = null;
		if(count($item_info) > 0)
		{
			$_item = null;

			foreach ($item_info as $key => $value) 
			{

				if($value)
				{
					$item_type = Item::get_item_type($value['item_id']);
					$qty = (abs($value['item_qty']) * UnitMeasurement::get_umqty($value['item_um']));
					$_item[$key]['item_rate'] = isset($value['item_rate']) ? $value['item_rate'] : 0;
					if($ref_name == 'adjust_inventory')
					{
						$_item[$key]['item_rate'] = Tbl_item::where('item_id', $value['item_id'])->value('item_cost');
					}

					$_item[$key]['item_id'] = $value['item_id'];
			        $_item[$key]['quantity'] = $qty;
			        $_item[$key]['remarks'] = $value['item_description'];
			        $_item[$key]['bin_location'] = $value['item_sub_warehouse'];
			  
					if($item_type == 5 || $item_type == 4)
					{
						$bundle_list = Item::get_bundle_list($value['item_id']);
						if(count($bundle_list) > 0)
						{
							foreach ($bundle_list as $key_bundle => $value_bundle) 
							{
								$qty = $value['item_qty'] * ($value_bundle->bundle_qty * UnitMeasurement::get_umqty($value_bundle->bundle_um_id));
								$_item[$key.'b'.$key_bundle]['item_id'] = $value_bundle->bundle_item_id;
								
								$_item[$key.'b'.$key_bundle]['remarks'] = $value_bundle->item_sales_information;
				        		
								if($ref_name != 'adjust_inventory')
				        		{
				        			$_item[$key.'b'.$key_bundle]['quantity'] = $qty * -1;
				        			$_item[$key.'b'.$key_bundle]['item_rate'] = $value['item_rate'];
				        			$_item[$key.'b'.$key_bundle]['bin_location'] = $value['bin_location'];
				        		}
				        		else
				        		{
				        			$_item[$key.'b'.$key_bundle]['quantity'] = $qty;
				        			$_item[$key.'b'.$key_bundle]['item_rate'] = Tbl_item::where('item_id', $value_bundle->bundle_item_id)->value('item_cost');
				        		}
							}
						}
					}
				}
			}

			if(count($_item) > 0)
			{
				$return = Warehouse2::consume_bulk($shop_id, $warehouse_id, $ref_name, $ref_id, $remarks, $_item);
			}
		}
		return $return;
	}
	public static function get_refuser($user_info)
	{
		$date = date("F j, Y, g:i a");
		$return = $date;
		if($user_info)
		{
	        $first_name         = $user_info->user_first_name;
	        $last_name         = $user_info->user_last_name;
	        $return  = 'Printed by: '.$first_name.' '.$last_name.'           '.$date.'           ';
		}

		return $return;
	}
	public static function get_signatories($shop_id, $transaction = '')
	{
		$data = Tbl_settings::where("shop_id", $shop_id)->where("settings_setup_done",3)->where("settings_transaction", $transaction)->get();


		return $data;
	}

	public static function check_proposal_exist($shop_id, $_proposal, $customer_id = null)
	{
		$return = null;
		if(count($_proposal) > 0)
		{
			foreach ($_proposal as $key => $value) 
			{
				$get = Tbl_customer::where("shop_id", $shop_id);
				if($customer_id)
				{
					$get = $get->where("customer_id","!=", $customer_id);
				}

				$get = $get->get();
				foreach ($get as $keyc => $valuec) 
				{
					$array = unserialize($valuec->customer_proposals) == false ? null : unserialize($valuec->customer_proposals);
					$dupe_array = $array;
					if(count($array) > 0)
					{
						foreach ($array as $keys => $val) 
						{
							if($val == $value)
							{
					        	$return .= "Proposal number ". $val ." already exist <br>";
							}
					    }
					}
				}
			}
		}
		return $return;
	}
	public static function get_proposal_number($shop_id, $transaction_name, $transaction_id, $item_id)
	{
		$return = null;
		$acctg_transaction_id = Tbl_acctg_transaction_list::where("transaction_ref_name", $transaction_name)
														    ->where("transaction_ref_id", $transaction_id)
															->value("acctg_transaction_id");

		$_inv = Tbl_acctg_transaction_list::where("transaction_ref_name", "sales_invoice")
												  ->where("acctg_transaction_id", $acctg_transaction_id)
												  ->get();
		foreach ($_inv as $key_inv => $value_inv)
		{
			$get_invoice =  Tbl_acctg_transaction_list::where("transaction_ref_name", "sales_invoice")
													  ->where("transaction_ref_id", $value_inv->transaction_ref_id)
													  ->orderBy("acctg_transaction_list_id","ASC")
													  ->first();
			if($get_invoice)
			{
				$_est = Tbl_acctg_transaction_list::where("transaction_ref_name", "estimate_qoutation")
												    ->where("acctg_transaction_id", $get_invoice->acctg_transaction_id)
													->get();
				foreach ($_est as $key_est => $value_est) 
				{
					$check = Tbl_customer_estimate_line::where("invline_inv_id", $value_est->transaction_ref_id)->where("estline_item_id", $item_id)->value("estline_proposal_number");
					if($check)
					{
						$return = $check;
					}
				}
			}
		}
		return $return;
	}

	public static function reset_transaction($shop_id, $_for_reset = array())
	{
		$return = null;
		/* ITEM PRE-REQ TO DELETE 
			ACCTG TRANS - INVENTORY
		*/
		foreach ($_for_reset as $key => $value) 
		{
			if($value == 'category')
			{
				/* INVENTORY & OTHER LOG FOR ITEM */
				Warehouse2::delete_inventory($shop_id);
				/* ACCTG TRANS */
				Self::delete_transaction($shop_id);
				/* JOURNAL ENTRY */
				Self::delete_journal_entry($shop_id);
				/* ITEMS */
				Item::delete_items($shop_id); 
				/* Category */
				Item::delete_category($shop_id);
			}
			if($value == 'initial_inventory')
			{
				Warehouse2::delete_initial_inventory($shop_id);
			}
			if($value == 'items')
			{
				/* INVENTORY & OTHER LOG FOR ITEM */
				Warehouse2::delete_inventory($shop_id);
				/* ACCTG TRANS */
				Self::delete_transaction($shop_id);
				/* JOURNAL ENTRY */
				Self::delete_journal_entry($shop_id);
				/* ITEMS */
				Item::delete_items($shop_id); 
			}
			if($value == 'um')
			{
				Item::delete_um($shop_id);
			}
			if($value == 'transaction')
			{
				/* INVENTORY & OTHER LOG FOR ITEM */
				Warehouse2::delete_inventory($shop_id);
				/* ACCTG TRANS */
				Self::delete_transaction($shop_id);
				/* JOURNAL ENTRY */
				Self::delete_journal_entry($shop_id);
				/* AUDIT TRAIL */
				AuditTrail::delete_audit_trail($shop_id);
			}
			if($value == 'inventory')
			{
				Warehouse2::delete_inventory($shop_id);
			}
			if($value == 'customer')
			{
				Customer::delete_customer($shop_id);
				Self::delete_transaction($shop_id);
				Warehouse2::delete_inventory($shop_id);
				AuditTrail::delete_audit_trail($shop_id);
				Self::delete_journal_entry($shop_id);
			}
			if($value == 'warehouse')
			{
				Warehouse2::delete_inventory($shop_id);
				Self::delete_transaction($shop_id);
				Warehouse2::delete_warehouse($shop_id);
			}
			if($value == 'vendor')
			{
				Vendor::delete_vendor($shop_id);
				Self::delete_transaction($shop_id);
				Warehouse2::delete_inventory($shop_id);
				AuditTrail::delete_audit_trail($shop_id);
				Self::delete_journal_entry($shop_id);
			}
			if($value == 'journal_entry')
			{
				/* INVENTORY & OTHER LOG FOR ITEM */
				Warehouse2::delete_inventory($shop_id);
				/* ACCTG TRANS */
				Self::delete_transaction($shop_id);
				/* JOURNAL ENTRY */
				Self::delete_journal_entry($shop_id);
				/* AUDIT TRAIL */
				AuditTrail::delete_audit_trail($shop_id);
			}
			if($value == 'coa')
			{
				Self::delete_coa($shop_id);
			}
		}
		return $return;
	}
	public static function delete_coa($shop_id)
	{
		Tbl_chart_of_account::where("account_shop_id", $shop_id)->delete();
	}
	public static function delete_transaction($shop_id)
	{
		Tbl_acctg_transaction::where("shop_id", $shop_id)->delete();
		Tbl_customer_invoice::where("inv_shop_id", $shop_id)->delete();
		Tbl_credit_memo::where("cm_shop_id", $shop_id)->delete();
		Tbl_customer_estimate::where("est_shop_id", $shop_id)->delete();
		Tbl_customer_wis::where("cust_wis_shop_id", $shop_id)->delete();
		Tbl_warehouse_issuance_report::where("wis_shop_id", $shop_id)->delete();
		Tbl_warehouse_receiving_report::where("rr_shop_id", $shop_id)->delete();
		Tbl_requisition_slip::where("shop_id", $shop_id)->delete();
		Tbl_purchase_order::where("po_shop_id", $shop_id)->delete();
		Tbl_receive_inventory::where("ri_shop_id", $shop_id)->delete();
		Tbl_bill::where("bill_shop_id", $shop_id)->delete();
		Tbl_pay_bill::where("paybill_shop_id", $shop_id)->delete();
		Tbl_write_check::where("wc_shop_id", $shop_id)->delete();
		Tbl_debit_memo::where("db_shop_id", $shop_id)->delete();
		Tbl_inventory_adjustment::where("adj_shop_id", $shop_id)->delete();
		Tbl_receive_payment::where("rp_shop_id", $shop_id)->delete();
	}
	public static function delete_journal_entry($shop_id)
	{
		Tbl_journal_entry::where("je_shop_id", $shop_id)->delete();
	}
	public static function sales_bank_interest($shop_id, $date_from = '', $date_to = '')
	{
		$_inv = Tbl_customer_invoice::customer()->where("inv_shop_id", $shop_id)->where("bank_interest", "!=","")->whereBetween("inv_date",[$date_from, $date_to])->get();

		$return = null;
		foreach ($_inv as $key => $value) 
		{
			$return[$key] = $value;
			$explode = explode("-",$value->bank_interest);
			if(isset($explode[4]))
			{
				$return[$key]->bank_interest = $explode[0];
				$return[$key]->bank_amount = $explode[1];
				$return[$key]->bank_name = $explode[2];
				$return[$key]->bank_months = $explode[3];
				$return[$key]->bank_remarks = $explode[4];

				$interest = (int) filter_var($explode[0], FILTER_SANITIZE_NUMBER_INT);
				$amount = (double) filter_var($explode[1], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				$return[$key]->bank_interest_amount = $amount * ($interest / 100);
			}
		}

		return $return;
	}
	public static function get_sales_gain_item($shop_id, $date_from = '', $date_to = '')
	{
		$get_item = Tbl_item::where("shop_id", $shop_id)->where("archived",0)->get();
		$return = null;
		foreach ($get_item as $key => $value) 
		{
			$_inv = Tbl_customer_invoice::invoice_item()->where("is_sales_receipt",0)->where("inv_shop_id", $shop_id)->where("invline_item_id", $value->item_id)->whereBetween("inv_date",[$date_from, $date_to])->get();
			$inv_id = 0;
			$qty = 0;
			$discount = 0;
			foreach ($_inv as $key_inv => $value_inv)
			{
				$inv_id += $value_inv->inv_id;
				$qty += UnitMeasurement::get_umqty($value_inv->invline_um) * $value_inv->invline_orig_qty;

				$compute_disc       = $value_inv->invline_discount != "" ? $value_inv->invline_discount : 0;
		        if($value_inv->invline_discount_type == "percent")
	            {
	            	$compute_disc       = substr($compute_disc, 0, strpos($compute_disc, '%')) / 100;
	            }
	            $discount += $compute_disc;
			}
			if($inv_id != 0 && $qty != 0)
			{
				$return['si'.$inv_id] = new stdClass;
				$return['si'.$inv_id]->item_id = $value->item_id;
				$return['si'.$inv_id]->item_name = $value->item_name;
				$return['si'.$inv_id]->reference_module = "Sales Invoice";
				$return['si'.$inv_id]->qty = $qty;
				$return['si'.$inv_id]->sales_price = $value->item_price;
				$return['si'.$inv_id]->cost_price = $value->item_cost;
				$return['si'.$inv_id]->sales_amount = $return['si'.$inv_id]->sales_price * $return['si'.$inv_id]->qty;
				$return['si'.$inv_id]->cost_amount = $return['si'.$inv_id]->cost_price * $return['si'.$inv_id]->qty;
				$return['si'.$inv_id]->discount_given = $discount;
				$return['si'.$inv_id]->gain = (($return['si'.$inv_id]->sales_amount - $return['si'.$inv_id]->cost_amount) - $return['si'.$inv_id]->discount_given);
			}

			$_sr = Tbl_customer_invoice::invoice_item()->where("is_sales_receipt",1)->where("inv_shop_id", $shop_id)->where("invline_item_id", $value->item_id)->whereBetween("inv_date",[$date_from, $date_to])->get();
			$sr_id = 0;
			$qty = 0;
			$discount = 0;
			foreach ($_sr as $key_sr => $value_sr)
			{
				$sr_id += $value_sr->inv_id;
				$qty += UnitMeasurement::get_umqty($value_sr->invline_um) * $value_sr->invline_orig_qty;

				$compute_disc       = $value_inv->invline_discount != "" ? $value_inv->invline_discount : 0;
		        if($value_sr->invline_discount_type == "percent")
	            {
	            	$compute_disc       = substr($compute_disc, 0, strpos($compute_disc, '%')) / 100;
	            }
	            $discount += $compute_disc;
			}
			if($sr_id != 0 && $qty != 0)
			{
				$return['sr'.$sr_id] = new stdClass;
				$return['sr'.$sr_id]->item_id = $value->item_id;
				$return['sr'.$sr_id]->item_name = $value->item_name;
				$return['sr'.$sr_id]->reference_module = "Sales Receipt";
				$return['sr'.$sr_id]->qty = $qty;
				$return['sr'.$sr_id]->sales_price = $value->item_price;
				$return['sr'.$sr_id]->cost_price = $value->item_cost;
				$return['sr'.$sr_id]->sales_amount = $return['sr'.$sr_id]->sales_price * $return['sr'.$sr_id]->qty;
				$return['sr'.$sr_id]->cost_amount = $return['sr'.$sr_id]->cost_price * $return['sr'.$sr_id]->qty;
				$return['sr'.$sr_id]->discount_given = $discount;
				$return['sr'.$sr_id]->gain = (($return['sr'.$sr_id]->sales_amount - $return['sr'.$sr_id]->cost_amount) - $return['sr'.$sr_id]->discount_given);
			}

		}
		return $return;
	}
	public static function check_update_sales_price($shop_id, $user_id, $item_id, $new_sales_price)
	{
		$check = Self::settings($shop_id, "auto_change_sales_price");
		if($check)
		{
			$item = Tbl_item::where("shop_id", $shop_id)->where("item_id", $item_id)->first();
			if($item)
			{
				if($item->item_price != $new_sales_price)
				{
					$update['item_price'] = $new_sales_price;
					Item::update_item_price($shop_id, $user_id, $item_id, $update);
				}
			}
		}
	}
	public static function inprogress_transaction($shop_id, $warehouse_id, $trans_name, $trans_id = 0, $transref_num)
	{
		
	}
	public static function get_range_discount($shop_id, $item_id)
	{
		$_get = Tbl_item_range_sales_discount::where("range_shop_id", $shop_id)->where("range_item_id", $item_id)->get();

		$return = null;
		foreach ($_get as $key => $value) 
		{
			$return .= $value->range_qty."-".$value->range_new_price_per_piece.",";
		}

		return $return;
	}
	public static function check_if_exist($args = array())
	{
		$exist = false;
		$return = null;
		$ret = array();
		foreach ($args as $key1 => $value1) 
		{
			$item_type = Item::get_item_type($value1);
			$item_name = Item::info($value1);
			if($item_type == 1 || $item_type == 5)
			{
				if($value1 && $item_name)
				{
					$exist = false;
					foreach ($args as $key2 => $value2) 
					{
						if($value1 == $value2 && $key1 != $key2 && $value2)
						{
							$exist = true;
						}
					}
					if($exist == true)
					{
						$ret[$value1] = "Please check duplication of this item <strong>".Warehouse2::string_replace_for_url($item_name->item_name)."</strong><br>";
					}
				}
			}
		}
		$msg = '';
		foreach ($ret as $key => $value) 
		{
			$msg .= $value;
		}

		$return['has_duplicate'] = count($ret) > 0 ? true : false;
		$return['status'] = count($ret) > 0 ? 'error' : '';
		$return['message'] = count($ret) > 0 ? $msg : '';
		
		return $return;
	}
}