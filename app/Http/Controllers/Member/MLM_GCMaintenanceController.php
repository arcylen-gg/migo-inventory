<?php
namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_mlm_slot_bank;
use App\Models\Tbl_mlm_slot_money_remittance;
use App\Models\Tbl_mlm_slot_coinsph;
use App\Models\Tbl_mlm_encashment_settings;
use App\Models\Tbl_payout_bank;
use App\Models\Tbl_payout_bank_shop;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_shop;
use App\Models\Tbl_customer;
use App\Globals\Currency;
use Redirect;
use App\Globals\MLM2;
use Excel;
use stdClass;

class MLM_GCMaintenanceController extends Member
{
	public function getIndex()
	{
		$data["page"] = "GC Maintenance";
		return view('member.mlm_gcmaintenance.gcmaintenance', $data);
	}
	public function getProcess()
	{
		$data["page"] = "GC Maintenance";
		return view('member.mlm_gcmaintenance.gcmaintenance_process', $data);
	}
	public function anyProcessOutput()
	{
		$data["page"] 	= "GC Maintenance Output";
		$shop_id 		= $this->user_info->shop_id;
		$_customer		= Tbl_customer::where("shop_id", $shop_id)->get();

		foreach($_customer as $key => $customer)
		{
			$_slot 				= Tbl_mlm_slot::where("slot_owner", $customer->customer_id)->currentWallet()->get();
			$customer_wallet 	= 0;
			$slot_count 		= 0;

			foreach($_slot as  $slot)
			{
				$customer_wallet += $slot->current_wallet;
				$slot_count++;
			}

			if($customer_wallet >= request("amount"))
			{
				$_customer[$key]->customer_wallet 					= $customer_wallet;
				$_customer[$key]->slot_count 						= $slot_count;
				$_customer[$key]->maintenance 						= request("maintenance");
				$_customer[$key]->wallet_after_maintenance 			= $customer_wallet - request("maintenance");
				$_customer[$key]->display_wallet_after_maintenance 	= Currency::format($customer_wallet - request("maintenance"));
			}
			else
			{
				unset($_customer[$key]);
			}	
		}

		if(request()->isMethod("post"))
		{
			dd($data);
		}
		else
		{
			$data["_customer"] = $_customer;
			return view('member.mlm_gcmaintenance.gcmaintenance_process_output', $data);
		}


	}
	public function postIndexTable()
	{
		$shop_id 		= $this->user_info->shop_id;
		$query 			= Tbl_mlm_slot_wallet_log::where("tbl_mlm_slot_wallet_log.shop_id", $shop_id)->slot()->customer();

		$query->where("wallet_log_plan", "GC MAINTENANCE");

		/* PAYOUT IMPORTATION QUERIES */
		$query->where("wallet_log_amount", "<", 0);
		$query->orderBy("wallet_log_date_created", "desc");
		
		/* SEARCH QUERY */
		$search_key = Request::input("search");

		if($search_key != "")
		{
			$query->where(function($q) use ($search_key)
			{
				$q->orWhere("first_name", "LIKE", "%$search_key%");
				$q->orWhere("last_name", "LIKE", "%$search_key%");
				$q->orWhere("slot_no", "LIKE", "%$search_key%");
			});
		}

		$data["_payout"]				= null;
		$data["total_payout"]			= Currency::format($query->sum("wallet_log_amount") * -1);
		$data["total_request"]			= Currency::format($query->sum("wallet_log_request"));
		$data["total_tax"]				= Currency::format($query->sum("wallet_log_tax"));
		$data["total_service"]			= Currency::format($query->sum("wallet_log_service_charge"));
		$data["total_other"]			= Currency::format($query->sum("wallet_log_other_charge"));
		$data["__payout"] 				= $query->paginate(5);

        /* GET TOTALS */
        $grand_total_slot_wallet  			= Tbl_mlm_slot_wallet_log::slot()->where("tbl_mlm_slot.shop_id", $this->user_info->shop_id)->sum("wallet_log_amount");
    	$grand_total_payout       			= Tbl_mlm_slot_wallet_log::slot()->where("tbl_mlm_slot.shop_id", $this->user_info->shop_id)->where("wallet_log_amount", "<", 0)->sum("wallet_log_amount") * -1;
    	$grand_total_earnings     			= Tbl_mlm_slot_wallet_log::slot()->where("tbl_mlm_slot.shop_id", $this->user_info->shop_id)->where("wallet_log_amount", ">", 0)->sum("wallet_log_amount");

        /* FORMAT TOTALS */
    	$data["grand_total_slot_wallet"]     	= Currency::format($grand_total_slot_wallet);
    	$data["grand_total_slot_earnings"]   	= Currency::format($grand_total_earnings);
    	$data["grand_total_payout"]          	= Currency::format($grand_total_payout);

		foreach($data["__payout"] as $key => $payout)
		{
			$data["_payout"][$key] 										= $payout;
			$reward_slot 												= Tbl_mlm_slot::where("slot_id", $payout->wallet_log_slot)->first();
			$data["_payout"][$key]->display_wallet_log_amount 			= Currency::format($payout->wallet_log_amount * -1);
			$data["_payout"][$key]->time_ago 							= time_ago($payout->wallet_log_date_created);
			$data["_payout"][$key]->display_date 						= date("m/d/Y", strtotime($payout->wallet_log_date_created));
			$data["_payout"][$key]->slot_no 							= $reward_slot->slot_no;
			$data["_payout"][$key]->display_wallet_log_request 			= Currency::format($payout->wallet_log_request);
			$data["_payout"][$key]->display_wallet_log_tax 				= Currency::format($payout->wallet_log_tax);
			$data["_payout"][$key]->display_wallet_log_service_charge 	= Currency::format($payout->wallet_log_service_charge);
			$data["_payout"][$key]->display_wallet_log_other_charge 	= Currency::format($payout->wallet_log_other_charge);
		}

		return view('member.mlm_gcmaintenance.gcmaintenance_table', $data);
	}
	public function getJca()
	{
		$shop_id 		= $this->user_info->shop_id;
		$query 			= Tbl_mlm_slot_wallet_log::where("tbl_mlm_slot_wallet_log.shop_id", $shop_id)->slot()->customer();

		$query->where("wallet_log_plan", "GC MAINTENANCE");

		/* PAYOUT IMPORTATION QUERIES */
		$query->where("wallet_log_amount", "<", 0);
		$query->orderBy("wallet_log_date_created", "desc");
		
		/* SEARCH QUERY */
		$search_key = Request::input("search");

		if($search_key != "")
		{
			$query->where(function($q) use ($search_key)
			{
				$q->orWhere("first_name", "LIKE", "%$search_key%");
				$q->orWhere("last_name", "LIKE", "%$search_key%");
				$q->orWhere("slot_no", "LIKE", "%$search_key%");
			});
		}

		$data["_payout"]				= null;
		$data["total_payout"]			= Currency::format($query->sum("wallet_log_amount") * -1);
		$data["total_request"]			= Currency::format($query->sum("wallet_log_request"));
		$data["total_tax"]				= Currency::format($query->sum("wallet_log_tax"));
		$data["total_service"]			= Currency::format($query->sum("wallet_log_service_charge"));
		$data["total_other"]			= Currency::format($query->sum("wallet_log_other_charge"));
		$_gc 							= $query->get();

		foreach($_gc as $key => $gc)
		{
			$insert_points[$key]["points_log_complan"] = "MAINTENANCE";
			$insert_points[$key]["points_log_slot"] = $gc->slot_id;
			$insert_points[$key]["points_log_Sponsor"] = 0;
			$insert_points[$key]["points_log_date_claimed"] = $gc->wallet_log_date_created;
			$insert_points[$key]["points_log_type"] = "GC";
			$insert_points[$key]["points_log_from"] = "GC Maintenance";
			$insert_points[$key]["points_log_points"] = $gc->wallet_log_amount * -1;
			echo "<div>" . $gc->wallet_log_amount . " GC was stored to SLOT (" . $gc->slot_no . ") - " . date("m/d/Y", strtotime($gc->wallet_log_date_created)) . "</div>";
		}

		Tbl_mlm_slot_points_log::insert($insert_points);
	}
}