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
use App\Globals\Customer;
use App\Globals\UnitMeasurement;

use App\Models\Tbl_credit_memo;
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
use App\Models\Tbl_journal_entry;
use App\Models\Tbl_acctg_transaction_list;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_bill;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_sales_representative;
use App\Models\Tbl_customer_invoice_line;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
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


class ReportsController extends Member
{

    public function income_statement()
    {
        
    }
    public function quick_report($account_id = 0)
    {
        $period         = Request::input('report_period');
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $report_type = Request::input('report_type');
        $report_field_type = Request::input('report_field_type');
        $data['report_type'] = $report_type;
        $shop_id = $this->user_info->shop_id;
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $data['_account'] = Accounting::getAccountTransaction(null, $account_id);
        dd($data['_account']);

    }
	public function checkuser($str = '')
    {
        $user_info = Tbl_user::where("user_email", Session('user_email'))->shop()->first();
        switch ($str) {
            case 'user_id':
                return $user_info->user_id;
                break;
            case 'user_shop':
                return $user_info->user_shop;
                break;
            default:
                return '';
                break;
        }
    }
	public function index()
    {
		$shop_id = $this->checkuser('user_shop');
		
		$data = SalesReport::reportcount($shop_id);
		
	    return view('member.reports.index',$data);
	}
	
	public function endDate()
    {
        return date('Y-m-d');
    }
    
    public function startDate()
    {
        $year = date('Y') - 1;
        $month = date('m') + 1;
        if($month > 12){
            $month = 1;
        }
        $day = date('d');
        $start = $year.'-'.$month.'-'.$day;
        
        return $start;
    }

    /**
     * Check Date Period fro showing the date range
     *
     * @return json  $data    data['start_date'], data['end_date'], date['period']     
     * @author BKA  
     */
    public function get_date_period_covered()
    {
        $date['start']      = Request::input('from');
        $date['end']        = Request::input('to');
        $data['period']     = Request::input('report_period');
        $data['start_date'] = dateFormat(Report::checkDatePeriod($data['period'], $date)['start_date']);
        $data['end_date']   = dateFormat(Report::checkDatePeriod($data['period'], $date)['end_date']);
        
        return json_encode($data);
    }

    /* 
    *
    *--------- PRODUCTS REPORTS --------------- 
    *
    *
    */
	
    public function monthlysale()
    {
    	$end    = $this->endDate();
        $start  = $this->startDate();
        $shop_id = $this->checkuser('user_shop');
        $data['_sales'] = SalesReport::monthreport($shop_id, $start, $end);
    	return view('member.reports.sale.month',$data);
    }
    
    public function monthlysaleAjax()
    {
    	$start = date('Y-m-d', strtotime(Request::input("start")));
    	$end = date('Y-m-d', strtotime(Request::input("end")));
    	$shop_id = $this->checkuser('user_shop');
    	$data['_sales'] = SalesReport::SalesReportBy("month",$shop_id,$start, $end);
    	return view('member.reports.sale.month_table',$data);
    }
    
    public function saleProduct()
    {
        $end        = $this->endDate();
        $start      = $this->startDate();
        $shop_id    = $this->checkuser('user_shop');
        $data       = SalesReport::productreport($shop_id,$start, $end);
        // if($data)
        $data['start'] = date('m/d/Y', strtotime($start));
        $data['end']   = date('m/d/Y', strtotime($end));
    	return view("member.reports.sale.product",$data);
    }
    
    public function variantProduct()
    {
    	$end    = $this->endDate();
        $start  = $this->startDate();
        $shop_id= $this->checkuser('user_shop');
        $data   = SalesReport::variantreport($shop_id, $start, $end);
        $data['start'] = date('m/d/Y', strtotime($start));
        $data['end'] = date('m/d/Y', strtotime($end));
        
    	return view("member.reports.sale.product_variant",$data);
    }
    
    public function saleCustomer()
    {
        $end    = $this->endDate();
        $start  = $this->startDate();
        $shop_id= $this->checkuser('user_shop');
        
        $data   = SalesReport::SalesReportBy('customer',$shop_id, $start, $end);
        $data['start'] = date('m/d/Y', strtotime($start));
        $data['end'] = date('m/d/Y', strtotime($end));
    	return view("member.reports.sale.customer",$data);
    }
    
    public function saleByAjax($name)
    {
        $start      = date('Y-m-d', strtotime(Request::input("start")));
    	$end        = date('Y-m-d', strtotime(Request::input("end")));
    	$shop_id    = $this->checkuser('user_shop');
    	$data       = SalesReport::SalesReportBy($name, $shop_id, $start, $end);
    	
    	if($data == 'no data')
        {
    	    return $data;
    	}
    	else
        {
    	    
    	    return view('member.reports.sale.' .$name .'_table',$data);
    	}
    	
    }
    
    public function customerOverTime()
    {
        $end    = $this->endDate();
        $start  = $this->startDate();
        $shop_id= $this->checkuser('user_shop');
        $data['_sales'] = SalesReport::monthlysale($shop_id, $start, $end);
        // dd($data);
        
        return view('member.reports.customer.customerOverTime',$data);
    }
    
    public function customerOTajax()
    {
        $start = date('Y-m-d', strtotime(Request::input("start")));
    	$end = date('Y-m-d', strtotime(Request::input("end")));
    	$shop_id = $this->checkuser('user_shop');
    	$data['_sales'] = SalesReport::monthlysale($shop_id, $start, $end);
    	return view('member.reports.customer.customerOTTable',$data);
    }
    public function pdfreport($name = '', $start = '00/00/0000', $end = '00/00/0000'){
        $start = date('Y-m-d', strtotime($start));
        $end = date('Y-m-d', strtotime($end));
        $shop_id = $this->checkuser('user_shop');
        $blade = '';
        $data = array();
        switch($name){
            case 'month':
                $blade = 'pdfmonth';
                $data = SalesReport::monthlysale($shop_id, $start, $end);
                break;
        }
        $view = 'member.reports.'.$blade;
        // dd($data);
        //return view($view, $data);
        $pdf = PDF::loadView($view,$data);
        return $pdf->stream('Paycheque.pdf');
    }

    /* 
    *
    *--------- ACCOUNTING REPORTS --------------- 
    *
    *
    */

    public function accounting_sale()
    {
        $data =[];

        $report_code = 'accounting_sales_report';
        $data['field_checker'] = $this->report_field_checker_seed($report_code);
        $data['head_title'] = 'Sales Report';
        $data['head_icon'] = 'fa fa-area-chart';
        $data['head_discription'] = 'Account Sales Report';
        $data['head'] = $this->report_header($data);
        $data['action'] = '/member/report/accounting/sale/get/report/customer';
        $data['report_code'] = $report_code;
        $data['table_header'] = Report::sales_report();
        $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id);

        return view('member.reports.accounting.sales', $data);
    }
    
    public function report_header($data)
    {
        return view('member.reports.head', $data);
    }
    public function report_field_checker_seed($filter = 'accounting_sales_report')
    {
        $shop_id = $this->user_info->shop_id; 
        $table_header = Report::sales_report($filter);
        foreach ($table_header as $key => $value) 
        {
            $count = DB::table('tbl_report_field')->where('report_field_shop', $shop_id)
            ->where('report_field_type', '=', $filter)
            ->where('report_field_module', '=', $key)
            ->count();
            if($count == 0)
            {
                $insert['report_field_shop'] = $shop_id;
                $insert['report_field_module'] = $key;
                $insert['report_field_label'] = $value;
                $insert['report_field_type'] = $filter;
                DB::table('tbl_report_field')->insert($insert);
            }
        }

        $data['report_field'] = Tbl_report_field::where('report_field_shop', '=', $shop_id)
        ->orderBy('report_field_position', 'ASC')
        ->where('report_field_archive', '=', 0)
        ->where('report_field_type', $filter)
        ->get()
        ->keyBy('report_field_module');
        $data['filter'] = $filter;
        $data['report_field_default'] = Report::sales_report($filter);
        return view('member.reports.field.check', $data);
    }

    public function accounting_sale_filter_edit()
    {
        $shop_id = $this->user_info->shop_id; 
        $report_field_module = Request::input('report_field_module');
        $report_field_position = Request::input('report_field_position');
        $report_field_type = Request::input('report_field_type');
        if($report_field_module)
        {
            $table_header = Report::sales_report($report_field_type);
            foreach ($table_header as $key => $value) 
            {
                if(!isset($report_field_module[$key]))
                {
                    $update['report_field_archive'] = 1;
                }
                else
                {
                    $update['report_field_archive'] = 0;
                }

                if(isset($report_field_position[$key]))
                {
                    $update['report_field_position'] = $report_field_position[$key];
                    Tbl_report_field::where('report_field_shop', $shop_id)
                    ->where('report_field_module', $key)
                    ->where('report_field_type', $report_field_type)
                    ->update($update);
                }
            }
        }
        $data['status'] = 'Success';
        $data['message'] = 'Nice';

        return json_encode($data);
    }

    public function accounting_sale_report_view()
    {
        // die(var_dump(Request::input('adj_warehouse_id')));
        $period         = Request::input('report_period');
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $report_type = Request::input('report_type');
        $report_field_type = Request::input('report_field_type');
        $data['report_type'] = $report_type;
        $shop_id = $this->user_info->shop_id; 
        $where_in[0] = 'customer';
        // $where_in[1] = 'vendor';
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        // ->where('jline_warehouse_id',Request::input('adj_warehouse_id'))
        $data['sales'] = Tbl_journal_entry_line::account()
                                            ->item()
                                            ->journal()
                                            ->selectsales()
                                            ->where('je_shop_id', $shop_id)
                                            ->customerorvendor()
                                            ->whereRaw("DATE(je_entry_date) >= '".$data['from']."'")
                                            ->whereRaw("DATE(je_entry_date) <= '".$data['to']."'")
                                            ->concatum()
                                            ->amount()
                                            ->whereIn('jline_name_reference', $where_in)
                                            ->get()
                                            ->keyBy('jline_id');
        $data['sales_by_customer'] = [];
        // die(var_dump($data['sales']));
        foreach($data['sales'] as $key => $value)
        {
            $other_details = $this->get_other_details($value->je_reference_module,$value->je_reference_id,$value->item_id);
            $cust_qty = $this->get_sales_quantity($value->je_reference_module,$value->je_reference_id,$value->item_id);
            if(Request::input('adj_warehouse_id'))
            {
                if(Request::input('adj_warehouse_id') == $other_details['warehouse_id'])
                {
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id] = $value ;
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->item_rate = $other_details['item_rate'];
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->warehouse = $other_details['warehouse'];
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->je_entry_date = $other_details['txn_date'];
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->sales_quantity = $cust_qty;
                    $data['customer_info'][$value->jline_name_id] = $value->full_name;        
                }
            }
            else
            {
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id] = $value ;
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->item_rate = $other_details['item_rate'];
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->warehouse = $other_details['warehouse'];
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->je_entry_date = $other_details['txn_date'];
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->sales_quantity = $cust_qty;
                $data['customer_info'][$value->jline_name_id] = $value->full_name;                
            }
        }

        $data['sales_by_item'] = [];
        $data['category'] = [];
        $data['category_w'] = [];
        foreach($data['sales'] as $key => $value)
        {   
            // dd($value);
            $other_details = $this->get_other_details($value->je_reference_module,$value->je_reference_id,$value->item_id);
            $data['sales_by_item'][$value->jline_item_id][$value->jline_id] = $value;
            $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->sales_quantity = $this->get_sales_quantity($value->je_reference_module,$value->je_reference_id,$value->item_id);
            $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->item_rate = $other_details['item_rate'];
            $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->warehouse = $other_details['warehouse'];
            $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->je_entry_date = $other_details['txn_date'];
            $data['item_info'][$value->jline_item_id] = $value->item_name ." (".$value->item_sku.")";
            $data['category'][$value->item_category_id][$value->jline_item_id][$value->jline_id] = $value;
            $data['category_w'][$value->item_category_id] = $value->item_category_id;
        }
        $data['category_data'] = Tbl_category::whereIn('type_id', $data['category_w'])
        ->get()
        ->keyBy('type_id');

        $data['report_field'] = Tbl_report_field::where('report_field_shop', '=', $shop_id)
        ->orderBy('report_field_position', 'ASC')
        ->where('report_field_archive', '=', 0)
        ->where('report_field_type', '=', $report_field_type)
        ->get()
        ->keyBy('report_field_module');
        // dd($data['report_field']);
        if($report_field_type == 'accounting_sales_report')
        {
            $data['head_title'] = 'Sales Report By Customer Detail';
            $view =  'member.reports.output.sale';
            $type = 'Sale';
        }
        else if($report_field_type == 'accounting_sales_report_item')
        {
            $data['head_title'] = 'Sales Report By Item Detail';
            $view =  'member.reports.output.item';
            $type = 'Item';
        }

        // dd($data['sales_by_item']);

        return Report::check_report_type($report_type, $view, $data, 'Sales_Report_By_'.$type.Carbon::now());
    }
    public function accounting_sale_report_view_customer()
    {
        // die(var_dump(Request::input('adj_warehouse_id')));
        $period         = Request::input('report_period');
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $report_type = Request::input('report_type');
        $report_field_type = Request::input('report_field_type');
        $data['report_type'] = $report_type;
        $shop_id = $this->user_info->shop_id; 
        $where_in[0] = 'customer';
        // $where_in[1] = 'vendor';
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        // ->where('jline_warehouse_id',Request::input('adj_warehouse_id'))
        $data['sales'] = Tbl_journal_entry_line::account()
                                            ->item()
                                            ->journal()
                                            ->selectsales()
                                            ->where('je_shop_id', $shop_id)
                                            ->customerorvendor()
                                            ->whereRaw("DATE(je_entry_date) >= '".$data['from']."'")
                                            ->whereRaw("DATE(je_entry_date) <= '".$data['to']."'")
                                            ->concatum()
                                            ->amount()
                                            ->whereIn('jline_name_reference', $where_in)
                                            ->get()
                                            ->keyBy('jline_id');
        $data['sales_by_customer'] = [];
        // die(var_dump($data['sales']));
        foreach($data['sales'] as $key => $value)
        {
            $other_details = $this->get_other_details($value->je_reference_module,$value->je_reference_id,$value->item_id);
            $cust_qty = $this->get_sales_quantity($value->je_reference_module,$value->je_reference_id,$value->item_id);
            if(Request::input('adj_warehouse_id'))
            {
                if(Request::input('adj_warehouse_id') == $other_details['warehouse_id'])
                {
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id] = $value ;
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->item_rate = $other_details['item_rate'];
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->warehouse = $other_details['warehouse'];
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->je_entry_date = $other_details['txn_date'];
                    $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->sales_quantity = $cust_qty;
                    $data['customer_info'][$value->jline_name_id] = $value->full_name;        
                }
            }
            else
            {
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id] = $value ;
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->item_rate = $other_details['item_rate'];
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->warehouse = $other_details['warehouse'];
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->je_entry_date = $other_details['txn_date'];
                $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->sales_quantity = $cust_qty;
                $data['customer_info'][$value->jline_name_id] = $value->full_name;                
            }
        }

        $data['sales_by_item'] = [];
        $data['category'] = [];
        $data['category_w'] = [];
        // foreach($data['sales'] as $key => $value)
        // {   
        //     // dd($value);
        //     $other_details = $this->get_other_details($value->je_reference_module,$value->je_reference_id,$value->item_id);
        //     $data['sales_by_item'][$value->jline_item_id][$value->jline_id] = $value;
        //     $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->sales_quantity = $this->get_sales_quantity($value->je_reference_module,$value->je_reference_id,$value->item_id);
        //     $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->item_rate = $other_details['item_rate'];
        //     $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->warehouse = $other_details['warehouse'];
        //     $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->je_entry_date = $other_details['txn_date'];
        //     $data['item_info'][$value->jline_item_id] = $value->item_name ." (".$value->item_sku.")";
        //     $data['category'][$value->item_category_id][$value->jline_item_id][$value->jline_id] = $value;
        //     $data['category_w'][$value->item_category_id] = $value->item_category_id;
        // }
        $data['category_data'] = Tbl_category::whereIn('type_id', $data['category_w'])
        ->get()
        ->keyBy('type_id');

        $data['report_field'] = Tbl_report_field::where('report_field_shop', '=', $shop_id)
        ->orderBy('report_field_position', 'ASC')
        ->where('report_field_archive', '=', 0)
        ->where('report_field_type', '=', $report_field_type)
        ->get()
        ->keyBy('report_field_module');
        // dd($data['report_field']);
        if($report_field_type == 'accounting_sales_report')
        {
            $data['head_title'] = 'Sales Report By Customer Detail';
            $view =  'member.reports.output.sale';
            $type = 'Sale';
        }
        else if($report_field_type == 'accounting_sales_report_item')
        {
            $data['head_title'] = 'Sales Report By Item Detail';
            $view =  'member.reports.output.item';
            $type = 'Item';
        }

        // dd($data['sales_by_item']);

        return Report::check_report_type($report_type, $view, $data, 'Sales_Report_By_'.$type.Carbon::now());
    }

    public function accounting_sale_report_view_items()
    {
        // die(var_dump(Request::input('adj_warehouse_id')));
        $period         = Request::input('report_period');
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $report_type = Request::input('report_type');
        $report_field_type = Request::input('report_field_type');
        $data['report_type'] = $report_type;
        $shop_id = $this->user_info->shop_id; 
        $where_in[0] = 'customer';
        // $where_in[1] = 'vendor';
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        //arcy
        $warehouse_id = Request::input('adj_warehouse_id');
        $data['sales'] = Tbl_journal_entry_line::account()
                                            ->item()
                                            ->journal()
                                            ->selectsales()
                                            ->where('je_shop_id', $shop_id)
                                            ->customerorvendor()
                                            ->whereRaw("DATE(je_entry_date) >= '".$data['from']."'")
                                            ->whereRaw("DATE(je_entry_date) <= '".$data['to']."'")
                                            ->concatum()
                                            ->amount()
                                            ->whereIn('jline_name_reference', $where_in)
                                            ->get()
                                            ->keyBy('jline_id');
        // $data['sales_by_customer'] = [];
        // // die(var_dump($data['sales']));
        // foreach($data['sales'] as $key => $value)
        // {
        //     $other_details = $this->get_other_details($value->je_reference_module,$value->je_reference_id,$value->item_id);
        //     $cust_qty = $this->get_sales_quantity($value->je_reference_module,$value->je_reference_id,$value->item_id);
        //     if(Request::input('adj_warehouse_id'))
        //     {
        //         if(Request::input('adj_warehouse_id') == $other_details['warehouse_id'])
        //         {
        //             $data['sales_by_customer'][$value->jline_name_id][$value->jline_id] = $value ;
        //             $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->item_rate = $other_details['item_rate'];
        //             $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->warehouse = $other_details['warehouse'];
        //             $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->je_entry_date = $other_details['txn_date'];
        //             $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->sales_quantity = $cust_qty;
        //             $data['customer_info'][$value->jline_name_id] = $value->full_name;        
        //         }
        //     }
        //     else
        //     {
        //         $data['sales_by_customer'][$value->jline_name_id][$value->jline_id] = $value ;
        //         $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->item_rate = $other_details['item_rate'];
        //         $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->warehouse = $other_details['warehouse'];
        //         $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->je_entry_date = $other_details['txn_date'];
        //         $data['sales_by_customer'][$value->jline_name_id][$value->jline_id]->sales_quantity = $cust_qty;
        //         $data['customer_info'][$value->jline_name_id] = $value->full_name;                
        //     }
        // }

        $data['sales_by_item'] = [];
        $data['category'] = [];
        $data['category_w'] = [];
        foreach($data['sales'] as $key => $value)
        {   
            // dd($value);
            $other_details = $this->get_other_details($value->je_reference_module,$value->je_reference_id,$value->item_id, $warehouse_id);
            if($other_details)
            {
                $data['sales_by_item'][$value->jline_item_id][$value->jline_id] = $value;
                $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->sales_quantity = $this->get_sales_quantity($value->je_reference_module,$value->je_reference_id,$value->item_id);
                $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->item_rate = $other_details['item_rate'];
                $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->warehouse = $other_details['warehouse'];
                $data['sales_by_item'][$value->jline_item_id][$value->jline_id]->je_entry_date = $other_details['txn_date'];
                $data['item_info'][$value->jline_item_id] = $value->item_name ." (".$value->item_sku.")";
                $data['category'][$value->item_category_id][$value->jline_item_id][$value->jline_id] = $value;
                $data['category_w'][$value->item_category_id] = $value->item_category_id;
            }
        }
        $data['category_data'] = Tbl_category::whereIn('type_id', $data['category_w'])
        ->get()
        ->keyBy('type_id');

        $data['report_field'] = Tbl_report_field::where('report_field_shop', '=', $shop_id)
        ->orderBy('report_field_position', 'ASC')
        ->where('report_field_archive', '=', 0)
        ->where('report_field_type', '=', $report_field_type)
        ->get()
        ->keyBy('report_field_module');
        // dd($data['report_field']);
        if($report_field_type == 'accounting_sales_report')
        {
            $data['head_title'] = 'Sales Report By Customer Detail';
            $view =  'member.reports.output.sale';
            $type = 'Sale';
        }
        else if($report_field_type == 'accounting_sales_report_item')
        {
            $data['head_title'] = 'Sales Report By Item Detail';
            $view =  'member.reports.output.item';
            $type = 'Item';
        }

        // dd($data['sales_by_item']);

        return Report::check_report_type($report_type, $view, $data, 'Sales_Report_By_'.$type.Carbon::now());
    }
    public function get_sales_quantity($module_name, $module_id, $item_id)
    {
        $return = '';
        if($module_name == 'invoice' || $module_name == 'sales-receipt' || $module_name == 'sales_receipt' || $module_name == 'sales_invoice')
        {
            $return = DB::table('tbl_customer_invoice_line')->where('invline_inv_id',$module_id)->where('invline_item_id',$item_id)->value('invline_orig_qty'); 
        }
        return $return;
    }
    public function get_other_details($module_name, $module_id, $item_id, $warehouse_id = null)
    {
        $return['item_rate'] = 0;
        $return['warehouse'] = 'none';
        $return['warehouse_id'] = '';
        $return['txn_num'] = '';
        $return['txn_date'] = '';
        if($module_name == 'invoice' || $module_name == 'sales-receipt' || $module_name == 'sales_receipt' || $module_name == 'sales_invoice')
        {
            $return['item_rate'] = DB::table('tbl_customer_invoice_line')->where('invline_inv_id',$module_id)->where('invline_item_id',$item_id)->value('invline_rate');
            $return['txn_num'] = DB::table('tbl_customer_invoice')->where('inv_id',$module_id)->value('transaction_refnum');
            $date = DB::table('tbl_customer_invoice')->where('inv_id',$module_id)->value('inv_date');
            $return['txn_date'] = date('F d, Y', strtotime($date));
            $module_name = $module_name == 'invoice' ? 'sales_invoice' : ($module_name == 'sales-receipt' ? 'sales_receipt' : '');
            $warehouse_data = Tbl_acctg_transaction_list::acctgtransaction()->where('transaction_ref_name',$module_name)
                                                             ->where('transaction_ref_id',$module_id)
                                                             ->first();
            if($warehouse_id)
            {
                $warehouse_data = Tbl_acctg_transaction_list::acctgtransaction()->where('transaction_ref_name',$module_name)
                                                                 ->where('transaction_ref_id',$module_id)
                                                                 ->where('transaction_warehouse_id', $warehouse_id)
                                                                 ->first();
            }
            if($warehouse_data)
            {
                $return['warehouse'] = $warehouse_data->warehouse_name;
                $return['warehouse_id'] = $warehouse_data->warehouse_id;
            }
            else
            {
                $return = null;
            }
        }
        return $return;
    }
    public function accounting_sale_items()
    {
        $data =[];
        $report_code = 'accounting_sales_report_item';
        $data['field_checker'] = $this->report_field_checker_seed($report_code );
        $data['head_title'] = 'Sales Report - Item ';
        $data['head_icon'] = 'fa fa-area-chart';
        $data['head_discription'] = 'Account Sales Report';
        $data['head'] = $this->report_header($data);
        $data['action'] = '/member/report/accounting/sale/get/report/items';
        $data['report_code'] = $report_code;
        $data['table_header'] = Report::sales_report($report_code);
        $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id);

        return view('member.reports.accounting.sales_item', $data);
    }
    public function general_ledger()
    {
        $data = [];
        $report_code = 'accounting_general_ledger';
        $data['field_checker'] = $this->report_field_checker_seed($report_code);
        $data['head_title'] = 'General Ledger';
        $data['head_icon'] = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head'] = $this->report_header($data);
        $data['action'] = '/member/report/accounting/general/ledger/get';
        $shop_id = $this->user_info->shop_id; 
        $data['report_code'] = $report_code;
        return view('member.reports.accounting.general_ledger', $data);
    }

    public function general_ledger_get()
    {
        $period         = Request::input('report_period');
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
        
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'General Ledger';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type = Request::input('report_type');
        $report_field_type = Request::input('report_field_type');

        $shop_id = $this->user_info->shop_id; 

        $data['report_field'] = Tbl_report_field::where('report_field_shop', '=', $shop_id)
        ->orderBy('report_field_position', 'ASC')
        ->where('report_field_archive', '=', 0)
        ->where('report_field_type', '=', $report_field_type)
        ->get()
        ->keyBy('report_field_module');

        $data['entry_line'] = Tbl_journal_entry_line::account()
            ->where('account_shop_id', $shop_id)
            ->customerorvendorv2()
            ->amount()
            ->groupBy('jline_account_id')
            ->groupBy('jline_je_id')
            ->journal()
            ->whereRaw("DATE(je_entry_date) >= '".$data['from']."'")
            ->whereRaw("DATE(je_entry_date) <= '".$data['to']."'")
            ->get();
        $data['chart_of_account'] = [];
        $data['chart_of_account_data'] = [];
        foreach($data['entry_line'] as $key => $value)
        {
            $data['chart_of_account'][$value->chart_type_id] = $value->account_name; 
            $data['chart_of_account_data'][$value->chart_type_id][$value->jline_id] = $value;
        }
        $view =  'member.reports.output.general_ledger'; 
        return Report::check_report_type($report_type, $view, $data, 'General-Ledger'.Carbon::now());
    }

    public function profit_loss()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Profit and Loss';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounting/profit_loss';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $filter[11] = 'Income';
        $filter[12] = 'Cost of Goods Sold';
        $filter[13] = 'Expense';
        $filter[14] = 'Other Expense';
        $filter[15] = 'Other Income';

        $shop_id         = $this->user_info->shop_id; 

        $data['_account_per_year'] = Tbl_journal_entry::selectRaw('year(je_entry_date) as entry_year')->groupBy('entry_year')->get()->keyBy('entry_year');

        foreach ($data['_account_per_year'] as $key => $value_year)
        {
           $data['_account_per_year'][$key]['_account'] = Tbl_chart_account_type::whereIn('chart_type_name', $filter)
            ->get()->keyBy('chart_type_name');

             foreach($data['_account_per_year'][$key]['_account'] as $key_year => $value)
            {
                $data['_account_per_year'][$key]['_account'][$key_year]->account_details = Tbl_journal_entry_line::account()
                                                        ->journal()
                                                        ->totalAmount()
                                                        ->where('chart_type_id', $value->chart_type_id)
                                                        ->where('account_shop_id', $shop_id)
                                                        ->where('je_entry_date','LIKE',$value_year->entry_year.'%')
                                                        ->get();
            }
        }
        // dd($data['_account_per_year']);
       
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.profit_loss'; 
            return Report::check_report_type($report_type, $view, $data, 'Profit_and_Loss-'.Carbon::now(), 'portrait');
        }
        else
        {
            return view('member.reports.accounting.profit_loss', $data);
        }
        
    }
    public function customer_auto_updates()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $selected_category = Request::input('selected_category');
        $data['head_title'] = $selected_category != 'all'? ucwords($selected_category) : 'Customer List';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/customer_auto_updates';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
        $data['migo_customization'] = AccountingTransaction::settings($this->user_info->shop_id, "migo_customization");

        $selected_category_type = Request::input('selected_category_type');
        $data['_customer'] = Tbl_customer::otherinfo()->address()
                                         ->where("shop_id", $this->user_info->shop_id)
                                         ->where("tbl_customer.archived", 0)
                                         ->orderBy('first_name', "ASC")
                                         ->groupBy('tbl_customer.customer_id');
        if($selected_category != 'all')
        {
            $data['_customer'] = $data['_customer']->where("customer_category", $selected_category);
        }
        if($selected_category_type != 'all')
        {
            $data['_customer'] = $data['_customer']->where("customer_category_type", $selected_category_type);
        }
        $data['_customer'] = $data['_customer']->get();
        foreach($data['_customer'] as $key=>$customer)
        {
            $data['_customer'][$key]->updates_history_category = array();
            if($customer->customer_category_history)
            {
                $data['_customer'][$key]->updates_history_category = unserialize($customer->customer_category_history);
            }
        }

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.customer_auto_updates'; 
            return Report::check_report_type($report_type, $view, $data, 'customer_auto_updates-'.Carbon::now());
        }
        else
        {
            return view('member.reports.accounting.customer_auto_updates', $data);
        }  
    }
    public function customer_list()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Customer List';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounting/customer_list';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
        $data['migo_customization'] = AccountingTransaction::settings($this->user_info->shop_id, "migo_customization");

        $selected_category = Request::input('selected_category');
        $selected_category_type = Request::input('selected_category_type');
        $data['_customer'] = Tbl_customer::balanceJournal()
                                         ->where("shop_id", $this->user_info->shop_id)
                                         ->where("archived", 0)
                                         ->orderBy('first_name', "ASC");
        if($selected_category != 'all')
        {
            $data['_customer'] = $data['_customer']->where("customer_category", $selected_category);
        }
        if($selected_category_type != 'all')
        {
            $data['_customer'] = $data['_customer']->where("customer_category_type", $selected_category_type);
        }
        $data['_customer'] = $data['_customer']->get();
        foreach($data['_customer'] as $key=>$customer)
        {
            $data['_customer'][$key]->customer_journal = Tbl_journal_entry_line::journal()->customerOrVendor()->account()->customerOnly()
                                                        ->where("jline_name_id", $customer->customer_id)
                                                        ->where("chart_type_name", 'Accounts Receivable')
                                                        ->whereRaw("DATE(je_entry_date) >= '".$data['from']."'")
                                                        ->whereRaw("DATE(je_entry_date) <= '".$data['to']."'")
                                                        ->get();

            $data['_customer'][$key]->balance          = collect($data['_customer'][$key]->customer_journal)->sum('amount');
            $data['_customer'][$key]->customer_category = str_replace("-", " ", ucwords($customer->customer_category));
            $data['_customer'][$key]->customer_category_type = str_replace("-", " ", strtoupper($customer->customer_category_type));
        }   

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.customer_list'; 
            return Report::check_report_type($report_type, $view, $data, 'Customer_list-'.Carbon::now());
        }
        else
        {
            return view('member.reports.accounting.customer_list', $data);
        }
    }

    public function vendor_purchase_order()
    { 
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Purchase Order by Vendor';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/vendor_purchase_order';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_vendor'] = Tbl_vendor::where('vendor_shop_id', $this->user_info->shop_id)->where('archived',0)->get();
        foreach ($data['_vendor'] as $key => $value) 
        {
            $po = Tbl_purchase_order::purchase_item()
                                    ->selectRaw("*, poline_orig_qty, poline_received_qty,poline_rate, sum((poline_orig_qty - poline_received_qty) * poline_rate) as rem_balance, sum(poline_orig_qty * poline_rate) as total_po")
                                    ->whereBetween('po_date',[$data['from'],$data['to']])
                                    ->where('po_vendor_id', $value->vendor_id)
                                    ->where('po_shop_id', $this->user_info->shop_id)
                                    ->groupBy('po_id')
                                    ->get();
            $data['_vendor'][$value->vendor_id] = $value;
            $data['_vendor'][$value->vendor_id]->purchase_order = $po;
            $data['_vendor'][$value->vendor_id]->total = collect($po)->sum('total_po');
            $data['_vendor'][$value->vendor_id]->balance = collect($po)->sum('rem_balance');
        }
        // dd($data['_vendor']);
         /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.purchase.po_vendor.po_vendor'; 
            return Report::check_report_type($report_type, $view, $data, 'Vendor_ourchase_order-'.Carbon::now());
        }
        else
        {
            return view('member.reports.purchase.po_vendor.po_vendor_index', $data);
        }
    }
    public function vendor_list()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Vendor List';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounting/vendor_list';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $sort_by = Request::input("sort_by");

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_vendor'] = Tbl_vendor::address()->balanceJournal()->where("vendor_shop_id", $this->user_info->shop_id)->where("archived", 0)->orderBy('ven_billing_city', $sort_by)->get();
        foreach($data['_vendor'] as $key => $vendor)
        {
            $data['_vendor'][$key]->vendor_address = $vendor->ven_billing_street." ".$vendor->ven_billing_city." ".$vendor->ven_billing_state." ".$vendor->ven_billing_zipcode;
            $journal = Tbl_journal_entry_line::journal()->customerOrVendor()->account()->vendorOnly()
                                                        ->where("jline_name_id", $vendor->vendor_id)
                                                        ->where("chart_type_name", 'Accounts Payable')
                                                        ->whereRaw("DATE(je_entry_date) >= '".$data['from']."'")
                                                        ->whereRaw("DATE(je_entry_date) <= '".$data['to']."'")
                                                        ->get();
            $vendor_journal = array();
            foreach ($journal as $keyv => $value) 
            {
                $other_details = $this->purchase_other_details($value->je_reference_module,$value->je_reference_id);
                $value->je_reference_module = $other_details['txn_name'];
                $value->je_reference_id = $other_details['txn_num'];
                $value->je_entry_date = $other_details['txn_date'];
                $vendor_journal[$keyv] = $value;
            }
            $data['_vendor'][$key]->vendor_journal = $vendor_journal;
            $data['_vendor'][$key]->balance        = collect($data['_vendor'][$key]->vendor_journal)->sum('amount');
        }
        // dd($data['_vendor']);
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.vendor_list'; 
            return Report::check_report_type($report_type, $view, $data, 'Vendor_list-'.Carbon::now());
        }
        else
        {
            return view('member.reports.accounting.vendor_list', $data);
        }
    }
    public function purchase_other_details($module_name, $module_id)
    {
        $return['txn_date'] = '';
        $return['txn_name'] = '';
        $return['txn_num'] = '';
        if($module_name == 'bill')
        {
            $get = Tbl_bill::selectRaw('*, tbl_bill.transaction_refnum as bill_refnum, tbl_receive_inventory.transaction_refnum as ri_refnum')->receive()->where("bill_id", $module_id)->first();
            if($get)
            {
                $return['txn_date'] = date('F d, Y', strtotime($get->bill_date));
                $return['txn_name'] = 'bill';
                $return['txn_num'] = $get->bill_refnum;
                if($get->bill_ri_id)
                {
                    $return['txn_date'] = date('F d, Y', strtotime($get->ri_date));
                    $return['txn_name'] = 'receive-inventory';
                    $return['txn_num'] = $get->ri_refnum;
                }
            }
        }
        return $return;
    }
    public function sales_bank_interest()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Sales with Bank Interest';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/sales_bank_interest';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_sales_bank_interest'] = AccountingTransaction::sales_bank_interest($this->user_info->shop_id, $data['from'], $data['to']);
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.sales_bank_interest'; 
            return Report::check_report_type($report_type, $view, $data, 'sales_bank_interest-'.Carbon::now());
        }
        else
        {
            return view('member.reports.sales_bank_interest.sales_bank_interest', $data);
        }
    }
    public function sales_gain_item()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Sales Gain By Item';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/sales_gain_item';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_gain'] = AccountingTransaction::get_sales_gain_item($this->user_info->shop_id, $data['from'], $data['to']);
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.sales_gain_item'; 
            return Report::check_report_type($report_type, $view, $data, 'sales_gain_item-'.Carbon::now());
        }
        else
        {
            return view('member.reports.sales_gain_item.sales_gain_item', $data);
        }
    }

    public function retain_credit()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Retain Credit by Customer Reports';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/retain_credit';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        
        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_customer'] = Customer::getAllCustomer();
        $data['_customer_credit'] = null;
        foreach ($data['_customer'] as $key => $value) 
        {
            $ret_cred = Tbl_credit_memo::selectRaw("*,tbl_credit_memo.transaction_refnum as cm_ref_num, tbl_receive_payment.transaction_refnum as rp_ref_num")
                                       ->rp()
                                       ->whereBetween("cm_date",[$data['from'], $data['to']])
                                       ->where("cm_customer_id", $value->customer_id)
                                       ->get();
            $amount = collect($ret_cred)->sum("cm_amount");
            if($amount)
            {
                $data['_customer_credit'][$key] = $value;
                $data['_customer_credit'][$key]->total_retain_credit = $amount;
                $data['_customer_credit'][$key]->retain_credit = $ret_cred;                
            }
        }

        if($report_type && !$load_view)
        {
            $view =  'member.reports.retain_credit.retain_credit_output'; 
            return Report::check_report_type($report_type, $view, $data, 'sales_gain_item-'.Carbon::now());
        }
        else
        {
            return view('member.reports.retain_credit.retain_credit_header', $data);
        }
    }
    public function item_status()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Item Status';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounting/item_list';
        
        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_item_status'] = Warehouse2::get_item_status($this->user_info->shop_id, $warehouse_id, $data['from'], $data['to']);
         /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.item_status'; 
            return Report::check_report_type($report_type, $view, $data, 'item_status-'.Carbon::now());
        }
        else
        {
            return view('member.reports.item_status.item_status', $data);
        }
    }
    public function sales_cost_price_history()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Item/Service List';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/sales_cost_price_history';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['item_costing'] = AccountingTransaction::settings($this->user_info->shop_id, 'item_new_cost');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_history'] = Item::get_item_price_history_v2($this->user_info->shop_id, $data['from'], $data['to'], $warehouse_id);


        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.sales_cost_price_history'; 
            return Report::check_report_type($report_type, $view, $data, 'sales_cost_price_history-'.Carbon::now());
        }
        else
        {
            return view('member.reports.sales_cost_price_history.sales_cost_price_history', $data);
        }

    }
    public function sales_representative()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Sales Report per Sales Representative';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/sales_representative';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_sales_rep'] = Tbl_sales_representative::where('sales_rep_shop_id', $this->user_info->shop_id)->get();
        // dd($data['from'],$data['to']);
        foreach ($data['_sales_rep'] as $key => $value) 
        {
            $data['_sales_rep'][$key] = $value;
            $data['_sales_rep'][$key]->sales = Tbl_customer_invoice::customer()
                                                                   ->where('inv_sales_rep_id', $value->sales_rep_id)
                                                                   ->whereBetween('inv_date',[$data['from'], $data['to']])
                                                                   ->get();
            $data['_sales_rep'][$key]->sales_amount = collect($data['_sales_rep'][$key]->sales)->sum("inv_overall_price");
        }
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.sales_representative.sales_representative_output'; 

            return Report::check_report_type($report_type, $view, $data, 'sales_report_per_sales_representative-'.Carbon::now());
        }
        else
        {
            return view('member.reports.sales_representative.sales_representative_head', $data);
        }
    }
    public function item_list()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Item/Service List';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounting/item_list';
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

        $data['w_type'] = Warehouse2::get_warehouse_type(Warehouse2::get_current_warehouse($this->user_info->shop_id));

        $data['_warehouse'] = Warehouse2::get_user_warehouse_access($this->user_info->shop_id, $this->user_info->user_id);
        if($this->user_info->shop_id == 81)
        {
            $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id);
        }
        $_item         = Tbl_item::type()
                                 ->where("shop_id", $this->user_info->shop_id)
                                 ->where("tbl_item.archived", 0);
        // dd(Request::input("selected_category"), Request::input("selected_item_type"));
        if(Request::input("selected_category") && Request::input("selected_category") != 0)
        {
            $_cat[Request::input("selected_category")] = Request::input("selected_category"); 
            $check_parent_category = Item::check_category(Request::input("selected_category"), $_cat);
            $_item = $_item->whereIn("item_category_id",$check_parent_category);
        }
        if(Request::input("selected_item_type") && Request::input("selected_item_type") != 0)
        {
            $_item = $_item->where("tbl_item.item_type_id",Request::input("selected_item_type"));            
        }
        
        if(Request::input("search_item"))
        {  
            $search_keyword = Request::input("search_item");
            $_item->where(function($q) use ($search_keyword)
            {   
                $q->orWhere("tbl_item.item_id", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_sku", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_name", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_sales_information", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_purchasing_information", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_price", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_cost", "LIKE", "%$search_keyword%");
                $q->orWhere("tbl_item.item_barcode", "LIKE", "%$search_keyword%");
            });
        }
        $data['_item'] = $_item->orderby('item_name')->get();
        foreach($data['_item'] as $key => $item)
        {
            $data['_item'][$key]->item_warehouse = Item::item_inventory_report($item->shop_id, $item->item_id, $data['from'], $data['to'], $this->user_info->user_id);
            $get_item_transit = Item::get_transit_item($this->user_info->shop_id, $item->item_id, $data['from'], $data['to'], $this->user_info->user_id);
            $data['_item'][$key]->pending_transit = $get_item_transit["pending_transit"];
            $data['_item'][$key]->in_transit = $get_item_transit["in_transit"];
        }
        
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.item_list'; 

            return Report::check_report_type($report_type, $view, $data, 'item_list-'.Carbon::now());
        }
        else
        {
            return view('member.reports.accounting.item_list', $data);
        }
    }
    public function dyandcy_annualreport()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Annual Sales Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/dyandcy/annualreport';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        
        $data['current_year'] = Request::input('report_year') ?? date('Y');

        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
        $data['_annualsales'] = array();

        $startdate = 2017;
        $enddate = date("Y");
        $years = range($startdate,$enddate);
        foreach($years as $keyyear => $year)
        {
            $datestart = $year.'-1-01';
            $dateend = $year.'-12-31';
            $data['_annualsales'][$keyyear]['yeardate'] = $year;
            $_sales = Tbl_customer_invoice_line::selectRaw('sum(invline_rate * invline_orig_qty) as sales_gross,
                                                sum(IF(invline_discount_type = "percent", (invline_rate * invline_orig_qty) * invline_discount, invline_discount)) as discount,
                                                sum((invline_rate - item_cost) * invline_orig_qty) as sales_netincome')
                                               ->invoice()->invoice_item()
                                               ->where('inv_shop_id', $this->user_info->shop_id)
                                               ->whereBetween('inv_date',[$datestart, $dateend])
                                               ->groupBy('inv_shop_id')
                                               ->get();
            $data['_annualsales'][$keyyear]['sales_gross'] = 0;
            $data['_annualsales'][$keyyear]['sales_netincome'] = 0;
            foreach ($_sales as $key => $value) 
            {
                $discount = $value->discount;
                $data['_annualsales'][$keyyear]['sales_gross'] = $value->sales_gross - $discount;
                $data['_annualsales'][$keyyear]['sales_netincome'] = $value->sales_netincome - $discount;
            }
        }
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.dyandcy.annual_report_output'; 

            return Report::check_report_type($report_type, $view, $data, 'dyandcysalesannual-'.Carbon::now());
        }
        else
        {
            return view('member.reports.dyandcy.annual_report_head', $data);
        }
    }
    public function dyandcy_monthlyreport()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Monthly Sales Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/dyandcy/monthlyreport';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $period         = 'custom';
        $startdate = 2017;
        $enddate = date("Y")+10;
        $years = range($startdate,$enddate);
        foreach($years as $key => $year)
        {
          $data['_year'][$key] = $year;
        }
        $data['current_year'] = Request::input('report_year') ?? date('Y');

        $date['start']  = Request::input('from')?? '01-01-'.$data['current_year'];
        $date['end']    = Request::input('to')?? '31-12-'.$data['current_year'];;
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
        $data['_monthlysales'] = array();
        // dd($res_date_start, $res_date_end);
        for ($i=1; $i <= 12 ; $i++) 
        {
            $datestart = $data['current_year'].'-'.$i.'-01';
            $dateend = $data['current_year'].'-'.$i.'-'.date('t',strtotime($datestart));
            $month = date('F', strtotime($datestart));
            // dd($datestart, $dateend);
            $data['_monthlysales'][$i]['monthdate'] = $month;
            $_sales = Tbl_customer_invoice_line::selectRaw('sum(invline_rate * invline_orig_qty) as sales_gross,
                                                sum(IF(invline_discount_type = "percent", (invline_rate * invline_orig_qty) * invline_discount, invline_discount)) as discount,
                                                sum((invline_rate - item_cost) * invline_orig_qty) as sales_netincome')
                                               ->invoice()->customer()->invoice_item()
                                               ->where('inv_shop_id', $this->user_info->shop_id)
                                               ->whereBetween('inv_date',[$datestart, $dateend])
                                               ->groupBy('inv_shop_id')
                                               ->get();
            $data['_monthlysales'][$i]['sales_gross'] = 0;
            $data['_monthlysales'][$i]['sales_netincome'] = 0;
            foreach ($_sales as $key => $value) 
            {
                $discount = $value->discount;
                $data['_monthlysales'][$i]['sales_gross'] = $value->sales_gross - $discount;
                $data['_monthlysales'][$i]['sales_netincome'] = $value->sales_netincome - $discount;
            }
        }

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.dyandcy.monthly_report_output'; 

            return Report::check_report_type($report_type, $view, $data, 'dyandcysalesmonthly-'.Carbon::now());
        }
        else
        {
            return view('member.reports.dyandcy.monthly_report_head', $data);
        }

    }
    public function dyandcy_weeklyreport()
    {
    	$data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Weekly Sales Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/dyandcy/weeklyreport';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = 'custom';
        $startdate = 2017;
        $enddate = date("Y")+10;
        $years = range($startdate,$enddate);
        foreach($years as $key => $year)
        {
          $data['_year'][$key] = $year;
        }

        $startweek = 1;
        $endweek = 53;
        $rangeweek = range($startweek,$endweek);
        foreach($rangeweek as $key => $week)
        {
          $data['_week'][$key] = $week;
        }
        $data['current_week'] = Request::input('report_week_no') ?? Carbon::now()->weekOfYear;
        $data['current_year'] = Request::input('report_week_year') ?? date('Y');

        $_date = new DateTime();
        $week_date = $_date->setISODate($data['current_year'], $data['current_week']);
        $res_date_start = $week_date->format('m/d/Y');
        $res_date_end = $week_date->modify('+6 days')->format('m/d/Y');
        $date['start']  = $res_date_start;
        $date['end']    = $res_date_end;
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
        $data['_weeklysales'] = array();
        $arr = [];
        // dd($res_date_start, $res_date_end);
        $period = CarbonPeriod::create(date('Y-m-d',strtotime($res_date_start)), date('Y-m-d',strtotime($res_date_end)));
        
        $ctr = 0;
        // Iterate over the period
        foreach ($period as $date) 
        {
            $data['_weeklysales'][$ctr]['salesdate'] = $date;
            $_sales = Tbl_customer_invoice_line::selectRaw('item_price, item_cost, invline_rate, invline_orig_qty, invline_discount_type, invline_discount')
                                               ->invoice()->customer()->invoice_item()
                                               ->where('inv_shop_id', $this->user_info->shop_id)
                                               ->where('inv_date',$data['_weeklysales'][$ctr]['salesdate'])
                                               ->get();
            $data['_weeklysales'][$ctr]['sales_gross'] = 0;
            $data['_weeklysales'][$ctr]['sales_netincome'] = 0;
            foreach ($_sales as $key => $value) 
            {

                $discount = $value->invline_discount;
                if($value->invline_discount_type == 'percent')
                {
                    $discount = ($value->invline_rate * $value->invline_orig_qty) * $value->invline_discount;
                }
                $data['_weeklysales'][$ctr]['sales_gross'] += $value->invline_rate * $value->invline_orig_qty - $discount;
                $data['_weeklysales'][$ctr]['sales_netincome'] += (($value->invline_rate - $value->item_cost) * $value->invline_orig_qty) - $discount;
            }
            $ctr++;
        }

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.dyandcy.weekly_report_output'; 

            return Report::check_report_type($report_type, $view, $data, 'dyandcysalesweekly-'.Carbon::now());
        }
        else
        {
            return view('member.reports.dyandcy.weekly_report_head', $data);
        }

    }
    public function dyandcy_dailysales()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Daily Sales Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/dyandcy/dailysales';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'today';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('from');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_item'] = Tbl_customer_invoice_line::invoice()->invoice_item()
                                                  ->where('inv_shop_id', $this->user_info->shop_id)
                                                  ->where('inv_date',$data['from'])
                                                  ->get();

        foreach ($data['_item'] as $key => $value) 
        {
            $data['_item'][$key] = $value;
            $discount = $value->invline_discount;
            if($value->invline_discount_type == 'percent')
            {
                $discount = ($value->invline_rate * $value->invline_orig_qty) * $value->invline_discount;
            }
            $sold_price = $value->invline_rate;
            $data['_item'][$key]->sold_price = $sold_price;
            $data['_item'][$key]->discount = doubleval($discount);
            $data['_item'][$key]->um_qty = UnitMeasurement::um_view($value->invline_orig_qty, $value->invline_um);
        }

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.dyandcy.dailysales_output'; 

            return Report::check_report_type($report_type, $view, $data, 'dyandcysales-'.Carbon::now());
        }
        else
        {
            return view('member.reports.dyandcy.dailysales_head', $data);
        }

    }
    public function dyandcy_incomepermonth()
    { 
        $data['shop_name']  = $this->user_info->shop_key; 
        $month = Request::input('from') ? date('F', strtotime(Request::input('from'))) : date('F');
        $data['head_title'] = 'Daily Income for the Month of '.$month;
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/dyandcy/incomepermonth';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'today';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('from');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['start_date'];

        $data['_item'] = Tbl_customer_invoice_line::invoice()->customer()->invoice_item()
                                                  ->where('inv_shop_id', $this->user_info->shop_id)
                                                  ->where('inv_date',$data['from'])
                                                  ->get();

        foreach ($data['_item'] as $key => $value) 
        {
            $data['_item'][$key] = $value;
            $discount = $value->invline_discount;
            if($value->invline_discount_type == 'percent')
            {
                $discount = ($value->invline_rate * $value->invline_orig_qty) * $value->invline_discount;
            }
            $sold_price = $value->invline_rate;
            $data['_item'][$key]->sold_price = $sold_price;
            $data['_item'][$key]->discount = $discount;
            $data['_item'][$key]->um_qty = UnitMeasurement::um_view($value->invline_orig_qty, $value->invline_um);
        }
        // dd($data['_item']);

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.dyandcy.income_permonth_output'; 

            return Report::check_report_type($report_type, $view, $data, 'dyandcysales-'.Carbon::now());
        }
        else
        {
            return view('member.reports.dyandcy.income_permonth_head', $data);
        }
        
    }
    public function item_vendor_category()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['action']     = '/member/report/accounting/purchase/item_vendor_category';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_item']  = Item::get_all_category_item([1,4,5]);
        $item_id = Request::input("item_id");
        if(!$item_id)
        {
            $item_id = isset($data['_item'][0]["item_list"][0]['item_id']) ? $data['_item'][0]["item_list"][0]['item_id'] : 0;
        }
        $data['item_name'] = Tbl_item::where("item_id", $item_id)->value("item_name");
        $data['head_title'] = 'Item Purchase By Vendor';
        
        $data['head']       = $this->report_header($data);
        $data['_report']    = TransactionEnterBills::get_item_vendor($this->user_info->shop_id, $item_id, $data['from'], $data['to']);

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.item_vendor_category'; 
            return Report::check_report_type($report_type, $view, $data, 'item_vendor_category-'.Carbon::now());
        }
        else
        {
            return view('member.reports.item_vendor_category.item_vendor_category', $data);
        }
    }

    public function bin_location()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Bin Location Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['action']     = '/member/report/bin_location';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);

        $bin_id = Request::input("item_bin_id");
        $data['w_name'] = Tbl_warehouse::where("warehouse_id", $warehouse_id)->value("warehouse_name"). " - ";
        $return = null;
        if($bin_id)
        {
            $data['w_name'] .=  Warehouse2::get_bin_location_name($bin_id);;
            $get_item = Tbl_item::where("shop_id", $this->user_info->shop_id)->whereIn("item_type_id",[1,4,5])->where("archived",0)->get();
            foreach ($get_item as $key => $value) 
            {
                $return[$key] = $value;
                $return[$key]->qty = Warehouse2::get_item_qty($warehouse_id, $value->item_id, $bin_id, null, null, $data['from'], $data['to']);
            }
        }

        $data['head_discription'] = $data['w_name'];
        $data['head']       = $this->report_header($data);
        $data['_bin'] = $return;

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.output_bin_location'; 
            return Report::check_report_type($report_type, $view, $data, 'bin_location-'.Carbon::now());
        }
        else
        {
            return view('member.reports.bin_location.bin_location', $data);
        }
    }

    public function best_seller_item()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Best Seller Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounting/sale/best_seller_item';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $warehouse_id   = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_sales'] = TransactionSalesInvoice::get_best_seller($this->user_info->shop_id, $warehouse_id, $data['from'], $data['to']);
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.best_seller.best_seller_output'; 
            return Report::check_report_type($report_type, $view, $data, 'best_seller-'.Carbon::now());
        }
        else
        {
            return view('member.reports.best_seller.best_seller_list', $data);
        }
        
    }
    public function best_seller_item_by_pattern()
    {
        
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Best Seller Report By Item Name';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounting/sale/best_seller_item_by_pattern';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
       
        $report_type        = Request::input('report_type');
        $load_view          = Request::input('load_view');
        $period             = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']      = Request::input('from');
        $date['end']        = Request::input('to');
        $data['from']       = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']         = Report::checkDatePeriod($period, $date)['end_date'];
        $data['filter_by']  = Request::input('report_period_by_item');
        $warehouse_id   = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_sales'] = TransactionSalesInvoice::get_best_seller_by_item($this->user_info->shop_id, $warehouse_id, $data['from'], $data['to'],$data['filter_by']);
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.best_seller_by_pattern.best_seller_by_pattern_output'; 
            return Report::check_report_type($report_type, $view, $data, 'best_seller-'.Carbon::now());
        }
        else
        {
            return view('member.reports.best_seller_by_pattern.best_seller_by_pattern_list', $data);
        }
        
    }
    public function account_list()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Account List';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounting/account_list';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $account_no_balance  = array('Income', 'Expense', 'Cost of Goods Sold', 'Other Income', 'Other Expense');
        $data['_account'] = Accounting::getAllAccount();
    
        // dd($data['_account']);  

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.account_list'; 
            return Report::check_report_type($report_type, $view, $data, 'account_list-'.Carbon::now());
        }
        else
        {
            return view('member.reports.accounting.account_list', $data);
        }
    }

    public function balance_sheet()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Balance Sheet';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounting/balance_sheet';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
    }
    public function sale_by_warehouse()
    {
        $data =[];
        $report_code = 'accounting_sales_report_warehouse';
        $this->fix_old_data();
        $data['field_checker'] = $this->report_field_checker_seed($report_code);
        $data['head_title'] = 'Sales Report - Item ';
        $data['head_icon'] = 'fa fa-area-chart';
        $data['head_discription'] = 'Account Sales Report';
        $data['head'] = $this->report_header($data);
        $data['action'] = '/member/report/accounting/sale_by_warehouse';
        $data['report_code'] = $report_code;
        $data['table_header'] = Report::sales_report($report_code);


        $report_type    = Request::input('report_type');
        if($report_type){ return $this->sale_by_warehouse_get(); }
        else
        {
            $data['output'] = $this->sale_by_warehouse_get('return_view');
        }

        return view('member.reports.accounting.sales_by_warehouse', $data);
    }
    public function fix_old_data()
    {
        $all_journal_entry_line = Tbl_journal_entry_line::where('jline_item_id', '!=', 0)
        ->item()
        ->journal()
        ->where('jline_warehouse_id', 0)->get();

        $e_commerce_warehouse_select = [];
        $main_warehouse_select = [];
        foreach ($all_journal_entry_line as $key => $value) 
        {
            if($value->item_type_id == 1)
            {
                if($value->je_reference_module == 'product-order')
                {
                    $e_commerce_warehouse_select[$value->jline_id] = $value->jline_id;
                }
                else
                {
                    $main_warehouse_select[$value->jline_id] = $value->jline_id;
                }
                
            }
        }
        $shop_id  = $this->user_info->shop_id;

        $warehouse['main']      = Tbl_warehouse::where('main_warehouse', 1)
        ->where('warehouse_shop_id', $shop_id)
        ->first();

        $warehouse['ecommerce'] = Tbl_warehouse::where('main_warehouse', 2)
        ->where('warehouse_shop_id', $shop_id)
        ->first();
        if($warehouse['main'])
        {
            $update['jline_warehouse_id'] = $warehouse['main']->warehouse_id;

            Tbl_journal_entry_line::whereIn('jline_id', $main_warehouse_select)
            ->update($update);
        }
        if($warehouse['ecommerce'])
        {
            $update['jline_warehouse_id'] = $warehouse['ecommerce']->warehouse_id;

            Tbl_journal_entry_line::whereIn('jline_id', $e_commerce_warehouse_select)
            ->update($update);
        }
    }
    public function sale_by_warehouse_get_old($report_type = null)
    {
        $report_code = 'accounting_sales_report_warehouse';
        $shop_id            = $this->user_info->shop_id; 
        if($report_type == null){ $report_type        = Request::input('report_type'); }
        $load_view          = Request::input('load_view');
        $period             = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']      = Request::input('from');
        $date['end']        = Request::input('to');
        $data['from']       = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']         = Report::checkDatePeriod($period, $date)['end_date'];
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $where_in[0]        = 'customer';

        $data['sales']      = Tbl_journal_entry_line::account()
        ->item()
        ->journal()
        ->selectsales()
        ->where('je_shop_id', $shop_id)
        ->customerorvendor()
        ->whereRaw("DATE(je_entry_date) >= '".$data['from']."'")
        ->whereRaw("DATE(je_entry_date) <= '".$data['to']."'")
        ->concatum()
        ->amount()
        ->whereIn('jline_name_reference', $where_in)
        ->get()
        ->keyBy('jline_id');
        $warehouse['main']      = Tbl_warehouse::where('main_warehouse', 1)
        ->where('warehouse_shop_id', $shop_id)
        ->first();

        $warehouse['ecommerce'] = Tbl_warehouse::where('main_warehouse', 2)
        ->where('warehouse_shop_id', $shop_id)
        ->first();

        $warehouse['all']     = Tbl_warehouse::where('warehouse_shop_id', $shop_id)
        ->get()->keyBy('warehouse_id');
        // dd($data);
        $filter_by_warehouse    = [];
        foreach($data['sales'] as $key => $value)
        {
            $data['sales'][$value->jline_id]->warehouse_quantity = $this->get_sales_quantity($value->je_reference_module,$value->je_reference_id,$value->item_id);
            // dd($data['sales']);
            switch ($value->jline_warehouse_id) {
                case 0:
                    if( $value->je_reference_module == 'product-order'){ $filter_by_warehouse[$warehouse['ecommerce']->warehouse_id][$value->jline_id] = 1; }
                    else{ $filter_by_warehouse[$warehouse['main']->warehouse_id][$value->jline_id] = 1; }
                break;
                
                default:
                    $filter_by_warehouse[$value->jline_warehouse_id][$value->jline_id] = 1;
                break;
            }
        }
        $data['head_title']         = 'Sales Report - Warehouse ';
        $data['head_icon']          = 'fa fa-area-chart';
        $data['head_discription']   = 'Account Sales Report By Warehouse';
        $data['report_field'] = Tbl_report_field::where('report_field_shop', '=', $shop_id)
        ->orderBy('report_field_position', 'ASC')
        ->where('report_field_archive', '=', 0)
        ->where('report_field_type', '=', $report_code)
        ->get()
        ->keyBy('report_field_module');
        // dd($warehouse['all'],$filter_by_warehouse,$data['sales'],$data['report_field'],$data['sales'][$key]['total_amount_per_warehouse']);
        // dd($filter_by_warehouse);
        $data['warehouse_all']      = $warehouse['all'] ;
        $data['filter'] = $filter_by_warehouse;
        $view   =  'member.reports.output.sale_by_warehouse'; 
        return Report::check_report_type($report_type, $view, $data, 'item_list-'.Carbon::now());
    }
    public function sale_by_warehouse_get($report_type = null)
    {
        $report_code = 'accounting_sales_report_warehouse';
        $shop_id            = $this->user_info->shop_id; 
        if($report_type == null){ $report_type        = Request::input('report_type'); }
        $load_view          = Request::input('load_view');
        $period             = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']      = Request::input('from');
        $date['end']        = Request::input('to');
        $data['from']       = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']         = Report::checkDatePeriod($period, $date)['end_date'];
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $where_in[0]        = 'customer';

        $data['sales']      = Tbl_journal_entry_line::account()
                                                    ->item()
                                                    ->journal()
                                                    ->selectsales()
                                                    ->where('je_shop_id', $shop_id)
                                                    ->customerorvendor()
                                                    ->whereRaw("DATE(je_entry_date) >= '".$data['from']."'")
                                                    ->whereRaw("DATE(je_entry_date) <= '".$data['to']."'")
                                                    ->concatum()
                                                    ->amount()
                                                    ->whereIn('jline_name_reference', $where_in)
                                                    ->get()
                                                    ->keyBy('jline_id');
        // dd($data['sales'][26968]);
        // $data['_transaction'] = Tbl_acctg_transaction_list::acctgtransaction()
        //                                               ->whereIn('transaction_ref_name',['sales_invoice','sales-receipt'])
        //                                               ->where('tbl_acctg_transaction.shop_id', $shop_id)
        //                                               ->get();
        $data['_transaction'] = Tbl_customer_invoice::acctg_trans()
                                                    ->invoice_item()
                                                    ->whereIn('transaction_ref_name',['sales_invoice','sales-receipt','sales_receipt'])
                                                    ->where('tbl_acctg_transaction.shop_id', $shop_id)
                                                    ->groupBy('inv_id')
                                                    ->where("warehouse_id", Warehouse2::get_current_warehouse($shop_id))
                                                    ->get();
                                                    // dd($data['_transaction']);
        $data['_sales'] = null;
        foreach ($data['_transaction'] as $key => $value) 
        {
            $data['_sales'][$value->warehouse_id]['warehouse_name'] = $value->warehouse_name;
            $je_data = Tbl_journal_entry_line::account()
                                            ->item()
                                            ->journal()
                                            ->selectsales()
                                            ->where('je_shop_id', $shop_id)
                                            ->customerorvendor()
                                            ->whereRaw("DATE(je_entry_date) >= '".$data['from']."'")
                                            ->whereRaw("DATE(je_entry_date) <= '".$data['to']."'")
                                            ->concatum()
                                            ->amount()
                                            ->where('jline_name_reference', 'customer')
                                            ->whereIn('je_reference_module',['invoice', 'sales-receipt'])
                                            ->where('je_reference_id', $value->inv_id)
                                            ->get();
            foreach ($je_data as $keyje => $valueje) 
            {
                $valueje->warehouse_quantity = $this->get_sales_quantity($value->transaction_ref_name,$value->transaction_ref_id,$valueje->jline_item_id);
                $other = $this->get_other_details($value->transaction_ref_name,$value->transaction_ref_id,$valueje->jline_item_id);
                $valueje->je_entry_date = $other['txn_date'];
                $valueje->item_rate = $other['item_rate'];
                $valueje->txn_num = $other['txn_num'];
                $data['_sales'][$value->warehouse_id]['sales'][$value->transaction_ref_name.$value->transaction_ref_id.$key.$keyje.$valueje->jline_item_id] = $valueje;
            }
        }

        // $warehouse['main']      = Tbl_warehouse::where('main_warehouse', 1)
        // ->where('warehouse_shop_id', $shop_id)
        // ->first();

        // $warehouse['ecommerce'] = Tbl_warehouse::where('main_warehouse', 2)
        // ->where('warehouse_shop_id', $shop_id)
        // ->first();

        // $warehouse['all']     = Tbl_warehouse::where('warehouse_shop_id', $shop_id)
        // ->get()->keyBy('warehouse_id');
        // dd($data);
        // $filter_by_warehouse    = [];
        // foreach($data['sales'] as $key => $value)
        // {
        //     $data['sales'][$value->jline_id]->warehouse_quantity = $this->get_sales_quantity($value->je_reference_module,$value->je_reference_id,$value->item_id);
        //     // dd($data['sales']);
        //     switch ($value->jline_warehouse_id) {
        //         case 0:
        //             if( $value->je_reference_module == 'product-order'){ $filter_by_warehouse[$warehouse['ecommerce']->warehouse_id][$value->jline_id] = 1; }
        //             else{ $filter_by_warehouse[$warehouse['main']->warehouse_id][$value->jline_id] = 1; }
        //         break;
                
        //         default:
        //             $filter_by_warehouse[$value->jline_warehouse_id][$value->jline_id] = 1;
        //         break;
        //     }
        // }
        $data['head_title']         = 'Sales Report - Warehouse ';
        $data['head_icon']          = 'fa fa-area-chart';
        $data['head_discription']   = 'Account Sales Report By Warehouse';
        $data['report_field'] = Tbl_report_field::where('report_field_shop', '=', $shop_id)
        ->orderBy('report_field_position', 'ASC')
        ->where('report_field_archive', '=', 0)
        ->where('report_field_type', '=', $report_code)
        ->get()
        ->keyBy('report_field_module');
        // dd($warehouse['all'],$filter_by_warehouse,$data['sales'],$data['report_field'],$data['sales'][$key]['total_amount_per_warehouse']);
        // dd($filter_by_warehouse);
        // $data['warehouse_all']      = $warehouse['all'] ;
        // $data['filter'] = $filter_by_warehouse;
        $view   =  'member.reports.output.sale_by_warehouse'; 
        return Report::check_report_type($report_type, $view, $data, 'item_list-'.Carbon::now());
    }

    public function equipment_report()
    {
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Equipment Monitoring Report';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/equipment_report';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_equipment'] = Warehouse2::get_equipment_report($this->user_info->shop_id, $warehouse_id, $data['from'], $data['to']);

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.equipment_report'; 
            return Report::check_report_type($report_type, $view, $data, 'equipment_report-'.Carbon::now());
        }
        else
        {
            return view('member.reports.equipment_report.equipment_report', $data);
        }

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

            return Report::check_report_type($report_type, $view, $data, 'top_customer_'.Carbon::now());
        }
        else
        {
            return view('member.reports.top_customer.list_header', $data);
        }
    }
    public function accounts_receivable()
    {
        $data['page'] = "Accounts Receivable";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Accounts Receivable';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounts_receivable';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_customer_ar'] = Migo::get_ar($this->user_info->shop_id, $data['from'], $data['to']);

        $data['report_type']    = $report_type;
         /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.accounts_receivable.ar_output'; 

            return Report::check_report_type($report_type, $view, $data, 'accounts_receivable'.Carbon::now());
        }
        else
        {
            return view('member.reports.accounts_receivable.accounts_receivable', $data);
        }
    }
    public function accounts_payable()
    {
        $data['page'] = "Accounts Payable";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title'] = 'Accounts Payable';
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['head']       = $this->report_header($data);
        $data['action']     = '/member/report/accounts_payable';
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');

        $report_type    = Request::input('report_type');
        $load_view      = Request::input('load_view');
        $period         = Request::input('report_period') ? Request::input('report_period') : 'all';
        $date['start']  = Request::input('from');
        $date['end']    = Request::input('to');
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['_vendor_ap'] = Migo::get_ap($this->user_info->shop_id, $data['from'], $data['to']); 
        $data['report_type']    = $report_type;
         /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.accounts_payable.ap_output'; 

            return Report::check_report_type($report_type, $view, $data, 'accounts_payable'.Carbon::now());
        }
        else
        {
            return view("member.reports.accounts_payable.accounts_payable", $data);
        }
        
    }
}