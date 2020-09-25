<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Tbl_payroll_leave_schedulev2 extends Model
{
    //
    protected $table = 'tbl_payroll_leave_schedulev2';
	protected $primaryKey = "payroll_leave_schedule_id";
    public $timestamps = false;

    public function scopegetlist($query, $shop_id = 0, $date = '0000-00-00' ,$param = '>=')
	{
		//table is okay
		$query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_leave_employee_id','=','tbl_payroll_leave_schedulev2.payroll_leave_employee_id')
			  ->join('tbl_payroll_leave_tempv2','tbl_payroll_leave_tempv2.payroll_leave_temp_id','=','tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			  ->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_employee_v2.payroll_employee_id')
			  ->where('tbl_payroll_leave_schedulev2.shop_id', $shop_id)
			  ->where('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $param, $date);
		return $query;
	}
	public function scopespecific($query, $payroll_leave_schedule_id = 0)
	{
			//table is okay
		$query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_leave_employee_id','=','tbl_payroll_leave_schedulev2.payroll_leave_employee_id')
			  ->join('tbl_payroll_leave_tempv2','tbl_payroll_leave_tempv2.payroll_leave_temp_id','=','tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			  ->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_employee_v2.payroll_employee_id')
			  ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_id', $payroll_leave_schedule_id);
		return $query;
	}


	public function scopecheckemployee($query, $payroll_employee_id = 0, $payroll_schedule_leave = '0000-00-00')
	{
				//table is okay
		$query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_leave_employee_id','=','tbl_payroll_leave_schedulev2.payroll_leave_employee_id')
			  ->join('tbl_payroll_leave_tempv2','tbl_payroll_leave_tempv2.payroll_leave_temp_id','=','tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			  ->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_employee_v2.payroll_employee_id')
			  ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			  ->where('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $payroll_schedule_leave);
		return $query;
	}

	public function scopegetremaining($query, $payroll_employee_id = 0, $date = '0000-00-00')
	{
				//table is okay
		$query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_leave_employee_id','=','tbl_payroll_leave_schedulev2.payroll_leave_employee_id')
			  ->join('tbl_payroll_leave_tempv2','tbl_payroll_leave_tempv2.payroll_leave_temp_id','=','tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			  ->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_employee_v2.payroll_employee_id')
			  ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			  ->where('tbl_payroll_leave_schedulev2.payroll_schedule_leave', '<' ,$date);
		return $query;
	}

	public function scopegetyearly($query, $payroll_leave_employee_id = 0, $date = array())
	{
		if(empty($date))
		{
			$date[0] = date('Y-01-01');
			$date[1] = date('Y-12-31');
		}

		$query->where('payroll_leave_employee_id', $payroll_leave_employee_id)->whereBetween('payroll_schedule_leave', $date);

		return $query;
	}


	public function scopegetemployeeleavedata($query, $payroll_employee_id = 0)
	{
				//table is okay
		$query->join('tbl_payroll_leave_employee_v2', 'tbl_payroll_leave_schedulev2.payroll_leave_employee_id', '=', 'tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
                              ->join('tbl_payroll_leave_tempv2', 'tbl_payroll_leave_employee_v2.payroll_leave_temp_id', '=', 'tbl_payroll_leave_tempv2.payroll_leave_temp_id')
                              ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', '=', $payroll_employee_id)
                              ->orderBy('tbl_payroll_leave_schedulev2.payroll_leave_schedule_id', 'desc');
        return $query;
	}

	public function scopegetemployeeleavedatedata($query, $payroll_employee_id = 0, $date = "0000-00-00")
	{
			//table is okay
		$query->join('tbl_payroll_leave_employee_v2', 'tbl_payroll_leave_schedulev2.payroll_leave_employee_id', '=', 'tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
                              ->join('tbl_payroll_leave_tempv2', 'tbl_payroll_leave_employee_v2.payroll_leave_temp_id', '=', 'tbl_payroll_leave_tempv2.payroll_leave_temp_id')
                              ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', '=', $payroll_employee_id)
                              ->where('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $date)
                              ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_archived', 0)
                              ->orderBy('tbl_payroll_leave_schedulev2.payroll_leave_schedule_id', 'desc');
        return $query;
	}


	public function scopegetemployeeleaveconsumesumdata($query, $payroll_leave_employee_id = 0)
	{

		/*select tbl_payroll_leave_employee.payroll_leave_employee_id, sum(tbl_payroll_leave_schedule.consume)from 
		tbl_payroll_leave_schedule left join tbl_payroll_leave_employee 
		on tbl_payroll_leave_employee.payroll_leave_employee_id = tbl_payroll_leave_schedule.payroll_leave_employee_id
		where tbl_payroll_leave_employee.payroll_leave_employee_id = 104
		group by tbl_payroll_leave_employee.payroll_leave_employee_id*/

		$query->select(DB::raw('tbl_payroll_leave_employee_v2.payroll_leave_employee_id, sum(tbl_payroll_leave_schedulev2.consume) as total_leave_consume'))
             ->leftJoin("tbl_payroll_leave_employee_v2","tbl_payroll_leave_employee_v2.payroll_leave_employee_id","=","tbl_payroll_leave_schedulev2.payroll_leave_employee_id")
             ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', '=', $payroll_leave_employee_id)
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_employee_id');

        return $query;
	}

	public function scopegetannualleave($query, $payroll_employee_id=0, $payroll_leave_employee_id=0,$month=0,$company=0)
	{
			  $tempmonth = date("Y-m-d");
	          $splitdate = explode("-", $tempmonth);
	          $lastyear = $splitdate[0] - 1;


		     $query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_schedulev2.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->join("tbl_payroll_employee_basic","tbl_payroll_leave_employee_v2.payroll_employee_id","=","tbl_payroll_employee_basic.payroll_employee_id")
             ->join("tbl_payroll_leave_tempv2","tbl_payroll_leave_employee_v2.payroll_leave_temp_id","=","tbl_payroll_leave_tempv2.payroll_leave_temp_id")
             ->select(DB::raw('tbl_payroll_leave_schedulev2.payroll_leave_temp_name, tbl_payroll_employee_basic.payroll_employee_id , tbl_payroll_leave_schedulev2.payroll_leave_date_created, tbl_payroll_employee_basic.payroll_employee_display_name, tbl_payroll_leave_employee_v2.payroll_leave_employee_id, tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_leave_schedulev2.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_leave_schedulev2.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_temp_id') //remove if total 
			 ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', $payroll_leave_employee_id) //remove if total 
			 ->whereMonth('tbl_payroll_leave_schedulev2.payroll_schedule_leave',$month)
			 ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_archived',0)
             ->whereYear('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $lastyear);

              if ($company !=0) 
	          {
	          	   	$query->where('tbl_payroll_employee_basic.payroll_employee_company_id',$company);
	          }


            return $query;                                                     
    
	}



	public function scopegetviewleavedata($query, $payroll_employee_id=0, $payroll_leave_employee_id=0,$date = array(),$company = 0, $shop_id = 0)
	{

			if(empty($date))
			{
				$date[0] = date('2010-01-01');
				$date[1] = date('Y-12-31');
			}


			 $query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_schedulev2.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->join("tbl_payroll_employee_basic","tbl_payroll_leave_employee_v2.payroll_employee_id","=","tbl_payroll_employee_basic.payroll_employee_id")
             ->join("tbl_payroll_leave_tempv2","tbl_payroll_leave_employee_v2.payroll_leave_temp_id","=","tbl_payroll_leave_tempv2.payroll_leave_temp_id")
             ->select(DB::raw('tbl_payroll_leave_schedulev2.payroll_leave_temp_name, tbl_payroll_employee_basic.payroll_employee_id , tbl_payroll_leave_schedulev2.payroll_leave_date_created, tbl_payroll_employee_basic.payroll_employee_display_name, tbl_payroll_leave_employee_v2.payroll_leave_employee_id, tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_leave_schedulev2.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_leave_schedulev2.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', $payroll_leave_employee_id)
			 ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_archived',0)
             ->whereBetween('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $date);


              if ($company !=0) 
	          {
	          	   	$query->where('tbl_payroll_employee_basic.payroll_employee_company_id',$company);
	          }
	      	  else
	      	  {
	      	  		$query->where('tbl_payroll_leave_schedulev2.shop_id',$shop_id);
	      	  }

            return $query;                                                     
    
	}

		public function scopegetviewleavedata2($query, $payroll_employee_id=0, $payroll_leave_employee_id=0,$shop_id=0)
	{

			if(empty($date))
			{
				$date[0] = date('2010-01-01');
				$date[1] = date('Y-12-31');
			}


			 $query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_schedulev2.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->join("tbl_payroll_employee_basic","tbl_payroll_leave_employee_v2.payroll_employee_id","=","tbl_payroll_employee_basic.payroll_employee_id")
             ->join("tbl_payroll_leave_tempv2","tbl_payroll_leave_employee_v2.payroll_leave_temp_id","=","tbl_payroll_leave_tempv2.payroll_leave_temp_id")
             ->select(DB::raw('tbl_payroll_leave_schedulev2.payroll_leave_temp_name, tbl_payroll_employee_basic.payroll_employee_id , tbl_payroll_leave_schedulev2.payroll_leave_date_created, tbl_payroll_employee_basic.payroll_employee_display_name, tbl_payroll_leave_employee_v2.payroll_leave_employee_id, tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_leave_schedulev2.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_leave_schedulev2.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', $payroll_leave_employee_id)
			 ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_archived',0)
             ->whereBetween('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $date)
	      	  ->where('tbl_payroll_leave_schedulev2.shop_id',$shop_id);

            return $query;                                                     
    
	}

	public function scopegetallemployeeleavedata($query,$payroll_leave_employee_id=0)
	{

			$date[0] = date('2010-01-01');
			$date[1] = date('Y-12-31');
		

			 $query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_schedulev2.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->join("tbl_payroll_employee_basic","tbl_payroll_leave_employee_v2.payroll_employee_id","=","tbl_payroll_employee_basic.payroll_employee_id")
             ->join("tbl_payroll_leave_tempv2","tbl_payroll_leave_employee_v2.payroll_leave_temp_id","=","tbl_payroll_leave_tempv2.payroll_leave_temp_id")
             ->select(DB::raw('tbl_payroll_employee_basic.payroll_employee_id , tbl_payroll_employee_basic.payroll_employee_display_name, tbl_payroll_leave_employee_v2.payroll_leave_employee_id, tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_leave_schedulev2.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_leave_schedulev2.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', $payroll_leave_employee_id)
			 ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_archived',0)
			 ->whereBetween('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $date);  
             		

        return $query;
	}

	public function scopegetmonthleavereportfilter($query,$payroll_employee_id=0,$date = "0000-00-00",$company = 0, $shop_id = 0)
	{	

			 $query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_schedulev2.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->join("tbl_payroll_employee_basic","tbl_payroll_leave_employee_v2.payroll_employee_id","=","tbl_payroll_employee_basic.payroll_employee_id")
             ->join("tbl_payroll_leave_tempv2","tbl_payroll_leave_employee_v2.payroll_leave_temp_id","=","tbl_payroll_leave_tempv2.payroll_leave_temp_id")
             ->select(DB::raw('tbl_payroll_leave_schedulev2.payroll_leave_temp_name ,tbl_payroll_employee_basic.payroll_employee_id , tbl_payroll_leave_schedulev2.payroll_schedule_leave, tbl_payroll_employee_basic.payroll_employee_display_name, tbl_payroll_leave_schedulev2.payroll_leave_temp_with_pay, tbl_payroll_leave_employee_v2.payroll_leave_employee_id, tbl_payroll_leave_schedulev2.consume, tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_leave_schedulev2.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_leave_schedulev2.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_schedulev2.payroll_leave_schedule_id')
			 ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_archived',0)
			 ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			 ->whereBetween('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $date); 

			 
              if ($company !=0) 
	          {
	          	   	$query->where('tbl_payroll_employee_basic.payroll_employee_company_id',$company);
	          }
	      	  else
	      	  {
	      	  		$query->where('tbl_payroll_leave_schedulev2.shop_id',$shop_id);
	      	  } 
             

        return $query;
	}

	public function scopegetleavewithpay($query, $payroll_employee_id=0, $payroll_leave_employee_id=0,$date = "0000-00-00",$company = 0, $shop_id = 0)
	{

			 $query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_schedulev2.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->join("tbl_payroll_employee_basic","tbl_payroll_leave_employee_v2.payroll_employee_id","=","tbl_payroll_employee_basic.payroll_employee_id")
             ->join("tbl_payroll_leave_tempv2","tbl_payroll_leave_employee_v2.payroll_leave_temp_id","=","tbl_payroll_leave_tempv2.payroll_leave_temp_id")
             ->select(DB::raw('tbl_payroll_leave_schedulev2.payroll_leave_temp_name, tbl_payroll_employee_basic.payroll_employee_id , tbl_payroll_leave_schedulev2.payroll_leave_date_created, tbl_payroll_employee_basic.payroll_employee_display_name, tbl_payroll_leave_employee_v2.payroll_leave_employee_id, tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_leave_schedulev2.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_leave_schedulev2.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', $payroll_leave_employee_id)
			 ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_archived',0)
			 ->where('tbl_payroll_leave_schedulev2.payroll_leave_temp_with_pay',1)
             ->whereBetween('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $date);

              if ($company !=0) 
	          {
	          	   	$query->where('tbl_payroll_employee_basic.payroll_employee_company_id',$company);
	          }
	      	  else
	      	  {
	      	  		$query->where('tbl_payroll_leave_schedulev2.shop_id',$shop_id);
	      	  }


            return $query;                                                     
    
	}

	public function scopegetleavewithoutpay($query, $payroll_employee_id=0, $payroll_leave_employee_id=0,$date = "0000-00-00",$company = 0, $shop_id = 0)
	{	
			 $query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_schedulev2.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->join("tbl_payroll_employee_basic","tbl_payroll_leave_employee_v2.payroll_employee_id","=","tbl_payroll_employee_basic.payroll_employee_id")
             ->join("tbl_payroll_leave_tempv2","tbl_payroll_leave_employee_v2.payroll_leave_temp_id","=","tbl_payroll_leave_tempv2.payroll_leave_temp_id")
             ->select(DB::raw('tbl_payroll_leave_schedulev2.payroll_leave_temp_name, tbl_payroll_employee_basic.payroll_employee_id , tbl_payroll_leave_schedulev2.payroll_leave_date_created, tbl_payroll_employee_basic.payroll_employee_display_name, tbl_payroll_leave_employee_v2.payroll_leave_employee_id, tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_leave_schedulev2.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_leave_schedulev2.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', $payroll_leave_employee_id)
			 ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_archived',0)
			 ->where('tbl_payroll_leave_schedulev2.payroll_leave_temp_with_pay',0)
             ->whereBetween('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $date);

              if ($company !=0) 
	          {
	          	   	$query->where('tbl_payroll_employee_basic.payroll_employee_company_id',$company);
	          }
	      	  else
	      	  {
	      	  		$query->where('tbl_payroll_leave_schedulev2.shop_id',$shop_id);
	      	  }



            return $query;                                                     
    
	}

	public function scopegetremainings($query, $payroll_employee_id=0,$date = "0000-00-00", $company = 0, $shop_id = 0)
	{

		$query->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_leave_employee_id','=','tbl_payroll_leave_schedulev2.payroll_leave_employee_id')
				  ->join('tbl_payroll_leave_tempv2','tbl_payroll_leave_tempv2.payroll_leave_temp_id','=','tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
				  ->join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_employee_v2.payroll_employee_id')
				     ->select(DB::raw('tbl_payroll_employee_basic.payroll_employee_id,(tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_leave_schedulev2.consume)) as remaining_leave'))
				  ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
				  ->where('tbl_payroll_leave_schedulev2.payroll_leave_schedule_archived',0)
				  ->whereBetween('tbl_payroll_leave_schedulev2.payroll_schedule_leave', $date);


              if ($company !=0) 
	          {
	          	   	$query->where('tbl_payroll_employee_basic.payroll_employee_company_id',$company);
	          }
	      	  else
	      	  {
	      	  		$query->where('tbl_payroll_leave_schedulev2.shop_id',$shop_id);
	      	  }

			return $query;
	}


}
