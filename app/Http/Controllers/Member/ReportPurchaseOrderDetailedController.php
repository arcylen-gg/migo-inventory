<?php

namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Globals\Report;
use App\Globals\Settings;
use App\Models\Tbl_quantity_monitoring;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_write_check;
use App\Models\Tbl_purchase_order_line;
use App\Models\Tbl_item;
use App\Models\Tbl_item_type;
use App\Models\Tbl_settings;
use App\Models\Tbl_category;
use Carbon\Carbon;

class ReportPurchaseOrderDetailedController extends Member
{
    public function index(Request $request)
    {
        $data["action"] = "/member/report/open/purchase/order";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Open Purchase Order'; 
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

        $_item_type = Tbl_item_type::get();
        $data['_type'] = null;
        $_items = null;

        foreach ($_item_type as $key_item_type => $value_item_type) 
        {
            $name = str_replace(' ','_', $value_item_type->item_type_name);

            $checked_item_types = Tbl_settings::where('shop_id', $this->user_info->shop_id)->where('settings_key', $name)->first();

            if(!is_null($checked_item_types))
            {
                $checked_item_types->toArray();

                if($checked_item_types['settings_value'] == 1 )
                {
                    $data['_type']['type_name'][$checked_item_types['settings_key']] = Self::get_items_per_category($checked_item_types['settings_key'], $data['from'], $data['to']);
                    $data['_type']['type_name'][$checked_item_types['settings_key']]['total'] = Self::get_total_per_item_type($checked_item_types['settings_key'], $data['from'], $data['to'], 'total');
                    $data['_type']['type_name'][$checked_item_types['settings_key']]['total_qty'] = Self::get_total_per_item_type($checked_item_types['settings_key'], $data['from'], $data['to'], 'total_qty');
                    $data['_type']['type_name'][$checked_item_types['settings_key']]['total_received_qty'] = Self::get_total_per_item_type($checked_item_types['settings_key'], $data['from'], $data['to'], 'total_received_qty');
                    $data['_type']['type_name'][$checked_item_types['settings_key']]['total_backorder_qty'] = Self::get_total_per_item_type($checked_item_types['settings_key'], $data['from'], $data['to'], 'total_backorder_qty');
                    $data['_type']['type_name'][$checked_item_types['settings_key']]['total_amount'] = Self::get_total_per_item_type($checked_item_types['settings_key'], $data['from'], $data['to'], 'total_amount');  
                }
            }
        }
        $data['total'] = 0;
        $data['total_qty'] = 0;  
        $data['total_received_qty'] = 0; 
        $data['total_backorder_qty'] = 0;
        $data['total_amount'] = 0;
        foreach ($data['_type']['type_name'] as $key => $value)
        {
            $data['total'] += $value['total'];
            $data['total_qty'] += $value['total_qty'];
            $data['total_received_qty'] += $value['total_received_qty'];
            $data['total_backorder_qty'] += $value['total_backorder_qty'];
            $data['total_amount'] += $value['total_amount'];
        }
        // dd($data);
        //dd($data['_type']);
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.open_purchase_order_detailed'; 
            return Report::check_report_type($report_type, $view, $data, 'Open Purchase Order by Item'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.inventory.open_purchase_order_detailed', $data);
        }
    }
    public function get_items_per_category($type_name, $from, $to)
    {
        $_category = Tbl_category::where('type_shop', $this->user_info->shop_id)->where('type_category', $type_name)->where('archived', 0)->get();

        $items['acategory'] = null;
        $poline_total = 0;
        if($_category)
        {
            foreach ($_category as $key => $value)
            {
                if($value)
                {
                    $items['acategory'][$value->type_name]['items'] = Self::get_poline_per_item($value->type_id, $from, $to);
                    $items['acategory'][$value->type_name]['items_total'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total');
                    $items['acategory'][$value->type_name]['items_total_qty'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total_qty');
                    $items['acategory'][$value->type_name]['items_total_received_qty'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total_received_qty');
                    $items['acategory'][$value->type_name]['items_total_backorder_qty'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total_backorder_qty');
                    $items['acategory'][$value->type_name]['items_total_amount'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total_amount');

                }
            }
        }
        //dd($items);
        return $items;
    }
    public function get_poline_per_item($category_id, $from, $to)
    {
        $items = Tbl_item::where('shop_id',$this->user_info->shop_id)->where('item_category_id', $category_id)->where('archived', 0)->get();
        $poline = null;
        foreach ($items as $key => $value) 
        {
            $poline[$value->item_name]['poline'] = Tbl_purchase_order_line::POvendorUm()->where('poline_item_id', $value->item_id)->where('po_is_billed', 0)->whereBetween('po_date', [$from,$to])->get();
            $poline[$value->item_name]['poline_total'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total');
            $poline[$value->item_name]['poline_total_qty'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total_qty');
            $poline[$value->item_name]['poline_total_received_qty'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total_received_qty');
            $poline[$value->item_name]['poline_total_backorder_qty'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total_backorder_qty');
            $poline[$value->item_name]['poline_total_amount'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total_amount');
        }
        return $poline;
    }
    public function get_all_poline_per_item($item_id, $from, $to, $get)
    {
        $poline = Tbl_purchase_order_line::POvendorUm()->where('poline_item_id', $item_id)->where('po_is_billed', 0)->whereBetween('po_date', [$from,$to])->get();
        $return = 0;
        if($poline)
        {
            foreach ($poline as $key => $value) 
            {
                if($value)
                {
                    if($get == 'total')
                    {
                        $return += $value->poline_amount * $value->poline_qty;
                    }
                    elseif($get == 'total_qty')
                    {
                        $return += $value->poline_orig_qty;
                    }
                    elseif($get == 'total_received_qty')
                    {
                        $return += $value->poline_orig_qty - $value->poline_qty;
                    }
                    elseif($get == 'total_backorder_qty')
                    {
                        $return += $value->poline_qty;
                    }
                    elseif($get == 'total_amount')
                    {
                        $return += $value->poline_amount;
                    }
                }
            }
        }
        return $return;
    }
    public function get_all_item_per_category($category_id, $from, $to, $get)
    {
        $return = 0;
        $items = Tbl_item::where('shop_id',$this->user_info->shop_id)->where('item_category_id', $category_id)->where('archived', 0)->get();
        if($items)
        {
            foreach ($items as $key => $value) 
            {
                if($value->item_id)
                {
                    $items[$key]['po_line'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total');
                    $items[$key]['po_line_total_qty'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total_qty');
                    $items[$key]['po_line_total_received_qty'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total_received_qty');
                    $items[$key]['po_line_total_backorder_qty'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total_backorder_qty');
                    $items[$key]['po_line_total_amount'] = Self::get_all_poline_per_item($value->item_id, $from, $to, 'total_amount');
                }
            }
            foreach ($items as $key_items => $value_items)
            {   
                if($get == 'total')
                {
                    $return += $value_items->po_line;
                }
                elseif($get == 'total_qty')
                {
                    $return += $value_items->po_line_total_qty;
                }
                elseif($get == 'total_received_qty')
                {
                    $return += $value_items->po_line_total_received_qty;
                }
                elseif($get == 'total_backorder_qty')
                {
                    $return += $value_items->po_line_total_backorder_qty;
                }
                elseif($get == 'total_amount')
                {
                    $return += $value_items->po_line_total_amount;
                }
            }
        }
        //dd($return);
        return $return;
    }
    public function get_total_per_item_type($type_name, $from, $to, $get)
    {
        $return = 0;
        $items = Tbl_category::where('type_shop', $this->user_info->shop_id)->where('type_category', $type_name)->where('archived', 0)->get();
        if($items)
        {
            foreach ($items as $key => $value) 
            {
                if($value)
                {
                    $items[$key]['category'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total');
                    $items[$key]['category_total_qty'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total_qty');
                    $items[$key]['category_total_received_qty'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total_received_qty');
                    $items[$key]['category_total_backorder_qty'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total_backorder_qty');
                    $items[$key]['category_total_amount'] = Self::get_all_item_per_category($value->type_id, $from, $to, 'total_amount');
                }
            }
            foreach ($items as $key_items => $value_items)
            {
                if($get == 'total')
                {
                    $return += $value_items->category;
                }
                elseif($get == 'total_qty')
                {
                    $return += $value_items->category_total_qty;
                }
                elseif($get == 'total_received_qty')
                {
                    $return += $value_items->category_total_received_qty;
                }
                elseif($get == 'total_backorder_qty')
                {
                    $return += $value_items->category_total_backorder_qty;
                }
                elseif($get == 'total_amount')
                {
                    $return += $value_items->category_total_amount;
                }
            }
        }
        return $return;
    }
    public function report_header($data)
    {
        return view('member.reports.head', $data);
    }
}
