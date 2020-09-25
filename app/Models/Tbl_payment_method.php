<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payment_method extends Model
{
    protected $table = 'tbl_payment_method';
	protected $primaryKey = "payment_method_id";
    public $timestamps = false;

    public static function scopeInvoice($query)
    {
    	return $query->leftjoin("tbl_customer_invoice","tbl_payment_method.payment_method_id","=","tbl_customer_invoice.inv_payment_method");
    }
}
