<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_order_item extends Model
{
	protected $table = 'tbl_order_item';
	protected $primaryKey = "tbl_order_item_id";
    public $timestamps = false;

    public function scopeSel($query, $oder_id = 0, $shop_id = 0)
    {
    	$query->join('tbl_variant','tbl_variant.variant_id','=','tbl_order_item.variant_id')
    		  ->join('tbl_image','tbl_image.image_id','=','tbl_variant.variant_main_image')
    		  ->join('tbl_order','tbl_order.tbl_order_id','=','tbl_order_item.tbl_order_id')
              ->join('tbl_product','tbl_product.product_id','=','tbl_variant.variant_product_id');
        if($shop_id != 0){
            $query->where('tbl_order.shop_id',$shop_id);
        }
    		  
        $query->where('tbl_order.tbl_order_id',$oder_id);

    	return $query;

    }
    
    public function scopeCompleteDetails($query)
    {
        return $query->join('tbl_order','tbl_order.tbl_order_id','=','tbl_order_item.tbl_order_id')
                    ->join('tbl_customer','tbl_customer.customer_id','=','tbl_order.customer_id')
                    ->join('view_product_variant','view_product_variant.variant_id','=','tbl_order_item.variant_id')
                    ->join('tbl_product','tbl_product.product_id','=','view_product_variant.product_id')
                    ->join('tbl_category','type_id','=','product_type');
    }
    
    public function scopeOrder($query)
    {
        return $query->join('tbl_order','tbl_order.tbl_order_id','=','tbl_order_item.tbl_order_id');
              
    }
    
    public function scopeProduct($query)
    {
        return $query->join('tbl_variant','tbl_variant.variant_id','=','tbl_order_item.variant_id')
                    ->join('tbl_product','tbl_product.product_id','=','tbl_variant.variant_product_id')
                    ->join('tbl_category','type_id','=','product_type');
    }
    
    public function scopeVariant($query)
    {
        return $query->rightjoin('view_product_variant','view_product_variant.variant_id','=','tbl_order_item.variant_id')
                    ->join('tbl_product','tbl_product.product_id','=','view_product_variant.product_id')
                    ->join('tbl_category','type_id','=','product_type');
    }
    
    //require Order function
    public function scopeCustomer($query)
    {
        return $query->join('tbl_customer','tbl_customer.customer_id','=','tbl_order.customer_id');
    }

}