<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Globals\Customer;
use App\Globals\Vendor;
use App\Globals\Billing;
use App\Globals\Accounting;
use App\Globals\Invoice;
use App\Globals\WriteCheck;
use App\Globals\BillPayment;
use App\Globals\Utilities;
use App\Globals\Pdf_global;

use App\Models\Tbl_payment_method;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_pay_bill_line;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_user;

use Request;
use Session;
use Redirect;
use PDF;
use Carbon\Carbon;
use App\Globals\AuditTrail;

class Vendor_PayBillController extends Member
{
    public function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public function index()
    {   
        $access = Utilities::checkAccess('vendor-pay-bills', 'access_page');
        if($access == 1)
        { 
            $data["v_id"]           = Request::input("vendor_id");
            $data["bill_id"]           = Request::input("bill_id");
            $data["_vendor"]        = Vendor::getAllVendor('active');
            $data['_account']       = Accounting::getAllAccount('all',null,['Bank']);
            $data['_payment_method']= Tbl_payment_method::where("archived",0)->where("shop_id", $this->getShopId())->get();
            $data['action']         = "/member/vendor/paybill/add";
            $data["_bill"]          = Billing::getAllBillByVendor($data["v_id"]);

            $id = Request::input('id');
            if($id)
            {
                $data["paybill"]         = Tbl_pay_bill::where("paybill_id", $id)->first();
                $data["_paybill_line"]   = Tbl_pay_bill_line::where("pbline_pb_id", $id)->get();
                $data["_bill"]           = Billing::getAllBillByVendorWithPaybill($data["paybill"]->paybill_vendor_id, $data["paybill"]->paybill_id);
                $data['action']             = "/member/vendor/paybill/update/".$data["paybill"]->paybill_id;
            }

            return view("member.pay_bill.pay_bill", $data);

        }
        else
        {   
            return $this->show_no_access();  
        }
    }
    public function paybill_list()
    {

        $access = Utilities::checkAccess('vendor-pay-bills', 'access_page');
        if($access == 1)
        { 
            $data["_paybill"] = Tbl_pay_bill::vendor()->where("paybill_shop_id",$this->getShopId())->get();

            return view("member.pay_bill.pay_bill_list",$data);
        }
        else
        {   
            return $this->show_no_access();  
        }
        
    }
    public function print_pay_bill()
    {
        $date       = date("F j, Y, g:i a");
        $first_name = $this->user_info->user_first_name;
        $last_name  = $this->user_info->user_last_name;
        $footer     ='Printed by: '.$first_name.' '.$last_name.'           '.$date.'           ';

        $id = Request::input('id');
        $data['paybill'] = Tbl_pay_bill::vendor()->where('paybill_id',$id)->first();
        $data['paybill_line'] = Tbl_pay_bill_line::where('pbline_pb_id',$id)->get();

        $data['transaction_type'] = "Bill Payment";

        // return view('member.pay_bill.paybill_pdf',$data);
        
        $pdf = view('member.pay_bill.paybill_pdf',$data);
        return Pdf_global::show_pdf($pdf, null, $footer);
    }
    public function load_vendor_pb($vendor_id)
    {
        $data["_bill"] = Billing::getAllBillByVendor($vendor_id);
        return view('member.pay_bill.load_pay_bill_items', $data);
    }

    public function add_pay_bill()
    { 
        $pb_data["paybill_vendor_id"]         = Request::input('paybill_vendor_id');
        $pb_data["paybill_ap_id"]             = Request::input('paybill_ap_id') != "" ? Request::input('paybill_ap_id') : 0;
        $pb_data["paybill_date"]              = datepicker_input(Request::input('paybill_date'));
        $pb_data["paybill_total_amount"]      = convertToNumber(Request::input('paybill_total_amount'));
        $pb_data["paybill_payment_method"]    = Request::input('paybill_payment_method');
        $pb_data["paybill_memo"]              = Request::input('paybill_memo');
        $pb_data["paybill_date_created"]      = Carbon::now();

        $txn_line = Request::input('line_is_checked');
        $pbline_data = [];
        $ctr_bill = 0;
        foreach($txn_line as $key => $txn)
        {
            if($txn)
            {
                $ctr_bill++;
            }
            
            $pbline_data[$key]["line_is_checked"]         = Request::input('line_is_checked')[$key];

            $pbline_data[$key]["pbline_reference_name"]   = Request::input('pbline_txn_type')[$key];
            $pbline_data[$key]["pbline_reference_id"]     = Request::input('pbline_bill_id')[$key];
            $pbline_data[$key]["pbline_amount"]           = convertToNumber(Request::input('pbline_amount')[$key]);

        }

        if($ctr_bill != 0)
        {
            if(Request::input('paybill_ap_id') != '')
            {
                $pb_data["paybill_ap_id"] = Request::input('paybill_ap_id');

                $paybill_id  = BillPayment::postPaybill($pb_data, $pbline_data);

                $paybill_data = AuditTrail::get_table_data("tbl_pay_bill","paybill_id",$paybill_id);
                AuditTrail::record_logs("Added","bill_payment",$paybill_id,"",serialize($paybill_data));

                $button_action = Request::input('button_action');

                $json["status"]         = "success";
                $json["rcvpayment_id"]  = $paybill_id;
                $json["message"]        = "Successfully Pay Bill";
                //$json["redirect"]       = "/member/vendor/paybill";

                if($button_action == "save-and-close")    
                {
                    $json["redirect"]   = "/member/vendor/paybill/list";
                }
                elseif($button_action == "save-and-edit")
                {
                    $json["redirect"]   = "/member/vendor/paybill?id=".$paybill_id;
                }
                elseif($button_action == "save-and-new") 
                {
                    $json["redirect"]   = "/member/vendor/paybill";
                } 
                elseif($button_action == "save-and-print") 
                {
                    $json["redirect"]   = "/member/vendor/print_paybill?id=".$paybill_id;
                } 
            }
            else
            {
                $json["status"]         = "error";
                $json["message"]        = "Please Select Account";
            }           
        }
        else
        {
            $json["status"]         = "error";
            $json["message"]        = "Please Select a Bill";
        }
        
        return json_encode($json);
    }

    public function update_pay_bill($paybill_id)
    {

        $old_data = AuditTrail::get_table_data("tbl_pay_bill","paybill_id",$paybill_id);

        $pb_data["paybill_vendor_id"]         = Request::input('paybill_vendor_id');
        $pb_data["paybill_ap_id"]             = Request::input('paybill_ap_id') != "" ? Request::input('paybill_ap_id') : 0;
        $pb_data["paybill_date"]              = datepicker_input(Request::input('paybill_date'));
        $pb_data["paybill_total_amount"]      = convertToNumber(Request::input('paybill_total_amount'));
        $pb_data["paybill_payment_method"]    = Request::input('paybill_payment_method');
        $pb_data["paybill_memo"]              = Request::input('paybill_memo');

        $txn_line = Request::input('line_is_checked');
        $ctr_bill = 0;
        foreach($txn_line as $key => $txn)
        {   
                if($txn)
                {
                    $ctr_bill++;
                }
                $pbline_data[$key]["pbline_pb_id"]            = $paybill_id;
                $pbline_data[$key]["line_is_checked"]         = Request::input('line_is_checked')[$key];
                $pbline_data[$key]["pbline_reference_name"]   = Request::input('pbline_txn_type')[$key];
                $pbline_data[$key]["pbline_reference_id"]     = Request::input('pbline_bill_id')[$key];
                $pbline_data[$key]["pbline_amount"]           = convertToNumber(Request::input('pbline_amount')[$key]);
        }

        if($ctr_bill != 0)
        {
            if(Request::input('paybill_ap_id') != '')
            {
                BillPayment::updatePaybill($paybill_id, $pb_data, $pbline_data);

                $new_data = AuditTrail::get_table_data("tbl_pay_bill","paybill_id",$paybill_id);
                AuditTrail::record_logs("Edited","bill_payment",$paybill_id,serialize($old_data),serialize($new_data));


                $button_action = Request::input('button_action');

                $json["status"]         = "success";
                $json["bill_id"]        = $paybill_id;
                $json["message"]        = "Successfully updated Pay Bills";
                /*$json["redirect"]            = "/member/vendor/paybill?id=".$paybill_id;
                
                if($button_action == "save-and-new")
                {
                    $json["redirect"]   = '/member/vendor/paybill';
                }*/

                if($button_action == "save-and-close")    
                    {
                        $json["redirect"]   = "/member/vendor/paybill/list";
                    }
                    elseif($button_action == "save-and-edit")
                    {
                        $json["redirect"]   = "/member/vendor/paybill?id=".$paybill_id;
                    }
                    elseif($button_action == "save-and-new") 
                    {
                        $json["redirect"]   = "/member/vendor/paybill";
                    } 
                    elseif($button_action == "save-and-print") 
                    {
                        $json["redirect"]   = "/member/vendor/print_paybill?id=".$paybill_id;
                    } 
            }
            else
            {
                $json["status"]         = "error";
                $json["message"]        = "Please Select Account";
            }
        }
        else
        {
            $json["status"]         = "error";
            $json["message"]        = "Please Select a Bill";
        }       

        return json_encode($json);
    }
}
