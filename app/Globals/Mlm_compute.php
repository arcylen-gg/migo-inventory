<?php
namespace App\Globals;

use App\Models\Tbl_membership_package;
use App\Models\Tbl_membership;
use App\Models\Tbl_mlm_plan;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_item_code;
use App\Models\Tbl_mlm_binary_setttings;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_slot_points_log;
use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;

use Schema;
use Session;
use DB;
use Carbon\Carbon;

use App\Globals\Mlm_tree;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_complan_manager_cd;
use App\Globals\Mlm_complan_manager_fs;
use App\Globals\Mlm_gc;
class Mlm_compute
{   
    public static function reset_all_slot()
    {
        // for testing purpose only
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tbl_mlm_slot')->delete();
        DB::statement('ALTER TABLE tbl_mlm_slot AUTO_INCREMENT  = 1');
        DB::table('tbl_mlm_gc')->delete();
        DB::table('tbl_tree_sponsor')->delete();
        DB::table('tbl_tree_placement')->delete();
        DB::table('tbl_mlm_slot_wallet_log')->delete();
        DB::table('tbl_membership_code')->delete();
        DB::table('tbl_membership_code_invoice')->delete();
        DB::table('tbl_mlm_matching_log')->delete();
        DB::table('tbl_mlm_slot_points_log')->delete();
        DB::table('tbl_voucher')->delete();
        DB::table('tbl_voucher_item')->delete();
        DB::table('tbl_item_code')->delete();
        DB::table('tbl_item_code_invoice')->delete();
        DB::table('tbl_mlm_discount_card_log')->delete();
        DB::table('tbl_mlm_encashment_process')->delete();
        DB::table('tbl_mlm_slot_wallet_log_refill')->delete();
        DB::table('tbl_mlm_slot_wallet_log_transfer')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
	public static function entry($slot_id, $type = 0)
	{
        $slot_info = Mlm_compute::get_slot_info($slot_id);
        // slot_info must have membership info and membership_points;
        // <-- error if not -->
	    // Sponsor Tree
        // not redundant, needed two slot_info for retainment of loop
        /* CHECK IF IT IS A SLOT CREATION OR USED FOR UPGRADING SLOT */
        /* 0 = NEW SLOT , 1 = UPGRADE SLOT */
        if($type == 0)
        {
            Mlm_tree::insert_tree_sponsor($slot_info, $slot_info, 1); /* TREE RECORD FOR SPONSORSHIP GENEALOGY TREE */    
            // check if binart is active 
                $plan_settings_count = Tbl_mlm_plan::where('shop_id', $slot_info->shop_id)
                ->where('marketing_plan_code', 'BINARY')
                ->where('marketing_plan_enable', 1)
                ->where('marketing_plan_trigger', 'Slot Creation')
                ->count();
                if($plan_settings_count != 0)
                {
                    $tbl_mlm_binary_setttings = Tbl_mlm_binary_setttings::where('shop_id', $slot_info->shop_id)->first();
                    if($tbl_mlm_binary_setttings)
                    {
                        if($tbl_mlm_binary_setttings->binary_settings_placement == 0)
                        {
                            // manual placement
                            Mlm_tree::insert_tree_placement($slot_info, $slot_info, 1); /* TREE RECORD FOR BINARY GENEALOGY TREE */
                        }
                        else
                        {
                            // auto placement
                            if($tbl_mlm_binary_setttings->binary_settings_auto_placement == 'left_to_right')
                            {
                                $a =  Mlm_tree::auto_place_slot_binary_left_to_right_v2($slot_info);
                                // $a =  Mlm_tree::auto_place_slot_binary_left_to_right($slot_info);
                            }
                            else if($tbl_mlm_binary_setttings->binary_settings_auto_placement == "auto_balance")
                            {
                            // $a = Mlm_tree::auto_place_slot_binary_auto_balance($slot_info);
                                $a = Mlm_tree::auto_place_slot_binary_auto_balance_revised($slot_info);
                            }
                        }
                    }
                }
            // end Tree
        }
        

        // Mlm Computation Plan
            $plan_settings = Tbl_mlm_plan::where('shop_id', $slot_info->shop_id)
            ->where('marketing_plan_enable', 1)
            ->where('marketing_plan_trigger', 'Slot Creation')
            ->get();

            if($slot_info->slot_status == 'PS')
            {
                foreach($plan_settings as $key => $value)
                {
                    $plan = strtolower($value->marketing_plan_code);
                    $a = Mlm_complan_manager::$plan($slot_info);
                }
            }
            else if($slot_info->slot_status == 'CD')
            {
                $a = Mlm_complan_manager_cd::enter_cd($slot_info);

            }
            else if($slot_info->slot_status == 'FS')
            {
                // no income for fs.
            }

            if($slot_info->slot_date_computed == "0000-00-00 00:00:00")
            {
                $update_slot["slot_date_computed"] = Carbon::now();
                Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$slot_info->shop_id)->update($update_slot);
            }
            // check if there are cd graduate
            $b = Mlm_complan_manager_cd::graduate_check($slot_info);
            // $c = Mlm_gc::slot_gc($slot_id);

            Mlm_compute::set_slot_nick_name_2($slot_info);
            // End Computation Plan
	}
    public static function get_slot_info($slot_id)
    {
        $slot_info = Tbl_mlm_slot::where('slot_id', $slot_id)->membership()->membership_points()->customer()->first();
        return $slot_info;
    }

    public static function repurchase($slot_info,$item_code_id)
    {

        $plan_settings = Tbl_mlm_plan::where('shop_id', $slot_info->shop_id)
        ->where('marketing_plan_enable', 1)
        ->where('marketing_plan_trigger', 'Product Repurchase')
        ->get();

        foreach($plan_settings as $key => $value)
        {
            $plan = strtolower($value->marketing_plan_code);
            Mlm_complan_manager_repurchase::$plan($slot_info,$item_code_id);
        }
    }

    public static function repurchasev2($slot_id,$shop_id,$data)
    {
        $slot_info     = Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->first();
        $plan_settings = Tbl_mlm_plan::where('shop_id', $slot_info->shop_id)
        ->where('marketing_plan_enable', 1)
        ->where('marketing_plan_trigger', 'Product Repurchase')
        ->get();

        foreach($plan_settings as $key => $value)
        {
            $points_title  = $value->marketing_plan_code; 

            if($points_title == "STAIRSTEP")
            {
                if(isset($data["STAIRSTEP"]) && isset($data["STAIRSTEP_GROUP"]))
                {    
                    $stairstep_points       = $data["STAIRSTEP"];
                    $stairstep_group_points = $data["STAIRSTEP_GROUP"];
                    $plan                   = strtolower($points_title);
                    Mlm_complan_manager_repurchasev2::$plan($slot_info,$stairstep_points,$stairstep_group_points);
                }
            }            
            else if($points_title == "RANK")
            {
                $rank_points       = $data["RANK"];
                $rank_group_points = $data["RANK_GROUP"];
                $plan              = strtolower($points_title);
                Mlm_complan_manager_repurchasev2::$plan($slot_info,$rank_points,$rank_group_points);
            }
            else if($points_title == "BROWN_REPURCHASE")
            {      
                $price              = $data["price"];
                $plan               = strtolower($points_title);
                Mlm_complan_manager_repurchasev2::$plan($slot_info, $price);
            }           
            else if($points_title == "REPURCHASE_CASHBACK")
            {      
                $cashback              = $data["REPURCHASE_CASHBACK"];
                $rank_cashback         = $data["RANK_REPURCHASE_CASHBACK"];
                $rank_cashback_points  = $data["REPURCHASE_CASHBACK_POINTS"];
                $plan                  = strtolower($points_title);
                Mlm_complan_manager_repurchasev2::$plan($slot_info, $cashback,$rank_cashback,$rank_cashback_points);
            }
            else
            {
                if($points_title != "TRIANGLE_REPURCHASE")
                {  
                    $points = $data[$points_title];
                    $plan   = strtolower($value->marketing_plan_code);
                    Mlm_complan_manager_repurchasev2::$plan($slot_info,$points);
                }
            }
        }
    }

    public static function give_voucher_v2($membership_code_id)
    {
        
    }

    public static function give_voucher($membership_code_id)
    {

        // change plan
        if($invoice_id != null)
        {
            $item_invoice = Tbl_membership_code_invoice::where('membership_code_invoice_id', $item_codes)->first();
            
            $item_codes = Tbl_membership_code::where('membership_code_id', $membership_code_id)
            ->package()->membership()->customer()
            ->first();
            
            if($item_invoice != null)
            {
                // dd($item_codes);
                foreach($item_codes as $key => $value)
                {
                    $package_item  = Tbl_membership_package_has::where('membership_package_id', $value->membership_package_id)->get();
                    if($package_item)
                    {
                        $data["item_id"] = [];
                        foreach($package_item as $key2 => $value2)
                        {
                            $data["item_id"][$item_invoice->item_id] = $value2->item_id;
                            $data["quantity"][$item_invoice->item_id] = $value2->membership_package_has_quantity;
                        }
                        if($data["item_id"] != null)
                        {
                            $data['customer_id']                             = $item_invoice->membership_code_paid;
                            $data['item_code_customer_email']                = $item_invoice->membership_code_customer_email;
                            $data['item_code_paid']                          = $item_invoice->membership_code_paid;
                            $data['item_code_product_issued']                = $item_invoice->membership_code_product_issued;
                        }
                        $shop_id = $item_invoice->shop_id;
                        // $a = Item_code::add_code($data, $shop_id);
                    }
                }
            }
        }
    }
    public static function give_product_code()
    {

    }
    public static function get_downline($count, $slot_id)
    {
        $all_slot = Tbl_mlm_slot::where('shop_id', 1)
        ->orderBy('slot_id', 'ASC')
        ->get();
        foreach($all_slot as $key => $value)
        {
            $count_downline = Tbl_tree_sponsor::where('sponsor_tree_parent_id', $value->slot_id)
            ->where('sponsor_tree_level', 1)->count();
            if($count_downline >= $count)
            {
                // return $value->slot_id++;
            }
            else
            {
                return $value->slot_id;
            }
        }
    }
    public static function create_slot_simulate($slot_no, $downline_count)
    {
        $membership_id = [7];
        $membership_count_id = count($membership_id) - 1;
        $random_membership = rand (0 , $membership_count_id );

        $customer = [300, 308];
        $random_customer = rand ( 300 , 308 );

        $shop_id = 5;

        // slot head
        $insert['slot_no'] = Mlm_plan::set_slot_no();
        $insert['shop_id'] = $shop_id;
        $insert['slot_owner'] = $random_customer;
        $insert['slot_sponsor'] = 0;
        $insert['slot_created_date'] = Carbon::now();
        $insert['slot_membership'] = $membership_id[$random_membership];
        $insert['slot_status'] = 'PS';
        $insert['slot_placement'] = 'left';
        $id = Tbl_mlm_slot::insertGetId($insert);
        Mlm_compute::entry($id);
        // end slot head
        $sponsor = 1;
        $sponsor_count = 1;
        for($i = 2; $i < $slot_no; $i++)
        {
            
            $limit = $downline_count -1;
            if($sponsor == 1 )
            {
              $limit = $downline_count;  
            }
            if($sponsor_count <= $limit)
            {
                $slot_s[$i] = $sponsor;
                $sponsor_count++;
            }
            else
            {
                $sponsor++;
                $slot_s[$i] = $sponsor;
                $sponsor_count= 1;
            }
        }

        $slot_sponsor = 1;
        for($i = 2; $i < $slot_no; $i++)
        {

            $random_membership = rand ( 0 , $membership_count_id );
            $random_customer = rand ( 300 , 308 );
            $insert['slot_no'] = Mlm_plan::set_slot_no();
            $insert['shop_id'] = $shop_id;
            $insert['slot_owner'] = $random_customer;
            $insert['slot_created_date'] = Carbon::now();
            $insert['slot_membership'] = $membership_id[$random_membership];
            $insert['slot_status'] = 'PS';

            // $slot_sponsor = Mlm_compute::get_downline($downline_count, $slot_sponsor);
            if ($i % 2 == 0) 
            {
              $slot_sponsor = $i/2;
            }
            else
            {
                $slot_sponsor = ($i - 1)/2;
            }
            $slot_sponsor = $slot_s[$i];
            $insert['slot_sponsor'] = 1;
            $id = Tbl_mlm_slot::insertGetId($insert);

            Mlm_compute::entry($id);
            
        }

    }
    public static function computer($shop_id)
    {
        $d['all_slot'] = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $shop_id)
        ->orderBy('tbl_mlm_slot.slot_id')->membership()->membership_points()->customer()
        ->get();
        foreach($d['all_slot'] as $key => $value )
        {
            $count_sponsor = Tbl_mlm_slot_wallet_log::where('wallet_log_slot_sponsor', $value->slot_id)->count();
            if($count_sponsor === 0)
            {
                Mlm_compute::entry($value->slot_id);
                $count_new = Tbl_mlm_slot_wallet_log::where('wallet_log_slot_sponsor', $value->slot_id)->count();
                if($count_new === 0)
                {
                    $a = Mlm_compute::entry($value->slot_id);
                }
            }
        }  
    }
    public static function simulate_perfect()
    {
        ini_set('max_execution_time', 60000);
        $slot_no = 5000;
        $downline_count = 500;
        $shop_id = 5;
        Mlm_compute::reset_all_slot();
        Mlm_compute::create_slot_simulate($slot_no, $downline_count);
        // Mlm_compute::computer($shop_id);
        $d['all_slot'] = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $shop_id)
        ->orderBy('tbl_mlm_slot.slot_id')->membership()->membership_points()->customer()
        ->get();

        $membership = [];
        $membership_price = [];
        $plan_settings = Tbl_mlm_plan::where('shop_id', $shop_id)->where('marketing_plan_enable', 1)->get();
        $income = [];
        $points = [];    
        $total = 0;

        foreach($d['all_slot'] as $key => $value)
        {
            if(isset($membership[$value->slot_membership]))
            {
                $membership[$value->slot_membership]++;
                $membership_price[$value->slot_membership] += $value->membership_price;
                $total += $value->membership_price;
            }
            else
            {
                $membership[$value->slot_membership] = 1;
                $membership_price[$value->slot_membership] = $value->membership_price;
                $total += $value->membership_price;
            }
            foreach($plan_settings as $key2 => $value2)
            {
                $income[$value->slot_id][$value2->marketing_plan_code] = 
                Tbl_mlm_slot_wallet_log::where('wallet_log_plan', $value2->marketing_plan_code)
                ->where('wallet_log_slot', $value->slot_id)->sum('wallet_log_amount');

                $points[$value->slot_id][$value2->marketing_plan_code] = Tbl_mlm_slot_points_log::where('points_log_complan', $value2->marketing_plan_code)
                ->where('points_log_slot', $value->slot_id)->sum('points_log_points');
            }
        }
        $membership_info = [];
        foreach($membership as $key => $value)
        {
            $membership_info[$key] = Tbl_membership::where('membership_id', $key)->first();
        }

        $data['membership'] = $membership;
        $data['membership_price'] = $membership_price;
        $data['membership_info'] = $membership_info;
        $data['no_of_slot'] = $slot_no;
        $data['income'] = $income;
        $data['points'] = $points;
        $data['all_slot'] = $d['all_slot'];
        $data['total_cashin'] = $total; 
        return view('member.mlm_slot.simulate', $data);
    }
    public static function set_slot_nick_name_2($slot_info)
    {
        $count_customer = Tbl_mlm_slot::where('slot_owner', $slot_info->slot_owner)
        ->where('slot_defaul', 1)
        ->count();
        if($count_customer == 0)
        {
            $update['slot_defaul'] = 1;

            // $last_name = strtolower(substr($slot_info->last_name, 0, 6));
            // $first_name = strtolower(substr($slot_info->first_name, 0, 3));
            // $nickname = $last_name . '.' . $first_name;
            $nickname = $slot_info->mlm_username;
            $update['slot_nick_name'] = $nickname;

            Tbl_mlm_slot::where('slot_id', $slot_info->slot_id)->update($update);
        }
    }
    public static function set_slot_nick_name($slot_info)
    {

    }
    public static function get_label_plan($plan, $shop_id)
    {
        $plan = Tbl_mlm_plan::where('shop_id', $shop_id)->where('marketing_plan_code', $plan)->first();

        if($plan)
        {
                $label = $plan->marketing_plan_label;
                return $label;
        }
        else
        {
            $label = 'Earnings';
            return  $label;
        }
    }
}