<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_category extends Model
{
	protected $table = 'tbl_category';
	protected $primaryKey = "type_id";
    public $timestamps = true;

    public function scopesel($query, $shop_id = 0, $archived = 0)
    {
    	$query->where('type_shop',$shop_id)->where('archived',$archived);
    }

    public function scopeShop($query, $shop = '', $archived = 0)
    {
    	$query->join('tbl_shop', function($on) use($shop, $archived)
    	{
    		$on->on('type_shop','=','shop_id');
    		$on->where('shop_key','=', $shop);
    		$on->where('archived', '=', $archived);
    	});
    }

    public function scopesearch($query, $search = '' , $type_category = '', $shop_id = 0)
    {
        $query = Tbl_category::where('type_shop', $shop_id)->where('archived',0);
        
        if($type_category != '' && $type_category != 'all'){
            $query ->where('type_category',$type_category);
        }
        if($search != '')
        {   
            $query->where('type_name','like','%'.$search.'%');
        }
        if($search == '')
        {
            $query ->where('type_parent_id',0)->where('type_shop', $shop_id)->where('archived',0);
        }
        return $query;
    }

    public function scopeselecthierarchy($query, $shop_id = 0, $type_parent_id = 0, $cat_type = array("all","services","inventory","non-inventory","bundles"),$archived = 0)
    {
        $query->where('type_shop', $shop_id)->where('type_parent_id', $type_parent_id)->where("archived",$archived);

        if($cat_type != null)
        {
            $query->whereIn("type_category",$cat_type);
        }

        return $query;
    }

    public function scopeProduct($query)
    {
        return $query->select("tbl_category.*")
                     ->join("tbl_ec_product","eprod_category_id","=","type_id")
                     ->groupBy("type_id");
    }
}