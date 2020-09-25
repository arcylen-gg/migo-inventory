<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Globals\Customer;
use App\Globals\Vendor;
use App\Globals\Billing;
use App\Globals\Accounting;
use App\Globals\Invoice;
use App\Globals\WriteCheck; 
use App\Globals\BillPayment;
use App\Globals\Utilities;
use App\Globals\Pdf_global;
use App\Globals\TransactionPayBills;
use App\Globals\TransactionEnterBills;
use App\Globals\AccountingTransaction;
use App\Models\Tbl_payment_method;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_pay_bill_line;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_user;

use Session;
use Redirect;
use PDF;
use Carbon\Carbon;
use App\Globals\AuditTrail;

class TransactionPayBillsController extends Member
{
    public function getIndex()
    {
        $data['page'] = 'Pay Bills';
        return view('member.accounting_transaction.vendor.pay_bills.pay_bills_list', $data);
    }
    public function getLoadPayBills(Request $request)
    {
        $display = 10;
        $data['_pay_bills'] = TransactionPayBills::get($this->user_info->shop_id, $display, $request->search_keyword);
        $data['page'] = $data['_pay_bills']->currentPage();
        $data['number'] = ($data['page'] - 1) * $display;
        return view('member.accounting_transaction.vendor.pay_bills.pay_bills_table', $data);
    }
    public function getPrint(Request $request)
    {
        $pb_id = $request->id;
        $data["pb"] = TransactionPayBills::info($this->user_info->shop_id, $pb_id);
        $data["_pbline"] = TransactionPayBills::info_line($pb_id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "pb");
        if($data["pb"])
        {
            $footer = AccountingTransaction::get_refuser($this->user_info);
            $data['transaction_type'] = "Bill Payment";

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_pb");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

            $pdf = view('member.accounting_transaction.vendor.pay_bills.pay_bills_pdf',$data);
            return Pdf_global::show_pdf($pdf, null, $footer, $format);
        }
        else
        {
            return view('member.no_transaction');
        }
    }
    public function getCreate(Request $request)
    {
        $data['page'] = 'Create Pay Bills';

    	$data["v_id"]           = $request->vendor_id;
        $data["bill_id"]        = $request->bill_id;
        $data["_vendor"]        = Vendor::getAllVendor('active');
        $data['_account']       = Accounting::getAllAccount('all',null,['Bank']);
        $data['_payment_method']= Tbl_payment_method::where("archived",0)->where("shop_id", $this->user_info->shop_id)->get();
        $data["transaction_refnum"] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'pay_bill');
        $data['action']         = "/member/transaction/pay_bills/create-pay-bills";

        if($request->eb_id)
        {
            $data['eb'] = TransactionEnterBills::info($this->user_info->shop_id, $request->eb_id);
        }

        if($request->id)
        {
            $data['action'] = "/member/transaction/pay_bills/update-pay-bills";
            $data['pb']     = TransactionPayBills::info($this->user_info->shop_id, $request->id);
            $data["_bill"]  = TransactionPayBills::info_item($this->user_info->shop_id, $data["pb"]->paybill_vendor_id, $request->id);
        }

        return view('member.accounting_transaction.vendor.pay_bills.pay_bills', $data);
    }

    public function getLoadVendorPayBill(Request $request)
    {
        $data = null;
        $return = null;
        if($request->rp_id)
        {
            $rp = TransactionPayBills::info($this->user_info->shop_id, $request->rp_id);
            if($rp['paybill_vendor_id'] == $request->cust)
            {
                $data["_bill"] = TransactionPayBills::getAllBillByVendor($this->user_info->shop_id, $request->cust, $request->rp_id);
            }
            else
            {
                $data["_bill"] = TransactionPayBills::getAllBillByVendor($this->user_info->shop_id, $request->cust);
            }
        }
        else
        {
            $data["_bill"] = TransactionPayBills::getAllBillByVendor($this->user_info->shop_id, $request->cust);
        }
        return view('member.accounting_transaction.vendor.pay_bills.load_pay_bills', $data);
    }

    public function postCreatePayBills(Request $request)
    {
        $btn_action  = $request->button_action;

        $insert["vendor_id"]                 = $request->vendor_id;
        $insert["transaction_refnumber"]     = $request->transaction_refnumber;
        $insert["paybill_ap_id"]             = $request->paybill_ap_id != "" ? $request->paybill_ap_id : 0;
        $insert["paybill_date"]              = $request->paybill_date;
        $insert["vendor_total"]              = str_replace(',', '',$request->paybill_total_amount);
        $insert["paybill_payment_method"]    = $request->paybill_payment_method;
        $insert["paybill_ref_num"]           = $request->paybill_ref_num;
        $insert["paybill_memo"]              = $request->vendor_memo;

        $insert_item = null;
        $ctr_bill = 0;

        foreach($request->line_is_checked as $key => $value)
        {
            if($value)
            {
                $ctr_bill++;
            }
            
            $insert_item[$key]["line_is_checked"]         = $request->line_is_checked[$key];
            $insert_item[$key]["pbline_reference_name"]   = $request->pbline_txn_type[$key];
            $insert_item[$key]["pbline_reference_id"]     = $request->pbline_bill_id[$key];
            $insert_item[$key]["item_amount"]             = str_replace(',', '',$request->pbline_amount[$key]);
            $insert_item[$key]["item_discount"]           = 0;
            $insert_item[$key]["item_id"]                 = 0;
            $insert_item[$key]["item_qty"]                = 0;
            $insert_item[$key]["item_ref_id"]             = $request->pbline_bill_id[$key];
            $insert_item[$key]["item_ref_name"]           = $request->pbline_txn_type[$key];
        }
        
        $return = null;
        if($ctr_bill != 0)
        {
            $validate = TransactionPayBills::postInsert($this->user_info->shop_id, $insert, $insert_item);
            if(is_numeric($validate))
            {
                $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'pay_bill');
                AuditTrail::record_logs('Added', 'pay_bill', $validate, "", serialize($transaction_data));
   
                TransactionPayBills::insert_acctg_transaction($this->user_info->shop_id, $validate);    
                $return['status'] = 'success';
                $return['status_message'] = 'Success creating pay Bills.';
                $return['call_function'] = 'success_pay_bills';
                $return['status_redirect'] = AccountingTransaction::get_redirect('pay_bills', $validate ,$btn_action);
            }
            else
            {
                $return['status'] = 'error';
                $return['status_message'] = $validate;
            }
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = 'Please Select Item';
        }
        return json_encode($return);
    }

    public function postUpdatePayBills(Request $request)
    {
        $btn_action  = $request->button_action;
        $paybill_id  = $request->pb_id;
        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $paybill_id, 'pay_bill');

        $insert["vendor_id"]                 = $request->vendor_id;
        $insert["transaction_refnumber"]     = $request->transaction_refnumber;
        $insert["paybill_ap_id"]             = $request->paybill_ap_id != "" ? $request->paybill_ap_id : 0;
        $insert["paybill_date"]              = $request->paybill_date;
        $insert["vendor_total"]              = str_replace(',', '',$request->paybill_total_amount);
        $insert["paybill_payment_method"]    = $request->paybill_payment_method;
        $insert["paybill_ref_num"]           = $request->paybill_ref_num;
        $insert["paybill_memo"]              = $request->vendor_memo;

        $insert['wc_reference_name']         = 0;
        $insert['vendor_email']              = 0;
        $insert['wc_mailing_address']        = 0;

        $insert_item = null;
        $ctr_bill = 0;

        foreach($request->line_is_checked as $key => $value)
        {
            if($value)
            {
                $ctr_bill++;
            }
            
            $insert_item[$key]["line_is_checked"]         = $request->line_is_checked[$key];
            $insert_item[$key]["pbline_reference_name"]   = $request->pbline_txn_type[$key];
            $insert_item[$key]["pbline_reference_id"]     = $request->pbline_bill_id[$key];
            $insert_item[$key]["item_amount"]             = $request->pbline_amount[$key] != '' ? str_replace(',', '',$request->pbline_amount[$key]) : 0;
            $insert_item[$key]["item_discount"]           = 0;
            $insert_item[$key]["item_id"]                 = 0;
            $insert_item[$key]["item_qty"]                = 0;
            $insert_item[$key]["item_description"]        = 0;    
        }
        
        $return = null;
        if($ctr_bill != 0)
        {
            $validate = TransactionPayBills::postUpdate($paybill_id, $this->user_info->shop_id, $insert, $insert_item);

            if(is_numeric($validate))
            {
                $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $paybill_id, 'pay_bill');
                AuditTrail::record_logs('Edited', 'pay_bill', $paybill_id, serialize($old_transaction_data), serialize($transaction_data));

                TransactionPayBills::insert_acctg_transaction($this->user_info->shop_id, $validate);
                $return['status'] = 'success';
                $return['status_message'] = 'Success creating pay Bills.';
                $return['call_function'] = 'success_pay_bills';
                $return['status_redirect'] = AccountingTransaction::get_redirect('pay_bills', $validate ,$btn_action);
            }
            else
            {
                $return['status'] = 'error';
                $return['status_message'] = $validate;
            }
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = 'Please Select Item';
        }
        return json_encode($return);
    }
}