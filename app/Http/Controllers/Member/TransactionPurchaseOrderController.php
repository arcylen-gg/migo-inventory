<?php
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Globals\Invoice;
use App\Globals\Accounting;
use App\Globals\Item;
use App\Globals\UnitMeasurement;
use App\Globals\Warehouse;
use App\Globals\Billing;
use App\Globals\Pdf_global;
use App\Globals\Utilities;
use App\Globals\Purchasing_inventory_system;
use App\Globals\TransactionPurchaseOrder;
use App\Globals\TransactionPurchaseRequisition;
use App\Globals\AccountingTransaction;
use App\Globals\TransactionSalesOrder;
use App\Globals\TransactionDebitMemo;
use App\Globals\RequisitionSlip;
use App\Globals\Warehouse2;
use App\Globals\TransactionSalesInvoice;
use App\Globals\TransactionSalesReceipt;
use App\Globals\Customer;
use App\Globals\CustomerWIS;
use App\Globals\Terms;
use App\Globals\Vendor;
use App\Globals\AuditTrail;
use App\Globals\Purchase_Order;
use App\Globals\ItemSerial;

use App\Models\Tbl_purchase_order_line;

use Carbon\Carbon;
use Session;
use Redirect;
use PDF;


class TransactionPurchaseOrderController extends Member
{
    public function getIndex()
    {
        $data['page'] = 'Purchase Order';
        return view('member.accounting_transaction.vendor.purchase_order.purchase_order_list', $data);
    }
    public function getLoadPurchaseOrder(Request $request)
    {
        $data['status'] = 'open';
        if($request->tab_type)
        {
            $data['status'] = $request->tab_type;
        }
         
        $display = 10;
        $data['_purchase_order'] = TransactionPurchaseOrder::get($this->user_info->shop_id, $display, $request->search_keyword, $request->tab_type);
        $data['page'] = $data['_purchase_order']->currentPage();
        $data['number'] = ($data['page'] - 1) * $display;
        $data['total_amount'] = currency('PHP', TransactionPurchaseOrder::get_total_amount($this->user_info->shop_id, $request->tab_type)); 

        $data['tab'] = $request->tab_type;
        foreach ($data['_purchase_order'] as $key => $value) 
        {
            $data['_purchase_order'][$key]['balance'] = TransactionPurchaseOrder::getBalancePerPO($this->user_info->shop_id, $value->po_id);
        }
        return view('member.accounting_transaction.vendor.purchase_order.purchase_order_table', $data);
    }
    public function getAddItem($po_id)
    {
        $po_data = Tbl_purchase_order_line::um()->where("poline_po_id",$po_id)->get();
        
        foreach ($po_data as $key => $value) 
        {
            Session::push('po_item',collect($value)->toArray());
        }
        $data["ctr_item"] = count(Session::get("po_item"));

        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["serial"]     = ItemSerial::check_setting();

        return view('member.accounting_transaction.vendor.purchase_order.po_load_item_session',$data);
    }
    public function getPrint(Request $request)
    {
        $po_id = $request->id;

        $data["po"] = TransactionPurchaseOrder::info($this->user_info->shop_id, $po_id);
        $data["_poline"]     = TransactionPurchaseOrder::info_item($po_id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "po");

        $data['shop_info'] = $this->user_info;
        $data['transaction_type'] = 'Purchase Order';
        if($data["po"])
        {
            $data['terms'] = Terms::terms($this->user_info->shop_id, $data['po']->po_terms_id);
            $data['count_tax'] = TransactionPurchaseOrder::count_tax($po_id);
            $data["_poline"] = TransactionPurchaseOrder::infoline($this->user_info->shop_id, $data["_poline"]);
            $data['total_tax']   = TransactionPurchaseOrder::infotax($this->user_info->shop_id, $data["_poline"]);
            $data['subtotal']   = TransactionPurchaseOrder::subtotal($this->user_info->shop_id, $data["_poline"]);

            $footer = AccountingTransaction::get_refuser($this->user_info);

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_po");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

            $for_migo = AccountingTransaction::settings_value($this->user_info->shop_id, "migo_customization");
            $pdf = view("member.accounting_transaction.vendor.purchase_order.purchase_order_pdf",$data);
            if(!$for_migo)
            {
                $proj = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
                if($proj != "default" && $proj)
                {
                    $pdf = view("member.accounting_transaction.vendor.purchase_order.printables.".$proj."_pdf",$data);
                }                
            }
            if($request->from == 'auto')
            {
                return $pdf;
            }
            else
            {
                return Pdf_global::show_pdf($pdf, null, $footer, $format);
            }
        }
        else
        {
            return view('member.no_transaction');
        }
    }
    public function getCreate(Request $request)
    {      
        $shop_id            = $this->user_info->shop_id;
        $data["page"]       = "Create Purchase order";
        $data["_vendor"]    = Vendor::getAllVendor('active');
        $data["_terms"]     = Terms::active_terms($shop_id);
        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['transaction_refnum'] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'purchase_order');

        $count_so = TransactionSalesOrder::getCountAllOpenSO($this->user_info->shop_id);
        $count_si = TransactionSalesInvoice::countDeliveredSalesInvoice($this->user_info->shop_id);
        $count_sr = TransactionSalesReceipt::countDeliveredSalesReceipt($this->user_info->shop_id);

        $data['count_transaction']  = $count_so + $count_si + $count_sr;

        $data['action']     = "/member/transaction/purchase_order/create-purchase-order";

        $warehouse_id            = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);

        $data["vendor_id"]       = $request->vendor_id;
        $data["terms_id"]        = $request->vendor_terms;
        $data["_reorder_point"]  = Session::get("reorderpoint_item_".$warehouse_id);
        $po_id = $request->id;

        
        Session::forget("applied_transaction");
        if($po_id)
        {
            $data["_reorder_point"] = null;
            $data["po"]            = TransactionPurchaseOrder::info($this->user_info->shop_id, $po_id);
            $data["_poline"]       = TransactionPurchaseOrder::info_item($po_id);

            foreach ($data["_poline"] as $key => $value)
            {
                $data['_bin_item_warehouse'][$key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $value->poline_sub_wh_id);
            }
            $data["action"]        = "/member/transaction/purchase_order/update-purchase-order";
        }
        
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');

        return view('member.accounting_transaction.vendor.purchase_order.purchase_order', $data);
    }

    public function postCreatePurchaseOrder(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }

        $btn_action  = $request->button_action;

        $insert['transaction_refnumber'] = $request->transaction_refnumber;
        $insert['vendor_id']             = $request->vendor_id;
        $insert['vendor_address']        = $request->vendor_address;
        $insert['vendor_email']          = $request->vendor_email;
        $insert['transaction_date']      = $request->transaction_date;
        $insert['transaction_duedate']   = $request->transaction_duedate;
        $insert['transaction_deliverydate']   = $request->delivery_date;
        $insert['vendor_message']        = $request->vendor_message;
        $insert['vendor_memo']           = $request->vendor_memo;
        $insert['vendor_ewt']            = 0;
        $insert['vendor_terms']          = $request->vendor_terms;
        $insert['vendor_discount']       = $request->vendor_discount != null ? str_replace(',', '', $request->vendor_discount): 0;
        $insert['vendor_discounttype']   = $request->vendor_discounttype != null ? $request->vendor_discounttype: 0;
        $insert['vendor_tax']            = $request->vendor_tax;
        $insert['vendor_subtotal']       = $request->vendor_subtotal;
        $insert['vendor_total']          = $request->vendor_total;

        $insert_item = null;
        foreach ($request->item_id as $key => $value) 
        {
            if($value)
            {
                $insert_item[$key]['item_id']          = $value;
                $insert_item[$key]['item_servicedate'] = $request->item_servicedate[$key];
                $insert_item[$key]['item_description'] = $request->item_description[$key];
                $insert_item[$key]['item_um']          = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_qty']         = str_replace(',', '', $request->item_qty[$key]);
                $insert_item[$key]['item_rate']        = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_discount']    = str_replace(',', '', $request->item_discount[$key]);
                $insert_item[$key]['item_remark']      = $request->item_remark[$key];
                $insert_item[$key]['item_taxable']     = isset($request->item_taxable[$key])? $request->item_taxable[$key] : 0;
                $insert_item[$key]['item_amount']      = str_replace(',', '', $request->item_amount[$key]);
                $insert_item[$key]['item_ref_name']    = $request->item_ref_name[$key];
                $insert_item[$key]['item_ref_id']      = $request->item_ref_id[$key];
                $insert_item[$key]['item_status']      = 0;
                $insert_item[$key]['item_remaining']   = str_replace(',', '', $request->item_qty[$key]);
                $insert_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['bin_location']      = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
            }
        }
        $validate = TransactionPurchaseOrder::postInsert($this->user_info->shop_id, $insert, $insert_item);

        $return = null;
        if(is_numeric($validate))
        {
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'purchase_order');
            AuditTrail::record_logs('Added', 'purchase_order', $validate, "", serialize($transaction_data));

            TransactionPurchaseOrder::applied_transaction($this->user_info->shop_id, $validate);
            $return['status'] = 'success';
            $return['status_message'] = 'Success creating purchase order.';
            $return['call_function'] = 'success_purchase_order';
            $return['status_redirect'] = AccountingTransaction::get_redirect('purchase_order', $validate ,$btn_action);
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $validate;
        }

        Session::forget("reorderpoint_item_".Warehouse2::get_current_warehouse($this->user_info->shop_id));
        return json_encode($return);
    }

    public function postUpdatePurchaseOrder(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        
        $po_id  = $request->po_id;
        $btn_action  = $request->button_action;

        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $po_id, 'purchase_order');
        
        $insert['transaction_refnumber'] = $request->transaction_refnumber;
        $insert['vendor_id']             = $request->vendor_id;
        $insert['vendor_address']        = $request->vendor_address;
        $insert['vendor_email']          = $request->vendor_email;
        $insert['vendor_terms']          = $request->vendor_terms;
        $insert['transaction_date']      = $request->transaction_date;
        $insert['transaction_duedate']   = $request->transaction_duedate;
        $insert['transaction_deliverydate']   = $request->delivery_date;
        $insert['vendor_message']        = $request->vendor_message;
        $insert['vendor_memo']           = $request->vendor_memo;
        $insert['vendor_ewt']            = 0;
        $insert['vendor_discount']       = $request->vendor_discount != null ? str_replace(',', '', $request->vendor_discount): 0;
        $insert['vendor_discounttype']   = $request->vendor_discounttype != null ? $request->vendor_discounttype: 0;
        $insert['vendor_tax']            = $request->vendor_tax;
        $insert['vendor_subtotal']       = $request->vendor_subtotal;
        $insert['vendor_total']          = $request->vendor_total;
        
        $insert_item = null;
        foreach ($request->item_id as $key => $value) 
        {
            if($value)
            {
                $insert_item[$key]['item_id']          = $value;
                $insert_item[$key]['item_servicedate'] = $request->item_servicedate[$key];
                $insert_item[$key]['item_description'] = $request->item_description[$key];
                $insert_item[$key]['item_um']          = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_qty']         = str_replace(',', '', $request->item_qty[$key]);
                $insert_item[$key]['item_status']      = $request->item_status[$key];
                $insert_item[$key]['item_rate']        = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_discount']    = str_replace(',', '', $request->item_discount[$key]);
                $insert_item[$key]['item_remark']      = $request->item_remark[$key];
                $insert_item[$key]['item_taxable']     = isset($request->item_taxable[$key])? $request->item_taxable[$key] : 0;
                $insert_item[$key]['item_amount']      = str_replace(',', '', $request->item_amount[$key]);
                $insert_item[$key]['item_ref_name']    = $request->item_ref_name[$key];
                $insert_item[$key]['item_ref_id']      = $request->item_ref_id[$key];
                $insert_item[$key]['item_remaining']   = $request->item_remaining[$key] != '' ? str_replace(',', '',$request->item_remaining[$key]) : str_replace(',', '', $request->item_qty[$key]);  
                $insert_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['bin_location']      = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
            }
        }
        $validate = TransactionPurchaseOrder::postUpdate($po_id, $this->user_info->shop_id, $insert, $insert_item);

        $return = null;
        if(is_numeric($validate))
        {
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $po_id, 'purchase_order');
            AuditTrail::record_logs('Edited', 'purchase_order', $po_id, serialize($old_transaction_data), serialize($transaction_data));

            TransactionPurchaseOrder::applied_transaction($this->user_info->shop_id, $validate);
            TransactionPurchaseOrder::update_transaction_status($validate);
            $return['status'] = 'success';
            $return['status_message'] = 'Success creating purchase order.';
            $return['call_function'] = 'success_purchase_order';
            $return['status_redirect'] = AccountingTransaction::get_redirect('purchase_order', $validate ,$btn_action);
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $validate;
        }
        return json_encode($return);
    }

    public function getLoadTransaction(Request $request)
    {
        $data['_so'] = TransactionSalesOrder::getAllOpenSO($this->user_info->shop_id);

        $data['_si'] = TransactionSalesInvoice::getDeliveredSalesInvoice($this->user_info->shop_id);
        $data['_sr'] = TransactionSalesReceipt::getDeliveredSalesReceipt($this->user_info->shop_id);

        $data['_applied'] = Session::get('applied_transaction');
        $data['action']   = '/member/transaction/purchase_order/apply-transaction';
        return view('member.accounting_transaction.vendor.purchase_order.load_transaction', $data);
    }
    public function postApplyTransaction(Request $request)
    {
        $apply_transaction = $request->_apply_transaction;
        Session::put("applied_transaction", $apply_transaction);

        $return['status'] = 'success';
        $return['call_function'] = 'success_apply_transaction';

        return json_encode($return);
    }
    public function getLoadAppliedTransaction(Request $request)
    {
        $applied_transaction = Session::get('applied_transaction');

        $return = null; 
        $remarks = null;
        $tax = 0;

        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value) 
            {
                if($value == 'sales_order')
                {
                    $get = TransactionSalesOrder::info_item($key);
                    $info = TransactionSalesOrder::info($this->user_info->shop_id, $key);


                    foreach ($get as $key_item => $value_item)
                    {
                        $item = Item::item($value_item->estline_item_id);
                        if($value_item->item_type_id == 1 || $value_item->item_type_id == 4 || $value_item->item_type_id == 5)
                        {
                            if($value_item->item_type_id == 4 || $value_item->item_type_id == 5)
                            {
                                $item->item_cost =  Item::get_item_bundle_cost($value_item->estline_item_id);
                            }               
                            $return[$key.'i'.$key_item]['service_date'] = $value_item->estline_service_date;
                            $return[$key.'i'.$key_item]['item_id'] = $value_item->estline_item_id;
                            $return[$key.'i'.$key_item]['item_description'] = $value_item->estline_description;
                            $return[$key.'i'.$key_item]['multi_um_id'] = $value_item->multi_um_id;
                            $return[$key.'i'.$key_item]['item_um'] = $value_item->estline_um;
                            $return[$key.'i'.$key_item]['item_qty'] = $value_item->estline_orig_qty;
                            $return[$key.'i'.$key_item]['item_rate'] = $item->item_cost * (UnitMeasurement::get_umqty($value_item->estline_um));
                            $return[$key.'i'.$key_item]['item_amount'] = $return[$key.'i'.$key_item]['item_rate'] *  $value_item->estline_orig_qty;
                            //$return[$key.'i'.$key_item]['item_amount'] = $value_item->estline_amount;
                            $return[$key.'i'.$key_item]['item_discount'] = $value_item->estline_discount;
                            $return[$key.'i'.$key_item]['item_discount_type'] = $value_item->estline_discount_type;
                            $return[$key.'i'.$key_item]['item_remarks'] = $value_item->estline_discount_remark;
                            $return[$key.'i'.$key_item]['taxable'] = $value_item->taxable;

                            $return[$key.'i'.$key_item]['refid'] = $value_item->estline_est_id;
                            $return[$key.'i'.$key_item]['refname'] = 'sales_order';

                        }
                    }
                    if($info)
                    {
                        $tax = $info->taxable;
                        $remarks .= $info->transaction_refnum != "" ? $info->transaction_refnum.', ' : 'SO#'.$info->est_id.', ';
                    }
                } 
                elseif($value == 'invoice')
                {
                    $get = CustomerWIS::get_inv_item($this->user_info->shop_id, $key);
                    $info = CustomerWIS::get_inv($this->user_info->shop_id, $key);


                    foreach ($get as $key_item => $value_item)
                    {
                        $item = Item::item($value_item->invline_item_id);

                        $type = Item::get_item_type($value_item->invline_item_id);
                        if($type == 1 || $type == 4 || $type == 5 )
                        {
                            if($type == 4 || $type == 5)
                            {
                                $item->item_cost =  Item::get_item_bundle_cost($value_item->invline_item_id);
                            }
                            $return[$key.'i'.$key_item]['service_date'] = $value_item->estline_service_date;
                            $return[$key.'i'.$key_item]['item_id'] = $value_item->invline_item_id;
                            $return[$key.'i'.$key_item]['item_description'] = $value_item->invline_description;
                            $return[$key.'i'.$key_item]['multi_um_id'] = $value_item->multi_um_id;
                            $return[$key.'i'.$key_item]['item_um'] = $value_item->invline_um;
                            $return[$key.'i'.$key_item]['item_qty'] = $value_item->invline_orig_qty;
                            $return[$key.'i'.$key_item]['item_rate'] = $item->item_cost * (UnitMeasurement::get_umqty($value_item->invline_um));
                            $return[$key.'i'.$key_item]['item_amount'] = $return[$key.'i'.$key_item]['item_rate'] *  $value_item->invline_orig_qty;
                            $return[$key.'i'.$key_item]['item_discount'] = $value_item->inv_discount_value;
                            $return[$key.'i'.$key_item]['item_discount_type'] = $value_item->inv_discount_type;
                            $return[$key.'i'.$key_item]['item_remarks'] = '';
                            $return[$key.'i'.$key_item]['taxable'] = $value_item->taxable;

                            $refname = "sales_invoice";
                            if($info)
                            {
                                if($info->is_sales_receipt == 1)
                                {
                                    $refname = "sales_receipt";
                                }
                            }

                            $return[$key.'i'.$key_item]['refname'] = $refname;
                            $return[$key.'i'.$key_item]['refid'] = $key;

                        }
                    }
                    if($info)
                    {
                        $tax = $info->taxable;
                        $con = 'SR#';
                        if($info->is_sales_receipt == 0)
                        {
                            $con = 'SI#';
                        }
                        $remarks .= $info->transaction_refnum != "" ? $info->transaction_refnum.', ' : $con.$info->inv_id.', ';
                    }
                }  
                
            }
        }
        $warehouse_id            = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);

        $data['_item']  = Item::get_all_category_item([1,4,5]);
        $data['_transactions'] = $return;
        $data['remarks'] = $remarks;
        $data['tax'] = $tax;
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["_vendor"] = Vendor::getAllVendor('active');
        
        return view('member.accounting_transaction.vendor.purchase_order.applied_transaction', $data);
    }
}
