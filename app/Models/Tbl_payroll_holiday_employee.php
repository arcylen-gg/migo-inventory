<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_holiday_employee extends Model
{
	protected $table = 'tbl_payroll_holiday_employee';
	protected $primaryKey = "holiday_employee_id";
    public $timestamps = false;

    public function scopegetholidayv2($query, $employee_id = 0, $date = '0000-00-00')
	{
		$query->join('tbl_payroll_holiday','tbl_payroll_holiday.payroll_holiday_id','=','tbl_payroll_holiday_employee.holiday_company_id')
			  ->where('tbl_payroll_holiday_employee.payroll_employee_id',$employee_id)
			  ->where('tbl_payroll_holiday.payroll_holiday_date',$date)
			  ->where('tbl_payroll_holiday.payroll_holiday_archived',0);

		return $query;
	}
}