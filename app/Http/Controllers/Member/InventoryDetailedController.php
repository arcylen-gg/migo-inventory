<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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


class InventoryDetailedController extends Member
{
    public function index($item_id, Request $request)
    {
        $data['item_id'] = $item_id;
        $data["action"] = "/member/report/inventory/detailed/".$item_id;
        $data['shop_name']  = $this->user_info->shop_key; 
        $data['head_title']  = 'Inventory - Detailed'; 
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
            $view =  'member.reports.output.inventory_detailed'; 
            return Report::check_report_type($report_type, $view, $data, 'Inventory Valuation Detailed -'.Carbon::now());
        }
        else
        {
            return view("member.reports.inventory.inventory_detailed",$data);
        }

    }
    public function get_all_transactions($item_id, $warehouse_id, $from, $to)
    {
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $transactions = Tbl_monitoring_inventory::where('invty_shop_id', $this->user_info->shop_id)
                    ->where('invty_item_id', $item_id)
                    ->where('invty_warehouse_id', $warehouse_id)
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
                /*$warehouse_id       = Warehouse2::get_current_warehouse($this->user_info->shop_id);
                Warehouse2::load_stock_on_hand($this->user_info->shop_id, $warehouse_id, $item_id, $value->invty_id);*/
                
                if($value->invty_transaction_name == 'initial_qty')
                {
                    $transaction_name = 'Initial Quantity';
                    $get_transaction  = Tbl_item::where('item_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->item_date_created));
                    $transaction_ref_num = '';
                    $transaction_company = '';
                    $transaction_status = 'in';
                }
                else if($value->invty_transaction_name == 'receive_inventory')
                {
                    $transaction_name = 'Receive Inventory';
                    $get_transaction = Tbl_receive_inventory::vendor()->where('ri_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->ri_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->ri_id;
                    $transaction_company = $get_transaction->vendor_company != '' ? ucfirst($get_transaction->vendor_company) : ucfirst($get_transaction->vendor_title_name." ".$get_transaction->vendor_first_name." ".$get_transaction->vendor_middle_name." ".$get_transaction->vendor_last_name." ".$get_transaction->vendor_suffix_name);
                    $transaction_status = 'in';
                    $value->invty_transaction_id = 'ri'.$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'enter_bills')
                {
                    $transaction_name = 'Enter Bills';
                    $get_transaction = Tbl_bill::vendor()->where('bill_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->bill_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->bill_id;
                    $transaction_company = $get_transaction->vendor_company != '' ? ucfirst($get_transaction->vendor_company) : ucfirst($get_transaction->vendor_title_name." ".$get_transaction->vendor_first_name." ".$get_transaction->vendor_middle_name." ".$get_transaction->vendor_last_name." ".$get_transaction->vendor_suffix_name);
                    $transaction_status = 'in';
                    $value->invty_transaction_id = 'eb'.$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'write_check')
                {
                    $transaction_name = 'Write Check';
                    $get_transaction = Tbl_write_check::vendor()->customer()->where('wc_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->wc_payment_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->wc_id;
                    $transaction_status = 'in';
                    if($get_transaction->wc_reference_name == 'vendor')
                    {
                        $transaction_company = $get_transaction->vendor_company != '' ? ucfirst($get_transaction->vendor_company) : ucfirst($get_transaction->vendor_title_name." ".$get_transaction->vendor_first_name." ".$get_transaction->vendor_middle_name." ".$get_transaction->vendor_last_name." ".$get_transaction->vendor_suffix_name);
                    }
                    else
                    {
                        $transaction_company = ucfirst($get_transaction->company) != '' ? ucfirst($get_transaction->company) : ucfirst($get_transaction->title_name)." ".ucfirst($get_transaction->first_name)." ".ucfirst($get_transaction->middle_name)." ".ucfirst($get_transaction->last_name)." ".ucfirst($get_transaction->suffix_name);
                    }
                    $value->invty_transaction_id = 'wc'.$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'credit_memo')
                {
                    $transaction_name = 'Credit Memo';
                    $get_transaction = Tbl_credit_memo::customer()->where('cm_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->cm_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->db_id;
                    $transaction_company = $get_transaction->company != '' ? ucfirst($get_transaction->company) : ucfirst($get_transaction->title_name." ".$get_transaction->first_name." ".$get_transaction->middle_name." ".$get_transaction->last_name." ".$get_transaction->suffix_name);    
                    $transaction_status = 'in';
                    $value->invty_transaction_id = 'cm'.$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'sales_invoice' || $value->invty_transaction_name == 'sales_receipt')
                {
                    $get_transaction  = Tbl_customer_invoice::customer()->where('inv_id', $value->invty_transaction_id)->first();

                    if(count($get_transaction) > 0)
                    {
                        if($get_transaction->is_sales_receipt == 0)
                        {
                            $transaction_name = 'Sales Invoice';
                        }
                        else
                        {
                            $transaction_name = 'Sales Receipt';
                        }
                    }
                    $transaction_date = $get_transaction->inv_date;
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->inv_id;
                    $transaction_company = $get_transaction->company != '' ? ucfirst($get_transaction->company) : ucfirst($get_transaction->title_name." ".$get_transaction->first_name." ".$get_transaction->middle_name." ".$get_transaction->last_name." ".$get_transaction->suffix_name);      
                    $transaction_status = 'out';

                    $value->invty_transaction_id = 'sr'.$value->invty_transaction_id.$value->invty_id;
                }
                else if ($value->invty_transaction_name == 'customer_wis')
                {
                    $transaction_name = 'Customer - WIS';
                    $get_transaction  = Tbl_customer_wis::customerinfo()->where('cust_wis_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->cust_delivery_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->cust_wis_id;
                    $transaction_company = $get_transaction->company != '' ? ucfirst($get_transaction->company) : ucfirst($get_transaction->title_name." ".$get_transaction->first_name." ".$get_transaction->middle_name." ".$get_transaction->last_name." ".$get_transaction->suffix_name);  
                    $transaction_status = 'out';
                    $value->invty_transaction_id = 'cwis'.$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'debit_memo')
                {
                    $transaction_name = 'Debit Memo';
                    $get_transaction = Tbl_debit_memo::vendor()->where('db_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->db_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->db_id;
                    $transaction_company = $get_transaction->vendor_company != '' ? ucfirst($get_transaction->vendor_company) : ucfirst($get_transaction->vendor_title_name." ".$get_transaction->vendor_first_name." ".$get_transaction->vendor_middle_name." ".$get_transaction->vendor_last_name." ".$get_transaction->vendor_suffix_name);
                    $transaction_status = 'out';

                    $value->invty_transaction_id = 'db'.$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'wis')
                {
                    $transaction_name = 'Warehouse Transfer';
                    $get_transaction  = Tbl_warehouse_issuance_report::destinationWarehouse()->where('wis_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->wis_delivery_date));
                    $transaction_ref_num =$get_transaction->wis_number != '' ? $get_transaction->wis_number : $get_transaction->wis_id;
                    $transaction_company = ucfirst($get_transaction->warehouse_name) ;
                    $transaction_status = 'out';

                    $value->invty_transaction_id = 'wis'.$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'rr')
                {
                    $transaction_name = 'Receive Transfer';
                    $get_transaction  = Tbl_warehouse_receiving_report::Wis()->where('rr_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->created_at));
                    $transaction_ref_num = $get_transaction->rr_number != '' ? $get_transaction->rr_number : $get_transaction->rr_id;
                    $warehouse = Tbl_warehouse::where('warehouse_id', $get_transaction->wis_from_warehouse)->first();
                    $transaction_company = $warehouse->warehouse_name;
                    $transaction_status = 'in';

                    $value->invty_transaction_id = 'rr'.$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'adjust_inventory')
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
                    $value->invty_transaction_id = 'adj'.$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'manual_adjust_inventory')
                {
                    $transaction_name = 'Manual Adjust Inventory';
                    $get_transaction  = Tbl_item::where('item_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($value->invty_date_created));
                    $transaction_ref_num = '';
                    $transaction_company = ''; 
                    if($value->invty_qty > 0)
                    {
                        $transaction_status = 'in';
                    }
                    else
                    {
                        $transaction_status = 'out';
                    }
                    $value->invty_transaction_id = "mai".$value->invty_transaction_id.$value->invty_id;
                }
                else if($value->invty_transaction_name == 'import_adjust_inventory')
                {
                    $transaction_name = 'Import Adjust Inventory';
                    $get_transaction  = Tbl_item::where('item_id', $value->invty_transaction_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($value->invty_date_created));
                    $transaction_ref_num = '';
                    $transaction_company = ''; 
                    if($value->invty_qty > 0)
                    {
                        $transaction_status = 'in';
                    }
                    else
                    {
                        $transaction_status = 'out';
                    }
                    $value->invty_transaction_id = "iai".$value->invty_transaction_id.$value->invty_id;
                }
                else
                {
                    $transaction_name = 'Unkown transaction';
                    if($value->invty_qty > 0)
                    {
                        $transaction_status = 'in';
                    }
                    else
                    {
                        $transaction_status = 'out';
                    }
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
    public function get_total_balance_qty($from, $item_id)
    {
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);

        $items = Tbl_item::type()->where('shop_id', $this->user_info->shop_id)->where('item_id', $item_id)->get();
        foreach ($items as $key => $value)
        {   
            $data = Tbl_warehouse_inventory_record_log::where('record_item_id', $value->item_id)
                                                        ->where('record_log_date_updated','<', $from)
                                                        ->orderBy('record_log_date_updated', 'ASC')
                                                        ->get();

            $inventory_count_balance = 0 ;
            foreach($data as $total) 
            {   
               $inventory_count_balance += $total->inventory_count;  
            }   

            $return  = $inventory_count_balance;
            return $return;
        }
    }
    public function report_header($data)
    {
        return view('member.reports.head', $data);
    }
    public function get_transaction($item_id, $warehouse_id, $from, $to)
    {

        $data = Tbl_warehouse_inventory_record_log::where('tbl_warehouse_inventory_record_log.record_warehouse_id', $warehouse_id)
                                                               ->where('record_item_id', $item_id)
                                                               ->orderBy('record_log_date_updated', 'ASC')
                                                               ->get();
        $transaction[$item_id]= null;
        $transaction_name = null;
        $transaction_date = null;
        $transaction_ref_num = null;
        $transaction_company = null;
        $transaction_qty = null;
        $transaction_total_item_cost = null;
        $transaction_total_item_price = null;
        $transaction_status = null;
        $transaction_item_cost = null;
        $transaction_item_price = null;
        $warehouse = null;
        foreach ($data as $key => $value)
        { 
            $item = Tbl_item::where('shop_id', $this->user_info->shop_id)->where('item_id', $item_id)->first();
            $transaction_item_cost = $item->item_cost;
            $transaction_item_price = $item->item_price;
            if($value->record_source_ref_name && $value->record_source_ref_id)
            {
                /*WRITE CHECK TRANSACTION*/
                if($value->record_source_ref_name == 'write_check')
                {
                    $transaction_name = 'Write Check';
                    $get_transaction = Tbl_write_check::vendor()->customer()->where('wc_id', $value->record_source_ref_id)->first();

                    $transaction_date = date('m/d/Y', strtotime($get_transaction->date_created));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->wc_id;
                    if($get_transaction->wc_reference_name == 'vendor')
                    {
                        $transaction_company = $get_transaction->vendor_company != '' ? ucfirst($get_transaction->vendor_company) : ucfirst($get_transaction->vendor_title_name)." ".ucfirst($get_transaction->vendor_first_name)." ".ucfirst($get_transaction->vendor_middle_name)." ".ucfirst($get_transaction->vendor_last_name)." ".ucfirst($get_transaction->vendor_suffix_name);
                    }
                    else
                    {
                        $transaction_company = ucfirst($get_transaction->company) != '' ? ucfirst($get_transaction->company) : ucfirst($get_transaction->title_name)." ".ucfirst($get_transaction->first_name)." ".ucfirst($get_transaction->middle_name)." ".ucfirst($get_transaction->last_name)." ".ucfirst($get_transaction->suffix_name);
                        
                    }
                    $transaction_qty = Self::get_qty_in_per_transaction($warehouse_id, $item_id, $value->record_source_ref_id, $value->record_source_ref_name);
                    $transaction_total_item_cost = $item->item_cost * $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_qty'] = $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_status'] = 'in';
                }
                /*ENTER BILLS TRANSACTION*/
                elseif($value->record_source_ref_name == 'enter_bills')
                {
                    $transaction_name = 'Enter Bills';
                    $get_transaction = Tbl_bill::vendor()->where('bill_id', $value->record_source_ref_id)->first();

                    $transaction_date = date('m/d/Y', strtotime($get_transaction->bill_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->bill_id;
                    $transaction_company = $get_transaction->vendor_company != '' ? ucfirst($get_transaction->vendor_company) : ucfirst($get_transaction->vendor_title_name)." ".ucfirst($get_transaction->vendor_first_name)." ".ucfirst($get_transaction->vendor_middle_name)." ".ucfirst($get_transaction->vendor_last_name)." ".ucfirst($get_transaction->vendor_suffix_name);
                    $transaction_qty = Self::get_qty_in_per_transaction($warehouse_id, $item_id, $value->record_source_ref_id, $value->record_source_ref_name);
                    $transaction_total_item_cost = $item->item_cost * $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_qty'] = $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_status'] = 'in';
                }
                /*RECEIVE INVENTORY TRANSACTION*/
                elseif($value->record_source_ref_name == 'receive_inventory')
                {
                    $transaction_name = 'Receive Inventory';
                    $get_transaction = Tbl_receive_inventory::vendor()->where('ri_id', $value->record_source_ref_id)->first();

                    $transaction_date = date('m/d/Y', strtotime($get_transaction->ri_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->ri_id;
                    $transaction_company = $get_transaction->vendor_company != '' ? ucfirst($get_transaction->vendor_company) : ucfirst($get_transaction->vendor_title_name)." ".ucfirst($get_transaction->vendor_first_name)." ".ucfirst($get_transaction->vendor_middle_name)." ".ucfirst($get_transaction->vendor_last_name)." ".ucfirst($get_transaction->vendor_suffix_name);
                    $transaction_qty = Self::get_qty_in_per_transaction($warehouse_id, $item_id, $value->record_source_ref_id, $value->record_source_ref_name);
                    $transaction_total_item_cost = $item->item_cost * $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_qty'] = $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_status'] = 'in';
                }
                /*CREDIT MEMO TRANSACTION*/
                elseif($value->record_source_ref_name == 'credit_memo')
                {
                    $transaction_name = 'Credit Memo';
                    $get_transaction = Tbl_credit_memo::customer()->where('cm_id', $value->record_source_ref_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->cm_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->cm_id;
                    $transaction_company = ucfirst($get_transaction->company) != '' ? ucfirst($get_transaction->company) : ucfirst($get_transaction->title_name)." ".ucfirst($get_transaction->first_name)." ".ucfirst($get_transaction->middle_name)." ".ucfirst($get_transaction->last_name)." ".ucfirst($get_transaction->suffix_name);                    
                    $transaction_qty = Self::get_qty_in_per_transaction($warehouse_id, $item_id, $value->record_source_ref_id, $value->record_source_ref_name);
                    $transaction_total_item_cost = $item->item_cost * $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_qty'] = $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_status'] = 'in';

                }
            
                /*INITIAL QTY TRANSACTION*/
                elseif($value->record_source_ref_name == 'initial_qty')
                {
                    $transaction_name = 'Initial Quantity';
                    $get_transaction  = Tbl_item::where('item_id', $value->record_source_ref_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->item_date_created));
                    $transaction_ref_num = '';
                    $transaction_company = '';
                    $transaction_qty = Self::get_qty_in_per_transaction($warehouse_id, $item_id, $value->record_source_ref_id, $value->record_source_ref_name);
                    
                    $transaction_total_item_cost = $item->item_cost * $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_qty'] = $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_status'] = 'in';

                }
                /*RR TRANSACTION*/
                elseif($value->record_source_ref_name == 'rr')
                {
                    $transaction_name = 'Receive Transfer';
                    $get_transaction  = Tbl_warehouse_receiving_report::Wis()->where('rr_id', $value->record_source_ref_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->created_at));
                    $transaction_ref_num = $get_transaction->rr_number != '' ? $get_transaction->rr_number : $get_transaction->rr_id;
                    $warehouse = Tbl_warehouse::where('warehouse_id', $get_transaction->wis_from_warehouse)->first();
                    $transaction_company = $warehouse->warehouse_name;
                    $transaction_qty = Self::get_qty_in_per_transaction($warehouse_id, $item_id, $value->record_source_ref_id, $value->record_source_ref_name);
                    $transaction_total_item_cost = $item->item_cost * $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_qty'] = $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_status'] = 'in';

                }
                elseif($value->record_source_ref_name == 'adjust_inventory')
                {
                    $transaction_name = 'Inventory Adjustment';
                    $get_transaction  = Tbl_inventory_adjustment::warehouse()->where('inventory_adjustment_id', $value->record_source_ref_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->date_created));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->inventory_adjustment_id;
                    $warehouse = Tbl_warehouse::where('warehouse_id', $get_transaction->adj_warehouse_id)->first();
                    $transaction_company = $warehouse->warehouse_name;
                    $transaction_qty = Self::get_qty_in_per_transaction($warehouse_id, $item_id, $value->record_source_ref_id, $value->record_source_ref_name);
                    $transaction_total_item_cost = $item->item_cost * $transaction_qty;

                    $transaction_change_qty = Self::get_item_inventory_by_transaction($item_id, $warehouse_id ,$value->record_source_ref_name, $value->record_source_ref_id);
                    /*$transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_change_qty'] = $transaction_change_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_status']     = 'actual_qty';
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_qty']        = $transaction_qty;*/
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_qty'] = $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_status'] = 'in';

                }
                if(/*$value->record_source_ref_name != "adjust_inventory" && */$value->record_source_ref_name && $value->record_source_ref_id)
                {

                    /*$transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_qty']     = $transaction_qty;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_status'] = 'in';*/
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_name']    = $transaction_name;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_date']    = $transaction_date;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_ref_num'] = $transaction_ref_num;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_company'] = $transaction_company;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_item_cost'] = $transaction_item_cost;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_item_price'] = $transaction_item_price ;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_total_item_cost'] = $transaction_total_item_cost;
                    $transaction[$item_id][$value->record_source_ref_name.$value->record_source_ref_id]['transaction_total_item_price'] = $transaction_total_item_price;

                }
            }
            if($value->record_consume_ref_id && $value->record_consume_ref_name)
            {
                /* CUSTOMER WIS TRANSACTION*/
                if ($value->record_consume_ref_name == 'customer_wis')
                {
                    $transaction_name = 'Customer - WIS';
                    $get_transaction  = Tbl_customer_wis::customerinfo()->where('cust_wis_id', $value->record_consume_ref_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->cust_delivery_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->cust_wis_id;
                    $transaction_company = ucfirst($get_transaction->company) != '' ? ucfirst($get_transaction->company) : ucfirst($get_transaction->title_name)." ".ucfirst($get_transaction->first_name)." ".ucfirst($get_transaction->middle_name)." ".ucfirst($get_transaction->last_name)." ".ucfirst($get_transaction->suffix_name);
                    $transaction_qty = Self::get_qty_out_per_transaction($warehouse_id, $item_id, $value->record_consume_ref_id, $value->record_consume_ref_name) ;
                    $transaction_total_item_price = $item->item_price * $transaction_qty; 
                    $transaction_qty   = $transaction_qty * -1;
                    $transaction_status = 'out';
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_qty']    = $transaction_qty;
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_status']    = $transaction_status;

                }
                 /*DEBIT MEMO TRANSACTION*/
                if($value->record_consume_ref_name == 'debit_memo')
                {
                    $transaction_name = 'Debit Memo';
                    $get_transaction = Tbl_debit_memo::vendor()->where('db_id', $value->record_consume_ref_id)->first();

                    $transaction_date = date('m/d/Y', strtotime($get_transaction->db_date));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->db_id;
                    $transaction_company = $get_transaction->vendor_company != '' ? ucfirst($get_transaction->vendor_company) : ucfirst($get_transaction->vendor_title_name)." ".ucfirst($get_transaction->vendor_first_name)." ".ucfirst($get_transaction->vendor_middle_name)." ".ucfirst($get_transaction->vendor_last_name)." ".ucfirst($get_transaction->vendor_suffix_name);
                    $transaction_qty = Self::get_qty_out_per_transaction($warehouse_id, $item_id, $value->record_consume_ref_id, $value->record_consume_ref_name);
                    $transaction_total_item_cost = $item->item_cost * $transaction_qty;
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_qty']     = $transaction_qty * -1;
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_status'] = 'out';
                }
                /*WAREHOUSE TRANSFER WIS TRANSACTION*/
                if ($value->record_consume_ref_name == 'wis')
                {
                    $transaction_name = 'Warehouse Transfer';
                    $get_transaction  = Tbl_warehouse_issuance_report::destinationWarehouse()->where('wis_id', $value->record_consume_ref_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->wis_delivery_date));
                    $transaction_ref_num =$get_transaction->wis_number != '' ? $get_transaction->wis_number : $get_transaction->wis_id;
                    $transaction_company = ucfirst($get_transaction->warehouse_name) ;
                    $transaction_qty = Self::get_qty_out_per_transaction($warehouse_id, $item_id, $value->record_consume_ref_id, $value->record_consume_ref_name);
                    $transaction_total_item_price = $item->item_price * $transaction_qty;
                    $transaction_qty  = $transaction_qty * -1;
                    $transaction_status = 'out';
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_qty']    = $transaction_qty;
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_status']    = $transaction_status;
                }
                /*SALES INVOICE TRANSACTION*/
                elseif ($value->record_consume_ref_name == 'sales_invoice')
                {
                    $get_transaction  = Tbl_customer_invoice::customer()->where('inv_id', $value->record_consume_ref_id, $transaction_name)->first();

                    if(count($get_transaction) > 0)
                    {
                        if($get_transaction->is_sales_receipt == 0)
                        {
                            $transaction_name = 'Sales Invoice';
                        }
                        else
                        {
                            $transaction_name = 'Sales Receipt';
                        }
                    }
                    $transaction_date = $get_transaction->inv_date;
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->inv_id;
                    $transaction_company = ucfirst($get_transaction->company) != '' ? ucfirst($get_transaction->company) : ucfirst($get_transaction->title_name)." ".ucfirst($get_transaction->first_name)." ".ucfirst($get_transaction->middle_name)." ".ucfirst($get_transaction->last_name)." ".ucfirst($get_transaction->suffix_name);
                    $transaction_qty = Self::get_qty_out_per_transaction($warehouse_id, $item_id, $value->record_consume_ref_id, $value->record_consume_ref_name);
                    $transaction_total_item_price = $item->item_price * $transaction_qty;
                    $transaction_qty   = $transaction_qty * -1;         
                    $transaction_status = 'out';
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_qty']    = $transaction_qty;
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_status']    = $transaction_status;
                }
                /*SALES RECEIPT TRANSACTION*/
                elseif ($value->record_consume_ref_name == 'sales_receipt')
                {
                    $get_transaction  = Tbl_customer_invoice::customer()->where('inv_id', $value->record_consume_ref_id, $transaction_name)->first();

                    if(count($get_transaction) > 0)
                    {
                        if($get_transaction->is_sales_receipt == 0)
                        {
                            $transaction_name = 'Sales Invoice';
                        }
                        else
                        {
                            $transaction_name = 'Sales Receipt';
                        }
                    }
                    $transaction_date = $get_transaction->inv_date;
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->inv_id;
                    $transaction_company = ucfirst($get_transaction->company) != '' ? ucfirst($get_transaction->company) : ucfirst($get_transaction->title_name)." ".ucfirst($get_transaction->first_name)." ".ucfirst($get_transaction->middle_name)." ".ucfirst($get_transaction->last_name)." ".ucfirst($get_transaction->suffix_name);
                    $transaction_qty = Self::get_qty_out_per_transaction($warehouse_id, $item_id, $value->record_consume_ref_id, $value->record_consume_ref_name);
                    $transaction_total_item_price = $item->item_price * $transaction_qty;
                    $transaction_qty   = $transaction_qty * -1;
                    $transaction_status = 'out';
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_qty']    = $transaction_qty;
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_status']    = $transaction_status;
                }
                elseif($value->record_consume_ref_name == 'adjust_inventory')
                {
                    $transaction_name = 'Inventory Adjustment';
                    $get_transaction  = Tbl_inventory_adjustment::warehouse()->where('inventory_adjustment_id', $value->record_consume_ref_id)->first();
                    $transaction_date = date('m/d/Y', strtotime($get_transaction->date_created));
                    $transaction_ref_num = $get_transaction->transaction_refnum != '' ? $get_transaction->transaction_refnum : $get_transaction->inventory_adjustment_id;
                    $warehouse = Tbl_warehouse::where('warehouse_id', $get_transaction->adj_warehouse_id)->first();
                    $transaction_company = $warehouse->warehouse_name;
                    $transaction_qty = Self::get_qty_out_per_transaction($warehouse_id, $item_id, $value->record_consume_ref_id, $value->record_consume_ref_name);
                    $transaction_total_item_cost = $item->item_cost * $transaction_qty;
                    $transaction_change_qty = Self::get_item_inventory_by_transaction($item_id, $warehouse_id ,$value->record_consume_ref_name, $value->record_consume_ref_id);
                    /*$transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_change_qty'] = $transaction_change_qty;
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_status']     = 'actual_qty';
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_qty']        = $transaction_qty;*/
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_qty'] = $transaction_qty *-1;
                    $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_status'] = 'out';
                }

               /* $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_qty']    = $transaction_qty;
                $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_status']    = $transaction_status;*/
                $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_name']    = $transaction_name;
                $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_date']    = $transaction_date;
                $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_ref_num'] = $transaction_ref_num;
                $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_company'] = $transaction_company;
                $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_item_cost'] = $transaction_item_cost;
                $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_item_price'] = $transaction_item_price ;
                $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_total_item_cost'] = $transaction_total_item_cost;
                $transaction[$item_id][$value->record_consume_ref_name.$value->record_consume_ref_id]['transaction_total_item_price'] = $transaction_total_item_price;
            }
            // usort($transaction[$item_id], function($a, $b)
            // {
            //     if($a['transaction_date'] == $b['transaction_date']) return 0;
            //     return $a['transaction_date'] < $b['transaction_date']?1:-1;
            // });
        }
        return $transaction;
    }

    public static function get_item_inventory_by_transaction($item_id, $warehouse_id = 0, $ref_name = '', $ref_id = '')
    {
        $offset = Tbl_warehouse_inventory_record_log::where("record_warehouse_id",$warehouse_id)
                                                    ->where('record_item_id', $item_id)
                                                    ->where('record_count_inventory',0)
                                                    ->where("record_source_ref_name", $ref_name)
                                                    ->where("record_source_ref_id", $ref_id);
        $current = Tbl_warehouse_inventory_record_log::where("record_item_id",$item_id)
                                                   ->where("record_inventory_status",0)
                                                   ->where("record_count_inventory",1)
                                                   ->where("record_source_ref_name", $ref_name)
                                                   ->where("record_source_ref_id", $ref_id);
 
        $offset_qty = $offset->count();
        $current_qty = $current->count();
        return $current_qty - $offset_qty;
    }
    public function get_qty_in_per_transaction($warehouse_id, $item_id, $transaction_id, $transaction_name)
    {


        $data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $warehouse_id)
                                                               ->where('record_item_id', $item_id)
                                                               ->where('record_source_ref_id', $transaction_id)
                                                               ->where('record_source_ref_name', $transaction_name)
                                                               ->get();

        $total_qty = 0;
        foreach ($data as $key => $value)
        {
            $total_qty += $value->record_count_inventory;
        }

        return $total_qty;
    }
    public function get_qty_out_per_transaction($warehouse_id, $item_id, $transaction_id, $transaction_name)
    {

        $data = Tbl_warehouse_inventory_record_log::where('record_warehouse_id', $warehouse_id)
                                                               ->where('record_item_id', $item_id)
                                                               ->where('record_consume_ref_id', $transaction_id)
                                                               ->where('record_consume_ref_name', $transaction_name)
                                                               ->get();

        
        $total_qty = 0;
        foreach ($data as $key => $value)
        {
            $total_qty += $value->record_count_inventory;
        }
        return $total_qty;
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
