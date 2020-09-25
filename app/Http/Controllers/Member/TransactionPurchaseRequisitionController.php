<?php

namespace App\Http\Controllers\Member;
use Illuminate\Http\Request;

use Redirect;

use App\Globals\Warehouse2;
use App\Globals\Warehouse;
use App\Globals\Utilities;
use App\Globals\Vendor;
use App\Globals\Pdf_global;
use App\Globals\UnitMeasurement;
use App\Globals\Purchasing_inventory_system;
use App\Globals\CustomerWIS;
use App\Globals\WarehouseTransfer;
use App\Globals\TransactionPurchaseRequisition;

use App\Globals\TransactionSalesOrder;
use App\Globals\TransactionSalesInvoice;
use App\Globals\TransactionEstimateQuotation;
use App\Globals\AccountingTransaction;


use App\Models\Tbl_vendor_item;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Session;
use App\Globals\Item;
use App\Globals\AuditTrail;
use App\Globals\RequisitionSlip;
use App\Globals\AdminNotification;
use Validator;
use Excel;
use DB;
class TransactionPurchaseRequisitionController extends Member
{
	public function getIndex()
	{
		$data['page'] = 'Purchase Requisition';
		$data['_list'] = RequisitionSlip::get($this->user_info->shop_id);
		return view('member.accounting_transaction.vendor.purchase_requisition.requisition_slip', $data);
	}
	public function getLoadRequisitionSlip(Request $request)
	{
        $display = 10;
		$data['_requisition_slip'] = RequisitionSlip::get($this->user_info->shop_id, $request->tab_type, $display, $request->search_keyword);
        $data['page'] = $data['_requisition_slip']->currentPage();
        $data['number'] = ($data['page'] - 1) * $display;
        $data['total_amount'] = currency('PHP', RequisitionSlip::get_total_amount($this->user_info->shop_id, $request->tab_type));

		return view('member.accounting_transaction.vendor.purchase_requisition.requisition_slip_table', $data);		
	}
	public function getCreate(Request $request)
	{
		$data['page']    = 'Create Purchase Requisition';
        $data['_item']   = Item::get_all_category_item([1,4,5]);
        $data['_um']     = UnitMeasurement::load_um_multi();
        $data["_vendor"] = Vendor::getAllVendor('active');
        $data['transaction_refnum'] = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'purchase_requisition');

        $data['action'] = '/member/transaction/purchase_requisition/create-submit';

        $warehouse_id            = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data["_reorder_point"]  = Session::get("reorderpoint_item_".$warehouse_id);

        $pr_id = $request->id;
        Session::forget('applied_transaction');
        if($pr_id)
        {
            $data["_reorder_point"] = null;
            $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        	$data['pr'] = RequisitionSlip::get_slip($this->user_info->shop_id, $pr_id);
        	$data['_prline'] = RequisitionSlip::get_slip_item($pr_id, $warehouse_id);
            $data['action'] = '/member/transaction/purchase_requisition/update-submit';
        }
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');
        $data['count_transaction'] = TransactionPurchaseRequisition::countTransaction($this->user_info->shop_id);
		return view('member.accounting_transaction.vendor.purchase_requisition.create_requisition_slip', $data);
	}
	public function postCreateSubmit(Request $request)
	{
        $check = AccountingTransaction::check_if_exist($request->rs_item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
		$return = RequisitionSlip::create($this->user_info->shop_id, $this->user_info->user_id, $request);
		return json_encode($return);
	}
	public function postUpdateSubmit(Request $request)
	{
        $check = AccountingTransaction::check_if_exist($request->rs_item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
		$return = RequisitionSlip::update($this->user_info->shop_id, $this->user_info->user_id, $request);
		return json_encode($return);
	}
	public function getPrint(Request $request)
	{
        $pr_id = $request->id;      
        $data['rs'] = RequisitionSlip::get_slip($this->user_info->shop_id, $pr_id);
		$data['_rs_item'] = RequisitionSlip::get_slip_item($pr_id , Warehouse2::get_current_warehouse($this->user_info->shop_id));
        $data['transaction_type'] = "Purchase Requisition";

        if($data['rs'])
        {
            foreach($data['_rs_item'] as $key => $value) 
            {
                $qty = UnitMeasurement::um_qty($value->rs_item_um);

                $total_qty = $value->rs_item_qty * $qty;
                $data['_rs_item'][$key]->qty = UnitMeasurement::um_view($total_qty,$value->item_measurement_id,$value->rs_item_um);
                $data['_rs_item'][$key]->rem_qty = UnitMeasurement::um_view($value->rs_rem_qty,$value->item_measurement_id,$value->rs_item_um);
            }
            $data['user'] = $this->user_info;
            $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "pr");

            $footer = AccountingTransaction::get_refuser($this->user_info);

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_pr");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

            $pdf = view('member.accounting_transaction.vendor.purchase_requisition.print_requisition_slip', $data);
            return Pdf_global::show_pdf($pdf,null, $footer, $format);
        }
        else
        {
            return view('member.no_transaction');
        }
	}
	public function getConfirm(Request $request, $slip_id)
    {
        $data['pr'] = RequisitionSlip::get_slip($this->user_info->shop_id, $slip_id);

        return view('member.accounting_transaction.vendor.purchase_requisition.pr_confirm', $data);
    }
    public function postConfirmSubmit(Request $request)
    {
        $pr_id = $request->id;
        $old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $pr_id, 'purchase_requisition');

        $pr_status = $request->status;
        if($pr_status == 'confirm')
        {
            $update['requisition_slip_status'] = 'closed';
        }
        $val = RequisitionSlip::check_pr_vendor($pr_id);
        if(!$val)
        {
            $return = RequisitionSlip::update_status($this->user_info->shop_id, $pr_id, $update);
            $data = null;
            if($return)
            {
                AdminNotification::update_notification($this->user_info->shop_id,'purchase_requisition', $pr_id, $this->user_info->user_id);
                $data['status'] = 'success';
                $data['call_function'] = 'success_confirm'; 

                $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $pr_id, 'purchase_requisition');
                AuditTrail::record_logs('Confirmed', 'purchase_requisition', $pr_id, serialize($old_transaction_data), serialize($transaction_data));
                
                RequisitionSlip::create_po($pr_id, $this->user_info->shop_id);
            }            
        }
        else
        {
            $data['status'] = 'error';
            $data['status_message'] = $val;
        }
        return json_encode($data);
    }
	public function getLoadTransaction()
	{
		$data['_so'] = TransactionSalesOrder::getAllOpenSO($this->user_info->shop_id);
		$data['_eq'] = TransactionEstimateQuotation::getAllOpenEQ($this->user_info->shop_id);

        $data['_applied'] = Session::get("applied_transaction");
		$data['action'] = '/member/transaction/purchase_requisition/apply-transaction';

		return view('member.accounting_transaction.vendor.purchase_requisition.load_transaction', $data);
	}

    public function postApplyTransaction(Request $request)
    {
        $_transaction = $request->_apply_transaction;
        
        Session::put('applied_transaction', $_transaction);

        $return['call_function'] = "success_apply_transaction";
        $return['status'] = "success";

        return json_encode($return);
    }
    public function getLoadAppliedTransaction(Request $request)
    {
        $applied_transaction = Session::get('applied_transaction');

        $return = null;
        $remarks = null;
        if(count($applied_transaction) > 0)
        {
            foreach ($applied_transaction as $key => $value) 
            {
                $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
                $get = TransactionSalesOrder::info_item_whse($key, $warehouse_id);
                $info = TransactionSalesOrder::info($this->user_info->shop_id, $key);
                if(count($get) > 0)
                {
                    foreach ($get as $key_item => $value_item)
                    {
                        if($value_item->item_type_id == 1 || $value_item->item_type_id == 4 || $value_item->item_type_id == 5)
                        {
                            $return[$key.'i'.$key_item]['so_id'] = $value_item->estline_est_id;
                            $return[$key.'i'.$key_item]['item_id'] = $value_item->estline_item_id;
                            $return[$key.'i'.$key_item]['item_description'] = $value_item->estline_description;
                            $return[$key.'i'.$key_item]['multi_um_id'] = $value_item->multi_um_id;
                            $return[$key.'i'.$key_item]['item_um'] = $value_item->estline_um;
                            $return[$key.'i'.$key_item]['item_qty'] = $value_item->estline_qty;
                            $return[$key.'i'.$key_item]['item_rate'] = $value_item->estline_rate;
                            $return[$key.'i'.$key_item]['item_amount'] = $value_item->estline_amount;
                            $return[$key.'i'.$key_item]['item_rate'] = $value_item->estline_rate;
                            $return[$key.'i'.$key_item]['item_invty_count'] = $value_item->invty_count;
                        }
                    }
                }
                if($info)
                {
                    $remarks .= $info->transaction_refnum != "" ? $info->transaction_refnum.', ' : 'SO#'.$info->est_id.', ';
                }
            }
        }
        $data['_item']  = Item::get_all_category_item([1,4,5]);
        $data['_transactions'] = $return;
        $data['remarks'] = $remarks;
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["_vendor"] = Vendor::getAllVendor('active');

        return view('member.accounting_transaction.vendor.purchase_requisition.applied_transaction', $data);
    }
}