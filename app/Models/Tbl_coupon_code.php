<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_coupon_code extends Model
{
	protected $table = 'tbl_coupon_code';
	protected $primaryKey = "coupon_code_id";
    public $timestamps = false;

    public function scopeOrder($query)
    {
    	return $query->leftJoin("tbl_ec_order","coupon_id","=","coupon_code_id");
    }

    public function scopeProduct($query, $separator = ' • ')
    {
        $query->selectRaw("* , tbl_coupon_code.date_created as coupon_date_created")
              ->leftJoin("tbl_ec_variant","evariant_id","=","coupon_product_id");
    }
    public function scopeVariantName($query, $separator = ' • ')
    {
        $variant_name = DB::table("tbl_ec_variant")
              ->selectRaw("GROUP_CONCAT(option_value ORDER BY variant_name_order ASC SEPARATOR '$separator') as variant_name")
              ->leftjoin(DB::raw("tbl_variant_name AS var_name"),"evariant_id","=", "variant_id")
              ->leftjoin(DB::raw("tbl_option_name AS op_name"),"op_name.option_name_id","=","var_name.option_name_id")
              ->leftjoin(DB::raw("tbl_option_value AS op_value"),"op_value.option_value_id","=","var_name.option_value_id")
              ->whereRaw("evariant_id = coupon_product_id")
              ->groupBy("evariant_id")
              ->toSql();

        $query->selectRaw("($variant_name) as 'variant_name'");
    }

}