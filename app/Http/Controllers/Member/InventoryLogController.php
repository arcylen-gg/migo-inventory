<?php

namespace App\Http\Controllers\Member;

use Request;
use App\Http\Controllers\Controller;
use Session;
use App\Models\Tbl_inventory_slip;
use DB;
class InventoryLogController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouse_id = $this->current_warehouse->warehouse_id;
        $data["_slip"] = Tbl_inventory_slip::where("tbl_inventory_slip.warehouse_id",$warehouse_id)
                        ->where("inventory_slip_shop_id",$this->user_info->shop_id)
                        ->join('tbl_warehouse_inventory', 'tbl_warehouse_inventory.inventory_slip_id', '=', 'tbl_inventory_slip.inventory_slip_id')
                        ->join('tbl_item', 'tbl_item.item_id', '=', 'tbl_warehouse_inventory.inventory_item_id')
                        ->where('inventory_count', '!=', 0);
                        
        $item_search = Request::input('item');
        $filter_search = Request::input('filter');
        if($item_search)
        {
            $data["_slip"] =  $data["_slip"]->where(DB::raw("CONCAT(tbl_item.item_name, ' ', tbl_item.item_sku)"), 'LIKE', "%".$item_search."%");
        }
        if($filter_search)
        {
            if($filter_search != 'all')
            {
                $data["_slip"] =  $data["_slip"]->where('inventory_reason', '=', $filter_search);
            }
        }
        $data["_slip"] =  $data["_slip"]->orderBy("inventory_slip_date","DESC")->get();

        // Filter
        $data['filter_inventory']['all'] = 'All';
        $data['filter_inventory']['refill'] = 'refill';
        $data['filter_inventory']['source'] = 'source';
        $data['filter_inventory']['adjust'] = 'adjust';
        $data['filter_inventory']['destination'] = 'destination';
        // End Filter

        foreach ($data["_slip"] as $key => $value) 
        {
            $data['filter_inventory'][$value->inventory_reason] = $value->inventory_reason;
            if($value->inventory_reason == "destination")
            {
                $data["_slip"][$key]->inventory_reason = "refill";
            }
        }
        return view("member.warehouse.inventory_log.general_inventory_log",$data);
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
