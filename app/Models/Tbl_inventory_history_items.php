<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_inventory_history_items extends Model
{
	protected $table = 'tbl_inventory_history_items';
	protected $primaryKey = "history_item_id";
    public $timestamps = false;

    public function scopeHistory($query)
    {
    	return $query->leftjoin('tbl_inventory_history','tbl_inventory_history_items.history_id','=','tbl_inventory_history.history_id');
    }
}