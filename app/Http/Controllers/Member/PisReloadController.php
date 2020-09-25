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

class PisReloadController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($sir_id = 0)
    {
        $data["_truck"] = Tbl_truck::where("archived",0)->where("truck_shop_id",$this->user_info->shop_id)->get();
        $data["_employees"] = Tbl_employee::position()->where("position_code","sales_agent")->where("shop_id",$this->user_info->shop_id)->where("tbl_employee.archived",0)->get();

        
        $type = [1,4]; 
        $data["_item"] = Item::get_all_category_item($type);

        $data["sir"] = Tbl_sir::truck()->saleagent()->where("sir_id",$sir_id)->first();

        return view("member.purchasing_inventory_system.reload_sir.reload_sir",$data);
        
    }
    public function reload_submit()
    {
        $item_id = Request::input("item");
        $item_qty = Request::input("item_qty");
        $related_um_type = Request::input("related_um_type");

        $sir_id = Request::input("sir_id");
        $warehouse_id = Tbl_sir::where("sir_id",$sir_id)->value("sir_warehouse_id");

        $data["status"] = "";
        $data["status_message"] = "";
        $items = [];
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
        $inventory_consume_product = [];
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
            if(count($inventory_consume_product) > 0)
            {
                $transaction_type = "sir";
                $transaction_id = $sir_id;
                $remarks = "SIR#".$sir_id." Reload";

                $data = Warehouse::inventory_consume($warehouse_id, $remarks, $inventory_consume_product,$consumer_id = 0, $consume_cause = '', $return = 'array', $transaction_type, $transaction_id);                
            }

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
                        $chck = Tbl_sir_item::where("sir_id",$sir_id)->where("item_id",$value)->first();

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
                            if($chck->related_um_type == $related_um_type[$key])
                            {
                                $insert_sir_item["item_qty"] = $chck->item_qty + str_replace(",","",$item_qty[$key]);

                                Tbl_sir_item::where("sir_id",$sir_id)->where("item_id",$value)->where("related_um_type",$related_um_type[$key])->update($insert_sir_item);                                   
                            }
                        }
                        
                        //record truck inventory                            
                        $item["item_id"] = $value;
                        $item["qty"] = str_replace(",","",$item_qty[$key]) * UnitMeasurement::um_qty($related_um_type[$key]);
                        Purchasing_inventory_system::insert_sir_inventory($sir_id,$item,"sir",$sir_id);

                    }
                }                    

            }
            $up["reload_sir"] = 0;
            Tbl_sir::where("sir_id",$sir_id)->update($up);


            $data["status"] = "success-reload-sir";
            $data["sir_id"] = $sir_id;

            $sir_data = Purchasing_inventory_system::get_sir_data($sir_id);
            AuditTrail::record_logs("Reload","pis_stock_issuance_report",$sir_id,"",serialize($sir_data));

        }


        return json_encode($data);

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
