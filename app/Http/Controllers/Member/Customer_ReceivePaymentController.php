<?php

namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use App\Globals\Customer;
use App\Globals\Accounting;
use App\Globals\Invoice;
use App\Globals\CreditMemo;
use App\Globals\ReceivePayment;
use App\Globals\CommissionCalculator;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Pdf_global;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_payment_method;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_receive_payment_credit;
use App\Models\Tbl_credit_memo_applied_payment;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_user;

use Request;
use Session;
use Redirect;
use PDF;
use Carbon\Carbon;
use App\Globals\AuditTrail;

/**
 * Receive Payment Module - all payment related module
 *
 * @author Bryan Kier Aradanas
 */

class Customer_ReceivePaymentController extends Member
{

    public function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }

    public function index()
    {
        $data['_receive_payment'] = Tbl_receive_payment::customer()->where('rp_shop_id',Self::getShopId())->get();
        //dd($data['_receive_payment']);
        return view("member.receive_payment.receive_payment_list", $data);
    }

    public function receive_payment()
    {    
        $data["c_id"] = Request::input("customer_id");
        $data["_customer"]      = Customer::getAllCustomer();
        $data['_account']       = Accounting::getAllAccount('all','',['Bank']);
        $data['_payment_method']= Tbl_payment_method::where("archived",0)->where("shop_id", $this->getShopId())->get();
        $data['action']         = "/member/customer/receive_payment/add";
        $data["_invoice"] = Invoice::getAllInvoiceByCustomer($data["c_id"]);
        //dd($data["_invoice"]);
        $data['comm_calculator'] = CommissionCalculator::check_settings($this->user_info->shop_id);
        Session::forget("applied_credits");
        $data['_apply_credit'] = null;

        $id = Request::input('id');
        if($id)
        {
            $data["rcvpayment"]         = Tbl_receive_payment::where("rp_id", $id)->first();
            $data["_rcvpayment_line"]   = Tbl_receive_payment_line::where("rpline_rp_id", $id)->get();
            $_rcvpayment_credit = Tbl_receive_payment_credit::where("rp_id", $id)->get();
            $data['_rcvpayment_credit'] = null;
            foreach ($_rcvpayment_credit as $key => $value) 
            {
                $cm_data = CreditMemo::get_info($value->credit_reference_id);
                if($cm_data)
                {
                    $data['_rcvpayment_credit'][$key]['ref_number'] = $cm_data->transaction_refnum != "" ? $cm_data->transaction_refnum : $cm_data->cm_id;
                    $data['_rcvpayment_credit'][$key]['cm_id'] = $value->credit_reference_id;
                    $data['_rcvpayment_credit'][$key]['cm_amount'] = $value->credit_amount;                
                }
            }
            $data["_invoice"]           = Invoice::getAllInvoiceByCustomerWithRcvPymnt($data["rcvpayment"]->rp_customer_id, $data["rcvpayment"]->rp_id);
            $data['action']             = "/member/customer/receive_payment/update/".$data["rcvpayment"]->rp_id;
        }
        return view("member.receive_payment.receive_payment", $data);
    }

    public function load_customer_rp($customer_id)
    {
        Session::put("applied_credits",null);
        $data["_invoice"] = Invoice::getAllInvoiceByCustomer($customer_id);
        return view('member.receive_payment.load_receive_payment_items', $data);
    }
    public function rp_pdf($rp_id)
    {
        $date = date("F j, Y, g:i a");
        $first_name         = $this->user_info->user_first_name;
        $last_name         = $this->user_info->user_last_name;
        $footer ='Printed by: '.$first_name.' '.$last_name.'           '.$date.'           ';

        $data['receive_payment'] = Tbl_receive_payment::customer()->where("rp_id",$rp_id)->where("rp_shop_id",$this->user_info->shop_id)->first();       
        $data["_invoice"] = Tbl_receive_payment_line::invoice()->where('inv_shop_id',$this->user_info->shop_id)->where('rpline_rp_id',$rp_id)->get();
        $pdf = view('member.receive_payment.receive_payment_pdf', $data);
        return Pdf_global::show_pdf($pdf, null, $footer);

    }
    public function add_receive_payment()
    {
        //for credit memo
        $cm_id = Request::input("cm_id");

        // dd(Request::input());
        $insert["rp_shop_id"]           = $this->getShopId();
        $insert["rp_customer_id"]       = Request::input('rp_customer_id');
        //$insert["rp_ar_account"]        = Request::input('rp_ar_account') or 0;

        $insert["rp_date"]              = datepicker_input(Request::input('rp_date'));
        $insert["rp_total_amount"]      = convertToNumber(Request::input('rp_total_amount'));
        $insert["rp_payment_method"]    = Request::input('rp_payment_method');
        $insert["rp_memo"]              = Request::input('rp_memo');
        $insert["date_created"]         = Carbon::now();

        //die(var_dump(Request::input('rp_ar_account')));
        // if($cm_id != '')
        // {
        //     $insert_credit["credit_reference_name"]        = "credit_memo";
        //     $insert_credit["credit_reference_id"]          = $cm_id;
        //     $insert_credit["credit_amount"]                = $insert["rp_total_amount"];
        // }
       
        if(Request::input('rp_ar_account') != '')
        {   
            $insert["rp_ar_account"]        = Request::input('rp_ar_account');

            $rcvpayment_id  = Tbl_receive_payment::insertGetId($insert);

            $txn_line = Request::input('line_is_checked');
            $cm_amt = 0;
            foreach($txn_line as $key=>$txn)
            {
                if($txn == 1)
                {
                    //$rcvpayment_id  = Tbl_receive_payment::insertGetId($insert);
                    $insert_line["rpline_rp_id"]            = $rcvpayment_id;
                    $insert_line["rpline_reference_name"]   = Request::input('rpline_txn_type')[$key];
                    $insert_line["rpline_reference_id"]     = Request::input('rpline_txn_id')[$key];

                    $cm_amount = CreditMemo::cm_amount(Request::input('rpline_txn_id')[$key]);
                    $cm_amt += $cm_amount;
                    $insert_line["rpline_amount"]           = convertToNumber(Request::input('rpline_amount')[$key]) + $cm_amount;

                    Tbl_receive_payment_line::insert($insert_line);

                    if($insert_line["rpline_reference_name"] == 'invoice')
                    {
                        $ret = Invoice::updateAmountApplied($insert_line["rpline_reference_id"]);
                        if($ret == 1)
                        {
                            $check = CommissionCalculator::check_settings($this->user_info->shop_id);
                            if($check == 1)
                            {
                                CommissionCalculator::update_commission($insert_line["rpline_reference_id"], $rcvpayment_id);
                            }
                        }
                    }
                }
            }
            $up['rp_total_amount'] = Purchasing_inventory_system::check() ? round($insert["rp_total_amount"] + $cm_amount,2) : $insert["rp_total_amount"] + $cm_amt;
            Tbl_receive_payment::where("rp_id",$rcvpayment_id)->update($up);

            $cm_id = Request::input('rp_cm_id');
            $cm_amount = Request::input('rp_cm_amount');
            $date_created = Carbon::now();
            $rp_credits = null;
            if(count($cm_id) > 0)
            {
                foreach ($cm_id as $key => $value) 
                {
                    if($value)
                    {
                        $rp_credits[$key]['rp_id'] = $rcvpayment_id;
                        $rp_credits[$key]['credit_reference_name'] = "credit_memo";
                        $rp_credits[$key]['credit_reference_id'] = $value;
                        $rp_credits[$key]['credit_amount'] = $cm_amount[$key];
                        $rp_credits[$key]['date_created'] = $date_created;   
                    }
                }
            }
            if(count($rp_credits) > 0)
            {
                Tbl_receive_payment_credit::insert($rp_credits);
                CreditMemo::update_cm_data($rp_credits);
            }

            /* Transaction Journal */
            $entry["reference_module"]      = "receive-payment";
            $entry["reference_id"]          = $rcvpayment_id;
            $entry["name_id"]               = $insert["rp_customer_id"];
            $entry["total"]                 = $insert["rp_total_amount"];
            $entry_data[0]['account_id']    = $insert["rp_ar_account"];
            $entry_data[0]['vatable']       = 0;
            $entry_data[0]['discount']      = 0;
            $entry_data[0]['entry_amount']  = $insert["rp_total_amount"];
            $inv_journal = Accounting::postJournalEntry($entry, $entry_data);
            
            $rcv_data = AuditTrail::get_table_data("tbl_receive_payment","rp_id",$rcvpayment_id);
            AuditTrail::record_logs("Added","receive_payment",$rcvpayment_id,"",serialize($rcv_data));

            $button_action = Request::input('button_action');

            $json["status"]         = "success";
            $json["rcvpayment_id"]  = $rcvpayment_id;
            $json["message"]        = "Successfully received payment";
            /*$json["redirect"]       = "/member/customer/receive_payment";*/

            if($button_action == "save-and-new")
            {
                $json["redirect"]    = "/member/customer/receive_payment";
            }
            elseif($button_action == "save-and-edit")
            {
                $json["redirect"]    = "/member/customer/receive_payment?id=".$rcvpayment_id;
            }
            elseif($button_action == "save-and-print")
            {
                $json["redirect"]    = "/member/customer/receive_payment/view_pdf/".$rcvpayment_id;
            }
            elseif($button_action == "save-and-close")
            {
                $json["redirect"]    = "/member/customer/receive_payment/list";
            }
            
            if($cm_id == "")
            {
                if($button_action == "save-and-edit")
                {
                    $json["redirect"]    = "/member/customer/receive_payment?id=".$rcvpayment_id;
                }            
            }
            else
            {
                $json["redirect"]    = "/member/customer/credit_memo/list";
            }
            
        }
        else
        {
            $json["status"]         = "error";
            $json["message"]        = "Please Select Account";
        }
        return json_encode($json);
    }

    public function update_receive_payment($rcvpayment_id)
    {
        $old_data = AuditTrail::get_table_data("tbl_receive_payment","rp_id",$rcvpayment_id);

        
        $update["rp_customer_id"]       = Request::input('rp_customer_id');
        //$update["rp_ar_account"]        = Request::input('rp_ar_account') or 0;
        $update["rp_date"]              = datepicker_input(Request::input('rp_date'));
        $update["rp_total_amount"]      = convertToNumber(Request::input('rp_total_amount'));
        $update["rp_payment_method"]    = Request::input('rp_payment_method');
        $update["rp_memo"]              = Request::input('rp_memo');

        if(Request::input('rp_ar_account') != '')
        {
            $update["rp_ar_account"]        = Request::input('rp_ar_account');
            Tbl_receive_payment::where("rp_id", $rcvpayment_id)->update($update);
            Tbl_receive_payment_line::where("rpline_rp_id", $rcvpayment_id)->delete();

            $txn_line = Request::input('line_is_checked');
            $cm_amt = 0;
            foreach($txn_line as $key=>$txn)
            {
                if($txn == 1)
                {
                    //die(var_dump($txn));
                    $insert_line["rpline_rp_id"]            = $rcvpayment_id;
                    $insert_line["rpline_reference_name"]   = Request::input('rpline_txn_type')[$key];
                    $insert_line["rpline_reference_id"]     = Request::input('rpline_txn_id')[$key];
               
                    $cm_amount = CreditMemo::cm_amount(Request::input('rpline_txn_id')[$key]);
                    $insert_line["rpline_amount"]           = convertToNumber(Request::input('rpline_amount')[$key]) + $cm_amount;
                    $cm_amt += $cm_amount;

                    Tbl_receive_payment_line::insert($insert_line);
                    if($insert_line["rpline_reference_name"] == 'invoice')
                    {
                        Invoice::updateAmountApplied(Request::input('rpline_txn_id')[$key]);
                    }
                }
                else
                {
                    Invoice::updateAmountApplied(Request::input('rpline_txn_id')[$key]);
                }
                //die(var_dump($txn));
            }
            
            if($txn != 0)
            {
                $cm_id = Request::input('rp_cm_id');
                $cm_amount = Request::input('rp_cm_amount');
                $date_created = Carbon::now();
                $rp_credits = null;
                if(count($cm_id) > 0)
                {
                    foreach ($cm_id as $key => $value) 
                    {
                        if($value)
                        {
                            $rp_credits[$key]['rp_id'] = $rcvpayment_id;
                            $rp_credits[$key]['credit_reference_name'] = "credit_memo";
                            $rp_credits[$key]['credit_reference_id'] = $value;
                            $rp_credits[$key]['credit_amount'] = $cm_amount[$key];
                            $rp_credits[$key]['date_created'] = $date_created;   
                        }
                    }
                }
                if(count($rp_credits) > 0)
                {
                    Tbl_receive_payment_credit::where("credit_reference_name","credit_memo")->where('rp_id', $rcvpayment_id)->delete();
                    Tbl_receive_payment_credit::insert($rp_credits);
                    Tbl_credit_memo_applied_payment::where("applied_ref_name","receive_payment")->where('applied_ref_id', $rcvpayment_id)->delete();
                    CreditMemo::update_cm_data($rp_credits);
                }

                /* Transaction Journal */
                $entry["reference_module"]      = "receive-payment";
                $entry["reference_id"]          = $rcvpayment_id;
                $entry["name_id"]               = $update["rp_customer_id"];
                $entry["total"]                 = $update["rp_total_amount"] + $cm_amt;
                $entry_data[0]['account_id']    = $update["rp_ar_account"];
                $entry_data[0]['vatable']       = 0;
                $entry_data[0]['discount']      = 0;
                $entry_data[0]['entry_amount']  = $update["rp_total_amount"] + $cm_amt;
                $inv_journal = Accounting::postJournalEntry($entry, $entry_data);

                $new_data = AuditTrail::get_table_data("tbl_receive_payment","rp_id",$rcvpayment_id);
                AuditTrail::record_logs("Updated","receive_payment",$rcvpayment_id,serialize($old_data),serialize($new_data));


                $button_action = Request::input('button_action');

                $json["status"]         = "success";
                $json["rcvpayment_id"]  = $rcvpayment_id;
                $json["message"]        = "Successfully updated payment";
                //$json["url"]            = "/member/customer/receive_payment?id=".$rcvpayment_id;
                
                if($button_action == "save-and-new")
                {
                    $json["redirect"]    = "/member/customer/receive_payment";
                }
                elseif($button_action == "save-and-edit")
                {
                    $json["redirect"]    = "/member/customer/receive_payment?id=".$rcvpayment_id;
                }
                elseif($button_action == "save-and-print")
                {
                    $json["redirect"]    = "/member/customer/receive_payment/view_pdf/".$rcvpayment_id;
                }
                elseif($button_action == "save-and-close")
                {
                    $json["redirect"]    = "/member/customer/receive_payment/list";
                }
            }
            else
            {
                $json["status"]         = "error";
                $json["message"]        = "Please Select Invoice";
            }
            
        }
        else
        {
            $json["status"]         = "error";
            $json["message"]        = "Please Select Account";
        }
        

        return json_encode($json);
    }
    public function apply_credit()
    {
        $customer_id = Request::input('customer_id');
        $return = null;
        $data['page'] = 'All Credits';
        $data['customer_id'] = $customer_id;
        $data['customer_data'] = Customer::get_info($this->user_info->shop_id, $customer_id);
        $data['_credits'] = CreditMemo::get_all_available_credit($this->user_info->shop_id, $customer_id);
        $data['_applied'] = Session::get("applied_credits");

        return view('member.receive_payment.load_all_credit',$data);
    }
    public function apply_credit_submit()
    {
        $_credit = Request::input("apply_credit");
        
        $_apply_credit = Session::get('applied_credits');
        
        
        if(count($_credit) > 0)
        {
            foreach ($_credit as $key => $value) 
            {
                $_apply_credit[$key] = $value;
            }
            Session::put('applied_credits', $_apply_credit);
        }

        $return['status'] = "success";
        $return['call_function'] = "success_apply_credit";

        return json_encode($return);
    }
    public function remove_apply_credit()
    {
        $_apply_credit = Session::get('applied_credits');
        $cm_id = Request::input('cm_id');
        foreach ($_apply_credit as $key => $value) 
        {
            if($key == $cm_id)
            {
                unset($_apply_credit[$key]);
            }
        }
        Session::put('applied_credits',$_apply_credit);        
    }
    public function load_apply_credit()
    {
        $credit = Session::get('applied_credits');
        $_credits = null;
        if(count($credit) > 0)
        {
            foreach ($credit as $key => $value) 
            {
                $cm_data = CreditMemo::get_info($key);
                if($cm_data)
                {
                    $_credits[$key]['ref_number'] = $cm_data->transaction_refnum != "" ? $cm_data->transaction_refnum : $cm_data->cm_id;
                    $_credits[$key]['cm_id'] = $key;
                    $_credits[$key]['cm_amount'] = $value;                
                }
            }   
        }
        $data['_applied_credit'] = $_credits;
        return view("member.receive_payment.load_credits",$data);
    }
}
