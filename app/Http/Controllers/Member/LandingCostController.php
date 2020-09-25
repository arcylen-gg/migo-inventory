<?php
namespace App\Http\Controllers\Member;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Session;
use Redirect;
use PDF;
use Carbon\Carbon;
use App\Globals\AuditTrail;
use App\Globals\LandingCost;

class LandingCostController extends Member
{
	public function getIndex()
	{
		$data['_landing_cost'] = LandingCost::get($this->user_info->shop_id);
		$data['action'] = "/member/maintenance/landing_cost/create-submit";

		return view("member.landing_cost.default_landing_cost", $data);
	}
	public function postCreateSubmit(Request $request)
	{
		$ins = null;
		$date = Carbon::now();

		foreach ($request->cost_name as $key => $value) 
		{
			if($value)
			{
				$ins[$key]['shop_id'] = $this->user_info->shop_id;
				$ins[$key]['default_cost_name'] = $value;
				$ins[$key]['default_cost_description'] = $request->cost_description[$key];
				$ins[$key]['default_cost_type'] = $request->cost_type[$key];
				$ins[$key]['default_cost_created'] = $date;
			}
		}
		$return = null;
		if(count($ins) > 0)
		{
			LandingCost::insert($this->user_info->shop_id, $ins);
			$return['status'] = 'success';
			$return['call_function'] = 'success_create';
			$return['status_message'] = 'Success updating landing cost computation';
		}
		else
		{
			$return['status'] = 'error';
			$return['status_message'] = 'Please enter a list';
		}

		return json_encode($return);
	}
}