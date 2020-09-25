<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_payroll_deduction_v2 extends Model
{
    protected $table = 'tbl_payroll_deduction_v2';
	protected $primaryKey = "payroll_deduction_id";
    public $timestamps = false;


    /* COLUMN REFERENCE NAME */
	// [PRIMARY KEY] 	payroll_deduction_id
	// [INTEGER]		shop_id
	// [VARCHAR] 		payroll_deduction_name
	// [DOUBLE] 		payroll_deduction_amount
	// [DOUBLE] 		payroll_monthly_amortization
	// [DOUBLE] 		payroll_periodal_deduction
	// [DATE] 			payroll_deduction_date_filed
	// [DATE] 			payroll_deduction_date_start
	// [DATE]			payroll_deduction_date_end
	// [VARCHAR] 		payroll_deduction_period
	// [VARCHAR] 		payroll_deduction_category
	// [INTEGER] 		payroll_deduction_type
	// [TEXT] 			payroll_deduction_remarks
	// [TINY INTEGER] 	payroll_deduction_archived

	public function scopeseldeduction($query, $shop_id  = 0, $payroll_deduction_archived = 0)
	{
		$query->leftjoin('tbl_payroll_deduction_type','tbl_payroll_deduction_type.payroll_deduction_type_id','=','tbl_payroll_deduction_v2.payroll_deduction_type')
			->where('tbl_payroll_deduction_v2.shop_id', $shop_id)
			->where('tbl_payroll_deduction_v2.payroll_deduction_archived', $payroll_deduction_archived);
		return $query;
	}



	public function scopeselalldeductionbycategory($query, $shop_id = 0, $payroll_deduction_archived = 0 )
	{

		// $query
		// ->leftjoin('tbl_payroll_deduction_payment_v2','tbl_payroll_deduction_v2.payroll_deduction_id','=','tbl_payroll_deduction_payment_v2.payroll_deduction_id')
		// ->where('tbl_payroll_deduction_v2.shop_id', $shop_id)
		// ->where('tbl_payroll_deduction_v2.payroll_deduction_archived',$payroll_deduction_archived)
		// ->select(DB::raw('tbl_payroll_deduction_v2.payroll_deduction_type, 
		// 	sum(tbl_payroll_deduction_v2.payroll_deduction_amount) as total_amount,
		// 	IFNULL(sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount),0) as total_payment,
		// 	(IFNULL(sum(tbl_payroll_deduction_v2.payroll_deduction_amount),0) - IFNULL(sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount),0)) as balance'))
		// ->groupBy('tbl_payroll_deduction_v2.payroll_deduction_type');

		$query->where('tbl_payroll_deduction_v2.shop_id', $shop_id)
		->where('tbl_payroll_deduction_v2.payroll_deduction_archived',$payroll_deduction_archived)
		->select(DB::raw('tbl_payroll_deduction_v2.payroll_deduction_type ,sum(tbl_payroll_deduction_v2.payroll_deduction_amount) as total_amount'))
		->groupBy('tbl_payroll_deduction_v2.payroll_deduction_type');

		// ,
		// 	IFNULL((select sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount) from tbl_payroll_deduction_payment_v2 
		// 	where tbl_payroll_deduction_payment_v2.deduction_name = tbl_payroll_deduction_v2.payroll_deduction_type
		// 	GROUP BY tbl_payroll_deduction_payment_v2.deduction_name),0) as total_payment , 
		// 	(sum(tbl_payroll_deduction_v2.payroll_deduction_amount) - IFNULL((select sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount) from tbl_payroll_deduction_payment_v2 
		// 	where tbl_payroll_deduction_payment_v2.deduction_name = tbl_payroll_deduction_v2.payroll_deduction_type
		// 	GROUP BY tbl_payroll_deduction_payment_v2.deduction_name),0) ) as balance

		return $query;
	}


	public function scopegetallinfo($query, $shop_id = 0, $payroll_deduction_type = '')
	{
		$query->join('tbl_payroll_deduction_payment_v2','tbl_payroll_deduction_v2.payroll_deduction_id','=','tbl_payroll_deduction_payment_v2.payroll_deduction_id')
          ->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_deduction_payment_v2.payroll_employee_id')
          ->select(DB::raw('IFNULL(sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount),0) as total_payment, IFNULL(count(tbl_payroll_deduction_payment_v2.deduction_name),0) as number_of_payment , tbl_payroll_deduction_v2.* , tbl_payroll_employee_basic.*'))
          ->groupBy('tbl_payroll_deduction_payment_v2.deduction_name','tbl_payroll_deduction_v2.payroll_deduction_id')
          ->orderBy('tbl_payroll_employee_basic.payroll_employee_id','asc');
          if ($shop_id!=0) 
          {
          	$query->where('tbl_payroll_deduction_v2.shop_id',$shop_id);
          }
          if ($payroll_deduction_type != '') 
          {
          	$query->where('tbl_payroll_deduction_v2.payroll_deduction_type',$payroll_deduction_type);
          }

          return $query;
	}

	public function scopedeductionbytype($query, $shop_id = 0, $payroll_deduction_type = '',$archive)
	{
		$query->join('tbl_payroll_deduction_employee_v2','tbl_payroll_deduction_employee_v2.payroll_deduction_id','=','tbl_payroll_deduction_v2.payroll_deduction_id')
		->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_deduction_employee_v2.payroll_employee_id')
		->select(DB::raw('tbl_payroll_employee_basic.*, tbl_payroll_deduction_v2.*,
			IFNULL((select count(tbl_payroll_deduction_payment_v2.payroll_total_payment_amount) from tbl_payroll_deduction_payment_v2 where tbl_payroll_deduction_payment_v2.payroll_deduction_id = tbl_payroll_deduction_v2.payroll_deduction_id and tbl_payroll_deduction_payment_v2.payroll_employee_id = tbl_payroll_employee_basic.payroll_employee_id GROUP BY tbl_payroll_deduction_payment_v2.deduction_name)  ,0) as number_of_payment,
			IFNULL((select sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount) from tbl_payroll_deduction_payment_v2 where tbl_payroll_deduction_payment_v2.payroll_deduction_id = tbl_payroll_deduction_v2.payroll_deduction_id and tbl_payroll_deduction_payment_v2.payroll_employee_id = tbl_payroll_employee_basic.payroll_employee_id GROUP BY tbl_payroll_deduction_payment_v2.deduction_name)  ,0) as total_payment
			'));
		  if ($shop_id!=0) 
	      {
	      	$query->where('tbl_payroll_deduction_v2.shop_id',$shop_id);
	      }
	      if ($payroll_deduction_type != '') 
	      {
	      	$query->where('tbl_payroll_deduction_v2.payroll_deduction_type',$payroll_deduction_type);
	      }
	      
	      $query->where('tbl_payroll_deduction_v2.payroll_deduction_archived',$archive);
	      
	      


	    return $query;
		/*select tbl_payroll_deduction_v2.payroll_deduction_type, tbl_payroll_employee_basic.payroll_employee_display_name, tbl_payroll_deduction_v2.payroll_deduction_name,  tbl_payroll_deduction_v2.payroll_deduction_amount,
		IFNULL((select count(tbl_payroll_deduction_payment_v2.payroll_total_payment_amount) from tbl_payroll_deduction_payment_v2 where tbl_payroll_deduction_payment_v2.payroll_deduction_id = tbl_payroll_deduction_v2.payroll_deduction_id and tbl_payroll_deduction_payment_v2.payroll_employee_id = tbl_payroll_employee_basic.payroll_employee_id GROUP BY tbl_payroll_deduction_payment_v2.deduction_name)  ,0) as number_of_payment,
		IFNULL((select sum(tbl_payroll_deduction_payment_v2.payroll_payment_amount) from tbl_payroll_deduction_payment_v2 where tbl_payroll_deduction_payment_v2.payroll_deduction_id = tbl_payroll_deduction_v2.payroll_deduction_id and tbl_payroll_deduction_payment_v2.payroll_employee_id = tbl_payroll_employee_basic.payroll_employee_id GROUP BY tbl_payroll_deduction_payment_v2.deduction_name)  ,0) as total_payment
		from tbl_payroll_deduction_v2
		inner join tbl_payroll_deduction_employee_v2 on tbl_payroll_deduction_employee_v2.payroll_deduction_id = tbl_payroll_deduction_v2.payroll_deduction_id
		inner join tbl_payroll_employee_basic on tbl_payroll_employee_basic.payroll_employee_id = tbl_payroll_deduction_employee_v2.payroll_employee_id*/

	}

}
