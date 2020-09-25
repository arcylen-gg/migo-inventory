<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_approver_group_employee extends Model
{
    protected $table = 'tbl_payroll_approver_group_employee';
	protected $primaryKey = "payroll_approver_group_employee_id";
    public $timestamps = false;


    public function scopeEmployeeInfo($query)
    {
    	$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_approver_employee.payroll_employee_id');
    	return $query;
    }
}
