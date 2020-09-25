<?php

namespace App\Http\Controllers\Member;

use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_default_chart_account;
use App\Models\Tbl_shop;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_sir;
use App\Models\Tbl_truck;
use App\Models\Tbl_employee;
use App\Models\Tbl_user;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_sir_inventory;
use App\Models\Tbl_item;
use App\Models\Tbl_credit_memo_line;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_sir_cm_item;
use App\Models\Tbl_temp_customer_invoice_line;
use App\Models\Tbl_temp_customer_invoice;
use App\Models\Tbl_manual_invoice;
use App\Models\Tbl_manual_receive_payment;
use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_manual_credit_memo;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_settings;
use App\Models\Tbl_sir_sales_report;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Pdf_global;
use DB;
use Carbon\Carbon;
use Session;
use Request;

class PisSalesLiquidationController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data["sales"] = "";
        $data["_sir"] = Purchasing_inventory_system::select_ilr_status($this->user_info->shop_id,'array',2,Request::input("sir_id"));

        return view("member.cashier.sales_liquidation.sales_liquidation",$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report($sir_id)
    {
        $chk = Tbl_sir_sales_report::where("sir_id",$sir_id)->first();
        if($chk)
        {
            $return = $chk->report_data;
            // $up["report_data"] = serialize(Purchasing_inventory_system::get_report_data($sir_id));
            // Tbl_sir_sales_report::where("sir_id",$sir_id)->update($up);
            // $return = serialize(Purchasing_inventory_system::get_report_data($sir_id));
        }
        else
        {
            $ins["sir_id"] = $sir_id;
            $ins["report_data"] = serialize(Purchasing_inventory_system::get_report_data($sir_id));
            $ins["report_created"] = Carbon::now();

            Tbl_sir_sales_report::insert($ins);

            $return = serialize(Purchasing_inventory_system::get_report_data($sir_id));
        }
        $data = unserialize($return);

        $html = view("member.cashier.sales_liquidation.liquidation_report",$data);
        $footer = 'REF#'.$sir_id;
        return Pdf_global::show_pdf($html,'',$footer);
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
