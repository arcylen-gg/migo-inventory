<?php

namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Globals\Report;
use App\Models\Tbl_quantity_monitoring;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_write_check;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_item;
use App\Models\Tbl_vendor;
use App\Models\Tbl_bill;
use Carbon\Carbon;
use App\Globals\TransactionEnterBills;
use DB;

class ReportPurchaseVendorController extends Member
{
    public function index_summary(Request $request)
    {
        $data["action"] = "/member/report/open/purchase/vendor_summary";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Purchases by Vendor Summary'; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);

        $report_type    = $request->report_type;
        $load_view      = $request->load_view;
        $period         = $request->report_period ? $request->report_period : 'all';
        $date['start']  = $request->from;
        $date['end']    = $request->to;
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $join_select = 1;
        $shop_id = $this->user_info->shop_id;

        $data['_vendor'] = Tbl_vendor::where('vendor_shop_id',$this->user_info->shop_id)->get();

        foreach ($data['_vendor'] as $key => $value)
        {
            $param_func['vendor_id'] = $value->vendor_id;
            $param_func['shop_id'] = $shop_id;
            $param_func['date_from'] = $data['from'];
            $param_func['date_to'] = $data['to'];
            $param_func['join_parameter'] = $join_select;
            $param_func['module_used'] = 'reports';

            $param_func['where_used'] = 'purchased_by_vendor';
            $data['_vendor'][$key]['bill'] = Tbl_bill::PurchasedByQueries_with_where($param_func)->get();

            $param_func['get_total_param'] = 'sum_qty';
            $param_func['where_used'] = 'purchased_by_vendor';
            $data['_vendor'][$key]['itm_qty_total'] = TransactionEnterBills::get_reports_amount($param_func);


            $param_func['get_total_param'] = 'sum_amount';
            $param_func['where_used'] = 'purchased_by_vendor';
            $data['_vendor'][$key]['amt_total'] = TransactionEnterBills::get_reports_amount($param_func);

            $param_func['get_total_param'] = 'sum_amount';
            $param_func['where_used'] = 'purchased_by_vendor_sum_amount';
            $data['_vendor'][$key]['amt_total_all_vendor'] = TransactionEnterBills::get_reports_amount($param_func);
        }

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.purchases_by_vendor_summary'; 
            return Report::check_report_type($report_type, $view, $data, 'Open Purchase Order by Vendor Summary'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.inventory.purchases_by_vendor_summary', $data);
        }
    }

    public function index(Request $request)
    {
        $data["action"] = "/member/report/open/purchase/vendor";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Purchase Order by Vendor'; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);

        $report_type    = $request->report_type;
        $load_view      = $request->load_view;
        $period         = $request->report_period ? $request->report_period : 'all';
        $date['start']  = $request->from;
        $date['end']    = $request->to;
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $join_select = 1;
        $shop_id = $this->user_info->shop_id;

        $data['_vendor'] = Tbl_vendor::where('vendor_shop_id',$this->user_info->shop_id)->get();

        foreach ($data['_vendor'] as $key => $value)
        {
            $param_func['vendor_id'] = $value->vendor_id;
            $param_func['shop_id'] = $shop_id;
            $param_func['date_from'] = $data['from'];
            $param_func['date_to'] = $data['to'];
            $param_func['join_parameter'] = $join_select;
            $param_func['module_used'] = 'reports';
            $param_func['where_used'] = 'purchased_by_vendor';

            $data['_vendor'][$key]['bill'] = Tbl_bill::PurchasedByQueries_with_where($param_func)->get();

            $param_func['get_total_param'] = 'sum_qty';
            $data['_vendor'][$key]['itm_qty_total'] = TransactionEnterBills::get_reports_amount($param_func);

            $param_func['get_total_param'] = 'sum_amount';
            $data['_vendor'][$key]['amt_total'] = TransactionEnterBills::get_reports_amount($param_func);

            $param_func['get_total_param'] = 'sum_amount';
            $param_func['where_used'] = 'purchased_by_vendor_sum_amount';
            $data['_vendor'][$key]['amt_total_all_vendor'] = TransactionEnterBills::get_reports_amount($param_func);
          
			$Cumulative_Sum = 0;

        	foreach ($data['_vendor'][$key]['bill'] as $key_item => $value) 
        	{
        		$data['_vendor'][$key]['bill'][$key_item]['balance_vendor'] = $data['_vendor'][$key]['bill'][$key_item]['itemline_rate'] * $data['_vendor'][$key]['bill'][$key_item]['itemline_qty'];

        		$Cumulative_Sum += $data['_vendor'][$key]['bill'][$key_item]['balance_vendor'];
    			$data['_vendor'][$key]['bill'][$key_item]['balance_cumulative'] = $Cumulative_Sum;
        	}
        }

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.open_purchase_vendor'; 
            return Report::check_report_type($report_type, $view, $data, 'Open Purchase Order by Vendor'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.inventory.open_purchase_vendor', $data);
        }
    }

     public function index_detailed(Request $request,$vendor_id)
    {
        $data['vendor_id'] = $vendor_id;
        $data["action"] = "/member/report/vendor/detailed/{id}";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Purchase by Vendor Summary'; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);

        $report_type    = $request->report_type;
        $load_view      = $request->load_view;
        $period         = $request->report_period ? $request->report_period : 'all';
        $date['start']  = $request->from;
        $date['end']    = $request->to;
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $join_select = 1;
        $shop_id = $this->user_info->shop_id;

        $data['_vendor'] = Tbl_vendor::where('vendor_shop_id',$this->user_info->shop_id)->where('vendor_id',$vendor_id)->get();

        foreach ($data['_vendor'] as $key => $value)
        {
            $param_func['vendor_id'] = $value->vendor_id;
            $param_func['shop_id'] = $shop_id;
            $param_func['date_from'] = $data['from'];
            $param_func['date_to'] = $data['to'];
            $param_func['join_parameter'] = $join_select;
            $param_func['module_used'] = 'reports';
            $param_func['where_used'] = 'purchased_by_vendor';

            $data['_vendor'][$key]['bill'] = Tbl_bill::PurchasedByQueries_with_where($param_func)->get();

            $param_func['get_total_param'] = 'sum_qty';
            $data['_vendor'][$key]['itm_qty_total'] = TransactionEnterBills::get_reports_amount($param_func);

            $param_func['get_total_param'] = 'sum_amount';
            $data['_vendor'][$key]['amt_total'] = TransactionEnterBills::get_reports_amount($param_func);

            $Cumulative_Sum = 0;

            foreach ($data['_vendor'][$key]['bill'] as $key_item => $value) 
            {
                $data['_vendor'][$key]['bill'][$key_item]['balance_vendor'] = $data['_vendor'][$key]['bill'][$key_item]['itemline_rate'] * $data['_vendor'][$key]['bill'][$key_item]['itemline_qty'];

                $Cumulative_Sum += $data['_vendor'][$key]['bill'][$key_item]['balance_vendor'];
                $data['_vendor'][$key]['bill'][$key_item]['balance_cumulative'] = $Cumulative_Sum;
            }
        }

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.purchases_by_vendor_summary_detailed'; 
            return Report::check_report_type($report_type, $view, $data, 'Purchase by Vendor Summary'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.inventory.purchases_by_vendor_summary_detailed', $data);
        }
    }

    public function report_header($data)
    {
        return view('member.reports.head', $data);
    }
}
