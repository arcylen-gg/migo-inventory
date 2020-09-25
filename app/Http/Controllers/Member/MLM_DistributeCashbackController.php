<?php
namespace App\Http\Controllers\Member;

use Request;
use Carbon\Carbon;
use Session;
use Validator;
use App\Models\Tbl_mlm_slot_wallet_log;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_mlm_slot;
use App\Models\Tbl_user;
use App\Models\Tbl_cashback_points_distribute;
use Redirect;
use App\Globals\MLM2;
use App\Globals\Mlm_slot_log;
use Excel;
use stdClass;
use DB;
class MLM_DistributeCashbackController extends Member
{
	public function getShopId()
	{
		return Tbl_user::where("user_email", session('user_email'))->shop()->value('user_shop');
	}

	public function index()
	{
    	$shop_id = $this->getShopId();
    	$data["shop_id"] = $shop_id;
        $data["_slot"]   = Tbl_mlm_slot::where("tbl_mlm_slot.shop_id",$shop_id)
								  	    ->customer();                                                                      
								  	   	
	    $data["_slot"]  = $data["_slot"]->leftjoin("tbl_mlm_stairstep_settings","tbl_mlm_stairstep_settings.stairstep_id","=","tbl_mlm_slot.stairstep_rank")
								  	   	->leftJoin("tbl_mlm_slot_points_log","tbl_mlm_slot_points_log.points_log_slot","=","tbl_mlm_slot.slot_id")
								  	   	->select("*",DB::raw("SUM(CASE WHEN points_log_type = 'RCP' THEN points_log_points ELSE 0 END) AS rank_cashback"))
								  	   	->groupBy("slot_id")
								  	   	->orderBy(DB::raw('rank_cashback'),"DESC")
								  	   	->where("points_log_points","!=",null)
								  	   	->where("points_log_type","RCP")
								  	   	->where("points_log_converted",0)
								  	   	->get();

		return view('member.cashback_points.cashback_points', $data);
	}

	public function distribute()
	{
		$shop_id 		  = $this->getShopId();
  		$_slot   		  = Tbl_mlm_slot::where("tbl_mlm_slot.shop_id",$shop_id)
								  	    ->customer()
								  	   	->leftJoin("tbl_mlm_slot_points_log","tbl_mlm_slot_points_log.points_log_slot","=","tbl_mlm_slot.slot_id")
								  	   	->select("*",DB::raw("SUM(CASE WHEN points_log_type = 'RCP' THEN points_log_points ELSE 0 END) AS rank_cashback"))
								  	   	->groupBy("slot_id")
								  	   	->orderBy(DB::raw('rank_cashback'),"DESC")
								  	   	->where("points_log_points","!=",null)
								  	   	->where("points_log_type","RCP")
								  	   	->where("points_log_converted",0)
								  	   	->get();
								  	   	// dd($_slot);
        
	    $batch_number = Tbl_cashback_points_distribute::where("shop_id",$shop_id)->orderBy("distribute_batch","DESC")->first();

        if(!$batch_number)
        {
        	$batch_number = 1;
        }
        else
        {
        	$batch_number = $batch_number->distribute_batch + 1;
        }

        foreach($_slot as $slot)
        {
        	$update["points_log_converted"] = 1;
        	Tbl_mlm_slot_points_log::where("points_log_type","RCP")
								   ->where("points_log_converted",0)
								   ->where("points_log_slot",$slot->slot_id)
								   ->update($update);

            $log = "Your slot no ".$slot->slot_no." earned an amount of ".number_format($slot->rank_cashback,2)." from cashback points";
            $arry_log['wallet_log_slot']            = $slot->slot_id;
            $arry_log['shop_id']                    = $slot->shop_id;
            $arry_log['wallet_log_slot_sponsor']    = $slot->slot_id;
            $arry_log['wallet_log_details']         = $log;
            $arry_log['wallet_log_amount']          = $slot->rank_cashback;
            $arry_log['wallet_log_plan']            = "CASHBACK_DISTRIBUTE_POINTS";
            $arry_log['wallet_log_status']          = "released";   
            $arry_log['wallet_log_claimbale_on']    = Carbon::now(); 
            $wallet_log_id = Mlm_slot_log::slot_array_with_return($arry_log); 


        	$insert["shop_id"]				= $shop_id;
        	$insert["log_wallet_id"]		= $wallet_log_id;
        	$insert["slot_id"]				= $slot->slot_id;
        	$insert["amount_distributed"]	= $slot->rank_cashback;
        	$insert["distribute_batch"]		= $batch_number;
        	$insert["date_created"]			= Carbon::now();

        	Tbl_cashback_points_distribute::insert($insert);
        }


		return redirect::to("/member/mlm/distribute_cashback");
	}
}