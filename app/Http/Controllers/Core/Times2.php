<?php
namespace App\Http\Controllers\Core;
use App\Http\Controllers\Controller;
use App\Globals\Payroll2;
use App\Globals\Payroll;
use stdClass;
use DateTime;

class Times2 extends Controller
{
	public function time_exist()
	{

		$if_check_exist = Payroll2::time_check_if_exist_between("11:00:00","11:00:00","13:00:00");

		if ($if_check_exist)
		{
			echo "Time is in Between!";
		}
		else
		{
			echo "Time is not in Between!";
		}
		
	}

	public function time_shift()
	{	
		$_time[0] = new stdClass();
		$_time[0]->time_in = "07:52:00"; 
		$_time[0]->time_out = "17:34:00"; 
		$_time[1] = new stdClass();
		$_time[1]->time_in = "21:00:00"; 
		$_time[1]->time_out = "23:03:00"; 

		//INPUT SHIFT 
	
		$_shift[0] = new stdClass();
		$_shift[0]->shift_in = "08:00:00"; 
		$_shift[0]->shift_out = "12:00:00";
		$_shift[1] = new stdClass();
		$_shift[1]->shift_in = "13:00:00"; 
		$_shift[1]->shift_out = "17:00:00";
		
		$_output = Payroll2::clean_shift($_time, $_shift, true);	
		
		dd($_output);
	}
	

	public function compute_time()
	{
		/*error input output*/
		$_time[0] = new stdClass();
		$_time[0]->time_in = "09:00:00"; 
		$_time[0]->time_out = "18:00:00";

		$_shift[0] = new stdClass();
		$_shift[0]->shift_in = "09:00:00"; 
		$_shift[0]->shift_out = "11:00:00";
		$_shift[1] = new stdClass();
		$_shift[1]->shift_in = "12:00:00"; 
		$_shift[1]->shift_out = "15:00:00";
		$_shift[2] = new stdClass();
		$_shift[2]->shift_in = "16:00:00"; 
		$_shift[2]->shift_out = "19:00:00";
		
           
		$late_grace_time = "00:15:00";
		$overtime_grace_time = "00:15:00";
		$late_grace_time_rule = "first";
		$grace_time_rule_overtime = "per_shift";
		$day_type = "regular";
		$is_holiday = "not_holiday";
		$leave = "00:00:00";
		$leave_fill_late=0;
		$leave_fill_undertime=0;

		$_output = Payroll2::clean_shift($_time, $_shift, true);
		$time = Payroll2::compute_time_mode_regular($_output, $_shift, $late_grace_time, $late_grace_time_rule, $overtime_grace_time, $grace_time_rule_overtime, $day_type, $is_holiday, $leave, $leave_fill_late, $leave_fill_undertime,false);

		//dd($time);
		dd($_output);
	}


	public function compute_flexi_time()
	{
		$_time[0] = new stdClass();
		$_time[0]->time_in = "09:00:00"; 
		$_time[0]->time_out = "23:59:00";
		// $_time[1] = new stdClass();
		// $_time[1]->time_in = "09:00:00"; 
		// $_time[1]->time_out = "12:00:00";
		// $_time[2] = new stdClass();
		// $_time[2]->time_in = "13:00:00"; 
		// $_time[2]->time_out = "18:00:00";


		$target_hours=8;
		$break_hours = "00:00:00";
		$overtime_grace_time = "00:15:00";
		$grace_time_rule_overtime="per_shift";
		$day_type="regular";
		$is_holiday="";
		$leave="00:00:00";
		$leave_fill_undertime=0;
		$_output = Payroll2::clean_shift_flexi($_time,$break_hours,$target_hours,false);
		$flexi_time = Payroll2::compute_time_mode_flexi($_output, $target_hours, $break_hours, $overtime_grace_time , $grace_time_rule_overtime, $day_type, $is_holiday, $leave, $leave_fill_undertime, true);
		//dd($_output);
		dd($flexi_time);
	}

}
