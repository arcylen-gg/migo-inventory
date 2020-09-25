<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_payroll_employee_basic extends Model
{
    protected $table = 'tbl_payroll_employee_basic';
	protected $primaryKey = "payroll_employee_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_employee_id
	// [INTEGER] 		shop_id
	// [INTEGER] 		payroll_employee_company_id
	// [VARCHAR] 		payroll_employee_title_name
	// [VARCHAR] 		payroll_employee_first_name
	// [VARCHAR] 		payroll_employee_middle_name
	// [VARCHAR] 		payroll_employee_last_name
	// [VARCHAR] 		payroll_employee_suffix_name
	// [VARCHAR] 		payroll_employee_display_name
	// [VARCHAR] 		payroll_employee_contact
	// [VARCHAR] 		payroll_employee_email
	// [DATE] 			payroll_employee_birthdate
	// [VARCHAR] 		payroll_employee_gender
	// [VARCHAR] 		payroll_employee_number
	// [VARCHAR] 		payroll_employee_atm_number
	// [VARCHAR] 		payroll_employee_street
	// [VARCHAR] 		payroll_employee_city
	// [VARCHAR] 		payroll_employee_state
	// [VARCHAR] 		payroll_employee_zipcode
	// [INTEGER] 		payroll_employee_country
	// [VARCHAR] 		payroll_employee_tax_status
	// [VARCHAR] 		payroll_employee_tin
	// [VARCHAR] 		payroll_employee_sss
	// [VARCHAR] 		payroll_employee_pagibig
	// [VARCHAR] 		payroll_employee_philhealth
	// [TEXT] 			payroll_employee_remarks


	public function scopeselemployee($query, $parameter)
	{
		$date 				= $parameter['date'];
		$company_id 		= $parameter['company_id'];
		$employment_status 	= $parameter['employement_status'];
		$shop_id 			= $parameter['shop_id'];
		
		if($date == '0000-00-00')
		{
			$date = date('Y-m-d');
		}

		$query->leftjoin('tbl_payroll_company as company','company.payroll_company_id','=','tbl_payroll_employee_basic.payroll_employee_company_id')
			  ->leftjoin('tbl_payroll_employee_contract as contract','contract.payroll_employee_id','=','tbl_payroll_employee_basic.payroll_employee_id')
			  ->leftjoin('tbl_payroll_department as department','department.payroll_department_id','=','contract.payroll_department_id')
			  ->leftjoin('tbl_payroll_jobtitle as jobtitle','jobtitle.payroll_jobtitle_id','=','contract.payroll_jobtitle_id')
			  ->where('contract.payroll_employee_contract_date_end','<=',$date)
			  ->where('company.shop_id',$shop_id);
			 
			  if($company_id != 0)
			  {
			  	$query->where('company.payroll_company_id',$company_id);
			  }

			  if($employment_status == 0)
			  {
			  	$status = array();
			  	$status[0] = 1;
			  	$status[1] = 2;
			  	$status[2] = 3;
			  	$status[3] = 4;
			  	$status[4] = 5;
			  	$status[5] = 6;
			  	$status[6] = 7;
			  }

			  if($employment_status === 'separated')
			  {
			  	$status = array();
			  	$status[0] = 8;
			  	$status[1] = 9;
			  	
			  }

			  if($employment_status != 0 && $employment_status != 'separated')
			  {
			  	$status = array();
			  	$status[0] = $employment_status;
			  }

			  $query->whereIn('contract.payroll_employee_contract_status',$status)
			  		->select('company.payroll_company_name','tbl_payroll_employee_basic.*','department.payroll_department_name','jobtitle.payroll_jobtitle_name')
			  		->groupBy('tbl_payroll_employee_basic.payroll_employee_id');

		return $query;

	}

	public function scopecheckexist($query, $_data = array())
	{
		foreach($_data as $key => $data)
		{
			$query->where($key, $data);
		}

		return $query;
	}
	
    public function scopeShift($query)
    {
    	$query->join("tbl_payroll_shift_code", "tbl_payroll_shift_code.shift_code_id", "=", "tbl_payroll_employee_basic.shift_code_id");
    	return $query;
    }
    public function scopeDay($query)
    {
    	$query->join("tbl_payroll_shift_day", "tbl_payroll_shift_day.shift_code_id", "=", "tbl_payroll_shift_code.shift_code_id");
    }
    public function scopeTime($query)
    {
    	$query->join("tbl_payroll_shift_time", "tbl_payroll_shift_time.shift_day_id", "=", "tbl_payroll_shift_day.shift_day_id");
    }
    public function scopeEmployeeShift($query, $employee_id)
    {
    	$query->join("tbl_payroll_shift_code", "tbl_payroll_shift_code.shift_code_id", "=", "tbl_payroll_employee_basic.shift_code_id")
    		  ->where('payroll_employee_id',$employee_id);

    	return $query;
    }
}
