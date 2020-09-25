<?php

namespace App\Globals;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use Redirect;
use Response;
use View;

use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_customer;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_user;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_binary_setttings;
use App\Models\Tbl_membership;

use App\Globals\Item;
use App\Globals\Mlm_plan;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_complan_manager_cd;
use App\Globals\Mlm_tree;
use App\Globals\Membership_code;
use App\Globals\Settings;
use App\Globals\Mlm_voucher;
use App\Globals\Mlm_slot_log;
use App\Globals\Item_code;
use App\Globals\Mlm_complan_manager_repurchase;
use App\Globals\Mlm_genealogy;
class Mlm_genealogy
{
    public static function tree($slot_id, $shop_id)
    {
        // $slot_id           = Request::input("id");
        // $shop_id           = Mlm_genealogy::user_info->shop_id;
        $data["slot"]      = Tbl_mlm_slot::membership()->customer()->where("tbl_mlm_slot.shop_id",$shop_id)->where("slot_id",$slot_id)->first();
        $data['l']         = Tbl_tree_placement::where('placement_tree_parent_id',$slot_id)->where('placement_tree_position','left')->count();
        $data['r']         = Tbl_tree_placement::where('placement_tree_parent_id',$slot_id)->where('placement_tree_position','right')->count();
        if($data["slot"])
        {
             $data["downline"] = Mlm_genealogy::downline($slot_id);   
        }
        else
        {
            return Redirect::to("/member/mlm/slot");
        }
        $data['format'] = Request::input("mode");
        return view('member.mlm_slot.mlm_slot_genealogy', $data);
    }
    public static function downline($x = 0)
    {
        $format = Request::input("mode");

        if($x == 0)
        {
            $slot_id = Request::input("x");
        }
        else
        {
            $slot_id = $x;
        }

        $return = "<ul>";

        if($format == "binary")
        {
            $return .= Mlm_genealogy::binary_downline($slot_id);
        }
        else
        {
            $return .= Mlm_genealogy::unilevel_downline($slot_id);
        }

        $return .= "</ul>";

        if($x == 0)
        {
            return json_encode($return);
        }
        else
        {
            return $return;
        }   
    }
    public static function binary_downline($slot_id)
    {
        $left_info  = Tbl_mlm_slot::where("slot_placement", $slot_id)->where("slot_position", "left")->membership()->customer()->first();
        $right_info = Tbl_mlm_slot::where("slot_placement", $slot_id)->where("slot_position", "right")->membership()->customer()->first(); 

        $tree_string = "";
        $tree_string .= Mlm_genealogy::downline_format($left_info,'Left',$slot_id);
        $tree_string .= Mlm_genealogy::downline_format($right_info,'Right',$slot_id);

        return $tree_string;
    }
    public static function unilevel_downline($slot_id)
    {
        $_info = Tbl_mlm_slot::where("slot_sponsor", $slot_id)->membership()->customer()->get();
        $count = Tbl_mlm_slot::where("slot_sponsor", $slot_id)->membership()->customer()->count();

        $tree_string = "";

        if($count != 0)
        {
            foreach($_info as $info)
            {
                $tree_string .= Mlm_genealogy::downline_format_unilevel($info);  
            }
        }
        else
        {
            $tree_string .= '<li class="width-reference">
                                <span class="parent parent-reference VC">
                                    <div class="id">+</div>
                                </span>
                            </li>';
        }

        
        return $tree_string;
    }
    public static function downline_format_unilevel($slot_info,$position = null,$placement = null)
    {

        if($slot_info)
        {
            $l = Tbl_tree_placement::where('placement_tree_parent_id',$slot_info->slot_id)->where('placement_tree_position','left')->count();
            $r = Tbl_tree_placement::where('placement_tree_parent_id',$slot_info->slot_id)->where('placement_tree_position','right')->count();
            

            $str_slot = '<span class="downline parent parent-reference PS SILVER" x="' . $slot_info->slot_id . '">';    



            if($slot_info->image == "")
            {
                return  '<li class="width-reference">'.$str_slot.
                                    '<div id="info">
                                        <div id="photo">
                                            <img src="/assets/slot_genealogy/member/img/default-image.jpg" alt="" />
                                        </div>
                                        <div id="cont">
                                            <div>' . strtoupper($slot_info->first_name) . ' </div>
                                            <b>' . $slot_info->membership_name . ' </b>
                                        </div>
                                        <div>' . $slot_info->slot_status . '</div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="id">' . $slot_info->slot_no . '</div>
                                </span>
                                <i class="downline-container"></i>
                            </li>';
            }
            else
            {
                return  '<li class="width-reference">'.$str_slot.
                                    '<div id="info">
                                        <div id="photo">
                                            <img src="/assets/slot_genealogy/member/img/default-image.jpg">
                                        </div>
                                        <div id="cont">
                                            <div>' . strtoupper($slot_info->first_name) . ' </div>
                                            <b>' . $slot_info->membership_name . ' </b>
                                        </div>
                                        <div>' . $slot_info->slot_status . '</div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="id">' . $slot_info->slot_id . '</div>
                                </span>
                                <i class="downline-container"></i>
                            </li>';             
            }

        }
        else if($position) 
        {
            $slot_info = Tbl_mlm_slot::where('slot_id',$placement)->customer()->first();

            return  '   <li class="width-reference">
                            <span class="positioning parent parent-reference VC" position="'.$position.'" placement="'.$placement.'" y="'.$slot_info->first_name.'">
                                <div class="id">+</div>
                            </span>
                        </li>';
        }
        else
        {
            return  '   <li class="width-reference">
                            <span class="parent parent-reference VC">
                                <div class="id">+</div>
                            </span>
                        </li>';
        }
    }
    public static function downline_format($slot_info,$position = null,$placement = null)
    {

        if($slot_info)
        {
            $l = Tbl_tree_placement::where('placement_tree_parent_id',$slot_info->slot_id)->where('placement_tree_position','left')->count();
            $r = Tbl_tree_placement::where('placement_tree_parent_id',$slot_info->slot_id)->where('placement_tree_position','right')->count();
            

            $str_slot = '<span class="downline parent parent-reference PS SILVER" x="' . $slot_info->slot_id . '">';    



            if($slot_info->image == "")
            {
                return  '<li class="width-reference">'.$str_slot.
                                    '<div id="info">
                                        <div id="photo">
                                            <img src="/assets/slot_genealogy/member/img/default-image.jpg" alt="" />
                                        </div>
                                        <div id="cont">
                                            <div>' . strtoupper($slot_info->first_name) . ' </div>
                                            <b>' . $slot_info->membership_name . ' </b>
                                        </div>
                                        <div>' . $slot_info->slot_status . '</div>
                                        <div>' . "L:".$l." R:".$r.'</div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="id">' . $slot_info->slot_no . '</div>
                                </span>
                                <i class="downline-container"></i>
                            </li>';
            }
            else
            {
                return  '<li class="width-reference">'.$str_slot.
                                    '<div id="info">
                                        <div id="photo">
                                            <img src="/assets/slot_genealogy/member/img/default-image.jpg">
                                        </div>
                                        <div id="cont">
                                            <div>' . strtoupper($slot_info->first_name) . ' </div>
                                            <b>' . $slot_info->membership_name . ' </b>
                                        </div>
                                        <div>' . $slot_info->slot_status . '</div>
                                        <div>' . "L:".$l."</br>R:".$r.'</div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="id">' . $slot_info->slot_id . '</div>
                                </span>
                                <i class="downline-container"></i>
                            </li>';             
            }

        }
        else if($position) 
        {
            $slot_info = Tbl_mlm_slot::where('slot_id',$placement)->customer()->first();

            return  '   <li class="width-reference">
                            <span class="positioning parent parent-reference VC" position="'.$position.'" placement="'.$placement.'" y="'.$slot_info->first_name.'">
                                <div class="id">+</div>
                            </span>
                        </li>';
        }
        else
        {
            return  '   <li class="width-reference">
                            <span class="parent parent-reference VC">
                                <div class="id">+</div>
                            </span>
                        </li>';
        }
    }
}