<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use App\Globals\SalesReport;
use App\Globals\Report;
use App\Globals\Pdf_global;
use App\Globals\Warehouse2;
use App\Globals\Accounting;
use App\Globals\Item;
use App\Globals\TransactionSalesInvoice;
use App\Globals\AccountingTransaction;
use App\Globals\TransactionEnterBills;
use App\Globals\Migo;

use App\Models\Tbl_shipping;
use App\Models\Tbl_user;
use App\Models\Tbl_customer;
use App\Models\Tbl_vendor;
use App\Models\Tbl_item;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_order;
use App\Models\Tbl_order_item;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_report_field;
use App\Models\Tbl_category;
use App\Models\Tbl_chart_account_type;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_payment_method;
use App\Models\Tbl_customer_invoice;


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
use DateTime;
use DateInterval;
use DatePeriod;


class CustomizedSalesReportController extends Member
{
	public function report_header($data)
    {
        return view('member.reports.head', $data);
    }

    public function top_customer()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Top Customer';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/top_customer';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        $data["_item_type"]     = Item::get_item_type_list();
        $data["_item_category"] = Item::getItemCategory($this->user_info->shop_id);
        $data['check_terms_to_be_used'] = AccountingTransaction::settings($this->user_info->shop_id, 'terms_to_be_used');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_top_customer'] = Migo::get_top_customer($this->user_info->shop_id, $data['from'],$data['to']);
        
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.top_customer.list'; 

            return Report::check_report_type($report_type, $view, $data, 'top-customer-'.Carbon::now());
        }
        else
        {
            return view('member.reports.top_customer.list_header', $data);
        }

    }

    public function daily_sales_report_receive()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Daily Sales Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/daily_sales_report';
        
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'today';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
        $shop_id = $this->user_info->shop_id;
        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
        $data['_payment_method'] = Tbl_payment_method::where('shop_id',$shop_id)->where('archived',0)->get();
        
        // $data['from'] = '2019-01-07';
        $for_shipping_fee = 6;

        $data['_inv'] = Tbl_customer_invoice::customer()
                                            ->where('inv_shop_id',$shop_id)
                                            ->where('inv_date',$data['from'])
                                            ->groupby('inv_customer_id')
                                            ->get()->toArray(); 
        $data['_sales'] = null;
        $data['_pm_total'] = null;
        $data['_total_sf'] = 0;
        $data['_total_sales'] = 0;
        $data['_total_applied'] = 0;
        $data['_total_balance'] = 0;
        $data['_total'] = 0;
        foreach ($data['_inv'] as $key => $value)
        {
            $data['_sales'][$value['inv_customer_id']]['customer_name'] = $value['company'] ? $value['company'] : $value['first_name']." ".$value['middle_name']." ".$value['last_name'];

            $total_payment_applied = 0;
            $total_overall = 0;
            $total_sales = 0;
            foreach ($data['_payment_method'] as $keypm => $valuepm) 
            {
                $pm_total = $this->get_old_inv_sum_amount($shop_id, $value['inv_customer_id'], $data['from'], $valuepm->payment_method_id, 'inv_overall_price', true);
                if($pm_total == 0)
                {
                    $pm_total = $this->get_inv_sum_amount($shop_id, $value['inv_customer_id'], $data['from'], $valuepm->payment_method_id, 'invoice_amount', false, true);
                }
                $payment_applied = $this->get_old_inv_sum_amount($shop_id, $value['inv_customer_id'], $data['from'], $valuepm->payment_method_id, 'inv_payment_applied', false);
                if($payment_applied == 0)
                {
                    $payment_applied = $this->get_inv_sum_amount($shop_id, $value['inv_customer_id'], $data['from'], $valuepm->payment_method_id, 'inv_payment_applied', false);
                }
                $overall_price = $this->get_old_inv_sum_amount($shop_id, $value['inv_customer_id'], $data['from'], $valuepm->payment_method_id);
                if($overall_price == 0)
                {
                    $overall_price = $this->get_inv_sum_amount($shop_id, $value['inv_customer_id'], $data['from'], $valuepm->payment_method_id, 'inv_overall_price');
                }
                $data['_sales'][$value['inv_customer_id']]['pm_paid'][$valuepm->payment_method_id] = $pm_total;
                $total_payment_applied += $payment_applied ;
                $total_overall += $overall_price;
                $total_sales += $pm_total;

                $total_per_pm = $this->get_old_inv_sum_amount($shop_id, null, $data['from'], $valuepm->payment_method_id, 'inv_overall_price');
                if($total_per_pm == 0)
                {
                    $total_per_pm = $this->get_inv_sum_amount($shop_id, null, $data['from'], $valuepm->payment_method_id, 'invoice_amount');
                }
                $total_sf_per_pm = $this->get_inv_old_shipping($shop_id, null, $data['from'], $valuepm->payment_method_id);
                if($total_sf_per_pm == 0)
                {
                    $total_sf_per_pm = $this->get_inv_shipping($shop_id, null, $data['from'], $valuepm->payment_method_id);
                }
                $data['_pm_total'][$valuepm->payment_method_id]['amount'] = $total_per_pm;
                $data['_pm_total'][$valuepm->payment_method_id]['sf'] = $total_sf_per_pm;
            }
            $sf = Tbl_customer_invoice::Invoice_item()
                                        ->where('inv_shop_id',$shop_id)
                                        ->where('inv_date',$data['from'])
                                        ->where('inv_customer_id',$value['inv_customer_id'])
                                        ->where('tbl_item.item_type_id',$for_shipping_fee)
                                        ->sum('invline_amount');

            $data['_sales'][$value['inv_customer_id']]['shipping_fee'] = $sf;
            $data['_sales'][$value['inv_customer_id']]['total_sales'] = $total_sales - $sf;
            $data['_sales'][$value['inv_customer_id']]['total'] = $total_sales;
            $data['_sales'][$value['inv_customer_id']]['total_applied'] = $total_payment_applied;
            $data['_sales'][$value['inv_customer_id']]['total_overall'] = $total_overall;
            $data['_total_sf'] += $sf;
            $data['_total_sales'] += $data['_sales'][$value['inv_customer_id']]['total_sales'];
            $data['_total_applied'] += $total_payment_applied;
            $data['_total'] += $total_sales;
            $data['_total_balance'] += $total_overall - $total_payment_applied;
            $data['_sales'][$value['inv_customer_id']]['_invoice_ref'] = $this->get_ref_num($shop_id, $value['inv_customer_id'], $data['from']);
        }
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.dailysalesreport.output_daily_sales_report'; 
            return Report::check_report_type($report_type, $view, $data, 'daily_sales_report-'.Carbon::now());
        }
        else
        {
            return view('member.reports.dailysalesreport.daily_sales_report',$data);
        }

    }
    public function get_ref_num($shop_id, $customer_id, $date)
    {
        $_get = Tbl_customer_invoice::customer()->where('inv_shop_id',$shop_id)
                                            ->where('inv_date',$date)
                                            ->where('inv_customer_id',$customer_id)->selectRaw('transaction_refnum, inv_id, is_sales_receipt')
                                            ->get()->toArray();
        // $return = null;
        // foreach ($_get as $value) 
        // {
        //     $return .= $value['transaction_refnum'].", ";
        // }
        return $_get;
    }
    public function get_old_inv_sum_amount($shop_id, $customer_id = null, $date = '', $payment_method_id = '', $sum_of = '', $without_shipping = false)
    {
        $ret = Tbl_customer_invoice::customer();

        if($customer_id)
        {
            $ret = $ret->where("inv_customer_id", $customer_id);
        }
        if($shop_id)
        {
            $ret = $ret->where('inv_shop_id',$shop_id);            
        }
        if($date)
        {
            $ret = $ret->where('inv_date',$date);
        }
        if($payment_method_id)
        {
            $ret = $ret->where('inv_payment_method', $payment_method_id);
        }
        if($sum_of)
        {
            $ret = $ret->sum($sum_of);
        }
        else
        {
            $ret = $ret->sum('inv_overall_price');
        }

        $total = $ret;
        if($total != 0 && $without_shipping)
        {
            $total = $ret - $this->get_inv_old_shipping($shop_id, $customer_id, $date, $payment_method_id);
        }
        return  $total;
    }
    public function get_inv_sum_amount($shop_id, $customer_id = null, $date = '', $payment_method_id = '', $sum_of = '', $without_shipping = false, $per_pm = false)
    {
        $ret = Tbl_customer_invoice::customer()->pm();

        if($customer_id)
        {
            $ret = $ret->where("inv_customer_id", $customer_id);
        }
        if($shop_id)
        {
            $ret = $ret->where('inv_shop_id',$shop_id);            
        }
        if($date)
        {
            $ret = $ret->where('inv_date',$date);
        }
        if($payment_method_id)
        {
            $ret = $ret->where('inv_pm_id', $payment_method_id);
        }
        $total = 0;
        if($per_pm)
        {
            $ret = $ret->groupby('invoice_pm_id')->get();
            foreach ($ret as $key => $value)
            {
                $total += $value->$sum_of;
            }
        }
        if($total == 0)
        {
            if($sum_of)
            {
                $ret = $ret->sum($sum_of);
            }
            else
            {
                $ret = $ret->sum('inv_overall_price');
            }
            $total = $ret;
        }
        if($total != 0 && $without_shipping)
        {
            $total = $total - $this->get_inv_shipping($shop_id, $customer_id, $date, $payment_method_id);
        }
        return  $total;
    }
    public function get_inv_old_shipping($shop_id, $customer_id = null, $date = '', $payment_method_id = '', $sum_of = '')
    {
        $ret = Tbl_customer_invoice::invoice_item()
                                    ->selectRaw('invline_amount')
                                    ->where('tbl_item.item_type_id',6);
                                    // ->groupby("invline_id");

        if($customer_id)
        {
            $ret = $ret->where("inv_customer_id", $customer_id);
        }
        if($shop_id)
        {
            $ret = $ret->where('inv_shop_id',$shop_id);            
        }
        if($date)
        {
            $ret = $ret->where('inv_date',$date);
        }
        if($payment_method_id)
        {
            $ret = $ret->where('inv_payment_method',$payment_method_id);            
        }
        if($sum_of)
        {
            $ret = $ret->sum($sum_of);
        }
        else
        {
            $ret = $ret->sum('invline_amount');
        }

        return $ret;
    }
    public function get_inv_shipping($shop_id, $customer_id = null, $date = '', $payment_method_id = '', $sum_of = '')
    {
        $ret = Tbl_customer_invoice::invoice_item()
                                    ->pm()
                                    ->selectRaw('invline_amount')
                                    ->where('tbl_item.item_type_id',6)
                                    ->groupby("invline_id");

        if($customer_id)
        {
            $ret = $ret->where("inv_customer_id", $customer_id);
        }
        if($shop_id)
        {
            $ret = $ret->where('inv_shop_id',$shop_id);            
        }
        if($date)
        {
            $ret = $ret->where('inv_date',$date);
        }
        if($payment_method_id)
        {
            $ret = $ret->where('inv_pm_id', $payment_method_id);         
        }
        if($sum_of)
        {
            $ret = $ret->sum($sum_of);
        }
        else
        {
            $ret = $ret->sum('invline_amount');
        }

        return $ret;
    }
	public function daily_sales_report()
	{
		$data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Daily Sales Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/daily_sales_report';
        
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'today';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
         
        // $data['from'] = "2018-11-19";

        $shop_id = $this->user_info->shop_id;

        $warehouse_id = Warehouse2::get_current_warehouse($shop_id);
   		
   		$data['shop_payment_method'] = Tbl_payment_method::where('shop_id',$shop_id)->where('archived',0)->get();

        $data['customer'] = Tbl_customer_invoice::customer()->where('inv_shop_id',$shop_id)->whereNotNull('first_name')->where('inv_date',$data['from'])->groupby('inv_customer_id')->select('inv_customer_id','first_name','middle_name','last_name','suffix_name','company')->get(); 

        
        $total_customer_shipping = 0;
        $total_customer_all = 0;

        $for_shipping_fee = 6; // item type 

        //CUSTOMER DATA
        foreach ($data['customer'] as $key => $value) 
        {
            $data['customer'][$key]['transaction_ref_num'] = Tbl_customer_invoice::customer()->where('inv_shop_id',$shop_id)->whereNotNull('first_name')->where('inv_date',$data['from'])->where('inv_customer_id',$value->inv_customer_id)->select('transaction_refnum')->get(); 
            //TOTAL PER PAYMENT METHOD
            $total_key = array();
            $total_shipping = array();
            $data['customer'][$key]['data'] = $data['shop_payment_method'];

            foreach ($data['customer'][$key]['data'] as $key_payment => $value_payment) 
            {
                $total = Tbl_customer_invoice::where('inv_shop_id',$shop_id)->where('inv_customer_id',$value->inv_customer_id)->where('inv_date',$data['from'])->where('inv_payment_method',$value_payment->payment_method_id)->sum('inv_overall_price');
                
                if($value_payment->payment_name == "Cash") // all null result in inv_payment_method are considered as cash
                {
                    $total += Tbl_customer_invoice::where('inv_shop_id',$shop_id)->where('inv_customer_id',$value->inv_customer_id)->where('inv_date',$data['from'])->whereNull('inv_payment_method')->sum('inv_overall_price');
                }

                $shipping_fee = Tbl_customer_invoice::Invoice_item()->where('inv_shop_id',$shop_id)->where('inv_date',$data['from'])->where('inv_customer_id',$value->inv_customer_id)->where('tbl_item.item_type_id',$for_shipping_fee)->where('inv_payment_method',$value_payment->payment_method_id)->sum('invline_amount');

                array_push($total_key, $total);
                array_push($total_shipping, $shipping_fee);
            }
            //TOTAL PER PAYMENT METHOD
            $data['customer'][$key]['total_payment'] = $total_key;
            $data['customer'][$key]['per_customer'] = $total_shipping;

            $data['customer'][$key]['total_all_shipping'] = collect($data['customer'][$key]['per_customer'])->sum();
            $data['customer'][$key]['total_all'] = collect($data['customer'][$key]['total_payment'])->sum();

            $total_customer_shipping += $data['customer'][$key]['total_all_shipping'];
            $total_customer_all += $data['customer'][$key]['total_all'];
        }
        //CUSTOMER DATA
        //TOTAL PER PAYMENT METHOD
        foreach ($data['shop_payment_method'] as $key => $value) 
        {
            $total = Tbl_customer_invoice::where('inv_shop_id',$shop_id)->where('inv_date',$data['from'])->where('inv_payment_method',$value->payment_method_id)->sum('inv_overall_price');

            if($value->payment_name == "Cash") // all null result in inv_payment_method are considered as cash
            {
                $total += Tbl_customer_invoice::where('inv_shop_id',$shop_id)->where('inv_date',$data['from'])->whereNull('inv_payment_method')->sum('inv_overall_price');
            }

            $deduct_shipping_per_payment_method = Tbl_customer_invoice::Invoice_item()->where('inv_shop_id',$shop_id)->where('inv_date',$data['from'])->where('inv_payment_method',$value->payment_method_id)->where('tbl_item.item_type_id',$for_shipping_fee)->sum('invline_amount');
            
            $data['total_all'][$key] = $total - $deduct_shipping_per_payment_method;
        }
        //TOTAL PER PAYMENT METHOD

        $data['total_all_shipping'] = $total_customer_shipping;
        // $data['total_all_customer'] = $total_customer_all;
        $data['total_all_customer'] = collect($data['total_all'])->sum();
        // dd($data['customer'],$data['total_all'],$data['total_all_customer'],$data['total_all_shipping']);

         /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.daily_sales_report'; 
            return Report::check_report_type($report_type, $view, $data, 'daily_sales_report-'.Carbon::now());
        }
        else
        {
            return view('member.reports.customized_sales_report.daily_sales_report.daily_sales_report',$data);
        }
	}

	public function monthly_sales_report()
	{
		$data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Monthly Sales Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/monthly_sales_report';

        
        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');

        $data['selected_year'] = Request::input("selected_year") ? Request::input("selected_year") : date("Y");
        $data['selected_month'] = Request::input("selected_month") ? Request::input("selected_month") : date("m");
        $data['from']   = $data['selected_year']."-".$data['selected_month']."-01";

        $data['_month'] = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $data['_year'] = [];
        //year to start with
        $startdate = 2017;
        $enddate = date("Y")+25;         
        $years = range ($startdate,$enddate);
        foreach($years as $key => $year)
        {
          $data['_year'][$key] = $year;
        }

        $shop_id = $this->user_info->shop_id;

        $for_shipping_fee = 6; // item type 

        $data['date_range'] = Self::get_date_range("for_header",$data['from']);
        $data['search_date'] = Self::get_date_range("for_search",$data['from']);

        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
   		
   		$data['shop_payment_method'] = Tbl_payment_method::where('shop_id',$this->user_info->shop_id)->where('archived',0)->get();

        $data['customer'] = Tbl_customer_invoice::customer()->where('inv_shop_id',$shop_id)->whereNotNull('first_name')->groupby('inv_customer_id')->select('inv_customer_id','first_name','middle_name','last_name','suffix_name','company')->get(); 

        //TOTAL PER CUSTOMER
        foreach ($data['customer'] as $key => $value) 
        {
            $total_key = array();
            $data['customer'][$key]['data'] = $data['search_date'];

            foreach ($data['customer'][$key]['data'] as $key_date => $value_date) 
            {
                $total = Tbl_customer_invoice::Invoice_item()->where('inv_shop_id',$shop_id)->where('inv_customer_id',$value->inv_customer_id)->where('inv_date',$data['search_date'][$key_date])->where('tbl_item.item_type_id','!=',$for_shipping_fee)->sum('invline_amount');

                array_push($total_key, $total);
            }

            $data['customer'][$key]['total_date'] = $total_key;

            $data['customer'][$key]['total_all'] = collect($data['customer'][$key]['total_date'])->sum();
        }
        //TOTAL PER CUSTOMER

        //TOTAL PER DAY
        foreach ($data['search_date'] as $key_date => $value) 
        {
            $data['total_per_day'][$key_date] = Tbl_customer_invoice::Invoice_item()->where('inv_shop_id',$shop_id)->where('inv_date',$data['search_date'][$key_date])->where('tbl_item.item_type_id','!=',$for_shipping_fee)->sum('invline_amount');
        }

        $data['total_month'] = collect($data['total_per_day'])->sum();
        //TOTAL PER DAY

        // dd($data);

        // dd(Tbl_customer_invoice::Invoice_item()->where('inv_shop_id',31)->where('inv_customer_id',489)->where('inv_date','2018-11-19')->where('tbl_item.item_type_id','!=',6)->sum('invline_amount'));
        // dd($data['customer'][3],$data['total_per_day']);

         /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.monthly_sales_report'; 
            return Report::check_report_type($report_type, $view, $data, 'monthly_sales_report-'.Carbon::now());
        }
        else
        {
            return view('member.reports.customized_sales_report.monthly_sales_report.monthly_sales_report',$data);
        }
	}

	public static function get_date_range($array_result,$date_from)
	{
        $date_split = explode("-", $date_from);
        // dd($wew);
		$data_date_range = array();
		$date_now = date("01-".$date_split[1]."-".$date_split[0]);
        // dd($date_now);  
        $date_end = date("t-m-Y",strtotime($date_now));

        $begin = new DateTime($date_now);
		$end = new DateTime($date_end);
		$end = $end->modify( '+1 day' ); 

		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);

        if($array_result == "for_header")
        {
            foreach($daterange as $date)
            {
                array_push($data_date_range, $date->format("m/d/Y"));
            }
        }
        else if($array_result == "for_search") 
        {
            foreach($daterange as $date)
            {
                array_push($data_date_range, $date->format("Y-m-d"));
            }
        }

		

		return $data_date_range;
	}
}