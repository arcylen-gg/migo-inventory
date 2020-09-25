<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_voucher extends Model
{
	protected $table = 'tbl_voucher';
	protected $primaryKey = "voucher_id";
    public $timestamps = false;

    public static function scopeInvoice($query)
    {
    	return $query->join("tbl_membership_code_invoice","tbl_membership_code_invoice.membership_code_invoice_id","=","tbl_voucher.voucher_invoice_membership_id");
    }
    public static function scopeCustomer($query)
    {
    	return $query->join("tbl_customer","tbl_customer.customer_id","=","voucher_customer");
    }
}
