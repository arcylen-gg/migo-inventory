<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_warehouse_issuance_report_itemline extends Model
{
	protected $table = 'tbl_warehouse_issuance_report_itemline';
	protected $primaryKey = "wt_id";
    public $timestamps = false;

    public function scopeUm($query)
    {
        return $query->leftjoin('tbl_unit_measurement_multi','multi_id','=','wt_um');
    }
    public function scopeItem($query)
    {
        return $query->leftjoin('tbl_item','item_id','=','wt_item_id');
    }
    public function scopeBinLocation($query)
    {
        return $query->leftjoin("tbl_warehouse", "warehouse_id", "=", "wt_sub_wh_id");        
    }
}