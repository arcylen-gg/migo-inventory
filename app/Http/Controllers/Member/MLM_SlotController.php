<?php

namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use Redirect;
use Response;
use View;
use Excel;
use DB;

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
use App\Models\Tbl_customer_search;
use App\Models\Tbl_customer_other_info;
use App\Models\Tbl_customer_address;
use App\Models\Tbl_mlm_matching_log;
use App\Models\Tbl_mlm_transfer_slot_log;
use App\Models\Tbl_mlm_binary_pairing;
use App\Models\Tbl_mlm_stairstep_settings;
use App\Globals\Item;
use App\Globals\AuditTrail;
use App\Globals\Mlm_plan;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_complan_manager_cd;
use App\Globals\Mlm_tree;
use App\Globals\Membership_code;
use App\Globals\Settings;
use App\Globals\Mlm_voucher;
use App\Globals\Mlm_slot_log;
use App\Globals\Item_code;
use App\Globals\Mlm_gc;

// use App\Globals\Mlm_compute;
use App\Globals\Mlm_complan_manager_repurchase;
use App\Globals\Utilities;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_member;
use App\Globals\Mlm_discount;
// use App\Globals\Mlm_compute;
use App\Models\Tbl_email_content;
use Crypt;
use App\Globals\Reward;
class MLM_SlotController extends Member
{
    public function instant_add_slot()
    {
        
        for($x = 0;$x < Request::input("loop");$x++)
        {
            $shop_id = $this->user_info->shop_id;
            if(Tbl_mlm_slot::where("shop_id",$shop_id)->count() == 0)
            {
                $insert['slot_sponsor'] = null;
            }
            else
            {
                $insert['slot_sponsor'] = 1;    
            }

            $insert['slot_no'] = Tbl_mlm_slot::where("shop_id",$shop_id)->count() + 1;
            $insert['shop_id'] = $this->user_info->shop_id;
            $insert['slot_owner'] = 1;
            $insert['slot_created_date'] = Carbon::now();
            $insert['slot_membership'] = 1;
            $insert['slot_status']  = "PS";


            $id = Tbl_mlm_slot::insertGetId($insert);

            $slot_info = Tbl_mlm_slot::where('slot_id', $id)->membership()->membership_points()->customer()->first();
            // compute mlm
            $a = Mlm_compute::entry($id);
        }

        dd("Success");
    }
    public function force_login()
    {
        $shop_id = $this->user_info->shop_id;
        $slot_id = Request::input('slot');
        if($slot_id != null)
        {
            $slot_info = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
            if($slot_info)
            {
                if($shop_id == $slot_info->shop_id)
                {
                    
                    $customer_id = $slot_info->slot_owner;
                    Mlm_member::add_to_session_edit($shop_id, $customer_id, $slot_id);
                    return Redirect::to('/mlm');
                }
                else
                {
                    die('This slot belongs to other shop');
                }
            }
            else
            {
                die('Invalid Slot');
            }
        }
        else
        {
            die('Invalid Slot');
        }

    }
    public function index()
    {      
        $access = Utilities::checkAccess('mlm-slots', 'access_page');
        if($access == 0)
        {
            return $this->show_no_access(); 
        }

        $data['page'] = 'Slot';
        $shop_id = $this->user_info->shop_id;
        $slot_count = Tbl_mlm_slot::where('shop_id', $shop_id)->count();
        
        if($slot_count == 0)
        {
            return redirect('/member/mlm/slot/head');
        }
        //end

        $data['membership'] = Tbl_membership::archive(0)->where('shop_id', $shop_id)->get();
        $data['count_all_slot_active'] = Tbl_mlm_slot::where('shop_id', $shop_id)->where('slot_active', 0)->count();
        $data['count_all_slot_inactive'] =  Tbl_mlm_slot::where('shop_id', $shop_id)->where('slot_active', 1)->count();
        $data['customer_account'] = Tbl_customer::where('shop_id', $shop_id)->where('ismlm', 1)->count();
        // dd($data['customer_account_w_slot']);
        $data['membership_count'] = [];
        foreach($data['membership'] as $key => $value)
        {
            $data['membership_count'][$key] = Tbl_mlm_slot::where('slot_membership', $value->membership_id)->count();
        }

        // dd($data);
        if(Request::ajax()) 
        {
            return $this->code_filter($shop_id, Request::input());
        }

        return view('member.mlm_slot.mlm_slot_list', $data);
    }
    public function code_filter($shop_id, $request = null)
    {
        $code                           = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $shop_id)
        ->orderBy('slot_id')
        ->customer()->membership();

        $membership                     = Request::input("membership");
        $membership_type                = Request::input("membership_type");
        $search_slot                    = Request::input("search_slot");
        $slot_active                    = Request::input('slot_active');
        
        if($membership != null || $membership != 0)
        {
            $code->where(function ($query) use($membership) 
                    {
                        $query ->where("slot_membership", $membership);
                    });
        }
        if($membership_type != null || $membership_type != 0)
        {
            $code->where(function ($query) use($membership_type) 
                    {
                        $query ->where("slot_status", $membership_type);
                    });
        }
        if($search_slot != null || $search_slot != 0)
        {
            $code->where(function ($query) use($search_slot)
            {$query ->where("slot_owner","LIKE","%".$search_slot."%")
                    ->orWhere("slot_no","LIKE","%".$search_slot."%")
                    ->orWhere("slot_id","LIKE","%".$search_slot."%")
                    ->orWhere(DB::raw("CONCAT(tbl_customer.first_name, ' ', tbl_customer.middle_name, ' ', tbl_customer.last_name)"), 'LIKE', "%".$search_slot."%");
            });
        }
        if($slot_active != null)
        {
            $code->where(function ($query) use($slot_active) 
                    {
                        $query ->where("slot_active", $slot_active);
                    });
        }
        $data['binary_settings'] = Tbl_mlm_plan::where('shop_id', $shop_id)
            ->where('marketing_plan_code', 'BINARY')
            ->where('marketing_plan_enable', 1)
            ->where('marketing_plan_trigger', 'Slot Creation')
            ->first();
            
        $data["code_selected"]  = $code->paginate(10);
        // dd($data);
        return view('member.mlm_slot.mlm_slot_ajax', $data);
    }
    public function add_slot()
    {
        $shop_id = $this->user_info->shop_id;
        $data["page"]               =   "Add Slot";
        $data['slotno']             =   Mlm_plan::set_slot_no();
        $data["_customer"]          =   Tbl_customer::where("archived",0)->where('shop_id', $shop_id)
                                        ->where('IsWalkin', 0)
                                        ->where('ismlm', 1)
                                        ->where('archived', 0)
                                        ->get();
        $data['_slots'] = Tbl_mlm_slot::where('tbl_mlm_slot.shop_id', $shop_id)->customer()->get();
        $data['binary_settings'] = Tbl_mlm_plan::where('shop_id', $shop_id)
            ->where('marketing_plan_code', 'BINARY')
            ->where('marketing_plan_enable', 1)
            ->where('marketing_plan_trigger', 'Slot Creation')
            ->first();
        $data['binary_advance'] = Tbl_mlm_binary_setttings::where('shop_id', $shop_id)->first();
        $data['sponsor'] = Request::input('sponsor');    
        return view('member.mlm_slot.mlm_slot_add_modal', $data);
    }
    public function add_slot_submit()
    {
        $shop_id = $this->user_info->shop_id;
        
        $validate['slot_owner'] = Request::input('slot_owner');
        $validate['membership_code_id'] = Request::input('membership_code_id');
        $validate['slot_sponsor'] = Request::input('slot_sponsor');
        
        
        $rules['slot_owner'] = "required";
        $rules['membership_code_id'] = "required";
        $rules['slot_sponsor'] = "required";
        
        $binary_settings = Tbl_mlm_plan::where('shop_id', $shop_id)
            ->where('marketing_plan_code', 'BINARY')
            ->where('marketing_plan_enable', 1)
            ->where('marketing_plan_trigger', 'Slot Creation')
            ->first();

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
                        $validate['slot_placement'] = Request::input('slot_placement');
                        $validate['slot_position'] = Request::input('slot_position');
                        $rules['slot_placement'] = "required";
                        $rules['slot_position'] = "required";


                        $insert['slot_placement'] = $validate['slot_placement'];
                        $insert['slot_position'] = $validate['slot_position'];

                        $count_tree_if_exist = Tbl_tree_placement::where('placement_tree_position', $validate['slot_position'])
                        ->where('placement_tree_parent_id', $validate['slot_placement'])
                        ->where('shop_id', $this->user_info->shop_id)
                        ->count();
                    } 
                }
           }      
        }

        $validator = Validator::make($validate,$rules);
        if ($validator->passes())
    	{
    	    $membership = Tbl_membership_code::where('membership_code_id', $validate['membership_code_id'])->package()->membership()->first();
    	    
    	   // tbl_tree_placement
    	    if($membership)
            {
                if($membership->used == 0)
                {
                    if($count_tree_if_exist == 0 )
                    {
                        $insert['slot_no'] = Mlm_plan::set_slot_no($this->user_info->shop_id, $validate['membership_code_id']);
                        $insert['shop_id'] = $this->user_info->shop_id;
                        $insert['slot_owner'] = $validate['slot_owner'];
                        $insert['slot_created_date'] = Carbon::now();
                        $insert['slot_membership'] = $membership->membership_id;
                        $insert['slot_status'] = $membership->membership_type;
                        $insert['slot_sponsor'] = $validate['slot_sponsor'];
                        
                        $id = Tbl_mlm_slot::insertGetId($insert);
                        $slot_info = Tbl_mlm_slot::where('slot_id', $id)->membership()->membership_points()->customer()->first();
                        // compute mlm
                            $a = Mlm_compute::entry($id);
                        // end
                        
                        $update['used'] = 1;
                        $update['date_used'] = Carbon::now();
                        $update['slot_id'] = $id;
                        Tbl_membership_code::where('membership_code_id', $validate['membership_code_id'])->update($update);
                        $c = Mlm_gc::slot_gc($id);
                        $insert['slot_id'] = $id;
                        $data['slot_data'] = $insert;
                        $data['response_status'] = "success_add_slot";

                        $slot_data = Tbl_mlm_slot::customer()->where("slot_id",$id)->first()->toArray();
                        AuditTrail::record_logs("Created","mlm_slot",$id,"",serialize($slot_data));
                    }
                    else
                    {
                        $data['response_status'] = "warning_2";
                        $data['error'] = "Slot Placement Already Taken";
                    }
                }
                else
                {
                    $data['response_status'] = "warning_2";
                    $data['error'] = "Membership Code Already Used";
                }
            }
            else
            {
                $data['response_status'] = "warning_2";
                $data['error'] = "Invalid Membership code";
            }
    	    
    	    
    	}
    	else
    	{
    		$data['response_status'] = "warning";
    	    $data['warning_validator'] = $validator->messages();
    	}
    	return json_encode($data);
    }
    public function view_slot_info($slot_id)
    {
        $data = [];
        $data['slot'] = Tbl_mlm_slot::where('slot_id', $slot_id)->membership()->membership_points()->customer()->first();
        if($data['slot'] != null)
        {
            $data['slot_sponsor'] = Tbl_mlm_slot::where('slot_id', $data['slot']->slot_sponsor)->membership()->membership_points()->customer()->first();

            $data['slot_refferals'] = Tbl_mlm_slot::where('slot_sponsor', $data['slot']->slot_id)->membership()->membership_points()->customer()->get();
            // $data['wallet_logs'] = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $data['slot']->slot_id)->get();
            $tree = Tbl_tree_sponsor::where('sponsor_tree_parent_id', $data['slot']->slot_id)
            ->child_info()->membership()->membership_points()->customer()
            ->orderBy('sponsor_tree_level', 'ASC')
            ->get();
            $data['plan_settings'] = Tbl_mlm_plan::where('shop_id', $data['slot']->shop_id)
            ->where('marketing_plan_enable', 1)
            ->where('marketing_plan_trigger', 'Slot Creation')
            ->where('marketing_plan_code', '!=', 'INDIRECT_POINTS')
            ->where('marketing_plan_code', '!=', 'DIRECT_POINTS')
            ->where('marketing_plan_code', '!=', 'INITIAL_POINTS')
            ->where('marketing_plan_code', '!=', 'DISCOUNT_CARD')
            ->get();
            $data['plan_ernings'] = [];
            foreach($data['plan_settings'] as $key => $value)
            {
                $data['plan_ernings'][$key] = Tbl_mlm_slot_wallet_log::where('wallet_log_slot', $data['slot']->slot_id)
                ->where('wallet_log_plan', $value->marketing_plan_code)
                ->sum('wallet_log_amount');
            }
            $tree_per_level = [];
            $data['tree_per_level'] = [];
            foreach($tree as $key => $value)
            {
                $data['tree_per_level'][$value->sponsor_tree_level][$value->sponsor_tree_child_id] = $value;
            }
            // dd($data);
        }
        return view('member.mlm_slot.mlm_slot_view', $data);
    }
    public function add_slot_head()
    {
        // redirect if already has company head
        $shop_id = $this->user_info->shop_id;
        $slot_count = Tbl_mlm_slot::where('shop_id', $shop_id)->count();
        if($slot_count != 0)
        {
            return redirect('/member/mlm/slot');
        }
        
        
        $data["page"]               =   "Set Head";
        $data['slotno']             =   Mlm_plan::set_slot_no();
        $data["_customer"]          =   Tbl_customer::where("archived",0)->where('shop_id', $shop_id)->get();
        $data['binary_settings'] = Tbl_mlm_plan::where('shop_id', $shop_id)
            ->where('marketing_plan_code', 'BINARY')
            ->where('marketing_plan_enable', 1)
            ->where('marketing_plan_trigger', 'Slot Creation')
            ->first();
        
        return view('member.mlm_slot.mlm_slot_head', $data);
    }
    public function get_member_code($customer_id)
    {
        $data["membership_code"]    =   Tbl_membership_code::where('customer_id', $customer_id)
        ->leftjoin('tbl_membership_package', 'tbl_membership_package.membership_package_id', '=','tbl_membership_code.membership_package_id')
        ->leftjoin('tbl_membership', 'tbl_membership.membership_id', '=','tbl_membership_package.membership_id')
        ->where('used', 0)->where('blocked', 0)->get();

        // dd($data);
        return view('member.mlm_slot.mlm_slot_get_code', $data);
    }
    public function get_member_code_form_submit()
    {        
        
        $validate['slot_owner'] = Request::input('slot_owner');
        $validate['membership_code_id'] = Request::input('membership_code_id');
        
        $rules['slot_owner'] = 'required';
        $rules['membership_code_id'] = 'required';
        
        $validator = Validator::make($validate,$rules);
        
        if($validator->passes())
    	{
    	    $membership = Tbl_membership_code::where('membership_code_id', $validate['membership_code_id'])->package()->membership()->first();
    	   
    	    $insert['slot_no'] = Mlm_plan::set_slot_no($this->user_info->shop_id, $validate['membership_code_id']);
            $insert['shop_id'] = $this->user_info->shop_id;
            $insert['slot_owner'] = $validate['slot_owner'];
            $insert['slot_created_date'] = Carbon::now();
            $insert['slot_membership'] = $membership->membership_id;
            $insert['slot_status'] = $membership->membership_type;
            $insert['slot_placement'] = 'left';
            $insert['auto_balance_position'] = 1;
            $slot_id = Tbl_mlm_slot::insertGetId($insert);
            
            $update['used'] = 1;
            $update['slot_id'] = $slot_id;
            Tbl_membership_code::where('membership_code_id', $validate['membership_code_id'])->update($update);
            Mlm_compute::entry($slot_id);
            $c = Mlm_gc::slot_gc($slot_id);

            $slot_data = Tbl_mlm_slot::customer()->where("slot_id",$slot_id)->first()->toArray();

            AuditTrail::record_logs("Create","mlm_slot",$slot_id,"",serialize($slot_data));

            $data['response_status'] = "success";
    	}
    	else
    	{
    		$data['response_status'] = "warning";
    	    $data['warning_validator'] = $validator->messages();
    	}
    	return json_encode($data);
    }
    public function tree()
    {
        $slot_id           = Request::input("id");
        $shop_id           = $this->user_info->shop_id;
        $data["slot"]      = Tbl_mlm_slot::membership()->customer()->where("tbl_mlm_slot.shop_id",$shop_id)->where("slot_id",$slot_id)->first();
        $data['l']         = Tbl_tree_placement::where('placement_tree_parent_id',$slot_id)->where('placement_tree_position','left')->count();
        $data['r']         = Tbl_tree_placement::where('placement_tree_parent_id',$slot_id)->where('placement_tree_position','right')->count();
        if($data["slot"])
        {
             $data["downline"]  = $this->downline($slot_id);   
             $stairstep         = Tbl_mlm_stairstep_settings::where("stairstep_id",$data["slot"]->stairstep_rank)->first();
             if($stairstep)
             {
                if($stairstep->stairstep_genealogy_color == "Default")
                {
                    $data["genealogy_color"] = null;
                }
                else
                {
                    $data["genealogy_color"] = "background-color:".$stairstep->stairstep_genealogy_color;
                }

                if($stairstep->stairstep_genealogy_border_color == "Default")
                {
                    $data["genealogy_border_color"] = null;
                }
                else
                {
                    $data["genealogy_border_color"] = "border-style: solid;border-color:".$stairstep->stairstep_genealogy_border_color;
                }
             }
             else
             {
                $data["genealogy_color"]        = null;
                $data["genealogy_border_color"] = null;
             }
        }
        else
        {
            die('Invalid Slot');
        }
        $data['format'] = Request::input("mode");
        return view('member.mlm_slot.mlm_slot_genealogy', $data);
    }
    public function downline($x = 0)
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
            $return .= $this->binary_downline($slot_id);
        }
        else
        {
            $return .= $this->unilevel_downline($slot_id);
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
    public function binary_downline($slot_id)
    {
        $left_info  = Tbl_mlm_slot::where("slot_placement", $slot_id)->where("slot_position", "left")->membership()->customer()->first();
        $right_info = Tbl_mlm_slot::where("slot_placement", $slot_id)->where("slot_position", "right")->membership()->customer()->first(); 

        $tree_string = "";
        $tree_string .= $this->downline_format($left_info,'Left',$slot_id);
        $tree_string .= $this->downline_format($right_info,'Right',$slot_id);

        return $tree_string;
    }
    public function unilevel_downline($slot_id)
    {
        $_info = Tbl_mlm_slot::where("slot_sponsor", $slot_id)->membership()->customer()->get();
        $count = Tbl_mlm_slot::where("slot_sponsor", $slot_id)->membership()->customer()->count();

        $tree_string = "";

        if($count != 0)
        {
            foreach($_info as $info)
            {
                $tree_string .= $this->downline_format_unilevel($info);  
            }
        }
        else
        {
            $tree_string .= '<li class="width-reference">
                                <span class="parent parent-reference VC" >
                                    <div class="id popup_add"  >+</div>
                                </span>
                            </li>';
        }

        
        return $tree_string;
    }
    public function downline_format_unilevel($slot_info,$position = null,$placement = null)
    {

        if($slot_info)
        {
            $l = Tbl_tree_placement::where('placement_tree_parent_id',$slot_info->slot_id)->where('placement_tree_position','left')->count();
            $r = Tbl_tree_placement::where('placement_tree_parent_id',$slot_info->slot_id)->where('placement_tree_position','right')->count();
            
            $stairstep         = Tbl_mlm_stairstep_settings::where("stairstep_id",$slot_info->stairstep_rank)->first();
            if($stairstep)
            {
               if($stairstep->stairstep_genealogy_color == "Default")
               {
                   $genealogy_color = null;
               }
               else
               {
                   $genealogy_color = "background-color:".$stairstep->stairstep_genealogy_color;
               }


                if($stairstep->stairstep_genealogy_border_color == "Default")
                {
                    $genealogy_border_color = null;
                }
                else
                {
                    $genealogy_border_color = "border-style: solid;border-color:".$stairstep->stairstep_genealogy_border_color;
                }
            }
            else
            {
               $genealogy_color = null;
               $genealogy_border_color = null;
            }

            $str_slot = '<span class="downline parent parent-reference PS SILVER" x="' . $slot_info->slot_id . '" style="'.$genealogy_color.';'.$genealogy_border_color.'">';    



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
                                    <div class="id">' . $slot_info->slot_no . '</div>
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
    public function downline_format($slot_info,$position = null,$placement = null)
    {

        if($slot_info)
        {
            $l = Tbl_tree_placement::where('placement_tree_parent_id',$slot_info->slot_id)->where('placement_tree_position','left')->count();
            $r = Tbl_tree_placement::where('placement_tree_parent_id',$slot_info->slot_id)->where('placement_tree_position','right')->count();
            

            $stairstep         = Tbl_mlm_stairstep_settings::where("stairstep_id",$slot_info->stairstep_rank)->first();
            if($stairstep)
            {
               if($stairstep->stairstep_genealogy_color == "Default")
               {
                   $genealogy_color = null;
               }
               else
               {
                   $genealogy_color = "background-color:".$stairstep->stairstep_genealogy_color;
               }
            }
            else
            {
               $genealogy_color = null;
            }

            $str_slot = '<span class="downline parent parent-reference PS SILVER" x="' . $slot_info->slot_id . '" style="'.$genealogy_color.'">';    



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
                                    <div class="id">' . $slot_info->slot_no . '</div>
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
    public function set_inactive_slot($slot_id)
    {
        $slot = Tbl_mlm_slot::where('slot_id', $slot_id)->first();
        if($slot)
        {
            if($slot->slot_active == 0)
            {
                $update['slot_active'] = 1;
            }
            else
            {
                $update['slot_active'] = 0;
            }
            Tbl_mlm_slot::where('slot_id', $slot->slot_id)->update($update);

            if($update['slot_active'] == 0)
            {
                echo "Statu: ";
                echo 'Active (<a href="javascript:" onClick="setactive('.$slot->slot_id.')">Set as Inactive</a>)';    
            }
            else
            {
                echo "Statu: ";
                echo 'Inactive (<a href="javascript:" onClick="setactive('.$slot->slot_id.')">Set as Active</a>)';    
            }
        }
        else
        {
            return "Oops Something Went Wrong";
        }
    }
    public function simulate($code)
    {
        if(Request::input('password')  != 'water123')
        {
            die('no_Accesso_for_pavor');
        }
        // dd($code);
        // return Mlm_compute::reset_all_slot();
        if($code =='binary')
        {
            // $slot_info = Tbl_mlm_slot::where('slot_id', 10)->first();
            // $a= Mlm_tree::auto_place_slot_binary_auto_balance($slot_info);

            $slot_info = Tbl_mlm_slot::where('slot_id', 11)->first();
            $a= Mlm_tree::auto_place_slot_binary_auto_balance($slot_info);

            $slot_info = Tbl_mlm_slot::where('slot_id', 12)->first();
            $a= Mlm_tree::auto_place_slot_binary_auto_balance($slot_info);

            $slot_info = Tbl_mlm_slot::where('slot_id', 13)->first();
            $a= Mlm_tree::auto_place_slot_binary_auto_balance($slot_info);

            $slot_info = Tbl_mlm_slot::where('slot_id', 14)->first();
            $a= Mlm_tree::auto_place_slot_binary_auto_balance($slot_info);

            $slot_info = Tbl_mlm_slot::where('slot_id', 15)->first();
            $a= Mlm_tree::auto_place_slot_binary_auto_balance($slot_info);
        }
        if($code == 'reset')
        {
            return Mlm_compute::reset_all_slot();
        }
        else if($code== 'simulate')
        {
            return  Mlm_compute::simulate_perfect();
        }
        else if($code == 'compute')
        {
            // return phpinfo();
            return Mlm_compute::computer(5);
        }
        else if($code =='excel')
        {
           // dd(1);
            // dd(public_path().'\assets\mlm\philteccustomer.xlsx');
            
            Excel::load(public_path().'/assets/mlm/soveriegn.xlsx', function($reader) 
            {
                $results = $reader->get()->toArray();
                // DB::table('tbl_customer_address')->delete();
                // DB::table('tbl_customer_search')->delete();
                // DB::table('tbl_customer_other_info')->delete();
                // DB::table('tbl_customer')->delete();
                foreach($results[0] as $key => $value)
                {
                    if($key == 265)
                    {
                        die('end_import');
                    }
                    $shop_id = $this->user_info->shop_id;
                    $count_first_last = Tbl_customer::where('shop_id', $shop_id)
                    ->where('first_name', $value['first_name'])
                    ->where('last_name', $value['last_name'])
                    ->count();

                    if($count_first_last == 0)
                    {
                        $insert['shop_id'] = $shop_id;
                        $insert['country_id'] = 420;
                        $insert['title_name'] = '';
                        $insert['first_name'] =  $value['first_name'];
                        $insert['middle_name'] = '';
                        $insert['last_name'] = $value['last_name'];
                        $insert['suffix_name'] = '';
                        $insert['email'] =  'soveriegn_defaultemail' . $key. '@gmail.com';
                        $insert['ismlm'] = 1;
                        $insert['mlm_username'] = $value['username'];
                        $insert['password'] = Crypt::encrypt('password');
                        $insert['tin_number'] = 0;
                        $insert['company'] = 'soveriegn';
                        $insert['created_date'] = Carbon::now();
                        $customer_id = Tbl_customer::insertGetId($insert);

                        $insertSearch['customer_id'] = $customer_id;
                        $insertSearch['body'] = $insert['title_name'].' '.$insert['first_name'].' '.$insert['middle_name'].' '.$insert['last_name'].' '.$insert['suffix_name'].' '.$insert['email'].' '.$insert['company'];

                        Tbl_customer_search::insert($insertSearch);

                        $insertInfo['customer_id'] = $customer_id;
                        $insertInfo['customer_mobile'] = '';
                        Tbl_customer_other_info::insert($insertInfo);

                        $insertAddress[0]['customer_id'] = $customer_id;
                        $insertAddress[0]['country_id'] = 420;
                        $insertAddress[0]['purpose'] = 'billing';
                        $insertAddress[0]['customer_street'] = $value['address'];
                        
                        $insertAddress[1]['customer_id'] = $customer_id;
                        $insertAddress[1]['country_id'] = 420;
                        $insertAddress[1]['purpose'] = 'shipping';
                        $insertAddress[1]['customer_street'] = $value['address'];
                        Tbl_customer_address::insert($insertAddress);
                    }
                }
            });
        }
        else if($code == 'excel2')
        {
            Excel::load(public_path().'/assets/mlm/phil2.xlsx', function($reader) {
                $results = $reader->get()->toArray();
                foreach($results[0] as $key => $value)
                {
                    $email = $value['email'];
                    $address =  $value['address'];

                    $customer = Tbl_customer::where('email', $email)->first();
                    if($customer)
                    {
                        $update['customer_street'] = $address;

                        Tbl_customer_address::where('customer_id', $customer->customer_id)->where('purpose', 'billing')->update($update);
                    }
                }
            });
        }
        else if($code =='fix_search')
        {
            DB::table('tbl_customer_search')->delete();
            $customer = Tbl_customer::leftjoin('tbl_customer_search', 'tbl_customer_search.customer_id', '=', 'tbl_customer.customer_id')->get();
            $customer_2 = Tbl_customer::get();

            // dd(count($customer));
            foreach ($customer as $key => $value) 
            {
                if($value->body == null)
                {

                    $title = $value->title_name;
                    $first_name = $value->first_name;
                    $middle_name = $value->middle_name;
                    $last_name = $value->last_name;
                    $suffix = $value->suffix_name; 
                    $email = $value->email;
                    $company = $value->company; 
                    // dd($value);
                    $updatetSearch['customer_id'] = $customer_2[$key]->customer_id;
                    $updatetSearch['body'] = $title.' '.$first_name.' '.$middle_name.' '.$last_name.' '.$suffix.' '.$email.' '.$company;
                    $updatetSearch['created_at'] = Carbon::now();
                    $updatetSearch['updated_at'] = Carbon::now();

                    DB::table('tbl_customer_search')->insert($updatetSearch);
                }
            }

        }
        else if($code == 'reset_income')
        {
            DB::table('tbl_mlm_slot_wallet_log')->delete();
            DB::table('tbl_mlm_matching_log')->delete();
            DB::table('tbl_mlm_slot_points_log')->delete();
            $update['slot_wallet_all'] = 0;
            $update['slot_wallet_withdraw'] = 0;
            $update['slot_wallet_current'] = 0;
            Tbl_mlm_slot::where('shop_id', 1)->update($update);
            
            return redirect::back();
        }
        else if($code == 'fix_income_matching')
        {
            $match = Tbl_mlm_matching_log::get()->toArray();
            $per_earner = [];
            foreach($match as $key => $value)
            {
                $per_earner[$value['matching_log_earner']][$key] = $value;
            }
            $per_earn = [];
            $per_earner_2 = [];
            
            foreach($per_earner as $key => $value)
            {
                
                foreach($value as $key2 => $value2)
                {
                    if(isset($per_earn[$key][$value2['matching_log_slot_1']]))
                    {

                        $per_earn[$key][$value2['matching_log_slot_1']] += 1;
                        // dd($key);
                    }
                    else
                    {
                        $per_earn[$key][$value2['matching_log_slot_1']] = 1;
                    }
                    if(isset($per_earn[$key][$value2['matching_log_slot_2']]))
                    {

                        $per_earn[$key][$value2['matching_log_slot_2']] += 1;
                    }
                    else
                    {
                        $per_earn[$key][$value2['matching_log_slot_2']] = 1;
                    }
                }
            }
            foreach ($per_earn as $key => $value) {
                foreach($value as $key2 => $value2)
                {
                    if($value2 == 2)
                    {
                        $per_earner_2[$key][$key2] = $value2;
                    }
                }
                # code...
            }
            $will_del = [];
            $match_delete = [];
            foreach($per_earner_2 as $key => $value)
            {
                // dd($key2);
                foreach ($value as $key2 => $value2) {
                    // dd($key);
                    $will_del[$key][$key2] = Tbl_mlm_matching_log::where(function ($query) use ($key2){
                                $query->where('matching_log_slot_1', $key2)
                                      ->orWhere('matching_log_slot_2', $key2);
                            })
                    ->where('matching_log_earner', $key)
                    ->first();
                }

                
            }
            
            foreach($will_del as $key => $value)
            {
                foreach ($value as $key2 => $value2) 
                {
                    $match_delete[$value2->matching_log] = $value2;
                }
            }
            foreach($match_delete as $key => $value)
            {
                Tbl_mlm_matching_log::where('matching_log', $key)->delete();
            }
            dd($match_delete);
            
            
        }
        else if($code == 'rep')
        {
            $slot = Mlm_compute::get_slot_info(10);
            return Mlm_complan_manager_repurchase::repurchase_points($slot, 1);
        }
        else if($code == 'mmatching_m')
        {
            $shop_id = 0;
            $update['matching_log_earning'] = 0;
            Tbl_mlm_matching_log::where('shop_id', $shop_id)->update($update);
            Mlm_compute::entry(289);
        }
        else if($code == 'leadership_m')
        {
            // $slot_id = DB::table('tbl_mlm_slot_points_log')->where('points_log_complan', 'LEADERSHIP_BONUS')->delete();
            $s = 289;

            $s = Mlm_compute::get_slot_info(289);
            // dd($s);
            return Mlm_complan_manager::leadership_bonus($s);
            // return Mlm_complan_manager::leadership_bonus_earn_2(15);
            // dd($s);

        }
        else if($code == 'discount')
        {
            // return 1;
            $shop_id = 5;
            return Mlm_discount::get_discount_all_membership($shop_id, 9, null);
        }
        else if($code == 'fix_mlm_slot')
        {
            $update['slot_matched_membership'] = 1;
            Tbl_mlm_slot::where('slot_id', '!=', 289)->update($update);
        }
        else if($code == 'recompute_slot_1')
        {
            $slot = Request::input('slot');
            $shop_id = $this->user_info->shop_id;
            $wallet = Tbl_mlm_slot_wallet_log::where('wallet_log_slot_sponsor', $slot)->get();
            Mlm_compute::entry($slot);
            dd($wallet);
            dd($shop_id);
        }
        else if ($code == 'match_wallet')
        {
            $shop_id = $this->user_info->shop_id;
            $slots = Tbl_mlm_slot::where('shop_id', $shop_id)->get();
            Mlm_slot_log::update_all_released();
        }
        else if ($code == 'fix_give_voucher')
        {
            
        }
    }
    public function transfer_slot()
    {
        $shop_id           = $this->user_info->shop_id;
        $slot_id           = Request::input("slot");
        $data["slot"]      = Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->first();

        if(!$data["slot"])
        {
            dd("Please refresh the page.");
        }

        $data["_customer"] = Tbl_customer::where("shop_id",$shop_id)->where("customer_id","!=",$data["slot"]->slot_owner)->where("archived",0)->get();
        return view('member.mlm_slot.transfer_slot', $data);
    }
    public function transfer_slot_post()
    {
        $shop_id    = $this->user_info->shop_id;
        $slot_id    = Request::input("slot_id");
        $slot_owner = Request::input("slot_owner");
        $slot       = Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->first();
        $customer   = Tbl_customer::where("customer_id",$slot_owner)->where("shop_id",$shop_id)->first();
        if(!$slot || !$customer)
        {
            dd("Please refresh the page.");
        }
        else
        {
            if($slot->slot_owner == $slot_owner)
            {
                dd("Please refresh the page.");
            }

            $update["slot_owner"] = $slot_owner;
            Tbl_mlm_slot::where("slot_id",$slot_id)->update($update);
            $old_data = $slot->toArray();
            $new_data = Tbl_mlm_slot::where("slot_id",$slot_id)->where("shop_id",$shop_id)->first()->toArray();

            AuditTrail::record_logs("Transfer","mlm_slot",$slot_id,serialize($old_data),serialize($new_data));

            $return["status"] = "success_transfer";


            $insert_log["slot_transfer_by"]           = $slot->slot_owner;
            $insert_log["slot_transfer_to"]           = $slot_owner;; 
            $insert_log["slot_id"]                    = $slot_id; 
            $insert_log["transfer_slot_date"]         = Carbon::now(); 
            $insert_log["transfer_slot_log_type"]     = "Admin"; 
            $insert_log["transfer_slot_log_cause_id"] = $this->user_info->user_id; 
            Tbl_mlm_transfer_slot_log::insert($insert_log);

            return json_encode($return);
        }
    }
}