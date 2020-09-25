<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_debit_memo extends Model
{
   protected $table = 'tbl_debit_memo';
   protected $primaryKey = "db_id";
   public $timestamps = false;

    public function scopeVendor($query)
    {
    	return $query->leftjoin("tbl_vendor","tbl_vendor.vendor_id","=","tbl_debit_memo.db_vendor_id");
    }

    public static function scopeAcctg_trans($query)
    {
        $return = $query->leftjoin("tbl_acctg_transaction_list","transaction_ref_id","=","db_id")
                        ->leftjoin("tbl_acctg_transaction","tbl_acctg_transaction.acctg_transaction_id","=","tbl_acctg_transaction_list.acctg_transaction_id")
                        ->where("transaction_ref_name", "debit_memo");

        return $return;
    }
}
