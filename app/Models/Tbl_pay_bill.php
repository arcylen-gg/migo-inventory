<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_pay_bill extends Model
{
	protected $table = 'tbl_pay_bill';
	protected $primaryKey = "paybill_id";
    public $timestamps = false;
    
    public static function scopeVendor($query)
    {
    	return $query->leftjoin("tbl_vendor","tbl_vendor.vendor_id","=","tbl_pay_bill.paybill_vendor_id")
                     ->leftjoin("tbl_vendor_address","ven_addr_vendor_id","=","vendor_id")
                     ->leftjoin("tbl_vendor_other_info","ven_info_vendor_id","=","vendor_id");
    }

    public static function scopePbline($query)
    {
        return $query->leftjoin("tbl_pay_bill_line","pbline_pb_id","=","paybill_id");     
    }
    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","paybill_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "pay_bills");

        return $return;
    }
    public static function scopeMethod($query)
    {
        return $query->join("tbl_payment_method","tbl_payment_method.payment_method_id","=","tbl_pay_bill.paybill_payment_method");
    }
    public static function scopeCoa($query)
    {
        return $query->join("tbl_chart_of_account","tbl_chart_of_account.account_id","=","tbl_pay_bill.paybill_ap_id");
    }
}