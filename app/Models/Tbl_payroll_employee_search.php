<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_payroll_employee_search extends Model
{
    protected $table = 'tbl_payroll_employee_search';
	protected $primaryKey = "payroll_employee_search_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */
 	// [PRIMARY KEY] 	payroll_employee_search_id
	// [INTEGER] 		payroll_search_employee_id
	// [TEXT] 			body

	public function scopesearch($query, $body = '', $status = 'active' ,$date = '0000-00-00', $shop_id = 0)
	{
		// dd($shop_id);
		if($date == '0000-00-00')
		{
			$date = date('Y-m-d');
		}
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_employee_search.payroll_search_employee_id')
			  ->join('tbl_payroll_employee_contract','tbl_payroll_employee_contract.payroll_employee_id','=','tbl_payroll_employee_search.payroll_search_employee_id')
			  ->leftjoin('tbl_payroll_department','tbl_payroll_department.payroll_department_id','=','tbl_payroll_employee_contract.payroll_department_id')
			  ->leftjoin('tbl_payroll_jobtitle','tbl_payroll_jobtitle.payroll_jobtitle_id','=','tbl_payroll_employee_contract.payroll_jobtitle_id')
			  ->leftjoin('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_employee_basic.payroll_employee_company_id')
			  ->whereRaw("MATCH(tbl_payroll_employee_search.body) AGAINST('*".$body."*' IN BOOLEAN MODE)")
			  ->where('tbl_payroll_employee_basic.shop_id', $shop_id)
			  ->where(function($query1) use ($date, $status)
			  {
			  	// dd($status);
			  	if($status == 'active')
			  	{
			  		$query1->where('tbl_payroll_employee_contract.payroll_employee_contract_date_end','>=', $date)
			  			->orWhere('tbl_payroll_employee_contract.payroll_employee_contract_date_end','0000-00-00');
			  	}
			  	else
			  	{
			  		$query1->where('tbl_payroll_employee_contract.payroll_employee_contract_date_end','<=', $date)
			  				->where('tbl_payroll_employee_contract.payroll_employee_contract_date_end','!=','0000-00-00');
			  	}
			  	
			  	return $query1;
			  });

		return $query;
	}
}
