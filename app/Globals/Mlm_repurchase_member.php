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
use App\Globals\Mlm_repurchase_member;
class Mlm_repurchase_member
{   
	// use App\Globals\Mlm_repurchase_member;
	// get all items 				Mlm_repurchase_member::get_all_items($shop_id);
	// get item 					Mlm_repurchase_member::get_item($item_id);
	// add item to cart 			Mlm_repurchase_member::add_to_cart($item_id, $quantity, $membership_id = null);
	// get item in cart             Mlm_repurchase_member::get_item_cart();
	// update quantity in cart      Mlm_repurchase_member::add_to_cart($item_id, $quantity, $membership_id = null);
	// delete product in cart 		Mlm_repurchase_member::remove_from_cart($item_id);
	// clear all product in cart 	Mlm_repurchase_member::clear_all_cart();
	public static function get_all_items($shop_id, $slot_id = null)
	{
		if($slot_id == null)
		{
			$item = Tbl_item::where("shop_id", $shop_id)->where("archived", 0)
			->get();
		}
		else
		{
			$slot = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
			$item = Tbl_item::where("shop_id", $shop_id)->where("archived", 0)
			->where('item_type_id', 1)
			->get()->keyBy('item_id');

			foreach($item  as $key => $value)
			{
				$item[$key]->z_price = intval($value->item_price);
				$item[$key]->z_discount = Item::get_discount_only($value->item_id, $slot->slot_membership);
				$item[$key]->z_total = $item[$key]->z_price - $item[$key]->z_discount;
			}
		}

		return $item;
	}
	public static function get_item($item_id, $slot_id = null)
	{
		if($slot_id == null)
		{
			$item = Tbl_item::where("tbl_item.item_id", $item_id)->where("archived", 0)
			->get();
		}
		else
		{

			$slot = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
			$item = Tbl_item::where("tbl_item.item_id", $item_id)->where("archived", 0)
			->get();

			foreach($item  as $key => $value)
			{
				$item[$key]->z_price = intval($value->item_price);
				$item[$key]->z_discount = Item::get_discount_only($value->item_id, $slot->slot_membership);
				$item[$key]->z_total = $item[$key]->z_price - $item[$key]->z_discount;
			}

		}
		

		return $item->first();
	}
	public static function add_to_cart($item_id, $quantity, $membership_id = null)
	{
		$get_session = Session::get("mlm_repurchase"); 
		$item_info = Mlm_repurchase_member::get_item($item_id);
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
			return Session::put('mlm_repurchase', $s);
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
			return Session::put('mlm_repurchase', $s);
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
	public static function get_item_cart()
	{
		$get_session = Session::get("mlm_repurchase"); 
        return $get_session;
	}
	public static function clear_all_cart()
	{
		Session::forget("mlm_repurchase"); 
	}
}