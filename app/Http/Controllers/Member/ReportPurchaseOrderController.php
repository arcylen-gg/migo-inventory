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
use Carbon\Carbon;
class ReportPurchaseOrderController extends Member
{
    public function index(Request $request)
    {
        $data["action"] = "/member/report/open/purchase/order";
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Open Purchase Order by Item'; 
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
        $data['_po'] = Tbl_purchase_order::vendor()->where('po_is_billed', 0)->where('po_shop_id', $this->user_info->shop_id)->whereBetween("po_date",[$data['from'], $data['to']])->orderBy('po_date', "DESC")->get();
        foreach($data['_po'] as $key => $value)
        {
            $data['_po'][$key]['monitoring_qty'] = Self::quantity_monitoring($value->po_id);
            $data['_po'][$key]['balance'] = Self::balance($value->po_id);
        }  
        /* IF REPORT TYPE IS EXIST AND NOT RETURNING VIEW */
        if($report_type && !$load_view)
        {
            $view =  'member.reports.output.open_purchase_order'; 
            return Report::check_report_type($report_type, $view, $data, 'Open Purchase Order by Item'.Carbon::now(), 'landscape');
        }
        else
        {
            return view('member.reports.inventory.open_purchase_order', $data);
        }
    }
    public function report_header($data)
    {
        return view('member.reports.head', $data);
    }
    public function quantity_monitoring($po_id, $from="", $to="")
    {
        $poline = Tbl_purchase_order_line::where('poline_po_id', $po_id)->get();
        $data = null;
        if($poline)
        {
            foreach ($poline as $key => $value) 
            {
                if($value)
                {
                    $item = Tbl_item::where('item_id', $value->poline_item_id)->first();
                    if($item)
                    {
                        $data[$value->poline_item_id]['item_name'] = $item->item_name;
                        $data[$value->poline_item_id]['orig_qty'] = $value->poline_orig_qty;
                        $data[$value->poline_item_id]['backorder'] = $value->poline_qty;
                        $data[$value->poline_item_id]['received'] = $value->poline_orig_qty - $value->poline_qty;
                        $data[$value->poline_item_id]['rate'] = $value->poline_rate;
                        $data[$value->poline_item_id]['amount'] = $value->poline_amount;
                        $data[$value->poline_item_id]['total'] = $value->poline_amount;
                    }
                }
            }
        }
        return $data;
    }
    public function balance($po_id)
    {
        $poline = Tbl_purchase_order_line::where('poline_po_id', $po_id)->get();

        if($poline)
        {
            $balance = null;
            foreach ($poline as $key => $value) 
            {
                if($value->poline_amount)
                {
                    $balance += $value->poline_amount;
                }
            }
        }
        return $balance;
    }
}