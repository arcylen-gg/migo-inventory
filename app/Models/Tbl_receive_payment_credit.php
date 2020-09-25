<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_receive_payment_credit extends Model
{
	protected $table = 'tbl_receive_payment_credit';
	protected $primaryKey = "rp_credit_id";
    public $timestamps = false;

    public static function scopeRp($query)
    {
    	return $query->leftjoin("tbl_receive_payment","tbl_receive_payment.rp_id","=","tbl_receive_payment_credit.rp_id");
    }
    public static function scopeCm($query)
    {
    	return $query->leftjoin("tbl_credit_memo","tbl_credit_memo.cm_id","=","tbl_receive_payment_credit.credit_reference_id");
    }
}