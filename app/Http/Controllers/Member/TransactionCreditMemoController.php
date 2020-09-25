<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Globals\Cart2;
use App\Globals\WarehouseTransfer;
use App\Globals\Warehouse2; 
use App\Globals\Item;
use App\Globals\AuditTrail;
use App\Globals\Customer;
use App\Globals\Transaction;
use App\Globals\UnitMeasurement;
use App\Globals\TransactionCreditMemo;
use App\Globals\AccountingTransaction;

use Session;
use Carbon\Carbon;
use App\Globals\Pdf_global;

class TransactionCreditMemoController extends Member
{
    public function getIndex()
	{
		$data['page'] = "Credit Memo";
		return view('member.accounting_transaction.customer.credit_memo.credit_memo_list',$data);
	}
	public function getLoadCreditMemo(Request $request)
	{
		$data['status'] = 'open';
        if($request->tab_type)
        {
            $data['status'] = $request->tab_type;
        }
		$display = 10;
		$data['_credit_memo'] = TransactionCreditMemo::get($this->user_info->shop_id, $display, $request->search_keyword, $data['status']);
		$data['page'] = $data['_credit_memo']->currentPage();
		$data['number'] = ($data['page'] - 1) * $display;
        $data['total_amount'] = currency('PHP', TransactionCreditMemo::get_total_amount($this->user_info->shop_id, $data['status'])); 
        
		return view('member.accounting_transaction.customer.credit_memo.credit_memo_table',$data);		
	}
	public function getCreate(Request $request)
	{
		$data['page'] = "Create Credit Memo";		
        $data["_customer"]  = Customer::getAllCustomer();
        $data["transaction_refnum"]  = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'credit_memo');
        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['action']		= "/member/transaction/credit_memo/create-credit-memo";
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);

        if($request->id)
        {
        	$data['action']		= "/member/transaction/credit_memo/update-credit-memo";
        	$data['credit_memo'] = TransactionCreditMemo::info($this->user_info->shop_id, $request->id);
        	$data['credit_memo_item'] = TransactionCreditMemo::info_item($request->id);
        	foreach ($data["credit_memo_item"] as $key => $value)
            {
                $data['_bin_item_warehouse'][$key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $value->cmline_sub_wh_id);
            }
        }
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');

		return view('member.accounting_transaction.customer.credit_memo.credit_memo',$data);
	}
	public function postCreateCreditMemo(Request $request)
	{
		// dd($request);
		$check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
		$btn_action = $request->button_action;

		$insert['transaction_refnum'] 	 = $request->transaction_refnumber;
		$insert['customer_id'] 			 = $request->customer_id;
		$insert['customer_email']        = $request->customer_email;
		$insert['customer_address']      = $request->customer_address;
		$insert['transaction_date']      = $request->transaction_date;
		$insert['customer_message']      = $request->customer_message;
		$insert['customer_memo']         = $request->customer_memo;
        $insert['cm_used_ref_name'] 	 = "retain_credit";

		$insert_item = null;
		foreach ($request->item_id as $key => $value)
		{
			if($value)
			{
				$insert_item[$key]['item_id'] = $value;
				$insert_item[$key]['item_servicedate'] = $request->item_servicedate[$key];
				$insert_item[$key]['item_description'] = $request->item_description[$key];
				$insert_item[$key]['item_um'] = isset($request->item_um[$key]) ? $request->item_um[$key] : null;
				$insert_item[$key]['item_qty'] = str_replace(',', '', $request->item_qty[$key]);
				$insert_item[$key]['item_rate'] = str_replace(',', '', $request->item_rate[$key]);
				$insert_item[$key]['item_amount'] = str_replace(',', '', $request->item_amount[$key]);
				$insert_item[$key]['item_taxable'] = $request->item_taxable[$key];

                $insert_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['bin_location']      = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
			}
		}
		$return = null;
		$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
		$validate = AccountingTransaction::inventory_validation('refill', $this->user_info->shop_id, $warehouse_id, $insert_item);
		if(!$validate)
		{
			$validate = TransactionCreditMemo::postInsert($this->user_info->shop_id, $insert, $insert_item);
		}
		if(is_numeric($validate))
		{
			$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'credit_memo');
			AuditTrail::record_logs('Added', 'credit_memo', $validate, "", serialize($transaction_data));

			TransactionCreditMemo::insert_acctg_transaction($this->user_info->shop_id, $validate);
			$return['status'] = 'success';
			$return['status_message'] = 'Success creating credit memo.';
			$return['call_function'] = 'success_credit_memo';
			$return['status_redirect'] = AccountingTransaction::get_redirect('credit_memo', $validate ,$btn_action);
			if($request->use_credit == "refund")
			{
				$return['status_message'] = 'Success creating credit memo. Now redirecting to give a refund';
				$return['status_redirect'] = "/member/transaction/write_check/create?type=credit_memo&cm_id=".$validate;
			}
			if($request->use_credit == "apply")
			{
				$return['status_message'] = 'Success creating credit memo. Now redirecting to apply to an invoice';
				$return['status_redirect'] = "/member/transaction/receive_payment/create?type=credit_memo&cm_id=".$validate;
			}
		}
		else
		{
			$return['status'] = 'error';
			$return['status_message'] = $validate;
		}

		return json_encode($return);
	}

	public function postUpdateCreditMemo(Request $request)
	{
		$check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
		$btn_action = $request->button_action;
		$credit_memo_id = $request->credit_memo_id;
		$old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $credit_memo_id, 'credit_memo');

		$insert['transaction_refnum'] 	 = $request->transaction_refnumber;
		$insert['customer_id'] 			 = $request->customer_id;
		$insert['customer_email']        = $request->customer_email;
		$insert['customer_address']      = $request->customer_address;
		$insert['transaction_date']      = $request->transaction_date;
		$insert['customer_message']      = $request->customer_message;
		$insert['customer_memo']         = $request->customer_memo;
        $insert['cm_used_ref_name'] 	 = "retain_credit";

		$insert_item = null;
		foreach ($request->item_id as $key => $value)
		{
			if($value)
			{
				$insert_item[$key]['item_id'] = $value;
				$insert_item[$key]['item_servicedate'] = $request->item_servicedate[$key];
				$insert_item[$key]['item_description'] = $request->item_description[$key];
				$insert_item[$key]['item_um'] = isset($request->item_um[$key]) ? $request->item_um[$key] : null;
				$insert_item[$key]['item_qty'] = str_replace(',', '', $request->item_qty[$key]);
				$insert_item[$key]['item_rate'] = str_replace(',', '', $request->item_rate[$key]);
				$insert_item[$key]['item_amount'] = str_replace(',', '', $request->item_amount[$key]);
				$insert_item[$key]['item_taxable'] = $request->item_taxable[$key];
				
                $insert_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
                $insert_item[$key]['bin_location']      = isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
			}
		}
		$return = null;
		$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
		$validate = AccountingTransaction::inventory_validation('refill', $this->user_info->shop_id, $warehouse_id, $insert_item);
		if(!$validate)
		{
			$validate = TransactionCreditMemo::postUpdate($credit_memo_id, $this->user_info->shop_id, $insert, $insert_item);
		}
		if(is_numeric($validate))
		{

			$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $credit_memo_id, 'credit_memo');
			AuditTrail::record_logs('Edited', 'credit_memo', $credit_memo_id, serialize($old_transaction_data), serialize($transaction_data));

			TransactionCreditMemo::insert_acctg_transaction($this->user_info->shop_id, $validate);
			$return['status'] = 'success';
			$return['status_message'] = 'Success updating credit memo.';
			$return['call_function'] = 'success_credit_memo';
			$return['status_redirect'] = AccountingTransaction::get_redirect('credit_memo', $validate ,$btn_action);	
		}
		else
		{
			$return['status'] = 'error';
			$return['status_message'] = $validate;
		}

		return json_encode($return);
	}
	public function getPrint(Request $request)
	{
		$id = $request->id;
        $footer = AccountingTransaction::get_refuser($this->user_info);

        $data['cm'] = TransactionCreditMemo::info($this->user_info->shop_id, $id);
        $data["transaction_type"] = "Credit Memo";
        $data["_cmline"] = TransactionCreditMemo::info_item($id);
        
        if($data['cm'])
        {
        	$data["_cmline"] = TransactionCreditMemo::infoline($this->user_info->shop_id, $data["_cmline"]);

	        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "cm");

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_cm");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

	        $pdf = view('member.accounting_transaction.customer.credit_memo.cm_print', $data);
	        return Pdf_global::show_pdf($pdf, null, $footer, $format);
        }
        else
        {
        	return view('member.no_transaction');
        }
	}
}