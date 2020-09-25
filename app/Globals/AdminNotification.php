<?php
namespace App\Globals;
use DB;
use App\Models\Tbl_admin_notification;
use App\Models\Tbl_customer;
use App\Models\Tbl_customer_invoice;
use App\Globals\AccountingTransaction;
use App\Globals\WarehouseTransfer;
use App\Globals\CustomerWIS;
use App\Globals\TransactionPurchaseOrder;
use Log;
use Request;
use Session;
use Validator;
use Redirect;
use Carbon\Carbon;

/**
 * 
 *
 * @author Arcylen Gutierrez
 */
class AdminNotification
{
	public static function countNoti($shop_id, $warehouse_id = null)
	{
		$ctr = Tbl_admin_notification::where("notification_shop_id", $shop_id)->whereIn("warehouse_id",[$warehouse_id])->where("transaction_status","pending")->count();
		return $ctr;
	}
	public static function get($shop_id, $warehouse_id = null)
	{
		$return = Tbl_admin_notification::where("notification_shop_id", $shop_id)->whereIn("warehouse_id",[$warehouse_id])->whereIn("transaction_status",["pending","seen"])->get();

		foreach ($return as $key => $value) 
		{
			$return[$key] = $value;
			$return[$key]->user_name = "";
			$return[$key]->opposite_transaction = "";
			if($value->transaction_refname && $value->transaction_refid)
			{
				$get = AccountingTransaction::get_transaction_list($shop_id, $value->transaction_refname, $value->transaction_refid);
				if($get)
				{
					$return[$key]->user_name = $get->user_first_name." ".$get->user_last_name;
				}				
			}
		}

		return $return;
	}
	public static function get_notification($shop_id, $notification_id)
	{
		$data = Tbl_admin_notification::where("notification_shop_id", $shop_id)->where("notification_id", $notification_id)->first();
		if($data)
		{
			$data->is_popup = 0;
			$data->print_transaction = "";
			if($data->transaction_refname == 'wis')
			{
				$data->opposite_transaction = "wis/confirm/".$data->transaction_refid."?action=delivered";
				$data->is_popup = 1;
			}
			if($data->transaction_refname == 'warehouse_transfer')
			{
				$data->print_transaction = "warehouse_transfer/print?id=".$data->transaction_refid;
				$data->opposite_transaction = "receiving_report/receive-items/".$data->transaction_refid;
			}
			if($data->transaction_refname == 'purchase_order')
			{
				$data->print_transaction = "purchase_order/print?id=".$data->transaction_refid;
				$data->opposite_transaction = "receive_inventory/create?po_id=".$data->transaction_refid;
			}
			if($data->transaction_refname == "purchase_requisition")
			{
				$data->opposite_transaction = "purchase_requisition/confirm/".$data->transaction_refid;
				$data->is_popup = 1;
			}
			Tbl_admin_notification::where("notification_shop_id", $shop_id)->where("notification_id", $notification_id)->update(["transaction_status" => "seen"]);
		}
		return $data;
	}
	public static function get_all_notification($shop_id)
	{
		$warehouse = Warehouse2::get_current_warehouse($shop_id);
		$data = Tbl_admin_notification::where("notification_shop_id", $shop_id)
									  ->where("warehouse_id", $warehouse)
									  ->whereIn("transaction_status",['pending'])->get();
		foreach ($data as $key => $value) 
		{
			$value->is_popup = 0;
			$value->print_transaction = "";
			if($value->transaction_refname == 'wis')
			{
				$value->opposite_transaction = "wis/confirm/".$value->transaction_refid."?action=delivered";
				$value->is_popup = 1;
			}
			if($value->transaction_refname == 'warehouse_transfer')
			{
				$value->print_transaction = "warehouse_transfer/print?from=auto&id=".$value->transaction_refid;
				$value->opposite_transaction = "receiving_report/receive-items/".$value->transaction_refid;
			}
			if($value->transaction_refname == 'purchase_order')
			{
				$value->print_transaction = "purchase_order/print?from=auto&id=".$value->transaction_refid;
				$value->opposite_transaction = "receive_inventory/create?po_id=".$value->transaction_refid;
			}
			if($value->transaction_refname == "purchase_requisition")
			{
				$value->opposite_transaction = "purchase_requisition/confirm/".$value->transaction_refid;
				$value->is_popup = 1;
			}
			$data[$key] = $value;
		}
		return $data;
	}
	public static function update_notification($shop_id, $trans_refname, $trans_refid, $user_id = 0)
	{
		$update['transaction_status'] = "done";
		$update['user_id'] = $user_id;
		$up = Tbl_admin_notification::where("transaction_refname", $trans_refname)->where("transaction_refid", $trans_refid)->where("notification_shop_id", $shop_id)->first();
		if($up)
		{
			Tbl_admin_notification::where("notification_id",$up->notification_id)->update($update);
		}
	}
	public static function check_notification() /*CRON JOB*/
	{
		$first = WarehouseTransfer::insert_notification();	
		$second = CustomerWIS::insert_notification();
		$third = TransactionPurchaseOrder::insert_notification();
		$fourth = RequisitionSlip::insert_notification();
		$fifth = Self::update_customer_category();
		dd("success");
	}
	public static function update_customer_category() // FOR MIGO
	{
        $_shop = DB::table("tbl_shop")->get();

        foreach ($_shop as $keyshop => $valueshop) 
        {
	        $migo_customization = AccountingTransaction::settings($valueshop->shop_id, "migo_customization");
	        if($migo_customization)
	        {
	        	$from = date('Y').'-'.date('m').'-01';
	        	$to = date('Y').'-'.date('m').'-31';
	        	// $from = '2019-12-01';
	        	// $to = '2019-12-31';
	        	if(date('Y-m-d') == date('Y-m-t'))
	        	{
		        	$_customer = Tbl_customer::where("shop_id", $valueshop->shop_id)->where("archived",0)->get();
		        	foreach ($_customer as $key => $value) 
		        	{
		        		$sales = Tbl_customer_invoice::where("inv_shop_id", $valueshop->shop_id)
		        									 ->where("inv_customer_id", $value->customer_id)
		        									 ->whereBetween("inv_date",[$from, $to])->count();
		        		if($value->customer_category == 'new-client' || $value->customer_category == 'regular' || $value->customer_category == 'former')
		        		{
			        		if($sales >= 1) // TO REGULAR
			        		{
			        			$get_old = $value->customer_category_history ? unserialize($value->customer_category_history) : array();
			        			$ctr = count($get_old) + 1;
			        			if($value->customer_category != 'regular')
			        			{
			        				$get_old[$ctr]['category'] = 'regular';
			        				$get_old[$ctr]['date'] = date('Y-m-d');
			        				Tbl_customer::where("customer_id", $value->customer_id)->update(['customer_category' => 'regular', 'customer_category_history' => serialize($get_old)]);
			        			}
			        		}
			        		if($sales <= 0) // TO FORMER
			        		{
			        			$get_old = $value->customer_category_history ? unserialize($value->customer_category_history) : array();
			        			$ctr = count($get_old) + 1;
			        			if($value->customer_category != 'former')
			        			{
				        			$get_old[$ctr]['category'] = 'former';
				        			$get_old[$ctr]['date'] = date('Y-m-d');
				        			Tbl_customer::where("customer_id", $value->customer_id)->update(['customer_category' => 'former', 'customer_category_history' => serialize($get_old)]);
				        		}
			        		}
		        		}
		        	}
	        	}
	        	return 'success';
	        }
	    }
	}
}