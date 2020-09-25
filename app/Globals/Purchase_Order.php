<?php
namespace App\Globals;

use App\Globals\Accounting;
use App\Globals\AuditTrail;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Models\Tbl_chart_account_type;
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

class Purchase_Order
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
    public static function count_po($start_date, $end_date)
    {
         $po = Tbl_purchase_order::where("po_shop_id",Purchase_Order::getShopId())->whereBetween("date_created",array($start_date,$end_date))->where("po_is_billed",0)->count();
         return $po;
    }
    public static function get_po_amount($start_date, $end_date)
    {
        $price = 0;
        $po = Tbl_purchase_order::where("po_shop_id",Purchase_Order::getShopId())
                                ->whereBetween("date_created",array($start_date,$end_date))
                                ->where("po_is_billed",0)->get();
        if(isset($po))
        {
            foreach ($po as $key => $value) 
            {
               $price += $value->po_overall_price;
            }            
        }

        return $price;
    }
	public static function postOrder($vendor_info, $po_info, $po_other_info, $item_info, $total_info)
	{
        $insert['po_shop_id']                    = Purchase_Order::getShopId();        
        $insert['po_ap_account']                 = Purchase_Order::getApAccount();  
        $insert['po_vendor_id']                  = $vendor_info['po_vendor_id'];
        $insert['po_vendor_email']               = $vendor_info['po_vendor_email'];        
        $insert['po_billing_address']            = $po_info['billing_address'];
        $insert['po_terms_id']                   = $po_info['po_terms_id'];
        $insert['po_date']                       = $po_info['po_date'];
        $insert['po_due_date']                   = $po_info['po_due_date'];
        $insert['po_message']                    = $po_other_info['po_message'];
        $insert['po_memo']                       = $po_other_info['po_memo'];
        $insert['po_discount_type']              = $total_info['po_discount_type'];
        $insert['po_discount_value']             = $total_info['po_discount_value'];
        $insert['ewt']                           = $total_info['ewt'];
        $insert['taxable']                       = $total_info['taxable'];
        $insert['po_subtotal_price']             = $total_info['po_subtotal_price'];
        $insert['po_overall_price']              = $total_info['po_overall_price'];
        $insert['date_created']                  = Carbon::now();
        

        $po_id = Tbl_purchase_order::insertGetId($insert);

        $po_data = AuditTrail::get_table_data("tbl_purchase_order","po_id",$po_id);
        AuditTrail::record_logs("Added","purchase_order",$po_id,"",serialize($po_data));

        Purchase_Order::insert_po_line($po_id, $item_info);

        return $po_id;
	}
    public static function getApAccount()
    {
        return Tbl_chart_account_type::where("chart_type_name","Accounts Payable")->value("chart_type_id");
    }
    public static function updatePurchase($po_id, $vendor_info, $po_info, $po_other_info, $item_info, $total_info)
    {
        $old = AuditTrail::get_table_data("tbl_purchase_order","po_id",$po_id);

        $update['po_vendor_id']                  = $vendor_info['po_vendor_id'];        
        $update['po_billing_address']            = $po_info['billing_address'];
        $insert['po_vendor_email']               = $vendor_info['po_vendor_email'];
        $update['po_terms_id']                   = $po_info['po_terms_id'];
        $update['po_date']                       = $po_info['po_date'];
        $update['po_due_date']                   = $po_info['po_due_date'];
        $update['po_message']                    = $po_other_info['po_message'];
        $update['po_memo']                       = $po_other_info['po_memo'];
        $update['po_discount_type']              = $total_info['po_discount_type'];
        $update['po_discount_value']             = $total_info['po_discount_value'];
        $update['ewt']                           = $total_info['ewt'];
        $update['taxable']                       = $total_info['taxable'];
        $update['po_subtotal_price']             = $total_info['po_subtotal_price'];
        $update['po_overall_price']              = $total_info['po_overall_price'];  

        Tbl_purchase_order::where("po_id", $po_id)->update($update);


        $new = AuditTrail::get_table_data("tbl_purchase_order","po_id",$po_id);
        AuditTrail::record_logs("Edited","purchase_order",$po_id,serialize($old),serialize($new));

        Tbl_purchase_order_line::where("poline_po_id", $po_id)->delete();
        Purchase_Order::insert_po_line($po_id, $item_info);

        return $po_id;
    }

    public static function insert_po_line($po_id, $item_info)
    {
        foreach($item_info as $key => $item_line)
        {
            if($item_line)
            {
                $discount = $item_line['discount'];
                $discount_type  = 'fixed';
                if(strpos($discount, '%'))
                {
                    $discount = substr($discount, 0, strpos($discount, '%')) / 100;
                    $discount_type  = 'percent';
                }

                $insert_line['poline_po_id']           = $po_id;
                $insert_line['poline_service_date']    = $item_line['item_service_date'];
                $insert_line['poline_item_id']         = $item_line['item_id'];
                $insert_line['poline_description']     = $item_line['item_description'];
                $insert_line['poline_um']              = $item_line['um'];
                $insert_line['poline_qty']             = $item_line['quantity'];
                $insert_line['poline_orig_qty']        = $item_line['quantity'];
                $insert_line['poline_rate']            = $item_line['rate'];
                $insert_line['poline_discount']        = $discount;
                $insert_line['poline_discounttype']    = $discount_type;
                $insert_line['poline_discount_remark'] = $item_line['discount_remark'];
                $insert_line['taxable']                = $item_line['taxable'];
                $insert_line['poline_amount']          = $item_line['amount'];
                $insert_line['date_created']           = Carbon::now();

                Tbl_purchase_order_line::insert($insert_line);
            }
        }
    }

    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
}
