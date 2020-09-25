<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_manual_receive_payment extends Model
{
    protected $table = 'tbl_manual_receive_payment';
	protected $primaryKey = "manual_receive_payment_id";
    public $timestamps = true;

    public static function scopeSir($query)
    {
    	return $query->join("tbl_sir","tbl_sir.sir_id","=","tbl_manual_receive_payment.sir_id");
    }
    public static function scopeCustomer_receive_payment($query)
    {
    	return $query->join("tbl_receive_payment","tbl_receive_payment.rp_id","=","tbl_manual_receive_payment.rp_id")
                    ->join("tbl_customer","tbl_customer.customer_id","=","tbl_receive_payment.rp_customer_id");
    }
}
