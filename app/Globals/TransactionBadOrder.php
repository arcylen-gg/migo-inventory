<?php
namespace App\Globals;

use App\Models\Tbl_purchase_order;
use Carbon\Carbon;
use DB;

/**
 * 
 *
 * @author Arcylen Garcia Gutierrez
 */

class TransactionBadOrder
{
	public static function countTransaction($shop_id)
	{
		// return Tbl_purchase_order::where('po_shop_id',$shop_id)->where('po_is_billed',0)->count();
	}
}