<?php

namespace App\Http\Controllers\Member;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_user_warehouse_access;
use App\Models\Tbl_item;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_settings;
use Redirect;

use App\Globals\Warehouse2;
use App\Globals\Warehouse;
use App\Globals\Utilities;
use App\Globals\Vendor;
use App\Globals\Pdf_global;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Session;
use App\Globals\Item;
use App\Globals\AuditTrail;
use Validator;
use Excel;
use DB;
class WarehouseControllerV2 extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $data['page'] = "Warehouse V2";
       
        $access = Utilities::checkAccess('warehouse-inventory', 'access_page');
        if($access == 1)
        {  
            return view('member.warehousev2.list_warehouse',$data);
        }
        else
        {
            return $this->show_no_access();
        }
    }
    public function getLoadBinWarehouse()
    {   
        $current_warehouse = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data["_bin_warehouse"] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $current_warehouse, Request::input('warehouse_id'));

        //dd($data["_bin_warehouse"]);
        return view("member.warehousev2.load_sub_warehouse_v2_select",$data);
    }
    public function getLoadWarehouse()
    {
        $archived = 0;
        if(Request::input("tab_type"))
        {
            $archived = Request::input("tab_type");
        }
        $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id, null, $archived,Request::input("search_keyword"));
        $data['_warehouse_list'] = Warehouse2::load_warehouse_list($this->user_info->shop_id, $this->user_info->user_id,0,0, $archived, Request::input("search_keyword"));
        return view("member.warehousev2.warehouse_table", $data);
    }
    public function getView(Request $request)
    {
        dd("Under maintenance");
    }
    public function getEdit($id)
    {
    
        $access = Utilities::checkAccess('warehouse-inventory', 'edit');
        if($access == 1)
        { 
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$id)->first();
            if(!$check_if_owned)
            {
                return $this->show_no_access_modal();
            }
            $data["warehouse"] = Tbl_warehouse::where("warehouse_id",$id)->first();
            
            $data['_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, 0, $data['warehouse']->warehouse_parent_id, $id);
            return view("member.warehousev2.edit_warehouse",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function postEditSubmit()
    {     
        $warehouse_id  = Request::input("warehouse_id");

        $old_data = AuditTrail::get_table_data("tbl_warehouse","warehouse_id",$warehouse_id);

        $up_warehouse["warehouse_name"] = Request::input("warehouse_name");
        $up_warehouse["warehouse_address"] = Request::input("warehouse_address");
        $up_warehouse["warehouse_parent_id"] = Request::input("warehouse_parent_id") != null ? Request::input("warehouse_parent_id") : 0;

        if($up_warehouse['warehouse_parent_id'])
        {            
            $up_warehouse["warehouse_level"] = Tbl_warehouse::where('warehouse_id', $up_warehouse['warehouse_parent_id'])->value('warehouse_level');
            $up_warehouse["warehouse_level"]++;
        }

        Tbl_warehouse::where("warehouse_id",Request::input("warehouse_id"))->update($up_warehouse);

        $data['status'] = 'success';
        $data['call_function'] = 'success_warehouse';

        if($data['status'] == 'success')
        {
            $warehouse_data = AuditTrail::get_table_data("tbl_warehouse","warehouse_id",$warehouse_id);
            AuditTrail::record_logs("Edited","warehouse",$warehouse_id,serialize($old_data),serialize($warehouse_data));
        }
        return json_encode($data);
    }
    public function getAdd()
    {
        $data['$access'] = Utilities::checkAccess('warehouse-inventory', 'add');
        if($data['$access'] == 1)
        { 
           $bin = Request::input("bin");
           $data['bin'] = $bin;
           $parent_warehouse = 0;
           if($bin)
           {
                $parent_warehouse = Warehouse2::get_current_warehouse($this->user_info->shop_id);
           }
           $data['_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $parent_warehouse, $parent_warehouse, 0, $parent_warehouse);
           return view("member.warehousev2.add_warehouse", $data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function postAddSubmit()
    {       
        //INSERT TO tbl_warehouse
        $ins_warehouse["warehouse_name"]    = Request::input("warehouse_name");
        $ins_warehouse["warehouse_address"] = Request::input("warehouse_address");
        $ins_warehouse["warehouse_parent_id"] = Request::input("warehouse_parent_id") != null ? Request::input("warehouse_parent_id") : 0;
        $ins_warehouse["warehouse_shop_id"] = $this->user_info->shop_id;
        $ins_warehouse["warehouse_created"] = Carbon::now();

        if($ins_warehouse['warehouse_parent_id'])
        {            
            $ins_warehouse["warehouse_level"] = Tbl_warehouse::where('warehouse_id', $ins_warehouse['warehouse_parent_id'])->value('warehouse_level');
            $ins_warehouse["warehouse_level"]++;
        }
        if(Request::input("bin") && !$ins_warehouse["warehouse_parent_id"])
        {
            $data['status'] = 'error';
            $data['status_message'] = 'Please select Bin or Sub Warehouse';
        }
        else
        {
            $id = Tbl_warehouse::insertGetId($ins_warehouse);

            Warehouse::insert_access($id);

            $data['status'] = 'success';

            if($data['status'] == 'success')
            {

                $data['warehouse_id'] = $id;
                $data['call_function'] = 'success_warehouse';
                /*$w_data = AuditTrail::get_table_data("tbl_warehouse","warehouse_id",$id);
                AuditTrail::record_logs("Added","warehouse",$id,"",serialize($w_data));*/
                $warehouse_data = AuditTrail::get_table_data("tbl_warehouse","warehouse_id",$id);
                AuditTrail::record_logs("Added","warehouse",$id,"",serialize($warehouse_data));
            }
        }


        return json_encode($data);
    }

    public function getRefill()
    {
        $access = Utilities::checkAccess('warehouse-inventory', 'refill');
        if($access == 1)
        { 
            $id = Request::input("warehouse_id");
            $check_if_owned = Tbl_user_warehouse_access::where("user_id",$this->user_info->user_id)->where("warehouse_id",$id)->first();
            if(!$check_if_owned)
            {
                return $this->show_no_access_modal();
            }

            $data["warehouse"] = Tbl_warehouse::where("warehouse_id",$id)->first();
            $data["_vendor"]    = Vendor::getAllVendor('active');
            //$data["_item"] = Warehouse::select_item_warehouse_single($id,'array');
            $data['_item']  = Item::get_all_category_item([1,5]);
            //dd($data['_item']);
            return view("member.warehousev2.refill_warehouse",$data);

        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    
    public function postRefillSubmit()
    {
        $shop_id        = $this->user_info->shop_id;
        $warehouse_id   = Request::input("warehouse_id");

        $remarks        = Request::input("remarks");
        $reference_name = Request::input("reference_name") == "other" ? "other" : "vendor";
        $reference_id   = Request::input("reference_name") == "other" ? 0 : Request::input("reference_name");
        
        $item_quantity = Request::input("item_quantity");
        $item_id = Request::input("item_id");

        $_item = null;
        foreach ($item_id as $key => $value) 
        {
            if($value)
            {
                $_item[$key]['item_id'] = $value;
                $_item[$key]['quantity'] = str_replace(",","",$item_quantity[$key]); 
                $_item[$key]['remarks'] = Request::input("remarks");
            }
        }

        $data = Warehouse2::refill_bulk($shop_id, $warehouse_id, $reference_name, $reference_id, $remarks, $_item);
        
        $data['status'] = 'success';
        $data['call_function'] = 'success_refill_warehouse';

       /*
        $return = 0;
        if(is_numeric($data))
        {
            $return['status'] = 'success';
            $return['call_function'] = 'success_refill_warehouse';
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $data;

        }
        return json_encode($return);*/
        
        return json_encode($data);
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
    public function getArchivedRestore()
    {
        $type = Request::input("ty");
        $id = Request::input("d");

        $data['action'] = "/member/item/v2/warehouse/archived-restore-submit";
        $data['id'] = $id;
        $data['val'] = $type;
        $data['type'] = "archived";
        $data['remaining_qty'] = Warehouse2::get_qty_warehouse($this->user_info->shop_id, $id);
        if($type == 0)
        {
            $data['type'] = "restore";
            $data['remaining_qty'] = "";
        }

        return view("member.warehousev2.archived_restore_warehouse", $data);
    }
    public function postArchivedRestoreSubmit()
    {
        if(Request::input("warehouse_id"))
        {
            $rem_qty = Warehouse2::get_qty_warehouse($this->user_info->shop_id, Request::input("warehouse_id"));
            if(!$rem_qty || $rem_qty < 0)
            {
                $update['archived'] = Request::input("val");
                Tbl_warehouse::where("warehouse_id", Request::input("warehouse_id"))->update($update);
                $return['status'] = "success";
                $return['call_function'] = "success_warehouse";
            }
            else
            {
                $return['status'] = "error";
                $return['status_message'] = "There's still <strong>".number_format($rem_qty)."</strong> quantity remaining in this warehouse. Kindly empty before archiving.";                
            }
        }
        else
        {
            $return['status'] = "error";
            $return['status_message'] = "Reload the page and please try again later";
        }

        return json_encode($return);
    }
}
