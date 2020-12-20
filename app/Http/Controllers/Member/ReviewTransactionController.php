<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_customer_invoice_line;
use App\Models\Tbl_receive_payment;
use App\Models\Tbl_receive_payment_line;
use App\Models\Tbl_journal_entry;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_item;
use App\Models\Tbl_warehouse;
use App\Models\Tbl_bill;

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

class ReviewTransactionController extends Member
{
	public function getUpdateCustomerCategory(Request $request)
	{
		AdminNotification::update_customer_category();
	}
	public function getUpdateJournalEntry(Request $request)
	{
		$_all = Tbl_journal_entry::where("je_shop_id", $this->user_info->shop_id)
								 ->whereIn("je_reference_module",["bill","invoice","sales-receipt"])
								 ->get();
		if($request->from && $request->to)
		{
			$_all = Tbl_journal_entry::where("je_shop_id", $this->user_info->shop_id)
									 ->whereIn("je_reference_module",["bill","invoice","sales-receipt"])
									 ->whereBetween("je_id", [$request->from, $request->to])
									 ->get();
		}
		foreach ($_all as $key => $value) 
		{
			if($value->je_reference_module == 'invoice' || $value->je_reference_module == 'sales-receipt')
			{
				$get_inv_data = Tbl_customer_invoice::where("inv_id", $value->je_reference_id)->first();

				$insert_item = null;
				$txn_name = 'invoice';
				$tax = 0;
				$entry_date = $value->je_entry_date;
				if($get_inv_data)
				{
					$txn_name = $get_inv_data->is_sales_receipt == 1 ? 'sales-receipt' : 'invoice';  
					$total = $get_inv_data->inv_subtotal_price;
					$customer_id = $get_inv_data->inv_customer_id;
					$ewt = $get_inv_data->ewt * $total;
					$entry_date = $get_inv_data->inv_date;
					$get_inv_item = Tbl_customer_invoice_line::where("invline_inv_id", $value->je_reference_id);
			        $discount = $get_inv_data->inv_discount_value;
			        if($get_inv_data->inv_discount_type == 'percent') $discount = ((($total + $tax) - $ewt) * (($get_inv_data->inv_discount_value) / 100));
					
					$_getitem = $get_inv_item->get();
					foreach ($_getitem as $keyitem => $valueitem) 
					{
						$insert_item[$keyitem]['item_id'] 		   	= $valueitem->invline_item_id;
						$insert_item[$keyitem]['item_servicedate'] 	= $valueitem->invline_service_date;
						$insert_item[$keyitem]['item_description'] 	= $valueitem->invline_description;
						$insert_item[$keyitem]['item_sub_warehouse']= $valueitem->invline_sub_wh_id;
						$insert_item[$keyitem]['item_um'] 			= $valueitem->invline_um;
						$insert_item[$keyitem]['item_qty'] 			= $valueitem->invline_qty;
						$insert_item[$keyitem]['item_rate'] 		= $valueitem->invline_rate;
						$insert_item[$keyitem]['item_discount'] 	= $valueitem->invline_discount;
						$insert_item[$keyitem]['item_discount_type']= $valueitem->invline_discount_type;
						$insert_item[$keyitem]['item_amount'] 		= $valueitem->invline_amount;
						$insert_item[$keyitem]['item_taxable']      = $valueitem->taxable;
						$tax += $valueitem->taxable == 1 ? $valueitem->invline_amount : 0;
					}
					$tax = $tax * 0.12;
				}
				/* Transaction Journal */
		        $entry["reference_module"]  = $value->je_reference_module;
		        $entry["reference_id"]      = $value->je_reference_id;
		        $entry["name_id"]           = $customer_id;
		        $entry["total"]             = $total;
		        $entry["vatable"]           = $tax;
		        $entry["txn_date"]          = $entry_date;
		        $entry["discount"]          = $discount;
		        $entry["ewt"]               = $ewt;
		        // dd($entry)
	        	AccountingTransaction::entry_data($entry, $insert_item, true);
			}
		}
		return "success";
	}
	public function getIndex()
	{
		$check_inv = Tbl_customer_invoice::where("inv_shop_id", $this->user_info->shop_id)
										 ->where("inv_is_paid",0)
										 ->where("is_sales_receipt",0)
										 ->get();
		$ret = null;
		foreach ($check_inv as $key => $value) 
		{
			// if($value->inv_payment_applied > $value->inv_overall_price)
			// {
			// 	$up['inv_payment_applied'] = $value->inv_overall_price;
			// 	$up['inv_is_paid'] = 1;
			// 	Tbl_customer_invoice::where("inv_id", $value->inv_id)->update($up);

			// 	$get_rp = Tbl_receive_payment_line::where("rpline_reference_name","invoice")
			// 								 ->where("rpline_reference_id", $value->inv_id)
			// 								 ->groupBy("rpline_id")
			// 								 ->get();
			// 	if(count($get_rp) > 1)
			// 	{
			// 		$rp_applied = 0;
			// 		foreach ($get_rp as $keyrp => $valuerp) 
			// 		{
			// 			$rp_applied += $valuerp->rpline_amount;
			// 			if($rp_applied > $value->inv_overall_price)
			// 			{
			// 				$up_rp['rp_total_amount'] = 0;
			// 				Tbl_receive_payment::where("rp_id", $valuerp->rpline_rp_id)->update($up_rp);
			// 				$up_rpline['rpline_amount'] = 0;
			// 				Tbl_receive_payment_line::where("rpline_id", $valuerp->rpline_id)->update($up_rpline);

			// 				$get_je = Tbl_journal_entry::line()
			// 										   ->where("je_reference_module", "receive-payment")
			// 										   ->where("je_reference_id", $valuerp->rpline_rp_id)
			// 										   ->groupBy("jline_id")
			// 										   ->get();
			// 				foreach ($get_je as $keyje => $valueje) 
			// 				{
			// 					$up_je['jline_amount'] = 0;
			// 					Tbl_journal_entry_line::where("jline_id", $valueje->jline_id)->update($up_je);

			// 					$ret .= $value->transaction_refnum.", ";
			// 				}						
			// 			}
			// 		}
			// 	}
			// }

			// $get_rp1 = Tbl_receive_payment_line::where("rpline_reference_name","invoice")
			// 											 ->where("rpline_reference_id", $value->inv_id)
			// 											 ->groupBy("rpline_id")
			// 											 ->get();
			// if(count($get_rp1) == 1)
			// {
			// 	$rp_applied = 0;
			// 	foreach ($get_rp1 as $keyrp1 => $valuerp1) 
			// 	{
			// 		$rp_applied += $valuerp1->rpline_amount;
			// 		if($rp_applied > $value->inv_overall_price)
			// 		{

			// 			$up_rp['rp_total_amount'] = $value->inv_overall_price;
			// 			Tbl_receive_payment::where("rp_id", $valuerp1->rpline_rp_id)->update($up_rp);
			// 			$up_rpline['rpline_amount'] = $value->inv_overall_price;
			// 			Tbl_receive_payment_line::where("rpline_id", $valuerp1->rpline_id)->update($up_rpline);

			// 			$get_je = Tbl_journal_entry::line()
			// 									   ->where("je_reference_module", "receive-payment")
			// 									   ->where("je_reference_id", $valuerp1->rpline_rp_id)
			// 									   ->groupBy("jline_id")
			// 									   ->get();
			// 			foreach ($get_je as $keyje => $valueje) 
			// 			{
			// 				$up_je['jline_amount'] = $value->inv_overall_price;
			// 				Tbl_journal_entry_line::where("jline_id", $valueje->jline_id)->update($up_je);

			// 				$ret .= $value->transaction_refnum.", ";
			// 			}	
			// 		}
			// 	}
			// }
			// if($value->inv_is_paid == 1 && $value->inv_overall_price > $value->inv_payment_applied)
			// {
			// 	$up['inv_is_paid'] = 0;
			// 	Tbl_customer_invoice::where("inv_id", $value->inv_id)->update($up);				
			// }
			if($value->inv_overall_price == 0)
			{
				$up['inv_is_paid'] = 1;
				$up['inv_payment_applied'] = 0;
				Tbl_customer_invoice::where("inv_id", $value->inv_id)->update($up);				
			}
		}

		$check_ri = Tbl_bill::where("bill_shop_id", $this->user_info->shop_id)
							->where("bill_is_paid",0)
							->get();
		foreach ($check_ri as $key => $value) 
		{
			if($value->bill_applied_payment >= $value->bill_total_amount)
			{
				$upbill['bill_applied_payment'] = $value->bill_total_amount;
				$upbill['bill_is_paid'] = 1;
				Tbl_bill::where("bill_id", $value->bill_id)->update($upbill);
			}
			if($value->bill_total_amount == 0)
			{
				$upbill['bill_is_paid'] = 1;
				$upbill['bill_applied_payment'] = 0;
				Tbl_bill::where("bill_id", $value->bill_id)->update($upbill);				
			}
		}


		// $ret .= $this->special_inv();
		return "Success : ".$ret;
	}
	public function special_inv()
	{
		$inv = Tbl_customer_invoice::where("inv_shop_id", $this->user_info->shop_id)->where("inv_is_paid",1)->where("inv_id",353)->first();
		$ret = null;
		if($inv)
		{
			if($inv->inv_overall_price != $inv->inv_overall_price)
			{
				$up['inv_payment_applied'] = $inv->inv_overall_price;
				$up['inv_is_paid'] = 1;
				Tbl_customer_invoice::where("inv_id", $inv->inv_id)->update($up);

				$get_rp = Tbl_receive_payment_line::where("rpline_reference_name","invoice")
												 ->where("rpline_reference_id", $inv->inv_id)
												 ->groupBy("rpline_id")
												 ->get();
				if(count($get_rp) > 0)
				{
					$rp_applied = 0;
					foreach ($get_rp as $keyrp => $valuerp) 
					{
						$up_rp['rp_total_amount'] = $inv->inv_overall_price;
						Tbl_receive_payment::where("rp_id", $valuerp->rpline_rp_id)->update($up_rp);
						$up_rpline['rpline_amount'] = $inv->inv_overall_price;
						Tbl_receive_payment_line::where("rpline_id", $valuerp->rpline_id)->update($up_rpline);
						$get_je = Tbl_journal_entry::line()
												   ->where("je_reference_module", "receive-payment")
												   ->where("je_reference_id", $valuerp->rpline_rp_id)
												   ->groupBy("jline_id")
												   ->get();
						foreach ($get_je as $keyje => $valueje) 
						{
							$up_je['jline_amount'] = $inv->inv_overall_price;
							Tbl_journal_entry_line::where("jline_id", $valueje->jline_id)->update($up_je);

							$ret .= $inv->transaction_refnum.", ";
						}		
					}
				}
			}
		}
		return $ret;
	}
	public function getUpdateInventory()
	{
		$sum = 0;
		$get_item = Tbl_item::warehouse()->where("shop_id", $this->user_info->shop_id)->where("tbl_warehouse.archived",0)->get();
		$sum += $this->divide_update($get_item);
		return "success : ". $sum; 
	}
	public function divide_update($get_item)
	{
		$_item = [];
		$sum = 0;
		foreach ($get_item as $key => $value) 
		{
			$new = Warehouse2::get_old_item_qty($value->warehouse_id, $value->item_id);
			$actual = Warehouse2::get_item_qty($value->warehouse_id, $value->item_id);
			if($actual != $new)
			{
				$diff_qty = $new - $actual;

				$_item[0]['item_um'] = null;
				$_item[0]['item_id'] = $value->item_id;
		        $_item[0]['item_qty'] = $diff_qty;
		        $_item[0]['item_description'] = 'Manual adjustment of inventory';
		        $_item[0]['item_rate'] = $value->item_cost;
		        $_item[0]['item_sub_warehouse'] = null;
			    $_item[0]['bin_location'] = 0;

				if($diff_qty > 0)
				{
					AccountingTransaction::refill_inventory($this->user_info->shop_id, $value->warehouse_id, $_item, 'manual_adjust_inventory', $value->item_id, 'Manual Adjust Inventory ');
				}
				else
				{
					AccountingTransaction::consume_inventory($this->user_info->shop_id, $value->warehouse_id, $_item, 'manual_adjust_inventory', $value->item_id, 'Manual Adjust Inventory ');
				}
				$sum += $diff_qty;
			}
		}
		return $sum;
	}
	public function getUpdateSalesDashboard(Request $request)
	{
		$_rp = Tbl_receive_payment::rpline()
								  ->where("rp_shop_id", $this->user_info->shop_id)
								  // ->whereBetween("rpline_id",[$request->from,$request->to])
								  ->get();
		$ctr = 0;
		foreach ($_rp as $key => $value) 
		{
			$inv = Tbl_customer_invoice::where("inv_id",$value->rpline_reference_id)->where("inv_is_paid",1)->first();
			if($inv)
			{
				if($inv->inv_payment_applied != $inv->inv_overall_price)
				{
					// if(date("m/Y",strtotime($value->rp_date)) != date("m/Y",strtotime($inv_date)))
					// {
					$update['inv_payment_applied'] = $inv->inv_overall_price;
					Tbl_customer_invoice::where("inv_id",$value->rpline_reference_id)->update($update);
					$ctr++;
				}
			}
			// }
		}
		$rpctr = 0;

		$_inv = Tbl_customer_invoice::where("inv_shop_id", $this->user_info->shop_id)->where("is_sales_receipt",0)->get();
		foreach ($_inv as $key => $value) 
		{
			$get_rp = Tbl_receive_payment::rpline()->where("rpline_reference_id", $value->inv_id)->first();
			if($get_rp)
			{
				$update_rp['rp_date'] = $get_rp->rp_date;
				$uprp = Tbl_receive_payment::rpline()->where("rpline_reference_id", $value->inv_id)->get();
				foreach ($uprp as $keyup => $valueup) 
				{
					Tbl_receive_payment::where("rp_id", $valueup->rpline_rp_id)->update($update_rp);
				}
			}
		}


		return "Success FROM : ".$request->from." TO : ".$request->to." SI-count: ".$ctr." SI-count: ".$ctr;
	}
	public function getArchiveItemByCategory(Request $request)
	{
		Tbl_item::where('shop_id', $this->user_info->shop_id)->where('item_category_id', 619)->where('archived', 0)->update(['archived' => 1]);
		return "success";
	}
}