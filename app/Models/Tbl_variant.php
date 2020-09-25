<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_variant extends Model
{
	protected $table = 'tbl_variant';
	protected $primaryKey = "variant_id";
    public $timestamps = false;
    public function scopeVariant_name($query)
    {
    	$query->join("tbl_variant_name","tbl_variant_name.variant_id","=","tbl_variant.variant_id")
    		  ->join("tbl_option_name","tbl_option_name.option_name_id","=","tbl_variant_name.option_name_id")
    		  ->join("tbl_option_value","tbl_option_value.option_value_id","=","tbl_variant_name.option_value_id");
    }

    public function scopeVariant($query, $product_id = 0){
    	$query->join('tbl_variant_name','tbl_variant_name.variant_id','=','tbl_variant.variant_id')
			->join('tbl_option_value','tbl_option_value.option_value_id','=','tbl_variant_name.option_value_id')
			->where('variant_product_id',$product_id)
			->orderBy('tbl_variant.variant_id','asc')
			->orderBy('tbl_variant_name.option_name_id','asc')
			->orderBy('tbl_variant_name.option_value_id','asc');
			
		return $query;
    }

    public function scopeVariantOnly($query, $variant_id = 0){
        $query->join('tbl_variant_name','tbl_variant_name.variant_id','=','tbl_variant.variant_id')
            ->join('tbl_option_value','tbl_option_value.option_value_id','=','tbl_variant_name.option_value_id')
            ->where('tbl_variant.variant_id',$variant_id)
            ->orderBy('tbl_variant_name.option_name_id','asc')
            ->orderBy('tbl_variant_name.option_value_id','asc');
            
        return $query;
    }

    public function scopeVariant_info($query)
    {
        $query->join('tbl_product','product_id',"=",'variant_product_id')
              ->join(DB::raw("(Select * from tbl_image group by image_reason_id) as image"),"image_reason_id","=","product_id")
              ->join("tbl_shop","shop_id","=","product_shop")
              ->join("tbl_product_vendor","vendor_id","=","product_vendor")
              ->join("tbl_category","type_id","=","product_type")
              ->leftjoin(DB::raw('(Select variant_product_id, min(variant_price) as min_price, max(variant_price) as max_price from tbl_variant group by variant_product_id) as v_price'),"v_price.variant_product_id","=","product_id");
    }

    public function scopeVariant_view($query)
    {
        $query->leftjoin("view_product_variant","view_product_variant.variant_id","=","tbl_variant.variant_id");
    }
    
    public function scopeVariant_inform($query)
    {
        $query->join('tbl_product','product_id',"=",'variant_product_id')
              ->join(DB::raw("(Select * from tbl_image group by image_reason_id) as image"),"image_reason_id","=","product_id")
              ->join("tbl_shop","shop_id","=","product_shop")
              ->join("tbl_product_vendor","vendor_id","=","product_vendor")
              ->join("tbl_category","type_id","=","product_type")
              ->leftjoin(DB::raw('(Select variant_product_id, min(variant_price) as min_price, max(variant_price) as max_price from tbl_variant group by variant_product_id) as v_price'),"v_price.variant_product_id","=","product_id");
    }
}
