<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_warehouse_issuance_report_item extends Model
{
	protected $table = 'tbl_warehouse_issuance_report_item';
	protected $primaryKey = "wis_item_id";
    public $timestamps = false;

    public function scopeInventory_item($query)
    {
    	return $query->selectRaw("*, count(record_log_id) as wis_item_quantity")
    				 ->leftjoin('tbl_warehouse_inventory_record_log','tbl_warehouse_inventory_record_log.record_log_id','=','record_log_item_id')
    				 ->leftjoin('tbl_item','tbl_item.item_id','=','record_item_id');
    }
}