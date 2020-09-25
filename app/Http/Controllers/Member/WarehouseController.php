<?php

namespace App\Http\Controllers\Member;

use App\Models\Tbl_item;
use App\Globals\Warehouse;
use App\Globals\Utilities;
use App\Globals\Vendor;
use App\Globals\Pdf_global;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_manufacturer;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_user_warehouse_access;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_settings;
use App\Models\Tbl_sir;
use App\Models\Tbl_sir_inventory;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_unit_measurement_multi;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Manufacturer;
use Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Tbl_category;
use App\Models\Tbl_user;
use App\Models\Tbl_inventory_serial_number;
use Session;
use App\Globals\Item;
use App\Globals\AuditTrail;
use App\Globals\Warehouse2;
use Validator;
use Excel;
use DB;
class WarehouseController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function stock_input($slip_id = 0)
    {
        $data["current_user"] = Tbl_user::where("user_id",$this->user_info->user_id)->first();
        $data["slip"] = Warehouse::inventory_input_report($slip_id);
        $data["slip_item"] = Warehouse::inventory_input_report_item($slip_id);
        if($data["slip"])
        {
            if($data["slip"]->inventory_reason == "refill" || $data["slip"]->inventory_reason == "insert_item" || $data["slip"]->inventory_reason == "destination")
            {
                $data["report_title"] = "STOCK INPUT";
            }
            else
            {
                $data["report_title"] = strtoupper($data["slip"]->inventory_reason);
            }
        }
        $pdf = view("member.warehouse.stock_input_pdf",$data);
        return Pdf_global::show_pdf($pdf);
    }
    public function refill_log($warehouse_id)
    {
        $data["_slip"] = Tbl_inventory_slip::whereIn("inventory_reason",['refill','insert_item'])->where("warehouse_id",$warehouse_id)->where("inventory_slip_shop_id",$this->user_info->shop_id)->orderBy("inventory_slip_date","DESC")->get();

        return view("member.warehouse.refill_log",$data);
    }
    public function view_pdf($slip_id)
    {
        $data["slip_id"] = $slip_id;
        return view("member.warehouse.stock_view",$data);
    }
   public function export_xls($warehouse_id)
    {
        $data["warehouse"] = DB::table("tbl_warehouse")->where("warehouse_id", $warehouse_id)->first();



        // $data["_item"] = Tbl_warehouse::inventory()
        //                               ->select("*", DB::raw("sum(tbl_warehouse_inventory.inventory_count) as sum"))
        //                            ->warehouseitem()
        //                            ->serialnumber()
        //                               ->where("tbl_warehouse.warehouse_id",$warehouse_id)
        //                               ->groupBy("tbl_inventory_serial_number.serial_number")
                                      // ->take(50)
        //                            ->get();
        $data["_item"] = Tbl_sub_warehouse::select_item($warehouse_id)
                                      ->leftjoin('tbl_inventory_serial_number', 'tbl_item.item_id', '=', 'tbl_inventory_serial_number.item_id')
                                      ->selectRaw("*, tbl_item.item_id as inventory_item_id")
                                      // ->take(50)
                                      ->get();
                  
        $data["quantity"] = 0;
        foreach ($data["_item"] as $key2 => $value2) 
        {
            $qty = 0;
            $qty = Tbl_warehouse_inventory::where("warehouse_id",$warehouse_id)->leftjoin("tbl_item","inventory_item_id","=","item_id")->where("tbl_warehouse_inventory.inventory_item_id",$value2->inventory_item_id)->where("tbl_item.archived",0)->sum("inventory_count");
            $data["_item"][$key2]->sum = $qty;
            $data["quantity"] += $qty;
        }
        Excel::create($data["warehouse"]->warehouse_name, function($excel) use ($data)
        {
            $excel->sheet('Warehouse', function($sheet) use ($data)
            {
                $sheet->loadView('member.warehouse.warehouse_xls', $data);
            });

        })->download('xls');
    }
    public function index()
    {
        $this->item();
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            $data["_warehouse"] = Tbl_warehouse::select_info($this->user_info->shop_id, 0)->orderBy('warehouse_created', 'DESC')->groupBy("tbl_warehouse.warehouse_id");
            if(Request::input("search_txt"))
            {
               $data["_warehouse"]->where("warehouse_name","LIKE","%".Request::input("search_txt")."%");
            }
            $data["_warehouse"] = $data["_warehouse"]->paginate(10);
            // $data["_warehouse_archived"] = Tbl_warehouse::inventory()->select_info($this->user_info->shop_id, 1)->groupBy("tbl_warehouse.warehouse_id")->get();
            
            $all_item = null;
            foreach($data["_warehouse"] as $key => $value)
            {
                $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$value->warehouse_id)->first();
                if(!$check_if_owned)
                {
                    unset($data["_warehouse"][$key]);
                }
                else
                {          
                    // $selling_price = 0;
                    // $cost_price = 0;
                    // $total_qty = 0;
                    
                    // $all_item = Tbl_sub_warehouse::select_item($value->warehouse_id)
                    //                              ->get();
                    // foreach ($all_item as $key2 => $value2) 
                    // {
                    //     $qty = 0;
                    //     // $qty = Tbl_warehouse_inventory::where("warehouse_id",$value2->warehouse_id)->leftjoin("tbl_item","inventory_item_id","=","item_id")->where("item_id",$value2->item_id)->where("tbl_item.archived",0)->sum("inventory_count");
                    //     $qty = Tbl_item::where('item_id',$value2->item_id)->inventory($value2->warehouse_id)->value('inventory_count');

                    //     $selling_price += $value2->item_price * $qty;
                    //     $cost_price += $value2->item_cost * $qty;
                    //     $total_qty += $qty;
                    // }
                    // $data["_warehouse"][$key]->total_selling_price = $selling_price;
                    // $data["_warehouse"][$key]->total_cost_price = $cost_price;
                    // $data["_warehouse"][$key]->total_qty = $total_qty;


                    // $data["_warehouse"][$key]->count_no_serial = count(Tbl_warehouse_inventory::item()->warehouse()->inventoryslip()->serialnumber()->groupBy("tbl_warehouse_inventory.inventory_id")->where("inventory_count",">",0)->where("inventory_reason","refill")->where("tbl_item.shop_id",$this->user_info->shop_id)->where("tbl_item.archived",0)->where("tbl_warehouse.warehouse_id",$value->warehouse_id)->whereNull("serial_id")->get()->toArray());
                }
            }
            $archive_item = null;
            // foreach($data["_warehouse_archived"] as $key3 => $value3)
            // {
            //     $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$value3->warehouse_id)->first();
            //     if(!$check_if_owned)
            //     {
            //         unset($data["_warehouse_archived"][$key3]);
            //     }
            //     else
            //     {
            //         $selling_price_a = 0;
            //         $cost_price_a = 0;
            //         $total_qty = 0;
            //         $archive_item = Tbl_sub_warehouse::select_item($value3->warehouse_id)
            //                                      ->get();
            //         $qty = 0;
            //         foreach ($archive_item as $key4 => $value4) 
            //         {
            //             $qty = Tbl_warehouse_inventory::where("warehouse_id",$value4->warehouse_id)->leftjoin("tbl_item","inventory_item_id","=","item_id")->where("item_id",$value4->item_id)->where("tbl_item.archived",0)->sum("inventory_count");

            //             $selling_price_a += $value4->item_price * $qty;
            //             $cost_price_a += $value4->item_cost * $qty;
            //             $total_qty += $qty;
            //         }
            //         $data["_warehouse_archived"][$key3]->total_selling_price = $selling_price_a;
            //         $data["_warehouse_archived"][$key3]->total_cost_price = $cost_price_a;
            //         $data["_warehouse_archived"][$key3]->total_qty = $total_qty;
            //     }
            // }
            $data["_warehouse_archived"] = null;
            $data["enable_serial"] = Tbl_settings::where("shop_id",$this->user_info->shop_id)->where("settings_key","item_serial")->value("settings_value");

            $data['pis'] = Purchasing_inventory_system::check();
            return view("member.warehouse.warehouse_list",$data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function inventory_log($warehouse_id)
    {
        $data["_inventory_log"] = Tbl_warehouse_inventory::item()
                                    ->warehouse()
                                    ->inventoryslip()
                                    ->serialnumber()
                                    ->orderBy("inventory_id","DESC")
                                    ->groupBy("tbl_warehouse_inventory.inventory_id")
                                    ->where("inventory_count",">",0)
                                    ->where("tbl_item.archived",0)
                                    ->where("inventory_reason","refill")
                                    ->where("tbl_item.shop_id",$this->user_info->shop_id)
                                    ->where("tbl_warehouse.warehouse_id",$warehouse_id)
                                    ->get();

        return view("member.warehouse.inventory_log",$data);
    }
    public function item()
    {
        $not_mainwarehouse = Tbl_warehouse::where("main_warehouse",0)->where("warehouse_shop_id",$this->user_info->shop_id)->get();
        if($not_mainwarehouse)
        {
            foreach($not_mainwarehouse as $key => $value) 
            {
                Warehouse::insert_item(Warehouse::getMainwarehouse(),$value->warehouse_id);
            }            
        }
    }
    public function load_warehouse()
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            if(Request::input("id"))
            {
                $data = Tbl_user_warehouse_access::join("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_user_warehouse_access.warehouse_id")->where("user_id",$this->user_info->user_id)->where("archived",0)->where("tbl_warehouse.warehouse_id","!=",Request::input("id"))->get();
            }
            else
            {
                $data = Tbl_user_warehouse_access::join("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_user_warehouse_access.warehouse_id")->where("user_id",$this->user_info->user_id)->where("archived",0)->get();
            }

            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function confirm_serial()
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            return view('member.warehouse.confirm_serial');
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function confirm_serial_submit()
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            $answer = Request::input("answer");

            $items = Session::get("item");
            $data = null;
            $data["item_id"] = isset($items["item_id"]) ? $items["item_id"] : '';   

            if($answer == "yes")
            {            
                $up["has_serial_number"] = 1;
                foreach ($items["item_list"] as $key1 => $value1) 
                {
                    Tbl_item::where("item_id",$key1)->update($up);                
                }
                $data["status"] = "confirmed-serial";
            }
            else
            {
                Session::forget("item");
                $data["status"] = "success";
                $data['message'] = 'Success';
                $data["type"]    = "item";
            }
            
            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function add_serial_number()
    {
        $access = Utilities::checkAccess('item-warehouse', 'add-serial');
        if($access == 1)
        {             
            $items = Session::get("item");

            if(Request::input("inventory_id"))
            {
                $details = Tbl_warehouse_inventory::where("inventory_id",Request::input("inventory_id"))->first();  
                $for_serial_item[$details->inventory_item_id]["quantity"] = $details->inventory_count;
                $for_serial_item[$details->inventory_item_id]["product_id"] = $details->inventory_item_id;
                $for_serial_item[$details->inventory_item_id]["inventory_id"] = $details->inventory_id;

                $items["item_list"] = $for_serial_item;
            }

            $data["items"] = null;
            // $data["slip_id"] = $item["slip_id"];
            foreach ($items["item_list"] as $key => $value) 
            {
                $data["items"][$key] = Tbl_item::where("item_id",$key)->first();
                $data["items"][$key]->quantity = $value["quantity"]; 
                $data["items"][$key]->inventory_id = $value["inventory_id"]; 
            }

            return view('member.warehouse.add_serial',$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function add_serial_number_submit()
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            $inventory = Request::input("inventory_id");
            $quantity = Request::input("quantity");
            $serials = Request::input("serial");
            $item_id = Request::input("item_id");
     
            $count = 1;
            $id = null;
            $insert = null;
            $duplicate_serial = null;

            $data["status"] = null;
            $data["status_message"] = null;
            foreach ($item_id as $key => $value) 
            {
                if($value == $id)
                {                
                    $count ++;
                }
                else
                {
                    $count = 1;
                }

                $insert[$key]["serial_inventory_id"] = $inventory[$value];
                $insert[$key]["item_id"] = $value;
                $insert[$key]["serial_number"] = strtoupper($serials[$key]);
                $insert[$key]["serial_created"] = Carbon::now();
                $insert[$key]["item_count"] = $count;
                $id = $value;

                $update_item["has_serial_number"] = 1;
                Tbl_item::where("item_id",$value)->update($update_item);
                $archived = 1;
                $rules[$key]["serial_number"] = 'required|alpha_num|unique:tbl_inventory_serial_number,serial_number,'.$archived.',archived';

                $validator[$key] = Validator::make($insert[$key],$rules[$key]);

                if($validator[$key]->fails())
                {
                  $data["status"] = "error";
                    foreach ($validator[$key]->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                    {
                        $data["status_message"] .= $message . " ".$insert[$key]["serial_number"];
                    }
                }
                else
                {
                    if($this->array_is_unique($serials) == false)
                    {
                        $duplicate_serial = $serials[$key];
                        $data["status"] = "error";
                        $data["status_message"] = "<li style='list-style:none'> This ".$duplicate_serial." serial number is ducplicate to your entry.</li>";
                    }
                    else
                    {
                        if($serials[$key] == null)
                        {
                            $data["status"] = "error";
                            $data["status_message"] = "The Serial Number is required";
                        }  
                    }

                }
            }

            if($data["status"] == null)
            {        
                $items = Session::get("item");
                $data["item_id"] = $items["item_id"];
                $data["type"]    = "item";
                $data["status"] = "success-adding-serial";
                Tbl_inventory_serial_number::insert($insert);
                Session::forget("item");
            }

            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }

    }
    public static function array_is_unique($array)
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            return array_unique($array) == $array;
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function view($id)
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$id)->first();
            if(!$check_if_owned)
            {
                return $this->show_no_access_modal();
            }
            $data["warehouse"] = Tbl_warehouse::where("warehouse_id",$id)->first();
            $data["warehouse_item"] = Warehouse::select_item_warehouse_single_w_page($id,'array');
            $data["warehouse_item_bundle"] = Warehouse::select_item_warehouse_per_bundle($id,'array');
            // dd(collect($data["warehouse_item_bundle"])->toArray());
            $data["pis"] = Purchasing_inventory_system::check();
            return view("member.warehouse.warehouse_view",$data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function inventory_break_down($warehouse_id, $item_id)
    {
        if($item_id)
        {
            $data["_sir"] = Tbl_sir::truck()->saleagent()->where("sir_warehouse_id",$warehouse_id)->where("ilr_status","!=",2)->where("is_sync",1)->get();

            $qty = 0;
            $data["item_details"] = Item::get_item_details($item_id);
            if($data["item_details"])
            {
                foreach ($data["_sir"] as $key => $value) 
                {   

                    $um_issued = Tbl_unit_measurement_multi::where("multi_um_id",$data["item_details"]->item_measurement_id)->where("is_base",0)->value("multi_id");

                    if($data["item_details"]->item_type_id != 4)
                    {        
                        $qty = Tbl_sir_inventory::where("sir_item_id",$item_id)->where("inventory_sir_id",$value->sir_id)->sum("sir_inventory_count");                       
                    }
                    else
                    {
                        $sir_bundle_item = Tbl_sir_item::where("sir_id",$value->sir_id)->where("item_id",$item_id)->get();
                        $rem_bundle_qty_sir = 0;

                        foreach ($sir_bundle_item as $key_sir => $value_sir) 
                        {
                            $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();

                            $total_bundle_qty = 0;
                            $total_sold_bundle_qty = 0;
                            $rem_bundle_qty = 0;
                            foreach ($bundle_item as $key_bundle => $value_bundle)
                            {
                               $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty;

                               $issued_bundle_qty_item = (UnitMeasurement::um_qty($value_sir->related_um_type) * $value_sir->item_qty) * $bundle_qty;

                               $total_sold_bundle_qty = Tbl_sir_inventory::where("sir_item_id",$value_bundle->bundle_item_id)->where("inventory_sir_id",$value->sir_id)->where("sir_inventory_count","<",0)->sum("sir_inventory_count");
                               $rem_bundle_qty = round(($issued_bundle_qty_item - abs($total_sold_bundle_qty)) / $bundle_qty);
                            }
                            $qty += $rem_bundle_qty; 
                        }     
                    }

                    if($qty > 0)
                    {
                        $data["_sir"][$key]->per_agent_qty = UnitMeasurement::um_view($qty,$data["item_details"]->item_measurement_id,$um_issued);
                    }
                    else
                    {
                        unset($data["_sir"][$key]);
                    } 
                }
               

            }
        }

        return view("member.warehouse.warehouse_sir",$data);

    }
    public function refill()
    {
        $access = Utilities::checkAccess('item-warehouse', 'refill');
        if($access == 1)
        { 
            $id = Request::input("warehouse_id");
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$id)->first();
            if(!$check_if_owned)
            {
                return $this->show_no_access_modal();
            }
            $data["warehouse"] = Tbl_warehouse::where("warehouse_id",$id)->first();
            
            $data["warehouse_item"] = Warehouse::select_item_warehouse_single($id,'array');
            $data["_vendor"]    = Vendor::getAllVendor('active');
            
            $data["_cat"] = Tbl_category::where("type_category","inventory")->where("type_parent_id",0)
                                                                            ->where("type_shop",$this->user_info->shop_id)
                                                                            ->get();
            return view("member.warehouse.warehouse_refill",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function refill_item_vendor($warehouse_id,$vendor_id)
    {        
        $data["_cat"] = Tbl_category::where("type_category","inventory")->where("type_parent_id",0)
                                                                            ->where("type_shop",$this->user_info->shop_id)
                                                                            ->get();
        $data["warehouse"] = Tbl_warehouse::where("warehouse_id",$warehouse_id)->first();
        $data["_vendor"]    = Vendor::getAllVendor('active');
        $data["warehouse_item"] = Warehouse::select_item_warehouse_single_vendor($warehouse_id,'array',$vendor_id);
        return view("member.warehouse.warehouse_refill",$data);
    }
    public function adjust($id)
    {
        $access = Utilities::checkAccess('item-warehouse', 'adjust');
        if($access == 1)
        { 
            // $id = Request::input("warehouse_id");
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$id)->first();
            if(!$check_if_owned)
            {
                return $this->show_no_access_modal();
            }
            $data["warehouse"] = Tbl_warehouse::where("warehouse_id",$id)->first();
            
            $data["warehouse_item"] = Warehouse::select_item_warehouse_single($id,'array');
            
            $data["_cat"] = Tbl_category::where("type_category","inventory")->where("type_parent_id",0)
                                                                            ->where("type_shop",$this->user_info->shop_id)
                                                                            ->get();                                                          
            return view("member.warehouse.adjust_inventory",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function adjust_submit()
    {      
        $return["status"] = "";
        $return["status_message"] = "";

        $warehouse_id = Request::input("warehouse_id");
        $remarks = Request::input("remarks");

        $item_id = Request::input("item_id");
        $quantity_product = Request::input("quantity");
        
        $warehouse_adjust_product = null;

        $cnt = 0;
        foreach ($item_id as $key => $value) 
        {
            if($value != "")
            {
                $item[$key]["item_id"] = $value;
                $item[$key]["quantity"] = $quantity_product[$key];
                
                $rules[$key]["item_id"] = "required";
                $rules[$key]["quantity"] = "required";

                $validator[$key] = Validator::make($item[$key],$rules[$key]);
                if($validator[$key]->fails())
                {
                    $return["status"] = "error";
                    $item_name = Item::get_item_details($value);
                    foreach ($validator[$key]->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                    {
                        $return["status_message"] .= $message. " : ".$item_name->item_name;
                    }
                }                
            }
            else
            {
                $return["status"] = "error";
                $return["status_message"] = "Please insert Item";
                $cnt++;
            }
        }
        if($return["status"] == "" && $cnt == 0)
        {
            foreach ($item_id as $key => $value) 
            {
                if($value != null)
                {
                    $warehouse_adjust_product[$key]['product_id'] = $value;
                    $warehouse_adjust_product[$key]['quantity'] = $quantity_product[$key];
                }
            }
            $data = Warehouse::adjust_inventory($warehouse_id, "", "", $remarks, $warehouse_adjust_product,'json');
        }
        else
        {
            $data = json_encode($return);
        }

        return $data;
    }
    public static function check_if_critical($c_stock, $reorder_point)
    {
        $color = "";
        if($c_stock <= $reorder_point)
        {
            $color = "red";
        }
        return $color;
    }
    public function create_main()
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            $warehouse = Tbl_warehouse::get();

            if($warehouse == null)
            {
                //MAKE MAIN WAREHOUSE
                $ins_warehouse["warehouse_name"] = "Main Warehouse";
                $ins_warehouse["warehouse_shop_id"] = $this->user_info->shop_id;
                $ins_warehouse["warehouse_created"] = Carbon::now();
                $ins_warehouse["main_warehouse"] = 1;

                Tbl_warehouse::insert($ins_warehouse);
            }
        }
        else
        {
            return $this->show_no_access();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $access = Utilities::checkAccess('item-warehouse', 'add');
        if($access == 1)
        { 
           $data["merchantwarehouse"] = Utilities::checkAccess('item-warehouse', 'merchantwarehouse');
           $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id);
           $data["_item"] = Tbl_item::where("archived",0)->where("item_type_id",1)->where("shop_id",$this->user_info->shop_id)->get();
           // $data["_cat"] = Tbl_category::where("type_category","inventory")->where("type_parent_id",0)->where("type_shop",$this->user_info->shop_id)->get();
           return view("member.warehouse.warehouse_add",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function load_item()
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            $data = Tbl_item::where("archived",0)->where("item_type_id",1)->where("shop_id",$this->user_info->shop_id)->get();

            if(Session::get("item_id") != null)
            {
                $_item_id = Session::get("item_id");
                foreach ($_item_id as $key => $value) 
                {
                    $_item_id[$key] = $value;
                }
            }
            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function archived($warehouse_id)
    {
        $access = Utilities::checkAccess('item-warehouse', 'archived');
        if($access == 1)
        { 
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$warehouse_id)->first();
            if(!$check_if_owned)
            {
                return $this->show_no_access_modal();
            }
            $shop_id               = $this->user_info->shop_id;
            $data["page"]          = "Warehouse";
            $data["warehouse"]          = Tbl_warehouse::inventory()
                                                        ->selectRaw('*, sum(tbl_warehouse_inventory.inventory_count) as total_qty')
                                                        ->where("tbl_warehouse.warehouse_id",$warehouse_id)
                                                        ->where("tbl_warehouse.warehouse_shop_id",$shop_id)
                                                        ->first();

            $selling_price = 0;
            $cost_price = 0;
            $all_item = Tbl_sub_warehouse::join("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_sub_warehouse.warehouse_id")
                                             ->join("tbl_item","tbl_item.item_id","=","tbl_sub_warehouse.item_id")
                                             ->where("tbl_sub_warehouse.warehouse_id",$warehouse_id) 
                                             ->get();

            foreach ($all_item as $key2 => $value2) 
            {
                $qty = Tbl_warehouse_inventory::where("inventory_item_id",$value2->item_id)
                                                    ->where("warehouse_id",$warehouse_id)
                                                    ->sum("inventory_count");

                $selling_price += $value2->item_price * $qty;
                $cost_price += $value2->item_cost * $qty;
            }
            $data["warehouse"]->total_selling_price = $selling_price;
            $data["warehouse"]->total_cost_price = $cost_price;

            if(!$data["warehouse"])
            {
                dd("Please try again.");
            }
            return view('member.warehouse.warehouse_confirm_archived',$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function archived_submit()
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            $shop_id    = $this->user_info->shop_id;
            $id         = Request::input("warehouse_id");
            $warehouse  = Tbl_warehouse::where("warehouse_id",$id)->where("warehouse_shop_id",$shop_id)->first();


            $all_item = Tbl_sub_warehouse::join("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_sub_warehouse.warehouse_id")
                                             ->join("tbl_item","tbl_item.item_id","=","tbl_sub_warehouse.item_id")
                                             ->where("tbl_sub_warehouse.warehouse_id",$id) 
                                             ->get();
            $qty = 0;
            foreach ($all_item as $key2 => $value2) 
            {
                $qty += Tbl_warehouse_inventory::where("inventory_item_id",$value2->item_id)
                                                    ->where("warehouse_id",$id)
                                                    ->sum("inventory_count");
            }

            if($qty == 0)
            {
                if($warehouse->main_warehouse == 0)
                {
                    if($warehouse)
                    {
                           $update["archived"] = 1;
                           Tbl_warehouse::where("warehouse_id",$id)->update($update);
                           $return["error"][0]  = "Successfully archived";
                           $return["status"]   = "Sucess-archived";
                    }
                    else
                    {
                        $return["error"][0]  = "Please try again";
                        $return["status"]   = "Failed";
                    }            
                }
                else
                {
                    $return["error"][0]  = "You cannot Archived the a Default Warehouse";
                    $return["status"]   = "Failed";
                }                
            }
            else
            {
                $return["error"][0]  = "You cannot Archived this Warehouse, it has Total of: ".$qty." item/s";
                $return["status"]   = "Failed";                
            }

            if($return['status'] == "Sucess-archived")
            {
                $wh_data = AuditTrail::get_table_data("tbl_warehouse","warehouse_id",$id);
                AuditTrail::record_logs("Archived","warehouse",$id,"",serialize($wh_data));                
            }


            return json_encode($return);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function restore($warehouse_id)
    {
        $access = Utilities::checkAccess('item-warehouse', 'restore');
        if($access == 1)
        { 
            $shop_id               = $this->user_info->shop_id;
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$warehouse_id)->first();
            if(!$check_if_owned)
            {
                return $this->show_no_access_modal();
            }
            $data["page"]          = "Warehouse";
            $data["warehouse"]     = Tbl_warehouse::inventory()
                                                        ->selectRaw('*, sum(tbl_warehouse_inventory.inventory_count) as total_qty')
                                                        ->where("tbl_warehouse.warehouse_id",$warehouse_id)
                                                        ->where("tbl_warehouse.warehouse_shop_id",$shop_id)
                                                        ->first();

            $selling_price = 0;
            $cost_price = 0;
            $all_item = Tbl_sub_warehouse::join("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_sub_warehouse.warehouse_id")
                                             ->join("tbl_item","tbl_item.item_id","=","tbl_sub_warehouse.item_id")
                                             ->where("tbl_sub_warehouse.warehouse_id",$warehouse_id) 
                                             ->get();

            foreach ($all_item as $key2 => $value2) 
            {
                $qty = Tbl_warehouse_inventory::where("inventory_item_id",$value2->item_id)
                                                    ->where("warehouse_id",$warehouse_id)
                                                    ->sum("inventory_count");

                $selling_price += $value2->item_price * $qty;
                $cost_price += $value2->item_cost * $qty;
            }
            $data["warehouse"]->total_selling_price = $selling_price;
            $data["warehouse"]->total_cost_price = $cost_price;

            if(!$data["warehouse"])
            {
                dd("Please try again.");
            }
            return view('member.warehouse.warehouse_restore',$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function refill_submit()
    {
        $warehouse_id = Request::input("warehouse_id");
        $remarks = Request::input("remarks");
        $reason_refill = Request::input("reason_refill") == "other" ? "other" : "vendor";
        $refill_source = Request::input("reason_refill") == "other" ? 0 : Request::input("reason_refill");

        $quantity_product = Request::input("quantity");

        $warehouse_refill_product = null;
        foreach ($quantity_product as $key => $value) 
        {
            if($value != 0)
            {
                $warehouse_refill_product[$key]['product_id'] = $key;
                $warehouse_refill_product[$key]['quantity'] = str_replace(",","",$value);                
            }
        }
        // dd($warehouse_refill_product);/
        $data = Warehouse::inventory_refill($warehouse_id, $reason_refill, $refill_source, $remarks, $warehouse_refill_product,'json');

        return $data;
    }
    public function restore_submit()
    {
        $shop_id    = $this->user_info->shop_id;
        $id         = Request::input("warehouse_id");
        $warehouse  = Tbl_warehouse::where("warehouse_id",$id)->where("warehouse_shop_id",$shop_id)->first();
        if($warehouse)
        {
               $update["archived"] = 0;
               Tbl_warehouse::where("warehouse_id",$id)->update($update);
               $return["error"][0]  = "Successfully restore";
               $return["status"]   = "Sucess-restore";
        }
        else
        {
            $return["error"][0]  = "Please try again";
            $return["status"]   = "Failed";
        }
        if($return['status'] == "Sucess-restore")
        {
            $wh_data = AuditTrail::get_table_data("tbl_warehouse","warehouse_id",$id);
            AuditTrail::record_logs("Restore","warehouse",$id,"",serialize($wh_data));                
        }

        return json_encode($return);
    }
    public function select_item()
    {
        $access = Utilities::checkAccess('item-warehouse', 'access_page');
        if($access == 1)
        { 
            // Session::forget('item_id');
            $item_id = Request::input("item_id");

            // $item["item"] = $item_id;
            $item = array();
            if(Session::has("item_id")){
                $item = Session::get("item_id");
                // dd("meron");
            }
            
            $space = '';
            if (!in_array($item_id, $item))
            {     
                array_push($item, $item_id);
            }
            Session::put("item_id",$item);
            Session::save();

            $data = Tbl_item::where("item_id",$item_id)->first();
            
            return json_encode($data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function add_submit()
    {
            $merchantwarehouse  = Utilities::checkAccess('item-warehouse', 'merchantwarehouse');
            $for_serial_item    = null;
            $merchant_warehouse = Request::input("merchant_warehouse") ? "1" : "0";
            /*INSERT TO WAREHOUSE (MERCHANT) */
            $ins_warehouse["merchant_warehouse"]   = $merchant_warehouse;
            if($merchant_warehouse == 1)
            {
                $ins_warehouse["merchant_logo"]                        = Request::input("merchant_logo_input");
                $ins_warehouse['default_repurchase_points_mulitplier'] = Request::input("default_repurchase_points_mulitplier"); 
                $ins_warehouse['default_margin_per_product']           = Request::input("default_margin_per_product");

                if($ins_warehouse['default_repurchase_points_mulitplier'] == null || $ins_warehouse['default_repurchase_points_mulitplier'] == "")
                {
                    $data['status']         = 'error';
                    $data['status_message'] = 'Default Repurchase Points Multiplier is required';

                    return json_encode($data);
                }

                if($ins_warehouse['default_margin_per_product'] == null || $ins_warehouse['default_margin_per_product'] == "")
                {
                    $data['status']         = 'error';
                    $data['status_message'] = 'Default Margin Per Product is required';

                    return json_encode($data);
                }
            }
            else
            {
                $ins_warehouse["merchant_logo"]                        = "";
                $ins_warehouse['default_repurchase_points_mulitplier'] = 0;
                $ins_warehouse['default_margin_per_product']           = 0;
            }

            //INSERT TO tbl_warehouse
            $ins_warehouse["warehouse_name"]    = Request::input("warehouse_name");
            $ins_warehouse["warehouse_address"] = Request::input("warehouse_address");
            $ins_warehouse["warehouse_parent_id"] = Request::input("warehouse_parent_id") != null ? Request::input("warehouse_parent_id") : 0;
            $ins_warehouse["warehouse_shop_id"] = $this->user_info->shop_id;
            $ins_warehouse["warehouse_created"] = Carbon::now();

            $id = Tbl_warehouse::insertGetId($ins_warehouse);

            Warehouse::insert_access($id);

            //INSERT tbl_warehouse per item reorderpoint
            $reorderpoint = Request::input("reoder_point");
            if($reorderpoint != null)
            {
                foreach ($reorderpoint as $key => $value) 
                {
                    $check = Tbl_sub_warehouse::where("item_id",$key)->where("warehouse_id",$id)->first();
                    if($check == null)
                    {
                        $ins_sub["warehouse_id"] = $id;
                        $ins_sub["item_id"] = $key;
                        $ins_sub["item_reorder_point"] = str_replace(",","",$value);

                        Tbl_sub_warehouse::insert($ins_sub);
                    }
                }                
            }
            //TRANSFER INVENTORY From MAIN to NEW warehouse 
            $quantity = Request::input("quantity");
            // dd($quantity);
            if($quantity != null)
            {
                foreach($quantity as $key => $value)
                {
                    $ins_inventory["inventory_item_id"] = $key;
                    $ins_inventory["warehouse_id"] = $id;
                    $ins_inventory["inventory_created"] = Carbon::now();  
                    $ins_inventory["inventory_count"] = str_replace(",","",$value);

                    $inventory_id = Tbl_warehouse_inventory::insertGetId($ins_inventory);

                    $for_serial_item[$key]["quantity"] = str_replace(",","",$value);
                    $for_serial_item[$key]["product_id"] = $key;
                    $for_serial_item[$key]["inventory_id"] = $inventory_id;       
                }                
            }

                $items["item_id"] = "";
                $items["item_list"] = $for_serial_item;


            $serial = Tbl_settings::where("settings_key","item_serial")->where("settings_value","enable")->where("shop_id",$this->user_info->shop_id)->first();
            if($serial != null && $for_serial_item != null)
            {
                $data['status'] = 'success-serial';
                Session::put("item", $items);
            }
            else
            {                
                $data['status'] = 'success';
                $data['type'] = 'warehouse';
            }
            if($data['status'] == 'success')
            {
                $w_data = AuditTrail::get_table_data("tbl_warehouse","warehouse_id",$id);
                AuditTrail::record_logs("Added","warehouse",$id,"",serialize($w_data));
            }

             return json_encode($data);
    }
    public function edit($id)
    {
        $access = Utilities::checkAccess('item-warehouse', 'edit');
        if($access == 1)
        { 
           $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id);
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$id)->first();
            if(!$check_if_owned)
            {
                return $this->show_no_access_modal();
            }
            $data["_item"] = Tbl_item::where("archived",0)->where("item_type_id",1)->where("shop_id",$this->user_info->shop_id)->get();
            $data["warehouse"] = Tbl_warehouse::where("warehouse_id",$id)->first();
            $data["merchantwarehouse"] = Utilities::checkAccess('item-warehouse', 'merchantwarehouse');

            $data["warehouse_item"] = Tbl_warehouse::warehouseitem()
                                                    ->selectRaw('*, tbl_sub_warehouse.item_reorder_point as sub_reorder_point')
                                                    ->where("tbl_sub_warehouse.warehouse_id",$id)
                                                    ->groupBy("tbl_sub_warehouse.item_id")
                                                    ->get();
            foreach ($data["warehouse_item"] as $key => $value) 
            {
                $qty = Tbl_warehouse_inventory::where("inventory_item_id",$value->item_id)
                                              ->where("warehouse_id",$id)
                                              ->sum("inventory_count");
                $data["warehouse_item"][$key]->item_qty = $qty;
            }
     
            return view("member.warehouse.warehouse_edit",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function edit_submit()
    {

        $warehouse_id  = Request::input("warehouse_id");

        $old_data = AuditTrail::get_table_data("tbl_warehouse","warehouse_id",$warehouse_id);

        $up_warehouse["warehouse_name"] = Request::input("warehouse_name");
        $up_warehouse["warehouse_address"] = Request::input("warehouse_address");
        $up_warehouse["warehouse_shop_id"] = $this->user_info->shop_id;
        // $up_warehouse["warehouse_created"] = Carbon::now();



        $merchant_warehouse = Request::input("merchant_warehouse") ? "1" : "0";
        /*INSERT TO WAREHOUSE (MERCHANT) */
        $up_warehouse["merchant_warehouse"]   = $merchant_warehouse;
        if($merchant_warehouse == 1)
        {
            $up_warehouse["merchant_logo"]                        = Request::input("merchant_logo_input");
            $up_warehouse['default_repurchase_points_mulitplier'] = Request::input("default_repurchase_points_mulitplier"); 
            $up_warehouse['default_margin_per_product']           = Request::input("default_margin_per_product");

            if($up_warehouse['default_repurchase_points_mulitplier'] == null || $up_warehouse['default_repurchase_points_mulitplier'] == "")
            {
                $data['status']         = 'error';
                $data['status_message'] = 'Default Repurchase Points Multiplier is required';

                return json_encode($data);
            }

            if($up_warehouse['default_margin_per_product'] == null || $up_warehouse['default_margin_per_product'] == "")
            {
                $data['status']         = 'error';
                $data['status_message'] = 'Default Margin Per Product is required';

                return json_encode($data);
            }
        }
        else
        {
            $up_warehouse["merchant_logo"]                        = "";
            $up_warehouse['default_repurchase_points_mulitplier'] = 0;
            $up_warehouse['default_margin_per_product']           = 0;
        }







        Tbl_warehouse::where("warehouse_id",Request::input("warehouse_id"))->update($up_warehouse);

        //EDIT tbl_warehouse per item reorderpoint
        $reorderpoint = Request::input("reoder_point");
        $quantity = Request::input("quantity");

        // dd($reorderpoint);
        if($reorderpoint != null)
        {
            foreach ($reorderpoint as $key => $value) 
            {
                $up_sub["warehouse_id"] = Request::input("warehouse_id");
                $up_sub["item_id"] = $key;
                $up_sub["item_reorder_point"] = str_replace(",","",$value);

                $sub = Tbl_sub_warehouse::where("item_id",$key)->where("warehouse_id",Request::input("warehouse_id"))->first();
                if($sub)
                {
                    Tbl_sub_warehouse::where("item_id",$key)->where("warehouse_id",Request::input("warehouse_id"))->update($up_sub);                
                }
                else
                {
                    Tbl_sub_warehouse::insert($up_sub);
                    
                    $ins_inventory["inventory_item_id"] = $key;
                    $ins_inventory["warehouse_id"] = Request::input("warehouse_id");
                    $ins_inventory["inventory_created"] = Carbon::now();            
                    $ins_inventory["inventory_count"] = $quantity[$key];

                    Tbl_warehouse_inventory::insert($ins_inventory);
                }
            }
        }

            //CREATE INVENTORY From warehouse 
            // foreach($quantity as $key => $value)
            // {          
            //     $ins_inventory["inventory_item_id"] = $key;
            //     $ins_inventory["warehouse_id"] = Request::input("warehouse_id");
            //     $ins_inventory["inventory_created"] = Carbon::now();            
            //     $ins_inventory["inventory_count"] = $value;

            //     Tbl_warehouse_inventory::insert($ins_inventory);

            // }

        $data['status'] = 'success';


        if($data['status'] == 'success')
        {
            $w_data = AuditTrail::get_table_data("tbl_warehouse","warehouse_id",$warehouse_id);
            AuditTrail::record_logs("Edited","warehouse",$warehouse_id,serialize($old_data),serialize($w_data));
        }

             return json_encode($data);
    }
    public function transferinventory()
    {
        $access = Utilities::checkAccess('item-warehouse', 'transfer');
        if($access == 1)
        { 
           // $data = Tbl_user_warehouse_access::join("tbl_warehouse","tbl_warehouse.warehouse_id","=","tbl_user_warehouse_access.warehouse_id")->where("user_id",$this->user_info->user_id)->where("archived",0)->get();
           $data["warehouse"] = Tbl_warehouse::where("archived",0)->where("warehouse_shop_id",$this->user_info->shop_id)->get();
           foreach ($data["warehouse"] as $key => $value) 
           {
               $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$value->warehouse_id)->first();
               if(!$check_if_owned)
                {
                    unset($data["warehouse"][$key]);
                }
                            
           } 

           return view("member.warehouse.warehouse_transfer",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function transferinventory_submit()
    {
        $access = Utilities::checkAccess('item-warehouse', 'transfer');
        if($access == 1)
        { 
            $from = Request::input("inventory_from");
            $to = Request::input("inventory_to");
            if($from != null && $to != null)
            {
                $data["from"] = Tbl_warehouse::where("archived",0)->where("warehouse_id",$from)->first();
                $data["to"] = Tbl_warehouse::where("archived",0)->where("warehouse_id",$to)->first();

                Warehouse::insert_item($from,$to);

                $count = Tbl_sub_warehouse::where("warehouse_id",$to)->get();
                $ctr = 0;
                foreach ($count as $key => $value) 
                { 
                  $qty_item = Tbl_warehouse_inventory::where("warehouse_id",$to)->where("inventory_item_id",$value->item_id)->sum("inventory_count");
                
                  if($qty_item <= $value->item_reorder_point)
                  {
                    $ctr = $ctr + 1; 
                  }
                }

                $data["ctr"] = $ctr;
                
                $data["warehouse_list"] = Warehouse::get_transfer_warehouse_information($from, $to,'array');

                return view("member.warehouse.warehouse_transferring",$data);
            }
            else
            {
                return Redirect("/member/item/warehouse");
            }
        }
        else
        {
            return $this->show_no_access();
        }
        
    }
    public function transfer_submit()
    {
        $from = Request::input("warehouse_from");
        $to = Request::input("warehouse_to");

        $remarks = Request::input("remarks");
        $quantity_product = Request::input("quantity");
        $selected_item_id = Request::input("selected_item_id");

        $data['message'] = "";
        $err_msg = "";

        $data['status']             = '';
        $data['response_status']    = '';

        $ctr = 0;
        foreach ($selected_item_id as $key => $value) 
        {
            if($value != "")
            {
                $value2 = $quantity_product[$key];
                $count_on_hand = Tbl_warehouse_inventory::check_inventory_single($from, $value)->value('inventory_count');
                if($value2 != 0)
                {
                    if($count_on_hand > 0 && $count_on_hand >= $value2) 
                    {
                        $info[$value]['product_id'] = $value;
                        $info[$value]['quantity'] = str_replace(",","",$value2);
                    }
                    else
                    {
                        $item_name = Item::get_item_details($value);

                        $data['status']             = 'error';
                        $data['response_status']    = 'error';
                        $data['error']              = $err_msg;
                        $data['message'] .= "The quantity of ".$item_name->item_name." is not enough for your transfer.<br>";
                    }      
                    $ctr++;              
                }
            }
        }
        if($ctr == 0)
        {
            $data['status']             = 'error';
            $data['response_status']    = 'error';
            $data['error']              = $err_msg;
            $data['message'] = "Please insert atleast 1 item and quantity to transfer.";                
        }

        if($data["status"] == null && $ctr != 0)
        {
            $data = Warehouse::inventory_transfer_bulk($from, $to, $info, $remarks, 'json');
        }
        
        return json_encode($data);
        
    }
    public function view_v2($warehouse_id)
    {
        $type = 'bundle';
        $data = Warehouse::get_inventory_item($warehouse_id, $type);

        $data['pis'] = Purchasing_inventory_system::check();
        $data['warehouse_id'] = $warehouse_id;
        $data['warehouse'] = Tbl_warehouse::where('warehouse_id',$warehouse_id)->first();
        $data['page'] = "View Warehouse";
        $data['warehouse_name'] = 'Main Warehouse';
        $data["_manufacturer"]   = Manufacturer::getAllManufaturer();

        return view("member.warehouse.warehouse_view_v2",$data);
    }
    public function print_inventory($warehouse_id, $type = '')
    {
        $manufacturer_id = Request::input('m_id');
        $data = Warehouse::get_inventory_item($warehouse_id, $type, $manufacturer_id);
        $data['type'] = $type;
        $data['pis'] = Purchasing_inventory_system::check();
        $data['owner'] = Tbl_warehouse::shop()->where('warehouse_id',$warehouse_id)->first();
        $data['manufacturer_name'] = Tbl_manufacturer::where('manufacturer_id',$manufacturer_id)->value('manufacturer_name');

        // return view("member.warehouse.warehouse_inventory_print",$data);

        $pdf = view("member.warehouse.warehouse_inventory_print",$data);
        return Pdf_global::show_pdf($pdf);
    }
    public function view_inventory_table($warehouse_id = 0)
    {
        $type = 'bundle';
        $manufacturer_id = Request::input('m_id');
        if(Request::input('type'))
        {
            $type = Request::input('type');   
        }

        $data = Warehouse::get_inventory_item($warehouse_id, $type, $manufacturer_id);
        $data['pis'] = Purchasing_inventory_system::check();
        $data['warehouse_id'] = $warehouse_id;
        $data['warehouse'] = Tbl_warehouse::where('warehouse_id',$warehouse_id)->first();

        return view("member.warehouse.warehouse_view_v2_inventory_table",$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
