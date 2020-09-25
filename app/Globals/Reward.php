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
use App\Models\Tbl_membership_code;
use App\Http\Controllers\Member\MLM_MembershipController;
use App\Http\Controllers\Member\MLM_ProductController;
use App\Models\Tbl_customer;
use Schema;
use Session;
use DB;
use Carbon\Carbon;
use Validator;

use App\Globals\Mlm_tree;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_complan_manager_cd;
use App\Globals\Mlm_complan_manager_fs;
use App\Globals\Mlm_gc;
class Reward
{   
    public static function generate_membership_code($customer_id, $membership_id = null, $return = null)
    {
        // Note Membership id = membership code package id
        $customer = Tbl_customer::where('customer_id', $customer_id)->first();

        if($customer)
        {
            $shop_id = $customer->shop_id;

            if($membership_id == null)
            {
                $membership = Tbl_membership_package::where('shop_id', $shop_id)->membership()->where('membership_archive', 0)->get()->toArray();
                $random = array_rand($membership);
                $membership_id = $membership[$random]['membership_package_id'];  
                $membership = Tbl_membership_package::where('membership_package_id', $membership_id)->membership()->first();
            }
            else
            {
                $membership = Tbl_membership_package::where('shop_id', $shop_id)->where('membership_package_id', $membership_id)->membership()->first();
            }

            if(!$membership)
            {
                $return['status'] = 'error';
                $return['message'] = 'The Package ID you used is invalid.';
            }
            else
            {
                $membership_package_id = $membership->membership_package_id;

                $insert['membership_activation_code'] = Self::random_code_generator(8);
                $insert['customer_id'] = $customer->customer_id;
                $insert['membership_package_id'] = $membership_package_id;
                $insert['membership_code_invoice_id'] = 0;
                $insert['shop_id'] = $shop_id;
                $insert['membership_code_pin'] = Tbl_membership_code::where('shop_id', $shop_id)->count() + 1; 
                $insert['membership_type'] = 'PS';

                $id = Tbl_membership_code::insertGetId($insert);

                $return['status'] = 'success';
                $return['message'] = 'Invalid Customer';
                $return['membership_code_id'] = $id;
                $return['membership_code'] = $insert;
            }
        }
        else
        {
            $return['status'] = 'error';
            $return['message'] = 'Invalid Customer';
        }

        if($return == 'json'){ return json_encode($return); }

        return $return;
    }
    public static function create_slot($request)
    {
        // shop_id
        // slot_owner
        // membership_code_id
        // slot_sponsor
        // slot_placement
        // slot_position

        $shop_id =  $request['shop_id'];
        
        $request_check['shop_id']               = null;
        $request_check['slot_owner']            = null;
        $request_check['membership_code_id']    = null;
        $request_check['slot_sponsor']          = null;
        $request_check['slot_placement']        = null;
        $request_check['slot_position']         = null;
        $request_check['brown_rank_id']         = null;

        foreach($request_check as $key => $value)
        {
            if(!isset($request[$key]))
            {
                $request[$key] = $request_check[$key];
            }
        }
        
        $validate['slot_owner'] = $request['slot_owner'];
        $validate['membership_code_id'] = $request['membership_code_id'];
        $validate['slot_sponsor'] = $request['slot_sponsor'];
        
        
        $rules['slot_owner'] = "required";
        $rules['membership_code_id'] = "required";
        //$rules['slot_sponsor'] = "required";
        
        $binary_settings = Tbl_mlm_plan::where('shop_id', $shop_id)->code('BINARY')->enable(1)->trigger('Slot Creation')->first();
        $binary_advance = Tbl_mlm_binary_setttings::where('shop_id', $shop_id)->first(); 

        $count_tree_if_exist = 0;

        if(isset($binary_settings->marketing_plan_enable))
        {
           if($binary_settings->marketing_plan_enable == 1)
           {
                if(isset($binary_advance->binary_settings_placement))
                {
                    if($binary_advance->binary_settings_placement == 0)
                    {
                        $validate['slot_placement'] = $request['slot_placement'];
                        $validate['slot_position'] = $request['slot_position'];

                        //$rules['slot_placement'] = "required";
                        //$rules['slot_position'] = "required";

                        $insert['slot_placement'] = $validate['slot_placement'];
                        $insert['slot_position'] = $validate['slot_position'];

                        $count_tree_if_exist = Tbl_tree_placement::where('placement_tree_position', $validate['slot_position'])
                        ->where('placement_tree_parent_id', $validate['slot_placement'])
                        ->where('shop_id', $shop_id)
                        ->count();
                    } 
                }
           }      
        }

        $validator = Validator::make($validate,$rules);
        $membership = Tbl_membership_code::where('membership_code_id', $validate['membership_code_id'])->package()->membership()->first();

        if(!$validator->passes())
        {
            $return['status'] = "error";
            $return['message'] = $validator->messages();
            return $return;
        }

        if(!$membership)
        {
            $return['status'] = "error";
            $return['message'] = 'Invalid Membership Code';
            return $return;
        }

        if($membership->used != 0)
        {
            $return['status'] = "error";
            $return['message'] = 'Membership Code Already Used';
            return $return;
        } 

        if($count_tree_if_exist != 0)
        {
            $return['status'] = "error";
            $return['message'] = 'Placement Alread Taken';
            return $return;
        }

        /* CREATE SLOT NO IF NOT SET */
        if(!isset($request["slot_no"]))
        {
            $request["slot_no"] = null;
        }

        /* CHECK IF SLOT NO. ALREADY EXIST */
        $check_slot_no = Tbl_mlm_slot::where("shop_id", $shop_id)->where("slot_no", $request["slot_no"])->first();
        if($check_slot_no)
        {
            $return['status'] = "error";
            $return['message'] = 'The slot number you are trying to use already exist.';
            return $return;
        }
        else
        {
            /* IF SLOT NO. IS NULL - CREATE SLOT NUMBERS */
            if($request["slot_no"] == null)
            {
                $insert['slot_no'] = Mlm_plan::set_slot_no($request['shop_id'], $validate['membership_code_id']);
            }
            else
            {
                $insert['slot_no'] = $request["slot_no"];
            }
        }

        $insert['shop_id'] = $request['shop_id'];
        $insert['slot_owner'] = $validate['slot_owner'];
        $insert['slot_created_date'] = $request["date_created"];
        $insert['slot_membership'] = $membership->membership_id;
        $insert['slot_status'] = $membership->membership_type;
        $insert['slot_sponsor'] = $validate['slot_sponsor'];

        if(isset($request['brown_rank_id']))
        {
            $insert['brown_rank_id'] = $request['brown_rank_id'];
        }
        
        $id = Tbl_mlm_slot::insertGetId($insert);
        $slot_info = Tbl_mlm_slot::where('slot_id', $id)->membership()->membership_points()->customer()->first();
        
        $update['used'] = 1;
        $update['date_used'] = Carbon::now();
        $update['slot_id'] = $id;
        
        Tbl_membership_code::where('membership_code_id', $validate['membership_code_id'])->update($update);            
          
        $return['status'] = "success"; 
        $return['message'] = 'Slot Inserted'; 
        $return['slot_id'] = $id;
        return $return;  
    }
    public static function random_code_generator($word_limit)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $word_limit; $i++) 
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}