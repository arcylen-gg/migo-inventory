<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_journal_tag_employee extends Model
{
    protected $table = 'tbl_payroll_journal_tag_employee';
	protected $primaryKey = "payroll_journal_tag_employee_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER] payroll_journal_tag_employee_id
	// [INTEGER] payroll_employee_id
	// [INTEGER] payroll_journal_tag_id

	public function scopecheckdata($query, $payroll_employee_id = 0, $payroll_journal_tag_id = 0)
	{
		$query->where('payroll_employee_id', $payroll_employee_id)
		 	  ->where('payroll_journal_tag_id',$payroll_journal_tag_id);

		return $query;
	}
}
