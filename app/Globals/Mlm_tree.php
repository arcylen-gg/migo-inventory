<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_triangle_repurchase_tree;
use App\Models\Tbl_mlm_triangle_repurchase_slot;

use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;

use App\Globals\Mlm_compute;

class Mlm_tree
{   
    public static function insert_tree_placement($slot_info, $new_slot, $level)
    {
        
        if($slot_info != null)
        {
            $old_level   = $level;
            $upline_info = Tbl_mlm_slot::id($slot_info->slot_placement)->first();
            
            /*CHECK IF TREE IS ALREADY EXIST*/
            if($upline_info)
            {
                $check_if_exist = Tbl_tree_placement::where("placement_tree_child_id",$new_slot->slot_id)
                ->where('placement_tree_level', '=', $level)
                ->where('placement_tree_parent_id', '=', $upline_info->slot_id)
                ->first();
            }
            else
            {
                $check_if_exist = Tbl_tree_placement::where("placement_tree_child_id",$new_slot->slot_id)
                ->where('placement_tree_level', '=', $level)
                ->first();
            }

            if($upline_info)
            {
                if(!$check_if_exist)
                {    
                    $insert['shop_id'] = $slot_info->shop_id;
                    $insert["placement_tree_parent_id"] = $upline_info->slot_id;
                    $insert["placement_tree_child_id"] = $new_slot->slot_id;
                    $insert["placement_tree_position"] = $slot_info->slot_position;
                    $insert["placement_tree_level"] = $level;
                    Tbl_tree_placement::insert($insert);
                }
                $level++;
                Mlm_tree::insert_tree_placement($upline_info, $new_slot, $level);  
            }

            // if(!$check_if_exist)
            // {    
            if($old_level == 1)
            {
                MLM_tree::update_auto_balance_position($new_slot,$old_level);
            }
            // }
        }
    }
    public static function update_auto_balance_position($new_slot,$level)
    {
        $top_slot                            = Tbl_tree_placement::where("placement_tree_child_id",$new_slot->slot_id)->orderBy("placement_tree_level","DESC")->first();
        if($top_slot)
        {  
            if($top_slot->placement_tree_level == 1)
            {
                if($new_slot->slot_position == "left")
                {
                    $auto_balance_position = 2;
                }
                else
                {
                    $auto_balance_position = 3;
                }
            }
            else
            {
                $placement                       = Tbl_mlm_slot::where("slot_id",$new_slot->slot_placement)->first();
                $top_slot                        = Tbl_tree_placement::where("placement_tree_child_id",$new_slot->slot_placement)->orderBy("placement_tree_level","DESC")->first();
                $get_top_level                   = Tbl_tree_placement::where("placement_tree_parent_id",$top_slot->placement_tree_parent_id)->where("placement_tree_child_id",$new_slot->slot_placement)->first();
                if($get_top_level)
                {     
                    $new_level = $get_top_level->placement_tree_level;
                    if($new_slot->slot_position == "left")
                    {
                        $auto_balance_position  = $placement->auto_balance_position + pow(2,$new_level);
                    }
                    else
                    {
                        $auto_balance_position  = $placement->auto_balance_position + (pow(2,$new_level) * 2);
                    }
                }
            }
        }

        if(isset($auto_balance_position))
        {
            $update["auto_balance_position"] = $auto_balance_position;
            Tbl_mlm_slot::where("slot_id",$new_slot->slot_id)->update($update);
        }
    }
    public static function insert_tree_sponsor($slot_info, $new_slot, $level)
    {
        if($slot_info != null)
        {
            $upline_info = Tbl_mlm_slot::id($slot_info->slot_sponsor)->first();
            /*CHECK IF TREE IS ALREADY EXIST*/
            $check_if_exist = null;
            if($upline_info)
            {
                $check_if_exist = Tbl_tree_sponsor::where("sponsor_tree_child_id",$new_slot->slot_id)
                ->where('sponsor_tree_parent_id', '=', $upline_info->slot_id )
                ->first();
            }
            else
            {
                $check_if_exist = Tbl_tree_placement::where("placement_tree_child_id",$new_slot->slot_id)
                ->first();
            }
            if($upline_info)
            {
                    if($upline_info)
                    {
                        if(!$check_if_exist)
                        {
                            $insert['shop_id'] = $new_slot->shop_id;
                            $insert["sponsor_tree_parent_id"] = $upline_info->slot_id;
                            $insert["sponsor_tree_child_id"] = $new_slot->slot_id;
                            $insert["sponsor_tree_level"] = $level;
                            Tbl_tree_sponsor::insert($insert);
                        }
                        $level++;
                        Mlm_tree::insert_tree_sponsor($upline_info, $new_slot, $level);  
                    }
            }
        }
    }
    public static function auto_place_slot_binary_left_to_right_v2($slot_info)
    {
        // tree per level
        $a[1] = 2;
        $a[2] = 4;
        $a[3] = 8;
        $a[4] = 16;
        $a[5] = 32;
        $a[6] = 64;
        $a[7] = 128;
        $a[8] = 256;
        $a[9] = 512;
        $a[10] = 1024;
        $a[11] = 2048;
        $a[12] = 4096;
        $a[13] = 8192;
        $a[14] = 16384;
        $a[15] = 32768;
        $a[16] = 65536;
        $a[17] = 131072;
        $a[18] = 262144;
        // end tree
        $tree = Tbl_tree_placement::where('placement_tree_parent_id', $slot_info->slot_sponsor)
        ->select(DB::raw('count(placement_tree_level ) as count_slot'), DB::raw('tbl_tree_placement.*'))
        ->groupBy(DB::raw('placement_tree_level') )
        ->groupBy('placement_tree_parent_id')
        ->orderBy('placement_tree_level', 'ASC')
        ->get();
        if(count($tree) == 0)
        {
            $update_slot['slot_position'] = 'left';
            $update_slot['slot_placement'] = $slot_info->slot_sponsor;
            Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update_slot);
            $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
            return Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
        }
        else
        {
            $last_level = 0;
            foreach ($tree as $key => $value) 
            {
                // dd($a[$value->placement_tree_level]);
                if($value->count_slot < $a[$value->placement_tree_level])
                {
                    $selected_level = $value->placement_tree_level;
                    $tree = Tbl_tree_placement::where('placement_tree_parent_id', $slot_info->slot_sponsor)
                    ->groupBy('placement_tree_child_id')
                    ->orderBy('placement_tree_child_id', 'ASC')
                    ->where('placement_tree_level', $selected_level -1)
                    ->select(DB::raw('placement_tree_child_id'))
                    ->get()->keyBy('placement_tree_child_id');

                    $count_per_slot = Tbl_tree_placement::whereIn('placement_tree_parent_id', $tree)
                    ->select(DB::raw('count(placement_tree_level ) as count_slot'), DB::raw('tbl_tree_placement.*'))
                    ->groupBy('placement_tree_parent_id')
                    ->orderBy('placement_tree_child_id', 'ASC')
                    ->Where('placement_tree_level', 1)
                    ->get()->keyBy('placement_tree_parent_id');

                    $cut = 0;
                    // dd($count_per_slot);

                    foreach ($tree as $key => $value) 
                    {
                        if($cut == 0)
                        {
                            if(!isset($count_per_slot[$key]))
                            {
                                $update_slot['slot_position'] = 'left';
                                $update_slot['slot_placement'] = $key;
                                Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update_slot);
                                $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
                                return Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
                            }
                            else
                            {
                                if($count_per_slot[$key]->count_slot < 2)
                                {
                                    $cut = 1;
                                }
                            }
                        }
                    }
                    foreach ($count_per_slot as $key => $value) 
                    {
                        if($value->count_slot < 2)
                        {
                            $update_slot['slot_position'] = 'right';
                            $update_slot['slot_placement'] = $key;
                            Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update_slot);
                            $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
                            return Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
                        }
                    }
                }
                else
                {
                    $last_level = $value->placement_tree_level;
                }
            }

            if($last_level != 0)
            {
                
                $tree = Tbl_tree_placement::where('placement_tree_parent_id', $slot_info->slot_sponsor)
                    ->groupBy('placement_tree_child_id')
                    ->orderBy('placement_tree_child_id', 'ASC')
                    ->where('placement_tree_level', $last_level)
                    ->select(DB::raw('placement_tree_child_id'))
                    ->first();
                $update_slot['slot_position'] = 'left';
                $update_slot['slot_placement'] = $tree->placement_tree_child_id;
                Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update_slot);
                $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
                return Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
            }
        }
        
    }
    public static function auto_place_slot_binary_left_to_right($slot_info)
    {
        // $slot_placement_sponsor = Tbl_mlm_slot::id()->membership
        // Get first slot with no left
        // $slot_with_no
        $arr_slot = [];
        $count_last = Tbl_tree_placement::where('shop_id', $slot_info->shop_id)
        ->orderby('placement_tree_parent_id', 'ASC')
        ->get(); 
        foreach($count_last as $key=> $value)
        {
            if(isset($arr_slot[$value->placement_tree_parent_id]))
            {
                if($value->placement_tree_level == 1)
                {
                    $arr_slot[$value->placement_tree_parent_id] += 1;
                }
            }
            else
            {
                if($value->placement_tree_level == 1)
                {
                    $arr_slot[$value->placement_tree_parent_id] = 1;
                }
            }
        }
        $select_slot = 0;
        $select_slot_2 = 0;
        foreach($arr_slot as $key => $value)
        {
            if($value == 1)
            {
                if($select_slot == 0)
                {
                    $select_slot = $key;
                }
            }
            if($value == 2)
            {
                $select_slot_2 = $key;
            }
        }

        if($select_slot == 0)
        {
            $select_slot_3 = 0;
            foreach($count_last as $value)
            {
                if($select_slot_3 == 0)
                {
                    if(!isset($arr_slot[$value->placement_tree_child_id]))
                    {
                        $select_slot_3 = $value->placement_tree_child_id;
                    }
                }
            }
            if($select_slot_3 == 0)
            {
                $slot = Tbl_mlm_slot::where('shop_id', $slot_info->shop_id)->first();

                $update['slot_placement']   = $slot->slot_id;
                $update['slot_position'] = 'left';
                Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update);

                $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
            }
            else
            {
                $update['slot_placement']   = $select_slot_3;
                $update['slot_position'] = 'left';
                Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update);

                $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
            }
        }
        else
        {
            // got the slot
            $update['slot_placement']   = $select_slot;
            $update['slot_position'] = 'right';
            Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update);

            $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
        }
        // dd ($select_slot_3);
        
        Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
    }
    public static function auto_place_slot_binary_auto_balance($slot_info)
    {

        $update_slot_no_placement['slot_placement'] = 0;
        $update_slot_no_placement2 = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update_slot_no_placement);
        $slot_info = Mlm_compute::get_slot_info($slot_info->slot_id);

        $arr_slot = [];
        $arr_slot_all= [];
        $selected_next_placement = 0;
        $count_last = Tbl_tree_placement::where('shop_id', $slot_info->shop_id)
        ->where('placement_tree_parent_id', $slot_info->slot_sponsor)
        ->orderby('placement_tree_parent_id', 'ASC')
        ->get(); 

        if(count($count_last) == 0)
        {
            $selected_next_placement = $slot_info->slot_sponsor;
            $position                = 'left';
        }

        // Get all down line of sponsor
        $slot_s = [];
        foreach($count_last as $key => $value)
        {
            $slot_s[$key] = $value->placement_tree_child_id;
        }

        // select tree in of all downline of sponsor
        // 
        $count_last = Tbl_tree_placement::where('shop_id', $slot_info->shop_id)
        ->where('placement_tree_parent_id', $slot_info->slot_sponsor);
        foreach($slot_s as $key => $value)
        {
            $count_last->orwhere('placement_tree_parent_id', $value);
        }
        $count_last = $count_last->orderby('placement_tree_parent_id', 'ASC')->get();

        // dd(DB::getQueryLog());
        

        // select all with downline
        foreach($count_last as $key=> $value)
        {
            if(isset($arr_slot[$value->placement_tree_parent_id]))
            {
                if($value->placement_tree_level == 1)
                {
                    $arr_slot[$value->placement_tree_parent_id] += 1;
                }
            }
            else
            {
                if($value->placement_tree_level == 1)
                {
                    $arr_slot[$value->placement_tree_parent_id] = 1;
                }
            }
        }
        // select all with no downline
        foreach ($count_last as $key => $value) 
        {
            $arr_slot_all[$value->placement_tree_child_id] = 0;
        }
        

        // combine no downline and with downline
        foreach($arr_slot as $key => $value)
        {
            foreach($arr_slot_all as $key2 => $value2)
            {
                if(!isset($arr_slot[$key2]))
                {
                    $arr_slot[$key2] = 0;   
                }
            }
        }
        $count_array_with_1_downline = 0;

        // sort array ascending
        ksort($arr_slot);
        $fiter_by_level = [];
        // Sort from left to right
            $get_slot_per_level = Tbl_tree_placement::where('shop_id', $slot_info->shop_id)
            ->where('placement_tree_parent_id', $slot_info->slot_sponsor)->orderby('placement_tree_parent_id', 'ASC')->get();

            foreach($arr_slot as $key => $value)
            {
                foreach($get_slot_per_level as $key2 => $value2)
                {
                    if($key == $value2->placement_tree_child_id)
                    {
                        $fiter_by_level[$key] = $value2->placement_tree_level;
                    }
                }
            }
            $filtered = [];
            foreach($fiter_by_level as $key => $value)
            {
                
                foreach($get_slot_per_level as $key2 => $value2)
                {
                    if($key == $value2->placement_tree_child_id)
                    {
                        $filtered[$value][$key] = $value2->placement_tree_position;
                    }
                }
            }
            foreach($filtered as $key => $value)
            {
                $array_keys = array_keys($value);
                array_multisort($value, $array_keys);
                $result = array_combine($array_keys, $value);
                $filtered[$key] = $result;
            }
            $filtered_with_n_level = [];
            foreach($filtered as $key => $value)
            {
                foreach($value as $key2 => $value2)
                {
                    $filtered_with_n_level[$key2] = 0;
                }
            }
            foreach($filtered_with_n_level as $key => $value)
            {
                foreach($arr_slot as $key2 => $value2)
                {
                    if($key == $key2)
                    {
                       $filtered_with_n_level[$key] = $value2; 
                    }
                }
            }
            $arr_slot = $filtered_with_n_level;
        // end


        // count slots with 1 downline
        foreach($arr_slot as $key => $value)
        {
            if($value == 1)
            {
                $count_array_with_1_downline++;
            }
        } 
        $position = 'left';       
        if($count_array_with_1_downline == 0)
        {
            $next = 0;
            // get first slot with no downline
            foreach($arr_slot as $key => $value)
            {
                if($value == 2)
                {
                    $next = 1;
                }
                elseif($value == 0)
                {
                    if($next == 0)
                    {
                        $position                   = 'left';
                        $selected_next_placement    = $key;
                        $next                       = 2;
                    }
                    else if($next == 1)
                    {
                        $position                   = 'left';
                        $selected_next_placement    = $key;
                        $next                       = 2;
                    }
                }
                else
                {
                    if($next == 1)
                    {
                        $position                   = 'left';
                        $selected_next_placement    = $key;
                        $next                       =0;
                    }
                }
            }
        }
        else
        {
            $next = 0;
            $slot_with_one = 0;
            $head = 0;
            foreach($arr_slot as $key => $value)
            {   
                if($head == 0)
                {
                    $head = $key;
                }

                if($value == 1)
                {
                    $next = 1;
                    $slot_with_one = $key;
                }
                else
                {
                    if($next == 1)
                    {
                        $selected_next_placement = $key;
                        $position = 'left';
                        $next =0;
                    }
                }
            }
            // dd($selected_next_placement);
            $head = $slot_info->slot_sponsor;

            $count_last_head = Tbl_tree_placement::where('shop_id', $slot_info->shop_id)
            ->orderby('placement_tree_parent_id', 'ASC')
            ->where('placement_tree_parent_id', $head)
            ->get();

            

            $count_per_level = [];

            $count_per_level_left_right = [];
            foreach($count_last_head as $key => $value)
            {
                if(isset($count_per_level[$value->placement_tree_level]))
                {
                    $count_per_level[$value->placement_tree_level]++;
                }
                else
                {
                    $count_per_level[$value->placement_tree_level] = 1;
                }
                if(isset($count_per_level_left_right[$value->placement_tree_level][$value->placement_tree_position]))
                {
                    $count_per_level_left_right[$value->placement_tree_level][$value->placement_tree_position]++;
                }
                else
                {
                    $count_per_level_left_right[$value->placement_tree_level][$value->placement_tree_position] = 1;
                }
            }

            

            $defendency_level = 0;
            $last_level = 0;
            $sum = [];
            $sum_n = [];
            ksort($count_per_level);
             foreach($count_per_level as $key => $value)
            {
                if($last_level == 0)
                {
                    $sum_dapat = 2;
                }
                else
                {
                    $sum_dapat = $last_level + $last_level;
                }
                $sum_ngayon = $value;
                $sum[$key] = $sum_dapat;
                $sum_n[$key] = $sum_ngayon;
                if($defendency_level == 0)
                {       
                    if($sum_dapat > $sum_ngayon)
                    {
                        $defendency_level = $key - 1;
                    }
                }
                $last_level = $sum_dapat;
            }

            $pos_final_selection = 'left';
            foreach($count_per_level_left_right as $key => $value)
            {
                if($defendency_level + 1 == $key)
                {
                    if(!isset($value['left']))
                    {
                        $value['left'] = 0;
                    }
                    if(!isset($value['right']))
                    {
                        $value['right'] = 0;
                    }
                    if($value['left'] == $value['right'])
                    {
                        $pos_final_selection = 'left';
                    }
                    else if($value['left'] > $value['right'])
                    {
                        $pos_final_selection = 'right';
                    }
                    else
                    {
                        $pos_final_selection = 'right';
                    }
                }
            }

            $arr_level_selected_final = [];
            foreach($count_last_head as $key => $value)
            {
                if($pos_final_selection == $value->placement_tree_position)
                {
                    if($value->placement_tree_level == $defendency_level)
                    {
                        $arr_level_selected_final[$value->placement_tree_child_id] = 0;
                    }
                }
                
            }
            // dd($arr_level_selected_final);
            foreach($count_last as $key => $value)
            {
                if($value->placement_tree_level  == 1)
                {
                    if(isset($arr_level_selected_final[$value->placement_tree_parent_id]))
                    {
                        $arr_level_selected_final[$value->placement_tree_parent_id]++;
                    }        
                }
            }
            $selector = 0;
            $lowest = 2;
            $lowest_old = 2;
            $selected_pos = 0;
            $selector_0 = 0;
            ksort($arr_level_selected_final);
            foreach($arr_level_selected_final as $key => $value)
            {

                $lowest_old = $lowest;
                $lowest = $value;
                if($value == 1)
                {

                    if($selector == 0)
                    {
                        
                        if($lowest <= $lowest_old)
                        {

                            $selector = $key;
                            $selected_pos = $lowest;
                        }
                    }
                }
                if($value == 0)
                {
                    if($selector_0 == 0)
                    {
                        $selector = $key;
                        $selected_pos = $lowest;
                        $selector_0 = $key;
                    }
                }
            }

            $selected_next_placement = $selector;
            if($selected_pos == 0)
            {
                $position                = 'left';
            }
            else
            {
                $position               = 'right';
            }
        }

        if(count($count_last) == 1)
        {
            $selected_next_placement = $slot_info->slot_sponsor;
            $position                = 'right';
        }

        $update['slot_placement']   = $selected_next_placement;
        $update['slot_position']    = $position;
        // dd($update);

        Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update);

        $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
        Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
        // dd($arr_slot);
        // dd($update);
    }
    public static function auto_place_slot_binary_auto_balance_revised($slot_info)
    {
        if($slot_info->slot_sponsor != 0 && $slot_info->slot_sponsor != null)
        {
            $sponsor = Tbl_mlm_slot::where("slot_id",$slot_info->slot_sponsor)->first();
            if($sponsor)
            {
                $condition_update = false;
                $current_level = $sponsor->current_level;
                while($condition_update == false)
                {                 
                    if($current_level == 0)
                    {
                        $check_placement = Tbl_mlm_slot::where("slot_placement",$slot_info->slot_sponsor)->where("slot_position","left")->first();
                        if(!$check_placement)
                        {
                            $slot_placement   = $slot_info->slot_sponsor;
                            $slot_position    = "left";
                            $slot_level       = 0;
                            $condition_update = true;
                            break;
                        }
                        else
                        {
                           $check_placement = Tbl_mlm_slot::where("slot_placement",$slot_info->slot_sponsor)->where("slot_position","right")->first();
                           if(!$check_placement)
                           {
                             $slot_placement   = $slot_info->slot_sponsor;
                             $slot_position    = "right";
                             $slot_level       = 1;
                             $condition_update = true;
                             break;
                           }
                        }
                    }
                    else
                    {
                        $placement_tree = Tbl_tree_placement::where("placement_tree_parent_id",$sponsor->slot_id)->where("placement_tree_level",$current_level)->childslot()->orderBy("tbl_mlm_slot.auto_balance_position","ASC")->get();
                        $current_count  = Tbl_tree_placement::where("placement_tree_parent_id",$sponsor->slot_id)->where("placement_tree_level",$current_level + 1)->childslot()->orderBy("tbl_mlm_slot.auto_balance_position","ASC")->count();
                        $max_count      = pow(2, $current_level + 1);
                        if($current_count < $max_count)
                        {   
                            $condition_right = true;

                            foreach($placement_tree as $placement)
                            {
                                $check_placement = Tbl_mlm_slot::where("slot_placement",$placement->placement_tree_child_id)->where("slot_position","left")->first();
                                if(!$check_placement)
                                {
                                    $slot_placement   = $placement->placement_tree_child_id;
                                    $slot_position    = "left";
                                    $condition_update = true;
                                    $condition_right  = false;
                                    if(($current_count + 1) >= $max_count)
                                    {
                                        $slot_level = $current_level + 1;
                                    }
                                    else
                                    {
                                        $slot_level = $current_level;
                                    }
                                    break;
                                }
                            }


                            if($condition_right == true)
                            {
                                foreach($placement_tree as $placement)
                                {
                                    $check_placement = Tbl_mlm_slot::where("slot_placement",$placement->placement_tree_child_id)->where("slot_position","right")->first();
                                    if(!$check_placement)
                                    {
                                        $slot_placement   = $placement->placement_tree_child_id;
                                        $slot_position    = "right";
                                        $condition_update = true;
                                        $condition_right  = false;
                                        if(($current_count + 1) >= $max_count)
                                        {
                                            $slot_level = $current_level + 1;
                                        }
                                        else
                                        {
                                            $slot_level = $current_level;
                                        }
                                        break;
                                    }
                                }
                            }
                        }
                    }

                    $current_level++;
                }



                if($condition_update == true)
                {
                    $update_sponsor['current_level']  = $slot_level;
                    Tbl_mlm_slot::where('slot_id', $slot_info->slot_sponsor)->update($update_sponsor);

                    // got the slot
                    $update['slot_placement'] = $slot_placement;
                    $update['slot_position']  = $slot_position;

                    Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update);

                    $slot_info_e = Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->first();
                    Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
                }
            }
        }
    }
    public static function triangle_repurchase_tree($slot_info, $new_tree, $level)
    {
        if($slot_info != null)
        {
            $old_level   = $level;
            $upline_info = Tbl_mlm_triangle_repurchase_slot::id($slot_info->repurchase_slot_placement)->first();
            if($upline_info)
            {
                $insert['tree_repurchase_shop_id'] = $slot_info->repurchase_slot_shop_id;
                $insert["tree_repurchase_slot_sponsor"] = $upline_info->repurchase_slot_id;
                $insert["tree_repurchase_slot_child"] = $new_tree->repurchase_slot_id;
                $insert["tree_repurchase_tree_position"] = $slot_info->repurchase_slot_position;
                $insert["tree_repurchase_tree_level"] = $level;
                Tbl_mlm_triangle_repurchase_tree::insert($insert);
                $level++;
                Mlm_tree::triangle_repurchase_tree($upline_info, $new_tree, $level);  
            }
        }
    }
    public static function triangle_repurchase_tree_l_r($slot_info)
    {
        if($slot_info != null)
        {
            $shop_id = $slot_info->repurchase_slot_shop_id;

            $head = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_shop_id', $shop_id)->first();
            $get_last_level = Tbl_mlm_triangle_repurchase_tree::where('tree_repurchase_shop_id', $shop_id)
            // ->where('tree_repurchase_slot_sponsor', $head->repurchase_slot_id)
            ->groupBy('tree_repurchase_tree_level')
            ->orderBy('tree_repurchase_tree_level', 'DESC')
            ->first();
            

            $last_level = 0;
            if(isset($get_last_level->tree_repurchase_tree_level))
            {
                if($get_last_level->tree_repurchase_tree_level != null)
                {
                    $last_level = $get_last_level->tree_repurchase_tree_level;
                }
            }
            if($last_level == 0 || $last_level == 1)
            {
                $slot = $head;

                $update['repurchase_slot_placement'] = $slot->repurchase_slot_id;

                if($last_level == 0)
                {
                    $update['repurchase_slot_position'] = 'left';
                }
                else
                {
                    $update['repurchase_slot_position'] = 'right';

                    $get_last_placement = Tbl_mlm_triangle_repurchase_tree::where('tree_repurchase_shop_id', $shop_id)
                    ->where('tree_repurchase_slot_sponsor', $head->repurchase_slot_id)
                    ->where('tree_repurchase_tree_level', $last_level)
                    ->get()->last();

                    $update = Mlm_tree::get_last_placement_triangle($get_last_placement->tree_repurchase_slot_sponsor, $last_level, $shop_id);

                }
                // Update Genealogy
                Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_id', $slot_info->repurchase_slot_id)->update($update);
                $slot_info = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_id', $slot_info->repurchase_slot_id)->first();
                Mlm_tree::triangle_repurchase_tree($slot_info, $slot_info, 1);
                // End
            }
            else
            {
                $get_last_placement = Tbl_mlm_triangle_repurchase_tree::where('tree_repurchase_shop_id', $shop_id)
                ->where('tree_repurchase_slot_sponsor', $head->repurchase_slot_id)
                ->where('tree_repurchase_tree_level', $last_level)
                ->get()->last();

                $update = Mlm_tree::get_last_placement_triangle($get_last_placement->tree_repurchase_slot_sponsor, $last_level, $shop_id);

                // Update Genealogy
                Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_id', $slot_info->repurchase_slot_id)->update($update);
                $slot_info = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_id', $slot_info->repurchase_slot_id)->first();
                Mlm_tree::triangle_repurchase_tree($slot_info, $slot_info, 1);
                // End

            }
        }
    }
    public static function get_last_placement_triangle($last_place, $last_level, $shop_id)
    {

        $count_level_1 = Tbl_mlm_triangle_repurchase_tree::where('tree_repurchase_shop_id', $shop_id)
        ->where('tree_repurchase_slot_sponsor', $last_place)
        ->where('tree_repurchase_tree_level', 1)
        ->count();
        if($count_level_1 == 0)
        {
            $update['repurchase_slot_placement'] = $last_place;
            $update['repurchase_slot_position'] = 'left';

            return $update;
        }
        else if($count_level_1 == 1)
        {
            $update['repurchase_slot_placement'] = $last_place;
            $update['repurchase_slot_position'] = 'right';

            return $update;
        }
        else if($count_level_1 == 2)
        {

            $get_last_placement = Tbl_mlm_triangle_repurchase_slot::where('repurchase_slot_id', '>',  $last_place)
            ->orderBy('repurchase_slot_id', 'ASC')
            ->first();    
            return Mlm_tree::get_last_placement_triangle($get_last_placement->repurchase_slot_id, $last_level, $shop_id);    
        }    
    }
}