<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;
use App\Models\Tbl_stairstep_distribute;
use App\Models\Tbl_stairstep_distribute_slot;
use App\Models\Tbl_mlm_slot_points_log;
use App\Models\Tbl_user;
use App\Models\Tbl_tree_placement;
use App\Models\Tbl_mlm_stairstep_settings;
use App\Models\Tbl_mlm_slot;
use App\Globals\Mlm_slot_log;
use App\Globals\Mlm_complan_manager;
use Crypt;
use Redirect;
use Request;
use Carbon\Carbon;
use View;
use App\Models\Tbl_mlm_plan;
use DB;
use App\Globals\abs\AbsMain;
use App\Models\Tbl_tour_wallet;
use App\Models\Tbl_settings;
use App\Globals\Settings;
class Mlm_ComplanSetupController extends Member
{
	public function index()
	{
		# code...
		$data = [];
		$shop_id = $this->user_info->shop_id;

		$count = Tbl_mlm_plan::where('shop_id', $shop_id)->where('marketing_plan_code', 'BINARY_PROMOTIONS')
		->where('marketing_plan_enable', 1)
		->count();
		if($count >= 1)
		{
			$data['links'][0]['label'] = 'Binary Promotions Setup';
			$data['links'][0]['link'] = '/member/mlm/complan_setup/binary_pro';
		}		

		$merchant_school = $this->user_info->shop_merchant_school;
		if($merchant_school == 1)
		{
			$data['links'][1]['label'] = 'School Merchant';
			$data['links'][1]['link'] = '/member/mlm/merchant_school';
		}

		$tours_wallet = $this->user_info->shop_wallet_tours;
		if($tours_wallet == 1)
		{
			$data['links'][2]['label'] = 'Airline Ticketing';
			$data['links'][2]['link'] = '/member/mlm/tours_wallet';
		}

		$count = Tbl_mlm_plan::where('shop_id', $shop_id)->where('marketing_plan_code', 'UNILEVEL_REPURCHASE_POINTS')
		->where('marketing_plan_enable', 1)
		->count();

		$restrict['PhilTECH'] = ['PhilTECH'];
		// $restrict
		if($count >= 1)
		{
			if(!isset($restrict[ $this->user_info->shop_key] ))
			{
				$data['links'][3]['label'] = 'Unilevel Distribute';
				$data['links'][3]['link'] = '/member/mlm/complan_setup/unilevel/distribute';
			}
			
		}		

		if($this->user_info->shop_key == 'myphone')
		{
			$data['other_settings_myphone'] = $this->myphone_other_settings();
		}

		return view('member.mlm_complan_setup.index', $data);
	}
	public function myphone_other_settings()
	{
		$shop_id = $this->user_info->shop_id;
		$settings = Tbl_settings::where('shop_id', $shop_id)->get()->keyBy('settings_key');

		if(isset($settings['myphone_require_sponsor']))
		{
			$data['settings_myphone_require_sponsor'] = 	Tbl_settings::where('settings_key', 'myphone_require_sponsor')->where('shop_id', $shop_id)->first();
		}
		else
		{
			$insert['settings_key'] = 'myphone_require_sponsor';
			$insert['settings_value'] = 1;
			$insert['settings_setup_done'] = 1;
			$insert['shop_id'] = $shop_id;

			Tbl_settings::insert($insert);

			$data['settings_myphone_require_sponsor'] = 	Tbl_settings::where('settings_key', $insert['settings_key'])->where('shop_id', $shop_id)->first();
		}
		return view('member.mlm_complan_setup.myphone.index', $data);
	}
	public function myphone_other_settings_update()
	{
		$settings_key = Request::input('settings_key');
		$settings_value = Request::input('settings_value');
		Settings::update_settings($settings_key, $settings_value);

		$data['status'] = 'success';
		$data['message'] = 'settings_changed';

		return json_encode($data);

	}
	public function binary_promotions()
	{
		$data = [];
		return view('member.mlm_complan_setup.binary_complan', $data);
	}
	public function merchant_school($shop_key)
	{
		$data['PhilTECH'] = 'PhilTECH';
		if(isset($data[$shop_key]))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	public function tours_wallet()
	{
		$data = [];
		$data['shop_information'] = $this->user_info;
		$data['account_tours'] = Tbl_tour_wallet::where('tour_wallet_shop', $this->user_info->shop_id)
			->where('tour_wallet_main', 1)
			->first();
		$data['logs'] = DB::table('tbl_tour_wallet_logs')
			->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_tour_wallet_logs.tour_wallet_logs_customer_id')
			->where('shop_id', $this->user_info->shop_id)
			->paginate(20);
		return view('member.mlm_tours_wallet.index', $data);
	}
	public function unilevel_distribute()
	{
		$data = [];
		$shop_id = $this->user_info->shop_id;
		$data['settings_unilevel'] = DB::table('tbl_mlm_unilevel_distribute_settings')->where('u_r_shop_id', $shop_id)->first();
		return view('member.mlm_complan_setup.unilevel_distribute', $data);
	}
	public function unilevel_distribute_set_settings()
	{
		// return $_POST;

		$i['u_r_personal'] = Request::input('u_r_personal');
		$i['u_r_group'] = Request::input('u_r_group');
		$i['u_r_convertion'] = Request::input('u_r_convertion');
		$i['u_r_shop_id'] = $shop_id = $this->user_info->shop_id;
		$i['u_r_date'] = Carbon::now();

		$count = DB::table('tbl_mlm_unilevel_distribute_settings')->where('u_r_shop_id', $i['u_r_shop_id'])->count();
		if($count == 0)
		{
			DB::table('tbl_mlm_unilevel_distribute_settings')->insert($i);
		}
		else
		{
			$u['u_r_personal'] = $i['u_r_personal'];
			$u['u_r_group'] = $i['u_r_group'];
			$u['u_r_convertion'] = $i['u_r_convertion'];
			$u['u_r_date'] = $i['u_r_date'];
			DB::table('tbl_mlm_unilevel_distribute_settings')->where('u_r_shop_id', $i['u_r_shop_id'])->update($u);
		}

		$data['response_status'] = 'successd';
		$data['message'] = 'Unilevel Distribute Settings Updated';

		return json_encode($data);
	}
	public function unilevel_distribute_simulate()
	{
		ignore_user_abort(true);
        set_time_limit(0);
        flush();
        ob_flush();
        session_write_close();

		$shop_id = $this->user_info->shop_id;
		$settings = DB::table('tbl_mlm_unilevel_distribute_settings')->where('u_r_shop_id', $shop_id)->first();
		if($settings)
		{
			$from = Carbon::parse(Request::input('from'))->startOfDay();
			$to = Carbon::parse(Request::input('to'))->endOfDay();

			$plan[0] = 'UNILEVEL_REPURCHASE_POINTS';
			$plan[1] = 'REPURCHASE_POINTS';
	        $all_log_personal = Tbl_mlm_slot_points_log::where('shop_id', $shop_id)
	        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_slot_points_log.points_log_slot')
	        ->where('points_log_converted_date', '>=', $from)
	        ->where('points_log_converted_date', '<=', $to)
	        // ->where('points_log_complan', 'UNILEVEL_REPURCHASE_POINTS')

	        ->select(DB::raw('sum(points_log_points ) as sum_personal'), DB::raw('tbl_mlm_slot_points_log.*'), DB::raw('tbl_mlm_slot.*'))

	        ->groupBy('points_log_complan')
	        ->groupBy('points_log_slot')
	        ->where('points_log_converted', 0)
	        ->where('points_log_complan', 'REPURCHASE_POINTS')
	        ->get()->keyBy('points_log_slot');
	        $selected_slot = [];
	        $personal_points = [];
	        $structured = [];
	        foreach($all_log_personal as $p_key => $p_value)
	        {
	        	$u_r_personal = $settings->u_r_personal;
	        	if( $p_value->sum_personal >= $u_r_personal)
	        	{
	        		$selected_slot[$p_value->points_log_slot] = $p_value->points_log_slot;
	        		$personal_points[$p_value->points_log_slot] = $p_value->sum_personal;
	        	}
	        }

	        $all_log_group = Tbl_mlm_slot_points_log::where('tbl_mlm_slot.shop_id', $shop_id)
	        ->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_slot_points_log.points_log_slot')
	        ->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_mlm_slot.slot_owner')
	        ->where('points_log_converted_date', '>=', $from)
	        ->where('points_log_converted_date', '<=', $to)

	        ->select(DB::raw('sum(points_log_points ) as sum_group'), DB::raw('tbl_mlm_slot_points_log.*'), DB::raw('tbl_mlm_slot.*'), DB::raw('tbl_customer.*'))

	        ->groupBy('points_log_complan')
	        ->groupBy('points_log_slot')

	        ->where('points_log_complan', 'UNILEVEL_REPURCHASE_POINTS')
	        ->whereIn('points_log_slot', $selected_slot)
	        ->where('points_log_converted', 0)
	        ->get()->keyBy('points_log_slot');

	        foreach ($all_log_group as $g_key => $g_value) 
	        {
	        	# code...
	        	$u_r_group = $settings->u_r_group;
	        	if( $g_value->sum_group >= $u_r_group )
	        	{
	        		$earn = $g_value->sum_group * $settings->u_r_convertion;
	        		$structured[$g_value->points_log_slot]['group_points'] = $g_value->sum_group;
	        		$structured[$g_value->points_log_slot]['personal_points'] = $personal_points[$g_value->points_log_slot];
	        		$structured[$g_value->points_log_slot]['info'] = $g_value;
	        		$structured[$g_value->points_log_slot]['income'] = $earn;

	        		$log_array['earning'] = $earn;
	                $log_array['level'] = 1;
	                $log_array['level_tree'] = 'Sponsor Tree';
	                $log_array['complan'] = 'UNILEVEL_REPURCHASE_POINTS';

	                $log = Mlm_slot_log::log_constructor($g_value, $g_value,  $log_array);
	                $log = 'Congratulations, you earned ' . currency('PHP', $earn) . ' from Unilevel this cutoff'; 
	                $arry_log['wallet_log_slot'] = $g_value->slot_id;
	                $arry_log['shop_id'] = $g_value->shop_id;
	                $arry_log['wallet_log_slot_sponsor'] = $g_value->slot_id;
	                $arry_log['wallet_log_details'] = $log;
	                $arry_log['wallet_log_amount'] = $earn;
	                $arry_log['wallet_log_plan'] = "UNILEVEL_REPURCHASE_POINTS";
	                $arry_log['wallet_log_status'] = "released";   
	                $arry_log['wallet_log_claimbale_on'] = Carbon::now(); 
	                Mlm_slot_log::slot_array($arry_log);
	        	}
	        }
	        // dd($structured);

	        $update_all['points_log_converted'] = 1;
	       	$update_all['points_log_converted_date'] = Carbon::now(); 

	       	Tbl_mlm_slot_points_log::where('shop_id', $shop_id)
	       	->join('tbl_mlm_slot', 'tbl_mlm_slot.slot_id', '=', 'tbl_mlm_slot_points_log.points_log_slot')
	       	->where('points_log_converted_date', '>=', $from)
	        ->where('points_log_converted_date', '<=', $to)
	        ->whereIn('points_log_complan', $plan)
	        ->update($update_all);
	        $data_v['structured'] = $structured;	
	        $data['response_status'] = 'success_e';
	        $data['view_blade'] = view('member.mlm_complan_setup.unilevel.unilevel_simulate', $data_v)->render();

	        return json_encode($data);
	        exit;
		}
	}

	public function set_tours_wallet_settings()
	{
		$tour_Wallet_a_account_id = Request::input('tour_Wallet_a_account_id');
		$tour_wallet_a_username = Request::input('tour_wallet_a_username');
		$tour_wallet_a_base_password = Request::input('tour_wallet_a_base_password');
		$tour_wallet_convertion = Request::input('tour_wallet_convertion');
		$base_uri = $this->user_info->shop_wallet_tours_uri;

		$status = AbsMain::get_balance($base_uri, $tour_Wallet_a_account_id, $tour_wallet_a_username, $tour_wallet_a_base_password);

		if($status['status'] == 1)
		{
			$count = Tbl_tour_wallet::where('tour_wallet_shop', $this->user_info->shop_id)
			->where('tour_wallet_main', 1)
			->count();

			$insert['tour_wallet_shop'] = $this->user_info->shop_id; 
			$insert['tour_wallet_customer_id'] = 0;
			$insert['tour_wallet_user_id'] = $this->user_info->user_id;
			$insert['tour_Wallet_a_account_id'] = $tour_Wallet_a_account_id;
			$insert['tour_wallet_a_username'] = $tour_wallet_a_username;
			$insert['tour_wallet_a_base_password'] = $tour_wallet_a_base_password;
			$insert['tour_wallet_a_current_balance'] = $status['result'];
			$insert['tour_wallet_convertion'] = floatval($tour_wallet_convertion);
			$insert['tour_wallet_main'] = 1;
			$insert['tour_wallet_block'] = 0; 
			if($count == 0)
			{
				
				Tbl_tour_wallet::insert($insert);
			}
			else
			{
				Tbl_tour_wallet::where('tour_wallet_shop', $this->user_info->shop_id)
				->where('tour_wallet_main', 1)
				->update($insert);
			}
			
		}
		return json_encode($status);

	}

	public function get_log()
	{
		$data['logs'] = DB::table('tbl_tour_wallet_logs')
		->join('tbl_customer', 'tbl_customer.customer_id', '=', 'tbl_tour_wallet_logs.tour_wallet_logs_customer_id')
		->where('shop_id', $this->user_info->shop_id)
		->paginate(20);

		return view('member.mlm_tours_wallet.logs', $data);
	}
}