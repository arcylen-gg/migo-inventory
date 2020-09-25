<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_approver_employee extends Model
{
    protected $table = 'tbl_payroll_approver_employee';
	protected $primaryKey = "payroll_approver_employee_id";
    public $timestamps = false;


    public function scopeEmployeeInfo($query)
    {
    	$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_approver_employee.payroll_employee_id');
    	return $query;
    }

    public function scopeGetApproverByLevelAndType($query, $shop_id = 0, $approver_level = '', $approver_type = '')
    {
    	$query->where('tbl_payroll_approver_employee.shop_id', $shop_id);

    	if ($approver_type != '' ) 
    	{
    		$query->where('payroll_approver_employee_type', $approver_type);
    	}

    	if ($approver_level != '' ) 
    	{
    		$query->where('payroll_approver_employee_level', $approver_level);
    	}

    	return $query;
    }

    public function scopeGetApproverInfoByType($query, $employee_id = 0, $approver_type = '')
    {
        if ($employee_id != 0) 
        {
            $query->where('payroll_employee_id', $employee_id);
        }

        if ($approver_type != '') 
        {
            $query->where('payroll_approver_employee_type', $approver_type);
        }

        return $query;
    }
}
