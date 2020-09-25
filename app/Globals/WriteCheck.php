<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_item;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_cart;
use App\Models\Tbl_coupon_code;
use App\Models\Tbl_user;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_credit_memo_line;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_write_check_line;
use App\Models\Tbl_write_check_account_line;
use App\Models\Tbl_bill_account_line;
use App\Models\Tbl_write_check;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_vendor;
use App\Models\Tbl_pay_bill_line;
use App\Models\Tbl_bill_item_line;
use App\Globals\UnitMeasurement;
use App\Globals\Item;
use App\Globals\Accounting;
use App\Globals\AuditTrail;
use DB;
use Session;
use Carbon\Carbon;

class WriteCheck
{
    public static function getShopId()
    {
    	return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
	 public static function postWriteCheck($customer_vendor_info, $wc_info, $wc_other_info, $item_info, $total_info, $account_info = array())
    {
    	$insert['wc_shop_id']             = WriteCheck::getShopId();    
    	$insert['wc_cash_account']		  = 0;    
        $insert['wc_reference_id']        = $customer_vendor_info['wc_reference_id'];
        $insert['wc_reference_name']      = $customer_vendor_info['wc_reference_name'];
        $insert['wc_customer_vendor_email']        = $customer_vendor_info['wc_customer_vendor_email'];    
        $insert['wc_mailing_address']     = $customer_vendor_info['wc_mailing_address'];

        $insert['wc_payment_date']        = $wc_info['wc_payment_date'];

        $insert['wc_memo']                = $wc_other_info['wc_memo'];
        $insert['wc_total_amount']        = collect($item_info)->sum('itemline_amount') + collect($account_info)->sum('account_amount');
        $insert['wc_is_paid']             = 1;
        $insert['wc_applied_payment']     = collect($item_info)->sum('itemline_amount') + collect($account_info)->sum('account_amount');
        $insert['date_created']           = Carbon::now();
        
        $wc_id = Tbl_write_check::insertGetId($insert);

        /* Transaction Journal */
        $entry["reference_module"]  = "write-check";
        $entry["reference_id"]      = $wc_id;
        $entry["name_id"]           = $customer_vendor_info['wc_reference_id'];
        $entry["name_reference"]    = $customer_vendor_info['wc_reference_name'];
        $entry["total"]             = $insert['wc_total_amount'];
        $entry["vatable"]           = '';
        $entry["discount"]          = '';
        $entry["ewt"]               = '';


        $wc_data = AuditTrail::get_table_data("tbl_write_check","wc_id",$wc_id);
        AuditTrail::record_logs("Added","write_check",$wc_id,"",serialize($wc_data));

        WriteCheck::insert_wc_line($wc_id, $item_info, $entry, $account_info);

        return $wc_id;

    }
    public static function insertPotoWc($wc_id = null, $po_data = array())
    {
        if($wc_id != null)
        {
            foreach ($po_data as $key => $value) 
            {
                $up_po["po_is_billed"] = $wc_id;
                Tbl_purchase_order::where("po_id",$value["poline_po_id"])->update($up_po);  
            }            
        }
    }
    public static function delete_bill_in_check($paybill_id)
    {
        $wc_data = Tbl_write_check::where("wc_ref_name","paybill")->where("wc_ref_id",$paybill_id)->first();
        Tbl_write_check_line::where("wcline_wc_id",$wc_data->wc_id)->delete();
    }

    public static function delete_check_acct($paybill_id)
    {
        $wc_data = Tbl_write_check::where("wc_ref_name","paybill")->where("wc_ref_id",$paybill_id)->first();
        Tbl_write_check_account_line::where("accline_wc_id",$wc_data->wc_id)->delete();
    }
    public static function update_check_from_paybill($paybill_id)
    {
        $pb_data = Tbl_pay_bill::where("paybill_id",$paybill_id)->first();
        $pbline_data = Tbl_pay_bill_line::where("pbline_pb_id",$paybill_id)->get();

        if($pb_data)
        {
            $wc_data = Tbl_write_check::where("wc_ref_name","paybill")->where("wc_ref_id",$paybill_id)->first();
            if($wc_data)
            {
                $update['wc_cash_account']        = 0;    
                $update['wc_reference_id']        = $pb_data->paybill_vendor_id;
                $update['wc_customer_vendor_email']        = Tbl_vendor::where("vendor_id",$pb_data->paybill_vendor_id)->value("vendor_email");        
                $update['wc_mailing_address']     = "";

                $update['wc_payment_date']        =  $pb_data->paybill_date;

                $update['wc_memo']                = $pb_data->paybill_memo;
                $update['wc_total_amount']        = $pb_data->paybill_total_amount;
                $update['wc_applied_payment']     = $pb_data->paybill_total_amount;
                $update['wc_ref_name']            = "paybill";
                $update['wc_ref_id']              = $pb_data->paybill_id;

                Tbl_write_check::where("wc_id",$wc_data->wc_id)->update($update);
                $wc_id = $wc_data->wc_id;
            }

            $item_info = null;
            foreach ($pbline_data as $key => $value) 
            {
                if($value->pbline_reference_name == "bill")
                {
                    $bill_line_data = Tbl_bill_item_line::where("itemline_bill_id",$value->pbline_reference_id)->get();

                    foreach ($bill_line_data as $key1 => $value1)
                    {
                        $item_info["itemline_item_id"] = $value1->itemline_item_id;
                        $item_info["itemline_ref_name"] = "bill";
                        $item_info["itemline_ref_id"] = $value1->itemline_bill_id;
                        $item_info["itemline_description"] = $value1->itemline_description;
                        $item_info["itemline_um"] = $value1->itemline_um;
                        $item_info["itemline_qty"] = $value1->itemline_qty;
                        $item_info["itemline_rate"] = $value1->itemline_rate;
                        $item_info["itemline_amount"] = $value1->itemline_amount;

                        WriteCheck::insert_bill_wc_line($wc_id, $item_info);
                    }

                    $bill_line_acct = Tbl_bill_account_line::where("accline_bill_id",$value->pbline_reference_id)->get();

                    foreach ($bill_line_acct as $key => $value2)
                    {
                        $account_info['accline_coa_id'] = $value2->accline_coa_id;
                        $account_info['accline_description'] = $value2->accline_description;
                        $account_info['accline_amount'] = $value2->accline_amount;

                        WriteCheck::insert_bill_wc_acct_line($wc_id, $account_info);
                    }
                }
            }

            /*$wc_data = AuditTrail::get_table_data("tbl_write_check","wc_id",$wc_id);
            AuditTrail::record_logs("Edited","bill_payment_check",$wc_id,"",serialize($wc_data));*/

            $transaction_data = AccountingTransaction::audit_trail(WriteCheck::getShopId(), $wc_id, 'write_check');
            AuditTrail::record_logs('Edited', 'bill_payment_check', $wc_id, "", serialize($transaction_data));
        }

    }
    public static function create_check_from_paybill($paybill_id)
    {
        $pb_data = Tbl_pay_bill::where("paybill_id",$paybill_id)->first();
        $pbline_data = Tbl_pay_bill_line::where("pbline_pb_id",$paybill_id)->get();

        if($pb_data)
        {
            $insert['wc_shop_id']             = WriteCheck::getShopId();    
            $insert['wc_cash_account']        = 0;    
            $insert['wc_reference_id']        = $pb_data->paybill_vendor_id;
            $insert['wc_reference_name']      = "vendor";
            $insert['wc_customer_vendor_email']        = Tbl_vendor::where("vendor_id",$pb_data->paybill_vendor_id)->value("vendor_email");        
            $insert['wc_mailing_address']     = "";

            $insert['wc_payment_date']        =  $pb_data->paybill_date;

            $insert['wc_memo']                = $pb_data->paybill_memo;
            $insert['wc_total_amount']        = $pb_data->paybill_total_amount;
            $insert['wc_applied_payment']     = $pb_data->paybill_total_amount;
            $insert['wc_ref_name']            = "paybill";
            $insert['wc_ref_id']              = $pb_data->paybill_id;

            $wc_id = Tbl_write_check::insertGetId($insert);
            
            $item_info = null;
            foreach ($pbline_data as $key => $value) 
            {
                if($value->pbline_reference_name == "bill")
                {
                    $bill_line_data = Tbl_bill_item_line::where("itemline_bill_id",$value->pbline_reference_id)->get();

                    Tbl_write_check_line::where('wcline_ref_id', $value->pbline_reference_id)->delete();


                    foreach ($bill_line_data as $key1 => $value1)
                    {
                        $item_info["itemline_item_id"] = $value1->itemline_item_id;
                        $item_info["itemline_ref_name"] = "bill";
                        $item_info["itemline_ref_id"] = $value1->itemline_bill_id;
                        $item_info["itemline_description"] = $value1->itemline_description;
                        $item_info["itemline_um"] = $value1->itemline_um;
                        $item_info["itemline_qty"] = $value1->itemline_qty;
                        $item_info["itemline_rate"] = $value1->itemline_rate;
                        $item_info["itemline_amount"] = $value1->itemline_amount;

                        WriteCheck::insert_bill_wc_line($wc_id, $item_info);
                    }

                    $bill_line_acct = Tbl_bill_account_line::where("accline_bill_id",$value->pbline_reference_id)->get();

                    
                    foreach ($bill_line_acct as $key => $value2)
                    {
                        $account_info['accline_coa_id'] = $value2->accline_coa_id;
                        $account_info['accline_description'] = $value2->accline_description;
                        $account_info['accline_amount'] = $value2->accline_amount;

                        
                        WriteCheck::insert_bill_wc_acct_line($wc_id, $account_info);
                    }
                }
            }

           /* $wc_data = AuditTrail::get_table_data("tbl_write_check","wc_id",$wc_id);
            AuditTrail::record_logs("Added","bill_payment_check",$wc_id,"",serialize($wc_data));*/

            $transaction_data = AccountingTransaction::audit_trail(WriteCheck::getShopId(), $wc_id, 'write_check');
            AuditTrail::record_logs('Added', 'bill_payment_check', $wc_id, "", serialize($transaction_data));

        }
    }
    public static function updateWriteCheck($wc_id, $customer_vendor_info, $wc_info, $wc_other_info, $item_info, $total_info, $account_info = array())
    {
        $old = AuditTrail::get_table_data("tbl_write_check","wc_id",$wc_id);

        $update['wc_cash_account']	          = 0;    
        $update['wc_reference_id']            = $customer_vendor_info['wc_reference_id'];
        $update['wc_reference_name']          = $customer_vendor_info['wc_reference_name'];
        $update['wc_customer_vendor_email']   = $customer_vendor_info['wc_customer_vendor_email'];        
        $update['wc_mailing_address']         = $customer_vendor_info['wc_mailing_address'];
        $update['wc_payment_date']            = $wc_info['wc_payment_date'];
        $update['wc_memo']                    = $wc_other_info['wc_memo'];
        $update['wc_total_amount']            = collect($item_info)->sum('itemline_amount') + collect($account_info)->sum('account_amount');

        Tbl_write_check::where("wc_id", $wc_id)->update($update);

        /* Transaction Journal */
        $entry["reference_module"]  = "write-check";
        $entry["reference_id"]      = $wc_id;
        $entry["name_id"]           = $customer_vendor_info['wc_reference_id'];
        $entry["name_reference"]    = $customer_vendor_info['wc_reference_name'];
        $entry["total"]             = $update['wc_total_amount'];
        $entry["vatable"]           = '';
        $entry["discount"]          = '';
        $entry["ewt"]               = '';

        Tbl_write_check_line::where("wcline_wc_id", $wc_id)->delete();
        Tbl_write_check_account_line::where("accline_wc_id", $wc_id)->delete();
        WriteCheck::insert_wc_line($wc_id, $item_info, $entry, $account_info);


        
        $new = AuditTrail::get_table_data("tbl_write_check","wc_id",$wc_id);
        AuditTrail::record_logs("Edited","write_check",$wc_id,serialize($old),serialize($new));

        return $wc_id;
    }
    public static function insert_bill_wc_line($wc_id, $item_info)
    {
        if($item_info)
        {
            $insert_line['wcline_wc_id']         = $wc_id;
            $insert_line['wcline_item_id']       = $item_info['itemline_item_id'];
            $insert_line['wcline_ref_name']      = $item_info['itemline_ref_name'] ;
            $insert_line['wcline_ref_id']        = $item_info['itemline_ref_id'] ;
            $insert_line['wcline_description']   = $item_info['itemline_description'];
            $insert_line['wcline_um']            = $item_info['itemline_um'];
            $insert_line['wcline_qty']           = $item_info['itemline_qty'];
            $insert_line['wcline_rate']          = $item_info['itemline_rate'];
            $insert_line['wcline_amount']        = $item_info['itemline_amount'];

            Tbl_write_check_line::insert($insert_line);
        }

    }
    public static function insert_bill_wc_acct_line($wc_id, $account_info)
    {
        if($account_info)
        {            
            $insert_acc['accline_wc_id'] = $wc_id;
            $insert_acc['accline_coa_id'] = $account_info['accline_coa_id'];
            $insert_acc['accline_description'] = $account_info['accline_description'];
            $insert_acc['accline_amount'] = $account_info['accline_amount'];

            Tbl_write_check_account_line::insert($insert_acc);
        }
    }
    public static function insert_wc_line($wc_id, $item_info, $entry, $account_info = array())
    {
        if(count($account_info) > 0)
        {
            foreach ($account_info as $key_acct => $value) 
            {
                if($value['account_id'])
                {
                    $insert_acc['accline_wc_id'] = $wc_id;
                    $insert_acc['accline_coa_id'] = $value['account_id'];
                    $insert_acc['accline_description'] = $value['account_desc'];
                    $insert_acc['accline_amount'] = $value['account_amount'];

                    Tbl_write_check_account_line::insert($insert_acc);

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
                    $insert_line['wcline_wc_id']         = $wc_id;
                    $insert_line['wcline_item_id']       = $item_line['itemline_item_id'];
                    $insert_line['wcline_ref_name']      = $item_line['itemline_ref_name'] ;
                    $insert_line['wcline_ref_id']        = $item_line['itemline_ref_id'] ;
                    $insert_line['wcline_description']   = $item_line['itemline_description'];
                    $insert_line['wcline_um']            = $item_line['itemline_um'];
                    $insert_line['wcline_qty']           = $item_line['itemline_qty'];
                    $insert_line['wcline_rate']		     = $item_line['itemline_rate'];
                    $insert_line['wcline_amount']        = $item_line['itemline_amount'];

                    Tbl_write_check_line::insert($insert_line);

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
            $wc_journal = Accounting::postJournalEntry($entry, $entry_data);
        }
    }
}
