<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_membership_points;
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
use App\Models\Tbl_mlm_item_points;
use App\Models\Tbl_mlm_matching;
use App\Models\Tbl_mlm_matching_log;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_mlm_complan_executive_settings;
use App\Models\Tbl_mlm_leadership_settings;
use App\Models\Tbl_mlm_indirect_points_settings;
use App\Models\Tbl_mlm_discount_card_log;
use App\Models\Tbl_mlm_discount_card_settings;
use App\Models\Tbl_mlm_binary_report;
use App\Globals\Mlm_gc;
use App\Models\Tbl_mlm_gc;
use App\Models\Tbl_mlm_binary_pairing_log;
use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;

use Carbon\Carbon;

use App\Globals\Mlm_compute;
use App\Globals\Mlm_slot_log;
use App\Globals\Membership_code;
use App\Globals\Binary_pairing;
class Binary_pairing
{   
    public static function binary_gc($binary_advance_pairing,$current_pair)
    {
        if($binary_advance_pairing->binary_settings_gc_enable == 'enable')
        {
            if($binary_advance_pairing->binary_settings_gc_every_pair != 0)
            {
                $modulus = ($current_pair%$binary_advance_pairing->binary_settings_gc_every_pair);
            } 
            else
            {
                $modulus = 1;
            }
        }
        else
        {
            $modulus = 1;
        }
        return $modulus;
    }
    public static function setting_cycle_no_1($check_date_is_today, $slot, $value2, $gc_count, $current_gc, $current_pair, $earning, $flush, $modulus)
    {
        // check if now and pairs per day are equal
        $flush = $flush;
        $gc_count = $gc_count;
        $current_gc = $current_gc;
        $current_pair = $current_pair;
        $earning = $earning;
        $current_pairs_per_date = Carbon::now();
        $modulus = $modulus;

        if($check_date_is_today == 1)
        {

            // check pairing
            if($slot->membership_points_binary_max_pair < $current_pair)
            {
                // add to flushout
                $flush += $value2->pairing_bonus;
            }
            else
            {
                // check if gc pair
                if($modulus == 0)
                {
                    // add gc 
                    $gc_count = $gc_count + 1;
                    $current_gc             = $current_gc   + 1;
                }
                else
                {
                    // add income
                    $earning += $value2->pairing_bonus;
                    $current_gc             = $current_gc   + 1;
                }
            }

        }
        // else pairs today not equal / reset pairing
        else
        {
            // reset pairing to 1
            $current_pair   = 1;
            $gc_count = 0;
            $current_pairs_per_date = Carbon::now();

            if($modulus == 0)
            {
                // add gc 
                $gc_count = $gc_count + 1;
                $current_gc             = $current_gc   + 1;
            }
            else
            {
                // add income
                $earning += $value2->pairing_bonus;
                $current_gc             = $current_gc   + 1;
            }
        }

        $data['flush']  = $flush;
        $data['gc_count']  = $gc_count;
        $data['current_gc']  = $current_gc;
        $data['current_pair']  = $current_pair;
        $data['earning']  = $earning;

        return $data;
    }
    public static function setting_cycle_no_2($check_date_is_today, $slot, $value2, $gc_count, $current_gc, $current_pair, $earning, $flush, $modulus)
    {
        $flush = $flush;
        $gc_count = $gc_count;
        $current_gc = $current_gc;
        $current_pair = $current_pair;
        $earning = $earning;
        $current_pairs_per_date = Carbon::now();
        $modulus = $modulus;
        $current_hour           = intval(Carbon::now()->format('G'));
        if($check_date_is_today == 1)
        {
            if($current_hour < 12)
            {
                // cycle 1
                $current_pairs_per_date_hour           = Carbon::parse($current_pairs_per_date)->format('G');
                if($current_pairs_per_date_hour < 12)
                {
                    // continue pairing
                    // check pairing
                    if($slot->membership_points_binary_max_pair < $current_pair)
                    {

                        // flush
                        $flush += $value2->pairing_bonus;

                    }
                    else
                    {
                        if($modulus == 0)
                        {
                            // add gc 
                            $current_gc             = $current_gc   + 1;
                            $gc_count = $gc_count + 1;
                        }
                        else
                        {
                            // add income
                            $earning += $value2->pairing_bonus;
                            $current_gc             = $current_gc   + 1;
                        }
                    }
                }
                else
                {   
                    // reset
                    $current_pair = 1;
                    $current_pairs_per_date =  Carbon::now();

                    // check pairing
                    if($slot->membership_points_binary_max_pair < $current_pair)
                    {
                        // flush
                        $flush += $value2->pairing_bonus;
                    }
                    else
                    {
                        if($modulus == 0)
                        {
                            // add gc 
                            $gc_count               = $gc_count + 1;
                            $current_gc             = $current_gc   + 1;
                        }
                        else
                        {
                            // add income
                            $earning += $value2->pairing_bonus;
                            $current_gc             = $current_gc   + 1;
                        }
                    }
                }
            }
            else if($current_hour >= 12)
            {
                // cycle 2
                $current_pairs_per_date_hour           = Carbon::parse($current_pairs_per_date)->format('G');
                if($current_pairs_per_date_hour >= 12)
                {
                    // continue pairing
                    // check pairing
                    if($slot->membership_points_binary_max_pair < $current_pair)
                    {
                        // flush
                        $flush += $value2->pairing_bonus;
                    }
                    else
                    {
                        if($modulus == 0)
                        {
                            // add gc 
                            $gc_count               = $gc_count + 1;
                            $current_gc             = $current_gc   + 1;
                        }
                        else
                        {
                            // add income
                            $earning               += $value2->pairing_bonus;
                            $current_gc             = $current_gc   + 1;
                        }
                    }
                }
                else
                {
                    // reset
                    $current_pair = 1;
                    $current_pairs_per_date         = Carbon::now();

                    // check pairing
                    if($slot->membership_points_binary_max_pair < $current_pair)
                    {
                        // flush
                        $flush                     += $value2->pairing_bonus;
                    }
                    else
                    {
                        if($modulus == 0)
                        {
                            // add gc 
                            $gc_count               = $gc_count + 1;
                            $current_gc             = $current_gc   + 1;
                        }
                        else
                        {
                            // add income
                            $earning += $value2->pairing_bonus;
                            $current_gc             = $current_gc   + 1;
                        }
                    }
                }
            }
        }
        else
        {
            // reset
            $current_pair = 1;
            $current_pairs_per_date =  Carbon::now();

            // check pairing
            if($slot->membership_points_binary_max_pair < $current_pair)
            {
                // flush
                $flush += $value2->pairing_bonus;
            }
            else
            {
                if($modulus == 0)
                {
                    // add gc 
                    $gc_count               = $gc_count + 1;
                    $current_gc             = $current_gc   + 1;
                }
                else
                {
                    // add income
                    $earning               += $value2->pairing_bonus;
                    $current_gc             = $current_gc   + 1;
                }
            }
        }
        $data['flush']  = $flush;
        $data['gc_count']  = $gc_count;
        $data['current_gc']  = $current_gc;
        $data['current_pair']  = $current_pair;
        $data['earning']  = $earning;

        return $data;
    }
    
}