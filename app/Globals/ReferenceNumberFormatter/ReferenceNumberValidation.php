<?php
namespace App\Globals\ReferenceNumberFormatter;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_credit_memo;
use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_customer_wis;
use App\Models\Tbl_warehouse_issuance_report;
use App\Models\Tbl_warehouse_receiving_report;
use App\Models\Tbl_requisition_slip;
use App\Models\Tbl_purchase_order;
use App\Models\Tbl_receive_inventory;
use App\Models\Tbl_bill;
use App\Models\Tbl_pay_bill;
use App\Models\Tbl_write_check;
use App\Models\Tbl_debit_memo;
use App\Models\Tbl_inventory_adjustment;
use App\Models\Tbl_receive_payment;


class ReferenceNumberValidation
{
	public static function check_transaction_ref_number($shop_id, $transaction_refnum, $transaction_type)
	{
		$return = null;
		$get = null;
		switch ($transaction_type) 
		{
			case 'sales_invoice':
				$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->where('is_sales_receipt',0)->first();
			break;

			case 'sales_receipt':
				$get = Tbl_customer_invoice::where('inv_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->where('is_sales_receipt',1)->first();
			break;

			case 'credit_memo':
				$get = Tbl_credit_memo::where('cm_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'estimate_quotation':
				$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->where('is_sales_order',0)->first();
			break;

			case 'sales_order':
				$get = Tbl_customer_estimate::where('est_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->where('is_sales_order',1)->first();
			break;

			case 'warehouse_issuance_slip':
				$get = Tbl_customer_wis::where('cust_wis_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'warehouse_transfer':
				$get = Tbl_warehouse_issuance_report::where('wis_shop_id', $shop_id)->where('wis_number', $transaction_refnum)->first();
			break;

			case 'receiving_report':
				$get = Tbl_warehouse_receiving_report::where('rr_shop_id', $shop_id)->where('rr_number', $transaction_refnum)->first();
			break;

			case 'purchase_requisition':
				$get = Tbl_requisition_slip::where('shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'purchase_order':
				$get = Tbl_purchase_order::where('po_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'received_inventory':
				$get = Tbl_receive_inventory::where('ri_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'enter_bills':
				$get = Tbl_bill::where('bill_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'pay_bill':
				$get = Tbl_pay_bill::where('paybill_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'write_check':
				$get = Tbl_write_check::where('wc_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'debit_memo':
				$get = Tbl_debit_memo::where('db_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'inventory_adjustment':
				$get = Tbl_inventory_adjustment::where('adj_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

			case 'received_payment':
				$get = Tbl_receive_payment::where('rp_shop_id', $shop_id)->where('transaction_refnum', $transaction_refnum)->first();
			break;

		}

		if($get)
		{
			$return = "Duplicate transaction number <br>";
		}

		return $return;
	}
}