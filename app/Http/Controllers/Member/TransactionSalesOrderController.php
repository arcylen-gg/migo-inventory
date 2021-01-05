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
use App\Globals\TransactionSalesOrder;
use App\Globals\TransactionEstimateQuotation;
use App\Globals\AccountingTransaction;
use App\Globals\Payment;
use App\Globals\AuditTrail;

use App\Models\Tbl_customer_estimate;

use Session;
use Carbon\Carbon;
use App\Globals\Pdf_global;

class TransactionSalesOrderController extends Member
{
	public function postRejectTransaction(Request $request)
	{
		$est_id = $request->est_id;
		$update['est_status'] = 'rejected';
		Tbl_customer_estimate::where('est_id', $est_id)->update($update);

		$data['status'] = 'success';
		$data['status_message'] = 'Sales Order Rejected';
		$data['call_function'] = 'sales_order_rejected';

		return json_encode($data);

	}
	public function getReject(Request $request)
	{
		$data['action'] = '/member/transaction/sales_order/reject-transaction';
		$data['est_id'] = $request->so_id;
		$data['sales_order_reject'] = Tbl_customer_estimate::where('est_id',$data['est_id'])->first();
		return view('member.accounting_transaction.customer.sales_order.reject_so',$data);
	}

  	public function getIndex()
	{
		$data['page'] = "Sales Order";
		return view('member.accounting_transaction.customer.sales_order.sales_order_list',$data);
	} 	
	public function getLoadSalesOrder(Request $request)
	{
		$data['status'] = 'accepted';
        if($request->tab_type)
        {
            $data['status'] = $request->tab_type;
        }
		$display = 10;
		$data['_sales_order'] = TransactionSalesOrder::get($this->user_info->shop_id, $display, $request->search_keyword, $request->tab_type);
		$data['page'] = $data['_sales_order']->currentPage();
		$data['number'] = ($data['page'] - 1) * $display;
        $data['total_amount'] = currency('PHP', TransactionSalesOrder::get_total_amount($this->user_info->shop_id, $request->tab_type)); 

		$data['tab'] = $request->tab_type;
        foreach ($data['_sales_order'] as $key => $value) 
        {
            $data['_sales_order'][$key]['balance'] = TransactionSalesOrder::getBalancePerSO($this->user_info->shop_id, $value->est_id, $value->est_customer_id);
        }

		return view('member.accounting_transaction.customer.sales_order.sales_order_table',$data);		
	}
	public function getCreate(Request $request)
	{
		$data['page'] = "Create Sales Order";		
        $data["_customer"]  = Customer::getAllCustomer();
        $data['_item']      = Item::get_all_category_item();
        $data["transaction_refnum"]  = AccountingTransaction::get_ref_num($this->user_info->shop_id, 'sales_order');
        $data['_um']        = UnitMeasurement::load_um_multi();
        $data['monthly_budget'] = AccountingTransaction::settings($this->user_info->shop_id, "monthly_budget");
        $data['action']		= "/member/transaction/sales_order/create-sales-order";
        $data['_payment_method'] = Payment::get_payment_method($this->user_info->shop_id);
        if($request->id)
        {
        	$data['action']		= "/member/transaction/sales_order/update-sales-order";
        	$data['sales_order'] = TransactionSalesOrder::info($this->user_info->shop_id, $request->id);
        	$data['sales_order_item'] = TransactionSalesOrder::info_item($request->id);
        	$data['sales_order_pm'] = TransactionSalesOrder::getPMline($request->id);
         	// dd($data['sales_order']);
        }
        Session::forget('applied_transaction_so');
        $data['check_barcode'] = AccountingTransaction::settings($this->user_info->shop_id, 'enable_barcode');
		return view('member.accounting_transaction.customer.sales_order.sales_order',$data);
	}
	public function getModalQuantityValidation(Request $request)
	{
		$data = null;
		$data['so_id'] = $request->so_id;
        if($data['so_id'] != 'null')
        {
        	$data['action']	= "/member/transaction/sales_order/update-sales-order";
        }
        else
        {
			$data['action']		= "/member/transaction/sales_order/create-sales-order";
        }
		$data['button_action'] =  $request->button_action;
		$data['item_name'] = TransactionSalesOrder::get_not_enough_item($request->item_id);
		$data['insert'] = TransactionSalesOrder::validated_transaction($request->insert);
		$data['insert_item'] = TransactionSalesOrder::get_insert_item($request->insert_item);	
		return view('member.accounting_transaction.customer.sales_order.modal_quantity_validation', $data);
	}
	public function postCreateSalesOrder(Request $request)
	{
		// dd($request);
		$check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
		$btn_action = $request->button_action;
		$insert['function_do'] = 'insert'; //what function will do
		$insert['tbl_name'] = 'tbl_customer_estimate'; //table affected
		$insert['column_for_reference_number'] = 'est_cheque_ref_no'; //column of table affected

		$insert['transaction_refnum'] 	 		= $request->transaction_refnum;
		$insert['customer_id'] 			 		= $request->customer_id;
		$insert['customer_email']        		= $request->customer_email;
		$insert['customer_address']      		= $request->customer_address;
		$insert['transaction_date']      		= datepicker_input($request->transaction_date);
		$insert['customer_message']      		= $request->customer_message;
		$insert['customer_memo']         		= $request->customer_memo;
		$insert['customer_ewt']			 		= $request->customer_ewt;
		$insert['customer_discounttype'] 		= $request->customer_discounttype;
		$insert['customer_discount_value']		= $request->customer_discount == ''? 0 : $request->customer_discount;
		$insert['customer_subtotal_price']		= floor(str_replace(',', '',$request->subtotal_price));
		$insert['customer_overall_price']		= floor(str_replace(',', '',$request->overall_price));
		$insert['transaction_payment_method']	= $request->transaction_payment_method == '' ? 0 : 0;
		$insert['transaction_ref_no']  			= $request->transaction_cheque_ref_no == '' ? 0 : 0;
		
		$insert_item = null;
		foreach ($request->item_id as $key => $value) 
		{
			if($value)
			{
				$insert_item[$key]['item_id'] 		   = $value;
				$insert_item[$key]['item_servicedate'] = datepicker_input($request->item_servicedate[$key]);
				$insert_item[$key]['item_description'] = $request->item_description[$key];
				$insert_item[$key]['item_um'] 		   = isset($request->item_um[$key]) ? $request->item_um[$key] : 0;
				$insert_item[$key]['item_qty'] 		   = str_replace(',', '', $request->item_qty[$key]);
				$insert_item[$key]['item_rate'] 	   = str_replace(',', '', $request->item_rate[$key]);
				$insert_item[$key]['item_discount']    = str_replace(',', '', $request->item_discount[$key]);
				$insert_item[$key]['item_remarks']     = $request->item_remarks[$key];
				$insert_item[$key]['item_amount']      = str_replace(',', '', $request->item_amount[$key]);
				$insert_item[$key]['item_taxable']     = isset($request->item_taxable[$key]) ? $request->item_taxable[$key] : 0;
				$insert_item[$key]['item_status']      = 0;
				$insert_item[$key]['item_remaining']   = str_replace(',', '', $request->item_qty[$key]);
				$insert_item[$key]['item_refname'] 	   = $request->item_refname[$key];
				$insert_item[$key]['item_refid'] 	   = $request->item_refid[$key];
			}
		}

		$_pm = null;
		foreach ($request->txn_payment_method as $key => $value) 
		{
			$_pm[$key]['pm_id'] = $value;
			$_pm[$key]['pm_ref_no'] = $request->txn_ref_no[$key];
			$_pm[$key]['pm_amount'] = str_replace(',', '',$request->txn_payment_amount[$key]);
		}

		$data = null;
		$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
		$validate_qty = TransactionSalesOrder::validate_qty($insert_item, $this->user_info->shop_id, $warehouse_id);
		if(array_filter($validate_qty) == null && $request->already_validate == 0 || $request->already_validate == 1)
        {
            $validate = TransactionSalesOrder::postInsert($this->user_info, $insert, $insert_item);
			if(is_numeric($validate))
			{
				$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $validate, 'sales_order');
				AuditTrail::record_logs('Added', 'sales_order', $validate, "", serialize($transaction_data));

				TransactionSalesOrder::applied_transaction($this->user_info->shop_id, $validate);
				TransactionSalesOrder::insertPMline($validate, $_pm);

				$data['status'] = 'success';
				$data['status_message'] = 'Success creating sales order.';
				$data['call_function'] = 'success_sales_order';
				$data['status_redirect'] = AccountingTransaction::get_redirect('sales_order', $validate ,$btn_action);
				Session::forget('applied_transaction_so');
			}
			else
			{
				$data['status'] 		  = 'error';
				$data['status_message'] = $validate;
			}
        }
        else
        {
			$data['call_function']  = 'validate_qty';
			foreach ($validate_qty as $key_validate_qty => $value_validate_qty)
			{
				$data['status'] 		= 'not_enough';
				$data['status_message'] = $validate_qty;
			}
			foreach ($insert_item as $key_array => $value_array)
			{
				$data['status_insert_item'][$key_array] = Warehouse2::implode_replace($value_array)."@";
			}
			$data['status_insert'] = Warehouse2::implode_replace($insert);
			$data['status_button_action'] = $btn_action;
			$data['status_so_id'] = null;
        }
		return json_encode($data);
	}

	public function postUpdateSalesOrder(Request $request)
	{
		$check = AccountingTransaction::check_if_exist($request->item_id);
        if($check['has_duplicate'] == true){
            $return['status'] = $check['status'];
            $return['status_message'] = $check['message'];
            return json_encode($return);
        }
		$btn_action = $request->button_action;
		$sales_order_id = $request->sales_order_id;
		$old_transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $sales_order_id, 'sales_order');

		$insert['transaction_refnum'] 	 = $request->transaction_refnum;
		$insert['customer_id'] 			 = $request->customer_id;
		$insert['customer_email']        = $request->customer_email;
		$insert['customer_address']      = $request->customer_address;
		$insert['transaction_date']      = datepicker_input($request->transaction_date);
		$insert['customer_message']      = $request->customer_message;
		$insert['customer_memo']         = $request->customer_memo;
		$insert['customer_ewt']			 	= $request->customer_ewt;
		$insert['customer_discounttype'] 	= $request->customer_discounttype;
		$insert['customer_discount_value']	= $request->customer_discount == ''? 0 : $request->customer_discount;
		$insert['customer_subtotal_price']	= floor(str_replace(',', '',$request->subtotal_price));
		$insert['customer_overall_price']	= floor(str_replace(',', '',$request->overall_price));
		$insert['transaction_payment_method']	= $request->transaction_payment_method == '' ? 0 : 0;
		$insert['transaction_ref_no']  			= $request->transaction_cheque_ref_no == '' ? '' : 0;

		$_pm = null;
		foreach ($request->txn_payment_method as $key => $value) 
		{
			$_pm[$key]['pm_id'] = $value;
			$_pm[$key]['pm_ref_no'] = $request->txn_ref_no[$key];
			$_pm[$key]['pm_amount'] = $request->txn_payment_amount[$key];
		}

		$insert_item = null;
		$return_so = null;
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
				$insert_item[$key]['item_remaining']   = $request->item_remaining[$key] != '' ? str_replace(',', '',$request->item_remaining[$key]) : str_replace(',', '', $request->item_qty[$key]);  
				$insert_item[$key]['item_refname'] 		= $request->item_refname[$key];
				$insert_item[$key]['item_refid'] 		= $request->item_refid[$key];
				$insert_item[$key]['item_status']      = $request->item_status[$key];
				if($insert_item[$key]['item_refid'])
				{
					$return_so[$insert_item[$key]['item_refid']] = '';
				}
			}
		}
		if(count($return_so) > 0)
		{
			Session::put('applied_transaction_so',$return_so);
		}
		$return = null;
		$warehouse_id = Warehouse2::get_current_warehouse($this->user_info->shop_id);
		$validate_qty = TransactionSalesOrder::validate_qty($insert_item, $this->user_info->shop_id, $warehouse_id);
		if(array_filter($validate_qty) == null && $request->already_validate == 0 || $request->already_validate == 1)
        {
            $validate = TransactionSalesOrder::postUpdate($sales_order_id, $this->user_info, $insert, $insert_item);
			if(is_numeric($validate))
			{
				$transaction_data = AccountingTransaction::audit_trail($this->user_info->shop_id, $sales_order_id, 'sales_order');
				AuditTrail::record_logs('Edited', 'sales_order', $sales_order_id, serialize($old_transaction_data), serialize($transaction_data));
				
				TransactionSalesOrder::applied_transaction($this->user_info->shop_id, $sales_order_id);
				TransactionSalesOrder::update_transaction_status($validate);

				TransactionSalesOrder::insertPMline($sales_order_id, $_pm);
				$return['status'] = 'success';
				$return['status_message'] = 'Success updating sales order.';
				$return['call_function'] = 'success_sales_order';
				$return['status_redirect'] = AccountingTransaction::get_redirect('sales_order', $validate ,$btn_action);
				Session::forget('applied_transaction_so');
			}
			else
			{
				$return['status'] = 'error';
				$return['status_message'] = $validate;
			}
        }
        else
        {
			$return['call_function']  = 'validate_qty';
			foreach ($validate_qty as $key_validate_qty => $value_validate_qty)
			{
				$return['status'] 		= 'not_enough';
				$return['status_message'] = $validate_qty;
			}
			foreach ($insert_item as $key_array => $value_array)
			{
				$return['status_insert_item'][$key_array] = Warehouse2::implode_replace($value_array)."@";
			}
			$return['status_insert']      = Warehouse2::implode_replace($insert);
			$return['status_button_action'] = $btn_action;
			$return['status_so_id'] = $sales_order_id;
        }
		return json_encode($return);
	}
	public function getCountTransaction(Request $request)
	{
		$customer_id = $request->customer_id;
		return TransactionSalesOrder::CountTransaction($this->user_info->shop_id, $customer_id);
	}

	public function getLoadTransaction(Request $request)
	{
		$data['_eq'] = TransactionEstimateQuotation::getOpenEQ($this->user_info->shop_id, $request->c);
		$data['customer_name'] = Customer::get_name($this->user_info->shop_id, $request->c);
		$data['action'] = '/member/transaction/sales_order/apply-transaction';
        $data['applied'] = Session::get('applied_transaction_so');
		return view("member.accounting_transaction.customer.sales_order.load_transaction", $data);
	}
    public function postApplyTransaction(Request $request)
    {
        $_transaction = $request->apply_transaction;
        Session::put('applied_transaction_so', $_transaction);

        $return['call_function'] = "success_apply_transaction";
        $return['status'] = "success";

        return json_encode($return);
    }
     public function getLoadAppliedTransaction(Request $request)
    {
        $_ids = Session::get('applied_transaction_so');

        $return = null;
        $remarks = null;
        if(count($_ids) > 0)
        {
            foreach ($_ids as $key => $value) 
            {
                $get = TransactionSalesOrder::info_item($key);
                $info = TransactionSalesOrder::info($this->user_info->shop_id, $key);

                foreach ($get as $key_item => $value_item)
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

                    $return[$key.'i'.$key_item]['refname'] = "estimate_quotation";
                    $return[$key.'i'.$key_item]['refid'] = $key;
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
        $data['_item']  = Item::get_all_category_item([1,4,5]);
        $data['_transactions'] = $return;
        $data['remarks'] = $remarks;
        $data['_um']        = UnitMeasurement::load_um_multi();

        return view('member.accounting_transaction.customer.sales_order.applied_transaction', $data);
    }
	
	public function getPrint(Request $request)
	{
		$id = $request->id;
        $footer = AccountingTransaction::get_refuser($this->user_info);

        $data['so'] = TransactionSalesOrder::info($this->user_info->shop_id, $id);
        $data["transaction_type"] = "Sales Order";
        $data['_signatories'] = AccountingTransaction::get_signatories($this->user_info->shop_id, "so");
        $data["so_item"] = TransactionSalesOrder::info_item($id);
        $data["so_pm"] = TransactionSalesOrder::getPMline($id);
        
	    if($data['so'])
        {
        	$data["so_item"] = TransactionSalesOrder::infoline($this->user_info->shop_id, $data["so_item"]);

            $_printed = AccountingTransaction::print_format($this->user_info->shop_id, "printable_so");
            $data['_header'] = AccountingTransaction::settings_value($this->user_info->shop_id, "printable_header");
            $data['content_width'] = "width: ".$_printed['width']."%";
            $data['printed_width'] = $_printed['width'];
            $format = $_printed['size'];

	        $pdf = view('member.accounting_transaction.customer.sales_order.so_print', $data);

	        $proj = AccountingTransaction::settings_value($this->user_info->shop_id, "project_name");
            if($proj != "default" && $proj != 'fieldmen' && $proj == 'migo')
            {
                $pdf = view("member.accounting_transaction.customer.sales_order.printables.".$proj."_pdf",$data);
			}
			
            if($request->ptype == 'dr' && $proj == 'migo')
            {
                $pdf = view("member.accounting_transaction.customer.sales_order.printables.".$proj."_dr_pdf",$data);
                return $pdf;
            }       
        	if($proj == 'migo')
        	{
        		$footer = null;
        	}

	        return Pdf_global::show_pdf($pdf, null, $footer, $format);
        }
        else
        {
        	return view('member.no_transaction');
        }
	}
}