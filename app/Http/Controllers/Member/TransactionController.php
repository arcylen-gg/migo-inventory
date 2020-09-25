<?php
namespace App\Http\Controllers\Member;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tbl_transaction;
use App\Models\Tbl_transaction_list;
use App\Models\Tbl_transaction_item;
use App\Models\Tbl_payment_logs;
use App\Globals\Currency;
use App\Globals\Transaction;
use App\Globals\Pdf_global;
use DB;
use Excel;

class TransactionController extends Member
{
    public function index()
    {
        $data["page"] = "Transaction";
        
        $_transaction = Tbl_transaction::where("shop_id", $this->user_info->shop_id)->paginate(5);
        
        foreach($_transaction as $key => $transaction)
        {
            $_list = Tbl_transaction_list::where("transaction_id", $transaction->transaction_id)->orderBy("transaction_list_id", "asc")->get();
            $_transaction[$key]->display_balance = "<b>" .Currency::format($transaction->transaction_balance) . "</b>";
            $_transaction[$key]->transaction_origin_date = date("F d, Y - h:i A", strtotime($_list[0]->transaction_date_created));
            $_transaction[$key]->transaction_origin_status = $transaction->transaction_balance == 0 ? "COMPLETED" : "PENDING";
            $_transaction[$key]->transaction_count = count($_list) . " TRANSACTION(S)";
        }
        
        $data["_transaction"] = $_transaction;        
    
        return view("member.transaction.transaction", $data);
    }
    public function view_list(Request $request, $transaction_id)
    {
        $data['_list'] = Transaction::get_transaction_list($this->user_info->shop_id,'all',null, 0, $transaction_id);
        
        return view("member.transaction.transaction_list", $data);
    }
    public function view_item(Request $request, $transaction_list_id)
    {
        $data['shop_key'] = strtoupper($this->user_info->shop_key);
        $data['shop_address'] = ucwords($this->user_info->shop_street_address.' '.$this->user_info->shop_city.', '.$this->user_info->shop_zip);
        
        $data['list'] = Tbl_transaction_list::salesperson()->transaction()->where('transaction_list_id',$transaction_list_id)->first();
        $data['_item'] = Tbl_transaction_item::where('transaction_list_id',$transaction_list_id)->get();
        $data['customer_name'] = Transaction::getCustomerNameTransaction($data['list']->transaction_id);
        $data['transaction_details'] = unserialize($data["list"]->transaction_details);
        
        return view("member.transaction.transaction_item", $data);
    }
    public function transaction_list(Request $request)
    {
        $data['_type'] = Transaction::get_all_transaction_type();
        
        return view("member.transaction.all_transaction_list", $data);
    }
    public function transaction_list_table(Request $request)
    {
        $data['_list'] = Transaction::get_transaction_list($this->user_info->shop_id, $request->transaction_type, $request->search_keyword);
        return view("member.transaction.all_transaction_list_table", $data);
    }
    public function view_pdf(Request $request, $transaction_list_id)
    {
        $data['shop_key']            = strtoupper($this->user_info->shop_key);
        $data['shop_address']        = ucwords($this->user_info->shop_street_address.' '.$this->user_info->shop_city.', '.$this->user_info->shop_zip);
        
        $data['list']                = Tbl_transaction_list::transaction()->where('transaction_list_id',$transaction_list_id)->first();
        $data['_item']               = Tbl_transaction_item::where('transaction_list_id',$transaction_list_id)->get();
        $data['customer_name']       = Transaction::getCustomerNameTransaction($data['list']->transaction_id);
        $data['transaction_details'] = unserialize($data["list"]->transaction_details);
        $data['customer_info']       = Transaction::getCustomerInfoTransaction($data['list']->transaction_id);
        $data['customer_address']    = Transaction::getCustomerAddressTransaction($data['list']->transaction_id);
        
        /* Item */
        $data['list']->vat           = $data['list']->transaction_subtotal / 1.12 * 0.12;
        $data['list']->vatable       = $data['list']->transaction_subtotal - $data['list']->vat;

        /* Get Payment Method */
        $data['customer_payment'] = Transaction::getPaymentMethod($data["list"]->transaction_number, $transaction_list_id, $data['transaction_details']);

        /* Old Date */
        $old = DB::table("tbl_ec_order")->where("invoice_number", $data["list"]->transaction_number)->first();
        if ($old) 
        {
            $data["list"]->transaction_date_created = $old->created_date;
        }
        $html = view("member.transaction.view_pdf", $data);
        $pdf = Pdf_global::show_pdfv2($html);
        return $pdf;
    }
    public function view_receipt(Request $request, $transaction_list_id)
    {       
        $data['shop_key']            = strtoupper($this->user_info->shop_key);
        $data['shop_address']        = ucwords($this->user_info->shop_street_address.' '.$this->user_info->shop_city.', '.$this->user_info->shop_zip);
        $data['list']                = Tbl_transaction_list::salesperson()->transaction()->where('transaction_list_id',$transaction_list_id)->first();
        $data['_item']               = Tbl_transaction_item::where('transaction_list_id',$transaction_list_id)->get();
        $data['customer_name']       = Transaction::getCustomerNameTransaction($data['list']->transaction_id);
        $data['transaction_details'] = unserialize($data["list"]->transaction_details);
        $data['customer_info']       = Transaction::getCustomerInfoTransaction($data['list']->transaction_id);
        $data['customer_address']    = Transaction::getCustomerAddressTransaction($data['list']->transaction_id);
        $data['_payment_list']       = Transaction::get_payment($data['list']->transaction_id);
        $data['_codes']              = Transaction::get_transaction_item_code($transaction_list_id, $this->user_info->shop_id);

        if ($this->user_info->shop_theme == "3xcell") 
        {
            // return view("member.transaction.receipt.3xcell", $data);
            $html = view("member.transaction.receipt.3xcell", $data);
            $pdf = Pdf_global::show_pdfv2($html);
            return $pdf;
        }
        else
        {
            // return view("member.transaction.all_shop_receipt_pdf", $data);
            $html = view("member.transaction.all_shop_receipt_pdf", $data);
            $pdf = Pdf_global::show_pdfv2($html);
            return $pdf;
        }
    }
}
