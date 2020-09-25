<?php

namespace App\Http\Controllers\Member;

use Request;

use App\Http\Controllers\Controller;
use App\Models\Tbl_customer;
use App\Models\Tbl_warehousea;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_item;
use App\Models\Tbl_write_check_account_line;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_user;
use App\Models\Tbl_bill_po;
use App\Models\Tbl_vendor;
use App\Models\Tbl_terms;

use App\Globals\Vendor;
use App\Globals\WriteCheck;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\Purchase_Order;
use App\Globals\Billing;
use App\Globals\Item;
use App\Globals\Warehouse;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Pdf_global;

use App\Models\Tbl_purchase_order;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_bill;
use App\Models\Tbl_bill_account_line;
use App\Models\Tbl_bill_item_line;
use App\Models\Tbl_write_check;
use App\Models\Tbl_write_check_line;
use Carbon\Carbon;
use Session;
class Vendor_CheckController extends Member
{
    /**
     * Display a listing of the resource.
     * @author \ARCYLEN
     * @return \Illuminate\Http\Response
     */
    public function write_check()
    {
        Session::forget("po_item");
        $data['pis']        = Purchasing_inventory_system::check();
        $data["_vendor"]    = Vendor::getAllVendor('active');
        $data["_name"]      = Tbl_customer::unionVendor(WriteCheck::getShopId())->get();
        $data['_item']      = Item::get_all_category_item();
        $data['_account']   = Accounting::getAllAccount('all',null,['Expense','Other Expense','Cost of Goods Sold']);
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['action']     = "/member/vendor/write_check/add";
        $data['vendor_id']     = Request::input("vendor_id");
        
        $data["_po"] = Tbl_purchase_order::where("po_vendor_id",Request::input("vendor_id"))->where("po_is_billed",0)->get();

        $data["_bill"]      = Billing::getAllBillByVendor(Request::input("vendor_id"));
        // dd($data["_bill"]);
        $id = Request::input("id");
        if($id)
        {
           $data["wc"] = Tbl_write_check::where("wc_id",$id)->first();
           $data["_po"] = Tbl_purchase_order::where("po_vendor_id",$data["wc"]->wc_vendor_id)->where("po_is_billed",0)->get();
           $data["_wc_item_line"] = Tbl_write_check_line::um()->where("wcline_wc_id",$id)->get();
           $data["_wc_acct_line"] = Tbl_write_check_account_line::where("accline_wc_id",$id)->get();
           $data['_item']      = Item::get_all_category_item();
           $data['action']     = "/member/vendor/write_check/update";
        }
        return view("member.vendor.check.write_check",$data);
    }
    public function check_list()
    {
        $data["_check"] = Tbl_write_check::where("wc_shop_id",$this->user_info->shop_id)->get();
        //dd($data["_check"]);
        foreach ($data["_check"] as $key => $value) 
        {
            $v_data = Tbl_vendor::where("vendor_id",$value->wc_reference_id)->first();
            $name = isset($v_data) ? ($v_data->vendor_company != "" ? $v_data->vendor_company : $v_data->vendor_first_name." ".$v_data->vendor_last_name) : "";
            if($value->wc_reference_name == "customer")
            {
                $c_data = Tbl_customer::where("customer_id",$value->wc_reference_id)->first();
                $name = isset($c_data) ? ($c_data->company != "" ? $c_data->company : $c_data->first_name." ".$c_data->last_name) : "";
            }

            $data["_check"][$key]->name = $name;
        }

        return view("member.vendor.check.check_list",$data);
    }

    public function add_check()
    {
        $button_action = Request::input('button_action');

        $vendor_info                       = [];
        $vendor_info['wc_reference_id']    = Request::input('wc_reference_id');
        $vendor_info['wc_reference_name']  = Request::input('wc_reference_name');
        $vendor_info['wc_customer_vendor_email']    = Request::input('wc_customer_vendor_email');
        $vendor_info['wc_mailing_address'] = Request::input('wc_mailing_address');

        $wc_info                            = [];
        $wc_info['wc_payment_date']         = datepicker_input(Request::input('wc_payment_date'));

        $wc_other_info                    = [];
        $wc_other_info['wc_memo']         = Request::input('wc_memo');

        $total_info                         = [];
        $total_info['wc_total_amount']    = Request::input('wc_total_amount');

        $item_info                          = [];
        $_itemline                          = Request::input('itemline_item_id');

        $_accountline                       = Request::input('expense_account');
        $_accountamount                     = Request::input('account_amount');
        $_accountdesc                       = Request::input('account_desc');

        $ctr_items = 0;

        $account_info = null;
        foreach ($_accountline as $key_acct => $value_acct) 
        {
            if($value_acct && $_accountamount[$key_acct] != 0)
            {
                $ctr_items++;
                $account_info[$key_acct]['account_id']      = $value_acct;
                $account_info[$key_acct]['account_amount']  = str_replace(",","",$_accountamount[$key_acct]);
                $account_info[$key_acct]['account_desc']    = $_accountdesc[$key_acct];
            }
        }

        $item_refill = [];
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $ctr_items++;
                $item_info[$key]['itemline_ref_name']     = Request::input('itemline_ref_name')[$key];
                $item_info[$key]['itemline_ref_id']       = Request::input('itemline_ref_id')[$key];
                
                $item_info[$key]['itemline_description']  = Request::input('itemline_description')[$key];
                $item_info[$key]['itemline_um']           = Request::input('itemline_um')[$key];
                $item_info[$key]['itemline_item_id']      = Request::input('itemline_item_id')[$key];
                $item_info[$key]['itemline_qty']          = str_replace(",","", Request::input('itemline_qty')[$key]);
                $item_info[$key]['itemline_rate']         = str_replace(",","", Request::input('itemline_rate')[$key]);
                $item_info[$key]['itemline_amount']       = str_replace(",","", Request::input('itemline_amount')[$key]);
            
                $item_type = Tbl_item::where("item_id",Request::input('itemline_item_id')[$key])->value("item_type_id");
                if($item_type == 4 || $item_type == 1)
                {
                    $um_qty = UnitMeasurement::um_qty(Request::input("itemline_um")[$key]);
                    $item_refill[$key]["quantity"] = $um_qty * $item_info[$key]['itemline_qty'];
                    $item_refill[$key]["product_id"] = Request::input('itemline_item_id')[$key];   
                }
            }
        }

        // --> for bundles
        foreach ($_itemline as $keyitem => $value_item) 
        {
            if($value_item != null)
            {
                $item_bundle_info = Tbl_item::where("item_id",Request::input("itemline_item_id")[$keyitem])->where("item_type_id",4)->first();
                if($item_bundle_info)
                {
                    $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("itemline_item_id")[$keyitem])->get();
                    foreach ($bundle as $key_bundle => $value_bundle) 
                    {
                        $qty = UnitMeasurement::um_qty(Request::input("itemline_um")[$keyitem]);
                        $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id);
                        $_bundle[$key_bundle]['product_id'] = $value_bundle->bundle_item_id;
                        $_bundle[$key_bundle]['quantity'] = (str_replace(",","",Request::input('itemline_qty')[$keyitem]) * $qty) * ($value_bundle->bundle_qty * $bundle_qty);

                        array_push($item_refill, $_bundle[$key_bundle]);
                    }
                }                 
            }
        }
        if(count($item_refill) > 0)
        {
            foreach ($item_refill as $key_items => $value_items) 
            {
                 $i = null;
                 foreach ($_itemline as $keyitemline => $valueitemline)
                 {
                    $type = Tbl_item::where("item_id",Request::input("itemline_item_id")[$keyitemline])->value("item_type_id");
                    if($type == 4)
                    {
                        if(Request::input("itemline_item_id")[$keyitemline] == $value_items['product_id'])
                        {
                            $i = "true";
                        }                    
                    }
                 }
                if($i != null)
                {
                    unset($item_refill[$key_items]);
                }           
            }
        }
        // <-- end bundle
        if($ctr_items != 0)
        {
            $wc_id = WriteCheck::postWriteCheck($vendor_info, $wc_info, $wc_other_info, $item_info, $total_info, $account_info);
            if(count(Session::get("po_item")) > 0)
            {
                WriteCheck::insertPotoWc($wc_id, Session::get("po_item"));
            }

            $remarks            = "Refill Items with Check # ". $wc_id;
            $warehouse_id       = $this->current_warehouse->warehouse_id;
            $transaction_type   = "write_check";
            $transaction_id     = $wc_id;
            if(count($item_refill) > 0)
            {
                $data               = Warehouse::inventory_refill($warehouse_id, $transaction_type, $transaction_id, $remarks, $item_refill, 'array');
            }

            $json["status"]         = "success-write-check";
            /*if($button_action == "save-and-edit")
            {
                $json["redirect"]    = "/member/vendor/write_check?id=".$wc_id;
            }
            elseif($button_action == "save-and-new")
            {
                $json["redirect"]   = '/member/vendor/write_check';
            }*/
            if($button_action == "save-and-edit")
            {
                $json["redirect"]    = "/member/vendor/write_check?id=".$wc_id;
            }
            elseif($button_action == "save-and-new")
            {
                $json["redirect"]   = '/member/vendor/write_check';
            }
            elseif($button_action == "save-and-close")
            {
                $json["redirect"]   = '/member/vendor/write_check/list';
            }
            elseif($button_action == "save-and-print")
            {
                $json["redirect"]   = '/member/vendor/write_check/view_pdf/'.$wc_id;
            }
            Request::session()->flash('success', 'Successfully Created');
        }
        else
        {
            $json["status"] = "error";
            $json["status_message"] = "Please insert Item or Expense Account.";
        }

        return json_encode($json);
    }

    public function wc_pdf($wc_id)
    {
        $date       = date("F j, Y, g:i a");
        $first_name = $this->user_info->user_first_name;
        $last_name  = $this->user_info->user_last_name;
        $footer     ='Printed by: '.$first_name.' '.$last_name.'           '.$date.'           ';
            
        $data["wc"] = Tbl_write_check::vendor()->customer()->where("wc_id",$wc_id)->first();
        $data["_wcline"] = Tbl_write_check_line::um()->item()->where("wcline_wc_id",$wc_id)->get();
        $data["_wcline_acc"] = Tbl_write_check_account_line::account()->where("accline_wc_id",$wc_id)->get();
       
        $pdf = view("member.vendor_list.wc_pdf",$data);
        return Pdf_global::show_pdf($pdf);
    }

    public function update_check()
    {
         $wc_id = Request::input('wc_id');

        $button_action = Request::input('button_action');

        $vendor_info                         = [];
        $vendor_info['wc_reference_id']    = Request::input('wc_reference_id');
        $vendor_info['wc_reference_name']  = Request::input('wc_reference_name');
        $vendor_info['wc_customer_vendor_email']    = Request::input('wc_customer_vendor_email');
        $vendor_info['wc_mailing_address'] = Request::input('wc_mailing_address');

        $wc_info                            = [];
        $wc_info['wc_payment_date']         = datepicker_input(Request::input('wc_payment_date'));

        $wc_other_info                    = [];
        $wc_other_info['wc_memo']       = Request::input('wc_memo');

        $total_info                         = [];
        $total_info['wc_total_amount']    = Request::input('wc_total_amount');

        $item_info                          = [];
        $_itemline                          = Request::input('itemline_item_id');


        $_accountline                       = Request::input('expense_account');
        $_accountamount                     = Request::input('account_amount');
        $_accountdesc                       = Request::input('account_desc');

        $ctr_items = 0;
        $account_info = null;
        foreach ($_accountline as $key_acct => $value_acct) 
        {
            if($value_acct && $_accountamount[$key_acct] != 0)
            {
                $ctr_items++;
                $account_info[$key_acct]['account_id']      = $value_acct;
                $account_info[$key_acct]['account_amount']  = str_replace(",","",$_accountamount[$key_acct]);
                $account_info[$key_acct]['account_desc']    = $_accountdesc[$key_acct];
            }
        }

        $item_refill = [];
        foreach($_itemline as $key => $item_line)
        {
            if($item_line)
            {
                $ctr_items++;
                $item_info[$key]['itemline_ref_name']     = Request::input('itemline_ref_name')[$key];
                $item_info[$key]['itemline_ref_id']       = Request::input('itemline_ref_id')[$key];
                
                $item_info[$key]['itemline_description']  = Request::input('itemline_description')[$key];
                $item_info[$key]['itemline_um']           = Request::input('itemline_um')[$key];
                $item_info[$key]['itemline_item_id']      = Request::input('itemline_item_id')[$key];
                $item_info[$key]['itemline_qty']          = str_replace(",","", Request::input('itemline_qty')[$key]);
                $item_info[$key]['itemline_rate']         = str_replace(",","", Request::input('itemline_rate')[$key]);
                $item_info[$key]['itemline_amount']       = str_replace(",","", Request::input('itemline_amount')[$key]);
            
                $item_type = Tbl_item::where("item_id",Request::input('itemline_item_id')[$key])->value("item_type_id");
                if($item_type == 4 || $item_type == 1)
                {
                    $um_qty = UnitMeasurement::um_qty(Request::input("itemline_um")[$key]);
                    $item_refill[$key]["quantity"] = $um_qty * $item_info[$key]['itemline_qty'];
                    $item_refill[$key]["product_id"] = Request::input('itemline_item_id')[$key];   
                }
            }
        }

        // --> for bundles
        foreach ($_itemline as $keyitem => $value_item) 
        {
            if($value_item != null)
            {
                $item_bundle_info = Tbl_item::where("item_id",Request::input("itemline_item_id")[$keyitem])->where("item_type_id",4)->first();
                if($item_bundle_info)
                {
                    $bundle = Tbl_item_bundle::where("bundle_bundle_id",Request::input("itemline_item_id")[$keyitem])->get();
                    foreach ($bundle as $key_bundle => $value_bundle) 
                    {
                        $qty = UnitMeasurement::um_qty(Request::input("itemline_um")[$keyitem]);
                        $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id);
                        $_bundle[$key_bundle]['product_id'] = $value_bundle->bundle_item_id;
                        $_bundle[$key_bundle]['quantity'] = (str_replace(",","",Request::input('itemline_qty')[$keyitem]) * $qty) * ($value_bundle->bundle_qty * $bundle_qty);

                        array_push($item_refill, $_bundle[$key_bundle]);
                    }
                }                 
            }
        }
        if(count($item_refill) > 0)
        {
            foreach ($item_refill as $key_items => $value_items) 
            {
                 $i = null;
                 foreach ($_itemline as $keyitemline => $valueitemline)
                 {
                    $type = Tbl_item::where("item_id",Request::input("itemline_item_id")[$keyitemline])->value("item_type_id");
                    if($type == 4)
                    {
                        if(Request::input("itemline_item_id")[$keyitemline] == $value_items['product_id'])
                        {
                            $i = "true";
                        }                    
                    }
                 }
                if($i != null)
                {
                    unset($item_refill[$key_items]);
                }           
            }
        }
        // <-- end bundle
        if($ctr_items != 0)
        {
            $wc_id = WriteCheck::updateWriteCheck($wc_id, $vendor_info, $wc_info, $wc_other_info, $item_info, $total_info, $account_info);
            if(count(Session::get("po_item")) > 0)
            {
                WriteCheck::insertPotoWc($wc_id, Session::get("po_item"));
            }

            $transaction_id = $wc_id;
            $transaction_type = "write_check";
            if(count($item_refill) > 0)
            {
                $json = Warehouse::inventory_update_returns($transaction_id, $transaction_type, $item_refill, $return = 'array');
            }


            $json["status"]         = "success-write-check";
            /*if($button_action == "save-and-edit")
            {
                $json["redirect"]    = "/member/vendor/write_check?id=".$wc_id;
            }
            elseif($button_action == "save-and-new")
            {
                $json["redirect"]   = '/member/vendor/write_check';
            }*/

            if($button_action == "save-and-edit")
            {
                $json["redirect"]    = "/member/vendor/write_check?id=".$wc_id;
            }
            elseif($button_action == "save-and-new")
            {
                $json["redirect"]   = '/member/vendor/write_check';
            }
            elseif($button_action == "save-and-close")
            {
                $json["redirect"]   = '/member/vendor/write_check/list';
            }
            elseif($button_action == "save-and-print")
            {
                $json["redirect"]   = '/member/vendor/write_check/view_pdf/'.$wc_id;
            }
            Request::session()->flash('success', 'Successfully Created');
        }
        else
        {
            $json["status"] = "error";
            $json["status_message"] = "Please insert Item or Expense Account.";
        }

        return json_encode($json);
    }
}
