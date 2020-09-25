<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Globals\Transaction;
use App\Globals\Warehouse2;
use App\Globals\Report;
use App\Globals\Item;
use Carbon\Carbon;
use App\Models\Tbl_inventory_history_items;
use App\Models\Tbl_inventory_history;
use App\Models\Tbl_item;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Models\Tbl_write_check;
use App\Models\Tbl_bill;
use App\Models\Tbl_receive_inventory;
use App\Models\Tbl_customer_wis;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_warehouse_issuance_report;
use App\Models\Tbl_warehouse_receiving_report;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_inventory_adjustment;
use App\Models\Tbl_debit_memo;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_monitoring_inventory;

class AdjustmentDetailedController extends Member
{
    public function index($item_id, Request $request)
    {
        $data['item_id'] = $item_id;
        $data["action"] = "/member/report/inventory/detailed/".$item_id;
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Inventory Adjustment- Detailed'; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';

        $report_type    = $request->report_type;
        $load_view      = $request->load_view;
        $period         = $request->report_period ? $request->report_period : 'all';
        $date['start']  = $request->from;
        $date['end']    = $request->to;
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];

        $data['w_type'] = Warehouse2::get_warehouse_type(Warehouse2::get_current_warehouse($this->user_info->shop_id));
        
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_report'] = Tbl_item::type()->Recordloginventory($warehouse_id)->where('item_id',$item_id)->get();

        foreach ($data['_report'] as $key => $value)
        {   
            $data['_report'][$key]->transaction = Self::get_all_transactions($item_id, $warehouse_id, $data['from'], $data['to']);
        }
        
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.adjustment_detailed'; 
            return Report::check_report_type($report_type, $view, $data, 'Inventory Valuation Detailed -'.Carbon::now());
        }
        else
        {
            return view("member.reports.inventory.adjustment_detailed",$data);
        }

    }
    public function get_all_transactions($item_id, $warehouse_id, $from, $to)
    {
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $transactions = Tbl_monitoring_inventory::where('invty_shop_id', $this->user_info->shop_id)
                    ->where('invty_item_id', $item_id)
                    ->where('invty_warehouse_id', $warehouse_id)
                    ->where('invty_transaction_name','adjust_inventory')
                    ->orderBy('invty_date_created', 'ASC')
                    ->whereBetween('invty_date_created', [$from, $to])
                    ->get();

        $data = null;
        $transaction  = null; 
        $transaction_name = null; 
        $transaction_date = null; 
        $transaction_ref_num = null; 
        $transaction_company = null; 
        $transaction_status = null;
        if($transactions)
        {
            foreach ($transactions as $key => $value)
            {
                $transaction_name = 'Inventory Adjustment';
                $get_transaction  = Tbl_inventory_adjustment::warehouse()->where('inventory_adjustment_id', $value->invty_transaction_id)->first();
                $transaction_date = date('m/d/Y', strtotime($get_transaction->date_created));
                $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->inventory_adjustment_id;
                $warehouse = Tbl_warehouse::where('warehouse_id', $get_transaction->adj_warehouse_id)->first();
                $transaction_company = $warehouse->warehouse_name;
                if($value->invty_qty > 0)
                {
                    $transaction_status = 'in';
                }
                else
                {
                    $transaction_status = 'out';
                }
                $data[$item_id][$value->invty_transaction_id]['transaction_name'] = $transaction_name;
                $data[$item_id][$value->invty_transaction_id]['transaction_date'] = $transaction_date;
                $data[$item_id][$value->invty_transaction_id]['transaction_ref_num'] = $transaction_ref_num;
                $data[$item_id][$value->invty_transaction_id]['transaction_company'] = $transaction_company;
                $data[$item_id][$value->invty_transaction_id]['transaction_line_qty'] = $value->invty_qty;
                $data[$item_id][$value->invty_transaction_id]['transaction_line_cost'] = $value->invty_cost_price;
                $data[$item_id][$value->invty_transaction_id]['transaction_line_total_cost'] = $value->invty_total_cost_price;
                $data[$item_id][$value->invty_transaction_id]['transaction_line_sales'] = $value->invty_sales_price;
                $data[$item_id][$value->invty_transaction_id]['transaction_line_total_sales'] = $value->invty_total_sales_price;
                $data[$item_id][$value->invty_transaction_id]['transaction_stock_on_hand'] = $value->invty_stock_on_hand;
                $data[$item_id][$value->invty_transaction_id]['transaction_status'] = $transaction_status;
            }
        }
        return $data;
    }
}
