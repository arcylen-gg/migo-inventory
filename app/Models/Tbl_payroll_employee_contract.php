<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_employee_contract extends Model
{
    protected $table = 'tbl_payroll_employee_contract';
	protected $primaryKey = "payroll_employee_contract_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_employee_contract_id
	// [INTEGER] 		payroll_employee_id
	// [INTEGER] 		payroll_department_id
	// [INTEGER] 		payroll_jobtitle_id
	// [DATE] 			payroll_employee_contract_date_hired
	// [DATE] 			payroll_employee_contract_date_end
	// [INTEGER] 		payroll_employee_contract_status
	// [INTEGER] 		payroll_group_id
	// [TINY INTEGER] 	payroll_employee_contract_archived
	
	public function scopeGroup($query)
	{
		$query->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id');
	}

	public function scopeGroup13thmonthBasis($query, $employee_id = 0)
	{
		$query->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
		->join('tbl_payroll_13th_month_basis','tbl_payroll_13th_month_basis.payroll_group_id', '=', 'tbl_payroll_group.payroll_group_id');

		if($employee_id != 0)
		{
			$query->where('tbl_payroll_employee_contract.payroll_employee_id', $employee_id);
		}
 
		return $query;
	}

	public function scopeselemployee($query, $payroll_employee_id = 0, $date = '0000-00-00')
	{
		if($date == '0000-00-00')
		{
			$date = date('Y-m-d');
		}
		$query->where('payroll_employee_id',$payroll_employee_id)
			  ->where('payroll_employee_contract_archived',0)
			  // ->where('payroll_employee_contract_date_hired','<=',$date);
			  ->where(function($query1) use ($date)
			  {
			  	$query1->where('tbl_payroll_employee_contract.payroll_employee_contract_date_end','>=', $date)
			  			->orWhere('tbl_payroll_employee_contract.payroll_employee_contract_date_end','0000-00-00');
			  	return $query1;
			  });
		return $query;
	}

	public function scopecontractlist($query, $payroll_employee_id = 0, $payroll_employee_contract_archived = 0)
	{
		$query->leftjoin('tbl_payroll_department','tbl_payroll_department.payroll_department_id','=','tbl_payroll_employee_contract.payroll_department_id')
			  ->leftjoin('tbl_payroll_jobtitle','tbl_payroll_jobtitle.payroll_jobtitle_id','=','tbl_payroll_employee_contract.payroll_jobtitle_id')
			  ->leftjoin('tbl_payroll_employment_status','tbl_payroll_employment_status.payroll_employment_status_id','=','tbl_payroll_employee_contract.payroll_employee_contract_status')
			  ->where('payroll_employee_id',$payroll_employee_id)
			  ->where('payroll_employee_contract_archived', $payroll_employee_contract_archived);

		return $query;
	}

	public function scopeemployeefilter($query, $company_id = 0, $department_id = 0, $job_title_id = 0, $date = '0000-00-00', $shop_id = 0, $payroll_employee_contract_status = array())
	{
		if($date == '0000-00-00')
		{
			$date = date('Y-m-d');
		}

		if(empty($payroll_employee_contract_status))
		{
			$payroll_employee_contract_status[0] = 1;
			$payroll_employee_contract_status[1] = 2;
			$payroll_employee_contract_status[2] = 3;
			$payroll_employee_contract_status[3] = 4;
			$payroll_employee_contract_status[4] = 5;
			$payroll_employee_contract_status[5] = 6;
			$payroll_employee_contract_status[7] = 7;
		}

		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_employee_contract.payroll_employee_id')
			  ->leftjoin('tbl_payroll_department','tbl_payroll_department.payroll_department_id','=','tbl_payroll_employee_contract.payroll_department_id')
			  ->leftjoin('tbl_payroll_jobtitle','tbl_payroll_jobtitle.payroll_jobtitle_id','=','tbl_payroll_employee_contract.payroll_jobtitle_id')
			  ->leftjoin('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_employee_basic.payroll_employee_company_id')
			 
			  ->whereIn('payroll_employee_contract_status',$payroll_employee_contract_status)
			  ->where('tbl_payroll_employee_basic.shop_id', $shop_id)
			  ->where('tbl_payroll_employee_contract.payroll_employee_contract_archived',0);

			  /*Removed from top where the query has blank space*/
			  /* ->where(function($query1) use ($date)
			  {
			  	$query1->where('tbl_payroll_employee_contract.payroll_employee_contract_date_end','>=', $date)
			  			->orWhere('tbl_payroll_employee_contract.payroll_employee_contract_date_end','0000-00-00');
			  })*/

			  $separated_status[0] = 8;
			  $separated_status[1] = 9;
			  $containsSearch = count(array_intersect($separated_status, $payroll_employee_contract_status)) == count($separated_status);

			  if(!$containsSearch)
			  {
			  	$query->where('tbl_payroll_employee_contract.payroll_employee_contract_date_hired','<=',$date);
			  }

			  if($company_id != 0)
			  {
			  	$query->where('tbl_payroll_employee_basic.payroll_employee_company_id', $company_id);
			  }

			  if($department_id != 0)
			  {
			  	$query->where('tbl_payroll_employee_contract.payroll_department_id', $department_id);
			  	//dd($query->get());
			  }
			  if($job_title_id != 0)
			  {
			  	$query->where('tbl_payroll_employee_contract.payroll_jobtitle_id', $job_title_id);
			  }

	return $query;
	}
	public function scopeEmployeePayrollGroup($query, $employee_id)
	{
		$query->join("tbl_payroll_group", "tbl_payroll_group.payroll_group_id", "=", "tbl_payroll_employee_contract.payroll_group_id")
		->join("tbl_payroll_employee_basic", "tbl_payroll_employee_basic.payroll_employee_id", "=", "tbl_payroll_employee_contract.payroll_employee_id");
		
		if($employee_id != 0)
		{
			$query->where('tbl_payroll_employee_basic.payroll_employee_id',$employee_id);
		}	
		else
		{
			return $query;
		}
	}

}
