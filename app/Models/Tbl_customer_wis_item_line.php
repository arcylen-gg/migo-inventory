<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_customer_wis_item_line extends Model
{
    protected $table = 'tbl_customer_wis_item_line';
	protected $primaryKey = "itemline_id";
    public $timestamps = false;
    
    public function scopeUm($query)
    {
        return $query->leftjoin("tbl_unit_measurement_multi", "multi_id", "=", "itemline_um");
    }
    public function scopeItem($query)
    {
        return $query->leftjoin("tbl_item", "item_id", "=", "itemline_item_id");    	
    }
    public function scopeBinLocation($query)
    {
        return $query->leftjoin("tbl_warehouse", "warehouse_id", "=", "itemline_sub_wh_id");        
    }
}