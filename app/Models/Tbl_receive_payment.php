<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_receive_payment extends Model
{
	protected $table = 'tbl_receive_payment';
	protected $primaryKey = "rp_id";
    public $timestamps = true;
    
    public static function scopeCustomer($query)
    {
    	return $query->leftjoin("tbl_customer","tbl_customer.customer_id","=","tbl_receive_payment.rp_customer_id");
    }
    public static function scopeRpline($query)
    {
    	return $query->leftjoin("tbl_receive_payment_line","rpline_rp_id","=","rp_id");    	
    }
    
    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","rp_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "receive_payment");

        return $return;
    }
    public static function scopeMethod($query)
    {
        return $query->leftjoin("tbl_payment_method","tbl_payment_method.payment_method_id","=","tbl_receive_payment.rp_payment_method");
    }
    public static function scopeCoa($query)
    {
        return $query->leftjoin("tbl_chart_of_account","tbl_chart_of_account.account_id","=","tbl_receive_payment.rp_ar_account");
    }
}