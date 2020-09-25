<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_manual_invoice extends Model
{
    protected $table = 'tbl_manual_invoice';
	protected $primaryKey = "manual_invoice_id";
    public $timestamps = true;

    public static function scopeSir($query)
    {
    	return $query->join("tbl_sir","tbl_sir.sir_id","=","tbl_manual_invoice.sir_id");
    }
    public static function scopeCustomer_invoice($query)
    {
    	return $query->leftjoin("tbl_customer_invoice","tbl_customer_invoice.inv_id","=","tbl_manual_invoice.inv_id")
                    ->leftjoin("tbl_customer","tbl_customer.customer_id","=","tbl_customer_invoice.inv_customer_id");
    }

}
