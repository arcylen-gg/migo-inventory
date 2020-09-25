<?php
namespace App\Globals;

use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_estimate_line;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_customer_invoice_pm;
use Carbon\Carbon;
use DB;
use App\Globals\AccountingTransaction;
use Session;
use App\Models\Tbl_quantity_monitoring;
/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionSalesReceipt
{
	// public static function transactionStatus($shop_id)
 //    {
 //        $get = Tbl_customer_invoice::whereNull('transaction_status')->where("inv_shop_id", $shop_id)->where("is_sales_receipt", 1)->get();
 //        foreach ($get as $key => $value) 
 //        {
 //            $update_status['transaction_status'] = 'posted';
 //            Tbl_customer_invoice::where("inv_shop_id", $shop_id)->where("inv_id", $value->inv_id)->where("is_sales_receipt", 1)->update($update_status);
 //        }
 //    }
	public static function get_total_amount($shop_id, $tab)
    {
    	$check_allow_transaction = AccountingTransaction::settings($shop_id, 'allow_transaction');
    	$sr = Tbl_customer_invoice::where("inv_shop_id",$shop_id)->where("is_sales_receipt", 1);

        $total = 0;
        if($tab == 'undelivered')
        {
            $sr = $sr->where('item_delivered', 0)->get();
        }
        elseif($tab == 'delivered')
        {
            $sr = $sr->where('item_delivered', 1)->get();
        }
        else
        {
            $sr = $sr->get();
            
        }
        if(count($sr) > 0)
        {
            foreach ($sr as $key => $value) 
            {
               $total += $value->inv_overall_price;

            }            
        }
        if($check_allow_transaction == 1)
        {
            $sr = Tbl_customer_invoice::where("inv_shop_id",$shop_id)->where("is_sales_receipt", 1);
            $sr = AccountingTransaction::acctg_trans($shop_id, $sr, 'sales_receipt');

            $data = null;
	        $total = null;
	        if($tab == 'undelivered')
	        {
	            $sr = $sr->where('item_delivered', 0)->get();
	        }
	        elseif($tab == 'delivered')
	        {
	            $sr = $sr->where('item_delivered', 1)->get();
	        }
	        else
	        {
	            $sr = $sr->get();
	        }
	        if(count($sr) > 0)
	        {
	        	foreach ($sr as $key_sr => $value_sr)
	            {
	                $data[$value_sr->inv_id] = $value_sr->inv_overall_price;
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
            $qty = UnitMeasurement::um_qty($value->invline_um);

            $total_qty = $value->invline_orig_qty * $qty;
            $transactionline[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->invline_um);
        }
        return $transactionline;
    }
    public static function subtotal($shop_id, $transactionline)
    {
        $subtotal = 0;
        foreach ($transactionline as $key => $value)
        {
            $subtotal += $value->invline_amount;
        }
        return $subtotal;
    }
    public static function infotax($shop_id, $transactionline)
    {
        $data = Self::infoline($shop_id, $transactionline);

        $total_tax =  0;
        foreach ($data as $key => $value)
        {
            if($value->taxable == 1)
            {
                $total_tax += $value->invline_amount * 0.12;
            }
        }
        return $total_tax;
    }
	public static function countTransaction($shop_id, $customer_id)
	{
		return Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("est_customer_id",$customer_id)->where("est_status","accepted")->count();
	}
	public static function countUndeliveredSalesReceipt($shop_id, $customer_id)
	{
		return Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('inv_customer_id', $customer_id)->where('is_sales_receipt',1)->where('item_delivered',0)->count();
	}
	public static function getUndeliveredSalesReceipt($shop_id, $customer_id)
	{
		return Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('inv_customer_id', $customer_id)->where('is_sales_receipt',1)->where('item_delivered',0)->get();
	}
	public static function countDeliveredSalesReceipt($shop_id)
	{
		return Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('is_sales_receipt',1)->where('item_delivered',1)->where('replenished',0)->count();
	}
	public static function getDeliveredSalesReceipt($shop_id)
	{
		return Tbl_customer_invoice::customer()->where('inv_shop_id', $shop_id)->where('is_sales_receipt',1)->where('item_delivered',1)->where('replenished',0)->get();
	}	
	public static function transaction_data($shop_id, $trans_id)
	{
		return Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("est_id",$trans_id)->first();
	}
	public static function transaction_data_item($trans_id)
	{
		return Tbl_customer_estimate_line::um()->where("estline_est_id",$trans_id)->get();		
	}
	/*public static function get($shop_id, $paginate = null, $search_keyword = null, $item_delivered = null)
=======
	public static function get($shop_id, $paginate = null, $search_keyword = null)
>>>>>>> client_inventory
	{
		$data = Tbl_customer_invoice::customer()->where('inv_shop_id', $shop_id)->where('inv_is_paid',1)->where('is_sales_receipt',1)->groupBy("inv_id");

		if($item_delivered)
		{
			if($item_delivered == 'fordelivery')
			{
				$data = $data->where("item_delivered",0);
			}
			elseif($item_delivered == 'delivered')
			{
				$data = $data->where("item_delivered",1);				
			}
		}
		else
		{
			$data = $data->where("item_delivered",0);
		}
        $data = AccountingTransaction::acctg_trans($shop_id, $data, 'sales_receipt');
		if($search_keyword)
		{
			$data->where(function($q) use ($search_keyword)
            {
                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("new_inv_id", "LIKE", "%$search_keyword%");
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
	}*/
	public static function get($shop_id, $paginate = null, $search_keyword = null, $status = null)
	{
		$data = Tbl_customer_invoice::customer()->where('inv_shop_id', $shop_id)->where('is_sales_receipt',1)->groupBy("inv_id")->orderBy("inv_date","desc");

        $data = AccountingTransaction::acctg_trans($shop_id, $data, 'sales_receipt');

		if($search_keyword)
		{
			$data->where(function($q) use ($search_keyword)
            {
                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("new_inv_id", "LIKE", "%$search_keyword%");
                $q->orWhere("company", "LIKE", "%$search_keyword%");
                $q->orWhere("first_name", "LIKE", "%$search_keyword%");
                $q->orWhere("middle_name", "LIKE", "%$search_keyword%");
                $q->orWhere("last_name", "LIKE", "%$search_keyword%");
            });
		}

		if($status != 'all')
		{
			$tab = 0;
			if($status == 'undelivered')
			{
				$tab = 0;
			}
			if($status == 'delivered')
			{
				$tab = 1;
			}
			$data->where('item_delivered',$tab);
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
	public static function info($shop_id, $sales_receipt_id)
	{
		return Tbl_customer_invoice::paymentmethod()->customer()->where("inv_shop_id", $shop_id)->where("inv_id", $sales_receipt_id)->first();
	}
	public static function info_item($sales_receipt_id)
	{
		$data = Tbl_customer_invoice_line::invoice_item()->um()->where("invline_inv_id", $sales_receipt_id)->get();
		foreach($data as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->invline_um);

            $total_qty = $value->invline_qty * $qty;
            $data[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->invline_um);
        }
        return $data;
	}

	public static function postUpdate($sales_receipt_id, $user_info, $insert, $insert_item = array())
	{
		$val = AccountingTransaction::customer_validation($insert, $insert_item);
        $check_if_salesrep = AccountingTransaction::settings($user_info->shop_id,'sales_representative');
        if($check_if_salesrep)
        {
            if(!$insert['inv_sales_rep_id'])
            {
                $val .= '<li style="list-style:none">Sales representative is required</li>';
            }
        }
		if(!$val)
		{
			$return  = null; 
			$ins['inv_shop_id']                  = $user_info->shop_id;  
			$ins['inv_customer_id']              = $insert['customer_id'];  
			$ins['transaction_refnum']	 		 = $insert['transaction_refnum'];   
	        $ins['inv_customer_email']           = $insert['customer_email'];
	        $ins['inv_customer_billing_address'] = $insert['customer_address'];
	        $ins['inv_date']                     = date("Y-m-d", strtotime($insert['transaction_date']));
	        $ins['ewt']                          = $insert['customer_ewt'];
	        $ins['inv_discount_type']            = $insert['customer_discounttype'];
	        $ins['inv_discount_value']           = $insert['customer_discount'];
	        $ins['taxable']                      = $insert['customer_tax'] != '' ? $insert['customer_tax'] : 0;
	        $ins['inv_message']                  = $insert['customer_message'];
	        $ins['inv_memo']                     = $insert['customer_memo'];
	        $ins['date_created']                 = Carbon::now();
	        $ins['bank_interest']				 = $insert['customer_bank_interest'] != '' ? $insert['customer_bank_interest'] : null;
	        $ins['inv_payment_method'] 	 		 = $insert['transaction_payment_method'];	
			$ins['inv_cheque_ref_no']    		 = $insert['transaction_ref_no'];
			$ins['inv_sales_rep_id']  			 = $insert['inv_sales_rep_id'];

	       	/* SUBTOTAL */
	        $subtotal_price = collect($insert_item)->sum('item_amount');
	       
	        /* 	EWT */
	        $ewt = $subtotal_price * convertToNumber($insert['customer_ewt']);

	        /* TAX */
	        $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;

	        /* DISCOUNT */
	        $discount = $insert['customer_discount'];
	        if($insert['customer_discounttype'] == 'percent') $discount = (($subtotal_price + $tax) - $ewt) * ((convertToNumber($insert['customer_discount']) / 100));

	        /* OVERALL TOTAL */
	        $overall_price  = convertToNumber($subtotal_price) - $ewt - $discount + $tax;

	        $ins['inv_subtotal_price']           = $subtotal_price;
	        $ins['inv_overall_price']            = $overall_price;

	        $ins['is_sales_receipt']             = 1;
            $ins['inv_payment_applied']		     = $ins['inv_overall_price'];
            $ins['inv_is_paid']                  = 1;


	        /* INSERT INVOICE HERE */
	        Tbl_customer_invoice::where('inv_id', $sales_receipt_id)->update($ins);
	        // $invoice_id = 0;

	       /* Transaction Journal */
	        $entry["reference_module"]  = 'sales-receipt';
	        $entry["reference_id"]      = $sales_receipt_id;
	        $entry["name_id"]           = $insert['customer_id'];
            $entry["txn_date"]          = $ins['inv_date'];
	        $entry["total"]             = $subtotal_price;
	        $entry["vatable"]           = $tax;
	        $entry["discount"]          = $discount;
	        $entry["ewt"]               = $ewt;

			Tbl_customer_invoice_line::where("invline_inv_id", $sales_receipt_id)->delete();
	        $return = Self::insertline($sales_receipt_id, $insert_item, $entry, $user_info, true);
	        $return = $sales_receipt_id;

	        if(CustomerWIS::settings($user_info->shop_id) == 0)
			{
		        /* UPDATE INVENTORY HERE */
		        $warehouse_id = Warehouse2::get_current_warehouse($user_info->shop_id);
				AccountingTransaction::inventory_consume_update($user_info->shop_id, $warehouse_id, 'sales_receipt', $sales_receipt_id); 
				AccountingTransaction::consume_inventory($user_info->shop_id, $warehouse_id, $insert_item, 'sales_receipt', $sales_receipt_id, 'Consume upon creating SALES RECEIPT '.$ins['transaction_refnum']);
			}
		}
		else
		{
			$return = $val;
		}		

        return $return; 
	}
	
	public static function postInsert($user_info, $insert, $insert_item = array())
	{
		$insert['function_do'] = 'insert'; //what function will do
		$insert['tbl_name'] = 'tbl_customer_invoice'; //table affected
		$insert['column_for_reference_number'] = 'inv_cheque_ref_no'; //column of table affected
		$val = AccountingTransaction::customer_validation($insert, $insert_item, 'sales_receipt');
        $check_if_salesrep = AccountingTransaction::settings($user_info->shop_id,'sales_representative');
        if($check_if_salesrep)
        {
            if(!$insert['inv_sales_rep_id'])
            {
                $val .= '<li style="list-style:none">Sales representative is required</li>';
            }
        }
		if(!$val)
		{
			$return  = null; 
			$ins['inv_shop_id']                  = $user_info->shop_id;  
			$ins['inv_customer_id']              = $insert['customer_id'];  
			$ins['transaction_refnum']	 		 = $insert['transaction_refnum'];   
	        $ins['inv_customer_email']           = $insert['customer_email'];
	        $ins['inv_customer_billing_address'] = $insert['customer_address'];
	        $ins['inv_date']                     = date("Y-m-d", strtotime($insert['transaction_date']));
	        $ins['ewt']                          = $insert['customer_ewt'];
	        $ins['inv_discount_type']            = $insert['customer_discounttype'];
	        $ins['inv_discount_value']           = $insert['customer_discount'];
	        $ins['taxable']                      = $insert['customer_tax'] != '' ? $insert['customer_tax'] : 0;
	        $ins['inv_message']                  = $insert['customer_message'];
	        $ins['inv_memo']                     = $insert['customer_memo'];
	        $ins['date_created']                 = Carbon::now();
	        $ins['bank_interest']				 = $insert['customer_bank_interest'] != '' ? $insert['customer_bank_interest'] : null;
	        $ins['inv_payment_method'] 	 		 = $insert['transaction_payment_method'];	
			$ins['inv_cheque_ref_no']    		 = $insert['transaction_ref_no'];
			$ins['inv_sales_rep_id']  			 = $insert['inv_sales_rep_id'];
	        //$ins['transaction_status'] 			 = 'pending';
	        $ins['item_delivered'] 				 = 0;

	        if(CustomerWIS::settings($user_info->shop_id) == 0)
			{
				$ins['item_delivered'] = 1;
			}

			/* SUBTOTAL */
	        $subtotal_price = collect($insert_item)->sum('item_amount');
	       
	        /* 	EWT */
	        $ewt = $subtotal_price * convertToNumber($insert['customer_ewt']);

	        /* TAX */
	        $tax = (collect($insert_item)->where('item_taxable', '1')->sum('item_amount')) * 0.12;

	        /* DISCOUNT */
	        $discount = $insert['customer_discount'];
	        if($insert['customer_discounttype'] == 'percent') $discount = (($subtotal_price + $tax) - $ewt) * ((convertToNumber($insert['customer_discount']) / 100));

	        /* OVERALL TOTAL */
	        $overall_price  = convertToNumber($subtotal_price) - $ewt - $discount + $tax;

	        $ins['inv_subtotal_price']           = $subtotal_price;
	        $ins['inv_overall_price']            = $overall_price;

	        $ins['is_sales_receipt']             = 1;
            $ins['inv_payment_applied']		     = $ins['inv_overall_price'];
            $ins['inv_is_paid']                  = 1;


	        /* INSERT SAlES RECEIPT HERE */
	        $sales_receipt_id = Tbl_customer_invoice::insertGetId($ins);

	        /* Transaction Journal */
	        $entry["reference_module"]  = 'sales-receipt';
	        $entry["reference_id"]      = $sales_receipt_id;
	        $entry["name_id"]           = $insert['customer_id'];
	        $entry["total"]             = $subtotal_price;
	        $entry["vatable"]           = $tax;
            $entry["txn_date"]          = $ins['inv_date'];
	        $entry["discount"]          = $discount;
	        $entry["ewt"]               = $ewt;

	        $return = Self::insertline($sales_receipt_id, $insert_item, $entry, $user_info, false);

	        if(CustomerWIS::settings($user_info->shop_id) == 0)
			{
				/*$settings_auto_post_transaction = AccountingTransaction::settings($user_info->shop_id, 'auto_post_transaction');
				if($settings_auto_post_transaction == 1)
            	{
	                $update_status['transaction_status'] = 'posted';
	                Tbl_customer_invoice::where('inv_id', $sales_receipt_id)->update($update_status);*/

					$warehouse_id = Warehouse2::get_current_warehouse($user_info->shop_id);
					AccountingTransaction::consume_inventory($user_info->shop_id, $warehouse_id, $insert_item, 'sales_receipt', $sales_receipt_id, 'Consume upon creating SALES RECEIPT '.$ins['transaction_refnum']);
				/*}*/
			}
		}
		else
		{
			$return = $val;
		}		

        return $return; 
	}
	public static function insertline($sales_receipt_id, $insert_item, $entry, $user_info, $for_update = '')
	{
		if(count($insert_item) > 0)
		{
			$id_not_delete = array();
			$itemline = null;
			$return = null;
			foreach ($insert_item as $key => $value) 
			{	
		        /* DISCOUNT PER LINE */
		        $discount       = $value['item_discount'];
		        $discount_type  = 'fixed';
		        if(strpos($discount, '%'))
	            {
	            	$discount       = substr($discount, 0, strpos($discount, '%')) / 100;
	                $discount_type  = 'percent';
	            }

				$itemline['invline_inv_id'] 			= $sales_receipt_id;
				$itemline['invline_service_date'] 		= $value['item_servicedate'];
				$itemline['invline_item_id'] 			= $value['item_id'];
				$itemline['invline_description'] 		= $value['item_description'];
				$itemline['invline_sub_wh_id']			= $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;
				$itemline['invline_um'] 				= $value['item_um'];
				$itemline['invline_qty'] 				= $value['item_qty'];
				$itemline['invline_orig_qty']			= $value['item_qty'];
				$itemline['invline_rate'] 				= $value['item_rate'];
				$itemline['invline_discount'] 			= $discount;
				$itemline['invline_discount_type'] 		= $discount_type;
				$itemline['invline_discount_remark'] 	= $value['item_remarks'];
				$itemline['taxable'] 					= $value['item_taxable'] != null ? $value['item_taxable'] : 0;
				$itemline['invline_amount'] 			= $value['item_amount'];
				$itemline['date_created'] 				= Carbon::now();

				$itemline['invline_refname'] 			= $value['item_refname'];
				$itemline['invline_refid'] 				= $value['item_refid'];

				$cus_inv_line_id = Tbl_customer_invoice_line::insertGetId($itemline);

				array_push($id_not_delete, $cus_inv_line_id);

				AccountingTransaction::check_update_sales_price($user_info->shop_id, $user_info->user_id, $value['item_id'], $value['item_rate']);
				
				if(CustomerWIS::settings($user_info->shop_id) == 0 && $itemline['invline_refid'] && $itemline['invline_refname'])
				{
					$qty = Tbl_quantity_monitoring::where('qty_transaction_id', $sales_receipt_id)->where('qty_item_id', $value['item_id'])->where('qty_shop_id', $user_info->shop_id)->first();

	 				if($qty == null || $for_update == false)
	 				{
	 					$insert_qty_item['qty_item_id']              = $itemline['invline_item_id'];
	                    $insert_qty_item['qty_transaction_id']       = $sales_receipt_id;
	                    $insert_qty_item['qty_transaction_name']     = 'sales_receipt';
	                    $insert_qty_item['qty_transactionline_id']   = $cus_inv_line_id;
	                    $insert_qty_item['qty_ref_id']               = $itemline['invline_refid'];
	                    $insert_qty_item['qty_ref_name']             = $itemline['invline_refname'];
	                    $insert_qty_item['qty_old']                  = $itemline['invline_orig_qty'];
	                    $insert_qty_item['qty_new']                  = $itemline['invline_orig_qty'];
	                    $insert_qty_item['qty_shop_id']              = $user_info->shop_id;
	                    $insert_qty_item['created_at']               = Carbon::now();
	                    Tbl_quantity_monitoring::insert($insert_qty_item);
	 				}
		 			else
		 			{
		 				$insert_qty_item['qty_old'] = $qty['qty_new'];
		                $insert_qty_item['qty_new'] = $value['item_qty'];
		                $insert_qty_item['qty_transactionline_id'] = $cus_inv_line_id;
		                Tbl_quantity_monitoring::where('qty_transaction_id', $sales_receipt_id)->where('qty_item_id', $value['item_id'])->update($insert_qty_item);
		 			}
				}
			}

			if($id_not_delete != null)
			{
				Tbl_quantity_monitoring::where("qty_transaction_id", $sales_receipt_id)->whereNotIn("qty_transactionline_id", $id_not_delete)->where('qty_transaction_name', 'sales_receipt')->delete();
			}
			AccountingTransaction::entry_data($entry, $insert_item);
			$return = $sales_receipt_id;
		}
		return $return;
	}
    public static function applied_transaction($shop_id, $transaction_id = 0, $for_update = false)
    {
        $applied_transaction = Session::get('applied_transaction_sr');
        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value) 
            {
            	AccountingTransaction::checkSolineQty($key, $transaction_id, $for_update);
            	//Self::check_qty($key, $transaction_id);
                // $update['est_status'] = 'closed';
                // Tbl_customer_estimate::where("est_id", $key)->where('est_shop_id', $shop_id)->update($update);
            }
        }
        Self::insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction);
    }
    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
    	$get_transaction = Tbl_customer_invoice::where("inv_shop_id", $shop_id)->where("inv_id", $transaction_id)->first();
    	$transaction_data = null;
    	if($get_transaction)
    	{
    		$transaction_data['transaction_ref_name'] = "sales_receipt";
		 	$transaction_data['transaction_ref_id'] = $transaction_id;
		 	$transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
		 	$transaction_data['transaction_date'] = $get_transaction->inv_date;

		 	$attached_transaction_data = null;
		 	if(count($applied_transaction) > 0)
		 	{
			 	foreach ($applied_transaction as $key => $value) 
			 	{
			 		$get_data = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("est_id", $key)->first();
			 		if($get_data)
			 		{
				 		$attached_transaction_data[$key]['transaction_ref_name'] = "estimate_qoutation";
				 		if($get_data->is_sales_order == 1)
				 		{
				 			$attached_transaction_data[$key]['transaction_ref_name'] = "sales_order";
				 		}
					 	$attached_transaction_data[$key]['transaction_ref_id'] = $key;
					 	$attached_transaction_data[$key]['transaction_list_number'] = $get_data->transaction_refnum;
					 	$attached_transaction_data[$key]['transaction_date'] = $get_data->est_date;
			 		}
			 	}
		 	}
    	}

    	if($transaction_data)
		{
			AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
		}
    }
    

    public static function check_qty($eq_so_id, $inv_id)
    {
        $estline = Tbl_customer_estimate_line::estimate()->where('estline_est_id', $eq_so_id)->get();
        $ctr = 0;
        foreach ($estline as $key => $value)
        {
        	$transaction = "estimate_quotation";
        	if($value->is_sales_order == 1)
        	{
        		$transaction = "sales_order";
        	}
            $invline = Tbl_customer_invoice_line::where('invline_inv_id', $inv_id)
            									->where('invline_refname', $transaction)
            									->where('invline_item_id', $value->estline_item_id)
            									->where('invline_refid',$eq_so_id)
            									->first();
            $update = null;
            $update['estline_qty'] = $value->estline_qty - $invline->invline_qty;
            
            Tbl_customer_estimate_line::where('estline_id', $value->estline_id)->update($update);    

            if($update['estline_qty'] <= 0)
            {
                $ctr++;
            }
        }
        if($ctr >= count($estline))
        {
            $updates["est_status"] = "closed";
            Tbl_customer_estimate::where("est_id",$eq_so_id)->update($updates);
        }
    }
    public static function getBalancePerSR($shop_id, $inv_id)
    {        
        $si = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('is_sales_receipt',1)->where('inv_id', $inv_id)->first();
        $si_line = Tbl_customer_invoice_line::where('invline_inv_id', $si->inv_id)->get();
        
        $balance = null;
        $invline_amount = null;
        foreach ($si_line as $key => $value)
        {
            $po_orig_qty = $value->invline_orig_qty < 1 ? 1 : $value->invline_orig_qty;
            if($si->inv_discount_value != 0 || $si->inv_discount_value != '')
            {
                if($value->invline_qty != 0)
                {
                	if($value->invline_orig_qty != 0)
                    {
                    	if($si->ewt != 0)
		            	{
	                    	if($si->inv_discount_type == 'percent')//
		                    {
		                        //PER ITEM LESS DISCOUNT PER PO
		                        $si_disc = ($si->inv_discount_value / 100) * $value->invline_amount;
		                        $si_discount_per_item = $si_disc / $value->invline_orig_qty;

		                        /*EWT PER TR*/
			            		$si_ewt = $value->invline_amount * $si->ewt; 
			            		$si_ewt_per_item = $si_ewt / $value->invline_orig_qty;

		                        //PER ITEM RATE LESS PER ITEM DISCOUNT
		                        $rate = $value->invline_amount / $value->invline_orig_qty;

		                        if($value->taxable == '1')
		                        {
		                            //PER ITEM TAX AMOUNT
		                            $tax_per_item = ($value->invline_amount * 0.12)  / $value->invline_orig_qty;

		                            //PER ITEM RATE - LESS DISCOUNT + TAX
		                            $new_rate = $rate - $si_discount_per_item + $tax_per_item - $si_ewt_per_item;
		                            
		                            $balance = $new_rate * $value->invline_qty;

		                        }
		                        elseif($value->taxable == '0')
		                        {
		                            //PER ITEM RATE - LESS DISCOUNT
		                            $new_rate = $rate - $si_discount_per_item - $si_ewt_per_item;
		                            $balance = $new_rate * $value->invline_qty;
		                        }
		                    }
		                    elseif($si->inv_discount_type == 'value')
		                    {
		                        /*DISCOUNT PER PO_LINE_AMOUNT*/
		                        $si_disc = ($si->inv_discount_value / $si->inv_subtotal_price) * $value->invline_amount;

		                        /*EWT PER TR*/
			            		$si_ewt = $value->invline_amount * $si->ewt; 
			            		$si_ewt_per_item = $si_ewt / $value->invline_orig_qty;

		                        /*DISCOUNT PER ITEM*/
		                        $si_discount_per_item = $si_disc / $value->invline_orig_qty;

		                        //PER ITEM RATE LESS PER ITEM DISCOUNT
		                        $rate = $value->invline_amount / $value->invline_orig_qty;

		                        if($value->taxable == '1')
		                        {
		                            /*TAX AMOUNT PER ITEM*/
		                            $tax_per_item = ($value->invline_amount * 0.12) / $value->invline_orig_qty;

		                            /*DISCOUNTED PRICE PER ITEM + TAX */
		                            $new_rate = ($rate - $si_discount_per_item) + $tax_per_item - $si_ewt_per_item;
		                            //dd($new_rate);
		                            /*DISCOUNTED AMOUNT + TAX PER PO_LINE*/
		                            $balance = $new_rate * $value->invline_qty;
		                        }
		                        elseif($value->taxable == '0')
		                        {
		                            //PER ITEM RATE - LESS DISCOUNT + TAX
		                            $new_rate = $rate - $si_discount_per_item - $si_ewt_per_item;
		                            
		                            /*DISCOUNTED AMOUNT PER PO_LINE*/
		                            $balance = $new_rate * $value->invline_qty;
		                        }
		                    }
		                    $invline_amount += $balance;
		                }
		                else
		                {
		                	if($si->inv_discount_type == 'percent')//
		                    {
		                        //PER ITEM LESS DISCOUNT PER PO
		                        $si_disc = ($si->inv_discount_value / 100) * $value->invline_amount;
		                        $si_discount_per_item = $si_disc / $value->invline_orig_qty;

		                        //PER ITEM RATE LESS PER ITEM DISCOUNT
		                        $rate = $value->invline_amount / $value->invline_orig_qty;

		                        if($value->taxable == '1')
		                        {
		                            //PER ITEM TAX AMOUNT
		                            $tax_per_item = ($value->invline_amount * 0.12)  / $value->invline_orig_qty;

		                            //PER ITEM RATE - LESS DISCOUNT + TAX
		                            $new_rate = $rate - $si_discount_per_item + $tax_per_item;
		                            
		                            $balance = $new_rate * $value->invline_qty;

		                        }
		                        elseif($value->taxable == '0')
		                        {
		                            //PER ITEM RATE - LESS DISCOUNT
		                            $new_rate = $rate - $si_discount_per_item;
		                            $balance = $new_rate * $value->invline_qty;
		                        }
		                    }
		                    elseif($si->inv_discount_type == 'value')
		                    {
		                        /*DISCOUNT PER PO_LINE_AMOUNT*/
		                        $si_disc = ($si->inv_discount_value / $si->inv_subtotal_price) * $value->invline_amount;

		                        /*DISCOUNT PER ITEM*/
		                        $si_discount_per_item = $si_disc / $value->invline_orig_qty;

		                        //PER ITEM RATE LESS PER ITEM DISCOUNT
		                        $rate = $value->invline_amount / $value->invline_orig_qty;

		                        if($value->taxable == '1')
		                        {
		                            /*TAX AMOUNT PER ITEM*/
		                            $tax_per_item = ($value->invline_amount * 0.12) / $value->invline_orig_qty;

		                            /*DISCOUNTED PRICE PER ITEM + TAX */
		                            $new_rate = ($rate - $si_discount_per_item) + $tax_per_item;
		                            //dd($new_rate);
		                            /*DISCOUNTED AMOUNT + TAX PER PO_LINE*/
		                            $balance = $new_rate * $value->invline_qty;
		                        }
		                        elseif($value->taxable == '0')
		                        {
		                            //PER ITEM RATE - LESS DISCOUNT + TAX
		                            $new_rate = $rate - $si_discount_per_item;
		                            
		                            /*DISCOUNTED AMOUNT PER PO_LINE*/
		                            $balance = $new_rate * $value->invline_qty;
		                        }
		                    }
		                    $invline_amount += $balance;
		                }
                    }
                    else
                    {
                    	$invline_amount = 0;
                    }
                }
                else
                {
                    $invline_amount = 0;
                }
            }
            else
            {
            	if($value->invline_qty != 0)
                {
                	if($value->invline_orig_qty != 0)
                    { 
		            	if($si->ewt != 0)
		            	{
		            		/*EWT PER TR*/
		            		$si_ewt = $value->invline_amount * $si->ewt; 
		            		$si_ewt_per_item = $si_ewt / $value->invline_orig_qty;

		            		$rate = $value->invline_amount / $value->invline_orig_qty;

			                if($value->taxable == '1')
			                {
			                    $tax_per_item = ($value->invline_amount * 0.12)  / $value->invline_orig_qty;

			                    $new_rate = $rate + $tax_per_item - $si_ewt_per_item;
			                    //dd()
			                    $balance = $new_rate * $value->invline_qty;
			                }
			                elseif($value->taxable == '0')
			                {
			                    $new_rate = $rate - $si_ewt_per_item;
			                    $balance = $new_rate * $value->invline_qty;
			                }
		            	}
		            	else
			            {
			            	$rate = $value->invline_amount / $value->invline_orig_qty;

			                if($value->taxable == '1')
			                {
			                    $tax_per_item = ($value->invline_amount * 0.12)  / $value->invline_orig_qty;

			                    $new_rate = $rate + $tax_per_item;
			                    //dd()
			                    $balance = $new_rate * $value->invline_qty;
			                }
			                elseif($value->taxable == '0')
			                {
			                    $new_rate = $rate;
			                    $balance = $new_rate * $value->invline_qty;
			                }
			            }
		            }
		            else
		            {
		            	$invline_amount = 0;
		            }

		            $invline_amount += $balance;
		        }
		        else
		        {
		        	$invline_amount = 0;
		        }
                
            }
        }
        return $invline_amount;
    }
    
    public static function insertPMline($so_id, $_pm)
    {
        Tbl_customer_invoice_pm::where("invoice_id", $so_id)->delete();
		$proj = AccountingTransaction::settings_value(AccountingTransaction::getShopId(), "project_name");
        if($proj == "migo")
        {
	        if(count($_pm) > 0)
	        {
	            $ins = null;
	            foreach ($_pm as $key => $value) 
	            {
	                if($value['pm_id'])
	                {
	                    $ins[$key]['invoice_id']            = $so_id;
	                    $ins[$key]['inv_pm_id']             = $value['pm_id'];
	                    $ins[$key]['invoice_reference_num'] = $value['pm_ref_no'];
	                    $ins[$key]['invoice_amount']        = str_replace(',', '', $value['pm_amount']);
	                }
	            }
	            if(count($ins) > 0)
	            {
	            	Tbl_customer_invoice_pm::insert($ins);
	            }
	        }
    	}
    }

    public static function getPMline($so_id)
    {
        return Tbl_customer_invoice_pm::pm()->where("invoice_id", $so_id)->get();
    }

}