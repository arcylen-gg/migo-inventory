<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_customer;
use App\Models\Tbl_membership;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_tree_sponsor;
// use App\Models\Tbl_mlm_slot;
use App\Globals\Mlm_compute;
use App\Globals\Mlm_tree;
use App\Globals\MLM2;

use Request;
use Carbon\Carbon;
class Developer_AutoentryController extends Member
{
    public function new_auto_balance()
    {

        // $sponsor                             = Tbl_mlm_slot::where("slot_id",$new_slot->slot_sponsor)->first();
        // if($sponsor)
        // {   
        //     $top_slot                        = Tbl_tree_placement::where("placement_tree_child_id",$sponsor->slot_id)->orderBy("placement_tree_level","DESC")->first();
        //     if(($sponsor->slot_placement == null || $sponsor->slot_placement == 0) && ($sponsor->slot_sponsor == null || $sponsor->slot_sponsor == 0))
        //     {
        //         $top_slot_id = $sponsor->slot_id; 
        //     }
        //     else if($top_slot)
        //     {
        //         $top_slot_id = $top_slot->placement_tree_parent_id;
        //     }
        //     else
        //     {
        //         $top_slot_id = null;
        //     }

        //     if($top_slot_id)
        //     {
        //         $top_slot                    = Tbl_mlm_slot::where("shop_id",$new_slot->shop_id)->where("slot_id",$top_slot_id)->orderBy("slot_id","ASC")->first();
        //         $get_top_level               = Tbl_tree_placement::where("placement_tree_parent_id",$top_slot->slot_id)->where("placement_tree_child_id",$new_slot->slot_placement)->first();
        //         $placement                   = Tbl_mlm_slot::where("slot_id",$new_slot->slot_placement)->first();
        //         if($get_top_level)
        //         {     
        //             if($new_slot->slot_position == "left")
        //             {
        //                 $auto_balance_position  = $placement->auto_balance_position + pow(2,$get_top_level->placement_tree_level);
        //             }
        //             else
        //             {
        //                 $auto_balance_position  = $placement->auto_balance_position + (pow(2,$get_top_level->placement_tree_level) * 2);
        //             }
        //         }
        //         else
        //         {
        //             $top_slot                  = Tbl_mlm_slot::where("shop_id",$new_slot->shop_id)->where("slot_id",$new_slot->slot_sponsor)->orderBy("slot_id","ASC")->first();
        //             if($new_slot->slot_sponsor == $top_slot->slot_id)
        //             {
        //                 if($new_slot->slot_position == "left")
        //                 {
        //                     $auto_balance_position = 2;
        //                 }
        //                 else
        //                 {
        //                     $auto_balance_position = 3;
        //                 }
        //             }
        //         }
        //     }

        //     if(isset($auto_balance_position))
        //     {
        //         $update["auto_balance_position"] = $auto_balance_position;
        //         Tbl_mlm_slot::where("slot_id",$new_slot->slot_id)->update($update);
        //     }
        // }
    }
	public function index()
	{
		$shop_id 			 = $this->user_info->shop_id;
		$data["slot_count"]  = Tbl_mlm_slot::where("shop_id",$shop_id)->count() ? Tbl_mlm_slot::where("shop_id",$shop_id)->count() : 0;
		$data["_slot"] 		 = Tbl_mlm_slot::where("shop_id",$shop_id)->get();
		$data["_membership"] = Tbl_membership::where("shop_id",$shop_id)->where("membership_archive",0)->get();
		$data["_customer"]   = Tbl_customer::where("shop_id",$shop_id)->where("archived",0)->get();
		return view('member.developer.auto_entry',$data);
	}

    public function instant_add_slot()
    {
    	$shop_id = $this->user_info->shop_id;
        if(Tbl_mlm_slot::where("shop_id",$shop_id)->count() == 0)
        {
            $insert['slot_sponsor'] = null;
        }
        else
        {
            $insert['slot_sponsor'] = Request::input("slot_sponsor");  
            $check_sponsor          = Tbl_mlm_slot::where("slot_id",Request::input("slot_sponsor"))->first(); 
            if(!$check_sponsor)
            {
            	$top_slot 				= Tbl_mlm_slot::where("shop_id",$shop_id)->orderBy("slot_id","ASC")->first();
            	$insert['slot_sponsor'] = $top_slot->slot_id; 
            } 
        }

        $insert['slot_no'] = Tbl_mlm_slot::where("shop_id",$shop_id)->count() + 1;
        $insert['shop_id'] = $this->user_info->shop_id;
        $insert['slot_owner'] = Request::input("slot_owner");
        $insert['slot_created_date'] = Carbon::now();
        $insert['slot_membership'] = Request::input("slot_membership");
        $insert['slot_status']  = Request::input("slot_status");


        $id = Tbl_mlm_slot::insertGetId($insert);

        $slot_info = Tbl_mlm_slot::where('slot_id', $id)->membership()->membership_points()->customer()->first();
        // compute mlm
        $a = Mlm_compute::entry($id);

        $count = Request::input("count");
        return json_encode($count);
    }

    public function index_independent()
    {
        $shop_id             = $this->user_info->shop_id;
        $data["slot_count"]  = 0;
        $data["_slot"]       = Tbl_mlm_slot::where("shop_id",$shop_id)->get();
        $data["_slot_root"]  = Tbl_mlm_slot::where("shop_id",$shop_id)
        ->where(function($query)
        {
            $query->where("slot_placement",null);
            $query->orWhere("slot_placement",0);
        })        
        ->where(function($query)
        {
            $query->where("slot_sponsor",null);
            $query->orWhere("slot_sponsor",0);
        })->get();

        $data["_membership"] = Tbl_membership::where("shop_id",$shop_id)->where("membership_archive",0)->get();
        $data["_customer"]   = Tbl_customer::where("shop_id",$shop_id)->where("archived",0)->get();
        return view('member.developer.auto_entry_independent',$data);
    }

    public function independent_create_slot()
    {
        $shop_id = $this->user_info->shop_id;
        if(Request::input("slot_sponsor") == "New")
        {
            $count_root               = Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_sponsor",null)->where("slot_placement",null)->count() + 1;
            $insert['slot_sponsor']   = null;
            $insert['slot_placement'] = null;
            $count                    = 1;
        }
        else
        {
            $insert['slot_sponsor'] = Request::input("slot_sponsor");  
            $count_root             = Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_sponsor",null)->where("slot_placement",null)->where("slot_id","<=",$insert['slot_sponsor'])->count() + 1;
            $check_sponsor          = Tbl_mlm_slot::where("slot_id",Request::input("slot_sponsor"))->first(); 
            $count                  = Tbl_tree_placement::where("placement_tree_parent_id",$check_sponsor->slot_id)->count() + 2;
        }

        
        $naming                      = $count_root - 1;

        $insert['slot_no']           = "CompanyHead".$naming.$count;
        $insert['shop_id']           = $this->user_info->shop_id;
        $insert['slot_owner']        = Request::input("slot_owner");
        $insert['slot_created_date'] = Carbon::now();
        $insert['slot_membership']   = Request::input("slot_membership");
        $insert['slot_status']       = Request::input("slot_status");
        $insert['slot_placement']    = 0;
        $insert['brown_rank_id']     = 0;

        $id = Tbl_mlm_slot::insertGetId($insert);
        $slot_info = Tbl_mlm_slot::where("slot_id",$id)->first();
        Mlm_compute::entry($id);
        // MLM2::matrix_auto($shop_id,$id,"autofill");
        // compute mlm

        $data["count"]     = $count; 

        if(Request::input("slot_sponsor") == "New")
        {
            $data["slot_root"] = $id;
        }
        else
        {
            $data["slot_root"] = Request::input("slot_sponsor");
        }

        return json_encode($data);
    }

    public function single_entry()
    {
        $shop_id             = $this->user_info->shop_id;
        return view('member.developer.single_entry');
    }
    public function single_entry_submit()
    {
        $shop_id             = $this->user_info->shop_id;
        $slot_id             = Request::input("slot_id");
        $slot_info           = Tbl_mlm_slot::where("slot_id",$slot_id)->first();

        if($slot_info)
        {
            if(($slot_info->slot_placement == null || $slot_info->slot_placement == 0) && ($slot_info->slot_sponsor != 0 || $slot_info->slot_sponsor != null))
            {
                $binary_downline  = Tbl_tree_placement::where("placement_tree_parent_id",$slot_id)->get();
                $sponsor_downline = Tbl_tree_sponsor::where("sponsor_tree_parent_id",$slot_id)->get();
                MLM2::matrix_position_auto_balance($shop_id,$slot_id);

                foreach($sponsor_downline as $s_downline)
                {
                    $slot_info_e = Tbl_mlm_slot::where('slot_id', $s_downline->sponsor_tree_child_id)->first();
                    Mlm_tree::insert_tree_sponsor($slot_info_e, $slot_info_e, 1);
                }

                foreach($binary_downline as $b_downline)
                {
                    $slot_info_e = Tbl_mlm_slot::where('slot_id', $b_downline->placement_tree_child_id)->first();
                    Mlm_tree::insert_tree_placement($slot_info_e, $slot_info_e, 1);
                }               



                dd("Success");
            }
            else
            {
                dd("Failed.");
            }
        }
        else
        {
            dd("Failed");
        }
    }

}