<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_item_code;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_binary_pairing;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_indirect_setting;
use App\Models\Tbl_mlm_binary_setttings;
use App\Models\Tbl_mlm_unilevel_settings;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_mlm_item_points;
use App\Models\Tbl_mlm_unilevel_points_settings;
use App\Models\Tbl_mlm_stairstep_settings;
use App\Models\Tbl_mlm_stairstep_points_settings;
use App\Models\Tbl_item_code_invoice;
use App\Models\Tbl_mlm_triangle_repurchase;
use App\Models\Tbl_mlm_triangle_repurchase_slot;
use App\Models\Tbl_mlm_discount_card_settings;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_mlm_triangle_repurchase_tree;
use App\Models\Tbl_stairstep_points_log;
use App\Models\Tbl_rank_points_log;
use App\Models\Tbl_rank_update;
use App\Models\Tbl_rank_update_slot;
use App\Models\Tbl_brown_rank;
use App\Models\Tbl_customer;
use App\Models\Tbl_email_content;
use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;

use Carbon\Carbon;

use App\Globals\Mlm_compute;
use App\Globals\Mlm_slot_log;
use App\Globals\Mlm_complan_manager_repurchasev2;
use App\Globals\Mlm_tree;
use App\Globals\Membership_code;
use App\Globals\Mail_global;




class Mlm_complan_manager_repurchasev2
{   
    public static function brown_repurchase($slot_info, $product_price)
    {
        $_sponsor_tree = Tbl_tree_sponsor::orderby("sponsor_tree_level", "asc")->child($slot_info->slot_id)->parent_info()->get();

        foreach($_sponsor_tree as $sponsor_tree)
        {
            /* BUILDER REWARD UP TO SPECIFIC LEVEL */
            Self::brown_builder_reward($sponsor_tree, $slot_info , $product_price);
        }
    }
    public static function brown_builder_reward($slot_info, $trigger_info, $product_price)
    {
        $slot_id = $slot_info->slot_id;
        $current_sponsor_level = $slot_info->sponsor_tree_level;
        $current_rank_id = $slot_info->brown_rank_id;
        $brown_current_rank = Tbl_brown_rank::where("rank_id", $current_rank_id)->first();

        $builder_up_to_level = $brown_current_rank->builder_uptolevel;
        $builder_percentage = $brown_current_rank->builder_reward_percentage / 100;

        if($current_sponsor_level <= $builder_up_to_level)
        {
            $compute_points = $product_price * $builder_percentage;

            if($compute_points != 0)
            {
                $insert_points["points_log_complan"] = "BROWN_BUILDER_POINTS";
                $insert_points["points_log_level"] = $current_sponsor_level;
                $insert_points["points_log_slot"] = $slot_info->slot_id;
                $insert_points["points_log_date_claimed"] = Carbon::now();
                $insert_points["points_log_type"] = "BBP";
                $insert_points["points_log_from"] = "Product Repurchase of Slot No. " . $trigger_info->slot_no;
                $insert_points["points_log_points"] = $compute_points;
                $insert_points["cause_id"] = $trigger_info->slot_id;
                Tbl_mlm_slot_points_log::insert($insert_points);

                /* LEADER REWARD */
                $_sponsor_tree = Tbl_tree_sponsor::orderby("sponsor_tree_level", "asc")->child($slot_info->slot_id)->parent_info()->get();

                foreach($_sponsor_tree as $sponsor_tree)
                {
                    Self::brown_leader_reward($sponsor_tree, $slot_info , "Builder Reward", $compute_points);
                }
            }
        }
    }
    public static function brown_leader_reward($slot_info, $trigger_info, $trigger_reason, $amount)
    {
        $slot_id = $slot_info->slot_id;
        $current_sponsor_level = $slot_info->sponsor_tree_level;
        $current_rank_id = $slot_info->brown_rank_id;

        $brown_current_rank = Tbl_brown_rank::where("rank_id", $current_rank_id)->first();

        if($trigger_reason == "Builder Reward")
        {
            $leader_up_to_level = $brown_current_rank->leader_override_build_uptolevel;
            $leader_percentage = $brown_current_rank->leader_override_build_reward / 100;
        }
        else
        {
            $leader_up_to_level = $brown_current_rank->leader_override_direct_uptolevel;
            $leader_percentage = $brown_current_rank->leader_override_direct_reward / 100;
        }

        if($current_sponsor_level <= $leader_up_to_level)
        {
            /* OVERRIDING */
            $trigger_rank_id = $trigger_info->brown_rank_id;
            $trigger_current_rank = Tbl_brown_rank::where("rank_id", $trigger_rank_id)->first();

            if($trigger_reason == "Builder Reward")
            {
                $trigger_percentage = $trigger_current_rank->leader_override_build_reward / 100;
            }
            else
            {
                $trigger_percentage = $trigger_current_rank->leader_override_direct_reward / 100;
            }

            /* PERCENTAGE OVERRIDING */
            //$leader_percentage = $leader_percentage - $trigger_percentage;

            /* START COMPUTE */
            $compute_points = $amount * $leader_percentage;

            if($compute_points != 0)
            {
                $insert_points["points_log_complan"] = "BROWN_LEADER_POINTS";
                $insert_points["points_log_level"] = $current_sponsor_level;
                $insert_points["points_log_slot"] = $slot_info->slot_id;
                $insert_points["points_log_date_claimed"] = Carbon::now();
                $insert_points["points_log_type"] = "BLP";
                $insert_points["points_log_from"] = $trigger_reason . " of Slot No. " . $trigger_info->slot_no;
                $insert_points["points_log_points"] = $compute_points;
                $insert_points["cause_id"] = $trigger_info->slot_id;
                Tbl_mlm_slot_points_log::insert($insert_points);
            }
        }

    }
    public static function unilevel($slot_info, $points)
    {
        $unilevel_pts = $points;

        $_unilevel_setting = Tbl_mlm_unilevel_settings::get();
        $_tree             = Tbl_tree_sponsor::child($slot_info->slot_id)->level()->distinct_level()->get();

        $unilevel_level = [];
        $unilevel_level_settings = [];
        foreach($_unilevel_setting as $key => $level)
        {
            $unilevel_level[$level->membership_id][$level->unilevel_settings_level] =  $level->unilevel_settings_amount;
            $unilevel_level_settings[$level->membership_id][$level->unilevel_settings_level] =  $level->unilevel_settings_type;
            $unilevel_level_settings_percentage[$level->membership_id][$level->unilevel_settings_level] =  $level->unilevel_settings_percent;
        }
        foreach($_tree as $key => $tree)
        {
            $slot_recipient = Mlm_compute::get_slot_info($tree->sponsor_tree_parent_id);
            if(isset($unilevel_level[$slot_recipient->membership_id][$tree->sponsor_tree_level]))
            {
                      
                $settings = $unilevel_level_settings[$slot_recipient->membership_id][$tree->sponsor_tree_level];
                $percentage = $unilevel_level_settings_percentage[$slot_recipient->membership_id][$tree->sponsor_tree_level];
                // 0 = fixed
                // 1 = percentage
                if($percentage === 0)
                {
                    $unilevel_bonus = $unilevel_level[$slot_recipient->membership_id][$tree->sponsor_tree_level];
                }
                else
                {
                    $unilevel_bonus = ($unilevel_level[$slot_recipient->membership_id][$tree->sponsor_tree_level]/100) * $unilevel_pts;
                }
            }

            else
            {
                $unilevel_bonus = 0;  
            }
            if($unilevel_bonus != 0)
            {
                // 0 = points
                // 1 = cash
                if($settings === 0)
                {
                    $array['points_log_complan'] = "UNILEVEL";
                    $array['points_log_level'] = $tree->sponsor_tree_level;
                    $array['points_log_slot'] = $slot_recipient->slot_id;
                    $array['points_log_Sponsor'] = $slot_info->slot_id;
                    $array['points_log_date_claimed'] = Carbon::now();
                    $array['points_log_converted'] = 0;
                    $array['points_log_converted_date'] = Carbon::now();
                    $array['points_log_type'] = 'GPV';
                    $array['points_log_from'] = 'Product Repurchase';
                    $array['points_log_points'] = $unilevel_bonus;

                    Mlm_slot_log::slot_log_points_array($array);
                }
                else
                {
                    $log_array['earning'] = $unilevel_bonus;
                    $log_array['level'] = $tree->sponsor_tree_level;
                    $log_array['level_tree'] = 'Sponsor Tree';
                    $log_array['complan'] = 'UNILEVEL';

                    $log = Mlm_slot_log::log_constructor($slot_recipient, $slot_info,  $log_array);

                    $arry_log['wallet_log_slot'] = $slot_recipient->slot_id;
                    $arry_log['shop_id'] = $slot_info->shop_id;
                    $arry_log['wallet_log_slot_sponsor'] = $slot_info->slot_id;
                    $arry_log['wallet_log_details'] = $log;
                    $arry_log['wallet_log_amount'] = $unilevel_bonus;
                    $arry_log['wallet_log_plan'] = "UNILEVEL";
                    $arry_log['wallet_log_status'] = "n_ready";   
                    $arry_log['wallet_log_claimbale_on'] = Mlm_complan_manager::cutoff_date_claimable('DIRECT', $slot_info->shop_id); 
                    Mlm_slot_log::slot_array($arry_log);
                }
            }
        }
        Mlm_complan_manager_repurchasev2::unilevel_cutoff('UNILEVEL', $slot_info->shop_id);  
    }
    public static function unilevel_cutoff($code, $shop_id)
    {
        $now = Carbon::now();
        $wallet_log = Tbl_mlm_slot_wallet_log::where('shop_id', $shop_id)
        ->where('wallet_log_plan', $code)
        ->where('wallet_log_status', 'n_ready')
        ->where('wallet_log_claimbale_on', '<', Carbon::now()->addMinutes(1))
        ->get();  
        $cutoff_log = [];
        foreach($wallet_log as $key => $value)
        {
            $cutoff_log[$value->wallet_log_slot][$value->wallet_log_id] = $value->wallet_log_amount; 
        }
        if($cutoff_log != null)
        {
            foreach($cutoff_log as $key => $value)
            {
                foreach($value as $key2 => $value2)
                {
                    $update['wallet_log_status'] = 'released';
                    Tbl_mlm_slot_wallet_log::where('wallet_log_id', $key2)->update($update);
                }
            }
        }
    }
    public static function unilevel_v2($slot_info,$points)
    {
        $unilevel_pts = $points;
        /* ----- COMPUTATION OF PERSONAL PV */
        $update_recipient["slot_personal_points"] = $slot_info->slot_personal_points + $unilevel_pts;

        dd($update_recipient["slot_personal_points"]);
        /* UPDATE SLOT CHANGES TO DATABASE */
        Tbl_mlm_slot::where("slot_id",$slot_info->slot_id)->update($update_recipient);
        $update_recipient  = null;

        /* ----- COMPUTATION OF GROUP PV ----- */
        $_unilevel_setting = Tbl_mlm_unilevel_settings::get();
        $_tree             = Tbl_tree_sponsor::child($slot_info->slot_id)->level()->distinct_level()->get();

        /* RECORD ALL INTO A SINGLE VARIABLE */
        $unilevel_level = null;
        foreach($_unilevel_setting as $key => $level)
        {
            $unilevel_level[$level->membership_id][$level->unilevel_settings_level] =  $level->unilevel_settings_amount;
        }

            /* CHECK IF LEVEL EXISTS */
            if($unilevel_level)
            {
                foreach($_tree as $key => $tree)
                {
                    /* GET SLOT INFO FROM DATABASE */
                    $slot_recipient                          = Tbl_mlm_slot::where("slot_id",$tree->sponsor_tree_parent_id)->customer()->membership()->first();
                    $update_recipient["slot_group_points"]   = $slot_recipient->slot_group_points;
                    $update_recipient["slot_upgrade_points"] = $slot_recipient->slot_upgrade_points;

                    /* COMPUTE FOR BONUS */
                    if(isset($unilevel_level[$slot_recipient->membership_id][$tree->sponsor_tree_level]))
                    {
                        $unilevel_bonus = ($unilevel_level[$slot_recipient->membership_id][$tree->sponsor_tree_level]/100) * $unilevel_pts;      
                    }
                    else
                    {
                        $unilevel_bonus = 0;  
                    }

                    /* CHECK IF BONUS IS ZERO */
                    if($unilevel_bonus != 0)
                    {
                        /* UPDATE GROUP PTS */
                        $update_recipient["slot_group_points"]  = $update_recipient["slot_group_points"] + $unilevel_bonus;
                    }
                    Tbl_mlm_slot::where("slot_id",$slot_recipient->slot_id)->update($update_recipient);
                }
            }            
    }
    public static function binary_repurchase($slot_info,$points)
    {
        $binary_pts    = $points;

        /* FOR PS ONLY or CD Turned PS */
        // GET PAIRING SETTINGS / BINARY TREE
        $settings_pairing       = Tbl_mlm_binary_pairing::orderBy("pairing_point_left", "desc")->get();
        $binary_advance_pairing = Tbl_mlm_binary_setttings::where('shop_id', $slot_info->shop_id)->first();

        // select max tree level
        if(isset($binary_advance_pairing->binary_settings_max_tree_level))
        {
            $binary_settings_max_tree_level = $binary_advance_pairing->binary_settings_max_tree_level;
        }
        else
        {
            // set 999 max tree level if setting does not exist
            $binary_settings_max_tree_level = 999;
        }
        $settings_tree = Tbl_tree_placement::child($slot_info->slot_id)
        ->where('placement_tree_level', '<=', $binary_settings_max_tree_level)
        ->level()->distinct_level()->parentslot()->get();
        // dd($slot_info->slot_id,$settings_tree);
        // end

        // DISTRIBUTE BINARY POINTS
        foreach($settings_tree as $tree)
        {
            // RETRIEVE LEFT & RIGHT POINTS
            $binary["left"] = $tree->slot_binary_left;
            $binary["right"] = $tree->slot_binary_right; 
            // END

            // GET BINARY POINTS OF MEMBERSHIP
            $binary['points'] = $unilevel_pts  = $binary_pts;
            // End
            if($binary['points'] != 0)
            {
                // ADD OLD BINARY POINTS + NEW BINARY POINTS

                $binary[$tree->placement_tree_position] = $binary[$tree->placement_tree_position] + $binary['points']; 
                // End
                $update['slot_binary_left'] =   $binary["left"];
                $update['slot_binary_right'] =  $binary["right"];
                Tbl_mlm_slot::where('slot_id', $tree->placement_tree_parent_id)->update($update);
            }
        }
        // END
        $a = Mlm_complan_manager::binary_pairing($slot_info);
        Mlm_complan_manager::cutoff_binary('BINARY', $slot_info->shop_id); 
    }
    public static function rank($slot_info,$points,$group_points)
    {
        $slot_info              = Tbl_mlm_slot::where("slot_id", $slot_info->slot_id)->customer()->membership()->first();
        $shop_id                = $slot_info->shop_id;

        $rank_points            = $points;
        $rank_group_points      = $group_points;
        $percentage             = null;
        $rank_real_time_update  = Tbl_mlm_plan_setting::where("shop_id",$shop_id)->first()->rank_real_time_update; 

        if($rank_points != 0)
        {
            $array['points_log_complan']        = "RANK_PV";
            $array['points_log_level']          = 0;
            $array['points_log_slot']           = $slot_info->slot_id;
            $array['points_log_Sponsor']        = $slot_info->slot_id;
            $array['points_log_date_claimed']   = Carbon::now();
            $array['points_log_converted']      = 0;
            $array['points_log_converted_date'] = Carbon::now();
            $array['points_log_type']           = 'RPV';
            $array['points_log_from']           = 'Product Repurchase';
            $array['points_log_points']         = $rank_points;
            $array['original_from_complan']     = "RANK";

            $slot_logs_id                       = Mlm_slot_log::slot_log_points_array($array);

            $insert_rank_log["rank_original_amount"] = $rank_points;
            $insert_rank_log["rank_percentage_used"] = 0;
            $insert_rank_log["slot_points_log_id"]   = $slot_logs_id;
            Tbl_rank_points_log::insert($insert_rank_log);

            if($rank_real_time_update == 1)
            {
                Mlm_complan_manager_repurchasev2::real_time_rank_upgrade($slot_info);
            }
        }

        $array               = null;
        $_stairstep_settings = Tbl_mlm_stairstep_points_settings::where("shop_id",$shop_id)->get();
        $_tree               = Tbl_tree_sponsor::child($slot_info->slot_id)->level()->distinct_level()->get();

        $stairstep_level = [];

        foreach($_stairstep_settings as $key => $level)
        {
            $stairstep_level[$level->stairstep_points_level]                     =  $level->stairstep_points_amount;
            $stairstep_level_settings_percentage[$level->stairstep_points_level] =  $level->stairstep_points_percentage;
        }

        foreach($_tree as $key => $tree)
        {
            $slot_recipient = Mlm_compute::get_slot_info($tree->sponsor_tree_parent_id);
            if(isset($stairstep_level[$tree->sponsor_tree_level]))
            {      
                // 0 = fixed
                // 1 = percentage
                // $percentage = $stairstep_level_settings_percentage[$tree->sponsor_tree_level];
                // if($percentage === 0)
                // {
                //     $rank_bonus = $stairstep_level[$tree->sponsor_tree_level];
                // }
                // else
                // {
                    $rank_bonus                              = ($stairstep_level[$tree->sponsor_tree_level]/100) * $rank_group_points;
                    $insert_rank_log["rank_percentage_used"] = $stairstep_level[$tree->sponsor_tree_level];
                // }
            }
            else
            {
                $rank_bonus = 0;  
            }


            if($rank_bonus != 0)
            {
                    $array['points_log_complan']        = "RANK_GPV";
                    $array['points_log_level']          = $tree->sponsor_tree_level;
                    $array['points_log_slot']           = $slot_recipient->slot_id;
                    $array['points_log_Sponsor']        = $slot_info->slot_id;
                    $array['points_log_date_claimed']   = Carbon::now();
                    $array['points_log_converted']      = 0;
                    $array['points_log_converted_date'] = Carbon::now();
                    $array['points_log_type']           = 'RGPV';
                    $array['points_log_from']           = 'Product Repurchase';
                    $array['points_log_points']         = $rank_bonus;
                    $array['original_from_complan']     = "RANK";

                    $slot_logs_id                       = Mlm_slot_log::slot_log_points_array($array);
                    
                    if(isset($insert_rank_log["rank_percentage_used"]))
                    {
                        $insert_rank_log["rank_original_amount"] = $rank_group_points;
                        $insert_rank_log["slot_points_log_id"]   = $slot_logs_id;
                        Tbl_rank_points_log::insert($insert_rank_log);
                    }

                    if($rank_real_time_update == 1)
                    {
                        Mlm_complan_manager_repurchasev2::real_time_rank_upgrade($slot_recipient);
                    }
            }
        } 
    }

    public static function stairstep($slot_info,$points,$group_points)
    {
        $slot_info     = Tbl_mlm_slot::where("slot_id", $slot_info->slot_id)->customer()->membership()->first();
        $shop_id       = $slot_info->shop_id;

        $stairstep_points       = $points;
        $stairstep_group_points = $group_points;
        $percentage             = null;
        
        if($stairstep_points != 0)
        {
            $array['points_log_complan'] = "STAIRSTEP_PV";
            $array['points_log_level'] = 0;
            $array['points_log_slot'] = $slot_info->slot_id;
            $array['points_log_Sponsor'] = $slot_info->slot_id;
            $array['points_log_date_claimed'] = Carbon::now();
            $array['points_log_converted'] = 0;
            $array['points_log_converted_date'] = Carbon::now();
            $array['points_log_type'] = 'SPV';
            $array['points_log_from'] = 'Product Repurchase';
            $array['points_log_points'] = $stairstep_points;
            $array['original_from_complan'] = "STAIRSTEP";

            $slot_logs_id = Mlm_slot_log::slot_log_points_array($array);

            $insert_stairstep_logs["stairstep_points_amount"]       = $stairstep_points;
            $insert_stairstep_logs["stairstep_percentage"]          = 0;
            $insert_stairstep_logs["stairstep_reduced_percentage"]  = 0;
            $insert_stairstep_logs["stairstep_reduced_by_id"]       = 0;
            $insert_stairstep_logs["stairstep_reduced_rank"]        = 0;
            $insert_stairstep_logs["stairstep_cause_id"]            = $slot_info->slot_id;
            $insert_stairstep_logs["current_rank"]                  = $slot_info->stairstep_rank;
            $insert_stairstep_logs["slot_points_log_id"]            = $slot_logs_id;
            Tbl_stairstep_points_log::insert($insert_stairstep_logs);
        }

        $_tree               = Tbl_tree_sponsor::child($slot_info->slot_id)->level()->distinct_level()->get();

        $slot_stairstep      = Tbl_mlm_stairstep_settings::where("stairstep_id",$slot_info->stairstep_rank)->first();

        if($slot_stairstep)
        {   
            $computed_points = 0;

            if($slot_stairstep->stairstep_bonus != 0)
            {
                $computed_points = ($slot_stairstep->stairstep_bonus/100) * $stairstep_group_points;
                $percentage      = $slot_stairstep->stairstep_bonus;
            }  

            if($slot_stairstep->stairstep_rebates_bonus != 0 && $stairstep_points != 0)
            {
                $stairstep_rebates_bonus = $slot_stairstep->stairstep_rebates_bonus;
                $rebates_bonus           = ($stairstep_rebates_bonus/100) * $stairstep_points;

                if($rebates_bonus != 0)
                {
                    $array['points_log_complan']        = "STAIRSTEP_REBATES_BONUS";
                    $array['points_log_level']          = 0;
                    $array['points_log_slot']           = $slot_info->slot_id;
                    $array['points_log_Sponsor']        = $slot_info->slot_id;
                    $array['points_log_date_claimed']   = Carbon::now();
                    $array['points_log_converted']      = 0;
                    $array['points_log_converted_date'] = Carbon::now();
                    $array['points_log_type']           = 'SRB';
                    $array['points_log_from']           = 'Product Repurchase';
                    $array['points_log_points']         = $rebates_bonus;
                    $array['original_from_complan']     = "STAIRSTEP";

                    $slot_logs_id = Mlm_slot_log::slot_log_points_array($array);
                }
            }
        }
        else
        {
            $percentage = 0;
        }
        
        $reduced_by      = $slot_info;
        $check_stairstep = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->first();
        $sponsor_tree    = Tbl_tree_sponsor::where("sponsor_tree_child_id",$slot_info->slot_id)->orderBy("sponsor_tree_level","ASC")->get();
        if($check_stairstep)
        {
            foreach($sponsor_tree as $placement)
            {
                $slot_recipient  = Mlm_compute::get_slot_info($placement->sponsor_tree_parent_id);
                $reduced_percent = 0;
                $computed_points = 0;
                $old_percentage  = 0;
                $slot_stairstep = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->where("stairstep_id",$slot_recipient->stairstep_rank)->first();
                
                if($slot_stairstep)
                {                       
                    if($slot_stairstep->stairstep_bonus > $percentage && $slot_stairstep->stairstep_bonus != 0)
                    { 
                        $reduced_percent = $slot_stairstep->stairstep_bonus - $percentage;
                        if($reduced_percent > 0)
                        {
                            $computed_points = (($reduced_percent)/100) * $stairstep_group_points;
                            $old_percentage  = $percentage;
                            $percentage      = $slot_stairstep->stairstep_bonus;
                        }  
                    }
                }

                if($computed_points > 0)
                {             
                    $array['points_log_complan']        = "STAIRSTEP_GPV";
                    $array['points_log_level']          = $placement->sponsor_tree_level;
                    $array['points_log_slot']           = $slot_recipient->slot_id;
                    $array['points_log_Sponsor']        = $slot_info->slot_id;
                    $array['points_log_date_claimed']   = Carbon::now();
                    $array['points_log_converted']      = 0;
                    $array['points_log_converted_date'] = Carbon::now();
                    $array['points_log_type']           = 'SGPV';
                    $array['points_log_from']           = 'Product Repurchase';
                    $array['points_log_points']         = $computed_points;
                    $array['original_from_complan']     = "STAIRSTEP";
                    
                    $slot_logs_id = Mlm_slot_log::slot_log_points_array($array);

                    if($old_percentage == null)
                    {
                        $old_percentage = 0;
                    }
                    
                    $insert_stairstep_logs["stairstep_points_amount"]       = $stairstep_group_points;
                    $insert_stairstep_logs["stairstep_percentage"]          = $percentage;
                    $insert_stairstep_logs["stairstep_reduced_percentage"]  = $old_percentage;
                    $insert_stairstep_logs["stairstep_reduced_by_id"]       = $reduced_by->slot_id;
                    $insert_stairstep_logs["stairstep_reduced_rank"]        = $reduced_by->stairstep_rank;
                    $insert_stairstep_logs["stairstep_cause_id"]            = $slot_info->slot_id;
                    $insert_stairstep_logs["current_rank"]                  = $slot_recipient->stairstep_rank;
                    $insert_stairstep_logs["slot_points_log_id"]            = $slot_logs_id;
                    Tbl_stairstep_points_log::insert($insert_stairstep_logs);

                    $reduced_by = $slot_recipient;
                }
            }
        }

        Mlm_complan_manager_repurchasev2::stairstep_cutoff('STAIRSTEP', $slot_info->shop_id);  
    }

    public static function stairstep_cutoff($code,$slot_info)
    { 

    }

    public static function repurchase_points($slot_info,$points)
    {
        $membership_points_repurchase = $points;

        if($membership_points_repurchase != 0)
        {
            $array['points_log_complan'] = "REPURCHASE_POINTS";
            $array['points_log_level'] = 0;
            $array['points_log_slot'] = $slot_info->slot_id;
            $array['points_log_Sponsor'] = $slot_info->slot_id;
            $array['points_log_date_claimed'] = Carbon::now();
            $array['points_log_converted'] = 0;
            $array['points_log_converted_date'] = Carbon::now();
            $array['points_log_type'] = 'PV';
            $array['points_log_from'] = 'Product Repurchase';
            $array['points_log_points'] = $membership_points_repurchase;

            Mlm_slot_log::slot_log_points_array($array);
        }
    }
    public static function repurchase_cashback($slot_info,$points,$rank_points = 0,$cashback_points = 0)
    {
        $membership_points_repurchase_cashback        = $points;
        $membership_points_repurchase_cashback_points = $cashback_points;
        $check_privilege                       = Tbl_mlm_plan_setting::where('shop_id',$slot_info->shop_id)->first();
        if($check_privilege)
        {
            $check_privilege = $check_privilege->enable_privilege_system;
        }
        else
        {
            $check_privilege = 0;
        }

        if($check_privilege == 1)
        {
            $privilege_membership = Tbl_membership::where("shop_id",$slot_info->shop_id)->where("membership_privilege",1)->first();
            if($privilege_membership)
            {
                $privilege_membership = $privilege_membership->membership_id;
            }
            else
            {
                $privilege_membership = 0;
            }
        }
        else
        {
            $privilege_membership = 0;
        }


        if($membership_points_repurchase_cashback != 0)
        {
            if(($privilege_membership == $slot_info->slot_membership) && ($check_privilege == 1))
            {
                $direct_slot          = Tbl_mlm_slot::where("slot_id",$slot_info->slot_sponsor)->where("shop_id",$slot_info->shop_id)->first();
                if($direct_slot)
                { 
                    if($direct_slot->slot_membership != $privilege_membership)
                    {
                        $log_array['earning'] = $membership_points_repurchase_cashback;
                        $log_array['level'] = 1;
                        $log_array['level_tree'] = 'Sponsor Tree';
                        $log_array['complan'] = 'REPURCHASE_CASHBACK';

                        $log = Mlm_slot_log::log_constructor($direct_slot, $slot_info,  $log_array);

                        $arry_log['wallet_log_slot'] = $direct_slot->slot_id;
                        $arry_log['shop_id'] = $slot_info->shop_id;
                        $arry_log['wallet_log_slot_sponsor'] = $slot_info->slot_id;
                        $arry_log['wallet_log_details'] = $log;
                        $arry_log['wallet_log_amount'] = $membership_points_repurchase_cashback;
                        $arry_log['wallet_log_plan'] = "REPURCHASE_CASHBACK";
                        $arry_log['wallet_log_status'] = "released";   
                        $arry_log['wallet_log_claimbale_on'] = Mlm_complan_manager::cutoff_date_claimable('REPURCHASE_CASHBACK', $slot_info->shop_id); 
                        Mlm_slot_log::slot_array($arry_log);
                    }     
                }
            }
            else
            {
                $log_array['earning'] = $membership_points_repurchase_cashback;
                $log_array['level'] = 0;
                $log_array['level_tree'] = 'Sponsor Tree';
                $log_array['complan'] = 'REPURCHASE_CASHBACK';

                $log = Mlm_slot_log::log_constructor($slot_info, $slot_info,  $log_array);

                $arry_log['wallet_log_slot'] = $slot_info->slot_id;
                $arry_log['shop_id'] = $slot_info->shop_id;
                $arry_log['wallet_log_slot_sponsor'] = $slot_info->slot_id;
                $arry_log['wallet_log_details'] = $log;
                $arry_log['wallet_log_amount'] = $membership_points_repurchase_cashback;
                $arry_log['wallet_log_plan'] = "REPURCHASE_CASHBACK";
                $arry_log['wallet_log_status'] = "released";   
                $arry_log['wallet_log_claimbale_on'] = Mlm_complan_manager::cutoff_date_claimable('REPURCHASE_CASHBACK', $slot_info->shop_id); 
                Mlm_slot_log::slot_array($arry_log);
            }
        }  

        /* PHILTECH VIP REPURCHASE CASHBACK-DESU */
        if($membership_points_repurchase_cashback_points != 0)
        {
            if(($privilege_membership == $slot_info->slot_membership) && ($check_privilege == 1))
            {
                $direct_slot          = Tbl_mlm_slot::where("slot_id",$slot_info->slot_sponsor)->where("shop_id",$slot_info->shop_id)->first();
                if($direct_slot)
                { 
                    if($direct_slot->slot_membership != $privilege_membership)
                    {
                        $array['points_log_complan'] = "REPURCHASE_CASHBACK";
                        $array['points_log_level'] = 0;
                        $array['points_log_slot'] = $slot_info->slot_id;
                        $array['points_log_Sponsor'] = $slot_info->slot_id;
                        $array['points_log_date_claimed'] = Carbon::now();
                        $array['points_log_converted'] = 0;
                        $array['points_log_converted_date'] = Carbon::now();
                        $array['points_log_type'] = 'RCP';
                        $array['points_log_from'] = 'Repurchase Cashback Points';
                        $array['points_log_points'] = $membership_points_repurchase_cashback_points;
                        Mlm_slot_log::slot_log_points_array($array);
                    }     
                }
            }
            else
            {
                $array['points_log_complan'] = "REPURCHASE_CASHBACK";
                $array['points_log_level'] = 0;
                $array['points_log_slot'] = $slot_info->slot_id;
                $array['points_log_Sponsor'] = $slot_info->slot_id;
                $array['points_log_date_claimed'] = Carbon::now();
                $array['points_log_converted'] = 0;
                $array['points_log_converted_date'] = Carbon::now();
                $array['points_log_type'] = 'RCP';
                $array['points_log_from'] = 'Repurchase Cashback Points';
                $array['points_log_points'] = $membership_points_repurchase_cashback_points;
                Mlm_slot_log::slot_log_points_array($array);
            }
        }        

        $membership_points_rank_repurchase_cashback = $rank_points;

        if($membership_points_rank_repurchase_cashback != 0)
        {
            $log_array['earning'] = $membership_points_rank_repurchase_cashback;
            $log_array['level'] = 0;
            $log_array['level_tree'] = 'Sponsor Tree';
            $log_array['complan'] = 'RANK_REPURCHASE_CASHBACK';

            $log = Mlm_slot_log::log_constructor($slot_info, $slot_info,  $log_array);

            $arry_log['wallet_log_slot'] = $slot_info->slot_id;
            $arry_log['shop_id'] = $slot_info->shop_id;
            $arry_log['wallet_log_slot_sponsor'] = $slot_info->slot_id;
            $arry_log['wallet_log_details'] = $log;
            $arry_log['wallet_log_amount'] = $membership_points_rank_repurchase_cashback;
            $arry_log['wallet_log_plan'] = "RANK_REPURCHASE_CASHBACK";
            $arry_log['wallet_log_status'] = "released";   
            $arry_log['wallet_log_claimbale_on'] = Mlm_complan_manager::cutoff_date_claimable('REPURCHASE_CASHBACK', $slot_info->shop_id); 
            Mlm_slot_log::slot_array($arry_log);
        }
    }
    public static function unilevel_repurchase_points($slot_info, $points)
    {
        $unilevel_pts = $points;


        $_unilevel_setting = Tbl_mlm_unilevel_points_settings::where('unilevel_points_archive', 0)->get();
        $_tree             = Tbl_tree_sponsor::child($slot_info->slot_id)->level()->distinct_level()->get();

        $unilevel_level = [];
        $unilevel_level_settings = [];
        foreach($_unilevel_setting as $key => $level)
        {
            $unilevel_level[$level->membership_id][$level->unilevel_points_level] =  $level->unilevel_points_amount;
            $unilevel_level_settings_percentage[$level->membership_id][$level->unilevel_points_level] =  $level->unilevel_points_percentage;
        }
        foreach($_tree as $key => $tree)
        {
            $slot_recipient = Mlm_compute::get_slot_info($tree->sponsor_tree_parent_id);
            if(isset($unilevel_level[$slot_recipient->membership_id][$tree->sponsor_tree_level]))
            {
                $percentage = $unilevel_level_settings_percentage[$slot_recipient->membership_id][$tree->sponsor_tree_level];
                // 0 = fixed
                // 1 = percentage
                if($percentage === 0)
                {
                    $unilevel_bonus = $unilevel_level[$slot_recipient->membership_id][$tree->sponsor_tree_level];
                }
                else
                {
                    $unilevel_bonus = ($unilevel_level[$slot_recipient->membership_id][$tree->sponsor_tree_level]/100) * $unilevel_pts;
                }
            }
            else
            {
                $unilevel_bonus = 0;  
            }
            if($unilevel_bonus != 0)
            {
                $array['points_log_complan'] = "UNILEVEL_REPURCHASE_POINTS";
                $array['points_log_level'] = $tree->sponsor_tree_level;
                $array['points_log_slot'] = $slot_recipient->slot_id;
                $array['points_log_Sponsor'] = $slot_info->slot_id;
                $array['points_log_date_claimed'] = Carbon::now();
                $array['points_log_converted'] = 0;
                $array['points_log_converted_date'] = Carbon::now();
                $array['points_log_type'] = 'GPV';
                $array['points_log_from'] = 'Product Repurchase';
                $array['points_log_points'] = $unilevel_bonus;
                Mlm_slot_log::slot_log_points_array($array);
            }
        }
        Mlm_complan_manager_repurchasev2::unilevel_cutoff('UNILEVEL_REPURCHASE_POINTS', $slot_info->shop_id);
    }
    
    public static function discount_card_repurchase($slot_info, $points)
    {
        $dc_count      = $points;

        if($dc_count != 0)
        {
            $membership_id = $slot_info->slot_membership;
            $settings = Tbl_mlm_discount_card_settings::where('membership_id', $membership_id)->first();
            if($settings)
            {   
                for($i = 0; $i < $dc_count; $i++)
                {
                    $insert['discount_card_log_date_created'] = Carbon::now();
                    $insert['discount_card_slot_sponsor'] = $slot_info->slot_id;
                    $insert['discount_card_customer_sponsor'] = $slot_info->slot_owner;
                    $insert['discount_card_membership'] = $settings->discount_card_membership;
                    $insert['discount_card_log_code'] = Membership_code::random_code_generator(8);
                    Tbl_mlm_discount_card_log::insert($insert);
                }
            }
        }
    }
    public static function triangle_repurchase($slot_info, $item_code_id)
    {
        ini_set('xdebug.max_nesting_level', 500);
        $item_code = Tbl_item_code::where('item_code_id', $item_code_id)->first();
        if($item_code)
        {
            $invoice = Tbl_item_code_invoice::where('item_code_invoice_id', $item_code->item_code_invoice_id)->first();

            if($invoice)
            {
                $amount = $invoice->item_subtotal;
                Mlm_complan_manager_repurchasev2::triangle_repurchase_amount($slot_info, $amount, $item_code->item_code_invoice_id);
            }
        }
    }
    public static function triangle_repurchase_amount($slot_info, $amount, $invoice_id)
    {
        $shop_id =  $slot_info->shop_id;
        $settings = Tbl_mlm_triangle_repurchase::where('shop_id', $shop_id)
        ->where('membership_id', $slot_info->membership_id)  
        ->first();
        if($settings)
        {
            if($settings->triangle_repurchase_amount != 0 || $settings->triangle_repurchase != null)
            {
                $mod = floor($amount/ $settings->triangle_repurchase_amount);

                $count_i = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_invoice_id', $invoice_id)->count();
                if($count_i == 0)
                {
                    if($mod >= 1)
                    {
                        for($i = 0; $i < $mod; $i++)
                        {
                            // repurchase_slot_no
                            $insert['repurchase_slot_owner'] = $slot_info->customer_id;
                            $insert['repurchase_slot_slot_id'] = $slot_info->slot_id;
                            $insert['repurchase_slot_invoice_id'] = $invoice_id;
                            $insert['repurchase_slot_amount'] = $settings->triangle_repurchase_amount;
                            $insert['repurchase_slot_shop_id'] = $shop_id;  

                            $id = Tbl_mlm_triangle_repurchase_slot::insertGetId($insert);
                            $count_slot = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_shop_id', $shop_id)->count();
                            $slot = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_id', $id)->first();
                            if($count_slot >= 2)
                            {
                                Mlm_tree::triangle_repurchase_tree_l_r($slot);
                            }
                            Mlm_complan_manager_repurchasev2::triangle_repurchase_graduate($id, $shop_id);
                        }
                    }
                }
            }
        }
    }
    public static function triangle_repurchase_graduate($repurchase_slot_id, $shop_id)
    {
        $slot = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_id', $repurchase_slot_id)->first();

        if($slot)
        {
            $slot_placement_grad_count = Tbl_mlm_triangle_repurchase_tree::where('tree_repurchase_slot_sponsor', $slot->repurchase_slot_placement)
            ->where('tree_repurchase_tree_level', 1)
            ->count();
            if($slot_placement_grad_count == 2)
            {

                $slot_earn = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_id', $slot->repurchase_slot_placement)
                ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_triangle_repurchase_slot.repurchase_slot_slot_id')
                ->first();
                if($slot_earn)
                {
                    $settings = Tbl_mlm_triangle_repurchase::where('shop_id', $shop_id)
                    ->where('membership_id', $slot_earn->slot_membership)  
                    ->first();

                    if($settings)
                    {
                        $label = Mlm_compute::get_label_plan('TRIANGLE_REPURCHASE', $shop_id);
                        $log = 'Congratulations! Your repurchase slot #'.$slot_earn->repurchase_slot_id.' earned ' .  $settings->triangle_repurchase_income . ' in ' . $label . '. From tree of Repurchase Slot #' . $slot->repurchase_slot_placement ;
                        $arry_log['wallet_log_slot'] = $slot_earn->slot_id;
                        $arry_log['shop_id'] = $slot_earn->shop_id;
                        $arry_log['wallet_log_slot_sponsor'] = $slot_earn->slot_id;
                        $arry_log['wallet_log_details'] = $log;
                        $arry_log['wallet_log_amount'] = $settings->triangle_repurchase_income;
                        $arry_log['wallet_log_plan'] = "TRIANGLE_REPURCHASE";
                        $arry_log['wallet_log_status'] = "released";   
                        $arry_log['wallet_log_claimbale_on'] = Carbon::now(); 
                        Mlm_slot_log::slot_array($arry_log); 

                        $tree_income = Tbl_mlm_triangle_repurchase_tree::where('tree_repurchase_slot_child', $slot_earn->repurchase_slot_id)
                        ->distinct_level()
                        ->get();
                        
                        foreach($tree_income as $key => $value)
                        {
                            $slot_earn = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_id', $value->tree_repurchase_slot_sponsor)
                            ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_triangle_repurchase_slot.repurchase_slot_slot_id')
                            ->first();

                            $label = Mlm_compute::get_label_plan('TRIANGLE_REPURCHASE', $shop_id);
                            $log = 'Congratulations! Your repurchase slot #'.$slot_earn->repurchase_slot_id.' earned ' .  $settings->triangle_repurchase_income . ' in ' . $label . '. From tree of Repurchase Slot #' . $slot->repurchase_slot_placement ;
                            $arry_log['wallet_log_slot'] = $slot_earn->slot_id;
                            $arry_log['shop_id'] = $slot_earn->shop_id;
                            $arry_log['wallet_log_slot_sponsor'] = $slot_earn->slot_id;
                            $arry_log['wallet_log_details'] = $log;
                            $arry_log['wallet_log_amount'] = $settings->triangle_repurchase_income;
                            $arry_log['wallet_log_plan'] = "TRIANGLE_REPURCHASE";
                            $arry_log['wallet_log_status'] = "released";   
                            $arry_log['wallet_log_claimbale_on'] = Carbon::now(); 
                            Mlm_slot_log::slot_array($arry_log); 
                        }
                    }
                    
                }
                
            }
        }
    }

    public static function real_time_rank_upgrade($slot_info)
    {
  
        $shop_id                    = $slot_info->shop_id;
        $slot_id                    = $slot_info->slot_id;
        $old_rank_id                = 0;
        $new_rank_id                = 0;
        $required_leg_update_id     = 0;
        $required_leg_update_count  = 0;
        $include_rpv_on_rgpv        = Tbl_mlm_plan_setting::where('shop_id',$shop_id)->first()->include_rpv_on_rgpv;

        // Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id","<",$slot->slot_id)->orderBy("slot_id","DESC")->first();                                          
        $slot_info          = Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id",$slot_id)->first();

        if($slot_info)
        {       
            $old_rank_id    = $slot_info->stairstep_rank;
            $rpv            = Tbl_mlm_slot_points_log::where("points_log_slot",$slot_id)
                                                     ->where("points_log_type","RPV");
                                                     

            $grpv           = Tbl_mlm_slot_points_log::where("points_log_slot",$slot_id)
                                                     ->where("points_log_type","RGPV");
             
            $days_sub       = Tbl_mlm_plan_setting::where('shop_id',$shop_id)->first()->rank_real_time_update_counter;
                   
            /* IF HAS RANGE FOR DATE FROM START TO END VARIABLE */                                                                     
            if($days_sub != 0)
            {
                $days_sub = $days_sub - 1;
                $start    = Carbon::parse(Carbon::now()->startOfMonth())->subMonths($days_sub)->format("Y-m-d 00:00:00");
                $end      = Carbon::parse(Carbon::now()->endOfMonth())->format("Y-m-d 23:59:59");
                $rpv      = $rpv->where('points_log_date_claimed',">=",$start)->where('points_log_date_claimed',"<=",$end)->sum("points_log_points");
                $grpv     = $grpv->where('points_log_date_claimed',">=",$start)->where('points_log_date_claimed',"<=",$end)->sum("points_log_points");
            }
            else
            {
                $rpv  = $rpv->sum("points_log_points");
                $grpv = $grpv->sum("points_log_points");
            }

            if(!$rpv)
            {
                $rpv = 0;
            }   
            if(!$grpv)
            {
                $grpv = 0;
            }

            if($include_rpv_on_rgpv == 1)
            {
                $grpv = $grpv + $rpv;
            }
                                               
            $slot_stairstep        = Tbl_mlm_stairstep_settings::where("stairstep_id",$slot_info->stairstep_rank)->first();
            $slot_stairstep_get    = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)
                                                               ->where("stairstep_required_pv","<=",$rpv)
                                                               ->where("stairstep_required_gv","<=",$grpv)
                                                               ->orderBy("stairstep_level","DESC")
                                                               ->get();

            $sponsor_tree    = Tbl_tree_sponsor::where("sponsor_tree_child_id",$slot_id)->orderBy("sponsor_tree_level","ASC")->get();
            $percentage      = null;
            $check_stairstep = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->first();
            $slot_pv         = $rpv;

            $check_if_change = 0;
            foreach($slot_stairstep_get as $slot_stairstep_new)
            {
                if(!$slot_stairstep)
                {
                    $check_stair_level = 0;
                }
                else
                {
                    $check_stair_level = $slot_stairstep->stairstep_level;
                }

                if($slot_stairstep_new->stairstep_level > $check_stair_level)
                {
                    if($slot_stairstep_new->stairstep_leg_id != 0)
                    {
                        $leg_count = Tbl_tree_sponsor::where("sponsor_tree_parent_id",$slot_id)->child_info()->where("stairstep_rank",$slot_stairstep_new->stairstep_leg_id)->count();
                        if($leg_count >= $slot_stairstep_new->stairstep_leg_count)
                        {
                            $update_slot["stairstep_rank"] = $slot_stairstep_new->stairstep_id;
                            $new_rank_id                   = $slot_stairstep_new->stairstep_id;
                            $required_leg_update_count     = $leg_count;
                            $required_leg_update_id        = $slot_stairstep_new->stairstep_leg_id;
                            Tbl_mlm_slot::where("slot_id",$slot_id)->update($update_slot);
                            $check_if_change = 1;
                            break;
                        }
                    }
                    else
                    {
                        $update_slot["stairstep_rank"] = $slot_stairstep_new->stairstep_id;
                        $new_rank_id                   = $slot_stairstep_new->stairstep_id;
                        Tbl_mlm_slot::where("slot_id",$slot_id)->update($update_slot);
                        $check_if_change = 1;
                        break;
                    }
                }
            }

            if($new_rank_id == 0)
            {
                $new_rank_id = $old_rank_id;
            }

            if($new_rank_id != $old_rank_id)
            {
                $insert["total_slots"]      = Tbl_mlm_slot::where("shop_id",$shop_id)->count();
                $insert["shop_id"]          = $shop_id;             
                $insert["date_created"]     = Carbon::now();    
                $insert["real_time_update"] = 1;    
                $rank_update_id             = Tbl_rank_update::insertGetId($insert);

                $insert_update_rank["rank_update_id"]           = $rank_update_id;              
                $insert_update_rank["slot_id"]                  = $slot_id;
                $insert_update_rank["rank_personal_pv"]         = $rpv;
                $insert_update_rank["rank_group_pv"]            = $grpv;
                $insert_update_rank["required_leg_rank_id"]     = $required_leg_update_id;
                $insert_update_rank["current_leg_rank_count"]   = $required_leg_update_count;
                $insert_update_rank["new_rank_id"]              = $new_rank_id;                 
                $insert_update_rank["old_rank_id"]              = $old_rank_id;                 
                $insert_update_rank["date_created"]             = Carbon::now();

                Tbl_rank_update_slot::insert($insert_update_rank);
                              

                $update_rank_update["complete"] = 1;
                Tbl_rank_update::where("rank_update_id",$rank_update_id)->update($update_rank_update);  

                $rank_update_email = Tbl_mlm_plan_setting::where("shop_id",$shop_id)->first()->rank_update_email;

                if($rank_update_email == 1)
                {
                    $content        = Mlm_complan_manager_repurchasev2::get_email_content_rank($shop_id,$new_rank_id);
                    if($content != null)
                    {
                        $new_rank_data  = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->where("stairstep_id",$new_rank_id)->first();
                        $old_rank_data  = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->where("stairstep_id",$old_rank_id)->first();

                        $customer_email = Tbl_customer::where("customer_id",$slot_info->slot_owner)->first();
                        $email_content["subject"] = $content->email_content_subject;
                        $email_content["content"] = $content->email_content;
                        $email_address            = $customer_email->email;
                        // $email_address            = "";

                        $return_mail = Mail_global::send_email(null, $email_content, $shop_id, $email_address);
                    }
                    else
                    {
                        $new_rank_data  = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->where("stairstep_id",$new_rank_id)->first();
                        $old_rank_data  = Tbl_mlm_stairstep_settings::where("shop_id",$shop_id)->where("stairstep_id",$old_rank_id)->first();

                        $customer_email = Tbl_customer::where("customer_id",$slot_info->slot_owner)->first();
                        $email_content["subject"] = "Rank Upgrade";
                        $email_content["content"] = "Your rank has been upgraded to ".$new_rank_data->stairstep_name;
                        $email_address            = $customer_email->email;
                        // $email_address            = "";

                        $return_mail = Mail_global::send_email(null, $email_content, $shop_id, $email_address);
                    }
                }

            }
        }
    }

    public static function get_email_content_rank($shop_id,$rank_id)
    {
        $content = null;

        if($shop_id == 47)
        {
            if($rank_id == 20)
            {
                $content = Tbl_email_content::where("email_content_key","advancement_to_bronze")->where("shop_id",$shop_id)->first();
            }
            else if($rank_id == 21)
            {
                $content = Tbl_email_content::where("email_content_key","advancement_to_silver")->where("shop_id",$shop_id)->first();
            }
            else if($rank_id == 22)
            {
                $content = Tbl_email_content::where("email_content_key","advancement_to_gold")->where("shop_id",$shop_id)->first();
            }
            else if($rank_id == 23)
            {
                $content = Tbl_email_content::where("email_content_key","advancement_to_platinum")->where("shop_id",$shop_id)->first();
            }
            else if($rank_id == 24)
            {
                $content = Tbl_email_content::where("email_content_key","advancement_to_diamond")->where("shop_id",$shop_id)->first();
            }
        }


        return $content;
    }
}