<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Models\Tbl_position;
use App\Models\Tbl_employee;
use App\Models\Tbl_truck;
use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_sir;
use App\Models\Tbl_sir_inventory;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_sir_cm_item;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_bill;
use App\Models\Tbl_sir_sales_report;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_settings;
use App\Globals\UnitMeasurement;
use App\Globals\Warehouse;
use App\Globals\Pdf_global;
use App\Globals\Item;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use App\Globals\Purchasing_inventory_system;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;
use Session;
use PDF;
use Redirect;
class PurchasingInventorySystemController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * lof_status
     * 1 - created and pass to the agent's tablet
     * 2 - confirmed by the agent (currently_sync)
     * 3 - rejected by the agent, return to warehouse supervisor's list of LOF
     *
     *
     * sir_status
     * 0 - new
     * 1 - open
     * 2 - close 

     * ilr_status
     * 1 - open
     * 2 - close 
     */
    public function view_status($sir_id)
    {
        $data["sir"] = Purchasing_inventory_system::view_status($sir_id);
        return view("member.purchasing_inventory_system.view_status",$data);        
    }
    public function enable_pis($pass, $action)
    {
        if($pass == "water123")
        {
            $shop_id = Purchasing_inventory_system::getShopId();

            $settings_key          =     "pis-jamestiong";
            $settings_value        =     $action; 

            $check = Purchasing_inventory_system::check();
            if($check)
            {
                $update["settings_value"] = $action;

                Tbl_settings::where("shop_id",$shop_id)->update($update);
            }
            else
            {
                $ins["settings_key"]        = $settings_key;
                $ins["settings_value"]      = $settings_value;
                $ins["settings_setup_done"] = 1;
                $ins["shop_id"]             = $shop_id;

                Tbl_settings::insert($ins);
            }
        }
        return Redirect::to('/member');
    }
    public function pis_counter()
    {
        $return["lof_ctr"] = 0;
        $return["sir_ctr"] = 0;
        $return["ilr_ctr"] = 0;
        $return["col_ctr"] = 0;
        $return["inv_ctr"] = 0;
        $return["po_ctr"] = 0;
        $return["bill_ctr"] = 0;
        if(Purchasing_inventory_system::check() != 0)
        {
            $return["lof_ctr"] = Tbl_sir::where("shop_id",Purchasing_inventory_system::getShopId())->whereIn("lof_status",[1,3])->count();
            $return["sir_ctr"] = Tbl_sir::where("shop_id",Purchasing_inventory_system::getShopId())->where("reload_sir",1)->count();
            $return["ilr_ctr"] = Tbl_sir::where("shop_id",Purchasing_inventory_system::getShopId())->where("ilr_status",1)->count();

            $return["col_ctr"] = Tbl_sir::where("shop_id",Purchasing_inventory_system::getShopId())->where("ilr_status",1)->count();

            $return["inv_ctr"] = Tbl_customer_invoice::where("inv_shop_id",Purchasing_inventory_system::getShopId())->where("inv_is_paid",0)->count();

            $return["po_ctr"] = Tbl_purchase_order::where("po_shop_id",Purchasing_inventory_system::getShopId())->where("po_is_billed",0)->count();

            $return["bill_ctr"] = Tbl_bill::where("bill_shop_id",Purchasing_inventory_system::getShopId())->where("bill_is_paid",0)->count();
        }

        return json_encode($return);
    }
    public function update_count($sir_id, $item_id)
    {
        $data["sir_item"] = Tbl_sir_item::select_sir_item()->where("sir_id",$sir_id)->where("tbl_sir_item.item_id",$item_id)->first();

        $data["base_name"] = null;
        $data["base_qty"] = null;
        $data["base_um_id"] = null;

        $data["issued_um_qty"] = null;
        $data["issued_um_name"] = null;
        $data["issued_um_id"] = null;
        
        if($data["sir_item"]->item_type_id != 4)
        {

            $rem_qty = Purchasing_inventory_system::count_rem_qty($sir_id, $item_id);
            $sold_qty = Purchasing_inventory_system::count_sold_qty($sir_id, $item_id);

            $um = Tbl_unit_measurement_multi::where("multi_id",$data["sir_item"]->related_um_type)->first();
           
            $data["sir_item"]->um_name = isset($um->multi_name) ? $um->multi_name : "";
            $data["sir_item"]->um_abbrev = isset($um->multi_abbrev) ? $um->multi_abbrev : "PC";

            $issued_qty = $data["sir_item"]->item_qty * UnitMeasurement::um_qty($data["sir_item"]->related_um_type);
            // dd($issued_qty);
            $remaining_qty = $rem_qty;
            $rem = "";
            $sold = "";

            $um_info = UnitMeasurement::um_info($data["sir_item"]->related_um_type);
            if($data["sir_item"]->item_measurement_id == $data["sir_item"]->related_um_type)
            {
                if($um != null)
                {                
                   $rem = $remaining_qty." ".$um->multi_abbrev;
                   $sold = $sold_qty." ".$um->multi_abbrev;

                   $data["base_qty"] = $remaining_qty;
                   $data["base_name"] = $um->multi_name." (".$um->multi_abbrev.")";
                   $data["base_um_id"] = $data["sir_item"]->related_um_type;
                }
                else
                {                
                   $data["base_qty"] = $remaining_qty;
                   $data["base_name"] = "Piece(s)";
                   $data["base_um_id"] = "";   
                }
            }
            else
            {                    
                //item base um
                $base_um = Tbl_unit_measurement_multi::where("multi_um_id",$data["sir_item"]->item_measurement_id)->where("is_base",1)->first();

                //for remaining
                $total_qty_issued =  $data["sir_item"]->item_qty * $um->unit_qty;
                $rem_qty = $remaining_qty;
                $issued_um_qty = $um->unit_qty;
                $issued_um = floor($rem_qty / $issued_um_qty);
                $each = (($rem_qty / $issued_um_qty) - floor($rem_qty / $issued_um_qty)) * $issued_um_qty;                

                //for sold
                $um_q = floor($sold_qty / $issued_um_qty);
                $sold_each = (($sold_qty / $issued_um_qty) - floor($sold_qty / $issued_um_qty)) * $issued_um_qty;                


                if($sold_qty != 0)
                {
                    $data["base_name"] = $base_um->multi_name." (".$base_um->multi_abbrev.")";
                    $data["base_qty"] = $each;
                    $data["base_um_id"] = $data["sir_item"]->item_measurement_id;
                    $data["issued_um_qty"] = $issued_um;
                    $data["issued_um_name"] = $um->multi_name." (".$um->multi_abbrev.")";
                    $data["issued_um_id"] = $data["sir_item"]->related_um_type;
                    $rem = $issued_um." ".$um->multi_abbrev." & ".$each." ".$base_um->multi_abbrev;
                }
                else
                {                
                    $data["issued_um_qty"] = $issued_um;
                    $data["issued_um_name"] = $um->multi_name." (".$um->multi_abbrev.")";
                    $data["issued_um_id"] = $data["sir_item"]->related_um_type;
                    $rem = $issued_um." ".$um->multi_abbrev;
                }

            }
            if($um != null)
            {
                if($um_info->is_base == 1)
                {
                   $rem = $remaining_qty." ".$um_info->multi_abbrev;
                   $sold = $sold_qty." ".$um_info->multi_abbrev;

                   $data["base_qty"] = $remaining_qty;
                   $data["base_name"] = $um_info->multi_name." (".$um_info->multi_abbrev.")";
                   $data["base_um_id"] = $data["sir_item"]->related_um_type;         

                   $data["issued_um_qty"] = null;
                   $data["issued_um_name"] = null;
                   $data["issued_um_id"] = null;   
                }
            }
            $data["sir_item"]->remaining_qty = $rem;

        }
        else
        {
            $base_um = Tbl_unit_measurement_multi::where("multi_um_id",$data["sir_item"]->item_measurement_id)->where("is_base",1)->first();

            $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();

            $total_bundle_qty = 0;
            $total_sold_bundle_qty = 0;
            $qty = [];
            $qtys = [];
            $base_qty = $data["sir_item"]->item_qty;
            foreach ($bundle_item as $key_bundle => $value_bundle)
            {
               $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty;

               $issued_bundle_qty_item = $data["sir_item"]->item_qty * $bundle_qty;

               $total_sold_bundle_qty = Tbl_sir_inventory::where("sir_item_id",$value_bundle->bundle_item_id)->where("inventory_sir_id",$sir_id)->where("sir_inventory_count","<",0)->sum("sir_inventory_count");
               $rem_bundle_qty = ($issued_bundle_qty_item - abs($total_sold_bundle_qty)) / $bundle_qty;
               $sold_bundle_qty = $base_qty - $rem_bundle_qty;
            }

            $rem_qty  = $rem_bundle_qty;
            $sold_qty = $sold_bundle_qty;

           $data["base_qty"] = $rem_qty;
           $data["base_name"] = $base_um->multi_name." (".$base_um->multi_abbrev.")";
           $data["base_um_id"] = $data["sir_item"]->item_measurement_id;

        }

        

        return view("member.purchasing_inventory_system.ilr.update_count",$data);
    }

    public function update_count_empties($sc_id)
    {
        $data["item"] = Tbl_sir_cm_item::item()->where("s_cm_item_id",$sc_id)->first();

        $data["base_name"] = null;
        $data["base_qty"] = null;
        $data["base_um_id"] = null;

        $data["issued_um_qty"] = null;
        $data["issued_um_name"] = null;
        $data["issued_um_id"] = null;


        $item_issued_um = Tbl_unit_measurement_multi::where("multi_um_id",$data["item"]->item_measurement_id)->where("is_base",0)->value("multi_id");
        if($data["item"]->item_measurement_id != null && $item_issued_um != null)
        {  
           $um_base_info = Tbl_unit_measurement_multi::where("multi_um_id",$data["item"]->item_measurement_id)->where("is_base",1)->first();
            $um_issued_info = UnitMeasurement::um_info($item_issued_um);

           $base_qty = $data["item"]->sc_item_qty;
           $um_qty = 1;
           if($um_issued_info)
           {
               $um_qty = $um_issued_info->unit_qty;
           }
           $each = round((($base_qty / $um_qty) - floor($base_qty / $um_qty)) * $um_qty);
           $data["base_qty"] = $each;
           $data["base_name"] = $um_base_info->multi_name."(".$um_base_info->multi_abbrev.")";
           $data["base_um_id"] = $um_base_info->multi_id;


            $issued_qty = $data["item"]->sc_item_qty;
            $data["issued_um_qty"] = floor($issued_qty / $um_issued_info->unit_qty);
            $data["issued_um_name"] =  $um_issued_info->multi_name."(".$um_issued_info->multi_abbrev.")";
            $data["issued_um_id"] = $item_issued_um;

        }
        elseif($data["item"]->item_measurement_id != null)
        {
           $um_base_info = Tbl_unit_measurement_multi::where("multi_um_id",$data["item"]->item_measurement_id)->where("is_base",1)->first();
           $um_issued_info = UnitMeasurement::um_info($item_issued_um);
           $um_qty = 1;
           $base_qty = $data["item"]->sc_item_qty;
           if($um_issued_info)
           {
               $um_qty = $um_base_info->unit_qty;
           }
           $each = round((($base_qty / $um_qty) - floor($base_qty / $um_qty)) * $um_qty);
           $data["base_qty"] = $each;
           $data["base_name"] = $um_base_info->multi_name."(".$um_base_info->multi_abbrev.")";
           $data["base_um_id"] = $um_base_info->multi_id;
        }
        else
        {
               $data["base_qty"] = $data["item"]->sc_item_qty;
               $data["base_name"] = "Piece(s)";
               $data["base_um_id"] = "";   
        }
        return view("member.purchasing_inventory_system.ilr.update_count_empties",$data);

    }
    public function update_count_empties_submit()
    {
        $sc_id = Request::input("sc_id");

        $sir_id = Request::input("sir_id");
        $item_id = Request::input("item_id");

        $base_um_id = Request::input("base_um_id");
        $base_qty = Request::input("base_qty");

        $issued_um_id = Request::input("issued_um_id");
        $issued_qty = Request::input("issued_qty");


        $data["item"] = Tbl_sir_cm_item::item()->where("s_cm_item_id",$sc_id)->first();
        $um_base_info = Tbl_unit_measurement_multi::where("multi_um_id",$data["item"]->item_measurement_id)->where("is_base",1)->first();
        $item_issued_um = Tbl_unit_measurement_multi::where("multi_um_id",$data["item"]->item_measurement_id)->where("is_base",0)->value("multi_id");
        $um_issued_info = UnitMeasurement::um_info($item_issued_um);


        $physical_count = ($base_qty * UnitMeasurement::um_qty($base_um_id)) + ($issued_qty * UnitMeasurement::um_qty($issued_um_id));
        // dd($physical_count);
        $qty = $data["item"]->sc_item_qty;
        $update["sc_infos"] = null;
        if($qty == $physical_count)
        {
            $update["sc_infos"] = 0;
        }
        elseif($physical_count > $qty)
        {
            //over
            $update["sc_infos"] = ($physical_count - $qty) * $data['item']->sc_item_price;   
        }
        elseif($physical_count < $qty) 
        {
            //short
            $update["sc_infos"] = ($qty - $physical_count) * -$data['item']->sc_item_price;
        }

        $update["sc_physical_count"] = $physical_count;
        $update["sc_is_updated"] = 1;
        Tbl_sir_cm_item::where("s_cm_item_id",$sc_id)->update($update);
        $data["status"] = "success-ilr-empties";
        $data["id"] = $sir_id;
        
        return json_encode($data);
    }
    public function update_count_submit()
    {
        $sir_id = Request::input("sir_id");
        $item_id = Request::input("item_id");
        $base_um_id = Request::input("base_um_id");
        $base_qty = Request::input("base_qty");
        $issued_um_id = Request::input("issued_um_id");
        $issued_qty = Request::input("issued_qty");

        $item_data = Tbl_item::where("item_id",$item_id)->first();

        $sir_info = Tbl_sir_item::where("sir_id",$sir_id)->where("item_id",$item_id)->first();
        $unit_qty = Tbl_unit_measurement_multi::where("multi_id",$sir_info->related_um_type)->value("unit_qty");

        $qt = UnitMeasurement::um_qty($sir_info->related_um_type);

        if($item_data != null)
        {
            if($item_data->item_type_id != 4)
            {                        
                $rem_qty = Purchasing_inventory_system::count_rem_qty($sir_id, $item_id);
                $sold_qty = Purchasing_inventory_system::count_sold_qty($sir_id, $item_id);
            }
            else
            {

                $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$item_id)->get();

                $total_bundle_qty = 0;
                $total_sold_bundle_qty = 0;
                $qty = [];
                $qtys = [];
                foreach ($bundle_item as $key_bundle => $value_bundle)
                {
                   $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty;

                   $issued_bundle_qty_item = $sir_info->item_qty * $bundle_qty;

                   $total_sold_bundle_qty = Tbl_sir_inventory::where("sir_item_id",$value_bundle->bundle_item_id)->where("inventory_sir_id",$sir_id)->where("sir_inventory_count","<",0)->sum("sir_inventory_count");
                   $rem_bundle_qty = ($issued_bundle_qty_item - abs($total_sold_bundle_qty)) / $bundle_qty;
                   $sold_bundle_qty = $base_qty - $rem_bundle_qty;
                }

                $rem_qty  = $rem_bundle_qty;
                $sold_qty = $sold_bundle_qty;
            }
        }
                    
        $remaining_qty = $rem_qty;

        $item_info = Tbl_item::where("item_id",$item_id)->first();
        $base_um_id = $item_info->item_measurement_id;
        $um_base_info = Tbl_unit_measurement_multi::where("multi_um_id",$base_um_id)->where("is_base",1)->first();
        $um_issued_info = UnitMeasurement::um_info($issued_um_id);

        $total_qty = 0;
        $base_pc_qty = 0;
        $issued_pc_qty = 0;

        if($um_base_info != null)
        {
            $base_pc_qty = $base_qty * $um_base_info->unit_qty;
        }
        if($um_issued_info != null)
        {
            $issued_pc_qty = $issued_qty * $um_issued_info->unit_qty;
        }
        else
        {
            $base_pc_qty = $base_qty;
        }

        $total_qty = $issued_pc_qty + $base_pc_qty;

        $update["infos"] = "";
        if($remaining_qty == $total_qty)
        {
            $update["infos"] = 0;
        }
        elseif($total_qty > $remaining_qty)
        {
            //over
            $update["infos"] = ($total_qty - $remaining_qty) * $sir_info->sir_item_price;   
        }
        elseif($total_qty < $remaining_qty) 
        {
            //short
            $update["infos"] = ($remaining_qty - $total_qty) * -$sir_info->sir_item_price;
        }

        $update["is_updated"] = 1;
        $update["physical_count"] = $total_qty;

        Tbl_sir_item::where("sir_id",$sir_id)->where("item_id",$item_id)->update($update);

        $data["status"] = "success-ilr";
        $data["id"] = $sir_id;
        return json_encode($data);

    }
    public function confirm_syncing($action)
    {
        $access = Utilities::checkAccess("pis-sir","sync");
        if($access != 0)
        {
            $data["action"] = "/member/pis/sir/sync_export";
            if($action == "import")
            {
                $data["action"] = "/member/pis/sir/sync_import";
            }
            return view("member.purchasing_inventory_system.sync_confirm",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function sync_import()
    {
        $data = Purchasing_inventory_system::open_sir_general();

        return json_encode($data);
    }

    public function sync_export()
    {
        $data = Purchasing_inventory_system::close_sir_general();

        return json_encode($data);
    }

    public function ilr_submit()
    {
        $sir_id = Request::input("sir_id");

        $check_item_if_updated = Tbl_sir_item::select_sir_item()->where("sir_id",$sir_id)->get();

        $data["status"] = null;
        $data["status_message"] = null;

        foreach ($check_item_if_updated as $key => $value) 
        {
            if($value->is_updated == 0)
            {
                $data["status"] = "error";
                $data["status_message"] .= "The item ".$value->item_name." is not updated from SIR Item<br>";
            }
        }

        $check_item = Tbl_sir_cm_item::item()->where("sc_sir_id",$sir_id)->get();

        foreach ($check_item as $keys => $values) 
        {
            if($values->sc_is_updated == 0)
            {
                $data["status"] = "error";
                $data["status_message"] .= "The item ".$values->item_name." is not updated from EMPTIES<br>";
            }
        }

        if($data["status"] == null)
        {
            $data = Purchasing_inventory_system::return_stock($sir_id);
            Purchasing_inventory_system::return_cm_item($sir_id);

            $ins["sir_id"] = $sir_id;
            $ins["report_data"] = serialize(Purchasing_inventory_system::get_report_data($sir_id));
            $ins["report_created"] = Carbon::now();

            Tbl_sir_sales_report::insert($ins);
        }

        $ilr = Purchasing_inventory_system::get_ilr_data($sir_id);
        AuditTrail::record_logs("Saved","pis_incoming_load_report",$sir_id,"",serialize($ilr));

        return json_encode($data);
    }
    public function ilr_pdf($id)
    {
        $data["sir"] = Purchasing_inventory_system::select_single_sir($this->user_info->shop_id,$id,'array');
        $data["_sir_item"] = Purchasing_inventory_system::select_sir_item($this->user_info->shop_id,$id,'array');

        $data["_returns"] = Tbl_sir_cm_item::item()->where("sc_sir_id",$id)->get();

        $data["type"] = "I.L.R";

        if($data["_sir_item"] != null)
        {
            foreach($data["_sir_item"] as $key => $value) 
            { 

                if($value->item_type_id != 4)
                {   
                    $rem_qty = Purchasing_inventory_system::count_rem_qty($id, $value->item_id);
                    $sold_qty = Purchasing_inventory_system::count_sold_qty($id, $value->item_id);

                    $um = Tbl_unit_measurement_multi::where("multi_id",$value->related_um_type)->first();

                    $data["_sir_item"][$key]->um_name = isset($um->multi_name) ? $um->multi_name : "";
                    $data["_sir_item"][$key]->um_abbrev = isset($um->multi_abbrev) ? $um->multi_abbrev : "PC";

                    $qt = UnitMeasurement::um_qty($value->related_um_type);
                        
                    $issued_qty = $value->item_qty * $qt;
                    $remaining_qty = $rem_qty;
                    $total_sold_qty = $sold_qty;
                    
                    $physical_ctr = $value->physical_count;
                    $rem = "";
                    $sold = "";
                    $physical_count = "";
                    $rem = UnitMeasurement::um_view($remaining_qty, $value->item_measurement_id, $value->related_um_type);
                    $sold = UnitMeasurement::um_view($total_sold_qty, $value->item_measurement_id, $value->related_um_type);
                    $physical_count = UnitMeasurement::um_view($value->physical_count, $value->item_measurement_id,$value->related_um_type);
                }
                else
                {
                    $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$value->item_id)->get();

                    $total_bundle_qty = 0;
                    $total_sold_bundle_qty = 0;
                    $qty = [];
                    $qtys = [];
                    foreach ($bundle_item as $key_bundle => $value_bundle)
                    {
                       $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty;

                       $issued_bundle_qty_item = (UnitMeasurement::um_qty($value->related_um_type) * $value->item_qty) * $bundle_qty;

                       $total_sold_bundle_qty = Tbl_sir_inventory::where("sir_item_id",$value_bundle->bundle_item_id)->where("inventory_sir_id",$id)->where("sir_inventory_count","<",0)->sum("sir_inventory_count");
                       $rem_bundle_qty = ($issued_bundle_qty_item - abs($total_sold_bundle_qty)) / $bundle_qty;
                       $sold_bundle_qty = $value->item_qty - $rem_bundle_qty;
                    }
                    $rem = UnitMeasurement::um_view(round($rem_bundle_qty), $value->item_measurement_id, $value->related_um_type);
                    $sold = UnitMeasurement::um_view(round($sold_bundle_qty), $value->item_measurement_id, $value->related_um_type);
                    $physical_count = UnitMeasurement::um_view($value->physical_count, $value->item_measurement_id,$value->related_um_type);
                    $total_sold_qty = $sold_bundle_qty;


                    $remaining_qty = $rem;
                    $physical_ctr = $value->physical_count;
                }
            
                $data["_sir_item"][$key]->quantity_sold = $total_sold_qty;
                $data["_sir_item"][$key]->remaining_qty = $rem;
                $data["_sir_item"][$key]->sold_qty = $sold;
                $data["_sir_item"][$key]->physical_count = $physical_count;

                $status = "";
                if($value->is_updated != 0)
                {
                    if($remaining_qty == $physical_ctr)
                    {
                        $status = "OK";
                    }
                    elseif($physical_ctr > $remaining_qty)
                    {
                        $status = "OVER ".(UnitMeasurement::um_view($physical_ctr - $remaining_qty,$value->item_measurement_id,$value->related_um_type));   
                    }
                    elseif($physical_ctr < $remaining_qty) 
                    {
                        $status = "SHORT ".(UnitMeasurement::um_view($remaining_qty - $physical_ctr,$value->item_measurement_id,$value->related_um_type));
                    }                    
                }
                $data["rem_total"][$key] = $remaining_qty." ".$physical_ctr;
                $data["_sir_item"][$key]->status = $status;

            }
        }
        $data["ctr_returns"] = count($data["_returns"]);
        if($data["_returns"] != null)
        {
            foreach ($data["_returns"] as $key_return => $value_return)
            {
                $item_um = Tbl_unit_measurement_multi::where("multi_um_id",$value_return->item_measurement_id)->where("is_base",0)->value("multi_id");

                $data["_returns"][$key_return]->item_count = UnitMeasurement::um_view($value_return->sc_item_qty,$value_return->item_measurement_id,$item_um); 
                $data["_returns"][$key_return]->item_physical_count = UnitMeasurement::um_view($value_return->sc_physical_count,$value_return->item_measurement_id,$item_um); 

                $original_qty = $value_return->sc_item_qty;
                $physical_qty = $value_return->sc_physical_count;
                $status = null;
                if($value_return->sc_is_updated != 0)
                {
                    if($original_qty == $physical_qty)
                    {
                        $status = "OK";
                    }
                    elseif($physical_qty > $original_qty)
                    {
                        $status = "OVER ".(UnitMeasurement::um_view($physical_qty - $original_qty,$value_return->item_measurement_id,$item_um));   
                    }
                    elseif($physical_qty < $original_qty) 
                    {
                        $status = "SHORT ".(UnitMeasurement::um_view($original_qty - $physical_qty,$value_return->item_measurement_id,$item_um));
                    }                 
                }

                $data["_returns"][$key_return]->sc_physical_count = UnitMeasurement::um_view($physical_qty,$value_return->item_measurement_id,$item_um); 
                $data["_returns"][$key_return]->status = $status;        
            }
        }
        $html = view('member.purchasing_inventory_system.ilr.ilr_pdf', $data);
        return Pdf_global::show_pdf($html);
    }
    public function ilr_confirm($sir_id, $action)
    {
        $data["action"] = $action;
        $data["sir_id"] = $sir_id;

        return view("member.purchasing_inventory_system.ilr.ilr_confirm",$data);
    }
    public function ilr($sir_id)
    {
        // processed-ilr
        $access = Utilities::checkAccess("pis-ilr","processed-ilr");
        if($access != 0)
        {
            $data["_truck"] = Tbl_truck::where("archived",0)->where("truck_shop_id",$this->user_info->shop_id)->get();
            $data["_employees"] = Tbl_employee::position()->where("position_code","sales_agent")->where("shop_id",$this->user_info->shop_id)->where("tbl_employee.archived",0)->get();
            $data["_item"] = Tbl_item::where("archived",0)->where("item_type_id",1)->get();

            $data["sir"] = Tbl_sir::where("sir_id",$sir_id)->where("shop_id",$this->user_info->shop_id)->where("sir_status",2)->where("archived",0)->first();
            $data["_sir_item"] = Tbl_sir_item::select_sir_item()->where("sir_id",$sir_id)->get();
            // dd($data["_sir_item"]);
            if($data["_sir_item"] != null)
            {
                foreach($data["_sir_item"] as $key => $value) 
                {   
                    $rem = "";
                    $sold = "";
                    $physical_count = "";
                    if($value->item_type_id != 4)
                    {
                        $rem_qty = Purchasing_inventory_system::count_rem_qty($sir_id, $value->item_id);
                        $sold_qty = Purchasing_inventory_system::count_sold_qty($sir_id, $value->item_id);

                        $um = Tbl_unit_measurement_multi::where("multi_id",$value->related_um_type)->first();

                        $data["_sir_item"][$key]->um_name = isset($um->multi_name) ? $um->multi_name : "";
                        $data["_sir_item"][$key]->um_abbrev = isset($um->multi_abbrev) ? $um->multi_abbrev : "PC";

                        $qt = UnitMeasurement::um_qty($value->related_um_type);

                        $issued_qty = $value->item_qty * $qt;

                        $remaining_qty = $rem_qty;
                        $total_sold_qty = $sold_qty;
                        
                        $physical_ctr = $value->physical_count;
                        $rem = UnitMeasurement::um_view($remaining_qty, $value->item_measurement_id, $value->related_um_type);
                        $sold = UnitMeasurement::um_view($total_sold_qty, $value->item_measurement_id, $value->related_um_type);
                        $physical_count = UnitMeasurement::um_view($value->physical_count, $value->item_measurement_id,$value->related_um_type);
                        
                        // $test[$key] = $remaining_qty." ".$rem." | ".$physical_ctr." ".$physical_count;
                    }
                    else
                    {
                        $bundle_item = Tbl_item_bundle::where("bundle_bundle_id",$value->item_id)->get();

                        $total_bundle_qty = 0;
                        $total_sold_bundle_qty = 0;
                        $qty = [];
                        $qtys = [];
                        foreach ($bundle_item as $key_bundle => $value_bundle)
                        {
                           $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id) * $value_bundle->bundle_qty;

                           $issued_bundle_qty_item = (UnitMeasurement::um_qty($value->related_um_type) * $value->item_qty) * $bundle_qty;

                           $total_sold_bundle_qty = Tbl_sir_inventory::where("sir_item_id",$value_bundle->bundle_item_id)->where("inventory_sir_id",$sir_id)->where("sir_inventory_count","<",0)->sum("sir_inventory_count");
                           $rem_bundle_qty = ($issued_bundle_qty_item - abs($total_sold_bundle_qty)) / $bundle_qty;
                           $sold_bundle_qty = $value->item_qty - $rem_bundle_qty;
                        }
                        $rem = UnitMeasurement::um_view(round($rem_bundle_qty), $value->item_measurement_id, $value->related_um_type);
                        $sold = UnitMeasurement::um_view(round($sold_bundle_qty), $value->item_measurement_id, $value->related_um_type);
                        $physical_count = UnitMeasurement::um_view($value->physical_count, $value->item_measurement_id,$value->related_um_type);


                        $remaining_qty = $rem_bundle_qty;
                        $physical_ctr = $value->physical_count;
                    }


                    $data["_sir_item"][$key]->remaining_qty = $rem;
                    $data["_sir_item"][$key]->sold_qty = $sold;
                    $data["_sir_item"][$key]->physical_count = $physical_count;


                        $status = "";
                        if($value->is_updated != 0)
                        {
                            if($remaining_qty == $physical_ctr)
                            {
                                $status = "OK";
                            }
                            elseif($physical_ctr > $remaining_qty)
                            {
                                $status = "OVER ".(UnitMeasurement::um_view($physical_ctr - $remaining_qty,$value->item_measurement_id,$value->related_um_type));   
                            }
                            elseif($physical_ctr < $remaining_qty) 
                            {
                                $status = "SHORT ".(UnitMeasurement::um_view($remaining_qty - $physical_ctr,$value->item_measurement_id,$value->related_um_type));
                            }                 
                        }
                        $data["rem_total"][$key] = $remaining_qty." ".$physical_ctr;
                        $data["_sir_item"][$key]->status = $status;

                }

                //arcylen
                $data["_returns"] = Tbl_sir_cm_item::item()->where("sc_sir_id",$sir_id)->get();
                $data["ctr_returns"] = count($data["_returns"]);
                if($data["ctr_returns"] != 0)
                {
                    foreach($data["_returns"] as $key_return => $value_return) 
                    {
                        $item_um = Tbl_unit_measurement_multi::where("multi_um_id",$value_return->item_measurement_id)->where("is_base",0)->value("multi_id");

                        $data["_returns"][$key_return]->item_count = UnitMeasurement::um_view($value_return->sc_item_qty,$value_return->item_measurement_id,$item_um); 
                        $data["_returns"][$key_return]->item_physical_count = UnitMeasurement::um_view($value_return->sc_physical_count,$value_return->item_measurement_id,$item_um); 

                        $original_qty = $value_return->sc_item_qty;
                        $physical_qty = $value_return->sc_physical_count;
                        $status = null;
                        if($value_return->sc_is_updated != 0)
                        {
                            if($original_qty == $physical_qty)
                            {
                                $status = "OK";
                            }
                            elseif($physical_qty > $original_qty)
                            {
                                $status = "OVER ".(UnitMeasurement::um_view($physical_qty - $original_qty,$value_return->item_measurement_id,$item_um));   
                            }
                            elseif($physical_qty < $original_qty) 
                            {
                                $status = "SHORT ".(UnitMeasurement::um_view($original_qty - $physical_qty,$value_return->item_measurement_id,$item_um));
                            }                 
                        }
                        $data["_returns"][$key_return]->status = $status;
                    }
                }

                return view("member.purchasing_inventory_system.ilr.ilr",$data);
            }
            else
            {
                return Redirect::to("/member/pis/sir");
            }
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function sir()
    {     
        if(Request::input("status") != null)
        {
            if(Request::input("status") == 'all')
            {
                $data["_sir"] = Purchasing_inventory_system::select_sir($this->user_info->shop_id,'array',Request::input("sir_id"));
            }
            else if(Request::input("status") == "reload")
            {
                $data["_sir"] = Purchasing_inventory_system::select_sir($this->user_info->shop_id,'array',Request::input("sir_id"),1);
            }
            else
            {
                $data["_sir"] = Purchasing_inventory_system::select_sir_status($this->user_info->shop_id,'array',Request::input("status"),0,Request::input("sir_id"),Request::input("is_sync"));
            }
        }
        else if(Request::input("archived") != null)
        {
                $data["_sir"] = Purchasing_inventory_system::select_sir_status($this->user_info->shop_id,'array',0,Request::input("archived"),Request::input("sir_id"),Request::input("is_sync"));
        }
        else
        {            
            $data["_sir"] = Purchasing_inventory_system::select_sir($this->user_info->shop_id,'array',Request::input("sir_id"));
        }
        return view("member.purchasing_inventory_system.sir",$data);
    }

    public function lof()
    {
        if(Request::input("status") != null)
        {
            if(Request::input("status") != 'all')
            {
                $data["_sir"] = Purchasing_inventory_system::select_lof_status($this->user_info->shop_id,'array',Request::input("status"),0,Request::input("sir_id"));
            }
            else
            {
                $data["_sir"] = Purchasing_inventory_system::select_lof($this->user_info->shop_id,'array',Request::input("sir_id"));
            }
        }
        else if(Request::input("archived") != null)
        {
            $data["_sir"] = Purchasing_inventory_system::select_lof_status($this->user_info->shop_id,'array',1,Request::input("archived"),Request::input("sir_id"));
        }
        else
        {            
            $data["_sir"] = Purchasing_inventory_system::select_lof($this->user_info->shop_id,'array',Request::input("sir_id"));
        }
        return view("member.purchasing_inventory_system.lof.lof",$data);
    }
    public function sir_list()
    {
        if(Request::input("status") != null)
        {
            if(Request::input("status") != 'all')
            {
                $lof_status = Request::input("lof_status");
                $sir_status = Request::input("sir_status");
                $ilr_status = Request::input("ilr_status");
                $sync = Request::input("sync");
                echo($lof_status." ".$sir_status." ".$ilr_status." ".$sync);
                $data["_sir"] = Purchasing_inventory_system::select_sir_list_status($lof_status,$sir_status,$ilr_status,$sync,Request::input("sir_id"));
            }
            else
            {
                $data["_sir"] = Purchasing_inventory_system::select_sir_list(Request::input("sir_id"));
            }
        }
        else
        {            
            $data["_sir"] = Purchasing_inventory_system::select_sir_list(Request::input("sir_id"));
        } 
        return view("member.purchasing_inventory_system.sir_list",$data);
    }
    public function create_sir()
    {
        $access = Utilities::checkAccess("pis-sir","add");
        if($access != 0)
        {
            $data["_truck"] = Tbl_truck::where("archived",0)->where("truck_shop_id",$this->user_info->shop_id)->get();
            $data["_employees"] = Tbl_employee::position()->where("position_code","sales_agent")->where("tbl_employee.archived",0)->where("shop_id",$this->user_info->shop_id)->get();

            $type = [1,4]; 
            $data["_item"] = Item::get_all_category_item($type);
            return view("member.purchasing_inventory_system.create_sir",$data);
        } 
        else
        {
            return $this->show_no_access(); 
        }
    }
    public function load_ilr()
    {        
       $data["count_sir"] = Purchasing_inventory_system::count_ilr($this->user_info->shop_id,'array',1,0);
       if(Request::input("status") != null)
        {
            if(Request::input("status") != 'all')
            {
                $data["_sir"] = Purchasing_inventory_system::select_ilr_status($this->user_info->shop_id,'array',Request::input("status"),Request::input("sir_id"));
            }
            else
            {
                $data["_sir"] = Purchasing_inventory_system::select_ilr($this->user_info->shop_id,'array',Request::input("sir_id"));
            }
        }
        else
        {            
            $data["_sir"] = Purchasing_inventory_system::select_ilr($this->user_info->shop_id,'array',Request::input("sir_id"));
        }

        return view("member.purchasing_inventory_system.ilr.list_ilr",$data);
    }
    public function ilr_view($sir_id)
    {
        $data["sir_id"] = $sir_id;
        $data["type"] = "Incoming Load Report";       

        return view("member.purchasing_inventory_system.ilr.ilr_view",$data);
    }
    public function view($id, $type)
    {
        $data["sir_id"] = $id;
        $data["type_code"] = $type; 
        $data["type"] = "Stock Issuance Report";
        if($type == "lof")
        {
            $data["type"] = "Load Out Form";
        }

        return view("member.purchasing_inventory_system.sir_view",$data);
    }
    public function view_pdf($id, $type_code)
    {
        $data["sir"] = Purchasing_inventory_system::select_single_sir($this->user_info->shop_id,$id,'array');
        $data["_sir_item"] = Purchasing_inventory_system::select_sir_item($this->user_info->shop_id,$id,'array');

        $data["type_code"] = $type_code; 
        $data["type"] = "S.I.R";
        if($type_code == "lof")
        {
            $data["type"] = "L.O.F";
        }
        // return view("member.purchasing_inventory_system.sir_pdf",$data);

        $html = view('member.purchasing_inventory_system.sir_pdf', $data);
        return Pdf_global::show_pdf($html);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_sir_submit()
    {
        $data["status"] = "";
        $data["status_message"] = "";

        $shop_id = $this->user_info->shop_id;
        $sales_agent_id = Request::input("sales_agent_id");
        $truck_id = Request::input("truck_id");
        $sir_date = datepicker_input(Request::input("sir_date"));
        //array
        $item_id = Request::input("item");
        $item_qty = Request::input("item_qty");
        $related_um_type = Request::input("related_um_type");
        
        $insert_sir["shop_id"] = $shop_id;
        $insert_sir["sales_agent_id"] = $sales_agent_id;
        $insert_sir["truck_id"] = $truck_id;
        $insert_sir["sir_warehouse_id"] = $this->current_warehouse->warehouse_id;
        $insert_sir["created_at"] = $sir_date;
        $insert_sir["lof_status"] = 1;

        $rule_sir["sales_agent_id"] = 'required';
        $rule_sir["truck_id"] = 'required';

        foreach ($item_id as $key => $value) 
        {
            if($value != "")
            {
                $qty = UnitMeasurement::um_qty($related_um_type[$key]);
                $items[$key]['id'] = $value;
                $items[$key]['quantity'] = str_replace(",","",$item_qty[$key]) * $qty;
            }

        }
        foreach ($item_id as $key_item => $value_item)
        {
            $type = Tbl_item::where("item_id",$value_item)->value("item_type_id");
            if($type == 4)
            {
                $bundle = Tbl_item_bundle::where("bundle_bundle_id",$value_item)->get();
                foreach ($bundle as $key_bundle => $value_bundle) 
                {
                    $qty = UnitMeasurement::um_qty($related_um_type[$key_item]);
                    $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id);
                    $_bundle[$key_bundle]['id'] = $value_bundle->bundle_item_id;
                    $_bundle[$key_bundle]['quantity'] = ($item_qty[$key_item] * $qty) * ($value_bundle->bundle_qty * $bundle_qty);

                    array_push($items, $_bundle[$key_bundle]);
                }
            }
        }  
        foreach ($items as $key_items => $value_items) 
        {
            $i = null;
            foreach ($item_id as $value_itemid) 
            {
                $type = Tbl_item::where("item_id",$value_itemid)->value("item_type_id");
                if($type == 4)
                {
                    if($value_itemid == $value_items['id'])
                    {
                        $i = "true";
                    }                    
                }
            }
            if($i != null)
            {
                unset($items[$key_items]);
            }
        }
        $result = array();
        foreach($items as $k => $v)
        {
            $id = $v['id'];
            $result[$id][] = $v['quantity'];
        }

        $new_item = array();
        foreach($result as $key1 => $value1) 
        {
            $new_item[$key1] = array('id' => $key1, 'quantity' => array_sum($value1));
        }

        $validator = Validator::make($insert_sir,$rule_sir);
        $warehouse_id = $this->current_warehouse->warehouse_id;
        $sir_id = 0;
        if($validator->fails())
        {
            $data["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }
        else
        {
            // $check_sales_agent = Tbl_sir::where("ilr_status",0)->where("sales_agent_id",$sales_agent_id)->count();
            // if($check_sales_agent == 0)
            // {
                if($item_id != null && $item_qty != null)
                {
                    foreach ($item_id as $key => $value) 
                    {
                        if($value != "")
                        {
                            $insert_sir_item[$key]["item_id"] = $value;
                            $insert_sir_item[$key]["item_qty"] = str_replace(",", "", $item_qty[$key]);
                            $insert_sir_item[$key]["related_um_type"] = $related_um_type[$key];
                            // $insert_sir_item[$key]["um_qty"] = $um_qty[$key];

                            $rule[$key]["item_id"]    = "required";
                            $rule[$key]["item_qty"]  = "required|numeric|min:1";
                            // $rule[$key]["related_um_type"]      = "required";
                            // $rule[$key]["um_qty"] = "required|numeric|min:1";

                            $validator2[$key] = Validator::make($insert_sir_item[$key], $rule[$key]);
                            if($validator2[$key]->fails())
                            {
                                $data["status"] = "error";
                                foreach ($validator2[$key]->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                                {
                                    $data["status_message"] .= $message;
                                }
                            }
                        }
                    }
                }
                else
                {
                    $data["status"] = "error";
                    $data["status_message"] = "Please insert items";
                }                
            // }
            // else
            // {
            //     $data["status"] = "error";
            //     $data["status_message"] = "You cannot issue Load out form. The sales agent has an unprocessed SIR";
            // }
            if($new_item)
            {
                foreach ($new_item as $key => $value) 
                {
                   $inventory_consume_product[$key]["product_id"] = $value["id"];
                   $inventory_consume_product[$key]["quantity"] = $value["quantity"];

                   $count_on_hand = Tbl_warehouse_inventory::check_inventory_single($warehouse_id, $value["id"])->value('inventory_count');
                    if($count_on_hand == null)
                    {
                        $count_on_hand = 0;   
                    }
                    if($value['quantity'] > 0 && $count_on_hand > 0 && $count_on_hand >= $value['quantity'])
                    {

                    }
                    else
                    {
                        $item_name = Tbl_item::where("item_id",$value["id"])->value("item_name");

                        $data["status"] = "error";
                        $data["status_message"] .= "<li style='list-style:none'>The quantity of item ".$item_name." is not enough to consume </li>";
                    }
                }                
            }
            if($data["status"] == "")
            {
                $sir_id = Tbl_sir::insertGetId($insert_sir);
                $remarks = "SIR consume";
                // $warehouse_id = Tbl_warehouse::where("warehouse_shop_id",$this->user_info->shop_id)->where("main_warehouse",1)->value("warehouse_id");
                $warehouse_id = Purchasing_inventory_system::get_warehouse_based_sir($sir_id);
                $transaction_type = "sir";
                $transaction_id = $sir_id;

                $data = Warehouse::inventory_consume($warehouse_id, $remarks, $inventory_consume_product,$consumer_id = 0, $consume_cause = '', $return = 'array', $transaction_type, $transaction_id);
            }
            $insert_sir_item = null;
            if($data["status"] == "success")
            {                
               if($item_id != null && $item_qty != null)
                {
                    foreach ($item_id as $key => $value) 
                    {
                        if($value != "")
                        {
                            $chck = Tbl_sir_item::where("sir_id",$sir_id)->where("item_id",$value)->where("related_um_type",$related_um_type[$key])->first();

                            if($chck == null)
                            {
                                $insert_sir_item["sir_id"] = $sir_id;
                                $insert_sir_item["item_id"] = $value;
                                $insert_sir_item["item_qty"] = str_replace(",","",$item_qty[$key]);
                                $insert_sir_item["sir_item_price"] = Purchasing_inventory_system::get_item_price($value);
                                $insert_sir_item["related_um_type"] = $related_um_type[$key];
                                $qty = UnitMeasurement::um_qty($related_um_type[$key]);
                                $related_um_qty = $qty;
                                $insert_sir_item["um_qty"] = $related_um_qty;

                                Tbl_sir_item::insert($insert_sir_item);
                            }
                            else
                            {
                                $insert_sir_item["item_qty"] = $chck->item_qty + str_replace(",","",$item_qty[$key]);

                                Tbl_sir_item::where("sir_id",$sir_id)->where("item_id",$value)->where("related_um_type",$related_um_type[$key])->update($insert_sir_item);   
                            }
                            
                            //record truck inventory                            
                            $item["item_id"] = $value;
                            $item["qty"] = str_replace(",","",$item_qty[$key]) * UnitMeasurement::um_qty($related_um_type[$key]);
                            Purchasing_inventory_system::insert_sir_inventory($sir_id,$item,"sir",$sir_id);

                        }
                    }                    

                }
                $data["status"] = "success-lof";
                // Session::put("sir_id",$sir_id);
                $data["sir_id"] = $sir_id;

                $sir_data = Purchasing_inventory_system::get_sir_data($sir_id);
                AuditTrail::record_logs("Added","pis_load_out_form",$sir_id,"",serialize($sir_data));

            }

        }            
        

        return json_encode($data);

    }

    public function edit_sir_submit()
    {
        $data["status"] = "";
        $data["status_message"] = "";

        $sir_id = Request::input("sir_id");
        $shop_id = $this->user_info->shop_id;

        $old_sir_data = Purchasing_inventory_system::get_sir_data($sir_id);

        $sales_agent_id = Request::input("sales_agent_id");
        $truck_id = Request::input("truck_id");
        $sir_date = datepicker_input(Request::input("sir_date"));

        $item_id = Request::input("item");
        $item_qty = Request::input("item_qty");
        $related_um_type = Request::input("related_um_type");
        // $um_qty = Request::input("um_qty");

        //if related um_type == 0 then um_qty == 1
        // if related um_type != 0 then um_qty
        $insert_sir["shop_id"] = $shop_id;
        $insert_sir["sales_agent_id"] = $sales_agent_id;
        $insert_sir["truck_id"] = $truck_id;
        $insert_sir["created_at"] = $sir_date;
        $insert_sir["lof_status"] = 1;

        $rule_sir["sales_agent_id"] = 'required';
        $rule_sir["truck_id"] = 'required';

        foreach ($item_id as $key => $value) 
        {
            if($value != "")
            {
                $qty = UnitMeasurement::um_qty($related_um_type[$key]);
                $items[$key]['id'] = $value;
                $items[$key]['quantity'] = $item_qty[$key] * $qty;                 
            }
        }

        foreach ($item_id as $key_item => $value_item)
        {
            $type = Tbl_item::where("item_id",$value_item)->value("item_type_id");
            if($type == 4)
            {
                $bundle = Tbl_item_bundle::where("bundle_bundle_id",$value_item)->get();
                foreach ($bundle as $key_bundle => $value_bundle) 
                {
                    $qty = UnitMeasurement::um_qty($related_um_type[$key_item]);
                    $bundle_qty = UnitMeasurement::um_qty($value_bundle->bundle_um_id);
                    $_bundle[$key_bundle]['id'] = $value_bundle->bundle_item_id;
                    $_bundle[$key_bundle]['quantity'] = ($item_qty[$key_item] * $qty) * ($value_bundle->bundle_qty * $bundle_qty);

                    array_push($items, $_bundle[$key_bundle]);
                }
            }
        }  
        foreach ($items as $key_items => $value_items) 
        {
            $i = null;
            foreach ($item_id as $value_itemid) 
            {
                $type = Tbl_item::where("item_id",$value_itemid)->value("item_type_id");
                if($type == 4)
                {
                    if($value_itemid == $value_items['id'])
                    {
                        $i = "true";
                    }                    
                }
            }
            if($i != null)
            {
                unset($items[$key_items]);
            }
        }
        $result = array();
        foreach($items as $k => $v)
        {
            $id = $v['id'];
            $result[$id][] = $v['quantity'];
        }

        $new_item = array();
        foreach($result as $key1 => $value1) 
        {
            $new_item[$key1] = array('id' => $key1, 'quantity' => array_sum($value1));
        }

        $validator = Validator::make($insert_sir,$rule_sir);

        $warehouse_id = Purchasing_inventory_system::get_warehouse_based_sir($sir_id);
        if($validator->fails())
        {
            $data["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }
        else
        {
            if($item_id != null && $item_qty != null && $related_um_type != null)
            {
                $inventory_consume_product = null;
                foreach ($item_id as $key => $value) 
                {
                    if($value != "")
                    {
                        if(isset($related_um_type[$key]))
                        {
                             $related_um_type[$key] = $related_um_type[$key];  
                        }
                        else
                        {
                            $related_um_type[$key] = 0;
                        }
                        $insert_sir_item[$key]["item_id"] = $value;
                        $insert_sir_item[$key]["item_qty"] = str_replace(",","", $item_qty[$key]);
                        $insert_sir_item[$key]["related_um_type"] = $related_um_type[$key] ;
                        // $insert_sir_item[$key]["um_qty"] = $um_qty[$key];

                        $rule[$key]["item_id"]    = "required";
                        $rule[$key]["item_qty"]  = "required|numeric|min:1";
                        // $rule[$key]["um_qty"] = "required|numeric|min:1";

                        $validator2[$key] = Validator::make($insert_sir_item[$key], $rule[$key]);
                        if($validator2[$key]->fails())
                        {
                            $data["status"] = "error";
                            foreach ($validator2[$key]->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
                            {
                                $data["status_message"] .= $message;
                            }
                        }
                    }
                }
            }
            else
            {
                $data["status"] = "error";
                $data["status_message"] = "Please insert items";
            }
            foreach ($new_item as $key => $value) 
            {
               $inventory_update_item[$key]["product_id"] = $value["id"];
               $inventory_update_item[$key]["quantity"] = $value["quantity"];

               $count_on_hand = Tbl_warehouse_inventory::check_inventory_single($warehouse_id, $value["id"])->value('inventory_count');
                if($count_on_hand == null)
                {
                    $count_on_hand = 0;   
                }
                 if($value['quantity'] > 0 && $count_on_hand > 0 && $count_on_hand >= $value['quantity'])
                {

                }
                else
                {
                     $item_name = Tbl_item::where("item_id",$value["id"])->value("item_name");

                    $data["status"] = "error";
                    $data["status_message"] .= "<li style='list-style:none'>The quantity of item ".$item_name." is not enough to consume </li>";
                }
            }
            $insert_sir_item = null;

            if($data["status"] == "")
            {
                // $transaction_id = $sir_id;
                // $transaction_type = "sir";
                // $data = Warehouse::inventory_update($transaction_id, $transaction_type, $inventory_update_item, $return = 'array');
                $remarks = "SIR Edit consume";
                // $warehouse_id = Tbl_warehouse::where("warehouse_shop_id",$this->user_info->shop_id)->where("main_warehouse",1)->value("warehouse_id");
                $warehouse_id = Purchasing_inventory_system::get_warehouse_based_sir($sir_id);
                $transaction_type = "sir";
                $transaction_id = $sir_id;

                $data = Warehouse::inventory_consume($warehouse_id, $remarks, $inventory_update_item,$consumer_id = 0, $consume_cause = '', $return = 'array', $transaction_type, $transaction_id);   
            }
            if($data["status"] == "success")
            {
                Tbl_sir::where("sir_id",$sir_id)->update($insert_sir);
                
               if($item_id != null && $item_qty != null && $related_um_type != null )
                {
                    Tbl_sir_item::where("sir_id",$sir_id)->delete();
                    Tbl_sir_inventory::where("inventory_sir_id",$sir_id)->delete();
                    foreach ($item_id as $key => $value) 
                    {
                        if($value != "")
                        {
                            $chck = Tbl_sir_item::where("sir_id",$sir_id)->where("item_id",$value)->where("related_um_type",$related_um_type[$key])->first();

                            if($chck == null)
                            {
                                $insert_sir_item["sir_id"] = $sir_id;
                                $insert_sir_item["item_id"] = $value;
                                $insert_sir_item["item_qty"] = str_replace(",","",$item_qty[$key]);
                                $insert_sir_item["sir_item_price"] = Purchasing_inventory_system::get_item_price($value);
                                $insert_sir_item["related_um_type"] = $related_um_type[$key];
                                $qty = UnitMeasurement::um_qty($related_um_type[$key]);

                                $related_um_qty = $qty;
                                $insert_sir_item["um_qty"] = $related_um_qty;

                                Tbl_sir_item::insert($insert_sir_item);                                
                            }
                            else
                            {
                                $insert_sir_item["item_qty"] = $chck->item_qty + str_replace(",","",$item_qty[$key]);

                                Tbl_sir_item::where("sir_id",$sir_id)->where("item_id",$value)->where("related_um_type",$related_um_type[$key])->update($insert_sir_item);   
                            }

                            //record truck inventory                            
                            $item["item_id"] = $value;
                            $item["qty"] = str_replace(",","",$item_qty[$key]) * UnitMeasurement::um_qty($related_um_type[$key]);
                            Purchasing_inventory_system::insert_sir_inventory($sir_id,$item,"sir",$sir_id);
                        }
                    }
                }
                $data["status"] = "success-lof";
                $data["sir_id"] = $sir_id;

                $new_sir_data = Purchasing_inventory_system::get_sir_data($sir_id);
                AuditTrail::record_logs("Edited","pis_load_out_form",$sir_id,serialize($old_sir_data),serialize($new_sir_data));
            }

        }

        return json_encode($data);

    }
    public function edit_sir($sir_id)
    {
        $access = Utilities::checkAccess("pis-sir","edit");
        if($access != 0)
        {
            $data["_truck"] = Tbl_truck::where("archived",0)->where("truck_shop_id",$this->user_info->shop_id)->get();
            $data["_employees"] = Tbl_employee::position()->where("position_code","sales_agent")->where("shop_id",$this->user_info->shop_id)->where("tbl_employee.archived",0)->get();

            
            $type = [1,4]; 
            $data["_item"] = Item::get_all_category_item($type);

            $data["sir"] = Tbl_sir::where("sir_id",$sir_id)->where("shop_id",$this->user_info->shop_id)->where("archived",0)->first();
            $data["_sir_item"] = Tbl_sir_item::select_sir_item()->where("sir_id",$sir_id)->get();

            if($data["_sir_item"] != null)
            {
                foreach ($data["_sir_item"] as $key => $value) 
                {
                    if($value->item_type_id == 4)
                    {
                        $data["_sir_item"][$key]->item_price = Item::get_item_bundle_price($value->item_id);
                    }
                    $um = UnitMeasurement::select_um_array($value->related_um_type, 'array');
                     
                    $data["_sir_item"][$key]->um_list = $um;
                }

                return view("member.purchasing_inventory_system.sir_edit",$data);
            }
            else
            {
                return Redirect::to("/member/pis/sir");
            }
        }        
        else
        {           
            return $this->show_no_access(); 
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function archived_sir($id, $action)
    {
        $access = Utilities::checkAccess("pis-sir","archived");
        if($access != 0)
        {
            $data["sir_id"] = $id;
            $data["action"] = $action;

            return view("member.purchasing_inventory_system.sir_confirm",$data);
        }
        else
        {
            return $this->show_no_access_modal(); 
        }
    }
    public function open_sir($id, $action)
    {
        $access = Utilities::checkAccess("pis-sir","open-sir");
        if($access != 0)
        {
            $data["sir_id"] = $id;
            $data["action"] = $action;

            return view("member.purchasing_inventory_system.sir_confirm",$data);
        }
        else
        {
            return $this->show_no_access_modal(); 
        }
    }
    public function archived_sir_submit()
    {
        $id = Request::input("sir_id");
        $action = Request::input("action");

        if($action == "archived")
        {
            $update["archived"] = 1;
            $sir_data = Purchasing_inventory_system::get_sir_data($id);
            AuditTrail::record_logs("Archived","pis_load_out_form",$id,"",serialize($sir_data));
        }
        else if($action == "open")
        {
            $update["sir_status"] = 1;
            $update["is_sync"] = 1; 
            
            $sir_data = Purchasing_inventory_system::get_sir_data($id);
            AuditTrail::record_logs("Open","pis_stock_issuance_report",$id,"",serialize($sir_data));
        }
        else if($action == "close")
        {
            $update["sir_status"] = 2;

            $sir_data = Purchasing_inventory_system::get_sir_data($id);
            AuditTrail::record_logs("Close","pis_stock_issuance_report",$id,"",serialize($sir_data));
        }
        else
        {        
            $update["archived"] = 0;
            $sir_data = Purchasing_inventory_system::get_sir_data($id);
            AuditTrail::record_logs("Restore","pis_load_out_form",$id,"",serialize($sir_data));
        }

        Tbl_sir::where("sir_id",$id)->update($update);

        $data["status"] = "success";

        return json_encode($data);
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
    public function edit($id)
    {
        //
    }

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
