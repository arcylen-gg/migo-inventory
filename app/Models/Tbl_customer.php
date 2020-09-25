<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_customer extends Model
{
	protected $table = 'tbl_customer';
	protected $primaryKey = "customer_id";
    public $timestamps = true;
    
    public function scopeTransaction($query, $shop_id, $customer_id = null)
    {
        /* ESTIMATE */
        $estimate = DB::table("tbl_customer")->selectRaw("est_date as date, 'Estimate' as type, est_id as no, est_exp_date as due_date, 0 as balance, est_overall_price as total, 'status' as status, date_created, 'customer/estimate' as reference_url")
                    ->join("tbl_customer_estimate","est_customer_id","=","customer_id")
                    ->where("est_shop_id", $shop_id)
                    ->where("is_sales_order", 0);
        if($customer_id) $estimate->where("est_customer_id", $customer_id);

        /* SALES ORDER */
        $sales_order = DB::table("tbl_customer")->selectRaw("est_date as date, 'Sales Order' as type, est_id as no, est_exp_date as due_date, 0 as balance, est_overall_price as total, 'status' as status, date_created, 'customer/sales_order' as reference_url")
                    ->join("tbl_customer_estimate","est_customer_id","=","customer_id")
                    ->where("est_shop_id", $shop_id)
                    ->where("is_sales_order", 1);
        if($customer_id) $sales_order->where("est_customer_id", $customer_id);

        /* INVOICE */
        $invoice = DB::table("tbl_customer")->selectRaw("inv_date as date, 'Invoice' as type, inv_id as no, inv_due_date as due_date, inv_overall_price - inv_payment_applied as balance, inv_overall_price as total, 'status' as status, date_created, 'customer/invoice' as reference_url")
                    ->join("tbl_customer_invoice","inv_customer_id","=","customer_id")
                    ->where("inv_shop_id", $shop_id)
                    ->where("is_sales_receipt", 0);
        if($customer_id) $invoice->where("inv_customer_id", $customer_id);

        /* CREDIT MEMO */
        $credit_memo = DB::table("tbl_credit_memo")->selectRaw("cm_date as date, 'Credit Memo' as type, cm_id as no, '' as due_date, 0 as balance, cm_amount as total, 'status' as status, date_created, 'cu,stomer/credit_memo' as reference_url")
                    ->join("tbl_credit_memo_line","cmline_cm_id","=","cm_id");
        if($customer_id) $credit_memo->where("cm_customer_id", $customer_id);

        /* SALES RECEIPT */
        $sales_receipt = DB::table("tbl_customer")->selectRaw("inv_date as date, 'Sales Receipt' as type, inv_id as no, inv_due_date as due_date, 0 balance, inv_overall_price as total, 'status' as status, date_created, 'customer/sales_receipt' as reference_url")
                    ->join("tbl_customer_invoice","inv_customer_id","=","customer_id")
                    ->where("inv_shop_id", $shop_id)
                    ->where("is_sales_receipt", 1);
        if($customer_id) $invoice->where("inv_customer_id", $customer_id);

        /* RECEIVE PAYMENT */
        $receive_payment = DB::table("tbl_customer")->selectRaw("rp_date as date, 'Payment' as type, rp_id as no, rp_date as due_date, 0 as balance, rp_total_amount as total, 'status' as status, date_created, 'customer/receive_payment' as reference_url")
                    ->join("tbl_receive_payment","rp_customer_id","=","customer_id")
                    ->where("rp_shop_id", $shop_id);
        if($customer_id) $receive_payment->where("rp_customer_id", $customer_id);

        /* JOURNAL ENTRY */
        $journal_entry = DB::table("tbl_journal_entry")->selectRaw("je_entry_date as date, 'Journal' as type, je_id as no,'' as due_date, 0 as balance, jline_amount as total, 'status' as status, tbl_journal_entry.created_at as date_created, 'accounting/journal' as reference_url")
                    ->leftJoin("tbl_journal_entry_line","jline_je_id","=","je_id")
                    ->where("je_shop_id", $shop_id)
                    ->where("je_reference_module","journal-entry");
        if($customer_id) $journal_entry->where("jline_name_id", $customer_id)->where("jline_name_reference","customer");

        
        
        return $query = $sales_order->union($estimate)->union($invoice)->union($credit_memo)->union($sales_receipt)->union($receive_payment)->union($journal_entry)->orderBy("date_created","desc");
    }
    public function scopeSearch($query)
    {
        return $query->join("tbl_mlm_slot","tbl_customer.customer_id","=","tbl_mlm_slot.slot_owner");
    }

    public function scopeInfo($query)
    {
        return $query->leftjoin("tbl_customer_address", function($on)
                        {
                            $on->on("tbl_customer_address.customer_id","=","tbl_customer.customer_id");
                            $on->on("purpose","=", DB::raw("'billing'"));
                        })
                        ->leftjoin("tbl_customer_other_info","tbl_customer_other_info.customer_id","=","tbl_customer.customer_id");
    }

    public function scopeAddress($query)
    {
        return $query->leftjoin("tbl_customer_address","tbl_customer_address.customer_id","=","tbl_customer.customer_id");
    }
    public function scopeSalesrepresentative($query)
    {
        return $query->leftjoin("tbl_sales_representative","sales_rep_customer_id","=","tbl_customer.customer_id");
    }
    public function scopeOtherinfo($query)
    {
        return $query->leftjoin("tbl_customer_other_info","tbl_customer_other_info.customer_id","=","tbl_customer.customer_id");
    }

    /* !! CURRENTLY NOT IN USE !! */
    public function scopeBalance($query, $shop_id, $customer_id = null)
    {
        $invoice = DB::table("tbl_customer")->selectRaw("sum(inv_overall_price - inv_payment_applied) as balance")
                    ->join("tbl_customer_invoice","inv_customer_id","=","customer_id")
                    ->where("inv_shop_id", $shop_id);
        if($customer_id) $invoice->where("inv_customer_id", $customer_id);

        $receive_payment = DB::table("tbl_customer")->selectRaw("sum(0) as balance")
                    ->join("tbl_receive_payment","rp_customer_id","=","customer_id")
                    ->where("rp_shop_id", $shop_id);
        if($customer_id) $receive_payment->where("rp_customer_id", $customer_id);
        
        $balance = $invoice->pluck("balance") + $receive_payment->pluck("balance");

        return $query->selectRaw("*, $balance as balance");
    }
    public function scopeCommission($query)
    {
        return $query->leftjoin('tbl_commission','tbl_commission.customer_id','=','tbl_customer.customer_id');
    }    
    public function scopeSalesrep($query)
    {
        return $query->leftjoin('tbl_employee','employee_id','=','agent_id');
    }
    public function scopeUnionVendor($query, $shop_id)
    {
        $raw = DB::table("tbl_vendor")->selectRaw("vendor_id as id, vendor_first_name as first_name, vendor_middle_name as middle_name, vendor_last_name as last_name, 'vendor' as reference, vendor_email as email")->where("tbl_vendor.archived", 0)->where("vendor_shop_id", $shop_id);

        return $query->selectRaw("customer_id as id, first_name as first_name, middle_name as middle_name, last_name as last_name, 'customer' as reference, email")->where("tbl_customer.archived", 0)->where("shop_id", $shop_id)->union($raw);
    }
    public function scopeUnionVendorCustomer($query, $shop_id)
    {
        $vendor = DB::table("tbl_vendor")->join("tbl_vendor_address","ven_addr_vendor_id","=","vendor_id")
                        ->selectRaw("vendor_id as id, vendor_first_name as first_name, vendor_middle_name as middle_name, vendor_last_name as last_name, 'vendor' as reference, vendor_email as email, ven_billing_street as street, ven_billing_city as city, ven_billing_zipcode as zipcode, vendor_company as company")
                        ->where("tbl_vendor.archived", 0)
                        ->where("vendor_shop_id", $shop_id);

        $customer = DB::table("tbl_customer")->join("tbl_customer_address","tbl_customer_address.customer_id","=","tbl_customer.customer_id")->selectRaw("tbl_customer.customer_id as id, first_name as first_name, middle_name as middle_name, last_name as last_name, 'customer' as reference, email, customer_street as street, customer_city as city, customer_zipcode as zipcode, company")->where("tbl_customer.archived", 0)->where("shop_id", $shop_id)->union($vendor);
        return $customer;
    }

    public function scopeBalanceJournal($query)
    {
        $balance = DB::table("tbl_journal_entry_line")->leftjoin("tbl_chart_of_account", "account_id", "=", "jline_account_id")
                                           ->leftjoin("tbl_chart_account_type","chart_type_id", "=", "account_type_id")
                                           ->whereRaw("chart_type_name = 'Accounts Receivable'")
                                           ->whereRaw("jline_name_reference = 'customer'")
                                           ->whereRaw("jline_name_id = tbl_customer.customer_id")
                                           ->whereRaw("account_shop_id = tbl_customer.shop_id")
                                           ->selectRaw("sum( CASE jline_type WHEN 'credit' then -jline_amount WHEN 'debit' then jline_amount END)")
                                           ->toSql();
        
        return $query->selectRaw("*, ($balance) as balance");
    }
    public function scopeShop($query, $shop_id)
    {
        return $query->where("shop_id", $shop_id);
    }
    public function scopeMembership($query)
    {
        return $query->leftjoin('tbl_mlm_slot','slot_owner','=','customer_id')
                     ->leftjoin('tbl_membership','membership_id','=','slot_membership');
    }
}