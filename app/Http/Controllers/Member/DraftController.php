<?php

namespace App\Http\Controllers\Member;

use Request;
use DB;
use Crypt;
use Carbon\Carbon;
use Session;
use App\Http\Controllers\Controller;
use App\Models\Tbl_country;
use App\Models\Tbl_customer;
use App\Models\Tbl_user;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_product;
use App\Models\Tbl_variant;
use App\Models\Tbl_product_vendor;
use App\Models\Tbl_order;
use App\Models\Tbl_order_item;
// use App\Http\Controllers\Orders\OrdersController;
use App\Globals\Item;
use App\Globals\Customer;
use App\Models\Tbl_order_refund;
use App\Models\Tbl_order_refund_item;

class DraftController extends Member
{
    public function index()
    {
        $data[] = null;
        $data["page"] = "Order";
        $data['orders'] = OrdersController::get_orders_with_view("draft",null, null);
        // echo $data['orders'];
        // return $data;
        // dd($data);
        return view('member.order.draft.index', $data);
    }
    public function view_draft($id)
    {
        $data['item_id'] = $id;
    	$user_info = Tbl_user::where("user_email", session('user_email'))->shop()->first();
    	$data['_item'] = Item::breakdown(Tbl_order_item::Sel($id, $user_info->user_shop)->select(DB::raw('tbl_variant.*, tbl_order_item.*, tbl_product.*, tbl_image.*, tbl_order_item.discount as item_discount, tbl_order_item.discount_reason as item_discount_reason, tbl_order_item.discount_var as item_discount_var'))->get());
    	$order = Tbl_order::where('tbl_order_id',$id)->first();
    	
    	if($order->customer_id)
    	{
    	    $customer_dd = Customer::info($order->customer_id, $user_info->user_shop, $id);
    	    $customer = view('member.order.customer_info', $customer_dd);
    	}
	    else
	    {
	        $customer = $this->customer_single_formatter($id);
	    }
	    
	    
    	$data['customer'] = $customer;
    	$data['_country'] = Tbl_country::orderBy('country_name','asc')->get();
    	$total = 0;
    	$currency = '₱';
        // dd($data['_item']);
        $deftotal = $data['_item']['total'];
        $total = $currency.' '.number_format($data['_item']['total'],2);
        if($deftotal == 0){
            $total = '--';
        }
        $tax = '--';
        $deftax = 0;
        $taxpercent = 0;
        $strtaxpercent = '';
        $IsTaxExemp = '0';
        $hasTax = 0;
        $discount = '--';
        $discounttext = '';
        $defdiscount = 0;
        $defnumdiscount = 0;
        $discount_reason = '';
        $discount_var = 'amount';
        $discount_currency = '₱';
        
        $defshipping = 0;
        $shipping = '--';
        $shipping_name = '';
        $notes = '';
        $reserveDate = '';
        $reserveDateTime = '';
        $total_ordering = '--';
        $tbl_order_id = 0;
        $discountamount = 0;
        $def_total_ordering = 0;
        // $customer = '';
        $customer_info = 'none';
        $customer_form = 'block';
        $customer_id = 0;
        $IsTaxExempStr = '';
        
        $tbl_order_id = $order->tbl_order_id;
        $taxpercent = $order->tax_percentage;
        $IsTaxExemp = $order->isTaxExempt;
        if($taxpercent > 0){
        	$strtaxpercent = 'VAT '.$taxpercent.' %';
        }
        $discount_var = $order->discount_var;
        $defnumdiscount = $order->discount;
        $shipping = $order->shipping_amount;
        $shipping_name = $order->shipping_name;
        if($shipping_name != ''){
        	$shipping_name = '<span class="light-brown">:&nbsp;'.$shipping_name.'</span>';
        }
        $isFreeShipping = $order->IsfreeShipping;
        $discount_reason = $order->discount_reason;
        if($discount_reason != ''){
        	$discount_reason = '<span class="light-brown">:&nbsp;'.$discount_reason.'</span>';
        }
        $notes = $order->notes;
        $reserve = $order->reserve_date;
        $reserveDate = date('Y-m-d', strtotime($reserve));
        $reserveDateTime = date('H:i:s a', strtotime($reserve));
        $tax = $deftotal * ($taxpercent / 100);
        // dd($tax);
        if($order->customer_id == 0){
            $IsTaxExemp = 0;
            $hasTax = 0;
            $strtaxpercent = '';
            $tax = 0;
        }
        $discounttext = $order->discount;
        if($discount_var == 'amount'){
            $discountamount = $order->discount;
        }
        else{
            $discount_currency = '%';
            $discountamount = $deftotal * ($order->discount / 100);
        }
        $total_ordering = ($deftotal + $shipping + $tax) - $discountamount;
        $def_total_ordering = $deftotal;
        $total_ordering = $currency.' '.number_format($total_ordering,2);
        
        
        if($IsTaxExemp == 1){
            $deftax = 0;
            $tax = '--';
            $IsTaxExempStr = 'Customer is tax exempt';
        }
        else{
        	$IsTaxExempStr = 'Tax : '.$taxpercent.'%';
            $hasTax = $order->hasTax;
            
            if($hasTax == 0){
                $tax = 0;
            }
            $deftax = $tax;
            
            if($tax == 0){
                $tax = '--';
            }
            else{
                $tax = $currency.' '.number_format($tax,2);
            }
        }
        
        $defdiscount = $discountamount;

        if($discountamount == 0){
            $discountamount = '--';
        }
        else{
            $discountamount = '- '.$currency.' '.number_format($discountamount,2);
        }
        $defshipping = $shipping;
        if($shipping == 0){
            $shipping = '--';
        }
        else{
            $shipping = $currency.' '.number_format($shipping,2);
        }

        $customer_id = $order->customer_id;
        // dd($customer_id);
        if($customer_id != 0){
            // $customer_dd = $this->customerData($customer_id);
            // $customer = view('member.order.customer_info', $customer_dd);
            $customer_info = 'block';
            $customer_form = 'none';
        }
        $refunded = Tbl_order_refund_item::where('tbl_order_id', $order->tbl_order_id)->get();
        $refunded_amount = 0;
        if($refunded)
        {
            foreach($refunded as $ref)
            {
                $refunded_amount += ($ref->item_amount * $ref->refund_quantity);
            }
        }
        $data['refunded_ammount'] = $discount_currency . " " . $refunded_amount . ".00";
        $data['payment_stat'] = $order->payment_stat;
        $data['tbl_order_id'] = $tbl_order_id;
        $data['tax'] = $tax;
        $data['IsTaxExemp'] = $IsTaxExemp;
        $data['IsTaxExempStr'] = $IsTaxExempStr;
        $data['deftax'] = $deftax;
        $data['hasTax'] = $hasTax;
        $data['taxpercent'] = $taxpercent;
        $data['strtaxpercent'] = $strtaxpercent;
        $data['discountamount'] = $discountamount;
        $data['defdiscount'] = $defdiscount;
        $data['discounttext'] = $discounttext;
        $data['discount_var'] = $discount_var;
        $data['discount_reason'] = $discount_reason;
        $data['defnumdiscount'] = $defnumdiscount;
        $data['discount_currency'] = $discount_currency;
        $data['shipping'] = $shipping;
        $data['defshipping'] = $defshipping;
        $data['shipping_name'] = $shipping_name;
        $data['notes'] = $notes;
        $data['reserveDate'] = $reserveDate;
        $data['reserveDateTime'] = $reserveDateTime;
        $data['total_order'] = $total;
        $data['total_ordering'] = $total_ordering;
        $data['def_total_ordering'] = $def_total_ordering;
        $data['deftotal'] = $deftotal;
        // $data['customer'] = $customer;
        $data['customer_info'] = $customer_info;
        $data['customer_form'] = $customer_form;
        $data['customer_id'] = $customer_id;
        $data['notes'] = $order->notes;
        // dd($data);
        return view('member.order.draft.viewdraft', $data);
    }
    public static function customer_single_formatter($id)
    {
        // return 1;
        // return $id;
        $data[] = null;
        $customer_info = 'none';
        $customer_form = 'block';
        $customer_id = 0;
        $data['tbl_order_id'] = $id;
        $data['customer_info'] = $customer_info;
        $data['customer_form'] = $customer_form;
        $data['customer_id'] = $customer_id;
        
        return view('member.order.draft.customer_single', $data);
    }
}
