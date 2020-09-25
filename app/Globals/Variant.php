<?php
namespace App\Globals;
use App\Models\Tbl_product_search;
use App\Models\Tbl_shop;
use DB;

class Variant
{
	public static function search($shop_id = 0, $search = '')
	{
	
		$data['_item'] = Tbl_product_search::join('view_product_variant','view_product_variant.variant_id','=','tbl_product_search.variant_id')
						->join('tbl_product','tbl_product.product_id','=','view_product_variant.product_id')
						->join('tbl_image','tbl_image.image_id','=','view_product_variant.variant_main_image')
						->whereRaw("MATCH(tbl_product_search.body) AGAINST('*".$search."*' IN BOOLEAN MODE)")
						->where('tbl_product.product_shop',$shop_id)
						->orderBy('view_product_variant.product_name','asc')
						->get();
		// dd($search);
		// $data['_item'] = Tbl_product_search::whereRaw("MATCH(tbl_product_search.body) AGAINST('*".$search."*' IN BOOLEAN MODE)")
		// 				->get();
    
    	return $data;
	}
}