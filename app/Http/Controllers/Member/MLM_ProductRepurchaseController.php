<?php

namespace App\Http\Controllers\Member;

use View;
use Request;
use Session;
use Response;
use Redirect;
use Validator;
use Carbon\Carbon;

use App\Models\Tbl_user;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_customer;
use App\Models\Tbl_item_code;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_membership_code;
use App\Models\Tbl_mlm_discount_card_log;

use App\Globals\Mlm_plan;
use App\Globals\Mlm_member;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_complan_manager_cd;

class MLM_ProductRepurchaseController extends Member
{
    public function index()
    {
        $shop_id = $this->user_info->shop_id;
        $data["page"]               =   "Product Repurchase";
        $data["_customer"]          =   Tbl_customer::where("archived",0)->get();
        return view('member.mlm_product_repurchase.product_repurchase', $data);
    }
    
    public function get_code($customer_id)
    {
        $data["product_code"]       =   Tbl_item_code::where('customer_id', $customer_id)->where('used', 0)->where('blocked', 0)->get();
        return view('member.mlm_product_repurchase.product_repurchase_get_code', $data);
    }
    public function get_code_info($item_code_id)
    {
        $data['item_code'] = [];
        $data['item_invoice'] = [];
        if($item_code_id != null)
        {
            $data['item_code'] = Tbl_item_code::where('item_code_id', $item_code_id)->item()->invoice()->customer()->get();
        }
        return view('member.mlm_product_repurchase.product_repurchase_info', $data);
    }
    public function get_slot($customer_id)
    {
        $data["_slot"]       =   Tbl_mlm_slot::where('slot_owner', $customer_id)->membership()->get();
        return view('member.mlm_product_repurchase.product_repurchase_get_slot', $data);
    }

    public function submit()
    {
        $validate['slot_owner']   = Request::input('slot_owner');
        $validate['slot_id']      = Request::input('slot_id');
        $validate['item_code_id'] = Request::input('item_code_id');
        
        $rules['slot_owner']      = "required";
        $rules['slot_id']         = "required";
        $rules['item_code_id']    = "required";
        
        $validator = Validator::make($validate,$rules);
        if ($validator->passes())
        {
            $shop_id   = $this->user_info->shop_id;
            $item_code = Tbl_item_code::where("item_code_id",$validate["item_code_id"])->where("shop_id",$shop_id)->first();
            $slot      = Tbl_mlm_slot::where("slot_id",$validate["slot_id"])->where("slot_owner",$validate["slot_owner"])->where("shop_id",$shop_id)->first();
            if($item_code)
            {
                if($item_code->used == 1)
                { 
                    $data['response_status'] = "warning_2";
                    $data['error'] = "This code is already used.";
                }
                else if($item_code->blocked == 1)
                {
                    $data['response_status'] = "warning_2";
                    $data['error'] = "This code is blocked.";
                }
                else if($item_code->customer_id != $validate['slot_owner'])
                {
                    $data['response_status'] = "warning_2";
                    $data['error'] = "This code is not yours.";
                }
                else if(!$slot)
                {
                    $data['response_status'] = "warning_2";
                    $data['error'] = "Slot doesn't exists.";
                }
                else
                {
                    $update["used"]          = 1;
                    $update["date_used"]     = Carbon::now();
                    $update["used_on_slot"]  = $validate["slot_id"];
                    Tbl_item_code::where("item_code_id",$item_code->item_code_id)->update($update);


                    Mlm_compute::repurchase($slot,$item_code->item_code_id);
                    $data['response_status'] = "success";
                }                
            }
            else
            {
                $data['response_status'] = "warning_2";
                $data['error'] = "Code doesn't exists.";
            }
        }
        else
        {
            $data['response_status'] = "warning";
            $data['warning_validator'] = $validator->messages();
        }
        echo json_encode($data);
    }
    public function get_membership_code($membership_code)
    {
        /// Membership code changed to slot
        $shop_id = $this->user_info->shop_id;
        $count_discount_card = Tbl_mlm_discount_card_log::where('discount_card_log_code', $membership_code)->count();
        $tbl_membership_code_count = Tbl_mlm_slot::where('slot_no', $membership_code)->where('shop_id', $shop_id)->count();
        if($tbl_membership_code_count != 0)
        {
            $tbl_membership_code_count = Tbl_mlm_slot::where('slot_no', $membership_code)->where('tbl_mlm_slot.shop_id', $shop_id)->customer()->first();
            return Mlm_member::get_customer_info_w_slot($tbl_membership_code_count->customer_id, $tbl_membership_code_count->slot_id);
        }
        else
        {
            if($count_discount_card != 0)
            {
                $count_discount_card = Tbl_mlm_discount_card_log::where('discount_card_log_code', $membership_code)->first();
                if($count_discount_card->discount_card_customer_holder == null)
                {
                    $update['discount_card_customer_holder'] = $count_discount_card->discount_card_customer_sponsor;
                    // $update['discount_card_log_date_expired'] = Carbon::now()->addYear(1);
                    // $update['discount_card_log_issued_date'] = Carbon::now();
                    Tbl_mlm_discount_card_log::where('discount_card_log_code', $membership_code)->update($update);
                    $count_discount_card = Tbl_mlm_discount_card_log::where('discount_card_log_code', $membership_code)->first();
                }
                return Mlm_member::get_customer_info($count_discount_card->discount_card_customer_holder, $count_discount_card->discount_card_log_id);
            }   
            else
            {
                return '<center>Invalid Slot</center>';
            }
        }
    }
}