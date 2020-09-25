<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Globals\Cart2;
use App\Globals\WarehouseTransfer;
use App\Globals\Warehouse2;
use App\Globals\Item;
use App\Globals\Customer;
use App\Globals\Transaction;
use App\Globals\UnitMeasurement;
use App\Globals\TransactionEstimateQuotation;
use App\Globals\AccountingTransaction;
use App\Models\Tbl_customer_estimate_line;
use App\Globals\AuditTrail;
use App\Globals\TransactionSalesOrder;

use Session;
use Carbon\Carbon;
use PDF2;
use App\Globals\Pdf_global;

class TransactionEstimateQuotationController extends Member
{
	public function getIndex()
	{
		$data['page'] = "Estimate and Quotation";
		return view('member.accounting_transaction.customer.estimate_quotation.estimate_quotation_list',$data);
	}	
	public function getLoadEstimateQuotation(Request $request)
	{
		$data['status'] = 'pending';
        if($request->tab_type)
        {
            $data['status'] = $request->tab_type;
        }
		$display = 10;
		$data['_estimate_quotation'] = TransactionEstimateQuotation::get($this->user_info->shop_id, $display, $request->search_keyword, $request->tab_type);
		$data['page'] = $data['_estimate_quotation']->currentPage();
        $data['number'] = ($data['page'] - 1) * $display;
        $data['total_amount'] = currency('PHP', TransactionEstimateQuotation::get_total_amount($this->user_info->shop_id, $request->tab_type)); 

		$data['tab'] = $request->tab_type;
        foreach ($data['_estimate_quotation'] as $key => $value) 
        {
            $data['_estimate_quotation'][$key]['balance'] = TransactionEstimateQuotation::getBalancePerEQ($this->user_info->shop_id, $value->est_id, $value->est_customer_id);
        }
		return view('member.accounting_transaction.customer.estimate_quotation.estimate_quotation_table',$data);		
	}
	public function getCreate(Request $request)
	{
		$data['page'] = "Create Estimate and Quotation";		
        $data["_customer"]  = Customer::getAllCustomer();
        $data["transaction_refnum"]  = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'estimate_quotation');
        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['action']		= "/member/transaction/estimate_quotation/create-estimate-quotation";

        $data['fieldmen'] = AccountingTransaction::settings($this->user_info->shop_id, "monthly_budget");
        $data['proposal_number'] = AccountingTransaction::settings($this->user_info->shop_id, "customer_proposal_number");
        if($request->id)
        {
        	$data['action']		= "/member/transaction/estimate_quotation/update-estimate-quotation";
        	$data['estimate_quotation'] = TransactionEstimateQuotation::info($this->user_info->shop_id, $request->id);
        	$data['estimate_quotation_item'] = TransactionEstimateQuotation::info_item($request->id);
			$data['_proposal'] = TransactionEstimateQuotation::get_proposal($this->user_info->shop_id, $data['estimate_quotation']->est_customer_id);
        }
        
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');
		return view('member.accounting_transaction.customer.estimate_quotation.estimate_quotation',$data);
	}
	public function postCreateEstimateQuotation(Request $request)
	{
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
		$insert['transaction_duedate']   = $request->transaction_duedate;
		$insert['customer_message']      = $request->customer_message;
		$insert['customer_memo']         = $request->customer_memo;
		$insert['customer_ewt']          = $request->customer_ewt;
		$insert['customer_discount']     = $request->customer_discount;
		$insert['customer_discounttype'] = $request->customer_discounttype;
		$insert['subtotal_price']        = $request->subtotal_price;
		$insert['overall_price'] 		 = $request->overall_price;

		$insert_item = null;
		foreach ($request->item_id as $key => $value) 
		{
			if($value)
			{
				$insert_item[$key]['item_id'] = $value;
				$insert_item[$key]['item_servicedate'] = date("Y-m-d", strtotime($request->item_servicedate[$key]));
				$insert_item[$key]['item_description'] = $request->item_description[$key];
				$insert_item[$key]['item_um'] = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
				$insert_item[$key]['item_qty'] = str_replace(',', '', $request->item_qty[$key]);
				$insert_item[$key]['item_rate'] = str_replace(',', '', $request->item_rate[$key]);
				$insert_item[$key]['item_discount'] = str_replace(',', '', $request->item_discount[$key]);
				$insert_item[$key]['item_remarks'] = $request->item_remarks[$key];
				$insert_item[$key]['item_amount'] = str_replace(',', '', $request->item_amount[$key]);
				$insert_item[$key]['item_taxable'] = isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;
				$insert_item[$key]['item_status']      = 0;
				$insert_item[$key]['item_remaining']   = str_replace(',', '', $request->item_qty[$key]);
				$insert_item[$key]['estline_proposal_number'] = isset($request->estline_proposal_number[$key]) ? $request->estline_proposal_number[$key] : null;
			}
		}

		$return = null;
		$validate = TransactionEstimateQuotation::postInsert($this->user_info, $insert, $insert_item);
		if(is_numeric($validate))
		{
			// TransactionSalesOrder::applied_transaction($this->user_info->shop_id, $validate);
			TransactionEstimateQuotation::insert_acctg_transaction($this->user_info->shop_id, $validate);
			$return['status'] = 'success';
			$return['status_message'] = 'Success creating estimate and quotation.';
			$return['call_function'] = 'success_estimate_quotation';
			$return['status_redirect'] = AccountingTransaction::get_redirect('estimate_quotation', $validate ,$btn_action);

			$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'estimate_quotation');
			AuditTrail::record_logs('Added', 'estimate_quotation', $validate, "", serialize($transaction_data));

		}
		else
		{
			$return['status'] = 'error';
			$return['status_message'] = $validate;
		}

		return json_encode($return);
	}

	public function getUpdateStatus(Request $request)
	{		
		
        $data["estimate_id"] = $request->id;
		
        $data["est"] = TransactionEstimateQuotation::info($this->user_info->shop_id, $request->id);
        $data['action'] = '/member/transaction/estimate_quotation/update-status-submit';
        $stat[0] = "pending";
        $stat[1] = "accepted";
        $stat[2] = "closed";
        $stat[3] = "rejected";

        $data["status"] = $stat;
        return view("member.accounting_transaction.customer.estimate_quotation.update_status",$data);
	}
	public function postUpdateStatusSubmit(Request $request)
	{
		
		$old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $request->eq_id, 'estimate_quotation');
		$update["est_status"] = $request->status;
        $update["est_accepted_by"] = $request->accepted_by;
        $update["est_accepted_date"] = date('Y:m:d h:i:s',strtotime($request->accepted_date));

        TransactionEstimateQuotation::update_status($request->eq_id, $update);
        $transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $request->eq_id, 'estimate_quotation');
		AuditTrail::record_logs(ucfirst($request->status), 'estimate_quotation', $request->eq_id, serialize($old_transaction_data), serialize($transaction_data));

        $return['status'] = 'success';
        $return['call_function'] = 'success_update';
        $return['status_message'] = 'Successfully updated.';

        return json_encode($return);
	}
	public function postUpdateEstimateQuotation(Request $request)
	{
		$check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
		$btn_action = $request->button_action;
		$estimate_quotation_id = $request->estimate_quotation_id;
		$old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $estimate_quotation_id, 'estimate_quotation');

		$insert['transaction_refnum'] 	 = $request->transaction_refnumber;
		$insert['customer_id'] 			 = $request->customer_id;
		$insert['customer_email']        = $request->customer_email;
		$insert['customer_address']      = $request->customer_address;
		$insert['transaction_date']      = $request->transaction_date;
		$insert['transaction_duedate']   = $request->transaction_duedate;
		$insert['customer_message']      = $request->customer_message;
		$insert['customer_memo']         = $request->customer_memo;
		$insert['customer_ewt']          = $request->customer_ewt;
		$insert['customer_discount']     = $request->customer_discount;
		$insert['customer_discounttype'] = $request->customer_discounttype;
		$insert['subtotal_price']        = $request->subtotal_price;
		$insert['overall_price'] 		 = $request->overall_price;
		

		$insert_item = null;
		foreach ($request->item_id as $key => $value) 
		{
			if($value)
			{
				$insert_item[$key]['item_id'] = $value;
				$insert_item[$key]['item_servicedate'] = datepicker_input($request->item_servicedate[$key]);
				$insert_item[$key]['item_description'] = $request->item_description[$key];
				$insert_item[$key]['item_um'] = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
				$insert_item[$key]['item_qty'] = str_replace(',', '', $request->item_qty[$key]);
				$insert_item[$key]['item_rate'] = str_replace(',', '', $request->item_rate[$key]);
				$insert_item[$key]['item_discount'] = str_replace(',', '', $request->item_discount[$key]);
				$insert_item[$key]['item_remarks'] = $request->item_remarks[$key];
				$insert_item[$key]['item_amount'] = str_replace(',', '', $request->item_amount[$key]);
				$insert_item[$key]['item_taxable'] = isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;
				$insert_item[$key]['item_status']      = $request->item_status[$key];
				$insert_item[$key]['item_remaining']   = $request->item_remaining[$key] != '' ? str_replace(',', '',$request->item_remaining[$key]) : str_replace(',', '', $request->item_qty[$key]);  
				$insert_item[$key]['estline_proposal_number'] = isset($request->estline_proposal_number[$key]) ? $request->estline_proposal_number[$key] : null;
			}
		}
		$return = null;
		$validate = TransactionEstimateQuotation::postUpdate($estimate_quotation_id, $this->user_info, $insert, $insert_item);
		if(is_numeric($validate))
		{
			// TransactionSalesOrder::applied_transaction($this->user_info->shop_id, $estimate_quotation_id);
			// TransactionSalesOrder::update_transaction_status($validate);
			TransactionEstimateQuotation::insert_acctg_transaction($this->user_info->shop_id, $estimate_quotation_id);
			$return['status'] = 'success';
			$return['status_message'] = 'Success update estimate and quotation.';
			$return['call_function'] = 'success_estimate_quotation';
			$return['status_redirect'] = AccountingTransaction::get_redirect('estimate_quotation', $validate ,$btn_action);


			$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $estimate_quotation_id, 'estimate_quotation');
			AuditTrail::record_logs('Edited', 'estimate_quotation', $estimate_quotation_id, serialize($old_transaction_data), serialize($transaction_data));
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

        $data['estimate'] = TransactionEstimateQuotation::info($this->user_info->shop_id, $id);
        $data["transaction_type"] = "Estimate & Quotation";
        $data["estimate_item"] = TransactionEstimateQuotation::info_item($id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "eq");
        $data['_header'] = AccountingTransaction::settings($this->user_info->shop_id, "printable_header");

        if($data['estimate'])
        {
        	$data["estimate_item"] = TransactionEstimateQuotation::infoline($this->user_info->shop_id, $data["estimate_item"]);

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_eq");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

	        $pdf = view('member.accounting_transaction.customer.estimate_quotation.eq_print', $data);
	        
	        return Pdf_global::show_pdf($pdf, null, $footer, $format);

			// $format["title"] = "A4";
			// $format["format"] = "A5";
			// $format["default_font"] = "sans-serif";

			// $pdf = PDF2::loadView('member.accounting_transaction.customer.estimate_quotation.eq_print', $data, [], $format);
			// return $pdf->stream('document.pdf');
        }
        else
        {
        	return view('member.no_transaction');
        }
	}
	public function getLoadProposal(Request $request)
	{
		$data['_proposal'] = TransactionEstimateQuotation::get_proposal($this->user_info->shop_id, $request->c_id);
		return view("member.accounting_transaction.customer.estimate_quotation.load_customer_proposal", $data);
	}
}