<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_warehouse_receiving_report_itemline extends Model
{
	protected $table = 'tbl_warehouse_receiving_report_itemline';
	protected $primaryKey = "rrline_id";
    public $timestamps = false;

    public function scopeUm($query)
    {
        return $query->leftjoin('tbl_unit_measurement_multi','multi_um_id','=','rr_um');
    }
    public function scopeItem($query)
    {
        return $query->leftjoin('tbl_item','item_id','=','rr_item_id');
    }
}