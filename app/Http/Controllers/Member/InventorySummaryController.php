<?php

namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Globals\Transaction;
use App\Globals\Warehouse2;
use App\Globals\Report;
use App\Globals\Item;
use Carbon\Carbon;
use App\Models\Tbl_item;
use App\Models\Tbl_warehouse_inventory_record_log;
use App\Models\Tbl_monitoring_inventory;
use App\Globals\AccountingTransaction;

class InventorySummaryController extends Member
{
    public function index(Request $request)
    {
        $data["action"] = "/member/report/inventory/summary";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Inventory - Summary'; 
        $data['now']        = Carbon::now()->format('l F j, Y h:i:s A');
        
        $data['head_icon']  = 'fa fa-area-chart';
        $data['head_discription'] = '';
        $data['check_terms_to_be_used'] = AccountingTransaction::settings($this->user_info->shop_id, 'terms_to_be_used');

        $report_type    = $request->report_type;
        $load_view      = $request->load_view;
        $period         = $request->report_period ? $request->report_period : 'all';
        $date['start']  = $request->from;
        $date['end']    = $request->to;
        $data['from']   = Report::checkDatePeriod($period, $date)['start_date'];
        $data['to']     = Report::checkDatePeriod($period, $date)['end_date'];
        $data["_item_type"]     = Item::get_item_type_list();
        $data["_item_category"] = Item::getItemCategory($this->user_info->shop_id);
        
        $data['w_type'] = Warehouse2::get_warehouse_type(Warehouse2::get_current_warehouse($this->user_info->shop_id));
        
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);

        $_item         = Tbl_item::type()
        ->where("shop_id", $this->user_info->shop_id)
        ->where("tbl_item.archived", 0);
        // dd(Request::input("selected_category"), Request::input("selected_item_type"));
        if($request->selected_category && $request->selected_category != 0)
        {
            $_cat[$request->selected_category] = $request->selected_category; 
            $check_parent_category = Item::check_category($request->selected_category, $_cat);
            $_item = $_item->whereIn("item_category_id",$check_parent_category);
        }
        if($request->selected_item_type && $request->selected_item_type != 0)
        {
            $_item = $_item->where("tbl_item.item_type_id",$request->selected_item_type);            
        }

        $data['_item'] = $_item->orderby('item_name')->get();
        foreach($data['_item'] as $key => $value)
        {
            $count_invty = Self::get_total_balance_qty($data['from'], $data['to'], $value->item_id, $warehouse_id, $this->user_info->shop_id);
            $cost = Item::get_ave_cost_per_warehouse($this->user_info->shop_id, $value->item_id, $warehouse_id);
            $invty_cost = round($cost != "" ? $cost : $value->item_cost,2);
            $data['_item'][$key]->invty_cost = $invty_cost;
            $data['_item'][$key]->invty_count = $count_invty != null ? $count_invty : 0;
            $data['_item'][$key]->total_cost = $count_invty * $data['_item'][$key]->invty_cost; 
            $data['_item'][$key]->total_price = $value->item_price * $count_invty;
        }
        $data['inventory_count_total']  = 0;
        $data['cost_total']         = 0;
        $data['total_cost_total']   = 0;  
        $total_cost_total   = 0;
        $total_price_total  = 0;
        $data['asset_value_total']  = 0;
        $data['price_total']         = 0;
        $data['total_price_total']   = 0;
        foreach ($data['_item'] as $key => $value)
        {
            if($value)
            {
                $data['inventory_count_total'] += $value->invty_count;
                $data['cost_total'] += $value->invty_cost;
                $data['price_total'] += $value->item_price;

                // $total_cost_total += $value->total_cost;
                $data['total_cost_total'] += $value->total_cost;

                $total_price_total += $value->total_price;
                $data['total_price_total'] = $total_price_total == 0 ? 1 : $total_price_total;
            }
        }
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.inventory_summary'; 
            return Report::check_report_type($report_type, $view, $data, 'Inventory Valuation Summary'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.inventory.inventory_summary', $data);
        }
    }
    public function get_total_balance_qty($from, $to, $item_id, $warehouse_id, $shop_id)
    {      
        $item = Tbl_monitoring_inventory::where('invty_shop_id', $this->user_info->shop_id)
                    ->where('invty_item_id', $item_id)
                    ->where('invty_warehouse_id', $warehouse_id)
                    ->orderBy('invty_date_created', 'ASC')
                    ->whereBetween('invty_date_created', [$from, $to])
                    ->get();
        $inventory_count = null;                                  
        foreach ($item as $value) 
        {
            $inventory_count += $value->invty_qty;
        }
        return $inventory_count;
    }
}
