<?php
namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Tbl_customer;

use App\Globals\Warehouse2;
use App\Globals\Vendor;
use App\Globals\WriteCheck;
use App\Globals\Accounting;
use App\Globals\Purchase_Order;
use App\Globals\Billing;
use App\Globals\Item;
use App\Globals\Warehouse;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\TransactionPurchaseOrder;
use App\Globals\AccountingTransaction;
use App\Globals\TransactionEnterBills;
use App\Globals\TransactionCreditMemo;
use App\Globals\AuditTrail;

use App\Globals\TransactionWriteCheck;
use App\Globals\Pdf_global;
use Carbon\Carbon;
use Session;
use stdClass;
use Redirect;
class TransactionWriteCheckController extends Member
{
    public function getIndex()
    {
        $data['page'] = 'Write Check';
        //TransactionWriteCheck::transactionStatus($this->user_info->shop_id);
        return view('member.accounting_transaction.vendor.write_check.write_check_list', $data);
    }
    public function getLoadWriteCheck(Request $request)
    {
        $display = 10;
        $data['_write_check']  = TransactionWriteCheck::get($this->user_info->shop_id, $display, $request->search_keyword);
        //dd($data['_write_check']);
        $data['page'] = $data['_write_check']->currentPage();
        $data['number'] = ($data['page'] - 1) * $display;
        return view('member.accounting_transaction.vendor.write_check.write_check_table', $data);
    }
    public function getCreate(Request $request)
    {
        $data['page'] = 'Create Write Check';
        $data["_vendor"]    = Vendor::getAllVendor('active');
        $data["_name"]      = TransactionWriteCheck::get_all_customer_vendor($this->user_info->shop_id);
        $data['_item']      = Item::get_all_category_item([1,4,5], null, null, null, null, true);
        //dd($data['_item'][0]['item_list'][27]);
        $data['_account']   = Accounting::getAllAccount('all',null,['Expense','Other Expense','Cost of Goods Sold','Contra-Revenue']);
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["transaction_refnum"] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'write_check');
        $data['item_new_cost'] = AccountingTransaction::settings_value($this->user_info->shop_id, "item_new_cost");
        $data['auto_change_sales_price'] = AccountingTransaction::settings($this->user_info->shop_id, 'auto_change_sales_price');
        $data['warehouse_id'] =  Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $data['warehouse_id']);
        $data['_account_id']       = Accounting::getAllAccount('all','',['Bank']);

        Session::forget('applied_transaction');
        $data['action']     = '/member/transaction/write_check/create-write-check';
         $data['po_id'] = $request->po_id;

        if($data['po_id'])
        {
            $sess[$request->po_id] = $request->po_id;
            $data['po'] = TransactionPurchaseOrder::info($this->user_info->shop_id, $data['po_id']);

            Session::put("applied_transaction",$sess);
        }

        $wc_id = $request->id;
        if($wc_id)
        {
            $data['wc']      = TransactionWriteCheck::info($this->user_info->shop_id, $wc_id);
            $data['_wcline'] = TransactionWriteCheck::info_item($wc_id);
            $data["_wc_acct_line"] = TransactionWriteCheck::acc_line($wc_id);
            foreach ($data["_wcline"] as $key => $value)
            {
                $data['_bin_item_warehouse'][$key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $data['warehouse_id'], $value->wcline_sub_wh_id);
            }
            $data['action']  = '/member/transaction/write_check/update-write-check';
        }

        if($request->type == "credit_memo")
        {
            $cm_data = TransactionCreditMemo::info($this->user_info->shop_id, $request->cm_id);
            if($cm_data)
            {
                $data['cmdata'] = new stdClass;
                $data['cmdata']->cm_refname = "customer";
                $data['cmdata']->cm_id = $cm_data->cm_id;
                $data['cmdata']->cm_refid = $cm_data->cm_customer_id;
                $data['cmdata']->cm_account_id = isset($data['_account'][25]['account_id']) ? $data['_account'][25]['account_id'] : null;
                $data['cmdata']->cm_description = isset($data['_account'][25]['account_description']) ? $data['_account'][25]['account_description'] : null;
                $data['cmdata']->cm_amount = $cm_data->cm_amount;
                if($cm_data->cm_status == 1)
                {
                    return Redirect::back();
                }
            }
        }

        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');

        return view('member.accounting_transaction.vendor.write_check.write_check', $data);
    }
    public function getPrint(Request $request)
    {
        $wc_id = $request->id;         
       
        $data['wc']      = TransactionWriteCheck::info($this->user_info->shop_id, $wc_id);
        $data['_wcline'] = TransactionWriteCheck::info_item($wc_id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id ,"wc");
        $data["_wcline_acc"] = TransactionWriteCheck::acc_line($wc_id);

        $data['transaction_type'] = "Check Voucher";
        if($data['wc'])
        {
            $data['count_tax'] = TransactionWriteCheck::count_tax($wc_id);
            $data["_wcline"] = TransactionWriteCheck::infoline($this->user_info->shop_id, $data["_wcline"]);
            $data['total_tax']   = TransactionWriteCheck::infotax($this->user_info->shop_id, $data["_wcline"]);
            $data['subtotal']   = TransactionWriteCheck::subtotal($this->user_info->shop_id, $data["_wcline"]);
            $data['total_account_amount']   = TransactionWriteCheck::total_account_amount($this->user_info->shop_id, $data["_wcline_acc"]);

            $footer = AccountingTransaction::get_refuser($this->user_info);

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_wc");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

            $pdf = view("member.accounting_transaction.vendor.write_check.write_check_pdf",$data);
            return Pdf_global::show_pdf($pdf, null, $footer, $format);
        }
        else
        {
            return view('member.no_transaction');
        }
    }

    public function postCreateWriteCheck(Request $request)
    {
        $check1 = AccountingTransaction::check_if_exist($request->expense_account);
        $check2 = AccountingTransaction::check_if_exist($request->item_id);
        if($check1['has_duplicate'] == true)
        {
            $return['status'] = $check1['status'];
            $return['status_message'] = 'Please check for duplicated account details.';
            return json_encode($return);
        }
        if($check2['has_duplicate'] == true)
        {
            $return['status'] = $check2['status'];
            $return['status_message'] = $check2['message'];
            return json_encode($return);

        }
        $btn_action = $request->button_action;

        $insert['transaction_refnumber']   = $request->transaction_refnumber;
        $insert['vendor_id']               = $request->wc_ref_id;
        $insert['wc_reference_name']       = $request->wc_reference_name;
        $insert['vendor_email']            = $request->wc_customer_vendor_email;
        $insert['wc_mailing_address']      = $request->wc_mailing_address;
        $insert['wc_payment_date']         = $request->wc_payment_date;
        $insert['wc_cash_account_id']      = $request->wc_cash_account_id;
        $insert['wc_memo']                 = $request->wc_memo;
        $insert['wc_remarks']              = $request->wc_remarks;
        $insert['vendor_total']            = $request->wc_total_amount;
        $insert['vendor_discounttype']     = $request->vendor_discounttype;
        $insert['vendor_discount']         = $request->vendor_discount != null ? str_replace(',', '', $request->vendor_discount): 0;
        $insert['vendor_tax']              = $request->vendor_tax;
        
        
        $insert_acct = null;
        foreach($request->expense_account as $key_account => $value_account)
        {
            if($value_account)
            {
                $insert_acct[$key_account]['account_id']    = $value_account;
                $insert_acct[$key_account]['account_desc']  = $request->account_desc[$key_account];
                $insert_acct[$key_account]['account_amount']= str_replace(',', '', $request->account_amount[$key_account]);
            }
        }

        $insert_item = null;
        foreach($request->item_id as $key => $value)
        {
            if($value)
            {
                $insert_item[$key]['item_id']           = $value;
                $insert_item[$key]['item_ref_name']     = $request->item_ref_name[$key];
                $insert_item[$key]['item_ref_id']       = $request->item_ref_id[$key];
                $insert_item[$key]['item_description']  = $request->item_description[$key];
                $insert_item[$key]['item_um']           = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_qty']          = str_replace(',', '', $request->item_qty[$key]);
                $insert_item[$key]['item_rate']         = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_amount']       = str_replace(',', '', $request->item_amount[$key]);
                $insert_item[$key]['item_discount']     = $request->item_discount[$key];
                $insert_item[$key]['item_taxable']      = isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;

                $insert_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['bin_location']      = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
            }
        }
        $return = null;
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $validate = null;
        if(!$insert['wc_cash_account_id'])
        {
            $validate = "Select Bank Account";
        }
        if(count($insert_item) > 0 && !$validate)
        {
            $validate = AccountingTransaction::inventory_validation('refill', $this->user_info->shop_id, $warehouse_id, $insert_item);
        }
        /* CM */
        if($request->type_id && !$validate)
        {
            $set[$request->type_id] = $request->type_id;
            Session::put("applied_transaction_cm", $set);
        }
        if(!$validate)
        {
            $validate = TransactionWriteCheck::postInsert($this->user_info->shop_id, $insert, $insert_item, $insert_acct);
        }
        if(is_numeric($validate))
        {
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'write_check');
            AuditTrail::record_logs('Added', 'write_check', $validate, "", serialize($transaction_data));
            
            TransactionWriteCheck::appliedTransaction($this->user_info->shop_id, $validate);
            $return['status'] = 'success';
            $return['status_message'] = 'Success creating write check.';
            $return['call_function'] = 'success_write_check';
            $return['status_redirect'] = AccountingTransaction::get_redirect('write_check', $validate ,$btn_action);
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $validate;
        }
        return $return;
    }
    public function postUpdateWriteCheck(Request $request)
    {
        $check1 = AccountingTransaction::check_if_exist($request->expense_account);
        $check2 = AccountingTransaction::check_if_exist($request->item_id);
        if($check1['has_duplicate'] == true)
        {
            $return['status'] = $check1['status'];
            $return['status_message'] = 'Please check for duplicated account details.';
            return json_encode($return);
        }
        if($check2['has_duplicate'] == true)
        {
            $return['status'] = $check2['status'];
            $return['status_message'] = $check2['message'];
            return json_encode($return);

        }
        $btn_action = $request->button_action;
        $write_check_id = $request->wc_id;
        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $write_check_id, 'write_check');

        $insert['transaction_refnumber']   = $request->transaction_refnumber;
        $insert['vendor_id']               = $request->wc_ref_id;
        $insert['wc_reference_name']       = $request->wc_reference_name;
        $insert['vendor_email']            = $request->wc_customer_vendor_email;
        $insert['wc_cash_account_id']      = $request->wc_cash_account_id;
        $insert['wc_mailing_address']      = $request->wc_mailing_address;
        $insert['wc_payment_date']         = $request->wc_payment_date;
        $insert['wc_memo']                 = $request->wc_memo;
        $insert['wc_remarks']              = $request->wc_remarks;
        $insert['vendor_total']            = $request->wc_total_amount;
        $insert['vendor_discounttype']     = $request->vendor_discounttype;
        $insert['vendor_discount']         = $request->vendor_discount != null ? str_replace(',', '', $request->vendor_discount): 0;
        $insert['vendor_tax']              = $request->vendor_tax;
        
        $insert_acct = null;
        foreach($request->expense_account as $key_account => $value_account)
        {
            if($value_account)
            {
                $insert_acct[$key_account]['account_id']    = $value_account;
                $insert_acct[$key_account]['account_desc']  = $request->account_desc[$key_account];
                $insert_acct[$key_account]['account_amount']  = str_replace(',', '', $request->account_amount[$key_account]);
            }
        }

        $insert_item = null;
        $return_po = null;
        foreach($request->item_id as $key => $value)
        {
            if($value)
            {            
                $insert_item[$key]['item_id']           = $value;
                $insert_item[$key]['item_ref_id']       = $request->item_ref_id[$key];
                $insert_item[$key]['item_ref_name']     = $request->item_ref_name[$key];
                $insert_item[$key]['item_description']  = $request->item_description[$key];
                $insert_item[$key]['item_um']           = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_qty']          = str_replace(',', '', $request->item_qty[$key]);
                $insert_item[$key]['item_rate']         = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_amount']       = str_replace(',', '', $request->item_amount[$key]);
                $insert_item[$key]['item_discount']     = $request->item_discount[$key];
                $insert_item[$key]['item_taxable']      = isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;
                
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
        $validate = null; 
        if(!$insert['wc_cash_account_id'])
        {
            $validate = "Select Bank Account";
        }
        if(count($insert_item) > 0 && !$validate)
        {
                $validate = AccountingTransaction::inventory_validation('refill', $this->user_info->shop_id, $warehouse_id, $insert_item);
        }
        if(!$validate)
        {
            $validate = TransactionWriteCheck::postUpdate($write_check_id, $this->user_info->shop_id, $insert, $insert_item, $insert_acct);
        }
        if(is_numeric($validate))
        {
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $write_check_id, 'write_check');
            AuditTrail::record_logs('Edited', 'write_check', $write_check_id, serialize($old_transaction_data), serialize($transaction_data));

            TransactionWriteCheck::AppliedTransaction($this->user_info->shop_id, $write_check_id, true);
            $return['status'] = 'success';
            $return['status_message'] = 'Success creating write check.';
            $return['call_function'] = 'success_write_check';
            $return['status_redirect'] = AccountingTransaction::get_redirect('write_check', $validate ,$btn_action);
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $validate;
        }
        return $return;
    }
    public function getCountTransaction(Request $request)
    {
        $vendor_id = $request->vendor_id;
        return TransactionPurchaseOrder::countOpenPOTransaction($this->user_info->shop_id, $vendor_id);
    }
    public function getLoadTransaction(Request $request)
    {
        $data['_po'] = TransactionPurchaseOrder::getOpenPO($this->user_info->shop_id, $request->vendor);
        $data['vendor'] = Vendor::getVendor($this->user_info->shop_id, $request->vendor);

        $data['_applied'] = Session::get('applied_transaction');
        $data['action']   = '/member/transaction/write_check/apply-transaction';
        return view('member.accounting_transaction.vendor.write_check.load_transaction', $data);
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

                $remarks = null;
                foreach ($_applied_poline as $poline_key => $poline_value) 
                {
                    // $type = Item::get_item_type($poline_value->poline_item_id);
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
                            $return[$key.'i'.$poline_key]['item_discount'] = $poline_value->poline_discount;
                            $return[$key.'i'.$poline_key]['taxable'] = $poline_value->taxable;
                            $return[$key.'i'.$poline_key]['orig_qty'] = $poline_value->poline_orig_qty;

                            $data['_bin_item_warehouse'][$key.'i'.$poline_key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $poline_value->poline_sub_wh_id);
                        }
                    // }
                }    
                if($info)
                {
                    $remarks .= $info->transaction_refnum != "" ? $info->transaction_refnum.', ' : 'PO#'.$info->po_id.', ';
                    //$term = $info->po_terms_id;
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
                $discount_type = 'percent';
                $total_sub = $total_sub_total == 0 ? 1 : $total_sub_total;
                $total_disc_percentage = number_format((($total_disc_value/ $total_sub) * 100 ),2,".",",");
            }
        }

        $data['_po']        = $return;
        $data['remarks']    = $remarks;
        $data['tax']        = $tax;
        //$data['term']       = $term;
        $data['disc_type']  = $discount_type;
        $data['disc_percentage'] = $total_disc_percentage;
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['_item']      = Item::get_all_category_item();
        return view('member.accounting_transaction.vendor.write_check.applied_transaction', $data);

    }

}