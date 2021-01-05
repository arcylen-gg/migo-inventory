<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Globals\Cart2;
use App\Globals\WarehouseTransfer;
use App\Globals\Warehouse2;
use App\Globals\Item;
use App\Globals\Terms;
use App\Globals\Customer;
use App\Globals\Transaction;
use App\Globals\UnitMeasurement;
use App\Globals\TransactionSalesInvoice;
use App\Globals\TransactionSalesOrder;
use App\Globals\TransactionEstimateQuotation;
use App\Globals\AccountingTransaction;
use App\Globals\CustomerWIS;
use App\Globals\Payment;
use App\Globals\AuditTrail;
use App\Globals\ReceivePayment;

use Session;
use Carbon\Carbon;
use App\Globals\Pdf_global;

class TransactionSalesInvoiceController extends Member
{
  	public function getIndex()
	{
		$data['page'] = "Sales Invoice";
		return view('member.accounting_transaction.customer.sales_invoice.sales_invoice_list',$data);
	}
	public function getLoadSalesInvoice(Request $request)
	{
		$data['status'] = 'open';
        if($request->tab_type)
        {
            $data['status'] = $request->tab_type;
        }
	    $data['proj'] = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
		$display = 10;
		$data['_sales_invoice'] = TransactionSalesInvoice::get($this->user_info->shop_id, $display, $request->search_keyword, $request->tab_type);
		$data['page'] = $data['_sales_invoice']->currentPage();
		$data['number'] = ($data['page'] - 1) * $display;
        $data['total_amount'] = currency('PHP', TransactionSalesInvoice::get_total_amount($this->user_info->shop_id, $request->tab_type)); 
		$data['tab'] = $request->tab_type;
        foreach ($data['_sales_invoice'] as $key => $value) 
        {
            $data['_sales_invoice'][$key]['balance'] = TransactionSalesInvoice::getBalancePerSI($this->user_info->shop_id, $value->inv_id);
        }

		return view('member.accounting_transaction.customer.sales_invoice.sales_invoice_table',$data);		
	}
	public function getCreate(Request $request)
	{
		$data['page'] = "Create Sales Invoice";		
        $data["transaction_refnum"]  = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'sales_invoice');
        $data["_customer"]  = Customer::getAllCustomer();
        $data['_item']      = Item::get_all_category_item();
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data["_terms"]     = Customer::getTerms($this->user_info->shop_id,0);
        $data['action']		= "/member/transaction/sales_invoice/create-sales-invoice";
        $data['_sales_rep'] = TransactionSalesInvoice::getSalesRep($this->user_info->shop_id);

        $data['_payment_method'] = Payment::get_payment_method($this->user_info->shop_id);
        
        $data['sales_rep_enabled'] = AccountingTransaction::settings($this->user_info->shop_id, 'sales_representative');
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);
        $data['bank_interest'] = AccountingTransaction::settings($this->user_info->shop_id, 'bank_interest');
        $data['open_transaction'] = 'show';
        Session::forget('applied_transaction_si');

		$data['c_id'] = $request->c_id;
        if($request->eq_id || $request->so_id)
        {
        	$data['open_transaction'] = 'hide';
        	$eqso_id = $request->eq_id == '' ? $request->so_id : $request->eq_id;
            $sess[$eqso_id] = $eqso_id;
            $data['est'] = TransactionEstimateQuotation::info($this->user_info->shop_id, $eqso_id);
            $data['c_id'] = $data['est'] != null ? $data['est']->est_customer_id : '';
            
            Session::put("applied_transaction_si",$sess);
        }
        if($request->id)
        {
        	$data['sales_invoice'] = TransactionSalesInvoice::info($this->user_info->shop_id, $request->id);
        	$data['sales_invoice_item'] = TransactionSalesInvoice::info_item($request->id);
        	$data['sales_invoice_pm'] = TransactionSalesInvoice::getPMline($request->id);
        	
        	foreach ($data["sales_invoice_item"] as $key => $value)
            {
                $data['_bin_item_warehouse'][$key] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id, $value->invline_sub_wh_id);
            }
        	$data['action']		= "/member/transaction/sales_invoice/update-sales-invoice";
        }
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');

		return view('member.accounting_transaction.customer.sales_invoice.sales_invoice',$data);
	}
	public function postCreateSalesInvoice(Request $request)
	{
		$check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
		$btn_action = $request->button_action;

		$insert['function_do'] = 'insert'; //what function will do
		$insert['tbl_name'] = 'tbl_customer_invoice'; //table affected
		$insert['column_for_reference_number'] = 'inv_cheque_ref_no'; //column of table affected

		$insert['transaction_refnum'] 	 = $request->transaction_refnumber;
		$insert['customer_id'] 			 = $request->customer_id;
		$insert['customer_email']        = $request->customer_email;
		$insert['customer_address']      = $request->customer_billing_address;
		$insert['transaction_date']      = date("Y-m-d", strtotime($request->transaction_date));
		$insert['transaction_duedate']   = date("Y-m-d", strtotime($request->transaction_duedate));
		$insert['customer_message']      = $request->customer_message;
		$insert['customer_memo']         = $request->customer_memo;
		$insert['customer_ewt']          = $request->customer_ewt;
		$insert['customer_terms']        = $request->customer_terms;
		$insert['customer_discount']     = $request->customer_discount != '' ? $request->customer_discount : 0;
		$insert['customer_discounttype'] = $request->customer_discounttype;
		$insert['customer_tax'] 		 = $request->customer_tax != null ? $request->customer_tax : 0;
		$insert['customer_bank_interest'] = $request->customer_bank_interest;
		$insert['transaction_payment_method']	= $request->transaction_payment_method == '' ? 0 : 0;
		$insert['transaction_ref_no']  			= $request->transaction_cheque_ref_no == '' ? 0 : 0;
		$insert['inv_sales_rep_id']  			= $request->sales_rep_id == '' ? null : $request->sales_rep_id;

		$insert_item = null;

		foreach ($request->item_id as $key => $value) 
		{
			if($value)
			{
				$insert_item[$key]['item_id'] 		   	= $value;
				$insert_item[$key]['item_servicedate'] 	= date("Y-m-d", strtotime($request->item_servicedate[$key]));
				$insert_item[$key]['item_description'] 	= $request->item_description[$key];

				$insert_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
				$insert_item[$key]['item_um'] 			= isset($request->item_um[$key]) ? $request->item_um[$key] : null;
				$insert_item[$key]['item_qty'] 			= str_replace(',', '', $request->item_qty[$key]);
				$insert_item[$key]['item_rate'] 		= str_replace(',', '', $request->item_rate[$key]);
				$insert_item[$key]['item_discount'] 	= str_replace(',', '', $request->item_discount[$key]);
				$insert_item[$key]['item_remarks'] 		= $request->item_remarks[$key];
				$insert_item[$key]['item_amount'] 		= str_replace(',', '', $request->item_amount[$key]);
				$insert_item[$key]['item_taxable'] 		= isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;

				$insert_item[$key]['item_refname'] 		= $request->item_refname[$key];
				$insert_item[$key]['item_refid'] 		= $request->item_refid[$key];

				$insert_item[$key]['bin_location']		= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
			}
		}

		$_pm = null;
		foreach ($request->txn_payment_method as $key => $value) 
		{
			$_pm[$key]['pm_id'] = $value;
			$_pm[$key]['pm_ref_no'] = $request->txn_ref_no[$key];
			$_pm[$key]['pm_amount'] = str_replace(',', '', $request->txn_payment_amount[$key]);
		}

		$return = null;
		$validate = null;
		if($insert['customer_bank_interest'])
		{
			$validate = TransactionSalesInvoice::check_format_bank_interest($insert['customer_bank_interest']);
		}
		if(CustomerWIS::settings($this->user_info->shop_id) == 0 && !$validate)
		{
			$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
			$validate = AccountingTransaction::inventory_validation('consume', $this->user_info->shop_id, $warehouse_id, $insert_item);
		}
		elseif($this->user_info->shop_id == 81 && !$validate)
		{
			$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
			$validate = AccountingTransaction::inventory_validation('consume', $this->user_info->shop_id, $warehouse_id, $insert_item);
		}
		if(!$validate)
		{
			$return = null;
			$validate = TransactionSalesInvoice::postInsert($this->user_info, $insert, $insert_item);
		}
		if(is_numeric($validate))
		{
			$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'sales_invoice');
			AuditTrail::record_logs('Added', 'sales_invoice', $validate, "", serialize($transaction_data));

			TransactionSalesInvoice::applied_transaction($this->user_info->shop_id, $validate);
			TransactionSalesInvoice::insertPMline($validate, $_pm);

			$return['status'] = 'success';
			$return['status_message'] = 'Success creating invoice.';
			$return['call_function'] = 'success_invoice';
			$return['status_redirect'] = AccountingTransaction::get_redirect('sales_invoice', $validate ,$btn_action);
			Session::forget('applied_transaction_si');
		}
		else
		{
			$return['status'] = 'error';
			$return['status_message'] = $validate;
		}

		return json_encode($return);
	}

	public function postUpdateSalesInvoice(Request $request)
	{
		$check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
		$btn_action = $request->button_action;
		$invoice_id = $request->invoice_id;
		$old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $invoice_id, 'sales_invoice');

		$insert['function_do'] = 'update'; //what function will do
		$insert['tbl_name'] = 'tbl_customer_invoice'; //table affected
		$insert['column_for_reference_number'] = 'inv_cheque_ref_no'; //column of table affected

		$insert['transaction_refnum'] 	 = $request->transaction_refnumber;
		$insert['customer_id'] 			 = $request->customer_id;
		$insert['customer_email']        = $request->customer_email;
		$insert['customer_address']      = $request->customer_billing_address;
		$insert['transaction_date']      = date("Y-m-d", strtotime($request->transaction_date));
		$insert['transaction_duedate']   = date("Y-m-d", strtotime($request->transaction_duedate));
		$insert['customer_message']      = $request->customer_message;
		$insert['customer_memo']         = $request->customer_memo;
		$insert['customer_ewt']          = $request->customer_ewt;
		$insert['customer_terms']        = $request->customer_terms;
		$insert['customer_discount']     = $request->customer_discount != '' ? $request->customer_discount : 0;
		$insert['customer_discounttype'] = $request->customer_discounttype;
		$insert['customer_tax'] 		 = $request->customer_tax != null ? $request->customer_tax : 0;
		$insert['customer_bank_interest'] = $request->customer_bank_interest;
		$insert['transaction_payment_method']	= $request->transaction_payment_method == '' ? 0 : 0;
		$insert['transaction_ref_no']  			= $request->transaction_cheque_ref_no == '' ? 0 : 0;
		$insert['inv_sales_rep_id']  			= $request->sales_rep_id == '' ? null : $request->sales_rep_id;

		$insert_item = null;
		$return_si = null;
		foreach ($request->item_id as $key => $value) 
		{
			if($value)
			{
				$insert_item[$key]['item_id'] 		   	= $value;
				$insert_item[$key]['item_servicedate'] 	= date("Y-m-d", strtotime($request->item_servicedate[$key]));
				$insert_item[$key]['item_description'] 	= $request->item_description[$key];

				$insert_item[$key]['item_sub_warehouse']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;
				$insert_item[$key]['item_um'] 			= isset($request->item_um[$key]) ? $request->item_um[$key] : null;
				$insert_item[$key]['item_qty'] 			= str_replace(',', '', $request->item_qty[$key]);
				$insert_item[$key]['item_rate'] 		= str_replace(',', '', $request->item_rate[$key]);
				$insert_item[$key]['item_discount'] 	= str_replace(',', '', $request->item_discount[$key]);
				$insert_item[$key]['item_remarks'] 		= $request->item_remarks[$key];
				$insert_item[$key]['item_amount'] 		= str_replace(',', '', $request->item_amount[$key]);
				$insert_item[$key]['item_taxable'] 		= isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;
				
				$insert_item[$key]['item_refname'] 		= $request->item_refname[$key];
				$insert_item[$key]['item_refid'] 		= $request->item_refid[$key];
				
				$insert_item[$key]['bin_location']= isset($request->item_sub_warehouse[$key]) ? $request->item_sub_warehouse[$key] : null;

				if($insert_item[$key]['item_refid'])
				{
					$return_si[$insert_item[$key]['item_refid']] = '';
				}
			}
		}


		$_pm = null;
		foreach ($request->txn_payment_method as $key => $value) 
		{
			$_pm[$key]['pm_id'] = $value;
			$_pm[$key]['pm_ref_no'] = $request->txn_ref_no[$key];
			$_pm[$key]['pm_amount'] = $request->txn_payment_amount[$key];
		}
		if(count($return_si) > 0)
		{
			Session::put('applied_transaction_si',$return_si);
		}

		$return = null;

		$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
		$validate = null;
		if($insert['customer_bank_interest'])
		{
			$validate = TransactionSalesInvoice::check_format_bank_interest($insert['customer_bank_interest']);
		}
		if(CustomerWIS::settings($this->user_info->shop_id) == 0 && !$validate)
		{
			$validate = AccountingTransaction::inventory_validation('consume', $this->user_info->shop_id, $warehouse_id, $insert_item,null, "sales_invoice", $invoice_id);
		}
		elseif($this->user_info->shop_id == 81 && !$validate)
		{
			$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
			$validate = AccountingTransaction::inventory_validation('consume', $this->user_info->shop_id, $warehouse_id, $insert_item);
		}
		if(!$validate)
		{
			$return = null;
			$validate = TransactionSalesInvoice::postUpdate($invoice_id, $this->user_info, $insert, $insert_item);
		}
		if(is_numeric($validate))
		{
			$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $invoice_id, 'sales_invoice');
			AuditTrail::record_logs('Edited', 'sales_invoice', $invoice_id, serialize($old_transaction_data), serialize($transaction_data));

			TransactionSalesInvoice::applied_transaction($this->user_info->shop_id, $validate, true);
			
			TransactionSalesInvoice::insertPMline($invoice_id, $_pm);
			$return['status'] = 'success';
			$return['status_message'] = 'Success updating invoice.';
			$return['call_function'] = 'success_invoice';
			$return['status_redirect'] = AccountingTransaction::get_redirect('sales_invoice', $validate ,$btn_action);
			Session::forget('applied_transaction_si');
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
		return TransactionSalesInvoice::CountTransaction($this->user_info->shop_id, $customer_id);
	}
	public function getLoadTransaction(Request $request)
	{
		$data['_eq'] = TransactionEstimateQuotation::getOpenEQ($this->user_info->shop_id, $request->c);
		$data['_so'] = TransactionSalesOrder::getOpenSO($this->user_info->shop_id, $request->c);
		$data['customer_name'] = Customer::get_name($this->user_info->shop_id, $request->c);
		$data['action'] = '/member/transaction/sales_invoice/apply-transaction';

        $data['applied'] = Session::get('applied_transaction_si');
		return view("member.accounting_transaction.customer.sales_invoice.load_transaction", $data);
	}

    public function postApplyTransaction(Request $request)
    {
        $_transaction = $request->apply_transaction;
        Session::put('applied_transaction_si', $_transaction);

        $return['call_function'] = "success_apply_transaction";
        $return['status'] = "success";

        return json_encode($return);
    }
    public function getAppliedPm(Request $request)
    {
        $_ids = Session::get('applied_transaction_si');
        $data['_payment_method'] = Payment::get_payment_method($this->user_info->shop_id);
        $_pm = null;
        if(count($_ids) > 0)
        {
            foreach ($_ids as $key => $value) 
            {
                $_pm[$key] = TransactionSalesOrder::getPMline($key);
            }
        }
        $data['_so_pm'] = $_pm;
        return view('member.accounting_transaction.customer.sales_invoice.sales_invoice_pm_applied', $data);
    }
    public function getLoadAppliedTransaction(Request $request)
    {
        $_ids = Session::get('applied_transaction_si');

        $return = null;
        $remarks = null;
        $_pm = null;
        if(count($_ids) > 0)
        {
            foreach ($_ids as $key => $value) 
            {
                $get = TransactionSalesInvoice::transaction_data_item($key);
                $info = TransactionSalesInvoice::transaction_data($this->user_info->shop_id, $key);
                $_pm[$key] = TransactionSalesOrder::getPMline($key);

                foreach ($get as $key_item => $value_item)
                {
                	if($value_item->estline_status == 0)
                    {
	                    $return[$key.'i'.$key_item]['service_date'] = $value_item->estline_service_date;
	                    $return[$key.'i'.$key_item]['item_id'] = $value_item->estline_item_id;
	                    $return[$key.'i'.$key_item]['item_description'] = $value_item->estline_description;
	                    $return[$key.'i'.$key_item]['multi_um_id'] = $value_item->multi_um_id;
	                    $return[$key.'i'.$key_item]['item_um'] = $value_item->estline_um;
	                    $return[$key.'i'.$key_item]['item_qty'] = $value_item->estline_qty;
	                    $return[$key.'i'.$key_item]['item_rate'] = $value_item->estline_rate;
	                    $return[$key.'i'.$key_item]['item_amount'] = $value_item->estline_amount;
	                    $return[$key.'i'.$key_item]['item_discount'] = $value_item->estline_discount;
	                    $return[$key.'i'.$key_item]['item_discount_type'] = $value_item->estline_discount_type;
	                    $return[$key.'i'.$key_item]['item_remarks'] = $value_item->estline_discount_remark;
	                    $return[$key.'i'.$key_item]['taxable'] = $value_item->taxable;

	                    $refname = "estimate_quotation";
	                    if($info)
	                    {
	                    	if($info->is_sales_order == 1)
	                		{
	                			$refname = "sales_order";
	                		}
	                    }
	                    $return[$key.'i'.$key_item]['refname'] = $refname;
	                    $return[$key.'i'.$key_item]['refid'] = $key;
	                }
                }
                if($info)
                {
                	$con = 'SO#';
                	if($info->is_sales_order == 0)
                	{
                		$con = 'EQ#';
                	}
                    $remarks .= $info->transaction_refnum != "" ? $info->transaction_refnum.', ' : $con.$info->est_id.', ';
                }
            }
        }

        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        $warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
        $data['_bin_warehouse'] = Warehouse2::load_all_warehouse_select($this->user_info->shop_id, $this->user_info->user_id, $warehouse_id);
        
        $data['_item']  = Item::get_all_category_item();
        $data['_transactions'] = $return;
        $data['_so_pm'] = $_pm;

        $data['remarks'] = $remarks;
        $data['_um']        = UnitMeasurement::load_um_multi();

        return view('member.accounting_transaction.customer.sales_invoice.applied_transaction', $data);
    }
	public function getPrint(Request $request)
	{
		$id = $request->id;
        $footer = AccountingTransaction::get_refuser($this->user_info);

        $data['invoice'] = TransactionSalesInvoice::info($this->user_info->shop_id, $id);
        $data["transaction_type"] = "Sales Invoice";
        $data["invoice_item"] = TransactionSalesInvoice::info_item($id);
        $data["invoice_pm"] = TransactionSalesInvoice::getPMline($id);
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "si");
        $data['check_settings'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_bin_location');
        
        if($data['invoice'])
        {
        	$data['sales_rep'] = TransactionSalesInvoice::salesrep_info($data['invoice']->inv_sales_rep_id);
	        $data['terms'] = Terms::terms($this->user_info->shop_id, $data['invoice']->inv_terms_id);
	        $data['customer_address'] = TransactionSalesInvoice::customer_address($data['invoice']->inv_customer_id);
	        $data['count_tax'] = TransactionSalesInvoice::count_tax($id);
	        $data["invoice_item"] = TransactionSalesInvoice::infoline($this->user_info->shop_id, $data["invoice_item"]);
	        $data['total_tax']   = TransactionSalesInvoice::infotax($this->user_info->shop_id, $data["invoice_item"]);
            $data['subtotal']   = TransactionSalesInvoice::subtotal($this->user_info->shop_id, $data["invoice_item"]);

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_si");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

            //return view('member.accounting_transaction.customer.sales_invoice.si_print', $data);
	        $pdf = view('member.accounting_transaction.customer.sales_invoice.si_print', $data);

			$proj = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
			if($proj == 'woa'){
				$qty = 0;
				foreach ($data["invoice_item"] as $key => $value) {
					$qty += $value->invline_qty;
					$name = explode("-", $value->item_name);
					$data["invoice_item"][$key]['pattern'] = isset($name[0]) ? $name[0] : '';
					$data["invoice_item"][$key]['color'] = isset($name[1]) ? $name[1] : '';
					$data["invoice_item"][$key]['size'] = isset($name[2]) ? $name[2] : '';
				}
				$data["invoice_item"][0]['total_quantity'] = $qty;
			}
			
	        $proj = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
   //          if($proj != "default" && $proj)
   //          {
   //              $pdf = view("member.accounting_transaction.customer.sales_invoice.printables.".$proj."_pdf",$data);
			// }
            if($proj != "default" && $proj != 'fieldmen' && $proj == 'migo' || $proj == 'woa')
            {
            	if($proj == 'migo')
            	{
            		$data["transaction_type"] = "Delivery Receipt";
            	}
            	if($request->ptype == 'dr' && $proj == 'migo' || $proj == 'fieldmen')
            	{
                	$pdf = view("member.accounting_transaction.customer.sales_invoice.printables.".$proj."_dr_pdf",$data);
            		return $pdf;
            	}
            	else
            	{
                	$pdf = view("member.accounting_transaction.customer.sales_invoice.printables.".$proj."_pdf",$data);
            	}
            	if($proj == 'migo')
            	{
            		$footer = null;
            	}
			}

	        return Pdf_global::show_pdf($pdf, null, $footer, $format);
        }
        else
        {
        	return view('member.no_transaction');
        }
	}
}