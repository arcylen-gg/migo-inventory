<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_membership_code;

use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;

class Mlm_plan
{
	public static function get_all_active_plan($shop_id)
	{
		$data['mlm_plan'] = Tbl_mlm_plan::where('shop_id', $shop_id)->get();
		return $data;
	}
	
	public static function get_settings($shop_id)
	{
		return Tbl_mlm_plan_setting::where('shop_id', $shop_id)->first();

	}
	
	public static function get_all_active_plan_repurchase($shop_id)
	{
		$data = Tbl_mlm_plan::where('shop_id', $shop_id)
		->where('marketing_plan_enable', 1)
		->where('marketing_plan_trigger', 'Product Repurchase')
		->get();
		
		foreach($data as $key => $value)
        {
        	if(!Schema::hasColumn('tbl_mlm_item_points', $value->marketing_plan_code))
	        {
	            DB::statement('ALTER TABLE `tbl_mlm_item_points` ADD '.$value->marketing_plan_code.' double DEFAULT 0');
	        }
	        if(!Schema::hasColumn('tbl_item_code', $value->marketing_plan_code))
	        {
	            DB::statement('ALTER TABLE `tbl_item_code` ADD '.$value->marketing_plan_code.' double DEFAULT 0');
	        }
        }
		return $data;
	}
	public static function set_slot_no($shop_id = null, $membership_code_id = null)
	{
		if($shop_id == null)
		{
			$shop_id = MLM_ProductController::checkuser('user_shop');
		}
		if($shop_id != null)
		{
			$plan_settings = Tbl_mlm_plan_setting::where('shop_id', $shop_id)->first();
			if($plan_settings != null)
			{
				$plan_settings_prefix_count = $plan_settings->plan_settings_prefix_count;
	    		$plan_settings_enable_mlm = $plan_settings->plan_settings_enable_mlm;
	    		$plan_settings_enable_replicated = $plan_settings->plan_settings_enable_replicated;
	    		$plan_settings_slot_id_format = $plan_settings->plan_settings_slot_id_format;
	    		$plan_settings_format = $plan_settings->plan_settings_format;
	    		$plan_settings_prefix_count = $plan_settings->plan_settings_prefix_count;
	    		
	    		//plan_settings_slot_id_format
			    // 0 = auto;
			    // 1 = format;
			    $slot_count = Tbl_mlm_slot::where('shop_id', $shop_id)->count();
			    $slot_count = $slot_count + 1;
	    		if($plan_settings_slot_id_format == 0)
	    		{
	    			return $slot_count;
	    		}
	    		else if($plan_settings_slot_id_format == 1)
	    		{
	    			// convert prefix count to int
			    	$int_con = intval($plan_settings_prefix_count);
			    	// make int to prefix
			    	$prefix_slot =  str_pad($slot_count, $int_con, '0', STR_PAD_LEFT);
			    	// add text to prefix
			    	$slot_full = $plan_settings_format . $prefix_slot;

			    	

			    	// check if prefix exceeded settings
			    	$slot_count = Tbl_mlm_slot::where('shop_id', $shop_id)
					->count();
					
			    	if(pow(10, $plan_settings_prefix_count) -1 <= $slot_count)
			    	{
			    		// update settings
			    		$update['plan_settings_prefix_count'] = $plan_settings_prefix_count + 1;
	    				Tbl_mlm_plan_setting::where('plan_settings_id', $plan_settings->plan_settings_id)->update($update);
	    				return Mlm_plan::set_slot_no();
			    	}

			    	return $slot_full;
	    		}
	    		else if($plan_settings_slot_id_format == 2)
	    		{
					$random_number_range = rand(pow(10, $plan_settings_prefix_count-1), pow(10, $plan_settings_prefix_count)-1);
					$slot_full = $plan_settings_format . $random_number_range;
					$slot_full = Mlm_plan::randomizer($shop_id, $plan_settings_prefix_count, $plan_settings_format);

					return $slot_full;
	    		}
	    		else if($plan_settings_slot_id_format == 3)
	    		{
	    			if($membership_code_id == null)
	    			{
	    				$slot_full = 'Slot no will be generated after use of membership code';
	    				return $slot_full;
	    			}
	    			else
	    			{
	    				$slot_full = Tbl_membership_code::where('membership_code_id', $membership_code_id)->value('membership_activation_code');
	    				return $slot_full;
	    			}
	    		}
			}
		}
	}
	public static function randomizer($shop_id, $plan_settings_prefix_count, $plan_settings_format)
	{
		$random_number_range = rand(pow(10, $plan_settings_prefix_count-1), pow(10, $plan_settings_prefix_count)-1);
		$slot_full = $plan_settings_format . $random_number_range;
		$slot_count = Tbl_mlm_slot::where('shop_id', $shop_id)
		->where('slot_no', $slot_full)
		->count();
		if($slot_count == 0)
		{
			$slot_count = Tbl_mlm_slot::where('shop_id', $shop_id)
			->count();
			if($slot_count >= pow(10, $plan_settings_prefix_count) - 1)
			{
				$plan_settings = Tbl_mlm_plan_setting::where('shop_id', $shop_id)->first();
	    		$update['plan_settings_prefix_count'] = $plan_settings->plan_settings_prefix_count + 1;
	    		Tbl_mlm_plan_setting::where('plan_settings_id', $plan_settings->plan_settings_id)->update($update);
	    		return Mlm_plan::randomizer($shop_id, $plan_settings->plan_settings_prefix_count + 1, $plan_settings_format);
			}
			else
			{
				return $slot_full;
			}
			
		}
		else
		{ 
			$slot_count = Tbl_mlm_slot::where('shop_id', $shop_id)
			->count();
			if($slot_count >= pow(10, $plan_settings_prefix_count-1))
			{
				$plan_settings = Tbl_mlm_plan_setting::where('shop_id', $shop_id)->first();
	    		$update['plan_settings_prefix_count'] = $plan_settings->plan_settings_prefix_count + 1;
	    		Tbl_mlm_plan_setting::where('plan_settings_id', $plan_settings->plan_settings_id)->update($update);
	    		return Mlm_plan::randomizer($shop_id, $plan_settings->plan_settings_prefix_count + 1, $plan_settings_format);
			}
			else
			{
				return Mlm_plan::randomizer($shop_id, $plan_settings_prefix_count, $plan_settings_format);
			}
		}

	}
}