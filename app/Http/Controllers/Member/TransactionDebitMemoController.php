<?php
namespace App\Http\Controllers\Member;
use Illuminate\Http\Request;

use App\Globals\AccountingTransaction;
use App\Globals\TransactionDebitMemo;
use App\Globals\TransactionPurchaseOrder;
use App\Globals\TransactionEnterBills;
use App\Globals\Item;
use App\Globals\Warehouse2;
use App\Globals\UnitMeasurement;
use App\Globals\Customer;
use App\Globals\Vendor;
use App\Globals\DebitMemo;
use App\Globals\Warehouse;
use App\Globals\Utilities;
use App\Globals\Pdf_global;
use App\Globals\Purchasing_inventory_system;
use App\Globals\ItemSerial;
use App\Globals\AuditTrail;
use App\Http\Controllers\Controller;
use Session;


class TransactionDebitMemoController extends Member
{
    public function getIndex()
    {
        $data['page'] = 'Debit Memo';
        //TransactionDebitMemo::transactionStatus($this->user_info->shop_id);
        return view('member.accounting_transaction.vendor.debit_memo.debit_memo_list', $data);
    }
    public function getLoadDebitMemo(Request $request)
    {
        $display = 10;
        $data['_debit_memo']  = TransactionDebitMemo::get($this->user_info->shop_id, $display, $request->search_keyword, $request->tab_type);
        $data['page'] = $data['_debit_memo']->currentPage();
        $data['number'] = ($data['page'] - 1) * $display;
        return view('member.accounting_transaction.vendor.debit_memo.debit_memo_table', $data);
    }
    public function getCreate(Request $request)
    {
        $data['page']       = 'Create Debit Memo';
        $data["_vendor"]    = Vendor::getAllVendor('active');
        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["transaction_refnum"] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'debit_memo');
        $data['action']     ='/member/transaction/debit_memo/create-debit-memo';

        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);

        $dm_id = $request->id;
        
        Session::forget("applied_transaction");
        if($request->po_id)
        {
            $sess[$request->po_id] = $request->po_id;
            $data['po'] = TransactionPurchaseOrder::info($this->user_info->shop_id, $request->po_id);

            Session::put("applied_transaction",$sess);
        }
        if($dm_id)
        {
            $data['dm']      = TransactionDebitMemo::info($this->user_info->shop_id, $dm_id);
            $data['_dmline'] = TransactionDebitMemo::info_item($dm_id);
            foreach ($data["_dmline"] as $key => $value)
            {
                $data['_bin_item_warehouse'][$key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $value->dbline_sub_wh_id);
            }
            $data['action']  ='/member/transaction/debit_memo/update-debit-memo';
        }
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');
        return view('member.accounting_transaction.vendor.debit_memo.debit_memo', $data);
    }
    public function getPrint(Request $request)
    {
        $dm_id = $request->id;

        $data["db"] = TransactionDebitMemo::info($this->user_info->shop_id, $dm_id);
        $data["_dbline"] = TransactionDebitMemo::info_item($dm_id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "dm");
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $data['transaction_type'] = "Debit Memo";

        if($data["db"])
        {
            $data['count_tax'] = TransactionDebitMemo::count_tax($dm_id);
            $data["_dbline"] = TransactionDebitMemo::infoline($this->user_info->shop_id, $data["_dbline"]);
            $data['total_tax']   = TransactionDebitMemo::infotax($this->user_info->shop_id, $data["_dbline"]);
            $data['subtotal']   = TransactionDebitMemo::subtotal($this->user_info->shop_id, $data["_dbline"]);
            
            $footer = AccountingTransaction::get_refuser($this->user_info);

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_dm");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

            $pdf = view('member.accounting_transaction.vendor.debit_memo.debit_memo_pdf', $data);
            return Pdf_global::show_pdf($pdf, null, $footer, $format);
        }
        else
        {
            return view('member.no_transaction');
        }
    }
    public function postCreateDebitMemo(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        $btn_action  = $request->button_action;

        $insert['transaction_refnumber']    = $request->transaction_refnumber;
        $insert['vendor_id']                = $request->vendor_id;
        $insert['vendor_email']             = $request->vendor_email;
        $insert['vendor_terms']             = $request->vendor_terms;
        $insert['transaction_date']         = $request->transaction_date;
        $insert['vendor_message']           = $request->vendor_message;
        $insert['vendor_memo']              = $request->vendor_memo;
        $insert['vendor_discounttype']      = $request->vendor_discounttype;
        $insert['vendor_discount']          = $request->vendor_discount != null ? str_replace(',', '', $request->vendor_discount): 0;
        $insert['vendor_tax']               = $request->vendor_tax;

        $insert_item = null;
        foreach ($request->item_id as $key => $value) 
        {
            if($value)
            {
                $insert_item[$key]['item_id']           = $value;
                $insert_item[$key]['item_description']  = $request->item_description[$key];

                $insert_item[$key]['item_sub_warehouse'] = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['item_um']           = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_qty']          = str_replace(',', '', $request->item_qty[$key]);
                $insert_item[$key]['item_rate']         = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_discount']     = 0;
                $insert_item[$key]['item_amount']       = str_replace(',', '', $request->item_amount[$key]);
                $insert_item[$key]['item_ref_name']     = $request->item_ref_name[$key];
                $insert_item[$key]['item_ref_id']       = $request->item_ref_id[$key];
                $insert_item[$key]['item_discount']     = $request->item_discount[$key];
                $insert_item[$key]['item_taxable']      = isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;

                $insert_item[$key]['bin_location']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
            }
        }
        $return = null;
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $validate = AccountingTransaction::inventory_validation('consume', $this->user_info->shop_id, $warehouse_id, $insert_item);
        if(!$validate)
        {
            $validate = TransactionDebitMemo::postInsert($this->user_info->shop_id, $insert, $insert_item);
        }
        if(is_numeric($validate))
        {
            TransactionDebitMemo::appliedTransaction_Debit_memo($this->user_info->shop_id, $validate, false);
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'debit_memo');
            AuditTrail::record_logs('Added', 'debit_memo', $validate, "", serialize($transaction_data));
            TransactionDebitMemo::appliedTransaction($this->user_info->shop_id, $validate);

            $return['status'] = 'success';
            $return['status_message'] = 'Success creating debit memo.';
            $return['call_function'] = 'success_debit_memo';
            $return['status_redirect'] = AccountingTransaction::get_redirect('debit_memo', $validate ,$btn_action);
        }
        else
        {
            $return['status'] = 'error';
            $return['status_message'] = $validate;
        }
        return json_encode($return);
    }
    public function postUpdateDebitMemo(Request $request)
    {
        $check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
        $btn_action  = $request->button_action;
        $debit_memo_id = $request->dm_id;
        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $debit_memo_id, 'debit_memo');

        $insert['transaction_refnumber']    = $request->transaction_refnumber;
        $insert['vendor_id']                = $request->vendor_id;
        $insert['vendor_email']             = $request->vendor_email;
        $insert['vendor_terms']             = $request->vendor_terms;
        $insert['transaction_date']         = $request->transaction_date;
        $insert['vendor_message']           = $request->vendor_message;
        $insert['vendor_memo']              = $request->vendor_memo;
        $insert['vendor_discounttype']      = $request->vendor_discounttype;
        $insert['vendor_discount']          = $request->vendor_discount != null ? str_replace(',', '', $request->vendor_discount): 0;
        $insert['vendor_tax']               = $request->vendor_tax;

        $insert_item = null;
        $return_po = null;
        
        foreach ($request->item_id as $key => $value) 
        {
            if($value)
            {
                $insert_item[$key]['item_id']           = $value;
                $insert_item[$key]['item_description']  = $request->item_description[$key];
                $insert_item[$key]['item_sub_warehouse'] = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['item_um']           = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
                $insert_item[$key]['item_qty']          = str_replace(',', '', $request->item_qty[$key]);
                $insert_item[$key]['item_rate']         = str_replace(',', '', $request->item_rate[$key]);
                $insert_item[$key]['item_discount']     = 0;
                $insert_item[$key]['item_amount']       = str_replace(',', '', $request->item_amount[$key]);
                $insert_item[$key]['item_ref_name']     = $request->item_ref_name[$key];
                $insert_item[$key]['item_ref_id']       = $request->item_ref_id[$key];
                $insert_item[$key]['item_discount']     = $request->item_discount[$key];
                $insert_item[$key]['item_taxable']      = isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;
                if($insert_item[$key]['item_ref_id'])
                {
                    $return_po[$insert_item[$key]['item_ref_id']] = '';
                }
                $insert_item[$key]['bin_location']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
            }
        }
        if(count($return_po) > 0)
        {
            Session::put('applied_transaction',$return_po);
        }
        $return = null;
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);

        $validate = AccountingTransaction::inventory_validation('consume', $this->user_info->shop_id, $warehouse_id, $insert_item, null, "debit_memo", $debit_memo_id);

        if(!$validate)
        {  
            $validate = TransactionDebitMemo::postUpdate($debit_memo_id, $this->user_info->shop_id, $insert, $insert_item);
        }

        if(is_numeric($validate))
        {
            TransactionDebitMemo::appliedTransaction_Debit_memo($this->user_info->shop_id, $validate, true);
            $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $debit_memo_id, 'debit_memo');
            AuditTrail::record_logs('Edited', 'debit_memo', $debit_memo_id, serialize($old_transaction_data), serialize($transaction_data));

            TransactionDebitMemo::appliedTransaction($this->user_info->shop_id, $validate, true);
            $return['status'] = 'success';
            $return['status_message'] = 'Success creating debit memo.';
            $return['call_function'] = 'success_debit_memo';
            $return['status_redirect'] = AccountingTransaction::get_redirect('debit_memo', $validate ,$btn_action);
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
        return TransactionEnterBills::countOpenBillTransaction($this->user_info->shop_id, $vendor_id);
    }
    public function getLoadTransaction(Request $request)
    {
        //EDEN
        // $data['_po'] = TransactionPurchaseOrder::getOpenPO($this->user_info->shop_id, $request->vendor);
        // $data['vendor'] = Vendor::getVendor($this->user_info->shop_id, $request->vendor);

        // $data['_applied'] = Session::get('applied_transaction');
        // $data['action']   = '/member/transaction/enter_bills/apply-transaction';
        // return view('member.accounting_transaction.vendor.debit_memo.load_transaction', $data);

        //EDRICH 
        $data['_bill'] = TransactionEnterBills::getBill($this->user_info->shop_id, $request->vendor);
        $data['vendor'] = Vendor::getVendor($this->user_info->shop_id, $request->vendor);

        $data['_applied'] = Session::get('applied_transaction');
        $data['action']   = '/member/transaction/enter_bills/apply-transaction';
        return view('member.accounting_transaction.vendor.debit_memo.load_transaction', $data); 
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
        // dd($applied_transaction);

        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value)
            {
                $_applied_itemline = TransactionEnterBills::get_info_item($key);    

                $info = TransactionEnterBills::info_vendor($this->user_info->shop_id,$key);

                $discount_total = 0;
                $sub_total = 0;
                $tax_total = 0;
                $item_total = 0;
                $remarks = null;

                foreach ($_applied_itemline as $itemline_key => $itemline_value) 
                {
                    // $type = Item::get_item_type($itemline_value->itemline_item_id);
                    // //get item type
                    // if($type == 1 || $type == 4 || $type == 5 )
                    // {
                        $data['item'][$key]['item_details'][$itemline_key]['itemline_id'] = $itemline_value->itemline_id;
                        $data['item'][$key]['item_details'][$itemline_key]['item_id'] = $itemline_value->itemline_item_id;
                        $data['item'][$key]['item_details'][$itemline_key]['item_description'] = $itemline_value->itemline_description;
                        $data['item'][$key]['item_details'][$itemline_key]['item_um'] = $itemline_value->itemline_um;
                        $data['item'][$key]['item_details'][$itemline_key]['multi_um_id'] = $itemline_value->multi_um_id;
                        $data['item'][$key]['item_details'][$itemline_key]['item_qty'] = $itemline_value->itemline_qty;
                        $data['item'][$key]['item_details'][$itemline_key]['item_rate'] = $itemline_value->itemline_rate;
                        $data['item'][$key]['item_details'][$itemline_key]['item_amount'] = $itemline_value->itemline_amount;
                        $data['item'][$key]['item_details'][$itemline_key]['item_discount_type'] = $itemline_value->itemline_discounttype;
                        $data['item'][$key]['item_details'][$itemline_key]['item_discount'] = $itemline_value->itemline_discount;
                        $data['item'][$key]['item_details'][$itemline_key]['taxable'] = $itemline_value->itemline_taxable;
                        $data['item'][$key]['item_details'][$itemline_key]['orig_qty'] = $itemline_value->itemline_orig_qty;

                        $param_bill['transaction_discount'] = $info->bill_discount_type;
                        $param_bill['transaction_discount_value'] = $info->bill_discount_value;
                        $param_bill['transaction_tax'] = $info->taxable;

                        $param_bill['tax'] = 0.12;
                        //item related data
                        //tbl_bill_item_line
                        $param_bill['item_discount_type']= $itemline_value->itemline_discounttype;
                        $param_bill['item_discount']= $itemline_value->itemline_discount;
                        $param_bill['item_tax'] = $itemline_value->itemline_taxable;

                        $param_bill['item_qty'] =   $itemline_value->itemline_orig_qty != 0 ? $itemline_value->itemline_orig_qty : 1;
                        $param_bill['item_rate'] =  $itemline_value->itemline_rate;
                        $param_bill['item_orig_qty'] = $itemline_value->itemline_orig_qty;

                        $data['item'][$key]['item_details'][$itemline_key]['computation'] = TransactionEnterBills::item_computation($param_bill);//total of computation

                        $total = $data['item'][$key]['item_details'][$itemline_key]['computation'];//total of computation

                       // $data['item'][$key]['item_details'][$itemline_key]['computation2'] = TransactionEnterBills::bill_computation(TransactionEnterBills::item_computation($param_bill),$key);
                        
                        $sub_total += $total['comp_amount'];
                        $discount_total += $total['comp_transaction_discount'];
                        $tax_total += $total['comp_amount_tax'];
                        $item_total += $itemline_value->itemline_orig_qty;
                    // }

                    //IF TRANSACTION IS SET BY PERCENT OR VALUE
                    $data['item'][$key]['item_line']['transaction_set_discount']= $transaction_set_discount = $param_bill['transaction_discount'];
                    //VALUE OF TRANSACTION SET DISCOUNT
                    $data['item'][$key]['item_line']['transaction_set_discount_value'] = $transaction_set_discount_value = $param_bill['transaction_discount_value'];
                    //TOTAL ITEM PER BILL
                    $data['item'][$key]['item_line']['total_item_qty'] = $total_item_qty = $item_total;
                    //SUB TOTAL AMOUNT PER BILL
                    $data['item'][$key]['item_line']['total_item_computation'] = $total_item_computation = $sub_total;
                    //TOTAL AMOUNT DISCOUNT IN ITEMS
                    $data['item'][$key]['item_line']['total_item_discount'] = $total_item_discount = $discount_total;
                    //TOTAL AMOUNT * TAX
                    $data['item'][$key]['item_line']['total_tax'] = $total_tax = $tax_total;

                    //TOTAL AMOUNT * DISCOUNT
                    if($param_bill['transaction_discount'] == 'percent')
                    {
                        //TOTAL DISCOUNT PER BILL
                        $data['item'][$key]['item_line']['total_discount_per_bill'] = (($transaction_set_discount_value / 100) * $total_item_computation);
                        $data['item'][$key]['item_line']['total_discount'] = (($transaction_set_discount_value / 100) * $total_item_computation) + $total_item_discount;
                        $data['item'][$key]['item_line']['bill_discount_value_percentage'] =  $transaction_set_discount_value; 
                         //TOTAL AMOUNT PER BILL
                        $data['item'][$key]['item_line']['total_bill'] = $tax_total + ($total_item_computation - (($transaction_set_discount_value / 100) * $total_item_computation));
                    }
                    else
                    {   //TOTAL DISCOUNT PER BILL
                        $data['item'][$key]['item_line']['total_discount_per_bill'] = $transaction_set_discount_value;
                        $data['item'][$key]['item_line']['total_discount'] =  $total_item_discount  + $transaction_set_discount_value;
                        // $data['item'][$key]['item_line']['bill_discount_value_percentage'] =  ($sub_total / $transaction_set_discount_value) / 100; 
                        $data['item'][$key]['item_line']['bill_discount_value_percentage'] = $sub_total;
                        //TOTAL AMOUNT PER BILL
                        $data['item'][$key]['item_line']['total_bill'] = $tax_total + ($total_item_computation - $transaction_set_discount_value);
                    }
                }

                //TOTAL DISCOUNT PER BILL
                $total_bill_discount = 0;
                $total_bill_tax = 0;
                $total_bill_amount = 0;

                foreach ($data['item'] as $key => $value)
                {
                   // $total_bill_discount += $data['item'][$key]['item_line']['bill_discount_value_percentage'];
                    $total_bill_amount += $total_item_computation;
                    $total_bill_discount += $data['item'][$key]['item_line']['total_discount_per_bill'];
                    $total_bill_tax += $total_tax;

                }

                $remarks .= $info->transaction_refnum != "" ? $info->transaction_refnum.', ' : 'PO#'.$info->bill_id.', ';

            }
        }
        // dd($total_bill_discount);
              // dd($bill_discount_value_percentage_total);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);
     

        
        $data['_get_item']        = $data['item'];
        $data['disc_percentage']  = ($total_bill_discount / $total_bill_amount) * 100;
        // dd( $data['_get_item'],$data['disc_percentage'],$total_bill_amount,  $total_bill_discount/$total_bill_amount);
        $data['disc_type']  = 'percent';
        // $data['disc_type']  = 'value';
        $data['remarks']    = $remarks;
        $data['tax']        =  $total_bill_tax != 0 ? 1 : 0;

        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['_item']      = Item::get_all_category_item();

        return view('member.accounting_transaction.vendor.debit_memo.applied_transaction', $data);
    }

    //EDEN
    // public function getLoadAppliedTransaction(Request $request)
    // {
    //     $applied_transaction = Session::get('applied_transaction');

    //     $return = null;
    //     $remarks = null;
    //     $total_disc_value = null;
    //     $total_sub_total = null;
    //     $tax = null;
    //     $total_disc_percentage = null;
    //     $percent = 0;
    //     $fix = 0;
    //     if(count($applied_transaction) > 0)
    //     {
    //         foreach ($applied_transaction as $key => $value)
    //         {
    //             $_applied_poline = TransactionPurchaseOrder::info_item($key);
    //             $info = TransactionPurchaseOrder::info($this->user_info->shop_id,$key);

    //             $remarks = null;
    //             foreach ($_applied_poline as $poline_key => $poline_value) 
    //             {
    //                 $type = Item::get_item_type($poline_value->poline_item_id);
    //                 if($type == 1 || $type == 4 || $type == 5 )
    //                 {
    //                     if($poline_value->poline_item_status == 0)
    //                     {
    //                         $return[$key.'i'.$poline_key]['po_id'] = $poline_value->poline_po_id;
    //                         $return[$key.'i'.$poline_key]['item_id'] = $poline_value->poline_item_id;
    //                         $return[$key.'i'.$poline_key]['item_description'] = $poline_value->poline_description;
    //                         $return[$key.'i'.$poline_key]['item_um'] = $poline_value->poline_um;
    //                         $return[$key.'i'.$poline_key]['multi_um_id'] = $poline_value->mumlti_um_id;
    //                         $return[$key.'i'.$poline_key]['item_qty'] = $poline_value->poline_qty;
    //                         $return[$key.'i'.$poline_key]['item_rate'] = $poline_value->poline_rate;
    //                         $return[$key.'i'.$poline_key]['item_amount'] = $poline_value->poline_amount;
    //                         $return[$key.'i'.$poline_key]['item_discount_type'] = $poline_value->poline_discounttype;
    //                         $return[$key.'i'.$poline_key]['item_discount'] = $poline_value->poline_discount;
    //                         $return[$key.'i'.$poline_key]['taxable'] = $poline_value->taxable;
    //                         $return[$key.'i'.$poline_key]['orig_qty'] = $poline_value->poline_orig_qty;
    //                     }
    //                 }
    //             }    
    //             if($info)
    //             {
    //                 $remarks .= $info->transaction_refnum != "" ? $info->transaction_refnum.', ' : 'PO#'.$info->po_id.', ';
    //                 //$term = $info->po_terms_id;
    //                 $tax = $info->taxable;

    //                 if($info->po_discount_type == 'percent')
    //                 {
    //                     $total_sub_total += $info->po_subtotal_price;
    //                     $total_disc_value += (($info->po_subtotal_price * $info->po_discount_value) / 100);
    //                     $percent ++;

    //                 }    
    //                 elseif($info->po_discount_type == 'value')
    //                 {
    //                     $total_sub_total += $info->po_subtotal_price;
    //                     $total_disc_value += $info->po_discount_value;
    //                     $fix++;
    //                 }  
    //             }
    //         }
    //         if($total_sub_total <= 0)
    //         {
    //             $total_sub_total = 1;
    //         }
    //         if($percent <= 0 && $fix > 0)
    //         {
    //             $discount_type = 'value';
    //             $total_disc_percentage = $total_disc_value;
    //         }
    //         else
    //         {
    //             $discount_type = 'percent';
    //             $total_disc_percentage = number_format((($total_disc_value/ $total_sub_total) * 100 ),2,".",",");
    //         }
    //     }
    //     $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
    //     $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
    //     $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);
        
    //     $data['_po']        = $return;
    //     $data['remarks']    = $remarks;
    //     $data['tax']        = $tax;
    //     //$data['term']       = $term;
    //     $data['disc_type']  = $discount_type;
    //     $data['disc_percentage'] = $total_disc_percentage;
    //     $data['_um']        = UnitMeasurement::load_um_multi();
    //     $data['_item']      = Item::get_all_category_item();


    //     return view('member.accounting_transaction.vendor.debit_memo.applied_transaction', $data);
    // }
}