<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_item;
use App\Models\Tbl_item_bundle;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_category;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_settings;
use App\Models\Tbl_unit_measurement;
use App\Models\Tbl_product_vendor;
use App\Models\Tbl_manufacturer;
use App\Models\Tbl_unit_measurement_multi;
use App\Models\Tbl_item_discount;
use App\Models\Tbl_item_multiple_price;
use App\Models\Tbl_inventory_slip;
use App\Models\Tbl_um;
use App\Models\Tbl_item_merchant_request;
use App\Models\Tbl_user;
use App\Models\Tbl_merchant_markup;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_merchant_commission;

use App\Globals\Category;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\DigimaTable;
use App\Globals\Warehouse;
use App\Globals\Item;
use App\Globals\Vendor;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Utilities;
use App\Globals\Pdf_global;
use Crypt;
use Redirect;
use Request;
use View;
use Session;
use DB;
use Input;
use Validator;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request as Request2;
use Image;
use File;
use Excel;

class MerchantReportController extends Member
{
	public function index()
	{
		$data =	[];
		$data['merchant'] = $this->list_all_merchnat();
		$data['report'] = $this->report_list();
		return view('member.merchant.report.admin', $data);
	}
	public function report_list()
	{
		$report['sales'] = 'Sales Report';
		$report['sales_item'] = 'Sales Report [Item]';
		// $report['commission'] = 'Commission Report';
		// $report['ewallet'] = 'E-wallet Transaction Report';

		return $report;
	}
	public function list_all_merchnat()
	{
		$shop_id = $this->user_info->shop_id;
		$user_id = $this->user_info->user_id;
		$user_is_merchant = $this->user_info->user_is_merchant;
		if($user_is_merchant == 0)
		{
			$merchant = Tbl_user::where('user_shop', $shop_id)->where('user_is_merchant', 1)->get();
		}else
		{
			$merchant = Tbl_user::where('user_id', $user_id)->where('user_is_merchant', 1)->get();
		}
		

		foreach ($merchant as $key => $value) 
		{
			$merchant[$key]->overall = Tbl_item_code_invoice::where('user_id', $value->user_id)->sum('item_total');
		}
		return $merchant;
	}
	public function view_report()
	{
		$report_select = Request::input('report_select');

		$report_f = 'report_' . $report_select;

		return $this->$report_f();
	}
	public function view_report_data()
	{
		$user_id = Request::input('user_id');
		$from = Request::input('from');
		$to = Carbon::parse(Request::input('to'))->endOfDay();
		$report_select = Request::input('report_select');
		$data['merchant'] = Tbl_user::where('user_id', $user_id)->first();
		$data['header'] = $this->view_report_header();
		return $data;
	}
	public function view_report_header()
	{
		$user_id 		= Request::input('user_id');
		$from 			= Carbon::parse(Request::input('from'))->startofDay();
		$to 			= Carbon::parse(Request::input('to'))->endOfDay();
		$report_select 	= Request::input('report_select');
		$pdf 			= Request::input('pdf');

		$data['link'] = '/member/merchant/report/view?user_id=' . $user_id . '&from=' . $from . '&to='. $to . '&report_select=' . $report_select;
		$data['merchant'] = Tbl_user::where('user_id', $user_id)->first();
		$data['pdf'] = $pdf;
		$data['report'] = $this->report_list();
		$data['report_header'] = $data['report'][$report_select];
		$data['from'] = $from;
		$data['to'] = $to;
		return view('member.merchant.report.header', $data);
	}
	public function report_sales()
	{
		$user_id = Request::input('user_id');
		$from = Request::input('from');
		$to = Carbon::parse(Request::input('to'))->endOfDay();
		$data = $this->view_report_data();
		$pdf = Request::input('pdf');
		$data['sales'] = Tbl_item_code_invoice::where('user_id', $user_id)
						->where('item_code_date_created', '>=', $from)
						->where('item_code_date_created', '<=', $to)
						->get();

		if($pdf == 'pdf')
		{
			$view = view('member.merchant.report.sales', $data);
			return Pdf_global::show_pdf($view);	

		}
		elseif($pdf == 'excel')
		{
			$view = $data;
			Excel::create('New file', function($excel) use($view) {

                $excel->sheet('New sheet', function($sheet) use($view) {

                    $sheet->loadView('member.merchant.report.sales', $view);

                });

            })->export('xls');
		}
					
		return view('member.merchant.report.sales', $data);

	}
	public function report_sales_item()
	{
		$user_id = Request::input('user_id');
		$from = Request::input('from');
		$to = Carbon::parse(Request::input('to'))->endOfDay();
		$data = $this->view_report_data();
		$pdf = Request::input('pdf');
		$data['sales'] = Tbl_item_code_invoice::where('user_id', $user_id)
						->where('item_code_date_created', '>=', $from)
						->join('tbl_item_code_item', 'tbl_item_code_item.item_code_invoice_id', '=','tbl_item_code_invoice.item_code_invoice_id')
						->where('item_code_date_created', '<=', $to)
						->get();

		$data['sales_item'] = [];
		foreach ($data['sales'] as $key => $value) 
		{
			if(isset($data['sales_item'][$value->item_name]))
			{
				$data['sales_item'][$value->item_name]['qty']  += $value->item_quantity;
				$data['sales_item'][$value->item_name]['total'] += $value->item_quantity * $value->item_membership_discounted;
			}
			else
			{
				$data['sales_item'][$value->item_name]['qty']  = $value->item_quantity;
				$data['sales_item'][$value->item_name]['total'] = $value->item_quantity * $value->item_membership_discounted;
			}
		}

		if($pdf == 'pdf')
		{
			$view = view('member.merchant.report.sales_item', $data);
			Pdf_global::show_pdf($view);	
		}
		elseif($pdf == 'excel')
		{
			$view = $data;
			Excel::create('New file', function($excel) use($view) {

                $excel->sheet('New sheet', function($sheet) use($view) {

                    $sheet->loadView('member.merchant.report.sales_item', $view);

                });

            })->export('xls');
		}


		return view('member.merchant.report.sales_item', $data);
	}
	public function report_commission()
	{

	}
	public function report_ewallet()
	{

	}
}	