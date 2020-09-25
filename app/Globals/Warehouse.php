<?php
namespace App\Globals;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_inventory_serial_number;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_item;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_settings;
use App\Models\Tbl_user;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_user_warehouse_access;
use App\Models\Tbl_inventory_history;
use App\Globals\Item;
use App\Globals\UnitMeasurement;

use App\Models\Tbl_manufacturer;
use App\Globals\ItemSerial;
use App\Globals\AuditTrail;
use App\Models\Tbl_unit_measurement_multi;
use DB;
use Carbon\Carbon;
use Session;
class Warehouse
{   
    public static function select_item_warehouse_per_bundle($warehouse_id, $manufacturer_id = 0, $keyword_search = '', $item_bundle_id = null)
    {
        if ($item_bundle_id) 
        {
            $bundle = Tbl_item::category()->where("item_id", $item_bundle_id)->where("shop_id", Warehouse::getShopId())->get();
        }
        else
        {
            $bundle = Tbl_item::category()->where("item_type_id", 4)->where('is_mts', 0)->where("shop_id", Warehouse::getShopId())->get();
        }

        $item = [];
        $bundle_data = [];

        foreach($bundle as $key => $value) 
        {
            //onhand = current
            $bundle_data[$key]["bundle_id"] = $value->item_id;
            $bundle_data[$key]["bundle_item_name"] = $value->item_name;
            $bundle_data[$key]["bundle_item_bardcode"] = $value->item_barcode;
            $bundle_data[$key]["bundle_item_description"] = $value->item_sales_information;
            $bundle_data[$key]["bundle_item_um"] = $value->item_measurement_id;
            $bundle_data[$key]["bundle_actual_stocks"] = null;
            $bundle_data[$key]["bundle_actual_stocks_um"] = null;
            $bundle_data[$key]["bundle_current_stocks"] = null;
            $bundle_data[$key]["bundle_current_stocks_um"] = null;

            $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$value->item_id)->get();

            $warehouse_bundle_qty = [];
            $bundle_qty_ = [];
            $bundle_qty_warehouse = [];
            $boo = false;
            foreach ($bundle_item as $key_bundle => $value_bundle) 
            {
               $warehouse_qty = Tbl_warehouse_inventory::check_inventory_single($warehouse_id, $value_bundle->bundle_item_id)->value('inventory_count');   

               
               $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty;
               $bundle_qty_warehouse[$value_bundle->bundle_item_id] = $warehouse_qty / $bundle_qty; 
               $bundle_qty_[$value_bundle->bundle_item_id] = $bundle_qty;
               if(isset(Item::info($value_bundle->bundle_item_id)->item_manufacturer_id))
               {
                    if($manufacturer_id != 0 && $manufacturer_id != null)
                    {
                        if(Item::info($value_bundle->bundle_item_id)->item_manufacturer_id != $manufacturer_id)
                        {
                            $boo = true;
                        }
                    }
               }
            }

            
            $bundle_data[$key]["item"] = $bundle_qty_warehouse;
            $bundle_data[$key]["bundle_qty"] = $bundle_qty_;

            if($boo == true)
            {
                unset($bundle_data[$key]);
            }
        }

        $um = null;
        $rem_item = [];
        foreach($bundle_data as $key_bundle_data => $value_bundle_data) 
        {
            $bundle_stocks = 0;
            if(count($value_bundle_data["item"]) > 0)
            {
                $bundle_stocks = floor(min($value_bundle_data["item"]));
            }

            foreach($value_bundle_data["item"] as $item_id_key => $value_item) 
            {
                $rem_item[$value_bundle_data["bundle_id"].".".$item_id_key]["item_id"] = $item_id_key;
                $rem_item[$value_bundle_data["bundle_id"].".".$item_id_key]["item_quantity"] = ($value_item - $bundle_stocks) * $value_bundle_data["bundle_qty"][$item_id_key];
            }

            $bundle_data[$key_bundle_data]["bundle_current_stocks_um"] = UnitMeasurement::um_view($bundle_stocks,$value_bundle_data["bundle_item_um"]);
            $bundle_data[$key_bundle_data]["bundle_current_stocks"] = $bundle_stocks;

            $qty = Purchasing_inventory_system::get_sir_stocks($warehouse_id, $value_bundle_data["bundle_id"]);
            $bundle_data[$key_bundle_data]['total_stock_sir'] = UnitMeasurement::um_view($qty,$value_bundle_data["bundle_item_um"]);

            $bundle_data[$key_bundle_data]["bundle_actual_stocks"] = $bundle_stocks + $qty;
            $bundle_data[$key_bundle_data]["bundle_actual_stocks_um"] = UnitMeasurement::um_view($bundle_data[$key_bundle_data]["bundle_actual_stocks"],$value_bundle_data["bundle_item_um"]);
        }

        // dd($rem_item);

        // foreach($rem_item as $key_rem => $value_rem)
        // {
        //     $item_details = Item::get_item_details($value_rem["item_id"]);

        //     $bundle_data["b".$key_rem]["bundle_id"] = $value_rem["item_id"];
        //     $bundle_data["b".$key_rem]["bundle_item_name"] = $item_details->item_name;
        //     $bundle_data["b".$key_rem]["bundle_item_bardcode"] = $item_details->item_barcode;
        //     $bundle_data["b".$key_rem]["bundle_item_um"] = $item_details->item_measurement_id;
        //     $um_info = UnitMeasurement::um_other($item_details->item_measurement_id);
        //     $um = "";
        //     if($um_info)
        //     {   
        //         $um = $um_info->multi_id;
        //     }
        //     $bundle_data["b".$key_rem]["bundle_current_stocks"] = UnitMeasurement::um_view($value_rem["item_quantity"],$item_details->item_measurement_id,$um);
        // }

        return $bundle_data;

    }

    public static function select_item_warehouse_per_bundle_empties($warehouse_id, $manufacturer_id = 0, $keyword_search = '')
    {
        $bundle = Tbl_item::category()->where("item_type_id",4)->where('is_mts',1)->where("shop_id",Warehouse::getShopId())->get();

        $item = [];
        $bundle_data = [];

        foreach($bundle as $key => $value) 
        {
            //onhand = current
            $bundle_data[$key]["bundle_id"] = $value->item_id;
            $bundle_data[$key]["bundle_item_name"] = $value->item_name;
            $bundle_data[$key]["bundle_item_bardcode"] = $value->item_barcode;
            $bundle_data[$key]["bundle_item_description"] = $value->item_sales_information;
            $bundle_data[$key]["bundle_item_um"] = $value->item_measurement_id;
            $bundle_data[$key]["bundle_actual_stocks"] = null;
            $bundle_data[$key]["bundle_actual_stocks_um"] = null;
            $bundle_data[$key]["bundle_current_stocks"] = null;
            $bundle_data[$key]["bundle_current_stocks_um"] = null;

            $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$value->item_id)->get();

            $warehouse_bundle_qty = [];
            $bundle_qty_ = [];
            $bundle_qty_warehouse = [];
            $boo = false;
            foreach ($bundle_item as $key_bundle => $value_bundle) 
            {
               $warehouse_qty = Tbl_warehouse_inventory::check_inventory_single($warehouse_id, $value_bundle->bundle_item_id)->value('inventory_count');   

               
               $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty;
               $bundle_qty_warehouse[$value_bundle->bundle_item_id] = $warehouse_qty / $bundle_qty; 
               $bundle_qty_[$value_bundle->bundle_item_id] = $bundle_qty;
               if(isset(Item::info($value_bundle->bundle_item_id)->item_manufacturer_id))
               {
                    if($manufacturer_id != 0 && $manufacturer_id != null)
                    {
                        if(Item::info($value_bundle->bundle_item_id)->item_manufacturer_id != $manufacturer_id)
                        {
                            $boo = true;
                        }
                    }
               }
            }

            
            $bundle_data[$key]["item"] = $bundle_qty_warehouse;
            $bundle_data[$key]["bundle_qty"] = $bundle_qty_;

            if($boo == true)
            {
                unset($bundle_data[$key]);
            }
        }

        $um = null;
        $rem_item = [];
        foreach($bundle_data as $key_bundle_data => $value_bundle_data) 
        {
            $bundle_stocks = 0;
            if(count($value_bundle_data["item"]) > 0)
            {
                $bundle_stocks = floor(min($value_bundle_data["item"]));
            }

            foreach($value_bundle_data["item"] as $item_id_key => $value_item) 
            {
                $rem_item[$value_bundle_data["bundle_id"].".".$item_id_key]["item_id"] = $item_id_key;
                $rem_item[$value_bundle_data["bundle_id"].".".$item_id_key]["item_quantity"] = ($value_item - $bundle_stocks) * $value_bundle_data["bundle_qty"][$item_id_key];
            }

            $bundle_data[$key_bundle_data]["bundle_current_stocks_um"] = UnitMeasurement::um_view($bundle_stocks,$value_bundle_data["bundle_item_um"]);
            $bundle_data[$key_bundle_data]["bundle_current_stocks"] = $bundle_stocks;

            $qty = Purchasing_inventory_system::get_sir_stocks($warehouse_id, $value_bundle_data["bundle_id"]);
            $bundle_data[$key_bundle_data]['total_stock_sir'] = UnitMeasurement::um_view($qty,$value_bundle_data["bundle_item_um"]);

            $bundle_data[$key_bundle_data]["bundle_actual_stocks"] = $bundle_stocks + $qty;
            $bundle_data[$key_bundle_data]["bundle_actual_stocks_um"] = UnitMeasurement::um_view($bundle_data[$key_bundle_data]["bundle_actual_stocks"],$value_bundle_data["bundle_item_um"]);
        }

        // dd($rem_item);

        // foreach($rem_item as $key_rem => $value_rem)
        // {
        //     $item_details = Item::get_item_details($value_rem["item_id"]);

        //     $bundle_data["b".$key_rem]["bundle_id"] = $value_rem["item_id"];
        //     $bundle_data["b".$key_rem]["bundle_item_name"] = $item_details->item_name;
        //     $bundle_data["b".$key_rem]["bundle_item_bardcode"] = $item_details->item_barcode;
        //     $bundle_data["b".$key_rem]["bundle_item_um"] = $item_details->item_measurement_id;
        //     $um_info = UnitMeasurement::um_other($item_details->item_measurement_id);
        //     $um = "";
        //     if($um_info)
        //     {   
        //         $um = $um_info->multi_id;
        //     }
        //     $bundle_data["b".$key_rem]["bundle_current_stocks"] = UnitMeasurement::um_view($value_rem["item_quantity"],$item_details->item_measurement_id,$um);
        // }

        return $bundle_data;

    }

    public static function get_inventory_item($warehouse_id, $type = '', $manufacturer_id = 0)
    {
        $data['warehouse_item_bundle'] = Warehouse::select_item_warehouse_per_bundle($warehouse_id, $manufacturer_id);
        $data['warehouse_item_bundle_empties'] = Warehouse::select_item_warehouse_per_bundle_empties($warehouse_id, $manufacturer_id);
        $data['_inventory'] = Warehouse::get_all_inventory_item($warehouse_id, $data['warehouse_item_bundle'], $manufacturer_id);
        $data['_empties'] = Warehouse::get_all_inventory_item($warehouse_id, $data['warehouse_item_bundle_empties'], $manufacturer_id, 1);

        return $data;
    }
    public static function get_all_inventory_item($warehouse_id, $bundled_item, $manufacturer_id = 0, $is_mts = 0)
    {
        $original_inventory = Warehouse::select_item_warehouse_single_empties($warehouse_id, $is_mts);
        $_return = [];
        foreach ($original_inventory as $key => $value) 
        {
            $item_data = Item::info($value->product_id);
            $_return[$key]['item_name'] = $item_data->item_name;
            $_return[$key]['item_sku'] = $item_data->item_sku;
            $_return[$key]['item_description'] = $item_data->item_sales_information;
            $_return[$key]['item_barcode'] = $item_data->item_barcode;
            $_return[$key]['item_actual_stock'] = null;
            $_return[$key]['item_actual_stock_um'] = null;

            $um_issued = Tbl_unit_measurement_multi::where("multi_um_id",$value->product_um)->where("is_base",0)->value("multi_id");

            $less = 0;
            $item_inventory = [];
            foreach ($bundled_item as $key_b => $value_b) 
            {
                $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$value_b['bundle_id'])->get();

                foreach ($bundle_item as $key_bundle => $value_bundle) 
                {
                    if($value_bundle->bundle_item_id == $value->product_id)
                    {
                        $bundle_inventory_qty = $value_b['bundle_current_stocks'];

                        // if($is_mts == 1) 
                        // {
                        //     if(isset($item_inventory[$value_bundle->bundle_item_id]))
                        //     {
                        //          $item_inventory[$value_bundle->bundle_item_id] = $bundle_inventory_qty - $item_inventory[$value_bundle->bundle_item_id];
                        //     }
                        //     else
                        //     {
                        //          $item_inventory[$value_bundle->bundle_item_id] = $bundle_inventory_qty;
                        //     }                            
                        // }

                        $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty;
                        $less = $bundle_inventory_qty * $bundle_qty;
                    }
                }
            }
            $less2 = 0;
            if($is_mts == 1)
            {
                $bundled_item2 = Warehouse::select_item_warehouse_per_bundle($warehouse_id, $manufacturer_id);
                 foreach ($bundled_item2 as $key_b2 => $value_b2) 
                {
                    $bundle_item2 = Tbl_item_bundle::where("bundle_bundle_id",$value_b2['bundle_id'])->get();

                    foreach ($bundle_item2 as $key_bundle2 => $value_bundle2) 
                    {
                        if($value_bundle2->bundle_item_id == $value->product_id)
                        {
                            $bundle_inventory_qty2 = $value_b2['bundle_current_stocks'];

                            // if($is_mts == 1) 
                            // {
                            //     if(isset($item_inventory[$value_bundle->bundle_item_id]))
                            //     {
                            //          $item_inventory[$value_bundle->bundle_item_id] = $bundle_inventory_qty - $item_inventory[$value_bundle->bundle_item_id];
                            //     }
                            //     else
                            //     {
                            //          $item_inventory[$value_bundle->bundle_item_id] = $bundle_inventory_qty;
                            //     }                            
                            // }

                            $bundle_qty2 = UnitMeasurement::um_qty($value_bundle2->bundle_um_id) * $value_bundle2->bundle_qty;
                            $less2 = $bundle_inventory_qty2 * $bundle_qty2;
                        }
                    }
                }
            }
            $less = $less + $less2; 
            $_return[$key]['item_id'] = $value->product_id;
            $_return[$key]['orig_stock'] = $value->product_current_qty;
            $_return[$key]['orig_stock_um'] = UnitMeasurement::um_view($_return[$key]['orig_stock'], $item_data->item_measurement_id, $um_issued);

            $_return[$key]['less_stock'] = $value->product_current_qty - $less;
            $_return[$key]['less_stock_um'] = UnitMeasurement::um_view($_return[$key]['less_stock'], $item_data->item_measurement_id, $um_issued);

            $qty = Purchasing_inventory_system::get_sir_stocks($warehouse_id, $value->product_id);
            $_return[$key]['sir_stock'] = UnitMeasurement::um_view($qty,$item_data->item_measurement_id, $um_issued);

            $_return[$key]['item_actual_stock'] = $_return[$key]['less_stock'] + $qty;
            $_return[$key]['item_actual_stock_um'] = UnitMeasurement::um_view($_return[$key]['item_actual_stock'],$item_data->item_measurement_id, $um_issued);


            if($manufacturer_id != 0 && $manufacturer_id != null)
            {
                if($item_data->item_manufacturer_id != $manufacturer_id)
                {
                    unset($_return[$key]);
                }
            }
        }
        return $_return;
    }
    public static function select_item_warehouse_single_empties($warehouse_id = 0, $is_mts = 0)
    {
        $data = Tbl_warehouse::Warehouseitem_woempties()
                             ->select_inventory($warehouse_id)
                             ->orderBy('product_name','asc')
                             ->get();
                             
        foreach($data as $key => $value)
        {  
            //cycy
            $um_issued = Tbl_unit_measurement_multi::where("multi_um_id",$value->product_um)->where("is_base",0)->value("multi_id");
            $data[$key]->product_qty_um = UnitMeasurement::um_view($value->product_current_qty,$value->product_um,$um_issued);
            $data[$key]->product_reorderqty_um = UnitMeasurement::um_view($value->product_reorder_point,$value->product_um,$um_issued);

            if($is_mts == 0)
            {
                if($value->is_mts == 1)
                {
                    unset($data[$key]);
                }                
            }
            else
            {
                if($value->is_mts == 0)
                {
                    unset($data[$key]);
                }
            }
        }
        return $data; 
    }  
    public static function insert_item_to_all_warehouse($item_id, $reorder_point = 0)
    {
        if($item_id)
        {
            $all_warehouse = Tbl_warehouse::where("warehouse_shop_id",Warehouse::getShopId())->get();
            foreach ($all_warehouse as $key => $value) 
            {
                $chk_if_existing = Tbl_sub_warehouse::where("item_id",$item_id)->where("warehouse_id",$value->warehouse_id)->first();
                if($chk_if_existing == null)
                {
                    $ins["item_id"] = $item_id;
                    $ins["warehouse_id"] = $value->warehouse_id;
                    $ins["item_reorder_point"] = $reorder_point;

                    Tbl_sub_warehouse::insert($ins);
                }

                $inventory = Tbl_warehouse_inventory::where("inventory_item_id",$item_id)->where("warehouse_id",$value->warehouse_id)->first();
                if($inventory == null)
                {
                    $ins_inventory["inventory_item_id"] = $item_id;
                    $ins_inventory["warehouse_id"] = $value->warehouse_id;
                    $ins_inventory["inventory_created"] = Carbon::now();
                    $ins_inventory["inventory_count"] = 0;

                    Tbl_warehouse_inventory::insert($ins_inventory);
                }
            }            
        }
    }
    public static function insert_item_to_warehouse($warehouse, $item_id, $item_quantity, $item_reorder_point)
    {
        $shop_id = Warehouse::getShopId();
        $slip_id = 0 ;
        $inventory_id = 0;
        if($warehouse == null)
        {
            $warehouse = Tbl_warehouse::where("warehouse_shop_id",$shop_id)->where("main_warehouse",1)->first();
            if($warehouse == null)
            {
                //MAKE MAIN WAREHOUSE
                $ins_warehouse["warehouse_name"] = "Main Warehouse";
                $ins_warehouse["warehouse_shop_id"] = $shop_id;
                $ins_warehouse["warehouse_created"] = Carbon::now();
                $ins_warehouse["main_warehouse"] = 1;

                $warehouse_id = Tbl_warehouse::insertGetId($ins_warehouse);

                $ins["warehouse_id"] = $warehouse_id;
                $ins["item_id"] = $item_id;
                $ins["item_reorder_point"] = $item_reorder_point;

                Tbl_sub_warehouse::insert($ins);

                $ins_slip["inventory_reason"] = "insert_item";
                $ins_slip["warehouse_id"] = $warehouse_id;
                $ins_slip["inventory_remarks"] = "Insert Item";
                $ins_slip["inventory_slip_date"] = Carbon::now();
                $ins_slip["inventory_slip_shop_id"] = $shop_id;
                $ins_slip["inventroy_source_reason"] = "item";
                $ins_slip["inventory_source_id"] = $item_id;

                $slip_id = Tbl_inventory_slip::insertGetId($ins_slip);

                $ins_inven["inventory_item_id"] = $item_id;
                $ins_inven["warehouse_id"] = $warehouse_id;
                $ins_inven["inventory_created"] = Carbon::now();
                $ins_inven["inventory_count"] = $item_quantity;

                $inventory_id = Tbl_warehouse_inventory::insertGetId($ins_inven);                       
            }
            else
            {
                $insert_sub["warehouse_id"] = $warehouse->warehouse_id;
                $insert_sub["item_id"] = $item_id;
                $insert_sub["item_reorder_point"] = $item_reorder_point;

                Tbl_sub_warehouse::insert($insert_sub);

                $ins_slip["inventory_reason"] = "insert_item";
                $ins_slip["warehouse_id"] = $warehouse->warehouse_id;
                $ins_slip["inventory_remarks"] = "Insert Item";
                $ins_slip["inventory_slip_date"] = Carbon::now();
                $ins_slip["inventory_slip_shop_id"] = $shop_id;
                $ins_slip["inventroy_source_reason"] = "item";
                $ins_slip["inventory_source_id"] = $item_id;

                $slip_id = Tbl_inventory_slip::insertGetId($ins_slip);

                $ins_inven["inventory_item_id"] = $item_id;
                $ins_inven["warehouse_id"] =  $warehouse->warehouse_id;
                $ins_inven["inventory_created"] = Carbon::now();
                $ins_inven["inventory_count"] = $item_quantity;
                $ins_inven["inventory_slip_id"] = $slip_id;

                $inventory_id = Tbl_warehouse_inventory::insertGetId($ins_inven);
            }
        }
        else
        {
            //
            $insert_sub["warehouse_id"] = $warehouse->warehouse_id;
            $insert_sub["item_id"] = $item_id;
            $insert_sub["item_reorder_point"] = $item_reorder_point;

            Tbl_sub_warehouse::insert($insert_sub);

            $ins_slip["inventory_reason"] = "insert_item";
            $ins_slip["warehouse_id"] = $warehouse->warehouse_id;
            $ins_slip["inventory_remarks"] = "Insert Item";
            $ins_slip["inventory_slip_date"] = Carbon::now();
            $ins_slip["inventory_slip_shop_id"] = $shop_id;
            $ins_slip["inventroy_source_reason"] = "item";
            $ins_slip["inventory_source_id"] = $item_id;

            $slip_id = Tbl_inventory_slip::insertGetId($ins_slip);

            $ins_inven["inventory_item_id"] = $item_id;
            $ins_inven["warehouse_id"] =  $warehouse->warehouse_id;
            $ins_inven["inventory_created"] = Carbon::now();
            $ins_inven["inventory_count"] = $item_quantity;
            $ins_inven["inventory_slip_id"] = $slip_id;

            $inventory_id = Tbl_warehouse_inventory::insertGetId($ins_inven);
        }
        return $inventory_id;
    }
    public static function insert_access($warehouse_id)
    {
        $ins_access["user_id"] = Warehouse::getUserid();
        $ins_access["warehouse_id"] = $warehouse_id;
        Tbl_user_warehouse_access::insert($ins_access);

        $user_position = Tbl_user::position()->where("user_id",Warehouse::getUserid())->value("position_rank");

        $all_user = Tbl_user::position()->where("user_shop",Warehouse::getShopId())->where("user_id","!=", Warehouse::getUserid())->where("tbl_user.archived",0)->get();

        foreach ($all_user as $key => $value) 
        {
            if($value->position_rank < $user_position)
            {
                $ins_access["user_id"] = $value->user_id;
                $ins_access["warehouse_id"] = $warehouse_id;
                Tbl_user_warehouse_access::insert($ins_access);
            }
        }
    }
    public static function inventory_input_report($inventory_slip_id)
    {
        $return["slip"] = Tbl_inventory_slip::shop()->warehouse()->where("inventory_slip_shop_id",Warehouse::getShopId())
                                                ->user()
                                                ->where("inventory_slip_id",$inventory_slip_id)
                                                ->first();
        if($return["slip"]->inventroy_source_reason == "vendor")
        {
            $return["slip"] = Tbl_inventory_slip::shop()->vendor()->warehouse()->where("inventory_slip_shop_id",Warehouse::getShopId())
                                                ->user()
                                                ->where("inventory_slip_id",$inventory_slip_id)
                                                ->first();
        }
        $data = $return["slip"];
        // dd($data);
        return $data;
    }
    public static function inventory_input_report_item($inventory_slip_id)
    {
        $data = Tbl_warehouse_inventory::inventoryslip()->item()->where("tbl_warehouse_inventory.inventory_slip_id",$inventory_slip_id)->groupBy("tbl_item.item_id")->get();
        // dd($data);
        foreach ($data as $key => $value) 
        {
            $data[$key]->serial_number_list = Tbl_inventory_serial_number::where("serial_inventory_id",$value->inventory_id)->get();
            $um_issued = Tbl_unit_measurement_multi::where("multi_um_id",$value->item_measurement_id)->where("is_base",0)->value("multi_id");
            $qty = $value->inventory_count < 0 ? abs($value->inventory_count) : $value->inventory_count;
            $sign = $value->inventory_count < 0 ? '- ' : '';
            $data[$key]->qty_um = $sign . UnitMeasurement::um_view($qty,$value->item_measurement_id,$um_issued);
            $data[$key]->conversion = UnitMeasurement::um_convertion($value->item_id);
        }
        return $data;
    }
    public static function select_item_warehouse_single_w_page($warehouse_id = 0, $return = 'array')
    {
    	$data = Tbl_warehouse::Warehouseitem()
                             ->select_inventory($warehouse_id)
    						 ->paginate(10);
        foreach($data as $key => $value)
        {   //cycy
            $um_issued = Tbl_unit_measurement_multi::where("multi_um_id",$value->product_um)->where("is_base",0)->value("multi_id");
            $data[$key]->product_qty_um = UnitMeasurement::um_view($value->product_current_qty,$value->product_um,$um_issued);
            $data[$key]->product_reorderqty_um = UnitMeasurement::um_view($value->product_reorder_point,$value->product_um,$um_issued);

            //sir
            $sir["pis"] = Purchasing_inventory_system::check();
            if($sir["pis"] != 0)
            {
                $qty = Purchasing_inventory_system::get_sir_stocks($warehouse_id, $value->product_id);
                $data[$key]->total_stock_sir = UnitMeasurement::um_view($qty,$value->product_um,$um_issued);
            }
        }
    	if($return == 'json')
    	{
    		$data = json_encode($data);
    	}
    	return $data; 
    }
    public static function select_item_warehouse_single($warehouse_id = 0, $return = 'array')
    {
        $data = Tbl_warehouse::Warehouseitem()
                             ->select_inventory($warehouse_id)
                             ->orderBy('product_name','asc')
                             ->get();
                             
        foreach($data as $key => $value)
        {  
            //cycy
            $um_issued = Tbl_unit_measurement_multi::where("multi_um_id",$value->product_um)->where("is_base",0)->value("multi_id");
            $data[$key]->product_qty_um = UnitMeasurement::um_view($value->product_current_qty,$value->product_um,$um_issued);
            $data[$key]->product_reorderqty_um = UnitMeasurement::um_view($value->product_reorder_point,$value->product_um,$um_issued);
        }           
        if($return == 'json')
        {
            $data = json_encode($data);
        }
        return $data; 
    }  

    public static function select_item_warehouse_single_vendor($warehouse_id = 0, $return = 'array',$vendor_id)
    {
        $data = Tbl_warehouse::Warehouseitem_vendor($vendor_id)
                             ->select_inventory($warehouse_id)
                             ->orderBy('product_name','asc')
                             ->get();
                             
        if($return == 'json')
        {
            $data = json_encode($data);
        }
        return $data; 
    }
    public static function warehouse_access()
    {
        $warehouse = Tbl_user_warehouse_access::where("user_id",Warehouse::getUserid())->get();

        $return["warehouse_id"] = null;
        foreach ($warehouse as $key => $value) 
        {
            $return["warehouse_id"][$key] = $value->warehouse_id;
        }
        return $return;        
    }

    public static function getUserid()
    {
        $user_id = 0;
        $user_data = Tbl_user::where("user_email", session('user_email'))->shop()->value('user_id');
        if($user_data)
        {
            $user_id = $user_data;
        }
        return $user_id;
    }
    public static function getWarehouseIdFromSlip($transaction_id = 0, $transaction_type = null)
    {
        $slip_data = Tbl_inventory_slip::where("inventroy_source_reason",$transaction_type)->where("inventory_source_id",$transaction_id)->first();
        $warehouse_id = 0;
        if($slip_data)
        {
            $warehouse_id = $slip_data->warehouse_id;
        }
        return $warehouse_id;
    }
    public static function check_inventory_on_warehouse($warehouse_id = 0, $item_id = 0, $return = 'array')
    {
    	$data = Tbl_warehouse::Warehouseitem()
                             ->select_inventory($warehouse_id, $item_id)
    						 ->get();

    	
    	if($return == 'json')
    	{
    		$data = json_encode($data);
    	}
    	return $data;
    }

    public static function inventory_transfer_bulk($source_id = 0, $destination_id = 0, $_info = array(), $remarks = '', $return = 'array')
    {
        $shop_id = Warehouse::get_shop_id($source_id);

        $insert_slip_source['inventory_slip_id_sibling']    = 0;
        $insert_slip_source['inventory_reason']             = 'source';
        $insert_slip_source['warehouse_id']                 = $source_id;
        $insert_slip_source['inventory_remarks']            = $remarks;
        $insert_slip_source['inventory_slip_date']          = Carbon::now();
        $insert_slip_source['inventory_slip_shop_id']       = $shop_id;
        $insert_slip_source['inventory_slip_status']        = 'transfer';
        $insert_slip_source['inventroy_source_reason']      = 'warehouse';
        $insert_slip_source['inventory_slip_consume_refill']= 'consume';
        $insert_slip_source['slip_user_id']= Warehouse::getUserid();

        $inventory_slip_id_source = Tbl_inventory_slip::insertGetId($insert_slip_source);

        $insert_slip_destination['inventory_slip_id_sibling']    = $inventory_slip_id_source;
        $insert_slip_destination['inventory_reason']             = 'destination';
        $insert_slip_destination['warehouse_id']                 = $destination_id;
        $insert_slip_destination['inventory_remarks']            = $remarks;
        $insert_slip_destination['inventory_slip_date']          = Carbon::now();
        $insert_slip_destination['inventory_slip_shop_id']       = $shop_id;
        $insert_slip_destination['inventory_slip_status']        = 'transfer';
        $insert_slip_destination['inventroy_source_reason']      = 'warehouse';
        $insert_slip_destination['inventory_source_id']          = $source_id;
        $insert_slip_destination['inventory_slip_consume_refill']= 'refill';
        $insert_slip_destination['slip_user_id']= Warehouse::getUserid();

        $inventory_slip_id_destination = Tbl_inventory_slip::insertGetId($insert_slip_destination);

        $insertsource = [];
        $insertdestination = '';
        $inventory_success = '';
        $inventory_err = '';
        $success = 0;
        $err = 0;
        $err_msg["msg"] ="";

        foreach($_info as $key => $info)
        {
            /**/ 
            $count_on_hand = Tbl_warehouse_inventory::check_inventory_single($source_id, $info['product_id'])->value('inventory_count');
            
            if($info['quantity'] > 0 && $count_on_hand > 0 && $count_on_hand >= $info['quantity']) 
            {
                $quantity_source = $info['quantity'] * -1;

                $quantity_destination = $info['quantity'];

                $insertsource[$key]['inventory_item_id']        = $info['product_id'];
                $insertsource[$key]['warehouse_id']             = $source_id;
                $insertsource[$key]['inventory_created']        = Carbon::now();
                $insertsource[$key]['inventory_count']          = $quantity_source;
                $insertsource[$key]['inventory_slip_id']        = $inventory_slip_id_source;

                $insertdestination[$key]['inventory_item_id']   = $info['product_id'];
                $insertdestination[$key]['warehouse_id']        = $destination_id;
                $insertdestination[$key]['inventory_created']   = Carbon::now();
                $insertdestination[$key]['inventory_count']     = $quantity_destination;
                $insertdestination[$key]['inventory_slip_id']   = $inventory_slip_id_destination;

                $inventory_success[$success] = Warehouse::array_tansfer('transfer_success', $info['product_id']);
                $success++;
            }
            else
            {
                $item_name = Item::get_item_details($info['product_id']);
                $err_msg["msg"] .= "The quantity of ".$item_name->item_name." is not enough for your transfer.<br>";
                $inventory_err[$err] = Warehouse::array_tansfer('error', $info['product_id']);
                $err++;
            }     
            
        }

        $data['status'] = '';

        if($inventory_err == "")
        {   
            $data['status']             = 'transfer_success';
            $data['response_status']    = 'transfer_success';
            $data['success']            = $inventory_success;
            $data['error']              = $inventory_err;
            Tbl_warehouse_inventory::insert($insertsource);
            Tbl_warehouse_inventory::insert($insertdestination);
            $data['inventory_slip_id_source']       = $inventory_slip_id_source;
            $data['inventory_slip_id_destination']  = $inventory_slip_id_destination;

            $inventory_slip_source = Tbl_inventory_slip::warehouse()->where("inventory_slip_id",$inventory_slip_id_source)->first();
            $inventory_slip_destination = Tbl_inventory_slip::warehouse()->where("inventory_slip_id",$inventory_slip_id_destination)->first();

            $wh_src_name ="";
            $wh_src_dest = "";
            if($inventory_slip_source != null && $inventory_slip_destination != null)
            {
                $wh_src_name = $inventory_slip_source->warehouse_name;
                $wh_src_dest =  $inventory_slip_destination->warehouse_name;
            }
            $wh_data = AuditTrail::get_table_data("tbl_inventory_slip","inventory_slip_id",$inventory_slip_id_source);
            AuditTrail::record_logs("Transfer from ".$wh_src_name,"warehouse_inventory",$inventory_slip_id_source,"",serialize($wh_data));

            $whs_data = AuditTrail::get_table_data("tbl_inventory_slip","inventory_slip_id",$inventory_slip_id_destination);
            AuditTrail::record_logs("Transfer to ".$wh_src_dest,"warehouse_inventory",$inventory_slip_id_destination,"",serialize($whs_data));            
        }
        else
        {
            $data['status']             = 'error';
            $data['response_status']    = 'error';
            $data['error']              = $err_msg;
            $data['message'] = $err_msg["msg"];
        }
        $space = '';

        return $data;
    }


    public static function inventory_transfer_single($warehouse_source_id = 0, $warehouse_destination_id = 0, $product_id = 0, $quantity = 0, $return = 'array')
    {
        $info[0]['quantity'] = $quantity;
        $info[0]['product_id'] = $product_id;
        return Warehouse::inventory_transfer_bulk($warehouse_source_id, $warehouse_destination_id, $info, $return);
    }

    /*
    * reason_refill [string accept]
    * vendor
    * other
    */
    public static function inventory_update($transaction_id = 0, $transaction_type = '', $transaction_item_inventory = array(), $return = 'array', $allow_out_of_stock = false , $item_serial = array())
    {
        //inventory source reason = $transaction_type
        //inventory source id = $transaction_id
        $inventory_slip = Tbl_inventory_slip::where("inventory_source_id",$transaction_id)->where("inventroy_source_reason",$transaction_type)->first();
        
        Tbl_warehouse_inventory::where("inventory_slip_id",$inventory_slip->inventory_slip_id)->delete();

        /*RETURN All TO ORIGINAL*/
        ItemSerial::return_original_serial($transaction_type,$transaction_id);

        foreach($transaction_item_inventory as $key2 => $value2)
        {            
            $count = Tbl_warehouse_inventory::check_inventory_single($inventory_slip->warehouse_id, $value2['product_id'])->value('inventory_count');
            // $count_on_hand = $count + $value2["quantity"];
            $count_on_hand = $count;

            if($allow_out_of_stock == true)
            {

                $insert["inventory_item_id"] = $value2["product_id"];
                $insert["inventory_count"] = $value2["quantity"] * -1;
                $insert["inventory_created"] = Carbon::now();
                $insert["warehouse_id"] = $inventory_slip->warehouse_id;
                $insert["inventory_slip_id"] = $inventory_slip->inventory_slip_id;

                Tbl_warehouse_inventory::insert($insert);
                $data["status"] = "success";

            }
            else
            {
                if($value2['quantity'] > 0 && $count_on_hand > 0 && $count_on_hand >= $value2['quantity'])
                {     
                    $insert["inventory_item_id"] = $value2["product_id"];
                    $insert["inventory_count"] = $value2["quantity"] * -1;
                    $insert["inventory_created"] = Carbon::now();
                    $insert["warehouse_id"] = $inventory_slip->warehouse_id;
                    $insert["inventory_slip_id"] = $inventory_slip->inventory_slip_id;

                    Tbl_warehouse_inventory::insert($insert);
                    $data["status"] = "success";
                }
                else
                {
                    $data["status"] = "error";
                    $data["status_message"] = "The quantity is not enough";
                }
            }

        }

        if(count($item_serial) > 0)
        {
            foreach ($item_serial as $key_item_serial => $value_item_serial) 
            {
                ItemSerial::consume_item_serial($item_serial[$key_item_serial], $transaction_type, $transaction_id);
            }
        }

        if($data["status"] != "error" && $inventory_slip != null)
        {
            $wh_data = AuditTrail::get_table_data("tbl_inventory_slip","inventory_slip_id",$inventory_slip->inventory_slip_id);
            AuditTrail::record_logs("Update","warehouse_inventory",$inventory_slip->inventory_slip_id,serialize($wh_data),serialize($wh_data));            
        }

        if($return == 'json')
        {
            $data = json_encode($data);
        }
        return $data;

    }
    public static function inventory_update_returns($transaction_id = 0, $transaction_type = '', $transaction_item_inventory = array(), $return = 'array', $item_serial  = array())
    {
        //inventory source reason = $transaction_type
        //inventory source id = $transaction_id
        $inventory_slip = Tbl_inventory_slip::where("inventory_source_id",$transaction_id)->where("inventroy_source_reason",$transaction_type)->first();
        
        Tbl_warehouse_inventory::where("inventory_slip_id",$inventory_slip->inventory_slip_id)->delete();

        foreach($transaction_item_inventory as $key2 => $value2)
        {     
                $insert["inventory_item_id"] = $value2["product_id"];
                $insert["inventory_count"] = $value2["quantity"];
                $insert["inventory_created"] = Carbon::now();
                $insert["warehouse_id"] = $inventory_slip->warehouse_id;
                $insert["inventory_slip_id"] = $inventory_slip->inventory_slip_id;

                $inventory_id = Tbl_warehouse_inventory::insertGetId($insert);
                if(count($item_serial) > 0)
                {
                    if($item_serial[$key2]["item_id"] == $value2["product_id"])
                    {
                        ItemSerial::insert_item_serial($item_serial[$key2], $inventory_id);
                    }
                }
                $data["status"] = "success";
        }

        if($data["status"] != "error" && $inventory_slip != null)
        {
            $wh_data = AuditTrail::get_table_data("tbl_inventory_slip","inventory_slip_id",$inventory_slip->inventory_slip_id);
            AuditTrail::record_logs("Update","warehouse_inventory",$inventory_slip->inventory_slip_id,serialize($wh_data),serialize($wh_data));            
        }


        if($return == 'json')
        {
            $data = json_encode($data);
        }
        return $data;

    }
    public static function adjust_inventory($warehouse_id = 0, $reason_refill = '', $refill_source = 0, $remarks = '', $warehouse_refill_product = array(), $return = 'array', $is_return = null)
    {
        
        $shop_id = Warehouse::get_shop_id($warehouse_id);

        $insert_slip['inventory_slip_id_sibling']    = 0;
        $insert_slip['inventory_reason']             = 'adjust';
        $insert_slip['warehouse_id']                 = $warehouse_id;
        $insert_slip['inventory_remarks']            = $remarks;
        $insert_slip['inventory_slip_date']          = Carbon::now();
        $insert_slip['inventory_slip_shop_id']       = $shop_id;
        $insert_slip['inventory_slip_status']        = 'adjust';
        $insert_slip['inventroy_source_reason']      = $reason_refill;
        $insert_slip['inventory_source_id']          = $refill_source;
        $insert_slip['inventory_slip_consume_refill']= 'adjust';
        $insert_slip['slip_user_id']= Warehouse::getUserid();

        $inventory_slip_id = Tbl_inventory_slip::insertGetId($insert_slip);

        $inventory_success = '';
        $inventory_err = '';
        $success = 0;
        $err = 0;
        $insert_refill = '';

        $for_serial_item = '';
        
        if($warehouse_refill_product)
        {
            foreach($warehouse_refill_product as $key => $refill_product)
            {
               
                $insert_refill['inventory_item_id']        = $refill_product['product_id'];
                $insert_refill['warehouse_id']             = $warehouse_id;
                $insert_refill['inventory_created']        = Carbon::now();
                $insert_refill['inventory_count']          = $refill_product['quantity'];
                $insert_refill['inventory_slip_id']        = $inventory_slip_id;

                $inventory_success[$success] = Warehouse::array_tansfer('success', $refill_product['product_id']);
                $success++;

                $inventory_id = Tbl_warehouse_inventory::insertGetId($insert_refill);

                $for_serial_item[$key]["quantity"] = $refill_product['quantity'];
                $for_serial_item[$key]["product_id"] = $refill_product['product_id'];
                $for_serial_item[$key]["inventory_id"] = $inventory_id;
            }

            $data['status'] = 'success'; 
            $data['inventory_slip_id'] = $inventory_slip_id;

            $slip_data = AuditTrail::get_table_data("tbl_inventory_slip","inventory_slip_id",$inventory_slip_id);
            AuditTrail::record_logs("Adjust","warehouse_inventory",$inventory_slip_id,"",serialize($slip_data));

        }
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = 'some fields are missing';
        }
       

        $space = '';
        if($return == 'json')
        {
            $data = json_encode($data);
        }
        return $data;
    }
    public static function inventory_refill($warehouse_id = 0, $reason_refill = '', $refill_source = 0, $remarks = '', $warehouse_refill_product = array(), $return = 'array', $is_return = null, $item_serial = array())
    {
        $shop_id = Warehouse::get_shop_id($warehouse_id);

        $insert_slip['inventory_slip_id_sibling']    = 0;
        $insert_slip['inventory_reason']             = 'refill';
        $insert_slip['warehouse_id']                 = $warehouse_id;
        $insert_slip['inventory_remarks']            = $remarks;
        $insert_slip['inventory_slip_date']          = Carbon::now();
        $insert_slip['inventory_slip_shop_id']       = $shop_id;
        $insert_slip['inventory_slip_status']        = 'refill';
        $insert_slip['inventroy_source_reason']      = $reason_refill;
        $insert_slip['inventory_source_id']          = $refill_source;
        $insert_slip['inventory_slip_consume_refill']= 'refill';
        $insert_slip['slip_user_id']= Warehouse::getUserid();

        $inventory_slip_id = Tbl_inventory_slip::insertGetId($insert_slip);

        $inventory_success = [];
        $inventory_err = [];
        $success = 0;
        $err = 0;
        $insert_refill = [];

        $for_serial_item = [];
        
        if($warehouse_refill_product)
        {
            foreach($warehouse_refill_product as $key => $refill_product)
            {
                
                $insert_refill['inventory_item_id']        = $refill_product['product_id'];
                $insert_refill['warehouse_id']             = $warehouse_id;
                $insert_refill['inventory_created']        = Carbon::now();
                $insert_refill['inventory_count']          = $refill_product['quantity'];
                $insert_refill['inventory_slip_id']        = $inventory_slip_id;

                $inventory_success[$success] = Warehouse::array_tansfer('success', $refill_product['product_id']);
                $success++;

                $inventory_id = Tbl_warehouse_inventory::insertGetId($insert_refill);

                if(count($item_serial) >= 1 && $is_return == null) 
                {
                    if($item_serial[$key]["item_id"] == $refill_product['product_id'])
                    {
                        ItemSerial::insert_item_serial($item_serial[$key], $inventory_id);
                    }
                }

                $for_serial_item[$key]["quantity"] = $refill_product['quantity'];
                $for_serial_item[$key]["product_id"] = $refill_product['product_id'];
                $for_serial_item[$key]["inventory_id"] = $inventory_id;
            }
           
            $data['status'] = ''; 

            $serial = Tbl_settings::where("settings_key","item_serial")->where("settings_value","enable")->where("shop_id",$shop_id)->first();

            $data['status'] = 'success';
            if($is_return == null)
            {
                if($serial != null)
                {
                    $data['status'] = 'success-serial';

                    $items["item_id"] = "";
                    // $items["slip_id"] = $inventory_slip_id;
                    $items["item_list"] = $for_serial_item;
                    Session::put("item", $items);
                }                
            }
 
            $data['inventory_slip_id'] = $inventory_slip_id;

            $slip_data = AuditTrail::get_table_data("tbl_inventory_slip","inventory_slip_id",$inventory_slip_id);
            AuditTrail::record_logs("Refill","warehouse_inventory",$inventory_slip_id,"",serialize($slip_data));

        }
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = 'some fields are missing';
        }
       

        $space = '';
        if($return == 'json')
        {
            $data = json_encode($data);
        }
        return $data;
    }
    public static function inventory_consume($warehouse_id = 0, $remarks = '', $consume_product ,$consumer_id = 0, $consume_cause = '', $return = 'array', $transaction_type = '', $transaction_id = 0,$allow_out_of_stock = false, $item_serial = array())
    {
        $shop_id = Warehouse::get_shop_id($warehouse_id);
        $insert_slip['inventory_slip_id_sibling']     = 0;
        $insert_slip['inventory_reason']              = 'consume';
        $insert_slip['warehouse_id']                  = $warehouse_id;
        $insert_slip['inventory_remarks']             = $remarks;
        $insert_slip['inventory_slip_date']           = Carbon::now();
        $insert_slip['inventory_slip_shop_id']        = $shop_id;
        $insert_slip['inventory_slip_status']         = 'consume';
        $insert_slip['inventroy_source_reason']       = $transaction_type;
        $insert_slip['inventory_source_id']           = $transaction_id;
        $insert_slip['inventory_slip_consume_refill'] = 'consume';
        $insert_slip['inventory_slip_consumer_id']    =  $consumer_id;
        $insert_slip['inventory_slip_consume_cause']  =  $consume_cause;
        $insert_slip['slip_user_id'] = Warehouse::getUserid() or 0;

        $inventory_slip_id = Tbl_inventory_slip::insertGetId($insert_slip);

        $insert_consume = null;

        $inventory_success = null;
        $inventory_err = null;
        $success = 0;
        $err = 0;
        $data['status_message'] = "";
        $err_msg = "";
        foreach($consume_product as $key => $product)
        {
            $item_type = Tbl_item::where("item_id",$product['product_id'])->value("item_type_id");
            if($item_type == 1) //inventory
            {
                $count_on_hand = Tbl_warehouse_inventory::check_inventory_single($warehouse_id, $product['product_id'])->value('inventory_count');
                if($count_on_hand == null)
                {
                    $count_on_hand = 0;   
                }

                
                if($allow_out_of_stock == true)
                {
                    $insert_consume[$key]['inventory_item_id']        = $product['product_id'];
                    $insert_consume[$key]['warehouse_id']             = $warehouse_id;
                    $insert_consume[$key]['inventory_created']        = Carbon::now();
                    $insert_consume[$key]['inventory_count']          = $product['quantity'] * -1;
                    $insert_consume[$key]['inventory_slip_id']        = $inventory_slip_id;

                    $inventory_success[$success] = Warehouse::array_tansfer('success', $product['product_id']);
                    $success++;
                }
                else
                {
                    if($product['quantity'] > 0 && $count_on_hand > 0 && $count_on_hand >= $product['quantity'])
                    {
                        $insert_consume[$key]['inventory_item_id']        = $product['product_id'];
                        $insert_consume[$key]['warehouse_id']             = $warehouse_id;
                        $insert_consume[$key]['inventory_created']        = Carbon::now();
                        $insert_consume[$key]['inventory_count']          = $product['quantity'] * -1;
                        $insert_consume[$key]['inventory_slip_id']        = $inventory_slip_id;

                        $inventory_success[$success] = Warehouse::array_tansfer('success', $product['product_id']);
                        $success++;
                    }
                    else
                    {
                        $item_name = Item::get_item_details($product['product_id']);
                        $err_msg[$key] = "The quantity of ".$item_name->item_name." is not enough for you to transfer.<br>";
                        $inventory_err[$err] = Warehouse::array_tansfer('error', $product['product_id']);
                        $err++;
                    }

                }                
            }
        }
        if(count($item_serial) > 0)
        {
            foreach ($item_serial as $key_item_serial => $value_item_serial) 
            {
                ItemSerial::consume_item_serial($item_serial[$key_item_serial], $transaction_type, $transaction_id);
            }
        }

        $data['status'] = '';
        if($inventory_err == null)
        {
            if($insert_consume != null)
            {
                Tbl_warehouse_inventory::insert($insert_consume);

                $data['status'] = 'success';
                $data['inventory_slip_id'] = $inventory_slip_id;     

                $slip_data = AuditTrail::get_table_data("tbl_inventory_slip","inventory_slip_id",$inventory_slip_id);
                AuditTrail::record_logs("Consume","warehouse_inventory",$inventory_slip_id,"",serialize($slip_data));           
            }
        }
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = $err_msg;
        }
        $space = '';
        if($return == 'json')
        {
            $data = json_encode($data);
        }

        return $data;
    }

    public static function get_shop_id($warehouse_id = 0)
    {

        $data = Tbl_warehouse::where('warehouse_id',$warehouse_id)->value('warehouse_shop_id');
        return $data;
    }
    public static function insert_item($from_warehouse_id, $to_warehouse_id)
    {
        $from_item = Tbl_sub_warehouse::where("warehouse_id",$from_warehouse_id)->get();

        foreach($from_item as $key => $value) 
        {
            $ctr_to_item_existence = Tbl_sub_warehouse::where("warehouse_id",$to_warehouse_id)->where("item_id",$value->item_id)->count();

            if($ctr_to_item_existence == 0)
            {
                $ins_item["warehouse_id"] = $to_warehouse_id;
                $ins_item["item_id"] = $value->item_id;
                $ins_item["item_reorder_point"] = 0;

                Tbl_sub_warehouse::insert($ins_item);
            }

            $ctr_to_item_inventory = Tbl_warehouse_inventory::where("warehouse_id",$to_warehouse_id)->where("inventory_item_id",$value->item_id)->count();

            if($ctr_to_item_inventory == 0)
            {
                $ins_inventory["inventory_item_id"] = $value->item_id;
                $ins_inventory["warehouse_id"] = $to_warehouse_id;
                $ins_inventory["inventory_created"] = Carbon::now();
                $ins_inventory["inventory_count"] = 0;

                Tbl_warehouse_inventory::insert($ins_inventory);
            }


        }
    }
    public static function get_transfer_warehouse_information($warehouse_from_id = 0, $warehouse_to_id = 0, $return = 'array')
    {   
        $data = Tbl_sub_warehouse::warehousetowarehouse($warehouse_from_id, $warehouse_to_id)->get();
        if($return == 'json')
        {
            $data = json_encode($data);
        }
        return $data;
    }


    public static function array_tansfer($status = '', $product_id = 0)
    {
        $item = Tbl_item::selitem($product_id)->first();
        $data['status'] = $status;
        $data['product_id'] = $product_id;
        $data['product_name'] = $item->item_name;
        return $data;
    }

    public static function put_default_warehouse($shop_id)
    {
        if(!Tbl_warehouse::where("warehouse_shop_id", $shop_id)->where("main_warehouse", 1)->first())
        {
            $insert1["warehouse_name"]    = "Main Warehouse";
            $insert1["warehouse_shop_id"] = $shop_id;
            $insert1["warehouse_created"] = Carbon::now();
            $insert1["main_warehouse"]    = "1";

            Tbl_warehouse::insertGetId($insert1);
        }

        if(!Tbl_warehouse::where("warehouse_shop_id", $shop_id)->where("warehouse_name", "Ecommerce Warehouse")->first())
        {
            $insert2["warehouse_name"]    = "Ecommerce Warehouse";
            $insert2["warehouse_shop_id"] = $shop_id;
            $insert2["warehouse_created"] = Carbon::now();
            $insert2["main_warehouse"]    = "2";

            Tbl_warehouse::insertGetId($insert2);
        }
        if(!Tbl_warehouse::where("warehouse_shop_id", $shop_id)->where("warehouse_name", "FOR REPAIR")->first())
        {
            $insert3["warehouse_name"]    = "FOR REPAIR";
            $insert3["warehouse_shop_id"] = $shop_id;
            $insert3["warehouse_created"] = Carbon::now();
            $insert3["main_warehouse"]    = "4";

            Tbl_warehouse::insertGetId($insert3);
        }
    }

    public static function getMainwarehouse()
    {
        return Tbl_warehouse::where("warehouse_shop_id",Warehouse::getShopId())->where("main_warehouse",1)->value("warehouse_id");
    }
    public static function getShopId()
    {
        return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
    }
    public static function mainwarehouse_for_developer($user_id, $shop_id)
    {
        $_warehouse     = Tbl_warehouse::where("warehouse_shop_id", $shop_id)->get();
        $warehouse_id   = collect($_warehouse)->where("main_warehouse", 1)->first()->value("warehouse_id");
        $user           = Tbl_user::where("user_id", $user_id)->first();
        
        if($user->user_level == 1)
        {
            foreach($_warehouse as $warehouse)
            {
                 if(!Tbl_user_warehouse_access::where("user_id", $user_id)->where("warehouse_id", $warehouse->warehouse_id)->first())
                 {
                    Tbl_user_warehouse_access::insert(['user_id' => $user_id, 'warehouse_id' => $warehouse->warehouse_id]);
                 }
            }
        }
        // elseif(!Tbl_user_warehouse_access::where("user_id", $user_id)->where("warehouse_id", $warehouse_id)->first())
        // {
        //     Tbl_user_warehouse_access::insert(['user_id' => $user_id, 'warehouse_id' => $warehouse_id]);
        // }
    }
   
    public static function checkStock($product_consume, $warehouse_id)
    {
        $err = 0;
        $err_msg = "";
        
        foreach ($product_consume["single"] as $key_item => $value_item) 
        {
            foreach ($value_item as $key_items => $value_items) 
            {
                $count_on_hand = Tbl_warehouse_inventory::check_inventory_single($warehouse_id, $value_items['item_id'])->value('inventory_count');
                
                if($count_on_hand == null)
                {
                    $count_on_hand = 0;   
                }

                if($value_items['quantity'] > 0 && $count_on_hand > 0 && $count_on_hand >= $value_items['quantity'])
                {
                    // Allowed
                }
                else
                {
                    $item_name = Item::get_item_details($value_items['item_id']);
                    $err_msg[$err] = "The quantity of ".$item_name->item_name." is not enough for you to transfer.";
                    $err++;
                } 
            } 
        }

        foreach ($product_consume["bundle"] as $key_item => $value_item) 
        {
            $ctr = 0;

            foreach ($value_item as $key_items => $value_items) 
            {
                if ($ctr == 0) 
                {
                    $count_on_hand = Warehouse::select_item_warehouse_per_bundle($warehouse_id, 0, '', $value_items->bundle_item_id);
                    
                    if (isset($count_on_hand[0]["bundle_actual_stocks"])) 
                    {
                        $count_on_hand = $count_on_hand[0]["bundle_actual_stocks"];
                    }
                    else
                    {
                        $count_on_hand = 0;
                    }

                    if($value_items->bundle_quantity > 0 && $count_on_hand > 0 && $count_on_hand >= $value_items->bundle_quantity)
                    {
                        // Allowed
                    }
                    else
                    {
                        $item_name = Item::get_item_details($value_items->bundle_bundle_id);
                        $err_msg[$err] = "The quantity of ".$item_name->item_name." is not enough for you to transfer.";
                        $err++;
                        $ctr++;
                    }  
                }
            }
        }

        if($err_msg == "")
        {
            $data['status'] = 'success';
            $data['status_message'] = "Success";
        }
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = $err_msg;
        }

        return $data;
    }

}