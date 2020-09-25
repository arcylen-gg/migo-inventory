<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_stairstep_distribute;
use App\Models\Tbl_stairstep_distribute_slot;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_user;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_tree_sponsor;
use App\Models\Tbl_mlm_stairstep_settings;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_shop;
use App\Models\Tbl_rank_update;
use App\Models\Tbl_mlm_plan_setting;
use App\Models\Tbl_customer;
use App\Models\Tbl_rank_update_slot;
use App\Globals\Mlm_slot_log;
use App\Globals\Mlm_complan_manager;
use App\Globals\Mlm_member;
use App\Globals\Mail_global;
use App\Globals\Mlm_complan_manager_repurchasev2;
use Crypt;
use Redirect;
use Request;
use Carbon\Carbon;
use View;
use DB;

class MLM_RankController extends Member
{
	public function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

    public function rank_stairstep_view()
    {
    	$shop_id = $this->getShopId();
    	$data["shop_id"] = $shop_id;
        $days_sub        = Tbl_mlm_plan_setting::where('shop_id',$shop_id)->first()->rank_real_time_update_counter;
        $data["_slot"]   = Tbl_mlm_slot::where("tbl_mlm_slot.shop_id",$shop_id)
								  	   ->customer();                                                                      
	    if($days_sub != 0)
	    {
	    	$days_sub 		= $days_sub - 1;
	        $start 			= Carbon::parse(Carbon::now()->startOfMonth())->subMonths($days_sub)->format("Y-m-d 00:00:00");
	        $end   			= Carbon::parse(Carbon::now()->endOfMonth())->format("Y-m-d 23:59:59");
	        $data["_slot"]  = $data["_slot"]->leftjoin("tbl_mlm_stairstep_settings","tbl_mlm_stairstep_settings.stairstep_id","=","tbl_mlm_slot.stairstep_rank")
									  	    ->leftJoin("tbl_mlm_slot_points_log",function($join) use ($start,$end)
									        {
									            $join->on("tbl_mlm_slot_points_log.points_log_slot","=","tbl_mlm_slot.slot_id")
									            	 ->where('tbl_mlm_slot_points_log.points_log_date_claimed',">=",$start)
	        										 ->where('tbl_mlm_slot_points_log.points_log_date_claimed',"<=",$end);
									        })
									  	    ->select("*",DB::raw("SUM(CASE WHEN points_log_type = 'RPV' THEN points_log_points ELSE 0 END) AS rank_personal_points"),DB::raw("SUM(CASE WHEN points_log_type = 'RGPV' THEN points_log_points ELSE 0 END) AS rank_group_points"))
									  	    ->groupBy("slot_id") 
	        								->get();
	    }
	    else
	    {
	        $data["_slot"]  = $data["_slot"]->leftjoin("tbl_mlm_stairstep_settings","tbl_mlm_stairstep_settings.stairstep_id","=","tbl_mlm_slot.stairstep_rank")
								  	   		->leftJoin("tbl_mlm_slot_points_log","tbl_mlm_slot_points_log.points_log_slot","=","tbl_mlm_slot.slot_id")
								  	   		->select("*",DB::raw("SUM(CASE WHEN points_log_type = 'RPV' THEN points_log_points ELSE 0 END) AS rank_personal_points"),DB::raw("SUM(CASE WHEN points_log_type = 'RGPV' THEN points_log_points ELSE 0 END) AS rank_group_points"))
								  	   		->groupBy("slot_id")
								  	   		->get();
	    }
	

  		$data["_history"]   		   = Tbl_rank_update::where('shop_id',$shop_id)->get();
		$data["include_rpv_on_rgpv"]   = Tbl_mlm_plan_setting::where('shop_id',$shop_id)->first()->include_rpv_on_rgpv;
		$data["points_log"]    		   = Tbl_mlm_slot_points_log::slot()->where("shop_id",$shop_id)    
																->where(function($query){
													      $query->where('points_log_type',"RPV");
													      $query->orWhere('points_log_type',"RGPV");})->get();
    	
    	if(Request::input("edit_date"))
    	{
    		// dd(Request::input("edit_date"));
    		foreach(Request::input("edit_date") as $key => $edit_date)
    		{
    			if($edit_date != "")
    			{
    				$check_date = Tbl_mlm_slot_points_log::slot()->where("shop_id",$shop_id)->where("points_log_id",$key)->first();
    				if($check_date)
    				{
    					if($check_date->points_log_type == "RPV" || $check_date->points_log_type == "RGPV") 
    					{
    						// dd(Carbon::parse($edit_date));
    						$update_log["points_log_date_claimed"] = Carbon::parse($edit_date);
    						Tbl_mlm_slot_points_log::slot()->where("shop_id",$shop_id)->where("points_log_id",$key)->update($update_log);
    					}
    				}
    			}
    		}

    		return Redirect::to("/member/mlm/rank/update");
    	}

    	return view("member.mlm_rank.rank_update_stairstep",$data);
    }

    public function view_rank_update()
    {
    	$rank_update_id 	= Request::input("id");
    	$data["_rank_slot"] = Tbl_rank_update_slot::where('rank_update_id',$rank_update_id)
    											  ->join("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_rank_update_slot.slot_id")
    											  ->join("tbl_customer","tbl_customer.customer_id","=","tbl_mlm_slot.slot_owner")
    											  ->leftJoin("tbl_mlm_stairstep_settings as new_rank","new_rank.stairstep_id","=","tbl_rank_update_slot.new_rank_id")
    											  ->leftJoin("tbl_mlm_stairstep_settings as old_rank","old_rank.stairstep_id","=","tbl_rank_update_slot.old_rank_id")
    											  ->select("*","new_rank.stairstep_name as new_rank_name","old_rank.stairstep_name as old_rank_name")
    											  ->get();
    											  // dd($data);
    	return view("member.mlm_rank.view_rank_update",$data);
    }
    
    public function start()
    {
		$shop_id = $this->getShopId();

		$insert["total_slots"]						= Tbl_mlm_slot::where("shop_id",$shop_id)->count();
		$insert["shop_id"]							= $shop_id;				
		$insert["date_created"]						= Carbon::now();	
		$rank_update_id                             = Tbl_rank_update::insertGetId($insert);
		$data["slot_id"]							= Tbl_mlm_slot::where("shop_id",$shop_id)->orderBy("slot_id","DESC")->first()->slot_id;
		$data["rank_update_id"]						= $rank_update_id;
		$data["status"]								= "Success";
		$data["total_slots"]						= $insert["total_slots"];
		$data["slot_no"]							= Tbl_mlm_slot::where("shop_id",$shop_id)->orderBy("slot_id","DESC")->first()->slot_no;
		// Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id","<",$slot->slot_id)->orderBy("slot_id","DESC")->first();

    	return json_encode($data);
    }

    public function compute()
    {
    	$shop_id 				 	= $this->getShopId();
    	$rank_update_id 		 	= Request::input("rank_update_id");
    	$slot_id 				 	= Request::input("slot_id");
    	$old_rank_id    		 	= 0;
    	$new_rank_id    		 	= 0;
    	$required_leg_update_id  	= 0;
    	$required_leg_update_count 	= 0;
    	$include_rpv_on_rgpv       	= Tbl_mlm_plan_setting::where('shop_id',$shop_id)->first()->include_rpv_on_rgpv;
    	$rank_update    			= Tbl_rank_update::where("shop_id",$shop_id)
    											 	 ->where("complete",0)
    											 	 ->where("rank_update_id",$rank_update_id)
    											 	 ->first();
    	// Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id","<",$slot->slot_id)->orderBy("slot_id","DESC")->first();										  
		$slot_info      			= Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id",$slot_id)->first();

		if($slot_info)
		{		
			$old_rank_id    = $slot_info->stairstep_rank;
	    	if($rank_update)
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
			                	$new_rank_id    			   = $slot_stairstep_new->stairstep_id;
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
		                	$new_rank_id    			   = $slot_stairstep_new->stairstep_id;
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

				$insert_update_rank["rank_update_id"]			= $rank_update_id;				
				$insert_update_rank["slot_id"]					= $slot_id;
				$insert_update_rank["rank_personal_pv"]			= $rpv;
				$insert_update_rank["rank_group_pv"]			= $grpv;
				$insert_update_rank["required_leg_rank_id"]		= $required_leg_update_id;
				$insert_update_rank["current_leg_rank_count"]	= $required_leg_update_count;
				$insert_update_rank["new_rank_id"]				= $new_rank_id;					
				$insert_update_rank["old_rank_id"]				= $old_rank_id;					
				$insert_update_rank["date_created"]				= Carbon::now();

		        Tbl_rank_update_slot::insert($insert_update_rank);

		        $get_new_slot = Tbl_mlm_slot::where("shop_id",$shop_id)->where("slot_id","<",$slot_id)->orderBy("slot_id","DESC")->first();										  

		        $data["current_count"] = Request::input("current_count") + 1;

		        if($get_new_slot)
		        {
		        	$data["slot_no"]  = $get_new_slot->slot_no;
		    		$data["status"]   = "Success";
		    		$data["slot_id"]  = $get_new_slot->slot_id;
		        }	
		        else
		        {
		        	$update_rank_update["complete"] = 1;
		        	Tbl_rank_update::where("rank_update_id",$rank_update_id)->update($update_rank_update);

		    		$data["status"]   = "Complete";
		    		$data["message"]  = "Complete";



		            if($new_rank_id != $old_rank_id)
		            {
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
	    	else
	    	{
	    		$data["status"]   = "Error";
	    		$data["message"]  = "Please try it again.";
	    	}
		}
		else
		{
    		$data["status"]   = "Error";
    		$data["message"]  = "No slot error.";		
		}

		return json_encode($data);
    }
}