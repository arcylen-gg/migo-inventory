<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_inventory_slip extends Model
{
    protected $table = 'tbl_inventory_slip';
	protected $primaryKey = "inventory_slip_id";
    public $timestamps = true;

    public function scopeShop($query)
    {
    	return $query->leftjoin("tbl_shop","tbl_shop.shop_id","=","tbl_inventory_slip.inventory_slip_shop_id");
    }
    public function scopeVendor($query)
    {
    	return $query->leftjoin("tbl_vendor","tbl_vendor.vendor_id","=","tbl_inventory_slip.inventory_source_id");
    }
    public function scopeUser($query)
    {
        return $query->leftjoin("tbl_user","user_id","=","tbl_inventory_slip.slip_user_id");
    }
    public function scopeCustomer($query)
    {
        return $query->leftjoin("tbl_vendor","tbl_vendor.vendor_id","=","tbl_inventory_slip.inventory_source_id");
    }
    public function scopeWarehouse($query)
    {
    	return $query->leftjoin("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_inventory_slip.warehouse_id");
    }
}
