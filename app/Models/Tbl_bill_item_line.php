<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_bill_item_line extends Model
{
    protected $table = 'tbl_bill_item_line';
	protected $primaryKey = "itemline_id";
    public $timestamps = false;

    public function scopeUm($query)
    {    	
        return $query->leftjoin("tbl_unit_measurement_multi", "multi_id", "=", "itemline_um");
    }

    public function scopeItem($query)
    {
    	 return $query->join('tbl_item', 'tbl_item.item_id', '=', 'tbl_bill_item_line.itemline_item_id');
    }
    public function scopeRef($query)
    {
         return $query->join('tbl_purchase_order', 'tbl_purchase_order.po_id', '=', 'tbl_bill_item_line.itemline_ref_id');
    }

    public static function scopeVendor($query)
    {
        return $query->join("tbl_bill","tbl_bill.bill_id","=","tbl_bill_item_line.itemline_bill_id")
                    ->join("tbl_vendor","tbl_vendor.vendor_id","=","tbl_bill.bill_vendor_id")
                    ->leftjoin("tbl_vendor_address","ven_addr_vendor_id","=","vendor_id")
                    ->leftjoin("tbl_vendor_other_info","ven_info_vendor_id","=","vendor_id");
    }

}
