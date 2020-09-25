<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_requisition_slip_item extends Model
{
	protected $table = 'tbl_requisition_slip_item';
	protected $primaryKey = "rs_itemline_id";
    public $timestamps = false;

    public function scopeVendor($query)
    {
    	return $query->leftjoin('tbl_vendor','rs_vendor_id','=','vendor_id');
    }
    public function scopeItem($query)
    {
    	return $query->leftjoin('tbl_item','rs_item_id','=','item_id');    	
    }
    public static function scopeUm($query)
    {
        return $query->leftjoin("tbl_unit_measurement_multi", "multi_id", "=", "rs_item_um");
    }
    public static function scopePRInfo($query)
    {
        $query->join('tbl_requisition_slip', 'tbl_requisition_slip.requisition_slip_id', '=', 'tbl_requisition_slip_item.rs_id')
              ->join('tbl_vendor', 'tbl_vendor.vendor_id', '=', 'tbl_requisition_slip_item.rs_vendor_id');

        return $query;
    }
    public static function scopeRRLineVendor($query)
    {
        $query->join('tbl_requisition_slip', 'tbl_requisition_slip.requisition_slip_id', '=', 'tbl_requisition_slip_item.rs_id')
              ->join('tbl_vendor', 'tbl_vendor.vendor_id', '=', 'tbl_requisition_slip_item.rs_vendor_id');

        return $query;
    }
}