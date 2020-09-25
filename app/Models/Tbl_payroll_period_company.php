<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_payroll_period_company extends Model
{
    protected $table = 'tbl_payroll_period_company';
	protected $primaryKey = "payroll_period_company_id";
    public $timestamps = false;

    /* COLUMN NAME REFERENCE */
	// [PRIMARY KEY] 	payroll_period_company_id
	// [INTEGER] 		payroll_period_id
	// [INTEGER] 		payroll_company_id
	// [VARCHAR] 		payroll_period_status

	/* STATUS */
	/*
	• pending
	• processed
	• registered
	• posted
	• approved
	*/
	public function scopeselperiod($query, $payroll_period_id = 0)
	{
		$query->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_period_company.payroll_company_id')
			
			  ->where('payroll_period_id', $payroll_period_id);
		return $query;
	}

	public function scopesel($query, $payroll_period_company_id = 0)
	{
		$query->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_period_company.payroll_company_id')
			  ->join('tbl_payroll_period','tbl_payroll_period.payroll_period_id','=','tbl_payroll_period_company.payroll_period_id')
			  ->where('payroll_period_company_id', $payroll_period_company_id);
		return $query;
	}

	public function scopecheck($query, $payroll_period_company_id = 0, $shop_id = 0)
	{
		$query->join('tbl_payroll_period','tbl_payroll_period.payroll_period_id','=','tbl_payroll_period_company.payroll_period_id')
			 ->where('tbl_payroll_period_company.payroll_period_company_id', $payroll_period_company_id)
			 ->where('tbl_payroll_period.shop_id', $shop_id);

		return $query;
	}

	public function scopegetperiod($query, $shop_id = 0, $payroll_period_category = '', $payroll_period_status = 'ready')
	{
		$query->join('tbl_payroll_period','tbl_payroll_period.payroll_period_id','=','tbl_payroll_period_company.payroll_period_id')
			 ->where('tbl_payroll_period.shop_id', $shop_id)
			 ->where('tbl_payroll_period_company.payroll_period_status', $payroll_period_status)
			 ->where('tbl_payroll_period.payroll_period_archived', 0)
			 ->where('tbl_payroll_period.payroll_period_category', $payroll_period_category)
			 ->groupBy('tbl_payroll_period.payroll_period_id');
		return $query;
	}

	public function scopeperiod($query, $shop_id = 0, $payroll_period_status = 'processed')
	{
		$query->join('tbl_payroll_period','tbl_payroll_period.payroll_period_id','=','tbl_payroll_period_company.payroll_period_id')
				->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_period_company.payroll_company_id')
			 	->where('tbl_payroll_period_company.payroll_period_status', $payroll_period_status)
			 	->where('tbl_payroll_period.shop_id', $shop_id);
		return $query;
	}

	public function scopegetcompanyperiod($query, $payroll_period_company_id = 0)
	{
		$query->join('tbl_payroll_period','tbl_payroll_period.payroll_period_id','=','tbl_payroll_period_company.payroll_period_id')
			 	->where('tbl_payroll_period_company.payroll_period_company_id', $payroll_period_company_id);

		return $query;
	}
	public function scopeCompany($query)
	{
		$query->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_period_company.payroll_company_id');
	}
	public function scopeCompanyPeriod($query)
	{
		$query->join('tbl_payroll_period','tbl_payroll_period.payroll_period_id','=','tbl_payroll_period_company.payroll_period_id');
	}
	public function scopegetcompanydetails($query, $payroll_period_company_id = 0)
	{
		$query->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_period_company.payroll_company_id')
			  ->leftjoin('tbl_payroll_bank_convertion','tbl_payroll_bank_convertion.payroll_bank_convertion_id','=','tbl_payroll_company.payroll_company_bank')
			  ->join('tbl_payroll_period','tbl_payroll_period.payroll_period_id','=','tbl_payroll_period_company.payroll_period_id')
			  ->where('tbl_payroll_period_company.payroll_period_company_id',$payroll_period_company_id);

		return $query;
	}
}
