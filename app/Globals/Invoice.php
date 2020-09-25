<?php
namespace App\Globals;

use App\Globals\Accounting;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Models\Tbl_credit_memo;
use App\Globals\AuditTrail;
use App\Globals\Tablet_global;
use App\Globals\CreditMemo;
use App\Globals\Purchasing_inventory_system;
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

class Invoice
{
    public static function count_ar($start_date, $end_date)
    {
         $ar = Tbl_customer_invoice::where("inv_shop_id",Invoice::getShopId())->whereBetween("date_created",array($start_date,$end_date))->where("inv_is_paid",0)->where("is_sales_receipt",0)->count();
         return $ar;
    }
    public static function get_ar_amount($start_date, $end_date)
    {
        $price = 0;
        $ar = Tbl_customer_invoice::where("inv_shop_id",Invoice::getShopId())
                                ->whereBetween("date_created",array($start_date,$end_date))
                                ->where("inv_is_paid",0)
                                ->where("is_sales_receipt",0)->get();
        if(isset($ar))
        {
            foreach ($ar as $key => $value) 
            {
               $price += $value->inv_overall_price - CreditMemo::cm_amount($value->inv_id);
            }            
        }

        return $price;
    }
    public static function get_sales_amount($start_date, $end_date)
    {
        $sr = Tbl_customer_invoice::where("inv_shop_id",Invoice::getShopId())
                                ->where("inv_is_paid",0)
                                ->get();
        foreach ($sr as $key1 => $value1)
        {
          Invoice::updateIsPaid($value1->inv_id);            
        }

        $price = 0;
        $ar = Tbl_customer_invoice::where("inv_shop_id",Invoice::getShopId())
                                ->whereBetween("date_created",array($start_date,$end_date))
                                ->where("inv_is_paid",1)->get();
        if(isset($ar))
        {
            foreach ($ar as $key => $value) 
            {
               $price += $value->inv_overall_price - CreditMemo::cm_amount($value->inv_id);
            }            
        }

        return $price;
    }
    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

	/**
	 * Creating a New Customer Invoice
	 *
	 * @param array  $customer_info 		(customer_id => '', customer_email => '' )
	 * @param array  $invoice_info  		(invoice_terms => '', invoice_date => '', invoice_due => '', billing_address => '')
	 * @param array  $invoice_other_info    (invoice_msg => '', invoice_memo => '')
	 * @param array  $item_info   	        ([0]item_service_date => '', [0]item_id => '', [0]item_description => '', [0]quantity => '', 
	 *										 [0]rate => '', [0]discount => '', [0]discount_remark => '', [0]amount => '')
	 * @param array  $total_info   	        (total_item_price => '', total_addons => [[0]label => '', [0]value => ''], 
	 *										 total_discount_type => '', total_discount_value => '', total_overall_price => '')
	 */
	public static function postInvoice($customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info, $is_sales_receipt = '', $for_tablet = false)
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
        $overall_price  = Purchasing_inventory_system::check() ? round((convertToNumber($subtotal_price) - $ewt - $discount + $tax),2) : convertToNumber($subtotal_price) - $ewt - $discount + $tax;

        $shop_id = Invoice::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
        
        $insert['inv_shop_id']                  = $shop_id;  
		$insert['inv_customer_id']              = $customer_info['customer_id'];        
        $insert['inv_customer_email']           = $customer_info['customer_email'];
        $insert['transaction_refnum']           = isset($invoice_info['transaction_refnum']) ? $invoice_info['transaction_refnum'] : "";
        $insert['new_inv_id']                   = $invoice_info['new_inv_id'];
        $insert['inv_customer_billing_address'] = $invoice_info['billing_address'];
        $insert['inv_terms_id']                 = $invoice_info['invoice_terms_id'];
        $insert['inv_date']                     = date("Y-m-d", strtotime($invoice_info['invoice_date']));
        $insert['inv_due_date']                 = date("Y-m-d", strtotime($invoice_info['invoice_due']));
        $insert['inv_subtotal_price']           = $subtotal_price;
        $insert['ewt']                          = $total_info['ewt'];
        $insert['inv_discount_type']            = $total_info['total_discount_type'];
        $insert['inv_discount_value']           = $total_info['total_discount_value'];
        $insert['taxable']                      = $total_info['taxable'];
        $insert['inv_overall_price']            = $overall_price;
        $insert['inv_message']                  = $invoice_other_info['invoice_msg'];
        $insert['inv_memo']                     = $invoice_other_info['invoice_memo'];
        $insert['date_created']                 = Carbon::now();    

        $transaction='';
        if($is_sales_receipt != '')
        {
            $insert['inv_payment_applied']        = $overall_price;
            $insert['inv_is_paid']                = 1;
            $insert['is_sales_receipt']           = 1;
            $transaction_type = "sales-receipt";
            $transaction = "sales_receipt";
        }
        else
        {
            $transaction_type = "invoice";
            $transaction = "invoice";
        }
        $invoice_id = Tbl_customer_invoice::insertGetId($insert);
        

        /* Transaction Journal */
        $entry["reference_module"]  = $transaction_type;
        $entry["reference_id"]      = $invoice_id;
        $entry["name_id"]           = $customer_info['customer_id'];
        $entry["total"]             = $overall_price;
        $entry["vatable"]           = $tax;
        $entry["discount"]          = $discount;
        $entry["ewt"]               = $ewt;

        Invoice::insert_invoice_line($invoice_id, $item_info, $entry, $for_tablet);

        $inv_data = AuditTrail::get_table_data("tbl_customer_invoice","inv_id",$invoice_id);
        AuditTrail::record_logs("Added",$transaction,$invoice_id,"",serialize($inv_data));

        return $invoice_id;
	}

    public static function postSales_receipt_payment($customer_info,$invoice_info,$overall_price,$inv_id)
    {
        $insert["rp_shop_id"]           = Invoice::getShopId();
        $insert["rp_customer_id"]       = $customer_info['customer_id'];
        $insert["rp_ar_account"]        = 0;
        $insert["rp_date"]              = datepicker_input($invoice_info['invoice_date']);
        $insert["rp_total_amount"]      = convertToNumber($overall_price);
        // $insert["rp_payment_method"]    = Request::input('rp_payment_method');
        // $insert["rp_memo"]              = Request::input('rp_memo');
        $insert["date_created"]         = Carbon::now();

        $rcvpayment_id  = Tbl_receive_payment::insertGetId($insert);

        $insert_line["rpline_rp_id"]            = $rcvpayment_id;
        $insert_line["rpline_reference_name"]   = "invoice";
        $insert_line["rpline_reference_id"]     = $inv_id;
        $insert_line["rpline_amount"]           = convertToNumber($overall_price);

        Tbl_receive_payment_line::insert($insert_line);
        if($insert_line["rpline_reference_name"] == 'invoice')
        {
            Invoice::updateAmountApplied($insert_line["rpline_reference_id"]);
        }

        return $rcvpayment_id;
    }

    public static function updateInvoice($invoice_id, $customer_info, $invoice_info, $invoice_other_info, $item_info, $total_info, $is_sales_receipt = '', $for_tablet = false)
    {        
        $old = AuditTrail::get_table_data("tbl_customer_invoice","inv_id",$invoice_id);

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
        
        $update['inv_customer_id']              = $customer_info['customer_id'];        
        $update['inv_customer_email']           = $customer_info['customer_email'];
        $update['new_inv_id']                   = $invoice_info['new_inv_id'];
        $update['inv_customer_billing_address'] = $invoice_info['billing_address'];
        $update['inv_terms_id']                 = $invoice_info['invoice_terms_id'];
        $update['inv_date']                     = date("Y-m-d", strtotime($invoice_info['invoice_date']));
        $update['inv_due_date']                 = date("Y-m-d", strtotime($invoice_info['invoice_due']));
        $update['inv_subtotal_price']           = $subtotal_price;
        $update['ewt']                          = $total_info['ewt'];
        $update['inv_discount_type']            = $total_info['total_discount_type'];
        $update['inv_discount_value']           = $total_info['total_discount_value'];
        $update['taxable']                      = $total_info['taxable'];
        $update['inv_overall_price']            = $overall_price;
        $update['inv_message']                  = $invoice_other_info['invoice_msg'];
        $update['inv_memo']                     = $invoice_other_info['invoice_memo'];   
        
        $transaction = "";
        if($is_sales_receipt != '')
        {
            $update["inv_payment_applied"] = $overall_price;
            $transaction_type = "sales-receipt";
            $transaction = "sales_receipt";
            Invoice::updateIsPaid($invoice_id);
        }
        else
        {
            $transaction_type = "invoice";
            $transaction = "invoice";
        }

        Tbl_customer_invoice::where("inv_id", $invoice_id)->update($update);
        

        $new = AuditTrail::get_table_data("tbl_customer_invoice","inv_id",$invoice_id);
        AuditTrail::record_logs("Edited","invoice",$invoice_id,serialize($old),serialize($new));

        
        /* Transaction Journal */
        $entry["reference_module"]  = $transaction_type;
        $entry["reference_id"]      = $invoice_id;
        $entry["name_id"]           = $customer_info['customer_id'];
        $entry["total"]             = $overall_price;
        $entry["vatable"]           = $tax;
        $entry["discount"]          = $discount;
        $entry["ewt"]               = $ewt;

        Tbl_customer_invoice_line::where("invline_inv_id", $invoice_id)->delete();
        Invoice::insert_invoice_line($invoice_id, $item_info, $entry, $for_tablet);

        return $invoice_id;
    }

    public static function update_rcv_payment($ref_name = '', $ref_id = 0, $amount = 0)
    {   
        $data = Tbl_receive_payment_line::where("rpline_reference_name",$ref_name)->where("rpline_reference_id",$ref_id)->first();

        if($data)
        {
            $rcv_data = Tbl_receive_payment::where("rp_id",$data->rpline_rp_id)->first();
            $up["rp_total_amount"] = ($rcv_data->rp_total_amount - $data->rpline_amount) + $amount;
            Tbl_receive_payment::where("rp_id",$rcv_data->rp_id)->update($up);

            $up_rcv["rpline_amount"] = $amount;
            Tbl_receive_payment_line::where("rpline_reference_name",$ref_name)->where("rpline_reference_id",$ref_id)->update($up_rcv);
        }

    }
    public static function insert_invoice_line($invoice_id, $item_info, $entry, $for_tablet = false)
    {    
        $total_discount = 0;
        foreach($item_info as $key => $item_line)
        {
            if($item_line)
            {
                /* DISCOUNT PER LINE */
                $discount       = $item_line['discount'];
                $discount_type  = 'fixed';
                if (strpos($discount, '/')) 
                {
                    $explode_discount = explode("/", $discount);
                    $main_rate = convertToNumber($item_line['rate']) * convertToNumber($item_line['quantity']);

                    foreach ($explode_discount as $key => $value) 
                    {
                        if(strpos($value, '%'))
                        {
                            $main_rate = convertToNumber($main_rate) * ((100-convertToNumber(str_replace("%", "", $value))) / 100);
                        }
                        else if($value == "" || $value == null) 
                        {
                            $main_rate -= 0;
                        }
                        else
                        {
                            $main_rate -= convertToNumber($value);
                        }
                    }
                    
                    $discount      = (convertToNumber($item_line['rate']) * convertToNumber($item_line['quantity'])) - $main_rate;
                    $discount_type = 'percent';                
                }
                else
                {
                    if(strpos($discount, '%'))
                    {   
                        $discount       = substr($discount, 0, strpos($discount, '%')) / 100;
                        $discount_type  = 'percent';
                    }
                }

                /* AMOUNT PER LINE */
                $amount = (convertToNumber($item_line['rate']) * convertToNumber($item_line['quantity'])) - $discount;

                $insert_line['invline_inv_id']          = $invoice_id;
                $insert_line['invline_service_date']    = date("Y-m-d", strtotime($item_line['item_service_date']));
                $insert_line['invline_item_id']         = $item_line['item_id'];
                $insert_line['invline_description']     = $item_line['item_description'];
                $insert_line['invline_um']              = $item_line['um'];
                $insert_line['invline_qty']             = convertToNumber($item_line['quantity']);
                $insert_line['invline_rate']            = convertToNumber($item_line['rate']);
                $insert_line['invline_discount']        = $item_line['discount'];
                $insert_line['invline_discount_type']   = $discount_type;
                $insert_line['invline_discount_remark'] = $item_line['discount_remark'];
                $insert_line['taxable']                 = $item_line['taxable'];
                $insert_line['invline_ref_name']        = $item_line['ref_name'];
                $insert_line['invline_ref_id']          = $item_line['ref_id'];
                $insert_line['invline_amount']          = $amount;
                $insert_line['date_created']            = Carbon::now();
                
                Tbl_customer_invoice_line::insert($insert_line);

                $item_type = Item::get_item_type($item_line['item_id']);
                /* TRANSACTION JOURNAL */  
                if($item_type != 4)
                {
                    $entry_data[$key]['item_id']            = $item_line['item_id'];
                    $entry_data[$key]['entry_qty']          = $item_line['quantity'];
                    $entry_data[$key]['vatable']            = 0;
                    $entry_data[$key]['discount']           = $discount;
                    $entry_data[$key]['entry_amount']       = $amount+$discount;
                    $entry_data[$key]['entry_description']  = $item_line['item_description'];                    
                }
                else
                {
                    $item_bundle = Item::get_item_in_bundle($item_line['item_id']);
                    if(count($item_bundle) > 0)
                    {
                        foreach ($item_bundle as $key_bundle => $value_bundle) 
                        {
                            $item_data = Item::get_item_details($value_bundle->bundle_item_id);
                            $entry_data['b'.$key.$key_bundle]['item_id']            = $value_bundle->bundle_item_id;
                            $entry_data['b'.$key.$key_bundle]['entry_qty']          = $item_line['quantity'] * (UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty);
                            $entry_data['b'.$key.$key_bundle]['vatable']            = 0;
                            $entry_data['b'.$key.$key_bundle]['discount']           = 0;
                            $entry_data['b'.$key.$key_bundle]['entry_amount']       = $item_data->item_price * $entry_data['b'.$key.$key_bundle]['entry_qty'];
                            $entry_data['b'.$key.$key_bundle]['entry_description']  = $item_data->item_sales_information; 
                        }
                    }
                }
                
                $total_discount +=$discount; 
            }
        }

        // $entry['discount'] += $total_discount;
        $inv_journal = Accounting::postJournalEntry($entry, $entry_data,'',$for_tablet);

        return $insert_line;
    }

    public static function getAllInvoiceByCustomer($customer_id, $for_tablet = false, $rp_id = null)
    {
        $shop_id = Invoice::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
        if($rp_id)
        {
            $data = Tbl_customer_invoice::appliedPayment($shop_id)->where("is_sales_receipt",0)->byReceivePayment($shop_id, $rp_id)->orwhere("inv_is_paid", 0)->byCustomer($shop_id, $customer_id)->get();
        }
        else
        {
            $data = Tbl_customer_invoice::appliedPayment($shop_id)->byCustomer($shop_id, $customer_id)->where("inv_is_paid", 0)->where("is_sales_receipt",0)->get()->toArray();   
        }
        foreach ($data as $key => $value) 
        {
            if($value['credit_memo_id'] != 0)
            {
                $data[$key]['inv_overall_price'] = $value['inv_overall_price'] - CreditMemo::cm_amount($value['inv_id']);
            }
        }
        return $data;
    }

    public static function getAllInvoiceByCustomerWithRcvPymnt($customer_id, $rcvpayment_id, $for_tablet = false)
    {
        $shop_id = Invoice::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
        $inv_in_rcvpayment = Tbl_receive_payment_line::select("rpline_reference_id")->where("rpline_reference_name", 'invoice')
                            ->where("rpline_rp_id", $rcvpayment_id)->get()->toArray();

        $data = Tbl_customer_invoice::appliedPayment($shop_id)->byCustomer($shop_id, $customer_id)
                ->rcvPayment($rcvpayment_id, $inv_in_rcvpayment)->orderBy("inv_id")->where("is_sales_receipt",0)->get()->toArray();

        // foreach ($data as $key => $value) 
        // {
        //     if($value['credit_memo_id'] != 0)
        //     {
        //         $data[$key]['inv_overall_price'] = $value['inv_overall_price'] - CreditMemo::cm_amount($value['inv_id']);
        //     }
        // }
        return $data;
    }

    public static function updateAmountApplied($inv_id, $for_tablet = false)
    {
        $shop_id = Invoice::getShopId();
        if($for_tablet == true)
        {
            $shop_id = Tablet_global::getShopId();
        }
        $payment_applied = Tbl_customer_invoice::appliedPayment($shop_id)->where("inv_id",$inv_id)->value("amount_applied");
        $data["inv_payment_applied"] = $payment_applied;
        
        Tbl_customer_invoice::where("inv_id", $inv_id)->update($data);

        return Invoice::updateIsPaid($inv_id);
    }

    public static function updateIsPaid($inv_id)
    {
        $payment_applied   = Tbl_customer_invoice::where("inv_id", $inv_id)->value("inv_payment_applied"); 
        $overall_price     = Tbl_customer_invoice::where("inv_id", $inv_id)->value("inv_overall_price"); 

        if($payment_applied == $overall_price)  $data["inv_is_paid"] = 1;
        else                                    $data["inv_is_paid"] = 0;

        Tbl_customer_invoice::where("inv_id", $inv_id)->update($data);

        return $data['inv_is_paid'];
    }


    /**
     * Check number of invoices tha is open, overdue and paid
     *
     * @param   string      $period     period of the report
     * @return  array[3]    open | overdue | paid   
     */
    public static function invoiceStatus($from, $to)
    {
        $now    = datepicker_input("today");

        $data["open"]       = Tbl_customer_invoice::where("inv_shop_id", Invoice::getShopId())
                            ->where("is_sales_receipt", 0)
                            ->whereRaw("DATE(inv_date) >= '$from'")
                            ->whereRaw("DATE(inv_date) <= '$to'")
                            ->whereRaw("inv_overall_price <> inv_payment_applied")
                            ->get();

        $data["overdue"]    = Tbl_customer_invoice::where("inv_shop_id", Invoice::getShopId())
                            ->where("is_sales_receipt", 0)
                            ->whereRaw("DATE(inv_date) >= '$from'")
                            ->whereRaw("DATE(inv_date) <= '$to'")
                            ->whereRaw("DATE(inv_due_date) <= '$now'")
                            ->get();

        $data["paid"]       = Tbl_customer_invoice::where("inv_shop_id", Invoice::getShopId())
                            ->where("is_sales_receipt", 0)
                            ->whereRaw("DATE(inv_date) >= '$from'")
                            ->whereRaw("DATE(inv_date) <= '$to'")
                            ->whereRaw("inv_overall_price = inv_payment_applied")
                            ->get();


        return $data;
    }
    public static function check_inv($shop_id, $transaction_refnum)
    {
        return Tbl_customer_invoice::where("inv_shop_id", $shop_id)->where("transaction_refnum", $transaction_refnum)->first();
    }  
}
