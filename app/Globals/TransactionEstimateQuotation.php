<?php
namespace App\Globals;

use App\Models\Tbl_customer;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_estimate_line;
use Carbon\Carbon;
use DB;
use App\Globals\AccountingTransaction;
/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionEstimateQuotation
{
	public static function get_total_amount($shop_id, $tab)
    {
    	$check_allow_transaction = AccountingTransaction::settings($shop_id, 'allow_transaction');
    	$eq = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("is_sales_order",0);

        $total    = 0;
        if($tab == 'all')
        {
            $eq = $eq->get();
        }
        else
        {
            $eq = $eq->where("est_status",$tab)->get();
        }
        if(count($eq) > 0)
        {
            foreach ($eq as $key => $value) 
            {
               $total += $value->est_overall_price;
            }            
        }

        if($check_allow_transaction == 1)
        {
        	$eq = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("is_sales_order",0);
        	$eq = AccountingTransaction::acctg_trans($shop_id, $eq, 'estimate_quotation');

        	$data = null;
	        $total = null;
	        if($tab == 'all')
	        {
	            $eq = $eq->get();
	        }
	        else
	        {
	            $eq = $eq->where("est_status",$tab)->get();
	        }
	        if(count($eq) > 0)
	        {
	            foreach ($eq as $key_eq => $value_eq)
	            {
	                $data[$value_eq->est_id] = $value_eq->est_overall_price;
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
            $qty = UnitMeasurement::um_qty($value->estline_um);

            $total_qty = $value->estline_orig_qty * $qty;
            $transactionline[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->estline_um);
        }
        return $transactionline;
    }
	public static function getOpenEQ($shop_id, $customer_id)
	{
		$data = Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("est_customer_id",$customer_id)->where("est_status","!=","closed")->where("is_sales_order",0)->get();
		foreach ($data as $key => $value)
        {
            $data[$key]->so_balance = Self::getBalancePerEQ($shop_id, $value->est_id, $customer_id);
        }
        return $data;

		//return Tbl_customer_estimate::where("is_sales_order",0)->where("est_status","accepted")->where("est_shop_id", $shop_id)->where("est_customer_id",$customer_id)->get();
	}

	public static function getAllOpenEQ($shop_id)
    {
        return Tbl_customer_estimate::customer()->where('est_shop_id',$shop_id)->where("est_status","accepted")->where('is_sales_order', 0)->get();
    }

	public static function info($shop_id, $eq_id)
	{
		return Tbl_customer_estimate::customer()->where("est_shop_id", $shop_id)->where("est_id", $eq_id)->first();
	}
	public static function info_item($eq_id)
	{
		$data = Tbl_customer_estimate_line::estimate_item()->um()->where("estline_est_id", $eq_id)->get();		
		foreach($data as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->estline_um);

            $total_qty = $value->estline_qty * $qty;
            $data[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->estline_um);
        }
        return $data;
	}
	public static function update_status($eq_id, $update)
	{
        Tbl_customer_estimate::where("est_id",$eq_id)->update($update);
	}

	public static function get($shop_id, $paginate = null, $search_keyword = null, $status = null)
	{
		$data = Tbl_customer_estimate::customer()->where('est_shop_id', $shop_id)->where('is_sales_order',0)->groupBy("est_id")->orderBy("est_date","desc");

        $data = AccountingTransaction::acctg_trans($shop_id, $data, 'estimate_quotation');

		if($search_keyword)
		{
			$data->where(function($q) use ($search_keyword)
            {
                $q->orWhere("transaction_refnum", "LIKE", "%$search_keyword%");
                $q->orWhere("est_id", "LIKE", "%$search_keyword%");
                $q->orWhere("company", "LIKE", "%$search_keyword%");
                $q->orWhere("first_name", "LIKE", "%$search_keyword%");
                $q->orWhere("middle_name", "LIKE", "%$search_keyword%");
                $q->orWhere("last_name", "LIKE", "%$search_keyword%");
            });
		}

		if($status != 'all')
		{
			$data->where('est_status',$status);
		}
		if($paginate)
		{
			$data = $data->paginate($paginate);
		}
		else
		{
			$data = $data->get();
		}
		if($data)
		{
			foreach ($data as $key => $value)
	        {
	            $customer = Tbl_customer::where('customer_id', $value->customer_id)->where('shop_id', $shop_id)->first();
	            $data[$key]['customer_archived'] =  $customer->archived;
	        }
		}
		return $data;
	}
	public static function postUpdate($estimate_id, $user_info, $insert, $insert_item = array())
	{
		$val = AccountingTransaction::customer_validation($insert, $insert_item);
		if(!$val)
		{
			$return  = null; 
			$ins['est_shop_id']                  = $user_info->shop_id;  
			$ins['est_customer_id']              = $insert['customer_id'];  
			$ins['transaction_refnum']	 		 = $insert['transaction_refnum'];   
	        $ins['est_customer_email']           = $insert['customer_email'];
	        $ins['est_customer_billing_address'] = $insert['customer_address'];
	        $ins['est_date']                     = date("Y-m-d", strtotime($insert['transaction_date']));
	        $ins['est_exp_date']                 = date("Y-m-d", strtotime($insert['transaction_duedate']));
	        $ins['est_message']                  = $insert['customer_message'];
	        $ins['est_memo']                     = $insert['customer_memo'];
	        $ins['est_discount_type']			 = $insert['customer_discounttype'];
	        $ins['est_discount_value'] 			 = $insert['customer_discount'];
 			$ins['ewt'] 						 = $insert['customer_ewt'];
	        $ins['date_created']                 = Carbon::now();

	        // /* SUBTOTAL */
	        // $subtotal_price = collect($insert_item)->sum('item_amount');

	        // /* OVERALL TOTAL */
	        // $overall_price  = convertToNumber($subtotal_price);

	        $ins['est_subtotal_price']           = $insert['subtotal_price'];
	        $ins['est_overall_price']            = $insert['overall_price'];


	        /* INSERT INVOICE HERE */
	        Tbl_customer_estimate::where('est_id', $estimate_id)->update($ins);


	        Tbl_customer_estimate_line::where('estline_est_id', $estimate_id)->delete();

	        $return = Self::insertline($estimate_id, $insert_item, $user_info);

			// /* INSERT TRANSACTION HERE */
			// $acctg['transaction_ref_name'] = "estimate_quotation";
			// $acctg['transaction_ref_id'] = $estimate_id;
			// $acctg['transaction_list_number'] = $ins['transaction_refnum'];
			// $acctg['transaction_date'] = $ins['est_date'];
			// AccountingTransaction::postTransaction($shop_id, $acctg, $insert_item);
		}
		else
		{
			$return = $val;
		}		

        return $return; 
	}
	public static function postInsert($user_info, $insert, $insert_item = array())
	{
		$val = AccountingTransaction::customer_validation($insert, $insert_item, 'estimate_quotation');
		if(!$val)
		{
			$return  = null; 
			$ins['est_shop_id']                  = $user_info->shop_id;  
			$ins['est_customer_id']              = $insert['customer_id'];  
			$ins['transaction_refnum']	 		 = $insert['transaction_refnum'];   
	        $ins['est_customer_email']           = $insert['customer_email'];
	        $ins['est_customer_billing_address'] = $insert['customer_address'];
	        $ins['est_date']                     = date("Y-m-d", strtotime($insert['transaction_date']));
	        $ins['est_exp_date']                 = date("Y-m-d", strtotime($insert['transaction_duedate']));
	        $ins['est_message']                  = $insert['customer_message'];
	        $ins['est_memo']                     = $insert['customer_memo'];
	        $ins['est_discount_type']			 = $insert['customer_discounttype'];
	        $ins['est_discount_value'] 			 = $insert['customer_discount'];
 			$ins['ewt'] 						 = $insert['customer_ewt'];
	        $ins['date_created']                 = Carbon::now();
	       
 		
	        // /* SUBTOTAL */
	        // $subtotal_price = collect($insert_item)->sum('item_amount');

	        // /* OVERALL TOTAL */
	        // $overall_price  = convertToNumber($subtotal_price);

	        $ins['est_subtotal_price']           = $insert['subtotal_price'];
	        $ins['est_overall_price']            = $insert['overall_price'];


	        /* INSERT INVOICE HERE */
	        $estimate_id = Tbl_customer_estimate::insertGetId($ins);

	        $return = Self::insertline($estimate_id, $insert_item, $user_info);

		}
		else
		{
			$return = $val;
		}		

        return $return; 
	}

	public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
    	$get_transaction = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("est_id", $transaction_id)->first();
    	$transaction_data = null;
    	if($get_transaction)
    	{
    		$transaction_data['transaction_ref_name'] = "estimate_quotation";
		 	$transaction_data['transaction_ref_id'] = $transaction_id;
		 	$transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
		 	$transaction_data['transaction_date'] = $get_transaction->est_date;

		 	$attached_transaction_data = null;
    	}

    	if($transaction_data)
		{
			AccountingTransaction::postTransaction($shop_id, $transaction_data, $attached_transaction_data);
		}
    }
	public static function insertline($estimate_id, $insert_item, $user_info)
	{
		$itemline = null;
		$return = null;
		foreach ($insert_item as $key => $value) 
		{	
	        /* DISCOUNT PER LINE */
	        $discount       = $value['item_discount'];
	        $discount_type  = 'fixed';
	        if(strpos($discount, '%'))
            {
            	$discount       = substr($discount, 0, strpos($discount, '%'));
                $discount_type  = 'percent';
            }

			$itemline[$key]['estline_est_id'] 			= $estimate_id;
			$itemline[$key]['estline_service_date'] 	= $value['item_servicedate'];
			$itemline[$key]['estline_item_id'] 			= $value['item_id'];
			$itemline[$key]['estline_description'] 		= $value['item_description'];
			$itemline[$key]['estline_um'] 				= $value['item_um'];
			$itemline[$key]['estline_qty'] 				= $value['item_remaining'];
			$itemline[$key]['estline_orig_qty']			= $value['item_qty'];
			$itemline[$key]['estline_status']           = $value['item_status'];
			$itemline[$key]['estline_rate'] 			= $value['item_rate'];
			$itemline[$key]['estline_discount'] 		= $discount;
			$itemline[$key]['estline_discount_type'] 	= $discount_type;
			$itemline[$key]['estline_discount_remark'] 	= $value['item_remarks'];
			$itemline[$key]['taxable'] 					= $value['item_taxable'] != null ? $value['item_taxable'] : 0;
			$itemline[$key]['estline_amount'] 			= $value['item_amount'];
			$itemline[$key]['date_created'] 			= Carbon::now();

			$itemline[$key]['estline_proposal_number']  = $value['estline_proposal_number'];

			AccountingTransaction::check_update_sales_price($user_info->shop_id, $user_info->user_id, $value['item_id'], $value['item_rate']);

		}
		if(count($itemline) > 0)
		{
			Tbl_customer_estimate_line::insert($itemline);
			$return = $estimate_id;
		}

		return $return;
	}
	public static function get_proposal($shop_id, $customer_id)
	{
		$return = array();
		$get = Tbl_customer::where("shop_id", $shop_id)->where("customer_id", $customer_id)->value("customer_proposals");
		if($get)
		{
			$return = $get != null ? unserialize($get) : $return; 
		}
		return $return;
	}
	public static function getBalancePerEQ($shop_id, $est_id, $customer_id = '')
    {        
        $data = Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("is_sales_order",0)->where('est_customer_id', $customer_id)->where('est_id', $est_id)->first();
        $eq_line = Tbl_customer_estimate_line::where('estline_est_id', $data->est_id)->get();
        
        $balance = null;
        $estline_amount = null;


        foreach ($eq_line as $key => $value)
        {
        	$est_orig_qty = $value->estline_orig_qty < 1 ? 1 : $value->estline_orig_qty;
        	if( $value->estline_orig_qty != 0)
        	{
        		if($value->estline_discount_type == 'fixed')
	        	{
	        		if($value->estline_discount > 0)
	        		{
	        			/*GET DISCOUNT PER ITEM*/
		        		$disc_per_item = $value->estline_discount / $value->estline_orig_qty;

		        		/*GET NEW RATE*/
			        	$new_rate = $value->estline_rate - $disc_per_item;
	        		}
	        		else
	        		{
	        			$new_rate = $value->estline_rate;
	        		}
		    		/*GET REMAINING BALANCE*/
		    		$balance = $new_rate * $value->estline_qty;
	        	}
	        	elseif($value->estline_discount_type == 'percent')
	        	{ 
	        		if($value->estline_discount > 0)
	        		{
		        		/*GET DISCOUNT PER ITEM*/
		        		$total = $value->estline_orig_qty * $value->estline_rate;
		            	$disc_per_item = ($total * $value->estline_discount) / $value->estline_orig_qty;
		            	
		            	/*GET NEW RATE*/
			        	$new_rate = $value->estline_rate - $disc_per_item;
		        	}
		        	else
		        	{
		        		$new_rate = $value->estline_rate;
		        	}
		    		/*GET REMAINING BALANCE*/
		    		$balance = $new_rate * $value->estline_qty;
	        	}
	        	
	        	$estline_amount += $balance;
        	}
        	else
        	{
        		$estline_amount = 0;
        	}
        	
        }
        return $estline_amount;
    }
}