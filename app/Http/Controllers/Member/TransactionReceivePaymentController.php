<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Globals\Cart2;
use App\Globals\WarehouseTransfer;
use App\Globals\Warehouse2;
use App\Globals\Payment;
use App\Globals\Item;
use App\Globals\Customer;
use App\Globals\Accounting;
use App\Globals\Transaction;
use App\Globals\UnitMeasurement;
use App\Globals\TransactionReceivePayment;
use App\Globals\TransactionSalesInvoice;
use App\Globals\TransactionCreditMemo;
use App\Globals\AccountingTransaction;
use App\Globals\AuditTrail;

use App\Globals\Invoice;

use Redirect;
use Session;
use Carbon\Carbon;
use App\Globals\Pdf_global;

class TransactionReceivePaymentController extends Member
{
	public function getIndex()
	{
		$data['page'] = "Receive Payment";
		$data["_customer"]       = Customer::getAllCustomer();
		return view('member.accounting_transaction.customer.receive_payment.receive_payment_list',$data);
	}
	public function getLoadReceivePayment(Request $request)
	{
		$display = 10;
		$data['_receive_payment'] = TransactionReceivePayment::get($this->user_info->shop_id, $display, $request->search_keyword);
		$data['page'] = $data['_receive_payment']->currentPage();
		$data['number'] = ($data['page'] - 1) * $display;
		return view('member.accounting_transaction.customer.receive_payment.receive_payment_table',$data);		
	}
	public function getCreate(Request $request) 
	{
		$data['page'] = "Receive Payment";
        $data["_customer"]       = Customer::getAllCustomer();
        $data["transaction_refnum"]  = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'received_payment');
        $data['_payment_method'] = Payment::get_payment_method($this->user_info->shop_id);
        $data['_account']       = Accounting::getAllAccount('all','',['Bank']);
        $data['action'] 		= "/member/transaction/receive_payment/create-receive-payment";
        //$data['auto_undeposit_acc'] = AccountingTransaction::settings_value($this->user_info->shop_id, "auto_undeposit_acc");
        $data['c_id'] = $request->c_id;
        if($request->si_id)
        {
            $data['si'] = TransactionSalesInvoice::info($this->user_info->shop_id, $request->si_id);
            $data['c_id'] = $data['si'] != null ? $data['si']->inv_customer_id : '';
		}
        if($request->type == "credit_memo" && $request->cm_id)
        {
        	$check = TransactionCreditMemo::check_credit_memo($this->user_info->shop_id, $request->cm_id);
        	if(!$check) // REDIRECT NO INVOICE TO BE APPLIED // CREDIT MEMO WILL BE RETAIN AS CREDIT
        	{
        		return Redirect::back();
        	}
        	else // CONTINUE
        	{
        		$cmdata = TransactionCreditMemo::info($this->user_info->shop_id, $request->cm_id);
        		if($cmdata)
        		{
        			$data['c_id'] = $cmdata->cm_customer_id;
        			$cmdata->cm_amount = $cmdata->cm_amount - TransactionCreditMemo::get_applied_cm($this->user_info->shop_id, $request->cm_id);
        			$data['cmdata'][0] = $cmdata;
        		}
        	}
        }
        if($request->id)
        {
        	$data['action']		= "/member/transaction/receive_payment/update-receive-payment";
        	$data['receive_payment'] = TransactionReceivePayment::info($this->user_info->shop_id, $request->id);
        	if($data['receive_payment'])
        	{
        	    $data['c_id'] = $data["receive_payment"]->rp_customer_id;
        	    $data['receive_payment_item'] = TransactionReceivePayment::info_item($request->id);
        		$data["_invoice"] = Invoice::getAllInvoiceByCustomerWithRcvPymnt($data["receive_payment"]->rp_customer_id, $request->id);
        	}
        	$data['cmdata'] = TransactionCreditMemo::get_applied_cm_rp_info($this->user_info->shop_id, $request->id);

        }
        Session::forget("apply_transaction_cm");

		return view('member.accounting_transaction.customer.receive_payment.receive_payment',$data);
	}

	public function getLoadCustomerReceivePayment(Request $request)
    {
    	$data = null;
    	$return = null;
    	if($request->rp_id)
		{
			$rp = TransactionReceivePayment::info($this->user_info->shop_id, $request->rp_id);
			if($rp['rp_customer_id'] == $request->cust)
			{
				$data["_invoice"] = Invoice::getAllInvoiceByCustomer($request->cust, false, $request->rp_id);
			}
			else
			{
				$data["_invoice"] = Invoice::getAllInvoiceByCustomer($request->cust);
			}
		}
		else
		{
			$data["_invoice"] = Invoice::getAllInvoiceByCustomer($request->cust);
		}
        return view('member.receive_payment.load_receive_payment_items', $data);
    }
	public function postCreateReceivePayment(Request $request)
	{
		$btn_action = $request->button_action;

		$insert['transaction_refnum']	 		= $request->transaction_refnumber;
		$insert['customer_id'] 			 		= $request->customer_id;
		$insert['customer_email']        		= $request->customer_email;
		$insert['transaction_payment_method']   = $request->transaction_payment_method;
		$insert['transaction_ref_no']     		= $request->transaction_ref_no;
		$insert['customer_memo']         	    = $request->customer_memo;
		$insert['transaction_date']       	    = date("Y-m-d", strtotime($request->transaction_date));
		$rp_total_amount = array_sum(str_replace(',', '',$request->rpline_amount));
		$insert['rp_total_amount']				= $rp_total_amount;
		/*$data['auto_undeposit_acc'] = AccountingTransaction::settings_value($this->user_info->shop_id, "auto_undeposit_acc");*/
		$undeposit_acc_id = TransactionReceivePayment::auto_undeposit_acc($this->user_info->shop_id);
		$insert['rp_ar_account']  				= $request->rp_ar_account == ''? $undeposit_acc_id : $request->rp_ar_account;
		$insert_item = null;
		$txn_line = $request->line_is_checked;
		$amount_to_credit = 0;
		if($txn_line)
		{
	        foreach($txn_line as $key => $txn)
	        {
	            if($txn == 1)
	            {
	                $insert_item[$key]["rpline_reference_name"]   = $request->rpline_txn_type[$key];
	                $insert_item[$key]["rpline_reference_id"]     = $request->rpline_txn_id[$key];
	                $insert_item[$key]["rpline_amount"] 		  = str_replace(',', '',$request->rpline_amount[$key]);
	                if($insert_item[$key]["rpline_amount"] > str_replace(',', '',$request->rpline_balance[$key]))
	                {
	                	$amount_to_credit += $insert_item[$key]["rpline_amount"] - str_replace(',', '',$request->rpline_balance[$key]);
	                	$insert_item[$key]["rpline_amount"] =  str_replace(',', '',$request->rpline_balance[$key]);
	                }
	            }
	        }
		}

		$cm_data = null;
        $return = null;
        $validate = null;
		if(count($request->cm_id) > 0)
		{
			foreach ($request->cm_id as $key => $value) 
			{
				$getcmdata = TransactionCreditMemo::info($this->user_info->shop_id, $value);
				if($getcmdata)
				{
					$balance = $getcmdata->cm_amount - $getcmdata->applied_cm_amount;
					if((double)$request->cm_applied_amount[$key] > (double)$balance)
					{
						$validate .= "Your entered amount for Credit number ".$getcmdata->transaction_refnum." is greater than reamining balance (".currency("PHP ",$balance).") of the credit.";
					}
					else
					{
						$cm_data[$value] = str_replace(",", "", $request->cm_applied_amount[$key]);
					}
				}
			}
		}
		if(!$validate)
		{
			$validate = TransactionReceivePayment::postInsert($this->user_info->shop_id, $insert, $insert_item);
		}
		if(is_numeric($validate))
		{
			TransactionReceivePayment::applied_transaction($this->user_info->shop_id, $validate, $cm_data);
			TransactionCreditMemo::auto_create_cm($this->user_info->shop_id, $validate, $amount_to_credit);
			$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'received_payment');
			AuditTrail::record_logs('Added', 'received_payment', $validate, "", serialize($transaction_data));
			$return['status'] = 'success';
			$return['status_message'] = 'Success receive payment.';
			$return['call_function'] = 'success_receive_payment';
			$return['status_redirect'] = AccountingTransaction::get_redirect('receive_payment', $validate ,$btn_action);
		}
		else
		{
			$return['status'] = 'error';
			$return['status_message'] = $validate;
		}

		return json_encode($return);
	}

	public function postUpdateReceivePayment(Request $request)
	{
		$btn_action = $request->button_action;
		$rp_id = $request->rp_id;
		$old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $rp_id, 'received_payment');

		$insert['transaction_refnum']	 		= $request->transaction_refnumber;
		$insert['customer_id'] 			 		= $request->customer_id;
		$insert['customer_email']        		= $request->customer_email;
		$insert['transaction_payment_method']   = $request->transaction_payment_method;
		$insert['transaction_ref_no']     		= $request->transaction_ref_no;
		$insert['customer_memo']         	    = $request->customer_memo;
		$insert['transaction_date']       	    = date("Y-m-d", strtotime($request->transaction_date));
		$rp_total_amount = array_sum(str_replace(',', '',$request->rpline_amount));
		$insert['rp_total_amount']				= $rp_total_amount;
		$undeposit_acc_id = TransactionReceivePayment::auto_undeposit_acc($this->user_info->shop_id);
		$insert['rp_ar_account']  				= $request->rp_ar_account == ''? $undeposit_acc_id : $request->rp_ar_account;

		$insert_item = null;
		$amount_to_credit = 0;
		$txn_line = $request->line_is_checked;
		if($txn_line)
		{
	        foreach($txn_line as $key => $txn)
	        {
	            if($txn == 1)
	            {
	                $insert_item[$key]["rpline_reference_name"]   = $request->rpline_txn_type[$key];
	                $insert_item[$key]["rpline_reference_id"]     = $request->rpline_txn_id[$key];
	                $insert_item[$key]["rpline_amount"] 		  = str_replace(',', '',$request->rpline_amount[$key]);
	                if($insert_item[$key]["rpline_amount"] > str_replace(',', '',$request->rpline_balance[$key]))
	                {
	                	$amount_to_credit += $insert_item[$key]["rpline_amount"] - str_replace(',', '',$request->rpline_balance[$key]);
	                	$insert_item[$key]["rpline_amount"] =  str_replace(',', '',$request->rpline_balance[$key]);
	                }
	            }
	        }
		}
		$cm_data = array();
  		$return = null;
        $validate = null;
		if(count($request->cm_id) > 0)
		{
			foreach ($request->cm_id as $key => $value) 
			{
				$getcmdata = TransactionCreditMemo::info($this->user_info->shop_id, $value);
				if($getcmdata)
				{
					$appliedcm = TransactionCreditMemo::get_applied_cm_rp($this->user_info->shop_id, $value, $rp_id);
					$balance = $getcmdata->cm_amount - ($getcmdata->applied_cm_amount - $appliedcm);
					if((double)$request->cm_applied_amount[$key] >= (double)$balance)
					{
						$validate .= "Your entered amount for Credit number ".$getcmdata->transaction_refnum." is greater than reamining balance (".currency("PHP ",$balance).") of the credit memo.";
					}
					else
					{
						$cm_data[$value] = str_replace(",", "", $request->cm_applied_amount[$key]);
					}
				}
			}
		}
		if(!$validate)
		{
			$validate = TransactionReceivePayment::postUpdate($rp_id, $this->user_info->shop_id, $insert, $insert_item);
		}
		if(is_numeric($validate))
		{
			$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $rp_id, 'received_payment');
			AuditTrail::record_logs('Edited', 'received_payment', $rp_id, serialize($old_transaction_data), serialize($transaction_data));

			TransactionReceivePayment::applied_transaction($this->user_info->shop_id, $validate, $cm_data);
			TransactionCreditMemo::auto_update_cm($this->user_info->shop_id, $validate, $amount_to_credit);
			$return['status'] = 'success';
			$return['status_message'] = 'Success updating payment.';
			$return['call_function'] = 'success_receive_payment';
			$return['status_redirect'] = AccountingTransaction::get_redirect('receive_payment', $validate ,$btn_action);
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
		$customer_id = $request->customer_id;
		return TransactionReceivePayment::countAvailableCredit($this->user_info->shop_id, $customer_id);
	}
	public function getLoadCredit(Request $request)
	{
		$data['_cm'] = TransactionCreditMemo::loadAvailableCredit($this->user_info->shop_id, $request->c);
		$data['action'] = "/member/transaction/receive_payment/applied-transaction";
		$data['applied'] = Session::get("apply_transaction_cm");
		$data['customer_name'] = Customer::get_name($this->user_info->shop_id, $request->c);
		return view("member.accounting_transaction.customer.receive_payment.load_transaction",$data);
	}
	public function postAppliedTransaction(Request $request)
	{
		$_transaction = null;
		$cm_id = $request->cm_id;
		if(count($cm_id) > 0)
		{
			foreach ($cm_id as $key => $value) 
			{
				$_transaction[$value] = $request->apply_amount[$key];
			}
			Session::put("apply_transaction_cm", $_transaction);

	        $return['call_function'] = "success_apply_transaction";
	        $return['status'] = "success";
		}
		else
		{
	        $return['status_message'] = "Please select credit memo";
	        $return['status'] = "error";		
		}

        return json_encode($return);
	}
    public function getLoadAppliedTransaction(Request $request)
    {
    	$getcm_data = Session::get("apply_transaction_cm");
    	$cmdata = null;
    	if($getcm_data)
		{
			foreach ($getcm_data as $key => $value) 
			{
				$cm_data = TransactionCreditMemo::info($this->user_info->shop_id, $key);
				$cmdata[$key] = $cm_data;
				$cmdata[$key]->cm_amount = $value;
			}
		}
		$data['cmdata'] = $cmdata;

		return view("member.accounting_transaction.customer.receive_payment.load_applied_cm", $data);
    }
	public function getPrint(Request $request)
	{
		$id = $request->id;
        $footer = AccountingTransaction::get_refuser($this->user_info);

        $data["receive_payment"] = TransactionReceivePayment::info($this->user_info->shop_id, $id);
        $data["_invoice"] = TransactionReceivePayment::info_item($id);
        $data["_cm_applied"] = TransactionReceivePayment::info_credit($id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "rp");
        $data['transaction_type'] = "Receive Payment";

        if($data["receive_payment"])
        {
            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_rp");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

	        $pdf = view('member.accounting_transaction.customer.receive_payment.rp_print', $data);
	        return Pdf_global::show_pdf($pdf, null, $footer, $format);
        }
        else
        {
        	return view('member.no_transaction');
        }
	}
}