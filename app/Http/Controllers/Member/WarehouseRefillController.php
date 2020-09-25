<?php

namespace App\Http\Controllers\Member;

use Request;
use Redirect;
use App\Http\Controllers\Controller;

use App\Globals\Vendor;
use App\Globals\Item;
use App\Globals\UnitMeasurement;

use App\Models\Tbl_warehouse;
class WarehouseRefillController extends Member
{   
	public function getIndex()
    {
    	$warehouse_id = Request::input("w_id");
    	$warehouse_data = Tbl_warehouse::where('warehouse_id',$warehouse_id)->where('warehouse_shop_id',$this->user_info->shop_id)->first();
    	if($warehouse_data)
    	{
	    	$data['page'] = 'Refill';
	    	$data['action'] = '/member/item/warehouse/v2/refill/submit';
	        $data["_vendor"]    = Vendor::getAllVendor('active');
	        $data['_item']      = Item::get_all_category_item([1,4]);

	    	return view('member.warehouse.refill_warehouse.refill',$data);
    	}
    	else
    	{
    		return Redirect::to('member/item/warehouse');
    	}

    }
    public function postSubmit()
    {
    	$warehouse_id = Request::input("warehouse_id");
        $remarks = Request::input("remarks");
        $reason_refill = Request::input("reason_refill") == "other" ? "other" : "vendor";
        $refill_source = Request::input("reason_refill") == "other" ? 0 : Request::input("reason_refill");

        $item_id = Request::input("item_id");
        $item_um = Request::input("item_um");
        $quantity_product = Request::input("quantity");

        $warehouse_refill_product = null;
        foreach ($item_id as $key => $value) 
        {
            if($value != 0)
            {
                $item_type = Item::type($value);    
                if($item_type == 1)
                {
                    $warehouse_refill_product[$key]['product_id'] = $value;
                    $warehouse_refill_product[$key]['quantity'] = str_replace(",","",UnitMeasurement::um_qty($item_um[$key]) * $quantity_product[$key]);
                }
                else if ($item_type >= 4)
                {
                    $bundle_item = Item::get_item_bundle($value)["bundle"];
                    foreach ($bundle_item as $key_b => $value_b) 
                    {
                        $warehouse_refill_product['b'.$key]['product_id'] = $value_b['bundle_item_id'];
                        $warehouse_refill_product['b'.$key]['quantity'] = str_replace(",","",UnitMeasurement::um_qty($value_b['bundle_um_id']) * $value_b['bundle_qty']);
                    }
                }
            }
        }

        dd($warehouse_refill_product);
        $data = Warehouse::inventory_refill($warehouse_id, $reason_refill, $refill_source, $remarks, $warehouse_refill_product,'json');

        return $data;
    }
}
