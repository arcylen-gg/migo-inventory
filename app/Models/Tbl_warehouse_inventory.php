<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_warehouse_inventory extends Model
{
	protected $table = 'tbl_warehouse_inventory';
	protected $primaryKey = "inventory_id";
    public $timestamps = false;

    public function scopecheck_inventory_single($query, $warehouse_id = 0, $item_id = 0)
    {
    	$query->where('warehouse_id', $warehouse_id)->where('inventory_item_id',$item_id)->select(DB::raw('sum(inventory_count) as inventory_count'));
    	return $query;
    }
    public function scopeItem($query)
    {
    	return $query->join("tbl_item","tbl_item.item_id","=","inventory_item_id")
                     ->leftjoin("tbl_unit_measurement_multi","tbl_item.item_measurement_id","=","tbl_unit_measurement_multi.multi_um_id");
    }
    public function scopeWarehouse($query)
    {
    	return $query->leftjoin("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_warehouse_inventory.warehouse_id");
    }
    public function scopeInventoryslip($query)
    {
    	return $query->leftjoin("tbl_inventory_slip","tbl_inventory_slip.inventory_slip_id","=","tbl_warehouse_inventory.inventory_slip_id");
    }
    public function scopeSerialnumber($query)
    {
    	return $query->leftjoin("tbl_inventory_serial_number","tbl_inventory_serial_number.serial_inventory_id","=","tbl_warehouse_inventory.inventory_id");
    }
}