<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_credit_memo extends Model
{
   protected $table = 'tbl_credit_memo';
	protected $primaryKey = "cm_id";
    public $timestamps = true;

    public function scopeCustomer($query)
    {
    	return $query->leftjoin("tbl_customer","tbl_customer.customer_id","=","tbl_credit_memo.cm_customer_id");
    }
    public function scopeManual_cm($query)
    {
        return $query->leftJoin("tbl_manual_credit_memo","tbl_manual_credit_memo.cm_id","=","tbl_credit_memo.cm_id")
                    ->selectRaw("*, tbl_credit_memo.cm_id as cm_id");
    }
    public static function scopeCm_item($query)
    {
    	return $query->join("tbl_credit_memo_line","tbl_credit_memo_line.cmline_cm_id","=","tbl_credit_memo.cm_id")
    				->join("tbl_item","tbl_item.item_id","=","cmline_item_id");
    }

    public function scopeApplied($query)
    {
        return $query->leftJoin("tbl_receive_payment_credit","tbl_receive_payment_credit.credit_reference_id","=","tbl_credit_memo.cm_id")
                    ->selectRaw("*, sum(credit_amount) as applied_cm_amount");;        
    }
    public static function scopeInv($query)
    {
        return $query->leftJoin("tbl_customer_invoice","credit_memo_id","=","tbl_credit_memo.cm_id");

    }    
    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","cm_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "credit_memo");

        return $return;
    }
    public static function scopeRp($query)
    {
        return $query->leftjoin("tbl_receive_payment","rp_id","=","cm_used_ref_id");
    }
}
