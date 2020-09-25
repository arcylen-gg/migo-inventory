<?php
namespace App\Globals;

use App\Globals\Accounting;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_estimate_line;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Globals\AuditTrail;
use DB;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\carbon;

/**
 * estimate Module - all customer estimate related module
 *
 * @author Arcylen Gutierrez C- Bry
 */

class Estimate
{
    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

	/**
	 * Creating a New Customer estimate
	 *
	 * @param array  $customer_info 		(customer_id => '', customer_email => '' )
	 * @param array  $estimate_info  		(estimate_terms => '', estimate_date => '', estimate_due => '', billing_address => '')
	 * @param array  $estimate_other_info    (estimate_msg => '', estimate_memo => '')
	 * @param array  $item_info   	        ([0]item_service_date => '', [0]item_id => '', [0]item_description => '', [0]quantity => '', 
	 *										 [0]rate => '', [0]discount => '', [0]discount_remark => '', [0]amount => '')
	 * @param array  $total_info   	        (total_item_price => '', total_addons => [[0]label => '', [0]value => ''], 
	 *										 total_discount_type => '', total_discount_value => '', total_overall_price => '')
	 */
    public static function update_all_estimate($items, $inv_id = 0)
    {
        foreach($items as $key => $value) 
        {
            $update["copy_to_inv_id"] = $inv_id;
            $update["est_status"] = 'closed';

            Tbl_customer_estimate::where("est_id",$value['estline_est_id'])->update($update);
        }
        //update invoice for reference
        // $inv_item = Tbl_customer_invoice_line::where("invline_inv_id",$inv_id)->get();
        
    }
	public static function postEstimate($customer_info, $estimate_info, $estimate_other_info, $item_info, $total_info, $is_sales_order = false)
	{
        /* SUBTOTAL */
        $subtotal_price = collect($item_info)->sum('amount');

        /* DISCOUNT */
        $discount = $total_info['total_discount_value'];
        if($total_info['total_discount_type'] == 'percent') $discount = (convertToNumber($total_info['total_discount_value']) / 100) * $subtotal_price;

        /* TAX */
        $tax = (collect($item_info)->where('taxable', '1')->sum('amount')) * 0.12;

        /* EWT */
        $ewt = $subtotal_price*convertToNumber($total_info['ewt']);

        /* OVERALL TOTAL */
        $overall_price  = convertToNumber($subtotal_price) - $ewt - $discount + $tax;

        $insert['est_shop_id']                  = Estimate::getShopId();  
		$insert['est_customer_id']              = $customer_info['customer_id'];        
        $insert['est_customer_email']           = $customer_info['customer_email'];
        $insert['est_customer_billing_address'] = $estimate_info['billing_address'];
        $insert['est_date']                     = date("Y-m-d", strtotime($estimate_info['estimate_date']));
        $insert['est_exp_date']                 = date("Y-m-d", strtotime($estimate_info['estimate_due']));
        $insert['est_subtotal_price']           = $subtotal_price;
        $insert['ewt']                          = $total_info['ewt'];
        $insert['est_discount_type']            = $total_info['total_discount_type'];
        $insert['est_discount_value']           = $total_info['total_discount_value'];
        $insert['taxable']                      = $total_info['taxable'];
        $insert['est_overall_price']            = $subtotal_price;
        $insert['est_message']                  = $estimate_other_info['estimate_msg'];
        $insert['est_memo']                     = $estimate_other_info['estimate_memo'];
        $insert['date_created']                 = Carbon::now();    

        $transaction = "estimate";
        if($is_sales_order == true)
        {
            $insert['is_sales_order'] = 1;    
            $insert['est_status'] = 'accepted';   
            $transaction = "sales_order"; 
        }
       
        $estimate_id = Tbl_customer_estimate::insertGetId($insert);

        Estimate::insert_estimate_line($estimate_id, $item_info);

        $est_data = AuditTrail::get_table_data("tbl_customer_estimate","est_id",$estimate_id);
        AuditTrail::record_logs("Added",$transaction,$estimate_id,"",serialize($est_data));

        return $estimate_id;
	}

  
    public static function updateEstimate($estimate_id, $customer_info, $estimate_info, $estimate_other_info, $item_info, $total_info, $is_sales_order = false)
    {        
        $old = AuditTrail::get_table_data("tbl_customer_estimate","est_id",$estimate_id);

        /* SUBTOTAL */
        $subtotal_price = collect($item_info)->sum('amount');
        
        /* DISCOUNT */
        $discount = $total_info['total_discount_value'];
        if($total_info['total_discount_type'] == 'percent') $discount = (convertToNumber($total_info['total_discount_value']) / 100) * $subtotal_price;

        /* TAX */
        $tax = (collect($item_info)->where('taxable', '1')->sum('amount')) * 0.12;

        /* EWT */
        $ewt = $subtotal_price*convertToNumber($total_info['ewt']);

        /* OVERALL TOTAL */
        $overall_price  = convertToNumber($subtotal_price) - $ewt - $discount + $tax;
        
        $update['est_customer_id']              = $customer_info['customer_id'];        
        $update['est_customer_email']           = $customer_info['customer_email'];
        $update['est_customer_billing_address'] = $estimate_info['billing_address'];
        $update['est_date']                     = date("Y-m-d", strtotime($estimate_info['estimate_date']));
        $update['est_exp_date']                 = date("Y-m-d", strtotime($estimate_info['estimate_due']));
        $update['est_subtotal_price']           = $subtotal_price;
        $update['ewt']                          = $total_info['ewt'];
        $update['est_discount_type']            = $total_info['total_discount_type'];
        $update['est_discount_value']           = $total_info['total_discount_value'];
        $update['taxable']                      = $total_info['taxable'];
        $update['est_overall_price']            = $subtotal_price;
        $update['est_message']                  = $estimate_other_info['estimate_msg'];
        $update['est_memo']                     = $estimate_other_info['estimate_memo'];   

        
        $transaction = "estimate";
        if($is_sales_order == true)
        {
            $update['is_sales_order'] = 1;    
            $update['est_status'] = 'accepted';
            $transaction = "sales_order";
        }

        Tbl_customer_estimate::where("est_id", $estimate_id)->update($update);

        Tbl_customer_estimate_line::where("estline_est_id", $estimate_id)->delete();
        Estimate::insert_estimate_line($estimate_id, $item_info);
        
        
        $new = AuditTrail::get_table_data("tbl_customer_estimate","est_id",$estimate_id);
        AuditTrail::record_logs("Edited",$transaction,$estimate_id,serialize($old),serialize($new));

        return $estimate_id;
    }

    public static function insert_estimate_line($estimate_id, $item_info)
    {        
        foreach($item_info as $key => $item_line)
        {
            if($item_line)
            {
                /* DISCOUNT PER LINE */
                $discount       = $item_line['discount'];
                $discount_type  = 'fixed';
                if(strpos($discount, '%'))
                {   
                    $discount       = substr($discount, 0, strpos($discount, '%')) / 100;
                    $discount_type  = 'percent';
                }

                /* AMOUNT PER LINE */
                $amount = (convertToNumber($item_line['rate']) * convertToNumber($item_line['quantity'])) - $discount;

                $insert_line['estline_est_id']          = $estimate_id;
                $insert_line['estline_service_date']    = date("Y-m-d", strtotime($item_line['item_service_date']));
                $insert_line['estline_item_id']         = $item_line['item_id'];
                $insert_line['estline_description']     = $item_line['item_description'];
                $insert_line['estline_um']              = $item_line['um'];
                $insert_line['estline_qty']             = convertToNumber($item_line['quantity']);
                $insert_line['estline_rate']            = convertToNumber($item_line['rate']);
                $insert_line['estline_discount']        = $item_line['discount'];
                $insert_line['estline_discount_type']   = $discount_type;
                $insert_line['estline_discount_remark'] = $item_line['discount_remark'];
                $insert_line['taxable']                 = $item_line['taxable'];
                $insert_line['estline_amount']          = $amount;
                $insert_line['date_created']            = Carbon::now();

                Tbl_customer_estimate_line::insert($insert_line);
            }
        }

        return $insert_line;
    }

  
}
