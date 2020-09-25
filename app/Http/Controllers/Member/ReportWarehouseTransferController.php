<?php

namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Globals\Transaction;
use App\Globals\Warehouse2;
use App\Globals\Report;
use App\Globals\Item;
use App\Globals\WarehouseTransfer;
use Carbon\Carbon;
use App\Models\Tbl_warehouse_issuance_report;
use App\Models\Tbl_warehouse_issuance_report_itemline;
use App\Models\Tbl_warehouse;

class ReportWarehouseTransferController extends Member
{
    public function index(Request $request)
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Warehouse Transfer';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/warehouse/transfer';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = $request->report_type;
        $load_view      = $request->load_view;
        $period         = $request->report_period ? $request->report_period : 'all';
        $date['start']  = $request->from;
        $date['end']    = $request->to;
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id, null, 0, null, 0);

        foreach ($data['_warehouse'] as $key_all_warehouse => $value_all_warehouse)
        {
            $data['_warehouse'][$key_all_warehouse]['_wt'] = WarehouseTransfer::get_all_wis($this->user_info->shop_id, 'all', $value_all_warehouse->warehouse_id, $data['from'], $data['to']);
            $data['_warehouse'][$key_all_warehouse]['total_per_warehouse'] = Self::total_per_warehouse($this->user_info->shop_id, $value_all_warehouse->warehouse_id, $data['from'], $data['to']);
        }
        //dd($data['_warehouse']);
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.warehouse_transfer'; 
            return Report::check_report_type($report_type, $view, $data, 'Warehouse Transfer'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.accounting.warehouse_transfer', $data);
        }
    }
    public function detailed(Request $request, $wis_id)
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Warehouse Transfer Detailed';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/warehouse/transfer/detailed/'.$wis_id;
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = $request->report_type;
        $load_view      = $request->load_view;
        $period         = $request->report_period ? $request->report_period : 'all';
        $date['start']  = $request->from;
        $date['end']    = $request->to;
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['wis_id'] = $wis_id;
        $data['wis'] = WarehouseTransfer::get_wis_data($wis_id);
        $data['transaction_description'] = WarehouseTransfer::get_transaction_description($wis_id);
        $data['wis_item'] = WarehouseTransfer::get_item_per_wt($this->user_info->shop_id, $wis_id);

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.warehouse_transfer_detailed'; 
            return Report::check_report_type($report_type, $view, $data, 'Warehouse Transfer Detailed'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.accounting.warehouse_transfer_detailed', $data);
        }
    }

    public function total_per_warehouse($shop_id, $warehouse_id, $from = '', $to = '')
    {
        $total_per_wis = 0;
        $data = Tbl_warehouse_issuance_report::itemline()->where('wis_from_warehouse', $warehouse_id)->where('wis_shop_id', $shop_id);
        if($from && $to)
        {
            $data = $data->whereBetween('created_at', [$from, $to]);
        }
        $data = $data->get();
        if($data)
        {
            foreach ($data as $key => $value) 
            {
                $total_per_wis += $value->wt_amount;
            }
        }
        return $total_per_wis;
    }
    public function total($shop_id, $from = '', $to = '')
    {
        $total_per_wis = 0;
        $data = Tbl_warehouse_issuance_report::where('wis_shop_id', $shop_id);
        if($from && $to)
        {
            $data = $data->whereBetween('wis_delivery_date', [$from, $to]);
        }
        $data = $data->get();
        if($data)
        {
            foreach ($data as $key => $value) 
            {
                $total_per_wis += $value->wis_total_amount;
            }
        }
        return $total_per_wis;
    }
    public function report_header($data)
    {
        return view('member.reports.head', $data);
    }
}
