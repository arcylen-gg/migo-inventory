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
use App\Models\Tbl_mlm_slot_wallet_log_refill_settings;

use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;
use Carbon\Carbon;

use App\Globals\Mlm_compute;
use App\Globals\Mlm_slot_log;
use App\Globals\Item;
class Mlm_pre
{   
	public static function pre_req($shop_id)
	{
		# code...
		$count = Tbl_mlm_slot_wallet_log_refill_settings::where('shop_id', $shop_id)->count();
		if($count == 0)
		{
			$insert['wallet_log_refill_settings_processings_fee'] = 50;
			$insert['wallet_log_refill_settings_processings_max_request'] = 5;
			$insert['shop_id'] = $shop_id;
			Tbl_mlm_slot_wallet_log_refill_settings::insert($insert);
		}
	}
}