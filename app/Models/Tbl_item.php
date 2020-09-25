<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use DB;
class Tbl_item extends Model
{
    use FullTextSearch;
    // use Searchable;
    protected $table = 'tbl_item';
    protected $primaryKey = "item_id";
    public $timestamps = true;
    protected $searchable = [
        'item_name',
        'item_sku',
        'item_sales_information',
        'item_purchasing_information',
        'item_barcode'
    ];
    
    public function scopeSearchName($query, $keyword)
    {
        $query->where("tbl_item.item_name", "LIKE", "%" . $keyword . "%");
        return $query;
    }
    public function scopeSearchSKU($query, $keyword)
    {
        $query->where("tbl_item.item_sku", "LIKE", "%" . $keyword . "%");

        return $query;
    }
    public function scopeActive($query)
    {
        $query->where("tbl_item.archived", 0);
        return $query;
    }
    public function scopeType($query)
    {
        $query->leftjoin('tbl_item_type','tbl_item_type.item_type_id','=','tbl_item.item_type_id');
        return $query;
    }
    public function scopeWarehouse($query)
    {
        $query->leftjoin('tbl_warehouse','tbl_warehouse.warehouse_shop_id','=','tbl_item.shop_id');
        return $query;
    }
    public function scopeMembership($query)
    {
        return $query->leftjoin("tbl_membership","tbl_membership.membership_id","=","tbl_item.membership_id");
    }
    public function scopeCategory($query)
    {
        $query->leftjoin('tbl_category','type_id','=','item_category_id');
        return $query;
    }
    public function scopeUm_multi($query)
    {
        return $query->leftjoin('tbl_unit_measurement_multi','multi_um_id','=','item_measurement_id');         
    }
    public function scopeUm_item($query)
    {
        return $query->leftjoin('tbl_unit_measurement_multi','multi_um_id','=','item_measurement_id');
         
    }
    public function scopeselitem($query, $item_id)
    {
        $query->where('item_id',$item_id);
    }

    public function scopeAccountAsset($query)
    {
        return $query->leftjoin("tbl_chart_of_account", "account_id", "=", "item_asset_account_id")
                     ->leftjoin("tbl_chart_account_type", "chart_type_id", "=", "account_type_id");
    }

    public function scopeItemDiscount($query)
    {
        return $query->leftjoin("tbl_item_discount","tbl_item_discount.discount_item_id","=","tbl_item.item_id");
    }
    public function scopeAccountIncome($query)
    {
        return $query->leftjoin("tbl_chart_of_account", "account_id", "=", "item_income_account_id")
                     ->leftjoin("tbl_chart_account_type", "chart_type_id", "=", "account_type_id");
    }

    public function scopeAccountExpense($query)
    {
        return $query->leftjoin("tbl_chart_of_account as asset", "account_id", "=", "item_expense_account_id")
                     ->leftjoin("tbl_chart_account_type", "chart_type_id", "=", "account_type_id");
    }

    public function scopeBundle($query)
    {
        return $query->leftjoin("tbl_item_bundle as bundle","bundle_bundle_id","=","item_id")
                     ->join("tbl_item as bundle_item","item_id","=","bundle_item_id");
    }

    public function scopeInventory($query, $warehouse_id = null)
    {
        return $query->selectRaw("*, IFNULL(sum(inventory_count),0) as inventory_count")
                     ->leftjoin(DB::raw("(Select wi.* from tbl_warehouse_inventory wi INNER JOIN tbl_warehouse wh on wh.warehouse_id = wi.warehouse_id where wh.archived = 0) warehouse"), function($join) use ($warehouse_id)
                     {
                        $join->on("inventory_item_id","=","item_id");
                        if($warehouse_id)
                        {
                            $join->on("warehouse.warehouse_id","=", DB::raw($warehouse_id));
                        }
                     })
                     ->groupBy("item_id");
    } 
    public function scopeInventorynew($query, $warehouse_id = null)
    {
        return $query->selectRaw("*, IFNULL(sum(invty_qty),0) as inventory_count")
                     ->leftjoin(DB::raw("(Select wi.* from tbl_monitoring_inventory wi INNER JOIN tbl_warehouse wh on wh.warehouse_id = wi.invty_warehouse_id where wh.archived = 0) warehouse"), function($join) use ($warehouse_id)
                     {
                        $join->on("invty_item_id","=","item_id");
                        if($warehouse_id)
                        {
                            $join->on("warehouse.invty_warehouse_id","=", DB::raw($warehouse_id));
                        }
                     })
                     ->groupBy("item_id");
    } 
    public function scopeRecordloginventory_old($query, $warehouse_id = null)
    {
        return $query->selectRaw("*, IFNULL(count(record_log_id),0) as inventory_count")
                     
                     ->leftjoin(DB::raw("(Select wi.* from tbl_warehouse_inventory_record_log wi INNER JOIN tbl_warehouse wh on wh.warehouse_id = wi.record_warehouse_id where wh.archived = 0) warehouse"), function($join) use ($warehouse_id)
                     {
                        $join->on("record_item_id","=","item_id");
                        if($warehouse_id)
                        {
                            $join->on("warehouse.record_warehouse_id","=", DB::raw($warehouse_id))
                            ->where('record_inventory_status',0)
                            ->where('item_in_use','unused');
                        }
                     })
                     ->groupBy("item_id");
    }
    public function scopeRecordloginventory($query, $warehouse_id = null, $all = false)
    {
        return $query->selectRaw("*, IFNULL(count(record_log_id),0) as inventory_count, count(CASE when record_count_inventory < 0 then record_count_inventory else 0 end) as offset_count")
                     ->leftjoin(DB::raw("(Select wi.* from tbl_warehouse_inventory_record_log wi INNER JOIN tbl_warehouse wh on wh.warehouse_id = wi.record_warehouse_id where wh.archived = 0) warehouse"), function($join) use ($warehouse_id, $all)
                     {
                        $join->on("record_item_id","=","item_id");
                        if($warehouse_id)
                        {
                            $join->on("warehouse.record_warehouse_id","=", DB::raw($warehouse_id));
                            if($all == false)
                            {
                                $join->where('record_inventory_status',0)
                                ->where('item_in_use','unused');
                            }
                        }
                     })
                     ->groupBy("item_id");
    }
    
    public function scopeInventorylog($query)
    {
        return $query->leftjoin('tbl_warehouse_inventory_record_log','record_item_id','=','item_id');
    }

    public function scopeNewPrice($query, $qty)
    {
        return $query->selectRaw("*, IFNULL(multiprice_price, item_price) as new_price")
                     ->leftJoin("tbl_item_multiple_price", function($on) use($qty)
                     {
                        $on->on("multiprice_item_id","=","item_id");
                        $on->on("multiprice_qty","=",DB::raw("(Select max(multiprice_qty) from tbl_item_multiple_price where multiprice_qty <= $qty and tbl_item.item_id = multiprice_item_id group by multiprice_item_id)"));
                     });                  
    }

    public function scopeMultiPrice($query)
    {
        return $query->select("multiprice_qty","multiprice_price")
                     ->join("tbl_item_multiple_price","multiprice_item_id","=","item_id");
    }
    public function scopeUm($query)
    {
        return $query->leftjoin("tbl_unit_measurement","item_measurement_id","=","um_id");
    }
    /* public function scopeOrderby($query, $column_name, $sort_column)
    {
        return $query->orderBy($column_name, $sort_column);
    }*/

    public function scopeProduct($query, $archived = null)
    {
        $query->join("tbl_ec_variant","item_id","=","evariant_item_id")
              ->join("tbl_ec_product","evariant_prod_id","=","eprod_id");
        if($archived != null) $query->where("tbl_ec_product.archived",$archived);

        return $query;
    }

    public function scopeWarehouseInventory($query, $shop_id = null)
    {
        return $query->selectRaw("warehouse_name, IFNULL(IF(sum(inventory_count) > 0, sum(inventory_count), 0), 0) as qty_on_hand")
                     ->leftJoin("tbl_warehouse_inventory","inventory_item_id","=","item_id")
                     ->join("tbl_warehouse as w","w.warehouse_id","=","tbl_warehouse_inventory.warehouse_id")
                     ->groupBy("w.warehouse_id");
    }
    public function scopeVendorItem($query)
    {
        return $query->join("tbl_vendor_item","tbl_vendor_item.tag_item_id","=","item_id")
                     ->join("tbl_vendor","tbl_vendor.vendor_id","=","tbl_vendor_item.tag_vendor_id");
    }
    
    public function scopeWarehousereorder($query)
    {
        return $query->leftjoin("tbl_warehouse_reorder","wr_item_id","=","item_id");
    }
}
