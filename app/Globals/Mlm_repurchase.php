<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_shop;
use App\Models\Tbl_customer;
use App\Models\Tbl_item;

use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;
use Carbon\Carbon;

use App\Globals\Mlm_compute;
use App\Globals\Mlm_slot_log;
use App\Globals\Item;
class Mlm_repurchase
{   
	public static function add_to_cart($item_info, $quantity, $membership_id = null)
	{
		$get_session = Session::get("mlm_repurchase"); 
		

		if($get_session != null)
		{
			$used = 0;
			foreach($get_session as $key => $value)
			{
				if($key == $item_info->item_id)
				{

					$s[$item_info->item_id]['item_id'] = $item_info->item_id;
					$s[$item_info->item_id]['quantity'] = $value['quantity'] + $quantity;
					$s[$item_info->item_id]['item_price_single'] = intval($item_info->item_price);
					$s[$item_info->item_id]['item_discount'] = intval(Item::get_discount_only($item_info->item_id, $membership_id));
					$s[$item_info->item_id]['item_discount_percentage'] = ($s[$item_info->item_id]['item_price_single'] - $s[$item_info->item_id]['item_discount'])/$s[$item_info->item_id]['item_price_single'];
					$s[$item_info->item_id]['item_price_subtotal'] = ($s[$item_info->item_id]['item_price_single'] - $s[$item_info->item_id]['item_discount']) * $s[$item_info->item_id]['quantity'];
					$s[$item_info->item_id]['item_info'] = $item_info;
					$used = 1;
				}
				else
				{
					$s[$key] = $value;
				}
			}
			if($used == 0)
			{
				$s[$item_info->item_id]['item_id'] = $item_info->item_id;
				$s[$item_info->item_id]['quantity'] = $quantity;
				$s[$item_info->item_id]['item_price_single'] = intval($item_info->item_price);
				$s[$item_info->item_id]['item_discount'] = intval(Item::get_discount_only($item_info->item_id, $membership_id));
				$s[$item_info->item_id]['item_discount_percentage'] = ($s[$item_info->item_id]['item_price_single'] - $s[$item_info->item_id]['item_discount'])/$s[$item_info->item_id]['item_price_single'];
				$s[$item_info->item_id]['item_price_subtotal'] = ($s[$item_info->item_id]['item_price_single'] - $s[$item_info->item_id]['item_discount']) * $s[$item_info->item_id]['quantity'];
				$s[$item_info->item_id]['item_info'] = $item_info;
			}
			Session::put('mlm_repurchase', $s);
		}
		else
		{
			$s[$item_info->item_id]['item_id'] = $item_info->item_id;
			$s[$item_info->item_id]['quantity'] = $quantity;
			$s[$item_info->item_id]['item_price_single'] = intval($item_info->item_price);
			$s[$item_info->item_id]['item_discount'] = intval(Item::get_discount_only($item_info->item_id, $membership_id));
			$s[$item_info->item_id]['item_discount_percentage'] = ($s[$item_info->item_id]['item_price_single'] - $s[$item_info->item_id]['item_discount'])/$s[$item_info->item_id]['item_price_single'];
			$s[$item_info->item_id]['item_price_subtotal'] = ($s[$item_info->item_id]['item_price_single'] - $s[$item_info->item_id]['item_discount']) * $s[$item_info->item_id]['quantity'];
			$s[$item_info->item_id]['item_info'] = $item_info;
			Session::put('mlm_repurchase', $s);
		}
		$get_session = Session::get("mlm_repurchase"); 
        return $get_session;
	}
	public static function remove_from_cart($item_id)
	{
		$get_session = Session::get("mlm_repurchase"); 
		

		if($get_session != null)
		{
			$s = null;
			foreach($get_session as $key => $value)
			{
				if($key != $item_id)
				{
					$s[$key] = $value;
				}
			}
			Session::put("mlm_repurchase", $s); 
		}
		$get_session = Session::get("mlm_repurchase"); 
        return $get_session;
	}
}