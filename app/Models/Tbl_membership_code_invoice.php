<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_membership_code_invoice extends Model
{
    //
    protected $table = 'tbl_membership_code_invoice';
	protected $primaryKey = "membership_code_invoice_id";
    public $timestamps = false;

    public function scopeCustomer($query)
    {
        $query->leftjoin('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_membership_code_invoice.customer_id');
	  
	  
	    return $query;
    }
}
