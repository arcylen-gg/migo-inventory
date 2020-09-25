<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_product extends Model
{
	protected $table = 'tbl_product';
	protected $primaryKey = "product_id";
    public $timestamps = false;
    
    public function scopeVariant($query)
    {
        $query->selectRaw("tbl_variant.*,tbl_product.*,tbl_option_name.*,tbl_option_value.*, GROUP_CONCAT(option_name ORDER BY variant_name_order SEPARATOR ',') AS option_name, GROUP_CONCAT(option_value ORDER BY variant_name_order SEPARATOR ',') AS option_value, GROUP_CONCAT(option_value ORDER BY variant_name_order SEPARATOR ' • ') AS option_value_dot")
              ->join("tbl_variant","tbl_variant.variant_product_id","=","tbl_product.product_id")
              ->leftjoin("tbl_variant_name","tbl_variant_name.variant_id","=","tbl_variant.variant_id")
              ->leftjoin("tbl_option_name","tbl_option_name.option_name_id","=","tbl_variant_name.option_name_id")
              ->leftjoin("tbl_option_value","tbl_option_value.option_value_id","=","tbl_variant_name.option_value_id")
              ->leftjoin("tbl_image","variant_main_image","=","image_id")
              ->groupBy("tbl_variant.variant_id");
    }

    public function scopeVariant_column($query)
    {
        $query->join("tbl_variant","tbl_variant.variant_product_id","=","tbl_product.product_id")
              ->join("tbl_variant_name","tbl_variant_name.variant_id","=","tbl_variant.variant_id")
              ->join("tbl_option_name","tbl_option_name.option_name_id","=","tbl_variant_name.option_name_id")
              ->join("tbl_option_value","tbl_option_value.option_value_id","=","tbl_variant_name.option_value_id")
              ->groupBy("tbl_variant_name.option_name_id")->groupBy("variant_name_order")
              ->orderBy("variant_name_order");;
    }

    public function scopeVariant_values($query)
    {
        $query->selectRaw("distinct option_value, product_id, option_name, tbl_option_name.option_name_id")
              ->join("tbl_variant","tbl_variant.variant_product_id","=","tbl_product.product_id")
              ->join("tbl_variant_name","tbl_variant_name.variant_id","=","tbl_variant.variant_id")
              ->join("tbl_option_name","tbl_option_name.option_name_id","=","tbl_variant_name.option_name_id")
              ->join("tbl_option_value","tbl_option_value.option_value_id","=","tbl_variant_name.option_value_id");
    }

    public function scopeInfo($query, $shop)
    {
        $query->selectRaw(DB::raw('*, sum(variant_inventory_count) as product_inventory'))
              ->join("tbl_shop","shop_id","=","product_shop")
              ->join("tbl_product_vendor","vendor_id","=","product_vendor")
              ->join("tbl_category","type_id","=","product_type")
              ->leftjoin(DB::raw("(Select * from tbl_image group by image_reason_id) as image"),"image_reason_id","=","product_id")
              ->leftjoin("tbl_variant", function($on)
                {
                  $on->on("variant_product_id","=","product_id");
                })
              ->where( function ($where) use($shop)
              {
                    $where->where("shop_id", $shop)
                          ->orWhere("shop_key", $shop);
              })
              ->groupBy("product_id");
    }

    public function scopeInfoitem($query, $shop)
    {
        $query->leftjoin("tbl_item","tbl_product.item_id","=","tbl_item.item_id");
    }

    public function scopeSelectItem($query, $shop_id = 0, $archived = 0, $vendor = 0, $type = 0)
    {
       
        $query->join('tbl_image','tbl_image.image_reason_id','=','tbl_product.product_id');

        if($vendor != 0)
        {
            $query->where('tbl_product.product_vendor',$vendor);
        }
        if($type != 0)
        {
            $query->where('tbl_product.product_type',$type);
        }

            $query  ->where('tbl_product.archived',$archived)
                    ->where('tbl_product.product_shop',$shop_id)
                    ->where('tbl_image.image_reason','product')
                    ->groupBy('tbl_product.product_id');
        return $query;
    }

    public function scopeGenInfo($query)
    {
        $query->join("tbl_shop","shop_id","=","product_shop")
              ->join("tbl_product_vendor","vendor_id","=","product_vendor")
              ->join("tbl_category","type_id","=","product_type")
              ->join(DB::raw("(Select * from tbl_image group by image_reason_id) as image"),"image_reason_id","=","product_id")
              ->leftjoin("tbl_variant", function($on)
                {
                  $on->on("variant_product_id","=","product_id");
                  $on->where("variant_single","=",1);
                })
              ->leftjoin(DB::raw('(Select variant_product_id, min(variant_price) as min_price, max(variant_price) as max_price from tbl_variant group by variant_product_id) as v_price'),"v_price.variant_product_id","=","product_id");
        return $query;
    }
    
    public function scopegetinventory($query, $shop_id = 0, $archived = 0, $filter = '')
    {
        $query->join('view_product_variant','view_product_variant.product_id','=','tbl_product.product_id')
              // ->join('tbl_image','tbl_image.image_id','=','view_product_variant.variant_main_image')
              ->where('tbl_product.product_shop',$shop_id)
              ->where('tbl_product.archived',0);
        
        return $query;
    }
}
