<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_inventory_serial_number extends Model
{
	protected $table = 'tbl_inventory_serial_number';
	protected $primaryKey = "serial_id";
    public $timestamps = true;

    public function scopeItem($query)
    {
    	return $query->leftjoin("tbl_item","tbl_item.item_id","=","tbl_inventory_serial_number.item_id")
                     ->selectRaw("*,tbl_item.created_at as item_created_at, tbl_item.updated_at as item_updated_at, tbl_item.item_id as serial_item_id");
    }
    public function scopeWarehouse_inventory($query)
    {
    	return $query->leftjoin("tbl_warehouse_inventory","tbl_warehouse_inventory.inventory_id","=","tbl_inventory_serial_number.serial_inventory_id");
    }
}