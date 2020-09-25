<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use Redirect;
use Response;
use View;
use Excel;
use DB;

use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_customer;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_user;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_binary_setttings;
use App\Models\Tbl_membership;
use App\Models\Tbl_customer_search;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_mlm_matching_log;
use App\Globals\Item;
use App\Globals\AuditTrail;
use App\Globals\Mlm_plan;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_complan_manager_cd;
use App\Globals\Mlm_tree;
use App\Globals\Membership_code;
use App\Globals\Settings;
use App\Globals\Mlm_voucher;
use App\Globals\Mlm_slot_log;
use App\Globals\Item_code;
use App\Globals\Mlm_gc;
use App\Globals\Mlm_complan_manager_repurchase;
use App\Globals\Utilities;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_report;
use App\Globals\Pdf_global;
use App\Models\Tbl_membership_code_invoice;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_journal_entry_line;
use App\Globals\Category;
use App\Models\Tbl_mlm_slot_wallet_log_transfer;
use Crypt;
class MLM_ReportController extends Member
{
    public function index()
    {
        // return $this->sales_chart_account();
        $shop_id = $this->user_info->shop_id; 
        # code...
        $data = [];

        $from = Carbon::now();
        $to = Carbon::now()->endOfDay();
        $count = 99999;
        // -----------------------------------------------------------------
        $data['report_list']['cashflow'] = 'Complan Income Report';
        $data['report_list_d']['cashflow']['from'] = $from;
        $data['report_list_d']['cashflow']['to'] = $to;
        $data['report_list_d']['cashflow']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['e_wallet'] = 'E-Wallet Report';
        $data['report_list_d']['e_wallet']['from'] = $from;
        $data['report_list_d']['e_wallet']['to'] = $to;
        $data['report_list_d']['e_wallet']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['e_wallet_transfer'] = 'E-Wallet Transfer Report';
        $data['report_list_d']['e_wallet_transfer']['from'] = $from;
        $data['report_list_d']['e_wallet_transfer']['to'] = $to;
        $data['report_list_d']['e_wallet_transfer']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['e_wallet_refill'] = 'E-Wallet Refill Report';
        $data['report_list_d']['e_wallet_refill']['from'] = $from;
        $data['report_list_d']['e_wallet_refill']['to'] = $to;
        $data['report_list_d']['e_wallet_refill']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['e_wallet_tour'] = 'E-Wallet -> Tour Wallet Report';
        $data['report_list_d']['e_wallet_tour']['from'] = $from;
        $data['report_list_d']['e_wallet_tour']['to'] = $to;
        $data['report_list_d']['e_wallet_tour']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['slot_count'] = 'Slot Count';
        $data['report_list_d']['slot_count']['from'] = $from;
        $data['report_list_d']['slot_count']['to'] = $to;
        $data['report_list_d']['slot_count']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['binary_slot_count'] = 'Binary Slot Count';
        $data['report_list_d']['binary_slot_count']['from'] = $from;
        $data['report_list_d']['binary_slot_count']['to'] = $to;
        $data['report_list_d']['binary_slot_count']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['top_earners'] = 'Top Earners';
        $data['report_list_d']['top_earners']['count'] = $count;
        $data['report_list_d']['top_earners']['from'] = $from;
        $data['report_list_d']['top_earners']['to'] = $to;
         // -----------------------------------------------------------------
        $data['report_list']['new_register'] = 'Registered Account';
        $data['report_list_d']['new_register']['from'] = $from;
        $data['report_list_d']['new_register']['to'] = $to;
        $data['report_list_d']['new_register']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['encashment_rep'] = 'Encashment';
        $data['report_list_d']['encashment_rep']['count'] = $count;
        $data['report_list_d']['encashment_rep']['to'] = $to;
        $data['report_list_d']['encashment_rep']['from'] = $from;
        // -----------------------------------------------------------------
        $data['report_list']['encashment_rep_req'] = 'Encashment(Requested)';
        $data['report_list_d']['encashment_rep_req']['count'] = $count;
        $data['report_list_d']['encashment_rep_req']['to'] = $to;
        $data['report_list_d']['encashment_rep_req']['from'] = $from;
        // -----------------------------------------------------------------
        $data['report_list']['encashment_rep_pro'] = 'Encashment(Processed)';
        $data['report_list_d']['encashment_rep_pro']['count'] =  $count;
        $data['report_list_d']['encashment_rep_pro']['to'] = $to;
        $data['report_list_d']['encashment_rep_pro']['from'] = $from;
        // -----------------------------------------------------------------
        $data['report_list']['product_sales_report'] = 'Product Sales Report';
        $data['report_list_d']['product_sales_report']['from'] = $from;
        $data['report_list_d']['product_sales_report']['to'] = $to;
        $data['report_list_d']['product_sales_report']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['membership_code_sales_report'] = 'Membership Sales Report';
        $data['report_list_d']['membership_code_sales_report']['from'] =  $from;
        $data['report_list_d']['membership_code_sales_report']['to'] =  $to;
        $data['report_list_d']['membership_code_sales_report']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['product_sales_report_warehouse'] = 'Product Sales Report (Ware house)';
        $data['report_list_d']['product_sales_report_warehouse']['from'] = $from;
        $data['report_list_d']['product_sales_report_warehouse']['to'] = $to;
        $data['report_list_d']['product_sales_report_warehouse']['count'] = $count;
        // -----------------------------------------------------------------
        $data['report_list']['product_sales_report_consolidated'] = 'Product Sales Report (Consiladated)';
        $data['report_list_d']['product_sales_report_consolidated']['from'] = $from;
        $data['report_list_d']['product_sales_report_consolidated']['to'] = $to;
        $data['report_list_d']['product_sales_report_consolidated']['count'] = $count;
        // -----------------------------------------------------------------
        // -----------------------------------------------------------------
        $data['report_list']['product_sales_report_consolidated'] = 'Product Sales Report (Consiladated)';
        $data['report_list_d']['product_sales_report_consolidated']['from'] = $from;
        $data['report_list_d']['product_sales_report_consolidated']['to'] = $to;
        $data['report_list_d']['product_sales_report_consolidated']['count'] = $count;
        // -----------------------------------------------------------------

        $data['report_list']['warehouse_consiladated'] = 'Consolidated Report';
        $data['report_list_d']['warehouse_consiladated']['from'] = Carbon::now();
        $data['report_list_d']['warehouse_consiladated']['to'] = Carbon::now();
        $data['report_list_d']['warehouse_consiladated']['count'] = 0;

        $data['report_list']['warehouse_consiladated'] = 'Warehouse Sales Report';
        $data['report_list_d']['warehouse_consiladated']['from'] = Carbon::now();
        $data['report_list_d']['warehouse_consiladated']['to'] = Carbon::now();
        $data['report_list_d']['warehouse_consiladated']['count'] = 0;
        // ----------------------------------------------------------------- patrick
        $data['report_list']['payout'] = 'Payout Report (Processed)';
        $data['report_list_d']['payout']['from'] = $from;
        $data['report_list_d']['payout']['to'] = $to;
        $data['report_list_d']['payout']['count'] = $count;
        // ----------------------------------------------------------------- patrick
        $data['report_list']['commission_payable'] = 'Commission Payable Report';
        $data['report_list_d']['commission_payable']['from'] = $from;
        $data['report_list_d']['commission_payable']['to'] = $to;
        $data['report_list_d']['commission_payable']['count'] = $count;
        // ----------------------------------------------------------------- patrick
        $data['report_list']['payin'] = 'Payin Report';
        $data['report_list_d']['payin']['from'] = $from;
        $data['report_list_d']['payin']['to'] = $to;
        $data['report_list_d']['payin']['count'] = $count;


        foreach($data['report_list_d'] as $key => $value)
        {
            $data['report_list_d'][$key]['from'] = Carbon::parse($value['from'])->format('Y-m-d');
            $data['report_list_d'][$key]['to'] = Carbon::parse($value['to'])->format('Y-m-d');
            $data['report_list_d'][$key]['cashiers'] = 'hide';
            $data['report_list_d'][$key]['warehouse'] = 'hide';

            if($key == 'product_sales_report_warehouse' || $key == 'product_sales_report')
            {
                $data['report_list_d'][$key]['cashiers'] = 'show';
                $data['report_list_d'][$key]['warehouse'] = 'show';
            }
        }
        $report_get = Request::input('report_choose');
        if($report_get != null)
        {
            return $this->get_report();
        }

        $data['users'] = Tbl_user::where('user_shop', $shop_id)
        ->where('user_email', '!=', 'PhiltechDeveloper@gmail.com')
        ->where('user_level', '>=', 2)
        ->where('archived', 0)->get();
        $data['warehouse'] = Tbl_warehouse::where('warehouse_shop_id', $shop_id)->where('archived', 0)->get();
        return view('member.mlm_report.index', $data);
    }
    public function get_report()
    {

        $filter['from'] = Request::input('from');
        $filter['to'] = Request::input('to');
        $from = Carbon::parse($filter['from']);
        $to = Carbon::parse($filter['to'])->addDay(1);
        $filter['to'] = $to;
        $filter['from'] = $from;
        $filter['skip'] = Request::input('skip');
        $filter['take'] = Request::input('take');

        $report = Request::input('report_choose');
        $pdf= Request::input('pdf');
        $shop_id = $this->user_info->shop_id;
        $view =  Mlm_report::$report($shop_id, $filter);
        $data['status'] = 'success';
        $view['from'] = date_format($from, 'd/M/Y');
        $view['to'] = date_format($to, 'd/M/Y');

        

        // return $view;
        $from = Request::input('from');
        if($from == 'paginate')
        {
            $data['view'] = $view->render();
            return $data['view'];
        }
        if($pdf == 'true')
        {

            $data['view'] = $view->render();
            return Pdf_global::show_pdf($data['view'], 'landscape');
        }
        else if($pdf == 'excel')
        {
            Excel::create('New file', function($excel) use($view) {
               
                $excel->sheet('New sheet', function($sheet) use($view) {
                    $sheet->loadView('member.mlm_report.report.' . $view['page'], $view);

                });

            })->export('xls');
        }
        else
        {
            $data['view'] = $view->render();
            return json_encode($data);
        }
    }
    public function sales_chart_account()
    {
        $entries = Tbl_journal_entry_line::account()
        ->item()
        ->journal()
        ->selectsales()
        ->get();
        dd($entries);
        $category = Category::re_select_raw($this->user_info->shop_id, 70, array("all","services","inventory","non-inventory","bundles"));
    }


}