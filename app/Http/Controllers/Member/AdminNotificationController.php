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
use App\Globals\TransactionSalesInvoice;
use App\Globals\TransactionSalesOrder;
use App\Globals\TransactionEstimateQuotation;

use App\Globals\AccountingTransaction;
use App\Globals\AdminNotification;
use App\Globals\CustomerWIS;

use Session;
use Carbon\Carbon;
use App\Globals\Pdf_global;

class AdminNotificationController extends Member
{
	public function getNotificationMessage(Request $request)
	{
		$data['_noti'] = AdminNotification::get_notification($this->user_info->shop_id, $request->id);

		return view("member.accounting_transaction.admin_notification.notification_message", $data);
	}
	public function getCheckNotificationMessage(Request $request)
	{
		$data['print'] = array();

        $auto_load_reorder_print = AccountingTransaction::settings($this->user_info->shop_id, 'auto_load_reorder_print');
		$noti = AdminNotification::get_all_notification($this->user_info->shop_id);
		if($auto_load_reorder_print)
		{
			foreach ($noti as $key => $value) 
			{
				if($value->print_transaction)
				{
					$data['print'][$key] = $value->print_transaction;
				}
			}
		}
		return json_encode($data);
	}
	public function getCheckNotif(Request $request)
	{
		 AdminNotification::check_notification($this->user_info->shop_id);
		 echo "success";
	}
	public function getReorderNotification(Request $request)
	{
		$data['_item'] = Session::get("reorder_item_".Warehouse2::get_current_warehouse($this->user_info->shop_id));
		$data['warehouse_id'] = Warehouse2::get_current_warehouse($this->user_info->shop_id);
		$data['action'] = "/member/transaction/notification/reorder-submit";

		if($request->warehouse_id)
		{
			$data['_item'] = Warehouse2::get_item_reorderpoint($this->user_info->shop_id, $request->warehouse_id);
			$data['warehouse_id'] = $request->warehouse_id;
		}

		return view("member.accounting_transaction.admin_notification.popup_reorder", $data);
	}
	public function postReorderSubmit(Request $request)
	{
		$item_id = $request->item_id;
		$warehouse_id = $request->warehouse_id;
		session(['warehouse_id_'.$this->user_info->shop_id => $warehouse_id]);
		$return = null;
		$json = null;
		if(count($item_id) > 0)
		{
			foreach ($item_id as $key => $value) 
			{
				if($value)
				{
					$get_data = Item::info($value);
					if($get_data)
					{
						$return[$key]['item_id'] = $value;
						$return[$key]['item_description'] = $get_data->item_purchasing_information;
						$return[$key]['multi_um_id'] = $get_data->item_measurement_id;
						$return[$key]['item_um'] = $get_data->multi_id;
						$return[$key]['item_rate'] = $get_data->item_cost;
						$return[$key]['item_qty'] = $request->item_reorder[$key] - $request->item_qty[$key];
						$return[$key]['item_amount'] = $get_data->item_cost * $return[$key]['item_qty'];
						$return[$key]['item_reorder'] = $request->item_reorder[$key];
						$return[$key]['item_rem'] = $request->item_qty[$key];
					}
				}
			}
		}
		if(count($return) > 0)
		{
			Session::put("reorderpoint_item_".$warehouse_id, $return);
			$json['status'] = "success";
			$json['status_message'] = "Success, You are now redirecting in purchase order.";
			$json['call_function'] = "success_reorder";
			$json['status_redirect'] = "/member/transaction/purchase_order/create";
			if(AccountingTransaction::settings($this->user_info->shop_id, "purchase_requisition"))
			{
				$json['status_message'] = "Success, You are now redirecting in purchase requisition.";
				$json['status_redirect'] = "/member/transaction/purchase_requisition/create";
			}
		}
		else
		{
			$json['status'] = "error";
			$json['status_message'] = "No item selected";
		}

		return json_encode($json);
	}
	public function getCheckReorder(Request $request)
	{
		$_warehouse = Warehouse2::get_other_warehouse($this->user_info->shop_id);
		$_items_reorder = array();
		foreach ($_warehouse as $key => $value) 
		{
			$_items_reorder[$value->warehouse_id]['warehouse'] = $value->warehouse_name;
			$_items_reorder[$value->warehouse_id]['item'] = Warehouse2::get_item_reorderpoint($this->user_info->shop_id, $value->warehouse_id);
		}

		$data['_items_reorder'] = $_items_reorder;
		// $data['is_pdf'] = 'true';
		$pdf = view('member.accounting_transaction.admin_notification.print_reorder', $data);

		return $pdf;
	    // return Pdf_global::show_pdf($pdf);
	}
	public function getCustomerUpdate(Request $request)
	{
		return AdminNotification::update_customer_category();
	}
}