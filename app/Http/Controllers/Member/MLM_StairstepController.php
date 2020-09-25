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
use App\Models\Tbl_flushed_wallet;
use App\Models\Tbl_mlm_plan_setting;
use App\Globals\Mlm_slot_log;
use App\Globals\Mlm_complan_manager;
use Crypt;
use Redirect;
use Request;
use Carbon\Carbon;
use View;
use DB;

class MLM_StairstepController extends Member
{
	public function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

    public function index()
    {
        return view("member.mlm_stairstep.stairstep");
    }

    public function stairstep_view()
    {
        $keyword = Request::input('search');
        $shop_id          = $this->getShopId();
        $data["_history"] = Tbl_stairstep_distribute::where("tbl_stairstep_distribute.shop_id",$shop_id)
                                                    ->leftJoin("tbl_stairstep_distribute_slot","tbl_stairstep_distribute_slot.stairstep_distribute_id","=","tbl_stairstep_distribute.stairstep_distribute_id")
                                                    ->leftJoin("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_stairstep_distribute_slot.slot_id")
                                                    ->leftJoin("tbl_customer","tbl_customer.customer_id","=","tbl_mlm_slot.slot_owner")  
                                                    ->groupBy("tbl_stairstep_distribute_slot.stairstep_distribute_id")
                                                    ->select("tbl_stairstep_distribute.*",DB::raw("COUNT('tbl_stairstep_distribute_slot.slot_id') as total_processed_slot"))
                                                    ->where('slot_no','LIKE','%'.$keyword.'%')
                                                    ->paginate(10);     

        $start  = Request::input("start");
        $end    = Request::input("end");
            
        if(!$start) 
        {
            $start = $this->get_start_date();
        }

        if(!$end)
        {
            $end   = $this->get_end_date($start);
        }
        $data["_slot"]    = Tbl_mlm_slot::where("tbl_mlm_slot.shop_id",$shop_id)
                                        ->customer()
                                        ->leftjoin("tbl_mlm_stairstep_settings","tbl_mlm_stairstep_settings.stairstep_id","=","tbl_mlm_slot.stairstep_rank")
                                        ->leftJoin("tbl_mlm_slot_points_log","tbl_mlm_slot_points_log.points_log_slot","=","tbl_mlm_slot.slot_id")
                                        ->select("*",DB::raw("SUM( ( CASE WHEN points_log_type = 'SGPV' AND points_log_date_claimed >= '".Carbon::parse($start)->format("Y-m-d 00:00:00")."' AND points_log_date_claimed <= '".Carbon::parse($end)->format("Y-m-d 23:59:59")."' THEN points_log_points ELSE 0 END ) ) AS stairstep_points")
                                                    ,DB::raw("SUM( ( CASE WHEN points_log_type = 'SPV' AND points_log_date_claimed >= '".Carbon::parse($start)->format("Y-m-d 00:00:00")."' AND points_log_date_claimed <= '".Carbon::parse($end)->format("Y-m-d 23:59:59")."' THEN points_log_points ELSE 0 END ) ) AS personal_stairstep")
                                                    ,DB::raw("SUM( ( CASE WHEN points_log_type = 'SRB' AND points_log_date_claimed >= '".Carbon::parse($start)->format("Y-m-d 00:00:00")."' AND points_log_date_claimed <= '".Carbon::parse($end)->format("Y-m-d 23:59:59")."' THEN points_log_points ELSE 0 END ) ) AS stairstep_rebates_bonus"))
                                        ->groupBy("slot_id")
                                        ->where('slot_no','LIKE','%'.$keyword.'%')
                                        ->paginate(10);
                                        // ->where("points_log_date_claimed",">=",Carbon::parse($start)->format("Y-m-d 00:00:00"))
                                        // ->where("points_log_date_claimed","<=",Carbon::parse($end)->format("Y-m-d 23:59:59"))


        // dd($data['_slot']);
    

        
        $data["start"] = $start;
        $data["end"]   = $end;
        // dd($start,$end);
        return view("member.mlm_stairstep.stairstep_distribution",$data);
    }

    public function distribution_submit()
    {
    	$start  	      = Carbon::parse(Request::input("start_date"))->format("Y-m-d 00:00:00");
    	$end    	      = Carbon::parse(Request::input("end_date"))->format("Y-m-d 23:59:59");
    	$shop_id	      = $this->getShopId();
        $dynamic_settings = Tbl_mlm_plan_setting::where("shop_id",$shop_id)->first()->stairstep_dynamic_compression; 

    	if($start && $end)
    	{
    		$distribute = Tbl_stairstep_distribute::where("stairstep_distribute_start_date",$start)
    											  ->where("stairstep_distribute_end_date",$end)
    											  ->where("shop_id",$shop_id)
    											  ->where("complete",0)
    											  ->first();

	    	$slot 		= Tbl_mlm_slot::where("shop_id",$shop_id)->get();
	    	$first_slot = Tbl_mlm_slot::where("shop_id",$shop_id)->orderBy("slot_id","ASC")->first();
	    	$last_slot  = Tbl_mlm_slot::where("shop_id",$shop_id)->orderBy("slot_id","DESC")->first();

    		if(!$distribute)
    		{
    			$first                                      = $first_slot ? $first_slot->slot_id : 0;
    			$last                                       = $last_slot ? $last_slot->slot_id : 0;

    			$insert["stairstep_distribute_start_date"]	= $start;
    			$insert["stairstep_distribute_end_date"]	= $end;
    			$insert["shop_id"]							= $shop_id;				
				$insert["from_slot_id"]						= $first;				
				$insert["to_slot_id"]						= $last;				
				$insert["total_slot"]						= count($slot);
    			$insert["date_created"]						= Carbon::now();	
    			$distribute_id                              = Tbl_stairstep_distribute::insertGetId($insert);
    		}	
    		else
    		{
    			$first                                      = $distribute->from_slot_id;
    			$last                                       = $distribute->to_slot_id;
    			$distribute_id 								= $distribute->stairstep_distribute_id;
    		}		

			$slot 	 		= Tbl_stairstep_distribute_slot::where("stairstep_distribute_id",$distribute_id)->orderBy("slot_id","ASC")->first();
    		$slot_lists 	= Tbl_mlm_slot::where("slot_id",">=",$first)
    								  	  ->where("slot_id","<=",$last);  

			if($slot)
			{
				$slot_lists = $slot_lists->where("slot_id","<",$slot);
			}
			else
			{
				$slot_lists = $slot_lists->where("slot_id","<=",$last);
			}

    		$slot_lists     = $slot_lists->where("shop_id",$shop_id)
    								  	 ->orderBy("slot_id","DESC")
    								  	 ->get();

    		// dd($slot_lists);						  	  
            if($slot_lists)
            {
            	$data["status"]		   = "Success";
            }
            else
            {
            	$update_distribute["complete"]     = 1;
            	$data["status"]		   			   = "Error";
            	$data["message"]	   			   = "No slot to convert, please try again";

				Tbl_stairstep_distribute::where("distribute_id",$distribute_id)->update($update_distribute);
            }
    	}
    	else
    	{
    		$data["status"]   = "Error";
    		$data["message"]  = "Please fill the start date and end date.";
    	}


		if($data["status"] == "Error")
		{
			return Redirect::to("/member/mlm/stairstep/distribution")->with('Error',$data["message"]);
		}
		else if($data["status"] == "Success")
		{
            $dynamic_compression = null;
            $dynamic_slot        = null;
            $dynamic_ctr         = 0;

			foreach($slot_lists as $list)
			{
                $total_dynamic = 0;
				$sgpv =  Tbl_mlm_slot_points_log::where("points_log_slot",$list->slot_id)
						   					    ->where('points_log_date_claimed',">=",$start)
						   					    ->where('points_log_date_claimed',"<=",$end)
						   					    ->where("points_log_type","SGPV")
						   					    ->where("points_log_converted","0")
						   					    ->sum("points_log_points");		

                $spv  =  Tbl_mlm_slot_points_log::where("points_log_slot",$list->slot_id)
                                                ->where('points_log_date_claimed',">=",$start)
                                                ->where('points_log_date_claimed',"<=",$end)
                                                ->where("points_log_type","SPV")
                                                ->where("points_log_converted","0")
                                                ->sum("points_log_points");

		    	$srb  =  Tbl_mlm_slot_points_log::where("points_log_slot",$list->slot_id)
						   					    ->where('points_log_date_claimed',">=",$start)
						   					    ->where('points_log_date_claimed',"<=",$end)
						   					    ->where("points_log_type","SRB")
						   					    ->where("points_log_converted","0")
						   					    ->sum("points_log_points");

				$settings         = Tbl_mlm_stairstep_settings::where("stairstep_id",$list->stairstep_rank)->first();
                $give_wallet      = 0;
				$give_srb         = 0;
				if($settings)
				{
					if($settings->commission_multiplier != 0)
					{
						$maintenance 	  = $settings->stairstep_pv_maintenance;
						if($spv >= $maintenance)
						{
                            if($dynamic_settings == 1)
                            {
                                if($dynamic_ctr != 0)
                                {
                                    foreach($dynamic_compression as $key => $compress)
                                    {
                                        $wallet_log_details = "Your slot ".$list->slot_no." earned ".$dynamic_compression[$key]." from Dynamic Compression";                     
                                        Mlm_slot_log::slot($list->slot_id, $dynamic_slot[$key], $wallet_log_details, $dynamic_compression[$key], "DYNAMIC_COMPRESSION", "released",Carbon::now()); 
                                        $total_dynamic = $total_dynamic + $dynamic_compression[$key];
                                        unset($dynamic_compression[$key]);
                                        unset($dynamic_slot[$key]);
                                        $dynamic_ctr--;
                                    }
                                }
                            }

                            $give_wallet = $settings->commission_multiplier * $sgpv;
                            if($give_wallet != 0)
                            {
                                $wallet_log_details = "Your slot ".$list->slot_no." earned ".$give_wallet." from Stairstep Bonus";                     
                                Mlm_slot_log::slot($list->slot_id, $list->slot_id, $wallet_log_details, $give_wallet, "STAIRSTEP", "released",Carbon::now());                      
                            }							

                            $give_srb = $srb;
							if($give_srb != 0)
							{
		   						$wallet_log_details = "Your slot ".$list->slot_no." earned ".$give_srb." from Rebates Bonus";    				   
		   						Mlm_slot_log::slot($list->slot_id, $list->slot_id, $wallet_log_details, $give_srb, "REBATES_BONUS", "released",Carbon::now());   				   
							}
						}
                        else
                        {
                            if($dynamic_settings == 1)
                            {
                                $give_wallet = $settings->commission_multiplier * $sgpv;
                                if($give_wallet != 0)
                                {
                                    $dynamic_compression[$dynamic_ctr] = $give_wallet;
                                    $dynamic_slot[$dynamic_ctr]        = $list->slot_id;
                                    $dynamic_ctr++;
                                }
                            }
                        }
					}
				}	

				$update_log["points_log_converted"] = 1; 
                Tbl_mlm_slot_points_log::where("points_log_slot",$list->slot_id)
                                           ->whereBetween('points_log_date_claimed', array($start, $end))
                                           ->where("points_log_type","SGPV")
                                           ->where("points_log_converted","0")
                                           ->update($update_log);		

                Tbl_mlm_slot_points_log::where("points_log_slot",$list->slot_id)
					    				   ->whereBetween('points_log_date_claimed', array($start, $end))
					    				   ->where("points_log_type","SRB")
					    				   ->where("points_log_converted","0")
					    				   ->update($update_log);

				$insert_distribute_slot["slot_id"]  				= $list->slot_id;
				$insert_distribute_slot["stairstep_distribute_id"]  = $distribute_id;
				$insert_distribute_slot["processed_current_rank"]   = $list->stairstep_rank;
				$insert_distribute_slot["processed_personal_pv"]    = $spv;
				$insert_distribute_slot["processed_required_pv"]    = $settings ? $settings->stairstep_pv_maintenance : 0;
				$insert_distribute_slot["processed_multiplier"]     = $settings ? $settings->commission_multiplier : 0;
				$insert_distribute_slot["processed_earned"]  	    = $give_wallet ? $give_wallet + $total_dynamic : 0 + $total_dynamic;
				$insert_distribute_slot["processed_status"]  	    = 1;


				Tbl_stairstep_distribute_slot::insert($insert_distribute_slot);					    
			}

        	$update_distribute["complete"]     = 1;
			Tbl_stairstep_distribute::where("stairstep_distribute_id",$distribute_id)->update($update_distribute);


            if($dynamic_ctr != 0)
            {
                foreach($dynamic_compression as $key => $compress)
                {
                    $insert_flushed["flushed_amount"]       = $dynamic_compression[$key];
                    $insert_flushed["flushed_by"]           = 0;
                    $insert_flushed["shop_id"]              = $shop_id;
                    $insert_flushed["flushed_date_created"] = Carbon::now();
                    $insert_flushed["flushed_by_slot"]      = $dynamic_slot[$key];
                    $insert_flushed["complan"]              = "DYNAMIC_COMPRESSION";
                    Tbl_flushed_wallet::insert($insert_flushed);
                }
            }

			return Redirect::to("/member/mlm/stairstep/distribution")->with('Success','Distribution success');
		}  	
    }

    public function view_summary()
    {
    	$id 		= Request::input("id");
    	$shop_id 	= $this->getShopId();

        $distribute         = Tbl_stairstep_distribute::where("stairstep_distribute_id",$id)->first();    													   
    	$data["_stairstep"] = Tbl_mlm_slot::join("tbl_customer","tbl_customer.customer_id","=","tbl_mlm_slot.slot_owner")
							              ->leftJoin('tbl_stairstep_distribute_slot', function($join) use ($id)
								          {
								              $join->on('tbl_stairstep_distribute_slot.slot_id', '=', 'tbl_mlm_slot.slot_id')->where("stairstep_distribute_id",$id);
							              	  $join->leftJoin('tbl_mlm_stairstep_settings.stairstep_id', '=', 'tbl_stairstep_distribute_slot.processed_current_rank');
    									  })
							              ->where("tbl_mlm_slot.shop_id",$shop_id)
							              ->where("tbl_mlm_slot.slot_id",">=",$distribute->from_slot_id)
							              ->where("tbl_mlm_slot.slot_id","<=",$distribute->to_slot_id)
    									  ->get();
        // dd($data);
    	return view("member.mlm_stairstep.view_summary",$data);
    }

    public function get_start_date()
    {
    	$shop_id 		   = $this->getShopId();
    	$last_distribute   = Tbl_stairstep_distribute::where("shop_id",$shop_id)->orderBy("stairstep_distribute_end_date","DESC")->first();
    	
    	$first_slot_points = Tbl_mlm_slot_points_log::where("shop_id",$shop_id)
    												->join("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_mlm_slot_points_log.points_log_slot")
    												->orderBy("points_log_date_claimed","")
    												->first();

    	$shop_date_created = Tbl_shop::where("shop_id",$shop_id)->first(); 

    	if($last_distribute)
    	{
    		$returned_date = $last_distribute->stairstep_distribute_end_date;
    	}
    	else if($first_slot_points)
    	{
    		$returned_date = $shop_date_created->shop_date_created;
    	}	
    	else
    	{
    		$returned_date = $shop_date_created->shop_date_created;
    	}

    	$returned_date = Carbon::parse($returned_date)->format("m/d/Y");

    	return $returned_date;
    }

    public function get_end_date($start)
    {
    	return Carbon::parse($start)->addDays(15)->format("m/d/Y");
    }
}