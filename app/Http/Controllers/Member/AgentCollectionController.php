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
use App\Models\Tbl_customer_invoice_line;
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

class AgentCollectionController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $status = Request::input("status");
        if($status == 'all' || $status == "")
        {
            $data["_sir"] = Tbl_sir::saleagent()->where("tbl_sir.shop_id",$this->user_info->shop_id)->whereIn("ilr_status",[1,2])->get();
            foreach ($data["_sir"] as $key => $value) 
            {
                $data["_sir"][$key]->total_collectibles = currency("Php",Purchasing_inventory_system::get_sir_total_amount($value->sir_id));
                $data["_sir"][$key]->total_collection = currency("Php",$value->agent_collection);
                $data["_sir"][$key]->loss_over = $value->agent_collection - Purchasing_inventory_system::get_sir_total_amount($value->sir_id);
            }
        }
        else
        {
           $data["_sir"] = Tbl_sir::saleagent()->where("tbl_sir.shop_id",$this->user_info->shop_id)->where("ilr_status",$status)->get();
            foreach ($data["_sir"] as $key => $value) 
            {
                $data["_sir"][$key]->total_collectibles = currency("Php",Purchasing_inventory_system::get_sir_total_amount($value->sir_id));
                $data["_sir"][$key]->total_collection = currency("Php",$value->agent_collection);
                $data["_sir"][$key]->loss_over = $value->agent_collection - Purchasing_inventory_system::get_sir_total_amount($value->sir_id);
            } 
        }  


        return view("member.purchasing_inventory_system.agent_transactions.agent_collection_center",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_collection($sir_id)
    {
        $data["sir_id"] = $sir_id;
        $data["collection_data"] = Tbl_sir::saleagent()->where("sir_id",$sir_id)->first();
        $data["collectibles"] = currency("Php",Purchasing_inventory_system::get_sir_total_amount($sir_id));
        return view("member.purchasing_inventory_system.agent_transactions.update_collection",$data);   
    }
    public function update_collection_submit()
    {
        $sir_id = Request::input("sir_id");

        $amount = str_replace(",","",Request::input("agent_collection"));
        $amount_remarks = Request::input("agent_remarks");

        $update["agent_collection"] = $amount;
        $update["agent_collection_remarks"] = $amount_remarks;

        Tbl_sir::where("sir_id",$sir_id)->update($update);


        $sir_data = AuditTrail::get_table_data("tbl_sir","sir_id",$sir_id);
        AuditTrail::record_logs("Update","agent_collection",$sir_id,"",serialize($sir_data));

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
