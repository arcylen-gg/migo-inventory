<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_manual_credit_memo extends Model
{
    protected $table = 'tbl_manual_credit_memo';
	protected $primaryKey = "manual_cm_id";
    public $timestamps = true;

    public static function scopeSir($query)
    {
    	return $query->join("tbl_sir","tbl_sir.sir_id","=","tbl_manual_credit_memo.sir_id");
    }
    public static function scopeCustomer_cm($query)
    {
    	return $query->leftjoin("tbl_credit_memo","tbl_credit_memo.cm_id","=","tbl_manual_credit_memo.cm_id")
                    ->leftjoin("tbl_customer","tbl_customer.customer_id","=","tbl_credit_memo.cm_customer_id");
    }
}
