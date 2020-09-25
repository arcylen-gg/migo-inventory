<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_binary_pairing;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_indirect_setting;

use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;
use Carbon\Carbon;

use App\Globals\Mlm_compute;
use App\Globals\Mlm_slot_log;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_complan_manager_cd;

class Mlm_complan_manager_cd
{   
	public static function enter_cd($slot_info)
	{	
		// dd($slot_info);

		// get price of membership
		$mem_price = $slot_info->membership_price;

		// set income to negative
		$log = "Congratulations! Your Comission Deductable Slot " . $slot_info->slot_no . " has been created. " ;
        $arry_log['wallet_log_slot'] = $slot_info->slot_id;
        $arry_log['shop_id'] = $slot_info->shop_id;
        $arry_log['wallet_log_slot_sponsor'] = $slot_info->slot_id;
        $arry_log['wallet_log_details'] = $log;
        $arry_log['wallet_log_amount'] = ($mem_price * -1);
        $arry_log['wallet_log_plan'] = "CD";
        $arry_log['wallet_log_status'] = "released";   
        $arry_log['wallet_log_claimbale_on'] = Carbon::now(); 
        Mlm_slot_log::slot_array($arry_log);

	}
	public static function graduate_check($slot_info)
	{
		// select all cd slots
		$slots = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $slot_info->shop_id)
        ->where('slot_status', 'CD')
        ->membership()
        ->membership_points()
        ->get();
        foreach($slots as $slot)
        {
        	// if wallet is not negative
        	if($slot->slot_wallet_current >= 0)
        	{
        		// graduate cd
        		$a = Mlm_complan_manager_cd::gradute_cd($slot);
        	}
        }
	}
	public static function gradute_cd($slot_info)
	{
		$plan_settings = Tbl_mlm_plan::where('shop_id', $slot_info->shop_id)
        ->where('marketing_plan_enable', 1)
        ->where('marketing_plan_trigger', 'Slot Creation')
        ->get();

        // Distribute Earning
        foreach($plan_settings as $key => $value)
        {
            $plan = strtolower($value->marketing_plan_code);
            $a = Mlm_complan_manager::$plan($slot_info);
        }
        // end distribute

        // change status from cd to ps
        $log = "Congratulations! Your " . $slot_info->slot_no . " Has been changed from CD TO PS.";
        $arry_log['wallet_log_slot'] = $slot_info->slot_id;
        $arry_log['shop_id'] = $slot_info->shop_id;
        $arry_log['wallet_log_slot_sponsor'] = $slot_info->slot_id;
        $arry_log['wallet_log_details'] = $log;
        $arry_log['wallet_log_amount'] = 0;
        $arry_log['wallet_log_plan'] = "CD_TO_PS";
        $arry_log['wallet_log_status'] = "released";   
        $arry_log['wallet_log_claimbale_on'] = Carbon::now(); 
        Mlm_slot_log::slot_array($arry_log);

        $update['slot_status']          = "PS";
        $update['old_slot_status']      = $slot_info->slot_status;
        $update['old_slot_status_date'] = Carbon::now();
        Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update);
	}
}