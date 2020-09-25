<?php

namespace App\Http\Controllers\Member;


use App\Globals\Report;
use App\Globals\Transaction;
use DB;
use Carbon\Carbon;
use Request;
class ReportLogisticController extends Member
{
    public function index()
    {
        $data["action"] = "/member/report/logistic";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Logistic Report'; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';

        $report_type    = Request::input('report_type');
        $payment_type    = Request::input('payment_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'this_week';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
        $data['_report'] = Transaction::get_all_transaction_item($this->user_info->shop_id, $data['from'], $data['to'], 'receipt', $payment_type,'a0');
        //dd($data['_report']);
        $data['payment_method'] = $payment_type;

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.logistic_report'; 
            return Report::check_report_type($report_type, $view, $data, 'Logistic Report-'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.logistic.logistic_report', $data);
        }

    }

}
