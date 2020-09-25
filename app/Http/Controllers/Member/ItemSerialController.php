<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Tbl_inventory_serial_number;
use App\Models\Tbl_warehouse_inventory;
use App\Globals\ItemSerial;
use App\Models\Tbl_item;
use Validator;
class ItemSerialController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($warehouse_id)
    {
        $data["_item_serial"] = Tbl_inventory_serial_number::item()->warehouse_inventory()->where("has_serial_number",1)->groupBy("tbl_item.item_id")->where("tbl_warehouse_inventory.warehouse_id",$warehouse_id)->where("tbl_item.shop_id",$this->user_info->shop_id)->get();
        foreach ($data["_item_serial"] as $key => $value) 
        {
            $data["_item_serial"][$key]->inventory_count = Tbl_warehouse_inventory::check_inventory_single($warehouse_id,$value->item_id)->value('inventory_count');
        }
        return view("member.item_serial.item_serial",$data);
    }

    public function input_serial()
    {
        $data[] = null;


        return view("member.item_serial.input_serial",$data);
    }
    public function archived_serial()
    {
        ItemSerial::archived_serial();
    }
    public function save_serial()
    {
        $data["status_message"] = "";
        $id = Request::input("id");
        $serial = Request::input("serial");
        $chck = Tbl_inventory_serial_number::where("serial_id",$id)->first();
        $data["status"] = "";
        if($chck)
        {
            if($chck->item_consumed == 0 && $chck->sold == 0)
            {
                $up["serial_number"] = $serial;

                $check_serial = Tbl_inventory_serial_number::where("serial_id","!=",$id)->where("archived",0)->where("serial_number",$serial)->count();
                if($check_serial > 0)
                {
                    $data["status"] = "error";
                    $data["status_message"] .= "The serial number must be unique.";
                }
                else
                {
                    $rule["serial_number"] = "required";

                    $validator = Validator::make($up, $rule);

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
                        Tbl_inventory_serial_number::where("serial_id",$id)->update($up);
                        $data["status"] = "success";                    
                    }                    
                }
            }
            else
            {                
                $data["status"] = "error";
                $data["status_message"] = "This serial has been consumed";
            }
        }
        else
        {                
            $data["status"] = "error";
            $data["status_message"] = "Serial ID not found";
        }

        return json_encode($data);

    }
    public function view_serial($item_id)
    {
        $data["item_name"] = Tbl_item::where("item_id",$item_id)->first();
        $data["_item_serial"] = ItemSerial::getItemSerial($item_id);

        return view("member.item_serial.list_serial",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
