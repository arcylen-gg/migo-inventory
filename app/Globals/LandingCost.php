<?php
namespace App\Globals;
use App\Models\Tbl_item_default_landing_cost;
use App\Models\Tbl_item_landing_cost;

use Carbon\Carbon;
use DB;

/**
 * LANDING COST
 *
 * @author Arcylen Gutierrez
 */

class LandingCost
{
	public static function get($shop_id = '')
	{
		return Tbl_item_default_landing_cost::where("shop_id", $shop_id)->get();
	}
	public static function insert($shop_id, $insert = array())
	{
		Tbl_item_default_landing_cost::where("shop_id", $shop_id)->delete();
		Tbl_item_default_landing_cost::insert($insert);
	}
	public static function insert_cost_item($item_id, $shop_id, $insert = array())
	{
		$date = Carbon::now();
		if(count($insert) > 0)
		{
			Tbl_item_landing_cost::where('landing_cost_shop_id',$shop_id)->where("landing_cost_item_id", $item_id)->delete();
			foreach ($insert as $key => $value) 
			{
				$insert[$key]['landing_cost_item_id'] = $item_id;
				$insert[$key]['landing_cost_created'] = $date;
			}
			Tbl_item_landing_cost::insert($insert);
			session(['landing_cost' => null]);
		}
	}
	public static function get_cost($shop_id, $item_id = 0)
	{
		$return = null;
		if($item_id != 0)
		{
			$return = Tbl_item_landing_cost::where("landing_cost_shop_id", $shop_id)->where("landing_cost_item_id", $item_id)->get()->toArray();

		}
		return $return;
			
	}
	public static function get_cost_amount($shop_id, $item_id = 0)
	{
		if($item_id != 0)
		{
			return Tbl_item_landing_cost::where("landing_cost_shop_id", $shop_id)->where("landing_cost_item_id", $item_id)->sum("landing_cost_amount");
		}
	}
}