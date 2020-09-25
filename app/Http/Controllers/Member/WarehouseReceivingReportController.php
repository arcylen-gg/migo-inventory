<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Globals\Warehouse2;
use App\Globals\WarehouseTransfer;
use App\Globals\AccountingTransaction;
use App\Globals\Pdf_global;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\AuditTrail;

use Session;
use Carbon\Carbon;
class WarehouseReceivingReportController extends Member
{
    public function getIndex()
    {
    	$data['page'] = "Receiving Report";
        $data['_rr'] = WarehouseTransfer::get_all_rr($this->user_info->shop_id);
        Session::forget('wis_id');
    	return view('member.warehousev2.rr.rr_list',$data);
    }
    public function getReceiveCode()
    {
    	$data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id);

    	return view('member.warehousev2.rr.rr_confirm_code',$data);
    }
    public function postInputCode(Request $request)
    {
        $return = WarehouseTransfer::check_wis($this->user_info->shop_id, $request->warehouse_id, $request->receiver_code);
        $data = null;
        if(is_numeric($return))
        {
            $data['status'] = 'success';
            $data['call_function'] = 'success_code';
            $data['redirect'] = '/member/transaction/receiving_report/receive-inventory/'.$return;
            Session::put('wis_id', $return);
        }
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = $return;
        }
        return json_encode($data);
    }
    public function getReceiveItems(Request $request, $wis_id)
    {
        $check = WarehouseTransfer::get_wis_data($wis_id);
        if($check)
        {
            Session::put('wis_id',$wis_id);
            return redirect('/member/transaction/receiving_report/receive-inventory/'.$wis_id);
        }
    }
    public function getReceiveInventory(Request $request, $wis_id)
    {
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['wis']        = WarehouseTransfer::get_wis_data($wis_id);
        $data['wis_item']   = WarehouseTransfer::get_wis_itemline($wis_id);
        $data['_item']      = Item::get_all_category_item([1,4,5], null, null, null, true);
        $data['wis_id']     = $wis_id;
        $data['transaction_refnum'] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'receiving_report'); 

        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);

        if($data['wis'])
        {
            if($data['wis']->wis_status == 'confirm')
            {
                
            }
            else
            {
                return redirect('/member/transaction/receiving_report');
            }
        }
        return view('member.warehousev2.rr.rr_receive_inventory',$data);
    }
    public function postReceiveInventorySubmit(Request $request)
    {
        $btn_action = $request->button_action;
        $shop_id = $this->user_info->shop_id;
        $ins_rr['rr_shop_id'] = $shop_id;
        $ins_rr['rr_number'] = $request->rr_number;
        $ins_rr['wis_id'] = $request->wis_id;
        $ins_rr['rr_date_received'] = date("Y-m-d", strtotime($request->rr_date_received));
        $ins_rr['warehouse_id'] = Warehouse2::get_current_warehouse($shop_id);
        $ins_rr['rr_remarks'] = $request->rr_remarks;
        $ins_rr['created_at'] = Carbon::now();

        $wis_data = WarehouseTransfer::get_wis_data($request->wis_id);

        $_item = $request->item_id;

        $return = null;

        $items = null;
        if($wis_data)
        {
            foreach ($_item as $key => $value) 
            {
                if($value)
                {
                    $item_um = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                    $qty = $request->item_qty[$key] * UnitMeasurement::get_umqty($item_um);
                    $wis_qty = $request->wis_item_quantity[$key] * UnitMeasurement::get_umqty($request->wis_item_um[$key]);
                    if($wis_qty < $qty)
                    {
                        $return .= "The ITEM no ".$value." is not enough to transfer <br>";
                    }

                    $items[$key] = null;
                    $items[$key]['item_id']          = $value;
                    $items[$key]['item_description'] = $request->item_description[$key];
                    $items[$key]['item_um']          = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                    $items[$key]['item_qty']         = str_replace(",", "", $request->item_qty[$key]);
                    $items[$key]['item_rate']        = str_replace(",", "", $request->item_rate[$key]);
                    $items[$key]['item_amount']      = str_replace(",", "", $request->item_amount[$key]);
                    $items[$key]['item_refname']     = $request->item_refname[$key];
                    $items[$key]['item_refid']       = $request->item_refid[$key];


                    $items[$key]['quantity']         = $items[$key]['item_qty'] * UnitMeasurement::get_umqty($items[$key]['item_um']);

                    $items[$key]['remarks'] = 'Transfer item no. '.$value.' from WIS -('.$wis_data->wis_number.')';      

                    $items[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                    $items[$key]['bin_location']      = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;              
                    //dd($items);
                }
            }            
        }

        $data = null;
        if(count($items) > 0)
        {
            if(!$return)
            {
                $val = WarehouseTransfer::create_rr($shop_id, $ins_rr['wis_id'], $ins_rr, $items, $this->user_info->user_id);
                if(is_numeric($val))
                {
                    WarehouseTransfer::applied_transaction_rr($shop_id, $val);
                    $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $val, 'receiving_report');
                    AuditTrail::record_logs('Added', 'receiving_report', $val, "", serialize($transaction_data));

                    $data['status'] = 'success';
                    $data['call_function'] = 'success_rr';    
                    $data['redirect'] = AccountingTransaction::get_redirect("receiving_report", $val, $btn_action);            
                }
                else
                {
                    $data['status'] = 'error';
                    $data['status_message'] = $val;
                }
            }
            else
            {
                $data['status'] = 'error';
                $data['status_message'] = $return;
            }
        }
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = "You don't have any items to receive.";
        }

        return json_encode($data);

    }
    public function getPrint(Request $request)
    {
        $rr_id = $request->id;
        $data['rr'] = WarehouseTransfer::get_rr_data($rr_id);
        $data['rr_item_v1'] = WarehouseTransfer::print_rr_item($rr_id);
        $data['rr_item'] = WarehouseTransfer::get_rr_itemline($rr_id);
        $data['user'] = $this->user_info;

        $data['wis'] = WarehouseTransfer::get_wis_data($data['rr']->wis_id);
        $data['to_warehouse'] = WarehouseTransfer::get_warehouse_data($data['rr']->warehouse_id);
        $data['from_warehouse'] = WarehouseTransfer::get_warehouse_data($data['wis']->wis_from_warehouse);

        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "wtrr");
        
        $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_wt_rr");
        $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
        $data['content_width'] = "width: ".$_printed['width']."%";
        $data['printed_width'] = $_printed['width'];
        $format = $_printed['size'];
        $proj = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
        if($data['rr_item'])
        {
            // dd($data['rr_item']);   
            $qty = 0;
            $amt = 0;
            foreach ($data['rr_item'] as $key => $value) {
                $qty += $value->rr_qty;
                $amt += $value->rr_amount;
                if($proj == 'woa'){
                    $name = explode("-", $value->item_name);
                    $data['rr_item'][$key]['pattern'] = isset($name[0]) ? $name[0] : '';
                    $data['rr_item'][$key]['color'] = isset($name[1]) ? $name[1] : '';
                    $data['rr_item'][$key]['size'] = isset($name[2]) ? $name[2] : '';
                }
            }
            $data['total_qty'] = $qty;
            $data['total_amount'] = number_format($amt,2);
        }
        
        $data['transaction_type'] = "Receiving Report";

        $pdf = $proj == 'woa' ? view('member.warehousev2.rr.woa_print_rr', $data) : view('member.warehousev2.rr.print_rr', $data);
        return Pdf_global::show_pdf($pdf,null,$data['rr']->rr_number, $format);
    }
}
