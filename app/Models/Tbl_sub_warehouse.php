<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_sub_warehouse extends Model
{
	protected $table = 'tbl_sub_warehouse';
	protected $primaryKey = "sub_warehouse_item_id";
    public $timestamps = false;

    public function scopeselect_item($query, $warehouse_id = 0)
    {
    	$query->join("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_sub_warehouse.warehouse_id")
             ->join("tbl_item","tbl_item.item_id","=","tbl_sub_warehouse.item_id")
             ->where("tbl_item.archived",0)
             ->where("tbl_sub_warehouse.warehouse_id",$warehouse_id);

        return $query;
    }
    public function scopewarehousetowarehouse($query, $from_id = 0, $to_id = 0)
    {
        $query->leftjoin('tbl_sub_warehouse as to_warehouse','to_warehouse.item_id','=','tbl_sub_warehouse.item_id')
              ->leftjoin('tbl_item','tbl_item.item_id','=','tbl_sub_warehouse.item_id')
              ->where('tbl_sub_warehouse.warehouse_id',$from_id)
              ->where('to_warehouse.warehouse_id',$to_id)
              ->where('tbl_item.archived',0)
              ->groupBy('to_warehouse.item_id')
              ->select(DB::raw('tbl_item.item_id as product_id, tbl_item.item_name as product_name, tbl_item.item_sku as product_sku, (select sum(from_inventory.inventory_count) 
                from tbl_warehouse_inventory as from_inventory 
                where from_inventory.warehouse_id = tbl_sub_warehouse.warehouse_id and from_inventory.inventory_item_id = tbl_sub_warehouse.item_id) as product_source_qty, (select sum(to_inventory.inventory_count) 
                from tbl_warehouse_inventory as to_inventory 
                where to_warehouse.warehouse_id = to_inventory.warehouse_id and to_inventory.inventory_item_id = to_warehouse.item_id) as product_current_qty, to_warehouse.item_reorder_point as product_reorder_point'));
        return $query;
    }

    public function scopeinventory($query)
    {
        $query->join("tbl_warehouse_inventory")->where("tbl_sub_warehouse.warehouse_id","=","tbl_warehouse.warehouse_id");

        return $query;
    }
}