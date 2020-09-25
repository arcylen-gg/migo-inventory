<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_item_code extends Model
{
    //
    protected $table = 'tbl_item_code';
	protected $primaryKey = "item_code_id";
    public $timestamps = false;
    
    public function scopeItem($query)
    {
        $query->join('tbl_item', 'tbl_item.item_id', '=', 'tbl_item_code.item_id');
	   
	   
	    return $query;
    } 
    public function scopeInvoice($query)
    {
        $query->join('tbl_item_code_invoice', 'tbl_item_code_invoice.item_code_invoice_id', '=', 'tbl_item_code.item_code_invoice_id' );

        return $query;
    }
    public function scopeCustomer($query)
    {
        $query->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_item_code.customer_id');
	  
	  
	    return $query;
    }
}
