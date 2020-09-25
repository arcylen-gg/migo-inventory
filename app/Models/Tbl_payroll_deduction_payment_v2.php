<?php

namespace App\Models;

use DB;

use Illuminate\Database\Eloquent\Model;


class Tbl_payroll_deduction_payment_v2 extends Model
{
    protected $table = 'tbl_payroll_deduction_payment_v2';
     protected $primaryKey = "payroll_deduction_payment_id";
    public $timestamps = false;

    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_deduction_payment_id
	// [INTEGER]		shop_id
	// [INTEGER] 		payroll_deduction_id
	// [INTEGER] 		payroll_employee_id
	// [INTEGER] 		payroll_record_id
	// [DOUBLE] 		payroll_payment_amount
	// [VARCHAR]		deduction_name
	// [VARCHAR]		deduction_category

	public function scopeselbyemployee($query, $payroll_deduction_id = 0, $payroll_employee_id = 0)
	{
		$query->where('payroll_deduction_id',$payroll_deduction_id)->where('payroll_employee_id', $payroll_employee_id);

		return $query;
	}

	public function scopegetpayment($query, $payroll_employee_id = 0, $payroll_deduction_id = 0)
	{
		$query->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_deduction_payment_v2.payroll_employee_id')
			  ->where('tbl_payroll_employee_basic.payroll_employee_id',$payroll_employee_id)
			  ->where('tbl_payroll_deduction_payment_v2.payroll_deduction_id', $payroll_deduction_id);
			  // ->whereIn('payroll_record_id',$payroll_record_id);
		return $query;
	}

	public function scopegetrecord($query, $payroll_record_id = array(), $payroll_deduction_category = '')
	{
		$query->join('tbl_payroll_deduction_v2','tbl_payroll_deduction_v2.payroll_deduction_id','=','tbl_payroll_deduction_payment_v2.payroll_deduction_id')
			 ->whereIn('tbl_payroll_deduction_payment_v2.payroll_record_id', $payroll_record_id)
			 ->where('tbl_payroll_deduction_v2.payroll_deduction_category', $payroll_deduction_category);

		return $query;
	}


	public function scopegetallinfo($query,$shop_id = 0 ,$payroll_employee_id = 0 ,$payroll_deduction_id = 0, $branchcompany = null)
	{
		$query->join('tbl_payroll_deduction_v2','tbl_payroll_deduction_v2.payroll_deduction_id','=','tbl_payroll_deduction_payment_v2.payroll_deduction_id')
          ->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_deduction_payment_v2.payroll_employee_id')
          ->select(DB::raw('IFNULL(sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount),0) as total_payment, IFNULL(count(tbl_payroll_deduction_payment_v2.deduction_name),0) as number_of_payment , tbl_payroll_deduction_v2.* , tbl_payroll_employee_basic.*'))
          ->groupBy('tbl_payroll_deduction_payment_v2.deduction_name','tbl_payroll_deduction_v2.payroll_deduction_id')
          ->orderBy('tbl_payroll_employee_basic.payroll_employee_company_id','asc')
          ->orderBy('tbl_payroll_employee_basic.payroll_employee_id','desc');
          if ($shop_id!=0) 
          {
          	$query->where('tbl_payroll_deduction_v2.shop_id',$shop_id);
          }
          // if ($payroll_employee_id!=0) 
          // {
          // 	$query->where('tbl_payroll_employee_basic.payroll_employee_company_id',$payroll_employee_id);
          // }
          if($branchcompany != null)
          {
               $query->whereIn("tbl_payroll_employee_basic.payroll_employee_company_id", $branchcompany);

          }
          else if($payroll_employee_id != 0)
          {
               $query->where('tbl_payroll_employee_basic.payroll_employee_company_id',$payroll_employee_id);
          }

          if ($payroll_deduction_id!=0)
          {
          	$query->where('tbl_payroll_deduction_v2.payroll_deduction_id',$payroll_deduction_id);
          }

          return $query;
	}


     public function scopegettotaldeductionpayment($query, $employee_id, $payroll_deduction_id, $deduction_name)
     {
          $query->where('payroll_employee_id',$employee_id)
               ->where('deduction_name',$deduction_name)
               ->where('payroll_deduction_id', $payroll_deduction_id)
               ->select(DB::raw('IFNULL(sum(payroll_payment_amount),0) as total_payment'))
               ->orderBy('payroll_deduction_payment_id','desc');
     }


     public function scopegetmonthdeductionpayment($query, $employee_id, $payroll_deduction_id, $deduction_name, $month)
     {
          $query->where('payroll_employee_id',$employee_id)
               ->where('deduction_name',$deduction_name)
               ->where('payroll_deduction_id', $payroll_deduction_id)
               ->select(DB::raw('IFNULL(sum(payroll_payment_amount),0) as total_payment'))
               ->whereBetween('payroll_payment_period',$month)
               ->orderBy('payroll_deduction_payment_id','desc');
     }



	public function scopegetdeductionpertype($query,$shop_id = 0 , $deduction_category )
	{
		$query->join('tbl_payroll_deduction_v2','tbl_payroll_deduction_v2.payroll_deduction_id','=','tbl_payroll_deduction_payment_v2.payroll_deduction_id')
          ->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_deduction_payment_v2.payroll_employee_id')
          ->select(DB::raw('IFNULL(sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount),0) as total_payment, IFNULL(count(tbl_payroll_deduction_payment_v2.deduction_name),0) as number_of_payment , tbl_payroll_deduction_v2.* , tbl_payroll_employee_basic.*'))
          ->groupBy('tbl_payroll_deduction_payment_v2.deduction_name','tbl_payroll_deduction_v2.payroll_deduction_id')
          ->orderBy('tbl_payroll_employee_basic.payroll_employee_id','asc');
          if ($shop_id!=0) 
          {
          	$query->where('tbl_payroll_deduction_v2.shop_id',$shop_id);
          }

          if ($deduction_category != '') {
          	$query->where('tbl_payroll_deduction_v2.payroll_deduction_type',$deduction_category);
          }

          return $query;
	}


	public function scopegetalldeductionpertype($query, $shop_id = 0 , $payroll_employee_id = 0, $payroll_deduction_id = 0 )
	{
		$query->join('tbl_payroll_deduction_v2','tbl_payroll_deduction_v2.payroll_deduction_id','=','tbl_payroll_deduction_payment_v2.payroll_deduction_id')
          ->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_deduction_payment_v2.payroll_employee_id')
          ->select(DB::raw('IFNULL(sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount),0) as total_payment, IFNULL(count(tbl_payroll_deduction_payment_v2.deduction_name),0) as number_of_payment , tbl_payroll_deduction_v2.* , tbl_payroll_employee_basic.*'))
          ->groupBy('tbl_payroll_deduction_payment_v2.deduction_name','tbl_payroll_deduction_v2.payroll_deduction_id')
          ->orderBy('tbl_payroll_employee_basic.payroll_employee_id','asc');
          if ($shop_id!=0) 
          {
          	$query->where('tbl_payroll_deduction_v2.shop_id',$shop_id);
          }
          if ($payroll_employee_id!=0) 
          {
          	$query->where('tbl_payroll_employee_basic.payroll_employee_id',$payroll_employee_id);
          }
          if ($payroll_deduction_id!=0)
          {
          	$query->where('tbl_payroll_deduction_v2.payroll_deduction_id',$payroll_deduction_id);
          }

          return $query;
	}
}