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
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_settings;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Report;
use DB;
use Carbon\Carbon;
use Session;
use Request;
class ReportAgentProfitLossController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date["from"] = Report::checkDatePeriod($period,$date)['start_date'];
        $date["to"] = Report::checkDatePeriod($period,$date)['end_date'];
        $data = Purchasing_inventory_system::get_sales_loss_over($date["from"],$date["to"]);
        $data["from"] = Report::checkDatePeriod($period,$date)['start_date'];
        $data["to"] = Report::checkDatePeriod($period,$date)['end_date'];   
        $data["action"] = "/member/report/agent/profit_loss";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Agent Profit & Loss'; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        // dd($data);
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.agent_profit_loss'; 
            return Report::check_report_type($report_type, $view, $data, 'Agent_Profit_and_Loss-'.Carbon::now());
        }
        else
        {
            return view("member.reports.agent_sales.agent_profit_loss",$data);
        }

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
