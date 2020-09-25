<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_biometric_record extends Model
{
    protected $table = 'tbl_payroll_biometric_record';
	protected $primaryKey = "payroll_biometric_record_id";
    public $timestamps = false;


    public function scopegetalldata($query, $shop_id)
    {
    	$query->where('tbl_payroll_biometric_record.shop_id',$shop_id)
        ->join('tbl_payroll_biometric_time_sheet','tbl_payroll_biometric_time_sheet.payroll_biometric_record_id','=','tbl_payroll_biometric_record.payroll_biometric_record_id')
    	->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_biometric_record.payroll_employee_id')
    	->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_biometric_record.payroll_company_id');

    	return $query;
    }

}

