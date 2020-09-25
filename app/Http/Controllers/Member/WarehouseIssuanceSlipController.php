<?php
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Globals\Cart2;
use App\Globals\WarehouseTransfer;
use App\Globals\Warehouse2;
use App\Globals\Item;
use App\Globals\AccountingTransaction;
use App\Globals\Transaction;
use App\Globals\UnitMeasurement;
use App\Globals\Truck;
use App\Globals\AuditTrail;

use App\Models\Tbl_item;

use Session;
use Carbon\Carbon;
use App\Globals\Pdf_global;

class WarehouseIssuanceSlipController extends Member
{
    public function getIndex(Request $request)
    {
        $current_warehouse = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['page'] = 'Warehouse Transfer';
        $data['status'] = isset($request->status) ? $request->status : 'pending';
        $data['optimize_wiswt'] = AccountingTransaction::settings($this->user_info->shop_id, "optimize_wiswt");
        $data['_wis'] = WarehouseTransfer::get_all_wis($this->user_info->shop_id, $data['status'], $current_warehouse);
        
    	return view('member.warehousev2.wis.wis_list',$data);
    }
    public function getLoadWisTable(Request $request)
    {
        $current_warehouse = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['status'] = isset($request->status) ? $request->status : 'pending';
        $data['optimize_wiswt'] = AccountingTransaction::settings($this->user_info->shop_id, "optimize_wiswt");
        $data['_wis'] = WarehouseTransfer::get_all_wis($this->user_info->shop_id, $data['status'], $current_warehouse);
        
        return view('member.warehousev2.wis.load_wis_table',$data);
    }
    public function getCreate(Request $request)
    {
    	$data['page'] = 'WIS';
        $data['_item']  = Item::get_all_category_item([1,4,5], null, null, null, true);
        $data['_warehouse'] = Warehouse2::get_all_warehouse($this->user_info->shop_id);
        $data['_truck'] = Truck::get($this->user_info->shop_id);      
        $data['transaction_ref_number'] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'warehouse_transfer');
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['action'] = "/member/transaction/warehouse_transfer/create-submit";

        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);

        $data['wis_item'] = null;
        if($request->id)
        {        
            $data['action'] = "/member/transaction/warehouse_transfer/update-submit";
            $data['wis'] = WarehouseTransfer::get_wis_data($request->id);
            $data['wis_item_v1'] = WarehouseTransfer::print_wis_item($request->id);
            $data['wis_item'] = WarehouseTransfer::get_wis_itemline($request->id);

            foreach ($data["wis_item"] as $key => $value)
            {
                $data['_bin_item_warehouse'][$key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $value->wt_sub_wh_id);
            }

            if($data['wis'])
            {
                if($data['wis']->wis_status != 'pending')
                {
                    return redirect('/member/transaction/warehouse_transfer');
                }
            }
        }
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');
    	return view('member.warehousev2.wis.wis_create',$data);
    }
    public function getTableItem()
    {
        $data['page'] = 'Table Item';
        $data["_wis_item"] = Session::get('wis_item');
        if(count($data['_wis_item']) > 0)
        {
            foreach ($data['_wis_item'] as $key => $value) 
            {
                $data['_wis_item'][$key]['warehouse_qty'] = Warehouse2::get_item_qty(Warehouse2::get_current_warehouse($this->user_info->shop_id), $key);
            }            
        }

        return view('member.warehousev2.wis.wis_table_item',$data);
    }
    public function postScanItem(Request $request)
    {
        $data["shop_id"]    = $shop_id = $this->user_info->shop_id;
        $data["item_id"]    = $item_id = $request->item_id;
        $return             = $item = WarehouseTransfer::scan_item($data["shop_id"], $data["item_id"]);

        if($return)
        {
            $return["status"]   = "success";
            $return["message"]  = "Item Number " .  $return['item_id'] . " has been added.";
            $serial = isset($return['item_serial']) ? $return['item_serial'] : null;
            WarehouseTransfer::add_item_to_list($shop_id, $return['item_id'], 1, $serial);
        }
        else
        {
            $return["status"]   = "error";
            $return["message"]  = "The ITEM you scanned didn't match any record.";
        }

        echo json_encode($return);
    }
    public function getCreateRemoveItem(Request $request)
    {        
        $item_id = $request->item_id;
        WarehouseTransfer::delete_item_from_list($item_id);
        $return["status"] = "success";
        $return["item_id"] = $item_id;
        echo json_encode($return);
    }
    public function getViewSerial(Request $request, $item_id)
    {
        $item = Session::get('wis_item');
        $data['item'] = Item::info($item_id);
        $data['_serial'] = $item[$item_id]['item_serial'];

        return view('member.warehousev2.wis.wis_serial',$data);
    }
    public function getChangeQuantity(Request $request)
    {
        $shop_id = $this->user_info->shop_id;
        WarehouseTransfer::add_item_to_list($shop_id, $request->item_id, $request->qty, '',1);

        echo json_encode('success');
    }   
    public function postCreateSubmit(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        if(!$request->destination_warehouse_id)
        {
            $return['status'] = 'error';
            $return['status_message'] = 'Destination Warehouse is required';
            return json_encode($return);   
        }

        $remarks = $request->wis_remarks;
        $items = Session::get('wis_item');
        $items = $request->item_id;
        $shop_id = $this->user_info->shop_id;
        $btn_action = $request->button_action;

        $ins_wis['wis_shop_id'] = $shop_id;
        $ins_wis['wis_number'] = $request->wis_number;
        $ins_wis['wis_from_warehouse'] = Warehouse2::get_current_warehouse($shop_id);
        $ins_wis['wis_remarks'] = $remarks;
        $ins_wis['destination_warehouse_id'] = $request->destination_warehouse_id;
        $ins_wis['destination_warehouse_address'] = $request->destination_warehouse_address;
        $ins_wis['wis_truck_id'] = $request->truck_id;
        $ins_wis['created_at'] = Carbon::now();
        $ins_wis['wis_delivery_date'] = date("Y-m-d", strtotime($request->delivery_date));
        $ins_wis['wis_issued_by'] = $this->user_info->user_id;

        $_item = null;
        foreach ($items as $key => $value) 
        {
            if($value)
            {
                $_item[$key] = null;
                $_item[$key]['item_id']          = $value;
                $_item[$key]['item_description'] = $request->item_description[$key];
                $_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $_item[$key]['item_um']          = isset($request->item_um[$key]) ? $request->item_um[$key] : null;
                $_item[$key]['item_qty']         = str_replace("," ,"" , $request->item_qty[$key]);
                $_item[$key]['item_rate']        = str_replace("," ,"" , $request->item_rate[$key]);
                $_item[$key]['item_amount']      = str_replace("," ,"" , $request->item_amount[$key]);
                $_item[$key]['item_refname']     = $request->item_refname[$key];
                $_item[$key]['item_refid']       = $request->item_refid[$key];

                if($_item[$key]['item_um'] != "")
                {
                    $_item[$key]['quantity']  = $_item[$key]['item_qty'] * UnitMeasurement::get_umqty($_item[$key]['item_um']);
                }
                else
                {
                    $_item[$key]['quantity'] = $_item[$key]['item_qty'];
                }

                $_item[$key]['remarks']          = $request->item_description[$key];
                $_item[$key]['bin_location'] = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
            }
        }

        $return = null;
        if(count($_item) > 0)
        {
            $val = WarehouseTransfer::create_wis($shop_id, $remarks, $ins_wis , $_item);
            if(is_numeric($val))
            {
                $check_auto_receive_wt = AccountingTransaction::settings($shop_id, 'auto_received_wt');

                WarehouseTransfer::applied_transaction_wt($shop_id, $val);

                $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $val, 'warehouse_transfer');
                AuditTrail::record_logs('Added', 'warehouse_transfer', $val, "", serialize($transaction_data));

                $return['status'] = 'success';
                $return['call_function'] = 'success_create_wis';
                $return['redirect'] = AccountingTransaction::get_redirect("warehouse_transfer", $val, $btn_action);
                Session::forget('wis_item');
                $return['status_message'] = 'Success creating warehouse transfer';
                if($btn_action == 'sconfirm')
                {
                    if($check_auto_receive_wt == 1)
                    {
                        $ins_rr['rr_shop_id'] = $shop_id;
                        $ins_rr['rr_number'] = $data['transaction_ref_number'] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'receiving_report');
                        $ins_rr['wis_id'] = $val;
                        $ins_rr['rr_date_received'] = Carbon::now();
                        $ins_rr['warehouse_id'] = $request->destination_warehouse_id;
                        $ins_rr['rr_remarks'] = $request->wis_number;
                        $ins_rr['created_at'] = Carbon::now();
                        WarehouseTransfer::create_rr($shop_id, $val, $ins_rr, $_item, $this->user_info->user_id);

                        $up['wis_status'] = 'received';

                    }
                    else
                    {
                        /*FOR CONFIRMATION*/
                        $up['wis_status'] = "confirm";
                    }
                    $up['receiver_code'] = WarehouseTransfer::get_code($this->user_info->shop_id);
                    WarehouseTransfer::update_wis($this->user_info->shop_id, $val, $up);
                }
            }
            else
            {
                $return['status'] = 'error';
                $return['status_message'] = $val;
            }
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = 'Please select item';
        }
        
       
        return json_encode($return);
    }

    public function postUpdateSubmit(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        
        if(!$request->destination_warehouse_id)
        {
            $return['status'] = 'error';
            $return['status_message'] = 'Destination Warehouse is required';
            return json_encode($return);   
        }
        $remarks = $request->wis_remarks;
        $items = Session::get('wis_item');
        $items = $request->item_id;
        $shop_id = $this->user_info->shop_id;

        $btn_action = $request->button_action;

        $wis_id = $request->wis_id;
        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $wis_id, 'warehouse_transfer');
        $ins_wis['wis_shop_id'] = $shop_id;
        $ins_wis['wis_number'] = $request->wis_number;
        $ins_wis['wis_from_warehouse'] = Warehouse2::get_current_warehouse($shop_id);
        $ins_wis['wis_remarks'] = $remarks;
        $ins_wis['wis_truck_id'] = $request->truck_id;
        $ins_wis['destination_warehouse_id'] = $request->destination_warehouse_id;
        $ins_wis['destination_warehouse_address'] = $request->destination_warehouse_address;
        $ins_wis['created_at'] = Carbon::now();
        $ins_wis['wis_delivery_date'] = date("Y-m-d", strtotime($request->delivery_date));
        $ins_wis['wis_issued_by'] = $this->user_info->user_id;
        
        $_item = null;
        foreach ($items as $key => $value) 
        {
            if($value)
            {
                $_item[$key] = null;
                $_item[$key]['item_id']          = $value;
                $_item[$key]['item_description'] = $request->item_description[$key];
                $_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) != '' ? $request->item_sub_warehouse[$key] : null;
                $_item[$key]['item_um']          = isset($request->item_um[$key]) ? $request->item_um[$key] : null;;
                $_item[$key]['item_qty']         = str_replace(",", "",$request->item_qty[$key]);
                $_item[$key]['item_rate']        = str_replace(",", "", $request->item_rate[$key]);
                $_item[$key]['item_amount']      = str_replace(",", "", $request->item_amount[$key]);
                $_item[$key]['item_refname']     = $request->item_refname[$key];
                $_item[$key]['item_refid']       = $request->item_refid[$key];
                $_item[$key]['quantity']         = $_item[$key]['item_qty'] * UnitMeasurement::get_umqty($_item[$key]['item_um']);
                $_item[$key]['remarks']          = $request->item_description[$key];
                $_item[$key]['bin_location']= isset($request->item_sub_warehouse[$key]) != '' ? $request->item_sub_warehouse[$key] : null;
            }
        }
        $return = null;
        if(count($_item) > 0)
        {
            $val = WarehouseTransfer::update_data_wis($wis_id, $shop_id, $remarks, $ins_wis , $_item);
            if(is_numeric($val))
            {
                WarehouseTransfer::applied_transaction_wt($shop_id, $val);

                $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $wis_id, 'warehouse_transfer');
                AuditTrail::record_logs('Edited', 'warehouse_transfer', $wis_id, serialize($old_transaction_data), serialize($transaction_data));

                Session::forget('wis_item');
                $return['status'] = 'success';
                $return['call_function'] = 'success_create_wis';
                $return['status_message'] = 'Success updating warehouse transfer';
                $return['redirect'] = AccountingTransaction::get_redirect("warehouse_transfer", $wis_id, $btn_action);
                if($btn_action == 'sconfirm')
                {
                    $check_auto_receive_wt = AccountingTransaction::settings($shop_id, 'auto_received_wt');
                    if($check_auto_receive_wt == 1)
                    {
                        $ins_rr['rr_shop_id'] = $shop_id;
                        $ins_rr['rr_number'] = $data['transaction_ref_number'] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'receiving_report');
                        $ins_rr['wis_id'] = $val;
                        $ins_rr['rr_date_received'] = Carbon::now();
                        $ins_rr['warehouse_id'] = $request->destination_warehouse_id;
                        $ins_rr['rr_remarks'] = $request->wis_number;
                        $ins_rr['created_at'] = Carbon::now();
                        WarehouseTransfer::create_rr($shop_id, $val, $ins_rr, $_item, $this->user_info->user_id);
                        
                        $up['wis_status'] = 'received';
                    }
                    else
                    {
                        /*FOR CONFIRMATION*/
                        $up['wis_status'] = "confirm";
                    }
                    $up['receiver_code'] = WarehouseTransfer::get_code($this->user_info->shop_id);
                    WarehouseTransfer::update_wis($this->user_info->shop_id, $wis_id, $up);
                }
            }
            else
            {
                $return['status'] = 'error';
                $return['status_message'] = $val;
            }
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = 'Please select item';
        }

        return json_encode($return);
    }
    public function getPrint(Request $request)
    {
        $wis_id = $request->id;
        $footer = AccountingTransaction::get_refuser($this->user_info);
        $data['wis'] = WarehouseTransfer::get_wis_data($wis_id);
        
        $data['wis_item_v1'] = WarehouseTransfer::print_wis_item($wis_id);
        $data['wis_item'] = WarehouseTransfer::get_wis_itemline($wis_id);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $data['user'] = $this->user_info;
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id);

        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['owner'] = WarehouseTransfer::get_warehouse_data($warehouse_id);
        $data['deliver_to'] = WarehouseTransfer::get_warehouse_data($warehouse_id);

        if($data['wis'] != null)
        {
            $data['owner'] = WarehouseTransfer::get_warehouse_data($data['wis']->wis_from_warehouse);
            $data['deliver_to'] = WarehouseTransfer::get_warehouse_data($data['wis']->destination_warehouse_id);

            if($data['wis']->wis_truck_id != NULL)
            {
                $truck_id = WarehouseTransfer::getTruck($data['wis']->wis_truck_id);
                $data['plate_number'] = $truck_id->plate_number;
            }
            else
            {
                $data['plate_number'] = '';   
            }
            $proj = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
            $data['total'] = 0;
            $qty = 0;
            // dd($data['wis_item'] );
            foreach ($data['wis_item'] as $key => $value)
            {
                if($proj == 'woa'){
                    $qty += $value->wt_qty;
                    $name = $value->item_name;
                    $name = explode("-", $value->item_name);
                    $data['wis_item'][$key]['pattern'] = isset($name[0]) ? $name[0] : '';
                    $data['wis_item'][$key]['color'] = isset($name[1]) ? $name[1] : '';
                    $data['wis_item'][$key]['size'] = isset($name[2]) ? $name[2] : '';
                }
                $data['total'] += $value->int_qty * $value->item_price;
            }
            
            $data['wis_item'][0]['total_quantity'] = $qty;

            $data["transaction_type"] = "Warehouse Transfer";            
            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_wt_rr");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

            if(!$request->picking)
            {
                // return view('member.warehousev2.wis.print_wis', $data);
                $pdf = $proj == 'woa' ?  view('member.warehousev2.wis.woa_print_wis', $data) : view('member.warehousev2.wis.print_wis', $data);
            }
            else
            {
                $data['footer'] = $footer;
                // member/warehousev2/wis/picking_slip
                $pdf = view('member.warehousev2.wis.picking_slip', $data);
                $footer = null;
            }
            
            if($request->from == 'auto')
            {
                return $pdf;
            }
            else
            {            
                return Pdf_global::show_pdf($pdf,null,$data['wis']->wis_number, $format);
            }
        }  
        else
        {
            return view('member.no_transaction');
        }
    }
    public function getConfirm(Request $request, $wis_id)
    {
        $data['wis_id'] = $wis_id;
        $data['wis'] = WarehouseTransfer::get_wis_data($wis_id);

        return view('member.warehousev2.wis.wis_confirm', $data);
    }
    public function postConfirmSubmit(Request $request)
    {
        $wis_id = $request->wis_id;

        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $wis_id, 'warehouse_transfer');
        $up['wis_status'] = $request->wis_status;
        $up['confirm_image'] = $request->confirm_image;
        $up['receiver_code'] = WarehouseTransfer::get_code($this->user_info->shop_id);
        $check_auto_receive_wt = AccountingTransaction::settings($this->user_info->shop_id, 'auto_received_wt');
        if($check_auto_receive_wt == 1)
        {
            if($request->wis_status == 'confirm')
            {
                $wt = WarehouseTransfer::get_wis_data($wis_id);
                $ins_rr['rr_shop_id'] = $this->user_info->shop_id;
                $ins_rr['rr_number'] = $data['transaction_ref_number'] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'receiving_report');
                $ins_rr['wis_id'] = $wis_id;
                $ins_rr['rr_date_received'] = Carbon::now();
                $ins_rr['warehouse_id'] = $wt->destination_warehouse_id;
                $ins_rr['rr_remarks'] = $wt->wis_number;
                $ins_rr['created_at'] = Carbon::now();

                $wtline = WarehouseTransfer::get_wtline($wis_id);
                $item = null;
                foreach ($wtline as $key => $value)
                {
                    $_item[$key] = null;
                    $_item[$key]['item_id']          = $value->wt_item_id;
                    $_item[$key]['item_description'] = $value->wt_description;
                    $_item[$key]['item_sub_warehouse']= isset($value->wt_sub_wh_id) != '' ? $value->wt_sub_wh_id[$key] : null;
                    $_item[$key]['item_um']          = isset($value->wt_um) ? $value->wt_um : null;;
                    $_item[$key]['item_qty']         = $value->wt_orig_qty;
                    $_item[$key]['item_rate']        = str_replace(",", "", $value->wt_rate);
                    $_item[$key]['item_amount']      = str_replace(",", "", $value->wt_amount);
                    $_item[$key]['item_refname']     = $value->wt_refname;
                    $_item[$key]['item_refid']       = $value->wt_refid;
                    $_item[$key]['quantity']         = $value->wt_orig_qty * UnitMeasurement::get_umqty($_item[$key]['item_um']);
                    $_item[$key]['remarks']          = $value->wt_description;
                    $_item[$key]['bin_location']     = isset($value->wt_sub_wh_id) != '' ? $value->wt_sub_wh_id : null;
                }
                WarehouseTransfer::create_rr($this->user_info->shop_id, $wis_id, $ins_rr, $_item, $this->user_info->user_id);
                $up['wis_status'] = 'received';
            }
        }
        else
        {
            $up['wis_status'] = $request->wis_status;
        }

        $return = WarehouseTransfer::update_wis($this->user_info->shop_id, $wis_id, $up);

        $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $wis_id, 'warehouse_transfer');
        AuditTrail::record_logs(ucfirst($request->wis_status), 'warehouse_transfer', $wis_id, serialize($old_transaction_data), serialize($transaction_data));
        $data = null;
        if($return)
        {
            $data['status'] = 'success';
            $data['call_function'] = 'success_confirm'; 
        }

        return json_encode($data);
    }
}
