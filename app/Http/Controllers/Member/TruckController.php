<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_truck;
use App\Models\Tbl_warehouse;
use App\Globals\Utilities;
use App\Globals\AuditTrail;
use Validator;
use Carbon\Carbon;
class TruckController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["_truck"] = Tbl_truck::where("archived",0)->where("truck_shop_id",$this->user_info->shop_id)->get();
        foreach ($data["_truck"] as $key => $value) 
        {
            $data["_truck"][$key]->warehouse_name = "No Warehouse";
            if($value->warehouse_id != 0)
            {              
                $data["_truck"][$key]->warehouse_name = Tbl_warehouse::where("warehouse_id",$value->warehouse_id)->value("warehouse_name");
            }
        }

        $data["_truck_archived"] = Tbl_truck::where("archived",1)->where("truck_shop_id",$this->user_info->shop_id)->get();
        foreach ($data["_truck_archived"] as $key => $value) 
        {
            $data["_truck_archived"][$key]->warehouse_name = "No Warehouse";
            if($value->warehouse_id != 0)
            {              
                $data["_truck_archived"][$key]->warehouse_name = Tbl_warehouse::where("warehouse_id",$value->warehouse_id)->value("warehouse_name");
            }
        }

        return view("member.purchasing_inventory_system.truck.truck_list",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $access = Utilities::checkAccess("pis-truck","add");
        if($access != 0)
        {
           $data["_warehouse"] = Tbl_warehouse::where("archived",0)->where("warehouse_shop_id",$this->user_info->shop_id)->get();

            return view("member.purchasing_inventory_system.truck.truck_add",$data);  
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }

    public function add_submit()
    {
        $data["status"] = null;
        $data["status_message"] = null;
        $truck_model = Request::input("truck_model");
        $truck_kilogram = str_replace(',', "", Request::input("truck_kilogram"));
        $truck_plate_number = Request::input("truck_plate_number");
        $truck_warehouse = Request::input("truck_warehouse");

        $insert["truck_model"] = $truck_model;
        $insert["truck_kilogram"] = $truck_kilogram;
        $insert["plate_number"] = $truck_plate_number;
        $insert["warehouse_id"] = $truck_warehouse;
        $insert["truck_shop_id"] = $this->user_info->shop_id;
        $insert["created_at"] = Carbon::now();

/*        $rule["truck_model"] = "required";*/
        $rule["truck_kilogram"] = "numeric";
        $rule["plate_number"] = "required|unique:tbl_truck,plate_number";

        $validator = Validator::make($insert, $rule);

        if($validator->fails())
        {
            $data["status"] = "error";
            foreach ($validator->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }
        if($data["status"] == null)
        {
            $data["status"] = "success";
            $data["id"] = Tbl_truck::insertGetId($insert);
            $data["type"] = "truck";

            $truck = AuditTrail::get_table_data("tbl_truck","truck_id", $data["id"]);
            AuditTrail::record_logs("Added","truck", $data["id"],"",serialize($truck));
        }

        return json_encode($data);
    }

    public function edit($id)
    {
         $access = Utilities::checkAccess("pis-truck","edit");
        if($access != 0)
        {
           $data["edit_truck"] = Tbl_truck::where("truck_id",$id)->where("truck_shop_id",$this->user_info->shop_id)->first();

           $data["_warehouse"] = Tbl_warehouse::where("archived",0)->where("warehouse_shop_id",$this->user_info->shop_id)->get();

            return view("member.purchasing_inventory_system.truck.truck_edit",$data);  
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function edit_submit()
    {
        $id = Request::input("truck_id");

        $old_data = AuditTrail::get_table_data("tbl_truck","truck_id", $id);

        $data["status"] = null;
        $data["status_message"] = null;

        $truck_model = Request::input("truck_model");
        $truck_kilogram = str_replace(',', "", Request::input("truck_kilogram"));
        $truck_plate_number = Request::input("truck_plate_number");
        $truck_warehouse = Request::input("truck_warehouse");

        $update["truck_model"] = $truck_model;
        $update["truck_kilogram"] = $truck_kilogram;
        $update["plate_number"] = $truck_plate_number;
        $update["warehouse_id"] = $truck_warehouse;
        $update["created_at"] = Carbon::now();

        /*$rule["truck_model"] = "required";*/
        $rule["truck_kilogram"] = "numeric";
        $rule["plate_number"] = "required|unique:tbl_truck,plate_number,".$id.",truck_id";

        $validation = Validator::make($update, $rule);

        if($validation->fails())
        {
             $data["status"] = "error";
            foreach ($validation->messages()->all('<li style="list-style:none">:message</li>') as $keys => $message)
            {
                $data["status_message"] .= $message;
            }
        }
        if($data["status"] == null)
        {
            $data["status"] = "success";
            Tbl_truck::where("truck_id",$id)->update($update);

            $truck = AuditTrail::get_table_data("tbl_truck","truck_id", $id);
            AuditTrail::record_logs("Edited","truck", $id,serialize($old_data),serialize($truck));
        }

        return json_encode($data);
    }
    public function archived($id,$action)
    {
        $access = Utilities::checkAccess("pis-truck","archived");
        if($access != 0)
        {
            $data["truck_id"] = $id;
            $data["truck_info"] = Tbl_truck::where("truck_id",$id)->first();
            $data["action"] = $action;

            return view("member.purchasing_inventory_system.truck.confirm",$data);
        }
        else
        {
            return $this->show_no_access_modal();
        }
    }
    public function archived_submit()
    {
        $id = Request::input("truck_id");
        $action = Request::input("action");

        $update["archived"] = 0;
        if($action == "archived")
        {
            $update["archived"] = 1;
        }

        Tbl_truck::where("truck_id",$id)->update($update);

        $truck = AuditTrail::get_table_data("tbl_truck","truck_id", $id);
        AuditTrail::record_logs($action,"truck", $id,"",serialize($truck));

        $data["status"] = "success";

        return json_encode($data);
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
