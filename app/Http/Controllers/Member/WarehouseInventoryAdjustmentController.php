<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_user_warehouse_access;
use App\Models\Tbl_item;
use App\Models\Tbl_sub_warehouse;
use App\Models\Tbl_warehouse_inventory;
use App\Models\Tbl_settings;
use Redirect;

use App\Globals\Warehouse2;
use App\Globals\Warehouse;
use App\Globals\Utilities;
use App\Globals\Vendor;
use App\Globals\Pdf_global;
use App\Globals\UnitMeasurement;
use App\Globals\AccountingTransaction;
use App\Globals\InventoryAdjustment;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Session;
use App\Globals\Item;
use App\Globals\AuditTrail;
use Validator;
use Excel;
use DB;
class WarehouseInventoryAdjustmentController extends Member
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        $data['page'] = "Inventory Adjustment";
       
        return view('member.warehousev2.inventory_adjustment.inventory_adjustment_list',$data);
    }

    public function getLoadInventoryAdjustment(Request $request)
    {
        $display = 10;
        $data['_inventory_adjustment'] = InventoryAdjustment::get($this->user_info->shop_id, $display, $request->search_keyword);
        $data['page'] = $data['_inventory_adjustment']->currentPage();
        $data['number'] = ($data['page'] - 1) * $display;
        return view('member.warehousev2.inventory_adjustment.inventory_adjustment_table',$data);      
    }
    public function getCreate(Request $request)
    {
        $data['page']       = "Inventory Adjustment";
        $data['_item']      = Item::get_all_category_item([1,4,5], null, true);
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['transaction_refnum'] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'inventory_adjustment');
        $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id);

        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);

        $data['action'] = '/member/item/warehouse/inventory_adjustment/create-submit';

        $data["_adj_line"] = null;
        if($request->id)
        {
            $data['action'] = '/member/item/warehouse/inventory_adjustment/update-submit';
            $data['adj'] = InventoryAdjustment::info($this->user_info->shop_id, $request->id);
            $data['_adj_line'] = InventoryAdjustment::info_item($request->id);

            foreach ($data["_adj_line"] as $key => $value)
            {
                $data['_bin_item_warehouse'][$key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $value->itemline_sub_wh_id);
            }
        }
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');
        return view('member.warehousev2.inventory_adjustment.inventory_adjustment',$data);
    }
    public function getItemInventory(Request $request)
    {
        $warehouse_id = $request->w;
        // $_item = Tbl_item::whereIn("item_type_id",[1,4,5])->where("tbl_item.archived",0)->where("tbl_item.shop_id",$this->user_info->shop_id)->get()->toArray();
        $return = null;
        // foreach ($_item as $key => $value) 
        // {
        //     $return[$value['item_id']][$value['warehouse_id']] = Warehouse2::get_item_qty($value['warehouse_id'], $value['item_id']);
        // }
        $return = Warehouse2::get_item_qty($warehouse_id, $request->item_id);

        return $return;
    }
    public function postCreateSubmit(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        $btn_action = $request->button_action;

        $insert['transaction_refnum']    = $request->transaction_refnum;
        $insert['adj_warehouse_id']      = $request->adj_warehouse_id;
        $insert['date_created']          = date("Y-m-d", strtotime($request->adj_created));
        $insert['adjustment_remarks']    = $request->adjustment_remarks;
        $insert['adjustment_memo']       = $request->adjustment_memo;
        $insert['adj_user_id']           = $this->user_info->user_id;

        $insert_item = null;
        foreach ($request->item_id as $key => $value) 
        {   
            if($value && $request->item_new_qty[$key] != null)
            {
                $insert_item[$key]['item_id']           = $value;
                $insert_item[$key]['item_description']  = $request->item_description[$key];
                $insert_item[$key]['item_sub_warehouse']  = $request->item_sub_warehouse[$key] != ''? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['item_um']           = isset($request->item_um[$key]) ? $request->item_um[$key] : null;
                $insert_item[$key]['item_actual_qty']   = str_replace(',', '', $request->item_actual_qty[$key] != ''? $request->item_actual_qty[$key] : 0);
                $insert_item[$key]['item_new_qty']      = str_replace(',', '', $request->item_new_qty[$key] != ''? $request->item_new_qty[$key] : 0);
                $insert_item[$key]['item_diff_qty']     = $insert_item[$key]['item_qty'] = str_replace(',', '', $request->item_diff_qty[$key] != ''? $request->item_diff_qty[$key] : 0);
                $insert_item[$key]['item_rate']         = str_replace(',', '', $request->item_rate[$key] != ''? $request->item_rate[$key] : 0);
                $insert_item[$key]['item_amount']       = str_replace(',', '', $request->item_amount[$key] != ''? $request->item_amount[$key] : 0);
            }
            else
            {
                if($value)
                {
                $item_name = Item::info($value);
                $return['status'] = 'error';
                $return['status_message'] = 'Please insert quantity for '.$item_name->item_name;
                return json_encode($return);
                }
            }
        }
        if($insert_item == null)
        {
            $return['status'] = 'error';
            $return['status_message'] = 'No item to be transact.';
            return json_encode($return);
        }
        $val = null;
        if(!$insert['adjustment_remarks'])
        {
            $val = "Remarks field is required";
        }
        if(!$insert['adj_warehouse_id'])
        {
            $val = "Warehouse field is required";
        }
        $check_dup = InventoryAdjustment::check_dup_refnum($this->user_info->shop_id, $insert['transaction_refnum']);
        if($check_dup)
        {
            $val = $check_dup;   
        }
        if(count($insert_item) > 0 && !$val)
        {
            $val = InventoryAdjustment::postInsert($this->user_info->shop_id, $insert, $insert_item);
            if(is_numeric($val))
            {
                $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $val, 'inventory_adjustment');
                AuditTrail::record_logs('Added', 'inventory_adjustment', $val, "", serialize($transaction_data));

                $return['status'] = 'success';
                $return['call_function'] = 'success_adjust_inventory';
                $return['status_message'] = 'Success adjusting inventory.';

                if($btn_action == 'sclose')
                {
                    $return['status_redirect'] = '/member/item/warehouse/inventory_adjustment';
                }
                elseif ($btn_action == 'sedit')
                {
                    $return['status_redirect'] = '/member/item/warehouse/inventory_adjustment/create?id='.$val;
                }
                elseif ($btn_action == 'snew')
                {
                    $return['status_redirect'] = '/member/item/warehouse/inventory_adjustment/create';
                }
                elseif ($btn_action == 'sprint')
                {
                    $return['status_redirect'] = '/member/item/warehouse/inventory_adjustment/print?id='.$val;
                }
            }
            else
            {
                $return['status'] = 'error';
                $return['status_message'] = "Please select item or enter it's new quantity.";
            }
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $val;
        }

        return json_encode($return);
    }
    public function getPrint(Request $request)
    {
        $footer = AccountingTransaction::get_refuser($this->user_info);

        $data['adj'] = InventoryAdjustment::info($this->user_info->shop_id, $request->id);
        $data['_adj_line'] = InventoryAdjustment::info_item($request->id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "adj");
        $proj = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
        $data['project_name'] = $proj;
        if($proj == 'woa'){
            foreach ($data['_adj_line'] as $key => $value) {
                $name = explode("-", $value->item_name);
                $data['_adj_line'][$key]['pattern'] = isset($name[0]) ? $name[0] : '';
                $data['_adj_line'][$key]['color'] = isset($name[1]) ? $name[1] : '';
                $data['_adj_line'][$key]['size'] = isset($name[2]) ? $name[2] : '';
            }
        }
        
        // dd($data['_adj_line']);
        $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_adj");
        $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
        $data['content_width'] = "width: ".$_printed['width']."%";
        $data['printed_width'] = $_printed['width'];
        $format = $_printed['size'];

        $data['transaction_type'] = "Inventory Adjustment";

        $pdf = $proj == 'woa' ? view('member.warehousev2.inventory_adjustment.woa_adj_print', $data) : view('member.warehousev2.inventory_adjustment.adj_print', $data);
        return $pdf;
        return Pdf_global::show_pdf($pdf, null, $footer, $format);
    }
    public function postUpdateSubmit(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        $btn_action = $request->button_action;

        $adj_id = $request->adj_id;
        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $adj_id, 'inventory_adjustment');
        $insert['transaction_refnum']    = $request->transaction_refnum;
        $insert['adj_warehouse_id']      = $request->adj_warehouse_id;
        $insert['date_created']           = date("Y-m-d", strtotime($request->adj_created));
        $insert['adjustment_remarks']    = $request->adjustment_remarks;
        $insert['adjustment_memo']       = $request->adjustment_memo;
        $insert['adj_user_id']           = $this->user_info->user_id;

        $insert_item = null;
        foreach ($request->item_id as $key => $value) 
        {
            if($value)
            {
                $insert_item[$key]['item_id']           = $value;
                $insert_item[$key]['item_description']  = $request->item_description[$key];
                $insert_item[$key]['item_sub_warehouse']  = $request->item_sub_warehouse[$key] != ''? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['item_um']           = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_actual_qty']   = str_replace(',', '', $request->item_actual_qty[$key] != ''? $request->item_actual_qty[$key] : 0);
                $insert_item[$key]['item_new_qty']      = str_replace(',', '', $request->item_new_qty[$key] != ''? $request->item_new_qty[$key] : 0);
                $insert_item[$key]['item_diff_qty']     = $insert_item[$key]['item_qty'] = str_replace(',', '', $request->item_diff_qty[$key] != ''? $request->item_diff_qty[$key] : 0);
                $insert_item[$key]['item_rate']         = str_replace(',', '', $request->item_rate[$key] != ''? $request->item_rate[$key] : 0);
                $insert_item[$key]['item_amount']       = str_replace(',', '', $request->item_amount[$key] != ''? $request->item_amount[$key] : 0);
            }
        }

        $val = null;

        if(!$insert['adjustment_remarks'])
        {
            $val = "Remarks field is required";
        }
        $check_dup = InventoryAdjustment::check_dup_refnum($this->user_info->shop_id, $insert['transaction_refnum'], $adj_id);
        if($check_dup)
        {
            $val = $check_dup;   
        }
        
        if(count($insert_item) > 0 && !$val)
        {
            $val = InventoryAdjustment::postUpdate($adj_id, $this->user_info->shop_id, $insert, $insert_item);
            if(is_numeric($val))
            {
                $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $adj_id, 'inventory_adjustment');
                AuditTrail::record_logs('Edited', 'inventory_adjustment', $adj_id, serialize($old_transaction_data), serialize($transaction_data));

                $return['status'] = 'success';
                $return['call_function'] = 'success_adjust_inventory';
                $return['status_message'] = 'Success adjusting inventory.';
                if($btn_action == 'sclose')
                {
                    $return['status_redirect'] = '/member/item/warehouse/inventory_adjustment';
                }
                elseif ($btn_action == 'sedit')
                {
                    $return['status_redirect'] = '/member/item/warehouse/inventory_adjustment/create?id='.$val;
                }
                elseif ($btn_action == 'snew')
                {
                    $return['status_redirect'] = '/member/item/warehouse/inventory_adjustment/create';
                }
                elseif ($btn_action == 'sprint')
                {
                    $return['status_redirect'] = '/member/item/warehouse/inventory_adjustment/print?id='.$val;
                }
            }
            else
            {
                $return['status'] = 'error';
                $return['status_message'] = 'Please select item.';
            }
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $val;
        }

        return json_encode($return);
    }
}
