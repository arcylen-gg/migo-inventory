<?php
namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;

class Tbl_ec_variant extends Model
{
	protected $table = 'tbl_ec_variant';
	protected $primaryKey = "evariant_id";
    public $timestamps = false;

    public function scopeVariantName($query, $separator = ' • ')
    {
    	$query->selectRaw("*, GROUP_CONCAT(option_value ORDER BY variant_name_order ASC SEPARATOR '$separator') as variant_name")
    		  ->leftjoin(DB::raw("tbl_variant_name AS var_name"),"evariant_id","=", "variant_id")
    		  ->leftjoin(DB::raw("tbl_option_name AS op_name"),"op_name.option_name_id","=","var_name.option_name_id")
    		  ->leftjoin(DB::raw("tbl_option_value AS op_value"),"op_value.option_value_id","=","var_name.option_value_id")
    		  ->groupBy("evariant_id");
    }

    public function scopeVariantNameValue($query, $separator = ' • ')
    {
        $query->selectRaw("*, GROUP_CONCAT(option_value ORDER BY variant_name_order ASC SEPARATOR '$separator') AS variant_name, GROUP_CONCAT(option_name ORDER BY variant_name_order ASC SEPARATOR '$separator') AS option_name")
              ->leftjoin(DB::raw("tbl_variant_name AS var_name"),"evariant_id","=", "variant_id")
              ->leftjoin(DB::raw("tbl_option_name AS op_name"),"op_name.option_name_id","=","var_name.option_name_id")
              ->leftjoin(DB::raw("tbl_option_value AS op_value"),"op_value.option_value_id","=","var_name.option_value_id")
              ->groupBy("evariant_id");
    }

    public function scopeProduct($query)
    {
        $query->join("tbl_ec_product","eprod_id","=","evariant_prod_id");
    }

    public function scopeItem($query)
    {
    	$query->join("tbl_item as item","item_id","=","evariant_item_id")->leftJoin("tbl_item_discount","discount_item_id","=","item_id");
    }

    /* DEPENDENT on scopeItem */
    public function scopeInventory($query, $warehouse_id = null)
    {
        return $query->selectRaw("IFNULL(inventory_count, 0), IF(inventory_status > 0, 'in stock', 'out of stock') as inventory_status")
                     ->leftjoin(DB::raw("(SELECT inventory_item_id, warehouse_id, sum(inventory_count) as inventory_count, sum(inventory_count) as inventory_status FROM tbl_warehouse_inventory WHERE warehouse_id = ".$warehouse_id ." GROUP BY inventory_item_id) as warehouse"), "inventory_item_id","=","item_id");
    }

    /* DEPENDENT on scopeItem */
    public function scopeManufacturer($query, $manufacturer_id)
    {
        $query->join("tbl_manufacturer","manufacturer_id","=", "item_manufacturer_id");
        if($manufacturer_id)  $query->where("manufacturer_id", $manufacturer_id);
        return $query;
    }

    public function scopeOptionName($query)
    {
    	$query->leftjoin(DB::raw("tbl_variant_name AS var_name"),"evariant_id","=", "variant_id")
    		  ->leftjoin(DB::raw("tbl_option_name AS op_name"),"op_name.option_name_id","=","var_name.option_name_id")
              ->orderBy("variant_name_order");
    }

    public function scopeOptionValue($query)
    {
    	$query->leftjoin(DB::raw("tbl_variant_name AS var_name"),"evariant_id","=", "variant_id")
    		  ->leftjoin(DB::raw("tbl_option_value AS op_value"),"op_value.option_value_id","=","var_name.option_value_id")
              ->orderBy("variant_name_order");
    }

    public function scopeOption($query)
    {
        $query->leftjoin(DB::raw("tbl_variant_name AS var_name"),"evariant_id","=", "variant_id")
              ->leftjoin(DB::raw("tbl_option_value AS op_value"),"op_value.option_value_id","=","var_name.option_value_id")
              ->leftjoin(DB::raw("tbl_option_name AS op_name"),"op_name.option_name_id","=","var_name.option_name_id")
              ->orderBy("variant_name_order");
    }

    /* JOIN TBL VARIANT NAME ONLY */
    public function scopeVariantOnly($query)
    {
        $query->leftjoin("tbl_variant_name", "variant_id", "=", "evariant_id");
    }

    public function scopeFirstImage($query)
    {
        $query->leftjoin(DB::raw("(SELECT * FROM tbl_ec_variant_image GROUP BY eimg_variant_id) AS variant_img"),"eimg_variant_id", "=", "evariant_id")
              ->leftjoin("tbl_image","image_id","=","eimg_image_id")->orderBy("default_image","DESC");
    }

    public function scopeImageOnly($query)
    {
        $query->select("image_id","image_path","default_image")
              ->rightjoin("tbl_ec_variant_image","evariant_id","=","eimg_variant_id")
              ->join("tbl_image","image_id","=","eimg_image_id")
              ->orderBy("default_image","DESC");
    }
}