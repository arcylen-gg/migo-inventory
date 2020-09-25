<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_temp_customer_invoice extends Model
{
    protected $table = 'tbl_temp_customer_invoice';
	protected $primaryKey = "inv_id";
    public $timestamps = true;

	public static function scopeCustomer($query)
    {
    	return $query->leftjoin("tbl_customer","tbl_customer.customer_id","=","tbl_temp_customer_invoice.inv_customer_id");
    }
    public static function scopeInvoice_item($query)
    {
    	return $query->leftjoin("tbl_temp_customer_invoice_line","tbl_temp_customer_invoice_line.invline_inv_id","=","tbl_temp_customer_invoice.inv_id")
    				->leftjoin("tbl_item","tbl_item.item_id","=","invline_item_id");
    }
     public static function scopeAppliedPayment($query, $shop_id)
    {
        return $query->leftJoin(DB::raw("(select sum(rpline_amount) as amount_applied, rpline_reference_id from tbl_receive_payment_line as rpline inner join tbl_receive_payment rp on rp_id = rpline_rp_id where rp_shop_id = ".$shop_id." and rpline_reference_name = 'invoice' group by concat(rpline_reference_name,'-',rpline_reference_id)) pymnt"), "pymnt.rpline_reference_id", "=", "inv_id");
    }
}
