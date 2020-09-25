<?php
namespace App\Globals;


use DB;
use Session;
use App;
use PDF;
use View;
use Excel;
use Request;
use Image;
use Validator;
use Redirect;
use File;
use URL;
use Carbon\Carbon;
use App\Globals\Pdf_global;
use App\Globals\Report;

class Report
{
	/**
	 * Get the range of report by selected period 
	 *
	 * @param  string  	$filter 	 ( tag of account report)
	 * @author LUKE
	 */
	public static function sales_report($filter = 'accounting_sales_report')
	{
		switch ($filter) {
			case 'accounting_sales_report':
					$data['je_remarks'] = 'Remarks';
					$data['je_reference_module'] = 'Reference Module';
					// $data['je_entry_date'] = 'Date Created';
					// $data['account_name'] = 'Type';
					$data['je_entry_date'] = 'Date';
					// $data['account_number'] = 'Num';
					$data['account_name'] = 'Account Name';
					// $data['full_name'] = 'Name';
					$data['item_name'] = 'Item';
					$data['item_sku'] = 'Item SKU';
					$data['item_price'] = 'Sales Price';
					$data['warehouse'] = 'Warehouse';
					$data['item_rate'] = 'Rate';
					$data['z_um'] = 'U/M';
					$data['jline_amount'] = 'Amount';
					return $data;
				break;
			case 'accounting_sales_report_item':
					$data['je_remarks'] = 'Remarks';
					$data['je_reference_module'] = 'Reference Module';
					// $data['je_entry_date'] = 'Date Created';
					// $data['account_name'] = 'Type';
					$data['je_entry_date'] = 'Date';
					// $data['account_number'] = 'Num';
					$data['account_name'] = 'Account Name';
					$data['full_name'] = 'Customer Name';
					// $data['item_name'] = 'Item';
					$data['warehouse'] = 'Warehouse';
					$data['item_price'] = 'Sales Price';
					$data['item_rate'] = 'Rate';
					$data['z_um'] = 'U/M';
					$data['jline_amount'] = 'Amount';
					return $data;
				break;
			case 'accounting_general_ledger':
					$data['date_a'] = 'Date';
					$data['je_reference_module'] = 'Transaction Type';
					$data['je_reference_id'] = 'Num';
					$data['c_full_name'] = 'Name';
					$data['jline_description'] = 'Memo/Discription';
					$data['amount2'] = 'Amount';
					return $data;
				break;	
			case 'accounting_sales_report_warehouse':
					$data['je_remarks'] = 'Remarks';
					$data['je_reference_module'] = 'Reference Module';
					// $data['je_entry_date'] = 'Date Created';
					// $data['account_name'] = 'Type';
					$data['txn_num'] = 'Ref Num';
					$data['je_entry_date'] = 'Date';
					$data['account_number'] = 'Num';
					$data['account_name'] = 'Account Name';
					$data['full_name'] = 'Customer Name';
					$data['item_name'] = 'Item';
					$data['item_sku'] = 'Item SKU';
					$data['item_price'] = 'Sales Price';
					$data['item_rate'] = 'Rate';
					$data['z_um'] = 'U/M';
					$data['jline_amount'] = 'Amount';
					$data['balance'] = 'Balance';
					return $data;	
				break;		
			default:
					
				break;
		}
	}
	/**
	 * Get the range of report by selected period 
	 *
	 * @param  string  	$period 	 ( Please see "case" below)
	 * @param  string  	$date  		 $date["start"], $date["end"], $date["days"] | nullable depends on requirements
	 * @return array    			 [start_date] , [end_date]; 
	 * @author BKA	
	 */
	public static function checkDatePeriod($period, $date = nul)
	{
		switch($period)
		{
			case 'all':
				$data["start_date"] = "1000-01-01";
				$data["end_date"]	= "9999-12-30";
				break;
			case 'custom':
				$data["start_date"] = $date["start"] != '' ? datepicker_input($date["start"]) : datepicker_input("today");
				$data["end_date"]	= $date["end"] != '' ? datepicker_input($date["end"]) : datepicker_input("today");
				break;
			case 'today':
				$data["start_date"] = datepicker_input("today");
				$data["end_date"]	= datepicker_input("today");
				break;
			case 'this_week':
				$data["start_date"] = datepicker_input("previous sunday");
				$data["end_date"]	= datepicker_input("this week saturday");
				break;
			case 'this_week_to_date':
				$data["start_date"] = datepicker_input("-1 week sunday");
				$data["end_date"]	= datepicker_input("today");
				break;
			case 'last_week':
				$data["start_date"] = datepicker_input("-2 week sunday");
				$data["end_date"]	= datepicker_input("-1 week saturday");
				break;
			case 'last_week_to_date':
				$data["start_date"] = datepicker_input("-2 week sunday");
				$data["end_date"]	= datepicker_input("today");
				break;
			case 'this_month':
				$data["start_date"] = datepicker_input("first day of this month");
				$data["end_date"]	= datepicker_input("last day of this month");
				break;
			case 'this_month_to_date':
				$data["start_date"] = datepicker_input("first day of this month");
				$data["end_date"]	= datepicker_input("today");
				break;
			case 'last_month':
				$data["start_date"] = datepicker_input("first day of previous month");
				$data["end_date"]	= datepicker_input("last day of previous month");
				break;
			case 'last_month_to_date':
				$data["start_date"] = datepicker_input("first day of previous month");
				$data["end_date"]	= datepicker_input("today");
				break;
			case 'this_quarter':
				$offset 			= (date('n')%3)-1;
				$data["start_date"]	= datepicker_input("first day of -$offset month midnight");
				$offset		 		= 3-(date('n')%3);
				$data["end_date"] 	= datepicker_input("last day of +$offset month midnight");
				break;
			case 'this_quarter_to_date':
				$offset 			= (date('n')%3)-1;
				$data["start_date"]	= datepicker_input("first day of -$offset month midnight");
				$data["end_date"] 	= datepicker_input("today");
				break;
			case 'this_year':
				$data["start_date"] = datepicker_input("first day of January");
				$data["end_date"]	= datepicker_input("last day of December");
				break;
			case 'days_ago':
				$data["start_date"] = datepicker_input("-".$date["days"] ." days");
				$data["end_date"]	= datepicker_input("today");
				break;
		}
		return $data;
	}


	/**
	 * Perform the right action depending on the report type gives
	 *
	 * @param  string  	$report_type 	( plain, pdf, excel)
	 * @param  string  	$view  		 	output view for the report	
	 * @param  array    $data			all data for the repjort
	 * @param  string   $name			name for the excel type
	 * @param  string   $pdf_format		landscape or portrait : default is landscape	
	 * @author BKA	
	 */
	public static function check_report_type($report_type, $view, $data, $name="File", $pdf_format = "landscape", $paper_size = null)
    {   
        $_view = view($view, $data); 

         switch ($report_type) 
         {
         	case 'return_view':
         			return $_view->render();
         		break;
            case 'pdf':
                    $data['view'] = $_view->render();
                    return Pdf_global::show_pdf($data['view'], $pdf_format == "landscape" ? $pdf_format : null,null, $paper_size);
                break;
            case 'excel':
                    Excel::create($name, function($excel) use($view, $data) 
                    {
                        $excel->sheet('New sheet', function($sheet) use($view, $data) 
                        {
                            $sheet->loadView($view, $data);
                        });
                    })->export('xls');	
            default:
                    $return['status'] = 'success_plain';
                    $return['view'] = $_view->render();
                    return json_encode($return);
                break;
        }
    }
}