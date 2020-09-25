<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_receive_payment_line extends Model
{
	protected $table = 'tbl_receive_payment_line';
	protected $primaryKey = "rpline_id";
    public $timestamps = true;

    public function scopeInvoice($query)
    {
    	return $query->leftjoin("tbl_customer_invoice","tbl_customer_invoice.inv_id","=","tbl_receive_payment_line.rpline_reference_id");
    	
    }
}