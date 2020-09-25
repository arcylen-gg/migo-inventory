<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_customer_wis_item extends Model
{
    protected $table = 'tbl_customer_wis_item';
	protected $primaryKey = "cust_wis_item_id";
    public $timestamps = true;

    public function scopeInventoryItem($query)
    {
    	return $query->selectRaw("*, count(cust_wis_record_log_id) as wis_item_quantity")
    				 ->leftjoin('tbl_warehouse_inventory_record_log','tbl_warehouse_inventory_record_log.record_log_id','=','cust_wis_record_log_id')
    				 ->leftjoin('tbl_item','tbl_item.item_id','=','record_item_id');
    }
}
