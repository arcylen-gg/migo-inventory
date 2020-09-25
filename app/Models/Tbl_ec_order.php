<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_ec_order extends Model
{
	protected $table = 'tbl_ec_order';
	protected $primaryKey = "ec_order_id";
    public $timestamps = false;

    public static function scopeCustomer($query)
    {
    	return $query->join(DB::raw("(select customer_id, title_name, first_name, middle_name, last_name from tbl_customer) customer"),"customer.customer_id","=","tbl_ec_order.customer_id");
    }
    public static function scopeCustomer_address($query)
    {
        return $query->leftjoin("tbl_customer_address","tbl_customer_address.customer_id","=","tbl_ec_order.customer_id");
    }
    public static function scopeCustomer_otherinfo($query)
    {
    	return $query->leftjoin("tbl_customer_other_info","tbl_customer_other_info.customer_id","=","tbl_ec_order.customer_id");
    }
    public static function scopeCoupon($query)
    {
        return $query->leftjoin("tbl_coupon_code","tbl_coupon_code.coupon_id","=","tbl_ec_order.coupon_id");
    }
    public static function scopePayment_method($query)
    {
    	return $query->leftjoin("tbl_online_pymnt_method","tbl_online_pymnt_method.method_id","=","tbl_ec_order.payment_method_id");
    }
}