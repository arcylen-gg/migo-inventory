<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_jobtitle extends Model
{
    protected $table = 'tbl_payroll_jobtitle';
	protected $primaryKey = "payroll_jobtitle_id";
    public $timestamps = false;


    /* REFERENCE COLUMN NAME */
    // [PRIMARY KEY]    payroll_jobtitle_id
    // [INTEGER]        shop_id
    // [INTEGER]        payroll_jobtitle_department_id
    // [VARCHAR]        payroll_jobtitle_name
    // [TINYINTEGER]    payroll_jobtitle_archived

    public function scopesel($query, $shop_id = 0, $archived = 0)
    {
    	$query->leftjoin('tbl_payroll_department','tbl_payroll_department.payroll_department_id','=','tbl_payroll_jobtitle.payroll_jobtitle_department_id')
    		 ->where('tbl_payroll_jobtitle.shop_id', $shop_id)
    		 ->where('tbl_payroll_jobtitle.payroll_jobtitle_archived', $archived);
    		 
    	return $query;
    }

    public function scopeseljobtitle($query, $payroll_jobtitle_department_id = 0, $payroll_jobtitle_archived = 0)
    {
        $query->where('payroll_jobtitle_department_id', $payroll_jobtitle_department_id)
              ->where('payroll_jobtitle_archived', $payroll_jobtitle_archived);

        return $query;
    }
}
