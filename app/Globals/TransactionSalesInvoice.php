<?php
namespace App\Globals;

use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_estimate_line;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_customer_invoice_pm;
use App\Models\Tbl_quantity_monitoring;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_sales_representative;
use Carbon\Carbon;
use DB;
use App\Globals\AccountingTransaction;
use App\Globals\CustomerWIS;
use App\Globals\Warehouse2;
use Session;
use stdClass;
/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionSalesInvoice
{
	/*public static function transactionStatus($shop_id)
    {
        $get = Tbl_customer_invoice::whereNull('transaction_status')->where("inv_shop_id", $shop_id)->where("inv_is_paid",0)->get();
        foreach ($get as $key => $value) 
        {
            $update_status['transaction_status'] = 'posted';
            Tbl_customer_invoice::where("inv_shop_id", $shop_id)->where("inv_id", $value->inv_id)->where("inv_is_paid",0)->update($update_status);
        }
    }*/
    public static function getSalesRep($shop_id, $archived = 0)
    {
        return Tbl_sales_representative::where('sales_rep_shop_id', $shop_id)->where('sales_rep_archived', $archived)->get();
    }
    public static function getSales($shop_id, $from = '', $to = '', $year = false)
    {
        // $get_ar = Tbl_customer_invoice::rpline()->groupBy("tbl_receive_payment_line.inv_id")->get();
        // dd($from, $to);
    	$get = Tbl_customer_invoice::where("inv_shop_id", $shop_id)
                                    ->whereBetween("tbl_customer_invoice.inv_date",[$from, $to]);
        // if($year)
        // {
        //     $get = $get->whereYear("tbl_customer_invoice.inv_date","=",date('Y',strtotime($from)));
        // }
        // else
        // {
        //     $get = $get->whereMonth("tbl_customer_invoice.inv_date","=",date('m',strtotime($from))) 
        //                ->whereYear("tbl_customer_invoice.inv_date","=",date('Y',strtotime($from)));
        // }
     //    $ret = 0;
     //    foreach ($get as $key => $value) 
     //    {
     //        if($value->rp_date)
     //        {
     //            if($value->rp_date <= $from && $value->rp_date >= $to)
     //            {
     //                $ret += $value->inv_overall_price;
     //            }
     //        }
     //        else
     //        {
     //            $ret += $value->inv_overall_price;                
     //        }
     //    }
    	// return $ret;
        return $get->sum('inv_overall_price');
    }
    public static function getAr($shop_id, $from = '', $to = '', $year = false)
    {
    	$get = Tbl_customer_invoice::where("inv_shop_id", $shop_id)
                                   // ->whereBetween("tbl_customer_invoice.inv_date",[$from, $to])
                                   ->where("inv_is_paid", 0);
        if($year)
        {
            $get = $get->whereYear("tbl_customer_invoice.inv_date","=",date('Y',strtotime($from)));
        }
        else
        {
            $get = $get->whereMonth("tbl_customer_invoice.inv_date","=",date('m',strtotime($from))) 
                       ->whereYear("tbl_customer_invoice.inv_date","=",date('Y',strtotime($from)));
        }
        $get = $get->get();

    	$bal = 0;
    	foreach ($get as $key => $value) 
    	{
    		$bal += $value->inv_overall_price - $value->inv_payment_applied;
    	}
    	return $bal;
    }
    public static function getCm($shop_id, $from = '', $to = '', $year = false)
    {
        // Tbl_credit_memo::where("cm_shop_id", $shop_id)->update(['cm_status' => 1]);
        $get = Tbl_credit_memo::where("cm_shop_id", $shop_id)->whereBetween("cm_date",[$from, $to])
                                ->where('cm_status',0)
                                ->sum('cm_amount');
        $bal = $get;
        return $bal;
    }
    public static function getPaid($shop_id, $from = '', $to = '', $year = false)
    {
    	$get = Tbl_customer_invoice::rpline()->where("inv_shop_id", $shop_id)
                                   
                                    ->whereBetween("tbl_customer_invoice.inv_date",[$from, $to])
                                   ->groupBy('inv_id');
        // if($year)
        // {
        //     $get = $get->whereYear("tbl_receive_payment.rp_date","=",date('Y',strtotime($from)));
        // }
        // else
        // {
        //     $get = $get->whereMonth("tbl_receive_payment.rp_date","=",date('m',strtotime($from))) 
        //                ->whereYear("tbl_receive_payment.rp_date","=",date('Y',strtotime($from)));
        // }
        //                            dd($from, $to);
        $get = $get->get();
        // dd($get);
    	$bal = 0;
    	foreach ($get as $key => $value) 
    	{
    		$bal += $value->inv_payment_applied;
    	}
    	return $bal;
    }
    public static function getReferenceNumber($shop_id, $customer_id)
    {
        $return_ref_num = "";

        $count_customer = Tbl_customer_invoice::where('inv_shop_id',$shop_id)->where('inv_customer_id',$customer_id)->orderBy('inv_id','DESC')->first();
        $chk_refnum = $count_customer['inv_cheque_ref_no'];
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
	public static function get_open_ar_total_amount($shop_id)
    {
        $price = 0;
        $data = Tbl_customer_invoice::where("inv_shop_id",$shop_id)->where("inv_is_paid",0)->get();
        if(isset($data))
        {
            foreach ($data as $key => $value) 
            {
            	$amount = $value->inv_overall_price - $value->inv_payment_applied;
                $price += $amount;
            }            
        }
        return $price;
    }
    public static function get_total_amount_perwh($shop_id, $warehouse_id)
    {
    	$amount = 0;
        $price = 0;
        $data = Tbl_customer_invoice::perWarehouse("inv_shop_id",$shop_id)->where("inv_is_paid",0)->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->get();
        if(isset($data))
        {
            foreach ($data as $key => $value) 
            {
            	$amount = $value->inv_overall_price - $value->inv_payment_applied;
                $price += $amount;
            }            
        }
        return $price;
    }
    public static function count_perwh($shop_id, $warehouse_id)
    {
         return Tbl_customer_invoice::perWarehouse("inv_shop_id",$shop_id)->where("inv_is_paid",0)->where('tbl_acctg_transaction.transaction_warehouse_id',$warehouse_id)->count();
    }
    public static function count_open_ar($shop_id)
    {
        return Tbl_customer_invoice::where("inv_shop_id", $shop_id)->where("inv_is_paid",0)->count();
    }
    public static function get_total_amount($shop_id, $tab)
    {
    	$check_allow_transaction = AccountingTransaction::settings($shop_id, 'allow_transaction');
    	$si = Tbl_customer_invoice::where("inv_shop_id",$shop_id)->where("is_sales_receipt", 0);

        $total = 0;
        if($tab == 'open')
        {
            $si = $si->where('inv_is_paid', 0)->get();
            if(count($si) > 0)
	        {
	            foreach ($si as $key => $value) 
	            {
	               $total += $value->inv_overall_price - $value->inv_payment_applied;

	            }            
	        }
        }
        elseif($tab == 'closed')
        {
            $si = $si->where('inv_is_paid', 1)->get();
            if(count($si) > 0)
	        {
	            foreach ($si as $key => $value) 
	            {
	               $total += $value->inv_overall_price;

	            }            
	        }
        }
        else
        {
            $si = $si->get();
            if(count($si) > 0)
	        {
	            foreach ($si as $key => $value) 
	            {
	               $total += $value->inv_overall_price;

	            }            
	        }
        }
        
        if($check_allow_transaction == 1)
        {
            $si = Tbl_customer_invoice::where("inv_shop_id",$shop_id)->where("is_sales_receipt", 0);
            $si = AccountingTransaction::acctg_trans($shop_id, $si,'sales_invoice');

            $data = null;
	        $total = null;
	        if($tab == 'open')
	        {
	            $si = $si->where('inv_is_paid', 0)->get();

	            if(count($si) > 0)
		        {
		        	foreach ($si as $key_si => $value_si)
		            {
		                $data[$value_si->inv_id] = $value->inv_overall_price - $value->inv_payment_applied;
		            }
		            foreach ($data as $key => $value)
		            {
		                $total += $value;
		            }    
		        }
	        }
	        elseif($tab == 'closed')
	        {
	            $si = $si->where('inv_is_paid', 1)->get();
	            if(count($si) > 0)
		        {
		        	foreach ($si as $key_si => $value_si)
		            {
		                $data[$value_si->inv_id] = $value_si->inv_overall_price;
		            }
		            foreach ($data as $key => $value)
		            {
		                $total += $value;
		            }    
		        }
	        }
	        else
	        {
	            $si = $si->get();
	            if(count($si) > 0)
		        {
		        	foreach ($si as $key_si => $value_si)
		            {
		                $data[$value_si->inv_id] = $value_si->inv_overall_price;
		            }
		            foreach ($data as $key => $value)
		            {
		                $total += $value;
		            }    
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
    public static function vat_sales($shop_id, $transactionline)
    {
        $subtotal = 0;
        foreach ($transactionline as $key => $value)
        {
            $subtotal += $value->invline_amount / 1.12;
        }
        return $subtotal;
    }
    public static function infotax($shop_id, $transactionline)
    {
        $data = Self::infoline($shop_id, $transactionline);

        $tax =  0;
        $no_tax =  0;
        $total_tax =  0;
        foreach ($data as $key => $value)
        {
            if($value->taxable == 1)
            {
                //$tax += $value->invline_amount * 0.12;
                $tax += ($value->invline_amount) * 0.12 ;
            }
            else
            {
            	$no_tax = 0;
            	//$no_tax += ($value->invline_amount / 1.12 ) * 0.12 ;
            }
            $total_tax = $tax + $no_tax; 

        }
        return $total_tax;
    }
	public static function countTransaction($shop_id, $customer_id)
	{
		return Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("est_customer_id",$customer_id)->where("est_status","!=","closed")->count();
	}
	public static function count_tax($invoice_id)
    {
        return Tbl_customer_invoice_line::where("invline_inv_id", $invoice_id)->where('taxable', 1)->count(); 
    }
	public static function countUndeliveredSalesInvoice($shop_id, $customer_id)
	{
		return Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('inv_customer_id', $customer_id)->where('is_sales_receipt',0)->where('item_delivered',0)->count();
	}
	public static function getUndeliveredSalesInvoice($shop_id, $customer_id)
	{
		return Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('inv_customer_id', $customer_id)->where('is_sales_receipt',0)->where('item_delivered',0)->get();
	}
	public static function countDeliveredSalesInvoice($shop_id)
	{
		return Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('is_sales_receipt',0)->where('item_delivered',1)->where('replenished',0)->count();
	}
	public static function getDeliveredSalesInvoice($shop_id)
	{
		return Tbl_customer_invoice::customer()->where('inv_shop_id', $shop_id)->where('is_sales_receipt',0)->where('item_delivered',1)->where('replenished',0)->get();
	}
	public static function transaction_data($shop_id, $trans_id)
	{
		return Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("est_id",$trans_id)->first();
	}
	public static function transaction_data_item($trans_id)
	{
		return Tbl_customer_estimate_line::estimate_item()->um()->where("estline_est_id",$trans_id)->get();		
	}
	public static function get($shop_id, $paginate = null, $search_keyword = null, $status = null)
	{
		$data = Tbl_customer_invoice::customer()->where('inv_shop_id', $shop_id)->where('is_sales_receipt',0)->groupBy("inv_id")->orderBy("tbl_customer_invoice.date_created", "desc");

        $data = AccountingTransaction::acctg_trans($shop_id, $data, 'sales_invoice');

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
			if($status == 'open')
			{
				$tab = 0;
			}
			if($status == 'closed')
			{
				$tab = 1;
			}
			$data->where('inv_is_paid',$tab);
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
	public static function info($shop_id, $invoice_id)
	{
		return Tbl_customer_invoice::paymentmethod()->customer()->where("inv_shop_id", $shop_id)->where("inv_id", $invoice_id)->first();
	}
    public static function customer_address($customer_id)
    {
        $data = Tbl_customer_address::country()->where('customer_id', $customer_id)->where('purpose','billing')->first();
        $return = null;
        if($data)
        {
            $return = $data->customer_street." ".$data->customer_state." ".$data->customer_city." ".$data->country_name.", ".$data->customer_zipcode;
        }
        return $return;
    }
	public static function info_item($invoice_id)
	{
		$data = Tbl_customer_invoice_line::invoice_item()->um()->binLocation()->where("invline_inv_id", $invoice_id)->get();
		foreach($data as $key => $value) 
        {
            $qty = UnitMeasurement::um_qty($value->invline_um);

            $total_qty = $value->invline_qty * $qty;
            $data[$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->invline_um);
        }

		return $data;
	}

    public static function salesrep_info($sales_rep_id)
    {
        return Tbl_sales_representative::where('sales_rep_id', $sales_rep_id)->first();
    }
	public static function postUpdate($invoice_id, $user_info, $insert, $insert_item = array())
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
	        $ins['inv_terms_id']                 = $insert['customer_terms'];
	        $ins['inv_date']                     = date("Y-m-d", strtotime($insert['transaction_date']));
	        $ins['inv_due_date']                 = date("Y-m-d", strtotime($insert['transaction_duedate']));
	        $ins['ewt']                          = $insert['customer_ewt'];
	        $ins['inv_discount_type']            = $insert['customer_discounttype'];
	        $ins['inv_discount_value']           = $insert['customer_discount'];
	        $ins['taxable']                      = $insert['customer_tax'];
	        $ins['inv_message']                  = $insert['customer_message'];
	        $ins['inv_memo']                     = $insert['customer_memo'];
	        $ins['date_created']                 = Carbon::now();
	        $ins['bank_interest']				 = $insert['customer_bank_interest'] != '' ? $insert['customer_bank_interest'] : null;
	        $ins['inv_payment_method']           = $insert['transaction_payment_method'];
	        $ins['inv_cheque_ref_no']            = $insert['transaction_ref_no'];
            $ins['inv_sales_rep_id']             = $insert['inv_sales_rep_id'];

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

	        /* INSERT INVOICE HERE */
	        Tbl_customer_invoice::where('inv_id', $invoice_id)->update($ins);

	        /* Transaction Journal */
	        $entry["reference_module"]  = 'invoice';
	        $entry["reference_id"]      = $invoice_id;
	        $entry["name_id"]           = $insert['customer_id'];
	        $entry["total"]             = $subtotal_price;
            $entry["txn_date"]          = $ins['inv_date'];
	        $entry["vatable"]           = $tax;
	        $entry["discount"]          = $discount;
	        $entry["ewt"]               = $ewt;

			Tbl_customer_invoice_line::where("invline_inv_id", $invoice_id)->delete();
	        $return = Self::insertline($invoice_id, $insert_item, $entry, $user_info, true);
	        $return = $invoice_id;

	        if(CustomerWIS::settings($user_info->shop_id) == 0)
			{
		        /* UPDATE INVENTORY HERE */
				$warehouse_id = Warehouse2::get_current_warehouse($user_info->shop_id);
				AccountingTransaction::inventory_consume_update($user_info->shop_id, $warehouse_id, 'sales_invoice', $invoice_id); 
				AccountingTransaction::consume_inventory($user_info->shop_id, $warehouse_id, $insert_item, 'sales_invoice', $invoice_id, 'Consume upon creating SALES INVOICE '.$ins['transaction_refnum']);
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
		$val = AccountingTransaction::customer_validation($insert, $insert_item, 'sales_invoice');
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
	        $ins['inv_terms_id']                 = $insert['customer_terms'];
	        $ins['inv_date']                     = date("Y-m-d", strtotime($insert['transaction_date']));
	        $ins['inv_due_date']                 = date("Y-m-d", strtotime($insert['transaction_duedate']));
	        $ins['ewt']                          = $insert['customer_ewt'];
	        $ins['inv_discount_type']            = $insert['customer_discounttype'];
	        $ins['inv_discount_value']           = $insert['customer_discount'];
	        $ins['taxable']                      = $insert['customer_tax'];
	        $ins['inv_message']                  = $insert['customer_message'];
	        $ins['inv_memo']                     = $insert['customer_memo'];
	        $ins['date_created']                 = Carbon::now();
	        $ins['bank_interest']				 = $insert['customer_bank_interest'] != '' ? $insert['customer_bank_interest'] : null;
	        $ins['inv_payment_method']           = $insert['transaction_payment_method'];
	        $ins['inv_cheque_ref_no']            = $insert['transaction_ref_no'];	
            $ins['inv_sales_rep_id']             = $insert['inv_sales_rep_id'];

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

	        /* INSERT INVOICE HERE */
	        $invoice_id = Tbl_customer_invoice::insertGetId($ins);
	        // $invoice_id = 0;

	        /* Transaction Journal */
	        $entry["reference_module"]  = 'invoice';
	        $entry["reference_id"]      = $invoice_id;
	        $entry["name_id"]           = $insert['customer_id'];
            $entry["txn_date"]          = $ins['inv_date'];
	        $entry["total"]             = $subtotal_price;
	        $entry["vatable"]           = $tax;
	        $entry["discount"]          = $discount;
	        $entry["ewt"]               = $ewt;
	        
	        $return = Self::insertline($invoice_id, $insert_item, $entry, $user_info, false);
	        $return = $invoice_id;

			if(CustomerWIS::settings($user_info->shop_id) == 0)
			{
				/*$settings_auto_post_transaction = AccountingTransaction::settings($user_info->shop_id, 'auto_post_transaction');
				if($settings_auto_post_transaction == 1)
            	{
	                $update_status['transaction_status'] = 'posted';
	                Tbl_customer_invoice::where('inv_id', $invoice_id)->update($update_status);*/
					$warehouse_id = Warehouse2::get_current_warehouse($user_info->shop_id);
					AccountingTransaction::consume_inventory($user_info->shop_id, $warehouse_id, $insert_item, 'sales_invoice', $invoice_id, 'Consume upon creating SALES INVOICE '.$ins['transaction_refnum']);
				/*}*/
			}
		}
		else
		{
			$return = $val;
		}		

        return $return; 
	}

    public static function applied_transaction($shop_id, $transaction_id = 0, $for_update = false)
    {
        $applied_transaction = Session::get('applied_transaction_si');
        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value) 
            {
            	AccountingTransaction::checkSolineQty($key, $transaction_id, $for_update);
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
    		$transaction_data['transaction_ref_name'] = "sales_invoice";
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
	public static function insertline($invoice_id, $insert_item, $entry, $user_info, $for_update)
	{
		// dd($invoice_id, $insert_item, $entry, $user_info, $for_update, $id_before_delete);
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

				$itemline['invline_inv_id'] 			= $invoice_id;
				$itemline['invline_service_date'] 		= $value['item_servicedate'];
				$itemline['invline_item_id'] 			= $value['item_id'];
				$itemline['invline_description'] 		= $value['item_description'];
				$itemline['invline_sub_wh_id'] 			= $value['item_sub_warehouse'] != "" ? $value['item_sub_warehouse'] : null;
				$itemline['invline_um'] 				= $value['item_um'];
				$itemline['invline_orig_qty']			= $value['item_qty'];
				$itemline['invline_qty'] 				= $value['item_qty'];
				$itemline['invline_rate'] 				= $value['item_rate'];
				$itemline['invline_discount'] 			= $discount;
				$itemline['invline_discount_type'] 		= $discount_type;
				$itemline['invline_discount_remark'] 	= $value['item_remarks'];
				$itemline['taxable'] 					= $value['item_taxable'] != null ? $value['item_taxable'] : 0;
				$itemline['invline_amount'] 			= $value['item_amount'];
				$itemline['date_created'] 				= Carbon::now();
				$itemline['invline_refname'] 			= $value['item_refname'];
				$itemline['invline_refid']  			= $value['item_refid'];

				$cus_inv_line_id = Tbl_customer_invoice_line::insertGetId($itemline);

				array_push($id_not_delete, $cus_inv_line_id);

				AccountingTransaction::check_update_sales_price($user_info->shop_id, $user_info->user_id, $value['item_id'], $value['item_rate']);

				// if(CustomerWIS::settings($user_info->shop_id) == 0 && $itemline['invline_refid'] && $itemline['invline_refname'])
				// {
					$qty = Tbl_quantity_monitoring::where('qty_transaction_id', $invoice_id)->where('qty_item_id', $value['item_id'])->where('qty_shop_id', $user_info->shop_id)->first();

	 				if($qty == null || $for_update == false)	
	 				{
	 					$insert_qty_item['qty_item_id']              = $itemline['invline_item_id'];
	                    $insert_qty_item['qty_transaction_id']       = $invoice_id;
	                    $insert_qty_item['qty_transaction_name']     = 'sales_invoice';
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
		                Tbl_quantity_monitoring::where('qty_transaction_id', $invoice_id)->where('qty_item_id', $value['item_id'])->update($insert_qty_item);
		 			}
				// }
			}

			if($id_not_delete != null)
			{
				Tbl_quantity_monitoring::where("qty_transaction_id", $invoice_id)->whereNotIn("qty_transactionline_id", $id_not_delete)->where('qty_transaction_name', 'sales_invoice')->delete();
			}

			$return = AccountingTransaction::entry_data($entry, $insert_item);
		}

		return $return;
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
    public static function get_customer_invoice($shop_id, $customer_id)
    {
        $data = Tbl_customer_invoice::appliedPayment($shop_id)->byCustomer($shop_id, $customer_id)->where("inv_is_paid", 0)->where("is_sales_receipt",0)->get()->toArray();
        return $data;
    }

    public static function get_customer_invoice_rp($shop_id, $customer_id, $rcvpayment_id)
    {
        $inv_in_rcvpayment = Tbl_receive_payment_line::select("rpline_reference_id")->where("rpline_reference_name", 'invoice')
                            ->where("rpline_rp_id", $rcvpayment_id)->get()->toArray();

        $data = Tbl_customer_invoice::appliedPayment($shop_id)->byCustomer($shop_id, $customer_id)
                ->rcvPayment($rcvpayment_id, $inv_in_rcvpayment)->orderBy("inv_id")->where("is_sales_receipt",0)->get()->toArray();
        return $data;
    }
    public static function get_best_seller($shop_id, $warehouse_id = 0, $date_from = '', $date_to = '')
    {
		$data = Tbl_customer_invoice::invoice_item()->acctg_trans()->where('inv_shop_id', $shop_id);
        $get_main_warehouse = Warehouse2::get_main_warehouse($shop_id);
        if($get_main_warehouse == $warehouse_id && $shop_id == 81)
        {
            $_warehouse  = Warehouse2::get_branches($shop_id);
            $data = $data->whereIn('transaction_warehouse_id',$_warehouse);
        }
        else
        {
            $data = $data->where('transaction_warehouse_id',$warehouse_id);
        }
        
		if($date_from && $date_to)
		{
			$data = $data->whereBetween("tbl_customer_invoice.inv_date",[$date_from, $date_to]);
		}
		$data = $data->groupBy("invline_id")->get();

		$return = null;
		$add = null;
		$um = null;
		foreach ($data as $key => $value) 
		{
			$old = isset($add[$value->invline_item_id]) ? $add[$value->invline_item_id] : 0;
			$add[$value->invline_item_id] = (UnitMeasurement::um_qty($value->invline_um) * $value->invline_orig_qty) + $old;
			$old_um = isset($um[$value->invline_item_id]) ? $um[$value->invline_item_id] : 0;
			$um[$value->invline_item_id] = UnitMeasurement::um_qty($value->invline_um) > UnitMeasurement::um_qty($old_um) ? $value->invline_um : $old_um;
		}
		foreach ($data as $key => $value) 	
		{
			$return[$value->invline_item_id] = new stdClass;
			$return[$value->invline_item_id]->item_name = $value->item_name;
			$return[$value->invline_item_id]->item_qty = UnitMeasurement::um_view($add[$value->invline_item_id], $value->item_measurement_id, $um[$value->invline_item_id]);
			$return[$value->invline_item_id]->sales_price = $value->item_price;
			$return[$value->invline_item_id]->item_amount = $add[$value->invline_item_id] * $value->item_price;
			$return[$value->invline_item_id]->qty = $add[$value->invline_item_id];
		}
		if(count($return) > 0)
		{
			usort($return, function($a, $b)
	        {
	            if($a->qty == $b->qty) return 0;
	            return $a->qty < $b->qty ? 1 : -1;
	        });
		}

		return $return;
	}
	public static function get_best_seller_by_item($shop_id, $warehouse_id = 0, $date_from = '', $date_to = '', $filter_by)
    {
		$data = Tbl_customer_invoice::invoice_item()->acctg_trans()->where('inv_shop_id', $shop_id)->where("transaction_warehouse_id", $warehouse_id,$filter_by);
		if($date_from && $date_to)
		{
			$data = $data->whereBetween("tbl_customer_invoice.inv_date",[$date_from, $date_to]);
		}
		$data = $data->groupBy("invline_id")->get();

		$return = null;
		$add = null;
		$um = null;
		foreach ($data as $key => $value) 
		{
			$old = isset($add[$value->invline_item_id]) ? $add[$value->invline_item_id] : 0;
			$add[$value->invline_item_id] = (UnitMeasurement::um_qty($value->invline_um) * $value->invline_orig_qty) + $old;
			$old_um = isset($um[$value->invline_item_id]) ? $um[$value->invline_item_id] : 0;
			$um[$value->invline_item_id] = UnitMeasurement::um_qty($value->invline_um) > UnitMeasurement::um_qty($old_um) ? $value->invline_um : $old_um;
		}
		
		foreach ($data as $key => $value) 
		{
			
			if($filter_by == 'all' || $filter_by == null){
				$return[$value->invline_item_id] = new stdClass;
				$return[$value->invline_item_id]->item_name = $value->item_name;
				$return[$value->invline_item_id]->item_qty = UnitMeasurement::um_view($add[$value->invline_item_id], $value->item_measurement_id, $um[$value->invline_item_id]);
				$return[$value->invline_item_id]->sales_price = $value->item_price;
				$return[$value->invline_item_id]->item_amount = $add[$value->invline_item_id] * $value->item_price;
				$return[$value->invline_item_id]->qty = $add[$value->invline_item_id];
				
			}else{
				if($filter_by){
					$item = Self::get_name($filter_by,$value->item_name);
					if ($return == null ) {
						$return[$value->invline_item_id] = new stdClass;
						$return[$value->invline_item_id]->item_name = $item;
						$return[$value->invline_item_id]->item_qty = UnitMeasurement::um_view($add[$value->invline_item_id], $value->item_measurement_id, $um[$value->invline_item_id]);
						$return[$value->invline_item_id]->sales_price = $value->item_price;
						$return[$value->invline_item_id]->item_amount = $add[$value->invline_item_id] * $value->item_price;
						$return[$value->invline_item_id]->qty = $add[$value->invline_item_id];
						
					}
					else{
						// dd($return);
						$has = false;
						foreach ($return as $key => $ret_val){
							if($ret_val->item_name == $item){
								$ret_val->qty += $add[$value->invline_item_id];
								$ret_val->item_amount += $add[$value->invline_item_id] * $value->item_price;
								$add[$value->invline_item_id] = (UnitMeasurement::um_qty($ret_val->qty));
								$ret_val->item_qty = UnitMeasurement::um_view($ret_val->qty, $value->item_measurement_id, $um[$value->invline_item_id]);
								$has = true;
							}
						}
						if($has == false){
							$return[$value->invline_item_id] = new stdClass;
							$return[$value->invline_item_id]->item_name = $item;
							$return[$value->invline_item_id]->item_qty = UnitMeasurement::um_view($add[$value->invline_item_id], $value->item_measurement_id, $um[$value->invline_item_id]);
							$return[$value->invline_item_id]->sales_price = $value->item_price;
							$return[$value->invline_item_id]->item_amount = $add[$value->invline_item_id] * $value->item_price;
							$return[$value->invline_item_id]->qty = $add[$value->invline_item_id];
						}
					}
				}
			}
		}
		// dd($return);
		
		// if($filter_by == 'pattern'){
		// 	dd($return);
		// }
		if(count($return) > 0)
		{
			usort($return, function($a, $b)
	        {
	            if($a->qty == $b->qty) return 0;
	            return $a->qty < $b->qty ? 1 : -1;
	        });
		}

		return $return;
	}
	public static function get_name($type,$name){
		$item_name = null;
		$item = explode('-',$name);
		if($type == 'pattern'){
			$item_name = isset($item[0]) ? $item[0] : $item[0];
		}else{
			$item_name = $type == 'color' ? (isset($item[1]) ? $item[1] : $item[0])  : (isset($item[2]) ? $item[2] : $item[0]);
		}
		return $item_name;
	}
    public static function getBalancePerSI($shop_id, $inv_id)
    {        
        $si = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('is_sales_receipt',0)->where('inv_id', $inv_id)->first();

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
    public static function check_format_bank_interest($string)
    {
    	$return = null;

		if(!isset(explode("-", $string)[4]))
		{
			$return = "Please complete the details of Bank Interest. Check your format.";
		}
		if(!$return)
		{
			$explode = explode("-", $string);
			if(isset($explode[4]))
			{
				$interest = (int) filter_var($explode[0], FILTER_SANITIZE_NUMBER_INT);
				$amount = (double) filter_var($explode[1], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
				if($interest <= 0)
				{
					$return = "Please complete the details of Bank Interest. Check your format.";
				}
				if($amount <= 0)
				{
					$return = "Please complete the details of Bank Interest. Check your format.";
				}
			}
		}
		return $return;
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