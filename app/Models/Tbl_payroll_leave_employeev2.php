<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_leave_employeev2 extends Model
{
    //
    protected $table = 'tbl_payroll_leave_employee_v2';
	protected $primaryKey = "payroll_leave_employee_id";
    public $timestamps = false;


	public function scopegetperleave($query, $payroll_leave_temp_id = 0, $payroll_leave_employee_is_archived = 0)
	{
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_employee_v2.payroll_employee_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_temp_id',$payroll_leave_temp_id)
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_is_archived',$payroll_leave_employee_is_archived);
		
		return $query;
	}

	public function scopeemployee($query, $payroll_leave_employee_id = 0)
	{
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_employee_v2.payroll_employee_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id',$payroll_leave_employee_id);

		return $query;
	}

	public function scopecheckleave($query, $payroll_employee_id = 0, $payroll_leave_temp_id = 0)
	{
		$query->where('payroll_employee_id', $payroll_employee_id)->where('payroll_leave_temp_id', $payroll_leave_temp_id);

		return $query;
	}

	public function scopegetemployeeid($query, $date = '0000-00-00', $shop_id)
	{	
	
		
			  $query->join('tbl_payroll_leave_schedulev2','tbl_payroll_leave_employee_v2.payroll_leave_employee_id','=','tbl_payroll_leave_schedulev2.payroll_leave_employee_id')
			  ->where('tbl_payroll_leave_schedulev2.shop_id',$shop_id)
			  ->whereBetween('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $date)
			  ->distinct();
		
		return $query;
	}

	public function scopegetemployeeidbytempid($query,$payroll_leave_temp_id =0,$shop_id)
	{
			$query->join('tbl_payroll_leave_tempv2','tbl_payroll_leave_employee_v2.payroll_leave_temp_id','=','tbl_payroll_leave_tempv2.payroll_leave_temp_id')
			   ->whereIn('tbl_payroll_leave_tempv2.payroll_leave_temp_id',$payroll_leave_temp_id)
               ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_is_archived',0)
               ->where('tbl_payroll_leave_tempv2.shop_id',$shop_id)
               ->select('payroll_employee_id','payroll_leave_employee_id');

              	return $query; 
	}

	// public function scopegetpayable_leave($query, $payroll_employee_id = 0)
	// {
	// 	$query->join('tbl_payroll_leave_temp','tbl_payroll_leave_temp.payroll_leave_temp_id','=','tbl_payroll_leave_employee.payroll_leave_temp_id')
	// 		  ->where('tbl_payroll_leave_temp.payroll_leave_temp_with_pay',1)
	// 		  ->where('tbl_payroll_leave_employee.payroll_employee_id', $payroll_employee_id)
	// 		  ->orderBy('tbl_payroll_leave_schedule.payroll_leave_schedule_id', 'desc');
	// 	return $query;
	// }

}
