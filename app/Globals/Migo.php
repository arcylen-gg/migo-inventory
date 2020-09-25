<?php
namespace App\Globals;

use App\Models\Tbl_purchase_order;
use App\Models\Tbl_bill;
use App\Models\Tbl_customer_invoice;
use App\Models\Tbl_acctg_transaction_list;
use App\Models\Tbl_acctg_transaction;
use App\Models\Tbl_journal_entry_line;
use App\Models\Tbl_customer;

use App\Globals\ReceivePayment;
use App\Globals\TransactionSalesInvoice;
use Carbon\Carbon;

use Validator;
use DB;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class Migo
{
	public static function dashboard($shop_id, $mo = '', $yr = '')
	{
		$return['r_po'] = 0;
		$return['r_ap'] = 0;
		$return['r_ri'] = 0;
		$return['r_pb'] = 0;

		$return['f_rts'] = 0;
		$return['f_pt']  = 0;
		$return['f_di']  = 0;
		$return['f_ar']  = 0;
		$return['f_rp']  = 0;


        $period         = "custom";
        $date['start'] = date('Y-m-d',strtotime($mo."/01/".$yr));
        $date['end'] = date('Y-m-t',strtotime($mo."/01/".$yr));
        // dd(date('m-t-Y',strtotime('09/01/2019')));
        // dd($date);
		$mfrom = $date['start'];
		$mto = $date['end'];
        $year = false;
        if(!is_numeric($mo))
        {
	        $date['start'] = "1/1/".$yr;
	        $date['end'] = "12/31/".$yr; 
	        $year = true;       	
	        $mfrom                   = Report::checkDatePeriod($period, $date)['start_date'];
	        $mto                     = Report::checkDatePeriod($period, $date)['end_date'];
        }
        // dd($mfrom." - ". $mto);
		// $po = Tbl_purchase_order::selectRaw("(poline_orig_qty - poline_qty) * poline_rate as po_item")->purchase_item()->where("item_type_id","!=",6)->where("po_shop_id", $shop_id)->get()->toArray();
		// $return['r_po'] = collect($po)->sum("po_item");
		$return['r_po'] = Tbl_purchase_order::purchase_item()->where("item_type_id","!=",6)->where("po_shop_id", $shop_id)->where("po_is_billed",0)->whereBetween("tbl_purchase_order.date_created",[$mfrom, $mto])->sum("poline_amount");

		$return['r_ap'] = Tbl_bill::item_line()->where("bill_shop_id", $shop_id)->where("item_type_id","!=",6)->where("bill_is_paid",0)->whereBetween("date_created",[$mfrom, $mto])->sum("itemline_amount");
		
		$ri =  Tbl_purchase_order::selectRaw("(poline_received_qty * poline_rate) as ri_item")->purchase_item()->where("item_type_id","!=",6)->where("po_shop_id", $shop_id)->whereBetween("tbl_purchase_order.date_created",[$mfrom, $mto])->get()->toArray();
		$return['r_ri'] = collect($ri)->sum("ri_item");  

		$return['r_pb'] = Tbl_bill::item_line()->where("bill_shop_id", $shop_id)->where("item_type_id","!=",6)->where("bill_is_paid",1)->whereBetween("date_created",[$mfrom, $mto])->sum("itemline_amount");


		$return['r_po'] = TransactionPurchaseOrder::getPo($shop_id, $mfrom, $mto, $year);
		$return['r_ap'] = TransactionPurchaseOrder::getAp($shop_id, $mfrom, $mto, $year);
		$return['r_ri'] = TransactionPurchaseOrder::getRi($shop_id, $mfrom, $mto, $year);
		$return['r_pb'] = TransactionPurchaseOrder::getPb($shop_id, $mfrom, $mto, $year);

		$return['f_rts'] = TransactionSalesInvoice::getSales($shop_id, $mfrom, $mto, $year);		
		$return['f_pt'] = TransactionSalesInvoice::getPaid($shop_id, $mfrom, $mto, $year);
		$return['f_ar'] = TransactionSalesInvoice::getAr($shop_id, $mfrom, $mto, $year);
		$return['f_di'] = $return['f_rts'];
		$return['f_cm']  = TransactionSalesInvoice::getCm($shop_id, $mfrom, $mto, $year);

		return $return;
	}
	public static function get_top_customer($shop_id, $from = '', $to = '')
	{
		$all = Tbl_customer::where("shop_id", $shop_id)->where("archived",0)->get();
		$_customer = array();
		foreach ($all as $key => $value) 
		{
			$_customer[$key] = $value;
			$_customer[$key]->total_sales = Self::get_sales_per_customer($shop_id, $value->customer_id, $from, $to);
		}
		usort($_customer, function($a, $b) {
		    if($a->total_sales==$b->total_sales) return 0;
		    return $a->total_sales < $b->total_sales?1:-1;
		});
		return $_customer;
	}
	public static function get_sales_per_customer($shop_id, $customer_id, $from = '', $to = '')
	{
		return Tbl_customer_invoice::invoice_item()->where("inv_customer_id",$customer_id)->where("inv_shop_id", $shop_id)->where("item_type_id","!=",6)->whereBetween("tbl_customer_invoice.date_created",[$from, $to])->sum("invline_amount");
	}
	public static function getSales($shop_id, $from, $to)
	{
		// $_get = Tbl_journal_entry_line::account()->journal()
  //                               ->selectRaw("*")
  //                               ->amount()
  //                               ->where("je_shop_id", $shop_id)
  //                               ->whereIn("chart_type_name", ['Income', 'Other Income'])
  //                               ->whereRaw("DATE(je_entry_date) >= '$from'")
  //                               ->whereRaw("DATE(je_entry_date) <= '$to'")
  //                               // ->whereBetween("je_entry_date",[$from, $to])
  //                               ->groupBy(DB::raw("DATE(je_entry_date)"))
  //                               ->get();
  //       $val = 0;
  //       foreach ($_get as $key => $value) 
  //       {
  //       	$val += $value->amount;
  //       }
        $val = Tbl_customer_invoice::invoice_item()->where("inv_shop_id", $shop_id)->where("item_type_id","!=",6)->whereBetween("tbl_customer_invoice.inv_date",[$from, $to])->sum("invline_amount");
        return $val;
	}
	public static function get_ar($shop_id, $from = null, $to = null)
	{
		$_get_all_ar = Tbl_customer_invoice::customer()
											->selectRaw("*, inv_overall_price - inv_payment_applied as inv_balance")
											->where("inv_shop_id", $shop_id)
											->where("is_sales_receipt",0)
											->where("inv_is_paid", 0);
		if($from && $to)
		{
			$_get_all_ar = $_get_all_ar->whereBetween('inv_date',[$from, $to]);
		}
		$_get_all_ar = $_get_all_ar->orderBy("inv_date","desc")
								   ->get();
		$return = array();
		foreach ($_get_all_ar as $key => $value) 
		{
			$return[$key] = $value;
			// $return[$key]->customer_balance = ReceivePayment::getBalance($shop_id, $value->inv_id, $value->inv_overall_price);
		}

		return $return;
	}
	public static function get_ap($shop_id , $from = null, $to = null)
	{
		$_get_all_ap = Tbl_bill::vendor()
								->where("bill_shop_id", $shop_id)
								->where("bill_is_paid", 0);
		if($from && $to)
		{
			$_get_all_ap = $_get_all_ap->whereBetween('bill_date',[$from, $to]);
		}

		$_get_all_ap = $_get_all_ap->orderBy("bill_date","desc")
								   ->get();
		$return = array();
		foreach ($_get_all_ap as $key => $value) 
		{
			$return[$key] = $value;
			$return[$key]->vendor_payable = TransactionPayBills::getBalance($shop_id, $value->bill_id, $value->bill_total_amount);
		}

		return $return;
	}
}