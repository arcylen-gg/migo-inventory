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
use App\Models\Tbl_item_type;
use App\Models\Tbl_vendor;
use App\Models\Tbl_bill;
use App\Models\tbl_category;
use Carbon\Carbon;
use App\Globals\TransactionEnterBills;
use App\Globals\Item;
use DB;
use stdClass;

class ReportPurchaseItemController extends Member
{
    public function index_summary(Request $request)
    {
        $data["action"] = "/member/report/open/purchase/item_summary";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Purchases by Item Summary'; 

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

        $data['_item_type'] = Tbl_item_type::get();

        $join_select = 2;
        $shop_id = $this->user_info->shop_id;

        foreach ($data['_item_type'] as $key => $value) 
        {
            $param_func['item_type_id'] = $value->item_type_id;
            $param_func['shop_id'] = $shop_id;
            $param_func['date_from'] = $data['from'];
            $param_func['date_to'] = $data['to'];
            $param_func['join_parameter'] = $join_select;
            $param_func['module_used'] = 'reports';

            $param_func['where_used'] = 'purchased_by_item_summary';
            $data['_item_type'][$key]['inventory'] = Tbl_bill::PurchasedByQueries_with_where($param_func);

            $param_func['get_total_param'] = 'sum_qty';
            $param_func['where_used'] = 'purchased_by_item_summary_total';
            $data['_item_type'][$key]['inventory_qty_sum'] = TransactionEnterBills::get_reports_amount($param_func);

            $param_func['where_used'] = 'purchased_by_item_summary_total_all';
            $data['_item_type'][$key]['inventory_qty_sum_all'] = TransactionEnterBills::get_reports_amount($param_func);

            $param_func['get_total_param'] = 'sum_amount';
            $param_func['where_used'] = 'purchased_by_item_summary_total';
            $data['_item_type'][$key]['inventory_amt_sum'] = TransactionEnterBills::get_reports_amount($param_func);

            $param_func['where_used'] = 'purchased_by_item_summary_total_all';
            $data['_item_type'][$key]['inventory_amt_sum_all'] = TransactionEnterBills::get_reports_amount($param_func);

        }
        // dd($data['_item_type']);
       // dd($data['_item_type'][$key]['inventory_qty_sum_all']);
       //dd($data['_item_type'][0]['inventory'][0]->item_name);

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.purchases_by_item_summary'; 
            return Report::check_report_type($report_type, $view, $data, 'Open Purchase Order by Item Summary'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.inventory.purchases_by_item_summary', $data);
        }
    }

    public function index_detail(Request $request)
    {
        $data["action"] = "/member/report/open/purchase/item_detail";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Purchases by Item Details'; 

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

        $data['_item_type'] = Tbl_item_type::get();

        $join_select = 4;
        $shop_id = $this->user_info->shop_id;


        foreach ($data['_item_type'] as $key => $value) 
        {
            $param_func['item_type_id'] = $value->item_type_id;
            $param_func['shop_id'] = $shop_id;
            $param_func['date_from'] = $data['from'];
            $param_func['date_to'] = $data['to'];
            $param_func['join_parameter'] = $join_select;
            $param_func['module_used'] = 'reports';

            //$param_func['where_used'] = 'purchased_by_item_summary_total';
            $param_func['where_used'] = 'purchased_by_item_detail_total_group';
            $param_func['item_id'] = 0;
            $data['_item_type'][$key]['inventory'] = Tbl_bill::PurchasedByQueries_with_where($param_func)->select('item_id','item_name')->get();
            // dd( $data['_item_type'][$key]['inventory']);

            $total_qty_all = 0;
            $total_amount_all = 0;

            foreach ($data['_item_type'][$key]['inventory'] as $key_item => $value) 
            {
                //data per product with description
                $param_func['where_used'] = 'purchased_by_item_detail_total';
                $param_func['item_id']                                                  = $data['_item_type'][$key]['inventory'][$key_item]['item_id'];
                $data['_item_type'][$key]['inventory'][$key_item]['inventory_group']     = Tbl_bill::PurchasedByQueries_with_where($param_func)->groupby('tbl_bill.transaction_refnum')->get();

                //for balance column
                $Cumulative_Sum = 0;
                $total_qty_per_item = 0;
                $total_amount_per_item = 0;

                foreach ($data['_item_type'][$key]['inventory'][$key_item]['inventory_group'] as $key_group => $value_group) 
                {
                    $bill_refnum = $data['_item_type'][$key]['inventory'][$key_item]['inventory_group'][$key_group]['bill_refnum'];

                    $data['_item_type'][$key]['inventory'][$key_item]['inventory_group'][$key_group]['itm_balance'] = $value_group->itemline_rate * $value_group->itemline_orig_qty;

                    $Cumulative_Sum += $data['_item_type'][$key]['inventory'][$key_item]['inventory_group'][$key_group]['itm_balance'];

                    $data['_item_type'][$key]['inventory'][$key_item]['inventory_group'][$key_group]['itm_balance_cummulative_sum'] = $Cumulative_Sum;

                    $data['_item_type'][$key]['inventory'][$key_item]['inventory_group'][$key_group]['inventory_group_checking'] = Tbl_bill::PurchasedByQueries_with_where($param_func)->where('tbl_bill.transaction_refnum',$bill_refnum)->get();

                    foreach($data['_item_type'][$key]['inventory'][$key_item]['inventory_group'][$key_group]['inventory_group_checking'] as $key_group_sort => $value_sort)
                    {
                        $data['_item_type'][$key]['inventory'][$key_item]['inventory_group'][$key_group]['inventory_group_checking'][$key_group_sort]['itm_balance'] = $value_sort->itemline_rate * - $value_sort->dbline_qty;
                
                        $Cumulative_Sum += $data['_item_type'][$key]['inventory'][$key_item]['inventory_group'][$key_group]['inventory_group_checking'][$key_group_sort]['itm_balance'];
                    
                        $data['_item_type'][$key]['inventory'][$key_item]['inventory_group'][$key_group]['inventory_group_checking'][$key_group_sort]['itm_balance_cummulative_sum'] = $Cumulative_Sum;
                    }
                    
                    $total_qty_per_item += $value_group->itemline_qty;

                    $total_amount_per_item += $value_group->itemline_qty * $value_group->itemline_rate;

                    $total_qty_all += $value_group->itemline_qty;

                    $total_amount_all += $value_group->itemline_qty * $value_group->itemline_rate;

                }

                $data['_item_type'][$key]['inventory'][$key_item]['inventory_group_qty_sum']  = $total_qty_per_item;

                $data['_item_type'][$key]['inventory'][$key_item]['inventory_group_amt_sum'] = $total_amount_per_item;

            }

              // dd($data['_item_type'][0]['inventory'][0]['inventory_group']);

            $data['_item_type'][$key]['inventory_qty_sum'] = $total_qty_all;

            $data['_item_type'][$key]['inventory_amt_sum'] = $total_amount_all;
        }

        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.purchases_by_item_detail'; 
            return Report::check_report_type($report_type, $view, $data, 'Open Purchase Order by Item Details'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.inventory.purchases_by_item_detail', $data);
        }
    }

    public function report_header($data)
    {
        return view('member.reports.head', $data);
    }
}
