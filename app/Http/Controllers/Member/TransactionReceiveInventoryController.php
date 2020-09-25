<?php
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Globals\TransactionDebitMemo;
use App\Globals\TransactionPurchaseOrder;
use App\Globals\AccountingTransaction;
use App\Globals\TransactionReceiveInventory;
use App\Globals\Purchasing_inventory_system;
use App\Globals\Vendor;
use App\Globals\ItemSerial;
use App\Globals\AuditTrail;
use App\Globals\Accounting;
use App\Globals\Purchase_Order;
use App\Globals\Billing;
use App\Globals\Item;
use App\Globals\Warehouse;
use App\Globals\Warehouse2;
use App\Globals\Utilities;
use App\Globals\UnitMeasurement;
use App\Globals\Pdf_global;
use App\Globals\Terms;
use Carbon\Carbon;
use Session;
use stdClass;
use App\Models\Tbl_receive_inventory_line;
class TransactionReceiveInventoryController extends Member
{
    public function getIndex()
    {
        $access = Utilities::checkAccess('vendor-receive-inventory', 'access_page');
        if($access == 1)
        { 
            $data['page'] = 'Receive Inventory';
            return view('member.accounting_transaction.vendor.receive_inventory.receive_inventory_list', $data);
        }
        else
        {
            return $this->show_no_access();            
        }
    }
    public function getSearchItem(Request $request)
    {
        $search_keyword = $request->item_id;
        $data['item_id'] = Item::search_item($this->user_info->shop_id, $search_keyword);

        if($data['item_id'] != null)
        {
            $data['status'] = 'success';
            $data['message'] = 'Item Added';
        }
        else
        {
            $data['status'] = 'error';
            $data['message'] = 'No Item Found';
        }
        
        return json_encode($data);
    }
    public function getLoadReceiveInventory(Request $request)
    {
        $display = 10;
        $data['_receive_inventory'] = TransactionReceiveInventory::get($this->user_info->shop_id, $display, $request->search_keyword);
        $data['page'] = $data['_receive_inventory']->currentPage();
        $data['number'] = ($data['page'] - 1) * $display;

        return view('member.accounting_transaction.vendor.receive_inventory.receive_inventory_table', $data);
    }
    public function getPrint(Request $request)
    {
        $ri_id = $request->id;
        $data["ri"] = TransactionReceiveInventory::info($this->user_info->shop_id,$ri_id);
        $data["_riline"] = TransactionReceiveInventory::info_item($ri_id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, 'ri');
        $data['transaction_type'] = "Receive Inventory";
        $qty = 0;
        $proj = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
        // dd($proj);
        if($proj == 'woa'){
            foreach ($data["_riline"] as $key => $value) {
                $qty += $value->riline_qty;
                $name = explode("-", $value->item_name);
                $data["_riline"][$key]['pattern'] = isset($name[0]) ? $name[0] : '';
                $data["_riline"][$key]['color'] = isset($name[1]) ? $name[1] : '';
                $data["_riline"][$key]['size'] = isset($name[2]) ? $name[2] : '';
            }
        }
        
        $data["_riline"][0]['total_quantity'] = $qty;
        // dd($data["_riline"]);
        if($data["ri"])
        {
            $data['terms'] = Terms::terms($this->user_info->shop_id, $data['ri']->ri_terms_id);
            $data['count_tax'] = TransactionReceiveInventory::count_tax($ri_id);
            $data["_riline"]     = TransactionReceiveInventory::infoline($this->user_info->shop_id, $data["_riline"]);
            $data['total_tax']   = TransactionReceiveInventory::infotax($this->user_info->shop_id, $data["_riline"]);
            $data['subtotal']   = TransactionReceiveInventory::subtotal($this->user_info->shop_id, $data["_riline"]);

            $footer = AccountingTransaction::get_refuser($this->user_info);

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_ri");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];
           
            $pdf = $proj == 'woa' ? view("member.accounting_transaction.vendor.receive_inventory.woa_receive_inventory_pdf",$data) : view("member.accounting_transaction.vendor.receive_inventory.receive_inventory_pdf",$data);
            return $pdf;
            return Pdf_global::show_pdf($pdf, null, $footer, $format);
        }
        else
        {
            return view('member.no_transaction');
        }
    }
    public function getCreate(Request $request)
    {
        $data['page'] = 'Create Receive Inventory';

        $data["_vendor"]    = Vendor::getAllVendor('active');
        $data['item_new_cost'] = AccountingTransaction::settings_value($this->user_info->shop_id, "item_new_cost");
        $data['_item']      = Item::get_all_category_item([1,4,5], null, null, null, null, $data['item_new_cost'] == "average_costing" ? true : false);
        $data['_account']   = Accounting::getAllAccount();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["_terms"]     = Terms::active_terms(Billing::getShopId());
        $data["transaction_refnum"] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'received_inventory');
        $data['warehouse_id'] = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $data['warehouse_id']);
        $data['action']     = '/member/transaction/receive_inventory/create-receive-inventory';
        
        $receive_id = $request->id;
        $data['term'] = $request->vendor_terms;

        $data['po_id'] = $request->po_id;

        Session::forget("applied_transaction");

        if($data['po_id'])
        {
            $sess[$request->po_id] = $request->po_id;
            $data['po'] = TransactionPurchaseOrder::info($this->user_info->shop_id, $data['po_id']);

            Session::put("applied_transaction",$sess);
        }
        if($receive_id)
        {
            $data['ri'] = TransactionReceiveInventory::info($this->user_info->shop_id,$receive_id);
            $data['_riline']= TransactionReceiveInventory::info_item($receive_id);
            foreach ($data["_riline"] as $key => $value)
            {
                $data['_bin_item_warehouse'][$key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $data['warehouse_id'], $value->riline_sub_wh_id);
            }
            $data['action']     = '/member/transaction/receive_inventory/update-receive-inventory';
        }

        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');

        $access = Utilities::checkAccess('vendor-receive-inventory', 'access_page');
        if($access == 1)
        { 
            return view('member.accounting_transaction.vendor.receive_inventory.receive_inventory', $data);
        }
        else
        {
            return $this->show_no_access();            
        }
    }
    public function postCreateReceiveInventory(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        $btn_action = $request->button_action;

        $insert['transaction_refnumber']    = $request->transaction_refnumber;
        $insert['vendor_id']                = $request->vendor_id;
        $insert['vendor_address']           = $request->vendor_address;
        $insert['vendor_email']             = $request->vendor_email;
        $insert['vendor_terms']             = $request->vendor_terms;
        $insert['transaction_date']         = $request->transaction_date;
        $insert['transaction_duedate']      = $request->transaction_duedate;
        $insert['vendor_memo']              = $request->vendor_memo;
        $insert['vendor_remarks']           = $request->vendor_remarks;
        $insert['vendor_total']             = $request->vendor_total;
        $insert['vendor_discounttype']      = $request->vendor_discounttype;
        $insert['vendor_discount']          = $request->vendor_discount != null ? str_replace(',', '', $request->vendor_discount): 0;
        $insert['vendor_tax']               = $request->vendor_tax;
        $insert['vendor_subtotal']          = $request->vendor_subtotal;

        $insert_item = null;
        foreach ($request->item_id as $key => $value) 
        {
            if($value)
            {
                $insert_item[$key]['item_id']          = $value;
                $insert_item[$key]['item_ref_name']    = $request->item_ref_name[$key];
                $insert_item[$key]['item_ref_id']      = $request->item_ref_id[$key];
                $insert_item[$key]['item_description'] = $request->item_description[$key];
                $insert_item[$key]['item_um']          = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_qty']         = str_replace(',', '', $request->item_qty[$key]);
                $insert_item[$key]['item_rate']        = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_amount']      = $request->item_amount[$key] != ''? str_replace(',', '', $request->item_amount[$key]) : 0;
                $insert_item[$key]['item_discount']    = str_replace(',', '', $request->item_discount[$key]);
                $insert_item[$key]['item_taxable']     = isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;
                $insert_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['bin_location']      = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
            }
        }

        $return = null;
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);

        $validate = AccountingTransaction::inventory_validation('refill', $this->user_info->shop_id, $warehouse_id, $insert_item);
        if(!$validate)
        {
            $validate = TransactionReceiveInventory::postInsert($this->user_info->shop_id, $insert, $insert_item);
        }
        if(is_numeric($validate))
        {
            TransactionReceiveInventory::appliedTransaction($this->user_info->shop_id, $validate, $this->user_info->user_id, false);
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'receive_inventory');
            AuditTrail::record_logs('Added', 'receive_inventory', $validate, "", serialize($transaction_data));

            $return['status'] = 'success';
            $return['status_message'] = 'Success creating receive inventory.';
            $return['call_function'] = 'success_receive_inventory';
            $return['status_redirect'] = AccountingTransaction::get_redirect('receive_inventory', $validate ,$btn_action);
            Session::forget('applied_transaction');
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $validate;
        }

        return json_encode($return);
    }

    public function postUpdateReceiveInventory(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        $btn_action = $request->button_action;
        $ri_id = $request->ri_id;

        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $ri_id, 'receive_inventory');
        

        $insert['transaction_refnumber']    = $request->transaction_refnumber;
        $insert['vendor_id']                = $request->vendor_id;
        $insert['vendor_address']           = $request->vendor_address;
        $insert['vendor_email']             = $request->vendor_email;
        $insert['vendor_terms']             = $request->vendor_terms;
        $insert['transaction_date']         = $request->transaction_date;
        $insert['transaction_duedate']      = $request->transaction_duedate;
        $insert['vendor_memo']              = $request->vendor_memo;
        $insert['vendor_remarks']           = $request->vendor_remarks;
        $insert['vendor_total']             = $request->vendor_total;
        $insert['vendor_discounttype']      = $request->vendor_discounttype;
        $insert['vendor_discount']          = $request->vendor_discount != null ? str_replace(',', '', $request->vendor_discount): 0;
        $insert['vendor_tax']               = $request->vendor_tax;
        $insert['vendor_subtotal']          = $request->vendor_subtotal;

        $insert_item = null;
        $return_po = null;
        foreach ($request->item_id as $key => $value) 
        {
            if($value)
            {
                $insert_item[$key]['item_id']          = $value;
                $insert_item[$key]['item_ref_name']    = $request->item_ref_name[$key];
                $insert_item[$key]['item_ref_id']      = $request->item_ref_id[$key];
                $insert_item[$key]['item_description'] = $request->item_description[$key];

                $insert_item[$key]['item_um']          = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_qty']         = str_replace(',', '', $request->item_qty[$key]);
                $insert_item[$key]['item_rate']        = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_amount']      = $request->item_amount[$key] != ''? str_replace(',', '', $request->item_amount[$key]) : 0;
                $insert_item[$key]['item_discount']    = str_replace(',', '', $request->item_discount[$key]);
                $insert_item[$key]['item_taxable']     = isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;

                if($insert_item[$key]['item_ref_id'])
                {
                    $return_po[$insert_item[$key]['item_ref_id']] = '';
                }
                $insert_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['bin_location']      = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;

            }
        }

        if(count($return_po) > 0)
        {
            Session::put('applied_transaction',$return_po);
        }
        $return = null;
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $validate = AccountingTransaction::inventory_validation('refill', $this->user_info->shop_id, $warehouse_id, $insert_item);
        if(!$validate)
        {
            $validate = TransactionReceiveInventory::postUpdate($ri_id, $this->user_info->shop_id, $insert, $insert_item);
        }
        
        if(is_numeric($validate))
        {
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $ri_id, 'receive_inventory');
            AuditTrail::record_logs('Edited', 'receive_inventory', $ri_id, serialize($old_transaction_data), serialize($transaction_data));
            
            TransactionReceiveInventory::appliedTransaction($this->user_info->shop_id, $validate, $this->user_info->user_id, true);
            $return['status'] = 'success';
            $return['status_message'] = 'Success updating receive inventory.';
            $return['call_function'] = 'success_receive_inventory';
            $return['status_redirect'] = AccountingTransaction::get_redirect('receive_inventory', $validate ,$btn_action);
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $validate;
        }

        return json_encode($return);
    }

    public function getCountTransaction(Request $request)
    {
        $vendor_id = $request->vendor_id;
        return TransactionReceiveInventory::countTransaction($this->user_info->shop_id, $vendor_id);
    }
    public function getLoadTransaction(Request $request)
    {
        $data['_po'] = TransactionPurchaseOrder::getOpenPO($this->user_info->shop_id, $request->vendor);
        $data['_dm'] = TransactionDebitMemo::getOpenDM($this->user_info->shop_id, $request->vendor);
        $data['vendor'] = Vendor::getVendor($this->user_info->shop_id, $request->vendor);
        
        $data['_applied'] = Session::get("applied_transaction");
        $data['action'] = '/member/transaction/receive_inventory/apply-transaction';
        return view('member.accounting_transaction.vendor.receive_inventory.load_transaction', $data);
    }
    public function postApplyTransaction(Request $request)
    {
        $apply_transaction = $request->_apply_transaction;
        Session::put("applied_transaction", $apply_transaction);
        $return['status']        = "success";
        $return['call_function'] = "success_apply_transaction";

        return json_encode($return);

    }
    public function getLoadAppliedTransaction(Request $request)
    {
        $applied_transaction = Session::get('applied_transaction');

        $return = null;
        $remarks = null;
        $total_disc_value = null;
        $total_sub_total = null;
        $tax = null;
        $total_disc_percentage = null;
        $percent = 0;
        $fix = 0;

        $warehouse_id            = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value)
            {
                $_applied_poline = TransactionPurchaseOrder::info_item($key);
                $info = TransactionPurchaseOrder::info($this->user_info->shop_id,$key);

                foreach ($_applied_poline as $poline_key => $poline_value) 
                {
                    $type = Item::get_item_type($poline_value->poline_item_id);
                    // if($type == 1 || $type == 4 || $type == 5 )
                    // {
                        if($poline_value->poline_item_status == 0)
                        {
                            $return[$key.'i'.$poline_key]['po_id'] = $poline_value->poline_po_id;
                            $return[$key.'i'.$poline_key]['item_id'] = $poline_value->poline_item_id;
                            $return[$key.'i'.$poline_key]['item_description'] = $poline_value->poline_description;
                            $return[$key.'i'.$poline_key]['item_um'] = $poline_value->poline_um;
                            $return[$key.'i'.$poline_key]['multi_um_id'] = $poline_value->multi_um_id;
                            $return[$key.'i'.$poline_key]['item_qty'] = $poline_value->poline_qty;
                            $return[$key.'i'.$poline_key]['item_rate'] = $poline_value->poline_rate;
                            $return[$key.'i'.$poline_key]['item_amount'] = $poline_value->poline_amount;
                            $return[$key.'i'.$poline_key]['item_discount_type'] = $poline_value->poline_discounttype;
                            $return[$key.'i'.$poline_key]['orig_qty'] = $poline_value->poline_orig_qty;
                            $return[$key.'i'.$poline_key]['item_discount_type'] = $poline_value->poline_discounttype;
                            $return[$key.'i'.$poline_key]['item_discount'] = $poline_value->poline_discount;
                            $return[$key.'i'.$poline_key]['taxable'] = $poline_value->taxable;

                            $data['_bin_item_warehouse'][$key.'i'.$poline_key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $poline_value->poline_sub_wh_id);
                        }
                    // }
                    
                }  
                if($info)
                {
                    $remarks .= $info->transaction_refnum != "" ? $info->transaction_refnum.', ' : 'PO#'.$info->po_id.', ';
                    $tax = $info->taxable;

                    if($info->po_discount_type == 'percent')
                    {
                        $total_sub_total += $info->po_subtotal_price;
                        $total_disc_value += (($info->po_subtotal_price * $info->po_discount_value) / 100);
                        $percent ++;

                    }    
                    elseif($info->po_discount_type == 'value')
                    {
                        $total_sub_total += $info->po_subtotal_price;
                        $total_disc_value += $info->po_discount_value;
                        $fix++;
                    }  
                }
            }
            if($total_sub_total <= 0)
            {
                $total_sub_total = 1;
            }
            if($percent <= 0 && $fix > 0)
            {
                $discount_type = 'value';
                $total_disc_percentage = $total_disc_value;
            }
            else
            {
                $total_sub = $total_sub_total == 0 ? 1 : $total_sub_total; 
                $discount_type = 'percent';
                $total_disc_percentage = number_format((($total_disc_value/ $total_sub_total) * 100 ),2,".",",");
            }
        }
        $data['_po']        = $return;
        $data['remarks']    = $remarks;
        $data['tax']        = $tax;
        $data['disc_type']  = $discount_type;
        $data['disc_percentage'] = $total_disc_percentage;
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['_item']      = Item::get_all_category_item();

        return view('member.accounting_transaction.vendor.receive_inventory.applied_transaction', $data);
    }

}

    