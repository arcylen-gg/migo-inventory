<?php
namespace App\Globals;

use App\Globals\Accounting;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_temp_customer_invoice;
use App\Models\Tbl_temp_customer_invoice_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use DB;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\carbon;

/**
 * Invoice Module - all customer invoice related module
 *
 * @author Bryan Kier Aradanas
 */

class Tablet_invoice
{

	/**
	 * Creating a New Customer Invoice
	 *
	 * @param array  $customer_info 		(customer_id => '', customer_email => '' )
	 * @param array  $invoice_info  		(invoice_terms => '', invoice_date => '', invoice_due => '', billing_address => '')
	 * @param array  $invoice_other_info    (invoice_msg => '', invoice_memo => '')
	 * @param array  $item_information    	([0]item_service_date => '', [0]item_id => '', [0]item_description => '', [0]quantity => '', 
	 *										 [0]rate => '', [0]discount => '', [0]discount_remark => '', [0]amount => '')
	 * @param array  $total_information    	(total_item_price => '', total_addons => [[0]label => '', [0]value => ''], 
	 *										 total_discount_type => '', total_discount_value => '', total_overall_price => '')
	 */
	public static function postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info)
	{
        $insert['inv_shop_id']                  = Tablet_invoice::getShopId();  
		$insert['inv_customer_id']              = $customer_info['customer_id'];        
        $insert['inv_customer_email']           = $customer_info['customer_email'];
        $insert['new_inv_id']                   = $invoice_info['new_inv_id'];
        $insert['inv_customer_billing_address'] = $invoice_info['billing_address'];
        $insert['inv_terms_id']                 = $invoice_info['invoice_terms_id'];
        $insert['inv_date']                     = $invoice_info['invoice_date'];
        $insert['inv_due_date']                 = $invoice_info['invoice_due'];
        $insert['inv_subtotal_price']           = $total_info['total_subtotal_price'];
        $insert['ewt']                          = $total_info['ewt'];
        $insert['inv_discount_type']            = $total_info['total_discount_type'];
        $insert['inv_discount_value']           = $total_info['total_discount_value'];
        $insert['taxable']                      = $total_info['taxable'];
        $insert['inv_overall_price']            = $total_info['total_overall_price'];
        $insert['inv_message']                  = $invoice_other_info['invoice_msg'];
        $insert['inv_memo']                     = $invoice_other_info['invoice_memo'];
        $insert['date_created']                 = Carbon::now();       

        $invoice_id = Tbl_temp_customer_invoice::insertGetId($insert);

        Tablet_invoice::insert_invoice_line($invoice_id, $item_info);

        return $invoice_id;
	}

    public static function updateInvoice($invoice_id, $customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info)
    {
        $update['inv_customer_id']              = $customer_info['customer_id'];        
        $update['inv_customer_email']           = $customer_info['customer_email'];
        $update['new_inv_id']                   = $invoice_info['new_inv_id'];
        $update['inv_customer_billing_address'] = $invoice_info['billing_address'];
        $update['inv_terms_id']                 = $invoice_info['invoice_terms_id'];
        $update['inv_date']                     = $invoice_info['invoice_date'];
        $update['inv_due_date']                 = $invoice_info['invoice_due'];
        $update['inv_subtotal_price']           = $total_info['total_subtotal_price'];
        $update['ewt']                          = $total_info['ewt'];
        $update['inv_discount_type']            = $total_info['total_discount_type'];
        $update['inv_discount_value']           = $total_info['total_discount_value'];
        $update['taxable']                      = $total_info['taxable'];
        $update['inv_overall_price']            = $total_info['total_overall_price'];
        $update['inv_message']                  = $invoice_other_info['invoice_msg'];
        $update['inv_memo']                     = $invoice_other_info['invoice_memo'];   

        Tbl_temp_customer_invoice::where("inv_id", $invoice_id)->update($update);

        Tbl_temp_customer_invoice_line::where("invline_inv_id", $invoice_id)->delete();
        Tablet_invoice::insert_invoice_line($invoice_id, $item_info);

        return $invoice_id;
    }

    public static function insert_invoice_line($invoice_id, $item_info)
    {
        foreach($item_info as $key => $item_line)
        {
            if($item_line)
            {
                $discount = $item_line['discount'];
                if(strpos($discount, '%'))
                {
                    $discount = substr($discount, 0, strpos($discount, '%')) / 100;
                }


                $insert_line['invline_inv_id']          = $invoice_id;
                $insert_line['invline_service_date']    = $item_line['item_service_date'];
                $insert_line['invline_item_id']         = $item_line['item_id'];
                $insert_line['invline_description']     = $item_line['item_description'];
                $insert_line['invline_um']              = $item_line['um'];
                $insert_line['invline_qty']             = $item_line['quantity'];
                $insert_line['invline_rate']            = $item_line['rate'];
                $insert_line['invline_discount']        = $discount;
                $insert_line['invline_discount_remark'] = $item_line['discount_remark'];
                $insert_line['taxable']                 = $item_line['taxable'];
                $insert_line['invline_amount']          = $item_line['amount'];
                $insert_line['date_created']            = Carbon::now();

                Tbl_temp_customer_invoice_line::insert($insert_line);
            }
        }
    }

    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
}
