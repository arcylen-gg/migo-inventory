<?php
namespace App\Globals;

use App\Models\Tbl_customer_estimate;
use App\Models\Tbl_requisition_slip;
use App\Models\Tbl_requisition_slip_item;
use Carbon\Carbon;
use DB;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */
 
class TransactionPurchaseRequisition
{
	public static function countTransaction($shop_id)
	{
		return Tbl_customer_estimate::where('est_shop_id',$shop_id)->where("est_status","accepted")->count();
	}
	public static function getPR($shop_id)
	{
		return Tbl_requisition_slip_item::PRInfo('shop_id',$shop_id)->where("requisition_slip_status","closed")->orderBy('rs_vendor_id', 'ASC')->get();
	}
	public static function postInsert()
	{
		
	}
}