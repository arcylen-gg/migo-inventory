<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_customer_estimate extends Model
{
	protected $table = 'tbl_customer_estimate';
	protected $primaryKey = "est_id";
    public $timestamps = false;

    public static function scopePaymentMethod($query)
    {
        return $query->leftjoin("tbl_payment_method","tbl_payment_method.payment_method_id","=","tbl_customer_estimate.est_payment_method");
    }
	public static function scopeCustomer($query)
    {
    	return $query->leftjoin("tbl_customer","tbl_customer.customer_id","=","tbl_customer_estimate.est_customer_id")
                    ->leftjoin("tbl_customer_address","tbl_customer_address.customer_id","=","tbl_customer.customer_id")
                    ->leftjoin("tbl_customer_other_info","tbl_customer_other_info.customer_id","=","tbl_customer.customer_id");
    }
    public static function scopeEstimate_item($query)
    {
    	return $query->leftjoin("tbl_customer_estimate_line","tbl_customer_estimate_line.estline_est_id","=","tbl_customer_estimate.est_id")
    				->leftjoin("tbl_item","tbl_item.item_id","=","estline_item_id");
    }

    public static function scopeByCustomer($query, $shop_id, $customer_id)
    {
        return $query->where("est_shop_id", $shop_id)->where("est_customer_id", $customer_id);
    }
    
    public static function scopeAcctg_trans($query, $trans_type = '')
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","est_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id");

                        //->where("transaction_ref_name", $trans_type);
        if($trans_type)
        {
            $return = $return->where("transaction_ref_name", $trans_type);            
        }
        return $return;
    }
    public static function scopePerwarehouse($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","est_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "sales_order");
        return $return;
    }
}

