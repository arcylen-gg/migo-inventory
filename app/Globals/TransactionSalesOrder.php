<?php
namespace App\Globals;

use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_estimate_line;
use App\Models\Tbl_customer_estimate_pm;
use App\Models\Tbl_item;
use App\Models\Tbl_customer;
use Carbon\Carbon;
use DB;
use App\Globals\AccountingTransaction;
use Session;
/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */ 

class TransactionSalesOrder
{
    public static function getReferenceNumber($shop_id, $customer_id)
    {
        $return_ref_num = "";

        $count_customer = Tbl_customer_estimate::where('est_shop_id',$shop_id)->where('est_customer_id',$customer_id)->orderBy('est_id','DESC')->first();
        $chk_refnum = $count_customer['est_cheque_ref_no'];
            if(empty($chk_refnum)) // check if $count_customer->est_cheque_ref_no have value
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
    public static function get_not_enough_item($ids)
    {
        $item_name = null;
        if($ids)
        {
            $item_ids =  explode(',', $ids);
            $filter_item_ids = array_filter($item_ids);

            foreach ($filter_item_ids as $key => $item) 
            {
                $item_name[$key] = Item::info($item)->item_name;
            }
        }
       
        return $item_name;
    }

    public static function validated_transaction($insert)
    {
        $insert_info = null;
        if($insert)
        {
            $array_insert =  explode(',', $insert);
            $insert_info['transaction_refnum']    = $array_insert[0];
            $insert_info['customer_id']           = $array_insert[1];
            $insert_info['customer_email']        = $array_insert[2];
            $insert_info['customer_address']      = $array_insert[3];
            $insert_info['transaction_date']      = $array_insert[4];
            $insert_info['customer_message']      = $array_insert[5];
            $insert_info['customer_memo']         = $array_insert[6];
            $insert_info['customer_ewt']             = $array_insert[7];;
            $insert_info['customer_discounttype']    = $array_insert[8];;
            $insert_info['customer_discount_value']  = $array_insert[9];;
            $insert_info['customer_subtotal_price']  = $array_insert[10];;
            $insert_info['customer_overall_price']   = $array_insert[11];;
        }
        return $insert_info;
    }
    public static function insert_item_keys()
    {
        $array_key = null;
        $array_key[0] = "item_id";
        $array_key[1] = "item_servicedate";
        $array_key[2] = "item_description";
        $array_key[3] = "item_um";
        $array_key[4] = "item_qty";
        $array_key[5] = "item_rate";
        $array_key[6] = "item_discount";
        $array_key[7] = "item_remarks";
        $array_key[8] = "item_amount";
        $array_key[9] = "item_taxable";
        $array_key[10] = "item_status";
        $array_key[11] = "item_remaining";
        $array_key[12] = "item_refname";
        $array_key[13] = "item_refid";
        return $array_key;
    }
    public static function get_insert_item($items)
    {
        $insert_item = null;
        $item_insert = null;
        if($items)
        {
            $array_insert_item =  explode('@',$items);
            $filtered_array = array_filter($array_insert_item);
            foreach ($filtered_array as $key_filtered_array => $value_filtered_array)
            {
                if($value_filtered_array[0] != ',')
                {
                    $remove_character = $value_filtered_array;
                }
                else
                {
                    $remove_character = substr($value_filtered_array, 1);
                }
                $insert_item[$key_filtered_array] =  explode(',',(str_replace("_"," ", $remove_character)));
            }
            if($insert_item)
            {
                foreach ($insert_item as $key_insert_item => $value_insert_item)
                {
                    foreach ($value_insert_item as $key_value_insert_item => $value_value_insert_item)
                    {
                        $data_key = Self::insert_item_keys();
                        foreach($data_key as $key2 => $value2)
                        {
                            if($key2 == $key_value_insert_item)
                            {
                                $item_insert[$key_insert_item][$value2] = $value_insert_item[$key_value_insert_item];
                            }
                        }
                    }
                }
            }
        }
        
        return $item_insert;
    }
	public static function get_total_amount($shop_id, $tab)
    {
        $check_allow_transaction = AccountingTransaction::settings($shop_id, 'allow_transaction');
        $so = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("is_sales_order",1);

        $total    = 0;
        if($tab == 'all')
        {
            $so = $so->get();
        }
        else
        {
            $so = $so->where("est_status",$tab)->get();
        }
        if(count($so) > 0)
        {
            foreach ($so as $key => $value) 
            {
               $total += $value->est_overall_price;
            }            
        }

        if($check_allow_transaction == 1)
        {
            $so = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("is_sales_order",1);
            $so = AccountingTransaction::acctg_trans($shop_id, $so, 'sales_order');

            $data = null;
            $total = null;
            if($tab == 'all')
            {
                $so = $so->get();
            }
            else
            {
                $so = $so->where("est_status",$tab)->get();
            }
            if(count($so) > 0)
            {
                foreach ($so as $key_so => $value_so)
                {
                    $data[$value_so->est_id] = $value_so->est_overall_price;
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
	public static function get_open_so_total_amount($shop_id)
    {
        $price = 0;
        $so = Tbl_customer_estimate::where("est_shop_id",$shop_id)
                                ->where("est_status", 'accepted')
                                ->where("is_sales_order",'1')->get();
        if(isset($so))
        {
            foreach ($so as $key => $value) 
            {
               $price += $value->est_overall_price;
            }            
        }
        return $price;
    }
    public static function update_transaction_status($so_id) 
    {
        $soline = tbl_customer_estimate_line::where('estline_est_id', $so_id)->get();
        if($soline)
        {
            $item_status=null;
            foreach ($soline as $key => $value)
            {
                $item_status += $value->estline_status;
            }

            if(count($soline) == $item_status)
            {
                $update_so['est_status'] = 'closed';
            }
            else
            {
                $update_so['est_status'] = 'accepted';
            }
            Tbl_customer_estimate::where("est_id",$so_id)->update($update_so);
            
        }
    }
    public static function count_open_so($shop_id)
    {
         return Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("est_status", 'accepted')->where("is_sales_order",'1')->count();
    }
    public static function get_total_amount_perwh($shop_id, $warehouse_id)
    {
        $price = 0;
        $so = Tbl_customer_estimate::PerWarehouse("est_shop_id",$shop_id)
                                ->where("est_status", 'accepted')
                                ->where("is_sales_order",'1')
                                ->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)
                                ->get();
        if(isset($so))
        {
            foreach ($so as $key => $value) 
            {
               $price += $value->est_overall_price;
            }            
        }
        return $price;
    }
    public static function count_perwh($shop_id, $warehouse_id)
    {
         return Tbl_customer_estimate::PerWarehouse("est_shop_id", $shop_id)->where("est_status", 'accepted')->where("is_sales_order",'1')->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->count();
    }
	public static function getOpenSO($shop_id, $customer_id)
	{
		$data = Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("est_customer_id",$customer_id)->where("est_status","!=","closed")->where("is_sales_order",1)->get();

        foreach ($data as $key => $value)
        {
            $data[$key]->so_balance = Self::getBalancePerSO($shop_id, $value->est_id, $customer_id);
        }
        return $data;
	}
	public static function countTransaction($shop_id, $customer_id)
	{
		return Tbl_customer_estimate::where("is_sales_order",0)->where("est_status","accepted")->where("est_shop_id", $shop_id)->where("est_customer_id",$customer_id)->count();
	}
	public static function info($shop_id, $sales_order_id)
	{
		return Tbl_customer_estimate::paymentmethod()->customer()->where("est_shop_id", $shop_id)->where("est_id", $sales_order_id)->first();
	}
	public static function info_item($sales_order_id)
	{
		
		$data = Tbl_customer_estimate_line::estimate_item()->um()->where("estline_est_id", $sales_order_id)->get();		
		foreach($data as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->estline_um);
            $total_qty = $value->estline_orig_qty * $qty;
            $received = ($value->estline_orig_qty - $value->estline_qty) * $qty;
            $backorder = $value->estline_qty * $qty;
            $data[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->estline_um);
            $data[$key]->received = UnitMeasurement::um_view($received,$value->item_measurement_id,$value->estline_um);
            $data[$key]->backorder = UnitMeasurement::um_view($backorder,$value->item_measurement_id,$value->estline_um);
        }
        return $data;		
	}
	public static function info_item_whse($sales_order_id, $warehouse_id)
	{
		
		$data = Tbl_customer_estimate_line::estimate_item()->um()->where("estline_est_id", $sales_order_id)->get();		
		foreach($data as $key => $value) 
        {
        	$data[$key]->invty_count = Tbl_item::recordloginventory($warehouse_id)->where('item_id', $value->estline_item_id)->value('inventory_count');
            $qty = UnitMeasurement::um_qty($value->estline_um);

            $total_qty = $value->estline_qty * $qty;
            $data[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->estline_um);
        }
        return $data;		
	}
	public static function getAllOpenSO($shop_id)
    {
        return Tbl_customer_estimate::customer()->where('est_shop_id',$shop_id)->where("est_status","accepted")->where('is_sales_order', 1)->get();
    }   
    public static function getCountAllOpenSO($shop_id)
    {
        return Tbl_customer_estimate::customer()->where('est_shop_id',$shop_id)->where("est_status","accepted")->where('is_sales_order', 1)->count();
    }

	public static function get($shop_id, $paginate = null, $search_keyword = null, $status = null)
	{
		$data = Tbl_customer_estimate::customer()->where('est_shop_id', $shop_id)->where('is_sales_order',1)->groupBy("est_id")->orderBy("date_created","desc");

        $data = AccountingTransaction::acctg_trans($shop_id, $data, 'sales_order');

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
    public static function validate_qty($insert_item, $shop_id, $warehouse_id)
    {
        $return = array();
        if($insert_item)
        {
            if(AccountingTransaction::settings($shop_id,"out_of_stock"))
            {
                foreach ($insert_item as $key => $value)
                {
                    $return[$key] = Warehouse2::consume_validation($shop_id, $warehouse_id, $value['item_id'], $value['item_qty'], $value['item_remarks'], null, null, null, null, null, true);
                }                
            }
            
            return $return;
        }
    }
	public static function postInsert($user_info, $insert, $insert_item = array())
	{
		$val = AccountingTransaction::customer_validation($insert, $insert_item, 'sales_order');
		if(!$val)
		{
			$return  = null; 
			$ins['est_shop_id']                  = $user_info->shop_id;  
			$ins['est_customer_id']              = $insert['customer_id'];  
			$ins['transaction_refnum']	 		 = $insert['transaction_refnum'];   
	        $ins['est_customer_email']           = $insert['customer_email'];
	        $ins['est_customer_billing_address'] = $insert['customer_address'];
	        $ins['est_date']                     = date("Y-m-d", strtotime($insert['transaction_date']));
	        $ins['est_message']                  = $insert['customer_message'];
	        $ins['est_memo']                     = $insert['customer_memo'];
            $ins['est_discount_type']            = $insert['customer_discounttype'];
            $ins['est_discount_value']           = $insert['customer_discount_value'];
            $ins['ewt']                          = $insert['customer_ewt'];
            $ins['est_payment_method']           = $insert['transaction_payment_method'];
            $ins['est_cheque_ref_no']            = $insert['transaction_ref_no'];
            
	        $ins['date_created']                 = Carbon::now();

	        // /* SUBTOTAL */
	        // $subtotal_price = collect($insert_item)->sum('item_amount');

	        // /* OVERALL TOTAL */
	        // $overall_price  = convertToNumber($subtotal_price);

	        $ins['est_subtotal_price']           = $insert['customer_subtotal_price'];
            $ins['est_overall_price']            = $insert['customer_overall_price'];
	        $ins['is_sales_order'] 				 = 1;    
            $ins['est_status']					 = 'accepted';   

	        /* INSERT SALES ORDER HERE */
	        $sales_order_id = Tbl_customer_estimate::insertGetId($ins);
	        
	        $return = Self::insertline($sales_order_id, $insert_item ,$user_info);
		}
		else
		{
			$return = $val;
		}		

        return $return; 
	}

	public static function postUpdate($sales_order_id, $user_info, $insert, $insert_item = array())
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
	        $ins['est_message']                  = $insert['customer_message'];
	        $ins['est_memo']                     = $insert['customer_memo'];
            $ins['est_discount_type']            = $insert['customer_discounttype'];
            $ins['est_discount_value']           = $insert['customer_discount_value'];
            $ins['ewt']                          = $insert['customer_ewt'];
            $ins['est_payment_method']           = $insert['transaction_payment_method'];
            $ins['est_cheque_ref_no']            = $insert['transaction_ref_no'];
	        $ins['date_created']                 = Carbon::now();

	        // /* SUBTOTAL */
	        // $subtotal_price = collect($insert_item)->sum('item_amount');

	        // /* OVERALL TOTAL */
	        // $overall_price  = convertToNumber($subtotal_price);

	        $ins['est_subtotal_price']           = $insert['customer_subtotal_price'];
	        $ins['est_overall_price']            = $insert['customer_overall_price'];
	        $ins['is_sales_order'] 				 = 1;    
            $ins['est_status']					 = 'accepted';   

	        /* INSERT SALES ORDER HERE */
	        Tbl_customer_estimate::where('est_id', $sales_order_id)->update($ins);
	        // $sales_order_id = 0;
	        Tbl_customer_estimate_line::where('estline_est_id', $sales_order_id)->delete();

	        $return = Self::insertline($sales_order_id, $insert_item, $user_info, true);
		}
		else
		{
			$return = $val;
		}		

        return $return; 
	}
	public static function insertline($sales_order_id, $insert_item, $user_info, $for_update = '')
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

            $itemline[$key]['estline_est_id']           = $sales_order_id;
            $itemline[$key]['estline_service_date']     = $value['item_servicedate'];
            $itemline[$key]['estline_item_id']          = $value['item_id'];
            $itemline[$key]['estline_description']      = $value['item_description'];
            $itemline[$key]['estline_um']               = $value['item_um'];
            if($for_update)
            {
                $received_qty = Warehouse2::update_remaining_qty($sales_order_id, $value['item_id'], $user_info->shop_id);
                $itemline[$key]['estline_orig_qty']         = $value['item_qty'];
                $itemline[$key]['estline_received_qty']     = $received_qty;
                $itemline[$key]['estline_qty']              = $value['item_qty'] - $received_qty;
            }
            else
            {
                $itemline[$key]['estline_qty']              = $value['item_remaining'];
                $itemline[$key]['estline_orig_qty']         = $value['item_qty'];
                $itemline[$key]['estline_received_qty']     = $value['item_qty'] - $value['item_remaining'];
            }
            $itemline[$key]['estline_status']           = $value['item_status'];
            $itemline[$key]['estline_rate']             = $value['item_rate'];
            $itemline[$key]['estline_discount']         = $discount;
            $itemline[$key]['estline_discount_type']    = $discount_type;
            $itemline[$key]['estline_discount_remark']  = $value['item_remarks'];
            $itemline[$key]['taxable']                  = $value['item_taxable'] != null ? $value['item_taxable'] : 0;
            $itemline[$key]['estline_amount']           = $value['item_amount'];
            $itemline[$key]['date_created']             = Carbon::now();

            $itemline[$key]['estline_refname']          = $value['item_refname'];
            $itemline[$key]['estline_refid']            = $value['item_refid'];

            AccountingTransaction::check_update_sales_price($user_info->shop_id, $user_info->user_id, $value['item_id'], $value['item_rate']);
		}
		if(count($itemline) > 0)
		{
			Tbl_customer_estimate_line::insert($itemline);
			$return = $sales_order_id;
		}

		return $return;
	}

    public static function applied_transaction($shop_id, $transaction_id = 0)
    {
        $applied_transaction = Session::get('applied_transaction_so');
        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value) 
            {
                AccountingTransaction::checkSolineQty($key, $transaction_id);
            	//Self::check_qty($key, $transaction_id);
                // $update['est_status'] = 'closed';
                // Tbl_customer_estimate::where("est_id", $key)->where('est_shop_id', $shop_id)->update($update);
            }
        }

        Self::insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction);
    }
    public static function insert_acctg_transaction($shop_id, $transaction_id, $applied_transaction = array())
    {
    	$get_transaction = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("est_id", $transaction_id)->first();
    	$transaction_data = null;
    	if($get_transaction)
    	{
    		$transaction_data['transaction_ref_name'] = "sales_order";
		 	$transaction_data['transaction_ref_id'] = $transaction_id;
		 	$transaction_data['transaction_list_number'] = $get_transaction->transaction_refnum;
		 	$transaction_data['transaction_date'] = $get_transaction->est_date;

		 	$attached_transaction_data = null;
		 	if(count($applied_transaction) > 0)
		 	{
			 	foreach ($applied_transaction as $key => $value) 
			 	{
			 		$get_data = Tbl_customer_estimate::where("est_shop_id", $shop_id)->where("est_id", $key)->first();
			 		if($get_data)
			 		{
				 		$attached_transaction_data[$key]['transaction_ref_name'] = "estimate_qoutation";
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

    public static function check_qty($eq_id, $so_id)
    {
        $estline = Tbl_customer_estimate_line::where('estline_est_id', $eq_id)->get();
        $ctr = 0;
        foreach ($estline as $key => $value)
        {
            $soline = Tbl_customer_estimate_line::where('estline_est_id', $so_id)->where('estline_refname', 'estimate_quotation')->where('estline_item_id', $value->estline_item_id)->where('estline_refid',$eq_id)->first();
            $update = null;
            $update['estline_qty'] = $value->estline_qty - $soline->estline_qty;
            
            Tbl_customer_estimate_line::where('estline_id', $value->estline_id)->update($update);    

            if($update['estline_qty'] <= 0)
            {
                $ctr++;
            }
        }
        if($ctr >= count($estline))
        {
            $updates["est_status"] = "closed";
            Tbl_customer_estimate::where("est_id",$eq_id)->update($updates);
        }
    }
    public static function getBalancePerSO($shop_id, $so_id, $customer_id = '')
    {        
        $data = Tbl_customer_estimate::where('est_shop_id',$shop_id)->where('est_id', $so_id)->where('est_customer_id', $customer_id)->where('is_sales_order', 1)->first();
        $so_line = Tbl_customer_estimate_line::where('estline_est_id', $data->est_id)->get();
        
        $balance = null;
        $soline_amount = null;
        foreach ($so_line as $key => $value)
        {
        	if($value->estline_orig_qty != 0)
        	{
        		$est_orig_qty = $value->estline_orig_qty < 1 ? 1 : $value->estline_orig_qty;
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
        	}

        	$soline_amount += $balance;

        }
        return $soline_amount;
    }
    public static function insertPMline($so_id, $_pm)
    {
        Tbl_customer_estimate_pm::where("estimate_id", $so_id)->delete();
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
                        $ins[$key]['estimate_id']            = $so_id;
                        $ins[$key]['est_pm_id']              = $value['pm_id'];
                        $ins[$key]['estimate_reference_num'] = $value['pm_ref_no'];
                        $ins[$key]['estimate_amount']        = str_replace(',', '', $value['pm_amount']);
                    }
                }
                if(count($ins) > 0)
                {
                    Tbl_customer_estimate_pm::insert($ins);
                }
            }
        }
    }

    public static function getPMline($so_id)
    {
        return Tbl_customer_estimate_pm::pm()->where("estimate_id", $so_id)->get();
    }
}