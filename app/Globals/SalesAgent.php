<?php
namespace App\Globals;
use App\Models\Tbl_position;
use App\Models\Tbl_employee;

use App\Globals\CommissionCalculator;

use Carbon\carbon;
use DB;

/**
 * 
 *
 * @author ARCYLEN
 */

class SalesAgent
{
	public static function get_position($shop_id)
	{
		return Tbl_position::where("position_shop_id",$shop_id)->get();
	}
	public static function create($shop_id, $ins)
	{
		$ins['shop_id'] = $shop_id;
		return Tbl_employee::insertGetId($ins);
	}
	public static function info($shop_id, $agent_id)
	{
		$return = Tbl_employee::where('shop_id',$shop_id)->where('employee_id',$agent_id)->first();

		return $return;
	}
	public static function get_list($shop_id, $commission = false, $archived = 0)
	{
		$agent = Tbl_employee::position()->where('shop_id',$shop_id)->where('tbl_employee.archived',$archived)->get();

		if($commission)
		{
			foreach ($agent as $key => $value) 
			{
				$amount = CommissionCalculator::per_agent($value->employee_id);
				$agent[$key]->orverall_comm = $amount['orverall_comm'];
				$agent[$key]->released_comm = $amount['released_comm'];
				$agent[$key]->for_releasing_comm = $amount['for_releasing_comm'];
				$agent[$key]->pending_comm = $amount['pending_comm'];
			}
		}

		return $agent;
	}
	public static function get_info($shop_id, $agent_code)
	{
		return Tbl_employee::position()->where("shop_id", $shop_id)->where('agent_code', $agent_code)->first();
	}
}