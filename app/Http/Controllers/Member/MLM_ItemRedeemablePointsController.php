<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_user;
use App\Models\Tbl_item_redeemable_request;
use App\Models\Tbl_item_redeemable_points;
use App\Models\Tbl_item_redeemable_report;
use App\Tbl_item_redeemable;
use App\Models\Tbl_mlm_slot;
use Crypt;
use Redirect;
use Illuminate\Http\Request;
use View;
use Session;
use DB;
use Carbon\Carbon;


class MLM_ItemRedeemablePointsController extends Member
{
	public function index()
	{
		// $shop_id 				    = $this->user_info->shop_id;
		// $data["_request_pending"]   = Tbl_item_redeemable_request::where("tbl_item_redeemable_request.shop_id",$shop_id)
		// 														 ->join("tbl_item_redeemable","tbl_item_redeemable_request.item_redeemable_id","=","tbl_item_redeemable.item_redeemable_id")
		// 														 ->join("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_item_redeemable_request.slot_id")
		// 														 ->join("tbl_customer","tbl_customer.customer_id","=","tbl_mlm_slot.slot_owner")
		// 														 ->where("status","PENDING")
		// 														 ->get();
		// $data["_request_cancelled"] = Tbl_item_redeemable_request::where("tbl_item_redeemable_request.shop_id",$shop_id)
		// 														 ->join("tbl_item_redeemable","tbl_item_redeemable_request.item_redeemable_id","=","tbl_item_redeemable.item_redeemable_id")
		// 														 ->join("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_item_redeemable_request.slot_id")
		// 														 ->join("tbl_customer","tbl_customer.customer_id","=","tbl_mlm_slot.slot_owner")
		// 														 ->where("status","CANCELLED")
		// 														 ->get();
		// $data["_request_complete"]  = Tbl_item_redeemable_request::where("tbl_item_redeemable_request.shop_id",$shop_id)
		// 														 ->join("tbl_item_redeemable","tbl_item_redeemable_request.item_redeemable_id","=","tbl_item_redeemable.item_redeemable_id")
		// 														 ->join("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_item_redeemable_request.slot_id")
		// 														 ->join("tbl_customer","tbl_customer.customer_id","=","tbl_mlm_slot.slot_owner")
		// 														 ->where("status","COMPLETE")
		// 														 ->get();
		// 														 dd($data);
	    return view('member.redeemable.redeemable_request');
	}

	public function get_table()
	{
		$shop_id 		     = $this->user_info->shop_id;
        $data["activetab"]   = request("activetab");
  		$data["_redeemable"] = Tbl_item_redeemable_request::where("tbl_item_redeemable_request.shop_id",$shop_id)
														  ->join("tbl_item_redeemable","tbl_item_redeemable_request.item_redeemable_id","=","tbl_item_redeemable.item_redeemable_id")
														  ->join("tbl_mlm_slot","tbl_mlm_slot.slot_id","=","tbl_item_redeemable_request.slot_id")
														  ->join("tbl_customer","tbl_customer.customer_id","=","tbl_mlm_slot.slot_owner")
														  ->where("status",request("activetab"))
														  ->get();
		// dd($data['_redeemable']);
        return view("member.redeemable.redeemable_request_table", $data);
	}

	public function submitpoints(Request $request)
	{
		$action = $request->action;
		$id     = $request->id;
		$check  = Tbl_item_redeemable_request::where("redeemable_request_id",$id)->where("shop_id",$this->user_info->shop_id)->where("status","PENDING")->first();
		if($check)
		{
			if($action == "complete")
			{
                $update_request["status"]   = "COMPLETE";
                Tbl_item_redeemable_request::where("redeemable_request_id",$id)->update($update_request);

                //not yet done
                $redeemable_id = Tbl_item_redeemable_request::where("redeemable_request_id",$id)->first()->item_redeemable_id;
                $redeemable_item = Tbl_item_redeemable::where('item_redeemable_id',$redeemable_id)->first()->item_name;
                $insert_report['slot_id'] = $check->slot_id;
                $insert_report["shop_id"] = $this->user_info->shop_id;
                $insert_report["amount"] = $check->amount;
                $insert_report['log_type'] = 'Approved';
                // you redeem <item> for <cost>. Please wait for admin's approval.
                $insert_report['log'] = 'An admin approved your request to redeem '.$redeemable_item.'.';
                $insert_report['date_created'] = Carbon::now();
                $insert_report['slot_owner'] = Tbl_mlm_slot::where('slot_id',$check->slot_id)->first()->slot_owner;
                Tbl_item_redeemable_report::insert($insert_report);
                
                $response["status"] 		= "success";
                $response["status_message"] = "Success";
                return Redirect::back()->with("response",$response);
			}
			else if($action == "cancel")
			{
                $insert["amount"]       = $check->amount;
                $insert["shop_id"]      = $this->user_info->shop_id;
                $insert["slot_id"]      = $check->slot_id;
                $insert["date_created"] = Carbon::now();
                Tbl_item_redeemable_points::insert($insert);

                $redeemable_id = Tbl_item_redeemable_request::where("redeemable_request_id",$id)->first()->item_redeemable_id;
                $redeemable_item = Tbl_item_redeemable::where('item_redeemable_id',$redeemable_id)->first()->item_name;
                $insert_report['slot_id'] = $check->slot_id;
                $insert_report["shop_id"] = $this->user_info->shop_id;
                $insert_report["amount"] = $check->amount;
                $insert_report['log_type'] = 'Cancelled';
                // you redeem <item> for <cost>. Please wait for admin's approval.
                $insert_report['log'] = 'An admin rejected your request to redeem '.$redeemable_item.'.';
                $insert_report['date_created'] = Carbon::now();
                $insert_report['slot_owner'] = Tbl_mlm_slot::where('slot_id',$check->slot_id)->first()->slot_owner;
                Tbl_item_redeemable_report::insert($insert_report);

                $update_request["status"]   = "CANCELLED";
                Tbl_item_redeemable_request::where("redeemable_request_id",$id)->update($update_request);
                Tbl_item_redeemable::where("item_redeemable_id",$redeemable_id)->where("shop_id",$this->user_info->shop_id)->decrement('number_of_redeem');

                $response["status"] 		= "success";
                $response["status_message"] = "Success";
                return Redirect::back()->with("response",$response);
			}
		}
	}
}