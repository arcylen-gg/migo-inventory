<?php
namespace App\Models;
use DB;

use Illuminate\Database\Eloquent\Model;
use App\Globals\Merchant;
class Tbl_warehouse extends Model
{
	protected $table = 'tbl_warehouse';
	protected $primaryKey = "warehouse_id";
    public $timestamps = false;

    public function scopeInventory($query)
    {
    	 return $query->leftjoin('tbl_warehouse_inventory', 'tbl_warehouse_inventory.warehouse_id', '=', 'tbl_warehouse.warehouse_id');
    }
    public function scopeWarehouseitem($query)
    {
        $ismerchant = Merchant::ismerchant();
        if($ismerchant == 1)
        {
            $user_id = Merchant::getuserid();
            return $query->leftjoin('tbl_sub_warehouse', 'tbl_sub_warehouse.warehouse_id', '=', 'tbl_warehouse.warehouse_id')
                 ->leftjoin('tbl_item','tbl_item.item_id','=','tbl_sub_warehouse.item_id')
                 ->leftjoin('tbl_category','type_id','=','item_category_id')
                 ->leftjoin('tbl_unit_measurement','tbl_unit_measurement.um_id','=','tbl_item.item_measurement_id')
                ->join("tbl_item_merchant_request","tbl_item_merchant_request.merchant_item_id","=","tbl_item.item_id")
                ->where('item_merchant_requested_by', $user_id)
                ->where("tbl_item.archived",0);

            
        }   
        else
        {
            return $query->leftjoin('tbl_sub_warehouse', 'tbl_sub_warehouse.warehouse_id', '=', 'tbl_warehouse.warehouse_id')
                 ->leftjoin('tbl_item','tbl_item.item_id','=','tbl_sub_warehouse.item_id')
                     ->leftjoin('tbl_category','type_id','=','item_category_id')
                 ->leftjoin('tbl_unit_measurement','tbl_unit_measurement.um_id','=','tbl_item.item_measurement_id')
                ->where("tbl_item.archived",0);
        } 

    }

    public function scopeWarehouseitem_woempties($query)
    {
        return $query->leftjoin('tbl_sub_warehouse', 'tbl_sub_warehouse.warehouse_id', '=', 'tbl_warehouse.warehouse_id')
                     ->leftjoin('tbl_item','tbl_item.item_id','=','tbl_sub_warehouse.item_id')
                     ->leftjoin('tbl_unit_measurement','tbl_unit_measurement.um_id','=','tbl_item.item_measurement_id')
                     ->leftjoin('tbl_category','type_id','=','item_category_id')
                     ->where("tbl_item.archived",0);
    }
    public function scopeShop($query)
    {
        return $query->leftjoin('tbl_shop','warehouse_shop_id','=','shop_id');
    }
    public function scopeSerialnumber($query)
    {
        return $query->leftJoin('tbl_inventory_serial_number', 'tbl_item.item_id', '=', 'tbl_inventory_serial_number.item_id');
    }
    public function scopeWarehouseitem_vendor($query,$vendor_id = 0)
    {
         return $query->leftjoin('tbl_sub_warehouse', 'tbl_sub_warehouse.warehouse_id', '=', 'tbl_warehouse.warehouse_id')
                     ->leftjoin('tbl_item','tbl_item.item_id','=','tbl_sub_warehouse.item_id')
                     ->leftjoin("tbl_vendor_item","tag_item_id","=","tbl_item.item_id")
                     ->orderBy("tag_vendor_id","<=",$vendor_id);
    }
    public function scopeselect_info($query, $shop_id = 0, $archived = 0)
    {
        $query->selectRaw('*, tbl_warehouse.warehouse_id as warehouse_id')
                    ->where("tbl_warehouse.archived",$archived)
                    ->where("tbl_warehouse.warehouse_shop_id", $shop_id);
        return $query;
    }
    public function scopeselect_inventory($query, $warehouse_id = 0, $item_id = 0, $archived = 0)
    {
        $query->leftjoin('tbl_warehouse_inventory','tbl_warehouse_inventory.inventory_item_id','=','tbl_item.item_id')
            ->where('tbl_sub_warehouse.warehouse_id', $warehouse_id)
            ->where('tbl_warehouse_inventory.warehouse_id','=',$warehouse_id);
        if($item_id != 0)
        {
            $query->where('tbl_item.item_id',$item_id);
            dd($item_id);
        }
            $query->where('tbl_item.archived',$archived)             
                 ->groupBy('tbl_warehouse_inventory.inventory_item_id')
                 ->select(DB::raw('tbl_unit_measurement.um_id as product_um, tbl_item.item_id as product_id, tbl_item.item_name as product_name, tbl_item.item_sku as product_sku, sum(tbl_warehouse_inventory.inventory_count) as product_warehouse_stocks, tbl_sub_warehouse.item_reorder_point as product_reorder_point, tbl_item.has_serial_number as has_serial_number, is_mts ,sum(tbl_warehouse_inventory.inventory_count) as product_current_qty'))
                 ->orderBy("product_warehouse_stocks","DESC");

        return $query;
    }
}