<?php

namespace App\Http\Controllers\Member;

use App\Globals\Purchasing_inventory_system;
use App\Globals\Report;
use App\Globals\UnitMeasurement;
use App\Models\Tbl_item;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_bill;
use App\Models\Tbl_customer_invoice;
use App\Globals\Warehouse2;
use App\Models\Tbl_bill_item_line;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_debit_memo;
use App\Models\Tbl_debit_memo_line;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_credit_memo_line;
use App\Models\Tbl_customer_wis;
use App\Models\Tbl_customer_wis_item_line;
use App\Models\Tbl_sir_item;
use App\Models\Tbl_sir;

use DB;
use Carbon\Carbon;
use Session;
use Request;

class ReportInventoryDetailedController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Inventory - Detailed';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/inventory/detailed';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';

        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);

        $data['_item'] = Tbl_item::type()->loginventory($warehouse_id, [$data["from"], $data["to"]])->get();
        $data['invty'] = null;
        foreach ($data['_item'] as $key => $value)
        {   
            $data['_item'][$key]->inventory_count_balance = Self::get_total_balance_qty($data['from'], $value->item_id);
            $data['_item'][$key]->inventory_count_um = UnitMeasurement::um_view($value->inventory_count_balance, $value->item_measurement_id);
            $data['_item'][$key]->transaction = Self::get_transaction($value->item_id, $warehouse_id, $data['from'], $data['to']);
        }

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            //die(var_dump($load_view));
            $view =  'member.reports.output.inventory_detailed'; 
            return Report::check_report_type($report_type, $view, $data, 'Inventory - Detailed -'.Carbon::now());
        }
        else
        {
            return view("member.reports.inventory.inventory_detailed",$data);
        }
    }
    public function get_total_balance_qty($from, $item_id)
    {
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);

        $data['_item'] = Tbl_item::type()->where('shop_id', $this->user_info->shop_id)->where('item_id', $item_id)->get();
        foreach ($data['_item'] as $key => $value)
        {   
            //dd($from); 2018-02-18 
            $period_start = '0000-00-00';
            $data['get_balance_qty'] = Tbl_warehouse_inventory::WiSlip()
                                                        ->where('inventory_item_id', $value->item_id)
                                                        ->where('inventory_slip_date','<', $from)
                                                        ->orderBy('inventory_created', 'ASC')
                                                        ->get();


            $inventory_count_um = null;
            $inventory_count_balance = 0 ;
            foreach($data['get_balance_qty'] as $total) 
            {  
               //$inventory_count_um = UnitMeasurement::um_view($total->inventory_count, $value->item_measurement_id);
               $inventory_count_balance += $total->inventory_count;  
            }   
            //dd($inventory_count_balance);
            $return  = $inventory_count_balance;
            return $return;
        }
    }
    public function report_header($data)
    {
        return view('member.reports.head', $data);
    }
    public function get_transaction($item_id, $warehouse_id, $from ='', $to ='')
    {
        $data = Tbl_warehouse_inventory::WiSlip()->where('inventory_slip_shop_id', $this->user_info->shop_id)
                                                        ->where('inventory_item_id', $item_id)
                                                        ->where('tbl_warehouse_inventory.warehouse_id', $warehouse_id)
                                                        ->orderBy('inventory_created', 'ASC')
                                                        ->whereBetween('inventory_slip_date', [$from, $to])
                                                        ->get();
        $transaction_name = null;                                               
        $transaction[$item_id] = null;                                             
        $company = null;                                            
        $date = null;
        $per_transaction_cost = null;
        $get_status = null;
        $get_inventory_count = null;
        $per_transaction_price = null;
        $per_transaction = null;
        foreach ($data as $key => $value)
        { 
            $transaction[$item_id][$key]['trans_num'] = $value['inventory_source_id'];
            $get_item_info  = Tbl_item::type()->where('item_id', $value['inventory_item_id'])->first();
            if($value['inventroy_source_reason'] == 'bill')
            {
                $transaction_name = 'Bill';
                $get_company  = Tbl_bill::vendor()->where('bill_id', $value['inventory_source_id'])->first();
                $company = $get_company->vendor_company != '' ? $get_company->vendor_company : $get_company->vendor_title_name." ".$get_company->vendor_first_name." ".$get_company->vendor_middle_name." ".$get_company->vendor_last_name." ".$get_company->vendor_suffix_name;
                $date = $get_company->bill_date;
                $get_per_transaction = Tbl_bill_item_line::where('itemline_bill_id', $value['inventory_source_id'])->where('itemline_item_id', $value['inventory_item_id'])->get();   
                $get_status = $value['inventory_slip_status'];
                $get_inventory_count = UnitMeasurement::um_view($value['inventory_count'], $value['item_measurement_id']);
                $per_transaction_cost = $get_item_info->item_cost;
            }
            elseif($value['inventroy_source_reason'] == 'debit_memo')
            {
                $transaction_name = 'Debit Memo';
                $get_company  = Tbl_debit_memo::vendor()->where('db_id', $value['inventory_source_id'])->first();
                $company = $get_company->vendor_company != '' ? $get_company->vendor_company : $get_company->vendor_title_name." ".$get_company->vendor_first_name." ".$get_company->vendor_middle_name." ".$get_company->vendor_last_name." ".$get_company->vendor_suffix_name;
                $date = $get_company->db_date;
                $get_per_transaction = Tbl_debit_memo_line::where('dbline_db_id', $value['inventory_source_id'])->where('dbline_item_id', $value['inventory_item_id'])->get();   
                $get_status = $value['inventory_slip_status'];
                $get_inventory_count = UnitMeasurement::um_view($value['inventory_count'], $value['item_measurement_id']);
                $per_transaction_cost = $get_item_info->item_cost;
                
            }
            elseif($value['inventroy_source_reason'] == 'credit_memo')
            {
                $transaction_name = 'Credit Memo';
                $get_company  = Tbl_credit_memo::customer()->where('cm_id', $value['inventory_source_id'])->first();
                $company = $get_company->company != '' ? $get_company->company : $get_company->title_name." ".$get_company->first_name." ".$get_company->middle_name." ".$get_company->last_name." ".$get_company->suffix_name;
                $date = $get_company->cm_date;
                $get_per_transaction = Tbl_credit_memo_line::where('cmline_cm_id', $value['inventory_source_id'])->where('cmline_item_id', $value['inventory_item_id'])->get();   
                $get_status = $value['inventory_slip_status'];
                $get_inventory_count = UnitMeasurement::um_view($value['inventory_count'], $value['item_measurement_id']);
                $per_transaction_cost = $get_item_info->item_cost;
            }
            elseif ($value['inventroy_source_reason'] == 'sir')
            {
                $transaction_name = 'Stock Issuance Report';
                $get_company  = Tbl_sir::truck()->where('sir_id', $value['inventory_source_id'])->first();
                $company = $get_company->truck_model != '' ? 'Truck Model - '.$get_company->truck_model.' /Plate # '.$get_company->plate_number : 'Truck # '.$get_company->truck_id.' /Plate # '.$get_company->plate_number;
                $date = $get_company->date_created;
                $get_per_transaction = Tbl_sir_item::where('sir_id', $value['inventory_source_id'])->where('item_id', $value['inventory_item_id'])->get();   
                $get_status = $value['inventory_slip_status'];
                $get_inventory_count = UnitMeasurement::um_view($value['inventory_count'], $value['item_measurement_id']);
                $per_transaction_cost = $get_item_info->item_cost;
                
            }
            elseif ($value['inventroy_source_reason'] == 'invoice')
            {
                $get_company  = Tbl_customer_invoice::customer()->where('inv_id', $value['inventory_source_id'])->first();
                if(count($get_company) > 0)
                {
                    if($get_company->is_sales_receipt == 0)
                    {
                        $transaction_name = 'Sales Receipt';
                        $company = $get_company->company != '' ? $get_company->company : $get_company->title_name." ".$get_company->first_name." ".$get_company->middle_name." ".$get_company->last_name." ".$get_company->suffix_name;
                        $date = $get_company->inv_date;

                        $get_per_transaction = Tbl_customer_invoice_line::where('invline_inv_id', $value['inventory_source_id'])->where('invline_item_id', $value['inventory_item_id'])->get();   
                        $get_status = $value['inventory_slip_status'];
                        $get_inventory_count = UnitMeasurement::um_view($value['inventory_count'], $value['item_measurement_id']);
                        
                        $per_transaction_price = $get_item_info->item_price;
                    }
                    else
                    {
                        $transaction_name = 'Sales Invoice';
                        $company = $get_company->company != '' ? $get_company->company : $get_company->title_name." ".$get_company->first_name." ".$get_company->middle_name." ".$get_company->last_name." ".$get_company->suffix_name;
                        $date = $get_company->inv_date;

                        $get_per_transaction = Tbl_customer_invoice_line::where('invline_inv_id', $value['inventory_source_id'])->where('invline_item_id', $value['inventory_item_id'])->get();   
                        $get_status = $value['inventory_slip_status'];
                        $get_inventory_count = UnitMeasurement::um_view($value['inventory_count'], $value['item_measurement_id']);
                        
                        $per_transaction_price = $get_item_info->item_price;
                    }
                }
            }
           
            elseif ($value['inventroy_source_reason'] == 'initial_qty')
            {
                $value['inventory_slip_status'] = 'refill';
                $transaction_name = 'Initial Quantity';
                $get_item  = Tbl_item::where('item_id', $value['inventory_source_id'])->first();
                $date = $get_item->item_date_created;
                $get_status = $value['inventory_slip_status'];
                $get_inventory_count = UnitMeasurement::um_view($value['inventory_count'], $value['item_measurement_id']);
                $per_transaction_cost = $get_item->item_cost;
                $per_transaction_price = $get_item->item_price;
            }
            elseif ($value['inventroy_source_reason'] == 'customer_wis')
            {
                //dd(123);
                $transaction_name = 'Customer - Warehouse Issuance Slip';
                $get_company  = Tbl_customer_wis::customerinfo()->where('cust_wis_id', $value['inventory_source_id'])->first();
                $company = $get_company->company != '' ? $get_company->company : $get_company->title_name." ".$get_company->first_name." ".$get_company->middle_name." ".$get_company->last_name." ".$get_company->suffix_name;
                $date = $get_company->cust_delivery_date;
                $get_per_transaction = Tbl_customer_wis_item_line::where('itemline_wis_id', $value['inventory_source_id'])->where('itemline_item_id', $value['inventory_item_id'])->get();   
                $get_status = $value['inventory_slip_status'];
                $get_inventory_count = UnitMeasurement::um_view($value['inventory_count'], $value['item_measurement_id']);
                $per_transaction_cost = $get_item_info->item_cost;
                
            }
            $transaction[$item_id][$key]['item_name'] = $get_item_info->item_name;
            $transaction[$item_id][$key]['item_type_name'] = $get_item_info->item_type_name;
            $transaction[$item_id][$key]['item_sales_information'] = $get_item_info->item_sales_information;
            $transaction[$item_id][$key]['trans_name'] = $transaction_name;
            $transaction[$item_id][$key]['trans_invty_count'] = $get_inventory_count;
            $transaction[$item_id][$key]['trans_date'] = $date;
            $transaction[$item_id][$key]['trans_status'] = $get_status;
            $transaction[$item_id][$key]['company'] = $company;
            $transaction[$item_id][$key]['cost'] = $per_transaction_cost;
            $transaction[$item_id][$key]['price'] = $per_transaction_price;               
        }
        return $transaction;
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
