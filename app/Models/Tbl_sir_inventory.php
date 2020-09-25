<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_sir_inventory extends Model
{
	protected $table = 'tbl_sir_inventory';
	protected $primaryKey = "sir_inventory_id";
    public $timestamps = true;

    public function scopeItem($query)
    {
    	 return $query->join('tbl_item', 'tbl_item.item_id', '=', 'sir_item_id')->selectRaw("*, sum(tbl_sir_inventory.sir_inventory_count) as sir_return_item_count")->where("sir_inventory_ref_name","credit_memo");
    }
}
