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
use App\Models\Tbl_mlm_encashment_process;
use App\Models\Tbl_membership_code;
use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;
use App\Models\Tbl_mlm_gc;
use Schema;
use Session;
use DB;
use Carbon\Carbon;
use App\Globals\Mlm_gc;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_slot_log;

class Mlm_gc
{   
	public static function insert_gc($data)
	{
		// $insert['mlm_gc_tag'] = 
		// $insert['mlm_gc_code'] = 
		// $insert['mlm_gc_amount'] = 
		// $insert['mlm_gc_member'] = 
		// $insert['mlm_gc_slot'] = /
		// $insert['mlm_gc_date'] = Carbon::now();
		// $insert['mlm_gc_used'] = 
		// $insert['mlm_gc_used_date'] = 
		//$id = Tbl_mlm_gc::insertGetId($insert);
	}
	public static function slot_gc($slot_id)
	{
		$code = Tbl_membership_code::where('slot_id', $slot_id)->first();
		$slot = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
		if(isset($code->membership_package_id))
		{
			$package = Tbl_membership_package::where('membership_package_id', $code->membership_package_id)->first();
			if(isset($package->membership_package_is_gc))
			{
				if($package->membership_package_is_gc == 1)
				{
					$insert['mlm_gc_tag'] = 'REG';
					$insert['mlm_gc_code'] = Mlm_gc::random_code_generator(8, $slot_id, $insert['mlm_gc_tag']);
					$insert['mlm_gc_amount'] = $package->membership_package_gc_amount;
					$insert['mlm_gc_member'] = $slot->slot_owner;
					$insert['mlm_gc_slot'] = $slot->slot_id;
					$insert['mlm_gc_date'] = Carbon::now();
					Tbl_mlm_gc::insert($insert);
				}
			}
		}
	}
	public static function return_gc($slot_id, $gc_code, $return)
	{
		$slot_info = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
		$insert['mlm_gc_tag'] = 'RET';
		$insert['mlm_gc_code'] = Mlm_gc::random_code_generator(8, $slot_id, 'RET');
		$insert['mlm_gc_amount'] = $return;
		$insert['mlm_gc_member'] = $slot_info->slot_owner;
		$insert['mlm_gc_slot'] = $slot_info->slot_id;
		$insert['mlm_gc_date'] = Carbon::now();
		Tbl_mlm_gc::insert($insert);
	}
	public static function random_code_generator($word_limit, $slot_id, $code)
	{
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = $code.$slot_id;
        for ($i = 0; $i < $word_limit; $i++) 
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
	}
}