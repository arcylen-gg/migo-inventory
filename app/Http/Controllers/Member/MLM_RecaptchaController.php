<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;

use App\Models\Tbl_mlm_point_log_setting;
use App\Models\Tbl_recaptcha_setting;
use App\Models\Tbl_recaptcha_pool_amount;
use App\Models\Tbl_mlm_slot_wallet_log;

use Carbon\Carbon;

class MLM_RecaptchaController extends Member
{
    public function index()
    {
    	$data['page'] = "Recaptcha";
    	return view("member.mlm_recaptcha.mlm_recaptcha",$data);
    }
    public function table()
    {
    	$data['logs'] = Tbl_mlm_slot_wallet_log::Recaptcha()->where('wallet_log_plan','RECAPTCHA')
				    	->where('tbl_mlm_slot_wallet_log.shop_id',$this->user_info->shop_id)
				    	->orderBy('wallet_log_date_created','DESC')
				    	->paginate(10);
    	return view('member.mlm_recaptcha.mlm_recaptcha_table',$data);
    }
    public function setting()
    {
    	$data['page'] 		= 'Recaptcha Setting';
    	$setting_points 	= Tbl_recaptcha_setting::where('shop_id',$this->user_info->shop_id)->first();
        if($setting_points)
        {
            $data['point'] = $setting_points->point;
        }
    	else
        {
            $data['point'] = 0;
        }
    	return view('member.mlm_recaptcha.mlm_recaptcha_setting',$data);
    }
    public function submit_setting(Request $request)
    {
    	$setting_points = Tbl_recaptcha_setting::where('shop_id',$this->user_info->shop_id)->first();
    	if(count($setting_points)>0)
    	{
            $update['point']    = $request->point;
            $update['schedule'] = $request->schedule;
    		Tbl_recaptcha_setting::where('shop_id',$this->user_info->shop_id)->update($update);
    	}
    	else
    	{
    		$insert['shop_id'] 	= $this->user_info->shop_id;
    		$insert['point'] 	= $request->point;
            $insert['schedule'] = $request->schedule;
    		Tbl_recaptcha_setting::insert($insert);
    	}
    	$response['call_function'] = 'success_setting';
    	return json_encode($response);
    }
    public function add_pool()
    {
    	$data['page'] = 'Add Amount';
    	return view('member.mlm_recaptcha.add_pool',$data);
    }
    public function submit_add_pool(Request $request)
    {
    	$pool = $this->getTotalPool();
    	$acquired_points = $this->getTotalAcquiredPoints();
    	$remaining_points = ($pool-$acquired_points);
    	if($remaining_points+$request->amount>=0)
    	{
    		$insert['shop_id'] 			= $this->user_info->shop_id;
	    	$insert['amount']			= $request->amount;
	    	$insert['date_created'] 	= Carbon::now();

	    	if(Tbl_recaptcha_pool_amount::insert($insert))
	    	{
	    		$response['call_function'] = 'success_pool';
	    	}
	    	else
	    	{
	    		$response['call_function'] = 'error';
	    	}
    	}
    	else
    	{
    		$response['call_function'] = 'negative';
    	}
    	

    	return json_encode($response);

    }
    public function getTotalAcquiredPoints()
    {
    	$acquired_points = Tbl_mlm_slot_wallet_log::where('wallet_log_plan','RECAPTCHA')
				    	->where('shop_id',$this->user_info->shop_id)
				    	->sum('wallet_log_amount');
		return $acquired_points;
    }
    public function getTotalPool()
    {
    	$pool = Tbl_recaptcha_pool_amount::where('shop_id',$this->user_info->shop_id)->sum('amount');
    	return $pool;
    }
    public function load_pool()
    {
    	$query = Tbl_recaptcha_pool_amount::where('shop_id',$this->user_info->shop_id)->sum('amount');
    	return currency('PHP',$query);
    }
    public function load_points()
    {
    	if($query =Tbl_recaptcha_setting::where('shop_id',$this->user_info->shop_id)->first())
        {
             $point = $query->point;
        }
        else
        {
            $point = 0;
        }
        
    	return currency('PHP',$point);
    }
    
}
