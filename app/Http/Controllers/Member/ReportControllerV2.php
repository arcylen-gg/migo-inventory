<?php
namespace App\Http\Controllers\Member;
use App\Models\Tbl_brown_rank;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_tree_sponsor;
use App\Globals\Mlm_complan_manager;
use Request;
use Excel;

class ReportControllerV2 extends Member
{
	public function getRewardBrownRank()
	{
		if(Request::input("rank_id") == "")
		{
			$rank_id = Tbl_brown_rank::value("rank_id");
		}
		else
		{
			$rank_id = Request::input("rank_id");
		}

		$data["rank_id"] = $rank_id;
		$data["_rank"] = Tbl_brown_rank::get();
		$data["_slot"] = Tbl_mlm_slot::where("tbl_mlm_slot.shop_id", $this->user_info->shop_id)->where("brown_rank_id", $rank_id)->customer()->info()->get();

		foreach($data["_slot"] as $key => $slot)
		{
            /* BROWN RANK DETAILS */
            $brown_current_rank = Tbl_brown_rank::where("rank_id", $slot->brown_rank_id)->first();

            if($brown_current_rank)
            {
                $data["_slot"][$key]->brown_current_rank = strtoupper($brown_current_rank->rank_name);
            }
            else
            {
                $data["_slot"][$key]->brown_current_rank = strtoupper("NO RANK");
            }
            

            if($slot->brown_rank_id)
            {
                $brown_next_rank = Tbl_brown_rank::where("rank_id",">", $slot->brown_rank_id)->orderBy("rank_id")->first();
            }
            else
            {
                $brown_next_rank = null;
            }

            if($brown_next_rank)
            {
                $data["_slot"][$key]->brown_next_rank = strtoupper($brown_next_rank->rank_name);
                $brown_rank_required_slots = $brown_next_rank->required_slot;
                $brown_count_required = Tbl_tree_sponsor::where("sponsor_tree_parent_id", $slot->slot_id)->where("sponsor_tree_level", "<=", $brown_next_rank->required_uptolevel)->count();
                $brown_count_required_direct = Tbl_tree_sponsor::where("sponsor_tree_parent_id", $slot->slot_id)->where("sponsor_tree_level", 1)->count();
                $data["_slot"][$key]->brown_next_rank_requirements = $brown_count_required_direct . " / " . $brown_count_required;
            }
            else
            {
                $data["_slot"][$key]->brown_next_rank = strtoupper("NO NEXT RANK");
                $brown_rank_required_slots = "NO NEXT RANK";
                $brown_count_required = "NO NEXT RANK";
                $data["_slot"][$key]->brown_next_rank_requirements = "NO NEXT RANK";
            }
		}


		return view('member.reports.reward.brownrank', $data);
	}
    public function getRewardBrownRankExcel()
    {
        $data["_rank"] = Tbl_brown_rank::get();

        Excel::create("brown-ranking-sheet", function($excel) use ($data)
        {
            foreach($data["_rank"] as $rank)
            {
                $excel->sheet($rank->rank_name, function($sheet) use ($data, $rank)
                {
                    $sheet->setOrientation('landscape');
                    $data["_slot"] = Tbl_mlm_slot::where("tbl_mlm_slot.shop_id", $this->user_info->shop_id)->where("brown_rank_id", $rank->rank_id)->customer()->info()->get();
                
                    foreach($data["_slot"] as $key => $slot)
                    {
                        /* BROWN RANK DETAILS */
                        $brown_current_rank = Tbl_brown_rank::where("rank_id", $slot->brown_rank_id)->first();

                        if($brown_current_rank)
                        {
                            $data["_slot"][$key]->brown_current_rank = strtoupper($brown_current_rank->rank_name);
                        }
                        else
                        {
                            $data["_slot"][$key]->brown_current_rank = strtoupper("NO RANK");
                        }
                        

                        if($slot->brown_rank_id)
                        {
                            $brown_next_rank = Tbl_brown_rank::where("rank_id",">", $slot->brown_rank_id)->orderBy("rank_id")->first();
                        }
                        else
                        {
                            $brown_next_rank = null;
                        }

                        if($brown_next_rank)
                        {
                            $data["_slot"][$key]->brown_next_rank = strtoupper($brown_next_rank->rank_name);
                            $brown_rank_required_slots = $brown_next_rank->required_slot;
                            $brown_count_required = Tbl_tree_sponsor::where("sponsor_tree_parent_id", $slot->slot_id)->where("sponsor_tree_level", "<=", $brown_next_rank->required_uptolevel)->count();
                            $brown_count_required_direct = Tbl_tree_sponsor::where("sponsor_tree_parent_id", $slot->slot_id)->where("sponsor_tree_level", 1)->count();
                            $data["_slot"][$key]->brown_next_rank_requirements = $brown_count_required_direct . " / " . $brown_count_required;
                        }
                        else
                        {
                            $data["_slot"][$key]->brown_next_rank = strtoupper("NO NEXT RANK");
                            $brown_rank_required_slots = "NO NEXT RANK";
                            $brown_count_required = "NO NEXT RANK";
                            $data["_slot"][$key]->brown_next_rank_requirements = "NO NEXT RANK";
                        }
                    }

                    $sheet->loadView('member.reports.reward.brownrankexcel', $data);
                });
            }
        })->export('xlsx');
    }
    public function getRewardBrownRankCompute()
    {
        $shop_id = $this->user_info->shop_id;

        $data["page"] = "Rank Recompute";
        $data["count"] = Tbl_mlm_slot::where("shop_id", $shop_id)->count();
        $data["_slot"] = Tbl_mlm_slot::customer()->where("tbl_mlm_slot.shop_id", $shop_id)->get();
        return view('member.reports.reward.brownrankrecompute', $data);
    }
    public function postRewardBrownRankCompute()
    {
        $slot_id = Request::input("slot_id");
        $slot_info = Tbl_mlm_slot::where("slot_id", $slot_id)->first();
        Mlm_complan_manager::brown_rank($slot_info);
    }
}