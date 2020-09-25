<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use App\Globals\Report;
use App\Globals\Warehouse2;
use App\Globals\CustomerWIS;
use App\Models\Tbl_customer_wis;

use Carbon\Carbon;
use Request;
use Image;
use Validator;
use Redirect;
use File;
use URL;
use Session;
use App;
use PDF;
use View;
use DB;
use Excel;


class ReportWISController extends Member
{
	public function index()
	{
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Warehouse Issuance Slip Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/warehouse/wis';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id, null, 0, null, 0);
		
		foreach ($data['_warehouse'] as $key_all_warehouse => $value_all_warehouse)
        {
            $data['_warehouse'][$key_all_warehouse]['_wis'] = CustomerWIS::get_all_customer_wis($this->user_info->shop_id, 'all', $value_all_warehouse->warehouse_id, $data['from'], $data['to']);
            $data['_warehouse'][$key_all_warehouse]['total_per_warehouse'] = Self::total_per_warehouse($this->user_info->shop_id, $value_all_warehouse->warehouse_id, $data['from'], $data['to']);
        }
        
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.wis.wis_summary'; 

            return Report::check_report_type($report_type, $view, $data, 'wis_summary-'.Carbon::now());
        }
        else
        {
            return view('member.reports.wis.wis_summary_head', $data);
        }
	}
    public function detailed($wis_id)
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Warehouse Issuance Slip Detailed';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/warehouse/wis/detailed/'.$wis_id;
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['wis_id'] = $wis_id;
        $data['wis'] = CustomerWIS::get_customer_wis_data($wis_id);
        $data['transaction_description'] = CustomerWIS::get_transaction_description($wis_id);
        $data['wis_item'] = CustomerWIS::get_item_per_wis($this->user_info->shop_id, $wis_id);

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.wis.wis_detailed'; 
            return Report::check_report_type($report_type, $view, $data, 'wis_detailed'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.wis.wis_detailed_head', $data);
        }
    }
    public function total_per_warehouse($shop_id, $warehouse_id, $from = '', $to = '')
    {
        $total_per_wis = 0;
        $data = Tbl_customer_wis::where('cust_wis_from_warehouse', $warehouse_id)->where('cust_wis_shop_id', $shop_id);
        if($from && $to)
        {
            $data = $data->whereBetween('created_at', [$from, $to]);
        }
        $data = $data->get();
        if($data)
        {
            foreach ($data as $key => $value) 
            {
                $total_per_wis += $value->total_amount;
            }
        }
        return $total_per_wis;
    }

    public function report_header($data)
    {
        return view('member.reports.head', $data);
    }
}