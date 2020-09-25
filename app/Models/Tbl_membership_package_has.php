<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_membership_package_has extends Model
{
    protected $table = 'tbl_membership_package_has';
	   protected $primaryKey = "membership_package_has_id";
    public $timestamps = false;
    
    public function scopeProd_info($query)
    {
        $query->join('tbl_product','tbl_product.product_id',"=",'tbl_membership_package_has.product_id');
    }
   public function scopeVariant($query, $variant)
    {
        $query->selectRaw("*, GROUP_CONCAT(option_name ORDER BY variant_name_order SEPARATOR ',') AS option_name, GROUP_CONCAT(option_value ORDER BY variant_name_order SEPARATOR ',') AS option_value, GROUP_CONCAT(option_value ORDER BY variant_name_order SEPARATOR ' • ') AS option_value_dot")
              ->join("tbl_variant","tbl_variant.variant_product_id","=","tbl_membership_package_has.product_id")
              ->join("tbl_variant_name","tbl_variant_name.variant_id","=","tbl_variant.variant_id")
              ->join("tbl_option_name","tbl_option_name.option_name_id","=","tbl_variant_name.option_name_id")
              ->join("tbl_option_value","tbl_option_value.option_value_id","=","tbl_variant_name.option_value_id")
              ->where('tbl_variant.variant_id', $variant)
              ->leftjoin("tbl_image","variant_main_image","=","image_id")
              ->groupBy("tbl_variant.variant_id");
    }
}
