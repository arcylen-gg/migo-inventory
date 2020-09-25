<?php
namespace App\Globals;

use App\Models\Tbl_bill;
use App\Models\Tbl_bill_po;
use App\Models\Tbl_bill_item_line;
use App\Models\Tbl_bill_account_line;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_pay_bill_line;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_user;
use App\Models\Tbl_item;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
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

class Billing
{

    public static function count_ap($start_date, $end_date)
    {
         $bill = Tbl_bill::where("bill_shop_id",Billing::getShopId())->whereBetween("date_created",array($start_date,$end_date))->where("bill_is_paid",0)->count();
         return $bill;
    }
    public static function count_paid_bills($start_date, $end_date)
    {
         $bill = Tbl_bill::where("bill_shop_id",Billing::getShopId())->whereBetween("date_created",array($start_date,$end_date))->where("bill_is_paid",1)->count();
         return $bill;
    }
    public static function get_ap_amount($start_date, $end_date)
    {
        $price = 0;
        $bill = Tbl_bill::where("bill_shop_id",Billing::getShopId())
                                ->whereBetween("date_created",array($start_date,$end_date))
                                ->where("bill_is_paid",0)->get();
        if(isset($bill))
        {
            foreach ($bill as $key => $value) 
            {
               $price += $value->bill_total_amount;
            }            
        }

        return $price;
    }
    public static function get_paid_bills_amount($start_date, $end_date)
    {
        $price = 0;
        $bill = Tbl_bill::where("bill_shop_id",Billing::getShopId())
                                ->whereBetween("date_created",array($start_date,$end_date))
                                ->where("bill_is_paid",1)->get();
        if(isset($bill))
        {
            foreach ($bill as $key => $value) 
            {
               $price += $value->bill_total_amount;
            }            
        }

        return $price;
    }
    public static function updateAmountApplied($bill_id)
    {
        $payment_applied = Tbl_bill::appliedPayment(Billing::getShopId())->where("bill_id",$bill_id)->value("amount_applied");
        
        $data["bill_applied_payment"] = $payment_applied;
        Tbl_bill::where("bill_id", $bill_id)->update($data);

        Billing::updateIsPaid($bill_id);
    }
     public static function updateIsPaid($bill_id)
    {
        $payment_applied   = Tbl_bill::where("bill_id", $bill_id)->value("bill_applied_payment"); 
        $overall_price     = Tbl_bill::where("bill_id", $bill_id)->value("bill_total_amount"); 

        if($payment_applied == $overall_price)  $data["bill_is_paid"] = 1;
        else                                    $data["bill_is_paid"] = 0;

        Tbl_bill::where("bill_id", $bill_id)->update($data);
    }
    public static function getShopId()
    {
    	return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
    public static function getAllBillByVendor($vendor_id)
    {
          return  Tbl_bill::appliedPayment(Billing::getShopId())->byVendor(Billing::getShopId(), $vendor_id)->where("bill_is_paid", 0)->where('inventory_only',0)->get()->toArray();
    }
    public static function getAllBillByVendorWithPaybill($vendor_id, $paybill_id)
    {
        $bill_in_paybill = Tbl_pay_bill_line::select("pbline_reference_id")->where("pbline_reference_name", 'bill')
                            ->where("pbline_pb_id", $paybill_id)->get()->toArray();

        return  Tbl_bill::appliedPayment(Billing::getShopId())->byVendor(Billing::getShopId(), $vendor_id)
                ->payBill($paybill_id, $bill_in_paybill)->orderBy("bill_id")->get()->toArray();
    }


    public static function postBill($vendor_info, $bill_info, $bill_other_info, $item_info, $total_info, $account_info = array())
    {
    	$insert['bill_shop_id']             = Billing::getShopId();    
    	$insert['bill_ap_account']			= 0;    
        $insert['bill_vendor_id']           = $vendor_info['bill_vendor_id'];
        $insert['bill_vendor_email']        = $vendor_info['bill_vendor_email'];        
        $insert['bill_mailing_address']     = $vendor_info['bill_mailing_address'];
        $insert['bill_terms_id']            = $bill_info['bill_terms_id'];
        $insert['bill_date']                = $bill_info['bill_date'];
        $insert['bill_due_date']            = $bill_info['bill_due_date'];

        $insert['inventory_only']           = $bill_info['inventory_only'];

        $insert['bill_memo']                = $bill_other_info['bill_memo'];
        $insert['bill_total_amount']        = collect($item_info)->sum('itemline_amount') + collect($account_info)->sum('account_amount');
        $insert['bill_payment_method']      = 0;
        $insert['date_created']             = Carbon::now();
        

        $bill_id = Tbl_bill::insertGetId($insert);

        $entry = null;
        if($bill_info['inventory_only'] == 0)
        {
            /* Transaction Journal */
            $entry["reference_module"]  = "bill";
            $entry["reference_id"]      = $bill_id;
            $entry["name_id"]           = $vendor_info['bill_vendor_id'];
            $entry["total"]             = $insert['bill_total_amount'];
            $entry["vatable"]           = '';
            $entry["discount"]          = '';
            $entry["ewt"]               = ''; 

            $transaction_bill = "bill";
        }

        
        if($bill_info['inventory_only'] != 0)
        {
            $entry["reference_module"]  = "receive-inventory";
            $entry["reference_id"]      = $bill_id;
            $entry["name_id"]           = $vendor_info['bill_vendor_id'];
            $entry["total"]             = $insert['bill_total_amount'];
            $entry["vatable"]           = '';
            $entry["discount"]          = '';
            $entry["ewt"]               = ''; 

            $transaction_bill = "receive_inventory";
        }

        //die(var_dump($transaction_bill));

        Billing::insert_bill_line($bill_id, $item_info, $entry, $account_info);

        $bill_data = AuditTrail::get_table_data("tbl_bill","bill_id",$bill_id);
        AuditTrail::record_logs("Added",$transaction_bill,$bill_id,"",serialize($bill_data));

        return $bill_id;

    }
    public static function insertPotoBill($bill_id = null, $po_data = array())
    {
        if($bill_id != null)
        {
            Tbl_bill_po::where("billed_id",$bill_id)->delete();
            foreach ($po_data as $key => $value) 
            {
                $chk = Tbl_bill_po::where("billed_id",$bill_id)->where("purchase_order_id",$value["poline_po_id"])->first();
                if($chk == null)
                {
                    $ins["billed_id"] = $bill_id;
                    $ins["purchase_order_id"] = $value["poline_po_id"];
                    
                    Tbl_bill_po::insert($ins);

                    // $up_po["po_is_billed"] = $bill_id;
                    // Tbl_purchase_order::where("po_id",$value["poline_po_id"])->update($up_po);  

                    Self::check_partial_po($bill_id, $value["poline_po_id"]);  
                }
            }            
        }
        //die(var_dump($po_data));
    }
    public static function updatePotoBill($bill_id = null, $po_id = array())
    {
        if($bill_id != null)
        {
            Tbl_bill_po::where("billed_id",$bill_id)->delete();

            foreach ($po_id as $key => $value) 
            {
                $chk = Tbl_bill_po::where("billed_id",$bill_id)->where("purchase_order_id",$value)->first();
                if($chk == null)
                {
                    if($value)
                    {
                        $ins["billed_id"] = $bill_id;
                        $ins["purchase_order_id"] = $value;
                        
                        Tbl_bill_po::insert($ins);

                        // $up_po["po_is_billed"] = $bill_id;
                        // Tbl_purchase_order::where("po_id",$value)->update($up_po);
                        Self::check_partial_po($bill_id, $value);                        
                    }
                }
            }            
        }
    }
    public static function check_partial_po($bill_id, $po_id)
    {
        $po_item = Tbl_purchase_order_line::where('poline_po_id',$po_id)->get();
        $ctr = 0;
        foreach ($po_item as $key => $value) 
        {
            $up_item_po = Tbl_bill_item_line::where("itemline_bill_id",$bill_id)->where('itemline_ref_name','purchase_order')->where('itemline_item_id', $value->poline_item_id)
                                 ->first();
            $update_poline['poline_qty'] =  $value->poline_qty - $up_item_po->itemline_qty;
            Tbl_purchase_order_line::where('poline_id',$value->poline_id)->update($update_poline);

            $bill_item = Tbl_bill_item_line::where("itemline_bill_id",$bill_id)->where('itemline_ref_name','purchase_order')->where('itemline_item_id', $value->poline_item_id)
                                 ->first();
            $po_item_qty = $value->poline_qty * UnitMeasurement::um_qty($value->poline_um);
            $bill_item_qty = $bill_item->itemline_qty * UnitMeasurement::um_qty($bill_item->itemline_um);
            if($update_poline['poline_qty'] <= 0)
            {
                $ctr++;
            }
        }
        if($ctr >= count($po_item))
        {
            $up_po["po_is_billed"] = $bill_id;
            Tbl_purchase_order::where("po_id",$po_id)->update($up_po);
        }
    }
    public static function updateBill($bill_id, $vendor_info, $bill_info, $bill_other_info, $item_info, $total_info, $account_info = array())
    {
        $old = AuditTrail::get_table_data("tbl_bill","bill_id",$bill_id);

        $update['bill_ap_account']			= 0;    
        $update['bill_vendor_id']           = $vendor_info['bill_vendor_id'];
        $update['bill_vendor_email']        = $vendor_info['bill_vendor_email'];        
        $update['bill_mailing_address']     = $vendor_info['bill_mailing_address'];
        $update['bill_terms_id']            = $bill_info['bill_terms_id'];
        $update['bill_date']                = $bill_info['bill_date'];
        $update['bill_due_date']            = $bill_info['bill_due_date'];

        $update['inventory_only']           = $bill_info['inventory_only'];

        $update['bill_memo']                = $bill_other_info['bill_memo'];
        $update['bill_total_amount']        = collect($item_info)->sum('itemline_amount') + collect($account_info)->sum('account_amount');
        $update['bill_payment_method']      = 0;

        Tbl_bill::where("bill_id", $bill_id)->update($update);

        /* Transaction Journal */
        if($bill_info['inventory_only'] == 0)
        {
            $entry["reference_module"]  = "bill";
            $entry["reference_id"]      = $bill_id;
            $entry["name_id"]           = $vendor_info['bill_vendor_id'];
            $entry["total"]             = $update['bill_total_amount'];
            $entry["vatable"]           = '';
            $entry["discount"]          = '';
            $entry["ewt"]               = '';

            $transaction_bill = "bill";
        }     
            
        if($bill_info['inventory_only'] != 0)
        {
            $entry["reference_module"]  = "receive-inventory";
            $entry["reference_id"]      = $bill_id;
            $entry["name_id"]           = $vendor_info['bill_vendor_id'];
            $entry["total"]             = $update['bill_total_amount'];
            $entry["vatable"]           = '';
            $entry["discount"]          = '';
            $entry["ewt"]               = '';

            $transaction_bill = "receive_inventory";
        }
        
        $new = AuditTrail::get_table_data("tbl_bill","bill_id",$bill_id);
        AuditTrail::record_logs("Edited",$transaction_bill,$bill_id,serialize($old),serialize($new));

        Tbl_bill_item_line::where("itemline_bill_id", $bill_id)->delete();
        Tbl_bill_account_line::where("accline_bill_id", $bill_id)->delete();
        Billing::insert_bill_line($bill_id, $item_info, $entry, $account_info);

        return $bill_id;
    }
    public static function insert_bill_line($bill_id, $item_info, $entry, $account_info = array())
    {
        if(count($account_info) > 0)
        {
            foreach ($account_info as $key_acct => $value) 
            {
                if($value['account_id'])
                {
                    $insert_acc['accline_bill_id'] = $bill_id;
                    $insert_acc['accline_coa_id'] = $value['account_id'];
                    $insert_acc['accline_description'] = $value['account_desc'];
                    $insert_acc['accline_amount'] = $value['account_amount'];

                    Tbl_bill_account_line::insert($insert_acc);

                    $entry_data['a'.$key_acct]['account_id'] = $value['account_id'];
                    $entry_data['a'.$key_acct]['vatable'] = 0;
                    $entry_data['a'.$key_acct]['discount'] = 0;
                    $entry_data['a'.$key_acct]['entry_amount'] = $value['account_amount'];
                    $entry_data['a'.$key_acct]['entry_description'] = $value['account_desc'];
                }
            }
        }

        if(count($item_info) > 0)
        {
            foreach($item_info as $key => $item_line)
            {
                if($item_line)
                {
                    $insert_line['itemline_bill_id']       = $bill_id;
                    $insert_line['itemline_item_id']       = $item_line['itemline_item_id'];
                    $insert_line['itemline_ref_name']      = $item_line['itemline_ref_name'] ;
                    $insert_line['itemline_ref_id']        = $item_line['itemline_ref_id'] ;
                    $insert_line['itemline_description']   = $item_line['itemline_description'];
                    $insert_line['itemline_um']            = $item_line['itemline_um'];
                    $insert_line['itemline_qty']           = $item_line['itemline_qty'];
                    $insert_line['itemline_rate']		   = $item_line['itemline_rate'];
                    $insert_line['itemline_amount']        = $item_line['itemline_amount'];

                    Tbl_bill_item_line::insert($insert_line);

                    $item_type = Item::get_item_type($item_line['itemline_item_id']);
                    /* TRANSACTION JOURNAL */  
                    if($item_type != 4)
                    { 
                        $entry_data[$key]['item_id']            = $item_line['itemline_item_id'];
                        $entry_data[$key]['entry_qty']          = $item_line['itemline_qty'];
                        $entry_data[$key]['vatable']            = 0;
                        $entry_data[$key]['discount']           = 0;
                        $entry_data[$key]['entry_amount']       = $item_line['itemline_amount'];
                        $entry_data[$key]['entry_description']  = $item_line['itemline_description'];
                    }
                    else
                    {
                        $item_bundle = Item::get_item_in_bundle($item_line['itemline_item_id']);
                        if(count($item_bundle) > 0)
                        {
                            foreach ($item_bundle as $key_bundle => $value_bundle) 
                            {
                                $item_data = Item::get_item_details($value_bundle->bundle_item_id);
                                $entry_data['b'.$key.$key_bundle]['item_id']            = $value_bundle->bundle_item_id;
                                $entry_data['b'.$key.$key_bundle]['entry_qty']          = $item_line['itemline_qty'] * (UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty);
                                $entry_data['b'.$key.$key_bundle]['vatable']            = 0;
                                $entry_data['b'.$key.$key_bundle]['discount']           = 0;
                                $entry_data['b'.$key.$key_bundle]['entry_amount']       = $item_data->item_price * $entry_data['b'.$key.$key_bundle]['entry_qty'];
                                $entry_data['b'.$key.$key_bundle]['entry_description']  = $item_data->item_sales_information; 
                            }
                        }
                    }
                }
            }
        }
        if(count($entry_data) > 0)
        {
            $bill_journal = Accounting::postJournalEntry($entry, $entry_data);            
        }
    }
}