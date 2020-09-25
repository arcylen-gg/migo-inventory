<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_journal_tag extends Model
{
    protected $table = 'tbl_payroll_journal_tag';
	protected $primaryKey = "payroll_journal_tag_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [INTEGER]		payroll_journal_tag_id
	// [INTEGER]		shop_id
	// [INTEGER]		account_id
	// [TINY INTEGER]	journal_tag_archived

	public function scopegettag($query, $shop_id = 0)
	{
		$query->join('tbl_chart_of_account','tbl_chart_of_account.account_id','=','tbl_payroll_journal_tag.account_id')
			 ->where('tbl_payroll_journal_tag.shop_id', $shop_id);

		return $query;
	}

	public function scopeTagEntity($query)
	{
		return $query->join("tbl_payroll_journal_tag_entity AS entity","entity.payroll_journal_tag_id","=","tbl_payroll_journal_tag.payroll_journal_tag_id");
	}

	public function scopeTagEmployee($query, $employee_id)
	{
		return $query->leftjoin("tbl_payroll_journal_tag_employee AS emp","emp.payroll_journal_tag_id","=","tbl_payroll_journal_tag.payroll_journal_tag_id")->where("emp.payroll_employee_id", $employee_id);
	}
	
	public function scopeAccount($query)
	{
		return $query->leftjoin("tbl_chart_of_account AS account","account.account_id","=","tbl_payroll_journal_tag.account_id")
					 ->leftJoin("tbl_chart_account_type","chart_type_id","=","account_type_id");
	}
}
