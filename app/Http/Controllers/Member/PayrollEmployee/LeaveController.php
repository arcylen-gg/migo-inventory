<?php
namespace App\Http\Controllers\Member\PayrollEmployee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Member\PayrollEmployee\EmployeeController;
use App\Http\Controllers\Member\PayrollEmployee\RequestForPaymentController;
use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_payroll_period;
use App\Models\Tbl_payroll_company;
use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_leave_temp;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_time_sheet_record_approved;
use App\Models\Tbl_payroll_rdo;
use App\Models\Tbl_payroll_overtime_rate;
use App\Models\Tbl_payroll_shift_code;
use App\Models\Tbl_payroll_shift_time;
use App\Models\Tbl_payroll_shift_day;
use App\Models\Tbl_payroll_approver_group;
use App\Models\Tbl_payroll_request_overtime;
use App\Models\Tbl_payroll_approver_employee;
use App\Models\Tbl_payroll_request_payment;
use App\Models\Tbl_payroll_request_payment_sub;
use App\Models\Tbl_payroll_request_leave;
use App\Models\Tbl_payroll_leave_employeev2;
use App\Models\Tbl_payroll_leave_tempv2;
use App\Globals\Payroll2;
use App\Globals\Payroll;
use App\Globals\Utilities;
use Redirect;
use Validator;
use Carbon\Carbon;
use Crypt;
use Session;
use DB;
use Request;
use App\Http\Controllers\Member\PayrollMember;

use PDF2;
use App\Globals\Pdf_global;


class LeaveController extends PayrollMember
{
	public function employee_info()
	{
		return $this->employee_info;
	}

	public function employee_id()
	{
		return $this->employee_info->payroll_employee_id;
	}

	public function employee_shop_id()
	{
		return $this->employee_info->shop_id;
	}

	public function employee_summary_of_leave()
	{
		$data['page']	= 'Summary of Leave';
		return view('member.payroll2.employee_dashboard.employee_summary_of_leave',$data);
	}

	public function authorized_access_leave()
	{
		$data['page']  = 'Authorized Access Leave';
		$data['employee_approver_info']	= Tbl_payroll_approver_employee::GetApproverInfoByType(Self::employee_id(), 'leave')->first();
		
		$level_approver 		= $data['employee_approver_info']['payroll_approver_employee_level'];
		$approver_employee_id 	= $data['employee_approver_info']['payroll_approver_employee_id'];

		$data['_request_pending']  = Tbl_payroll_request_leave::GetAllRequest($approver_employee_id, $level_approver, 'pending')->paginate(15);
		$data['_request_approved'] = Tbl_payroll_request_leave::GetAllRequest($approver_employee_id, $level_approver, 'approved')->paginate(15);
		$data['_request_rejected'] = Tbl_payroll_request_leave::GetAllRequest($approver_employee_id, $level_approver, 'rejected')->paginate(15);
		$data['_request_canceled'] = Tbl_payroll_request_leave::GetAllRequest($approver_employee_id, $level_approver, 'canceled')->paginate(15);
		
		return view('member.payroll2.employee_dashboard.authorized_access_leave',$data);
	}

	public function employee_leave_application()
	{

		$shop_id = Self::employee_shop_id();
		$employee_id = Self::employee_id();

		$data['page']		= 'Employee Leave Application';
        $data["company"] 	= Tbl_payroll_company::where("tbl_payroll_company.payroll_company_id", $this->employee_info->payroll_employee_company_id)->first();

	    $data["employees_info"] = Tbl_payroll_employee_contract::employeefilter($company = 0, $department = 0, $jobtitle = 0, date('Y-m-d'), $shop_id)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->groupBy('tbl_payroll_employee_basic.payroll_employee_id')->get();

	   	$data['_group_approver'] = Tbl_payroll_approver_group::where('tbl_payroll_approver_group.shop_id', Self::employee_shop_id())->where('payroll_approver_group_type','leave')->where('archived', 0)->get();
	      
	    $data['leave_temp_id']	= Tbl_payroll_leave_employeev2::select('payroll_leave_temp_id')->where('payroll_employee_id',$employee_id)->where('payroll_leave_employee_is_archived',0)->get();

	    $data['leave_type'] = Tbl_payroll_leave_tempv2::whereIn('payroll_leave_temp_id',$data['leave_temp_id'])->where('payroll_leave_temp_archived',0)->get();

    	return view('member.payroll2.employee_dashboard.employee_leave_application',$data);
    }

    public function create_employee_leave()
    {
		$data['page']	= 'Create Employee Leave';
    	return view('member.payroll2.employee_dashboard.create_employee_leave',$data);
    }

	public function employee_leave_management()
	{
		$data['page']			= 'Leave Management';
	  	return view('member.payroll2.employee_dashboard.employee_leave_management',$data);
	}

	public function ajax_load_pending_leave()
	{

		$emp = Tbl_payroll_request_leave::select('tbl_payroll_request_leave.*','tbl_payroll_employee_basic.payroll_employee_display_name')
		 		->leftjoin('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id', '=','tbl_payroll_request_leave.payroll_request_leave_id_reliever')
		 		->where('tbl_payroll_request_leave.payroll_employee_id',Self::employee_id())
		 		->where('tbl_payroll_request_leave.payroll_request_leave_status','pending')->get();

		 return json_encode($emp);
	}

	public function ajax_load_approved_leave()
	{

		 $emp = Tbl_payroll_request_leave::select('tbl_payroll_request_leave.*','tbl_payroll_employee_basic.payroll_employee_display_name')
		 		->leftjoin('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id', '=','tbl_payroll_request_leave.payroll_request_leave_id_reliever')
		 		->where('tbl_payroll_request_leave.payroll_employee_id',Self::employee_id())
		 		->where('tbl_payroll_request_leave.payroll_request_leave_status','approved')->get();

		 return json_encode($emp);
	}

	public function ajax_load_rejected_leave()
	{
		 $emp = Tbl_payroll_request_leave::select('tbl_payroll_request_leave.*','tbl_payroll_employee_basic.payroll_employee_display_name')
		 		->leftjoin('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id', '=','tbl_payroll_request_leave.payroll_request_leave_id_reliever')
		 		->where('tbl_payroll_request_leave.payroll_employee_id',Self::employee_id())
		 		->where('tbl_payroll_request_leave.payroll_request_leave_status','rejected')->get();

		 return json_encode($emp);
	}

	public function ajax_load_canceled_leave()
	{
		 $emp = Tbl_payroll_request_leave::select('tbl_payroll_request_leave.*','tbl_payroll_employee_basic.payroll_employee_display_name')
		 		->leftjoin('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id', '=','tbl_payroll_request_leave.payroll_request_leave_id_reliever')
		 		->where('tbl_payroll_request_leave.payroll_employee_id',Self::employee_id())
		 		->where('tbl_payroll_request_leave.payroll_request_leave_status','canceled')->get();

		 return json_encode($emp);
	}

	public function save_leave()
    {

    	$leave_info = Tbl_payroll_leave_employeev2::join('tbl_payroll_leave_tempv2','tbl_payroll_leave_employee_v2.payroll_leave_temp_id','=','tbl_payroll_leave_tempv2.payroll_leave_temp_id')->select('tbl_payroll_leave_employee_v2.payroll_leave_employee_id','tbl_payroll_leave_tempv2.payroll_leave_temp_name')->where('tbl_payroll_leave_employee_v2.payroll_leave_temp_id',Request::input('payroll_request_leave_type'))->where('tbl_payroll_leave_employee_v2.payroll_employee_id',Self::employee_id())->get();

    	$insert = array();
    	foreach($leave_info as $leave)
    	{
    			if(Request::has('single_date_only'))
		        {	 
		        	 $temp['payroll_approver_group_id'] 			=   Request::input('approver_group');
			    	 $temp['payroll_request_leave_id_reliever']		=	Request::input('payroll_request_leave_id_reliever');
					 $temp['payroll_employee_id']					= 	Self::employee_id();
					 $temp['payroll_request_leave_date']			=	Request::input('payroll_request_leave_date');
					 $temp['payroll_request_leave_date_filed']		=	Request::input('payroll_request_leave_date_filed');
					 $temp['payroll_request_leave_total_hours']		=	Request::input('payroll_request_leave_total_hours');
					 $temp['payroll_request_leave_remark']			=	Request::input('remark');
					 $temp['payroll_request_leave_status']			=	'pending';
					 $temp['payroll_request_leave_status_level'] 	=    1;
					 $temp['payroll_request_leave_type']			=	$leave->payroll_leave_temp_name;
					 $temp['payroll_leave_employee_id']				=   $leave->payroll_leave_employee_id;
          			 $temp['consume']                               = Payroll::time_float(Request::input('payroll_request_leave_total_hours'));
					 array_push($insert, $temp);
				}
				else
				{
					  $end = datepicker_input(Request::input('payroll_request_leave_date_end'));
		              $payroll_request_leave_date = datepicker_input(Request::input('payroll_request_leave_date'));

					   while($payroll_request_leave_date <= $end)
		               {
		               	   	 $temp['payroll_approver_group_id'] 			= Request::input('approver_group');
		               		 $temp['payroll_request_leave_id_reliever']		=	Request::input('payroll_request_leave_id_reliever');
							 $temp['payroll_employee_id']					= 	Self::employee_id();
							 $temp['payroll_request_leave_date']			=	$payroll_request_leave_date;
							 $temp['payroll_request_leave_date_filed']		=	Request::input('payroll_request_leave_date_filed');
							 $temp['payroll_request_leave_total_hours']		=	Request::input('payroll_request_leave_total_hours');
							 $temp['payroll_request_leave_remark']			=	"Used ".Request::input('payroll_request_leave_total_hours')." hours in ".Request::input('payroll_request_leave_type');
							 $temp['payroll_request_leave_status']			=	'pending';
							 $temp['payroll_request_leave_status_level'] 	=    1;
							 $temp['payroll_request_leave_type']			=	$leave->payroll_leave_temp_name;
							 $temp['payroll_leave_employee_id']				=   $leave->payroll_leave_employee_id;
							 $temp['consume']                               = Payroll::time_float(Request::input('payroll_request_leave_total_hours'));

							 array_push($insert, $temp);
		     				 $payroll_request_leave_date = Carbon::parse($payroll_request_leave_date)->addDay()->format("Y-m-d");
		               }
				}
    	}

		if(!empty($insert)) 
        {  
           	Tbl_payroll_request_leave::insert($insert);
        }

	   	$response['call_function'] = 'reload';
		$response['status'] = 'success';

		return $response;
    	
    }
	
	public function employee_request_leave_view($request_id)
	{
		$data['request_info'] = Tbl_payroll_request_leave::where('payroll_request_leave_id',$request_id)->EmployeeInfo()->first();
		$data['approver_group_info'] = Tbl_payroll_approver_group::where('payroll_approver_group_id',$data['request_info']['payroll_approver_group_id'])->first();
		$data['page'] = 'View Leave Request';
		$data['_group_approver'] = Self::get_group_approver_grouped_by_level(Self::employee_shop_id(),$data['request_info']['payroll_approver_group_id'], 'leave');	

		$data['reliever'] = Tbl_payroll_employee_basic::select('*')->where('payroll_employee_id',$data['request_info']['payroll_request_leave_id_reliever'])->get();

		return view('member.payroll2.employee_dashboard.modal.modal_view_leave_request',$data);
	}

	public function employee_request_leave_cancel($request_id)
	{
		if (Request::method() == 'POST') 
		{
			$update['payroll_request_leave_status'] = "canceled";
			Tbl_payroll_request_leave::where('payroll_request_leave_id',$request_id)->update($update);
			
			$response['status'] = 'success';
			$response['call_function'] = 'reload';

			return $response;
		}
		else
		{
			
			$data['id'] 	 = $request_id;
			$data['action']  = 'employee_request_leave_cancel/'.$request_id;
			$data['message'] = 'Warning: you cannot restore canceled request, Do you really want to cancel this request?';
			$data['btn']	 = '<label><button type="submit" class="btn btn-custom-white">Confirm</label>';
			
			return view('member.payroll2.employee_dashboard.modal.modal_confirm',$data);
		}
		
	}

	public function view_leave_request($request_id)
	{
		$data['request_info'] = Tbl_payroll_request_leave::where('payroll_request_leave_id',$request_id)->EmployeeInfo()->first();
		$data['approver_group_info'] = Tbl_payroll_approver_group::where('payroll_approver_group_id',$data['request_info']['payroll_approver_group_id'])->first();
		$data['page'] = 'View Leave Request';
		$data['_group_approver'] = Self::get_group_approver_grouped_by_level(Self::employee_shop_id(),$data['request_info']['payroll_approver_group_id'], 'leave');	

		$data['reliever'] = Tbl_payroll_employee_basic::select('*')->where('payroll_employee_id',$data['request_info']['payroll_request_leave_id_reliever'])->get();

		return view('member.payroll2.employee_dashboard.modal.modal_view_leave_request',$data);
	}

	public function approve_leave_request($request_id)
	{
		if (Request::method() == 'POST') 
		{
			$request_info = Tbl_payroll_request_leave::where('payroll_request_leave_id',$request_id)->EmployeeInfo()->first();
			
			$_approver_group = collect(Tbl_payroll_approver_group::where('tbl_payroll_approver_group.payroll_approver_group_id', $request_info['payroll_approver_group_id'])
										->EmployeeApproverInfo()->get())
										->groupBy('payroll_approver_group_level');
			
			$count_approvers = count($_approver_group);
			
			/*check if approve or go to next level of approval*/
			if($count_approvers == $request_info['payroll_request_leave_status_level'])
			{
				$update['payroll_request_leave_status'] = "approved";
				Tbl_payroll_request_leave::where('payroll_request_leave_id',$request_id)->update($update);
			}
			else
			{
				$update['payroll_request_leave_status_level'] = $request_info['payroll_request_leave_status_level'] + 1;
				Tbl_payroll_request_leave::where('payroll_request_leave_id',$request_id)->update($update);
			}
			$response['status'] = 'success';
			$response['call_function'] = 'reload';

			return $response;
		}
		else
		{
			$data['id'] 	 = $request_id;
			$data['action']  = '/authorized_access_leave/approve_leave_request/'.$request_id;
			$data['message'] = 'Do you really want to approve this request?';
			$data['btn']	 = '<label><button type="submit" class="btn btn-custom-white">Confirm</label>';
			
			return view('member.payroll2.employee_dashboard.modal.modal_confirm',$data);
		}
	}

	public function get_leave_hours()
	{
			$leave_temp_id = Request::get('leave_temp_id');
			$payroll_employee_id = Self::employee_id();
			$leaveinfo = Tbl_payroll_leave_employeev2::where('payroll_employee_id',$payroll_employee_id)->where('payroll_leave_temp_id',$leave_temp_id)->get();

			$leaveemployeeid = Tbl_payroll_leave_employeev2::where('payroll_employee_id',$payroll_employee_id)->where('payroll_leave_temp_id',$leave_temp_id)->value('payroll_leave_employee_id');

			$empdata = Tbl_payroll_request_leave::join('tbl_payroll_leave_employee_v2','tbl_payroll_request_leave.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->select(DB::raw('tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_request_leave.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_request_leave.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', $leaveemployeeid)
			 ->where('tbl_payroll_request_leave.payroll_leave_employee_id', $leaveemployeeid)
			 ->where('tbl_payroll_request_leave.archived',0)->where('payroll_request_leave_status','approved')->get();

			 if(count($empdata) == 0)
			 {
			 	$empdata = $leaveinfo;
			 }

		 return json_encode($empdata);
	}

	public function reject_leave_request($request_id)
	{
		if (Request::method() == 'POST') 
		{
			$update['payroll_request_leave_status'] = "rejected";
			Tbl_payroll_request_leave::where('payroll_request_leave_id',$request_id)->update($update);
			
			$response['status'] = 'success';
			$response['call_function'] = 'reload';

			return $response;
		}
		else
		{
			$data['id'] 	 = $request_id;
			$data['action']  = '/authorized_access_leave/reject_leave_request/'.$request_id;
			$data['message'] = 'Do you really want to reject this request?';
			$data['btn']	 = '<label><button type="submit" class="btn btn-custom-white">Confirm</label>';
			
			return view('member.payroll2.employee_dashboard.modal.modal_confirm',$data);
		}

	}

	public function employee_request_leave_export_pdf($request_id)
	{
			$parameter['date']					= date('Y-m-d');
			$parameter['company_id']			= 0;
			$parameter['employement_status']	= 0;
			$parameter['shop_id'] 				= $this->employee_info->shop_id;
			$leave_temp_id_sick = Tbl_payroll_leave_tempv2::where('shop_id',Self::employee_shop_id())->where('payroll_leave_temp_name','Sick Leave')->value('payroll_leave_temp_id');
			$payroll_employee_id = Self::employee_id();

			$leavesick = Tbl_payroll_leave_employeev2::where('payroll_employee_id',$payroll_employee_id)->where('payroll_leave_temp_id',$leave_temp_id_sick)->get();

			$leaveemployeeidsick = Tbl_payroll_leave_employeev2::where('payroll_employee_id',$payroll_employee_id)->where('payroll_leave_temp_id',$leave_temp_id_sick)->value('payroll_leave_employee_id');

			$data['sickleave'] = Tbl_payroll_request_leave::join('tbl_payroll_leave_employee_v2','tbl_payroll_request_leave.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->select(DB::raw('tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_request_leave.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_request_leave.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', $leaveemployeeidsick)
			 ->where('tbl_payroll_request_leave.payroll_leave_employee_id', $leaveemployeeidsick)
			 ->where('tbl_payroll_request_leave.archived',0)->where('payroll_request_leave_status','approved')->get();


			 $leave_temp_id_vacation = Tbl_payroll_leave_tempv2::where('shop_id',Self::employee_shop_id())->where('payroll_leave_temp_name','Vacation Leave')->value('payroll_leave_temp_id');

			 $leavecation = Tbl_payroll_leave_employeev2::where('payroll_employee_id',$payroll_employee_id)->where('payroll_leave_temp_id',$leave_temp_id_vacation)->get();

			 $leavevacation = Tbl_payroll_leave_employeev2::where('payroll_employee_id',$payroll_employee_id)->where('payroll_leave_temp_id',$leave_temp_id_vacation)->value('payroll_leave_employee_id');


			 $data['vacationleave'] = Tbl_payroll_request_leave::join('tbl_payroll_leave_employee_v2','tbl_payroll_request_leave.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
             ->select(DB::raw('tbl_payroll_leave_employee_v2.payroll_leave_temp_hours, sum(tbl_payroll_request_leave.consume) as total_leave_consume, (tbl_payroll_leave_employee_v2.payroll_leave_temp_hours - sum(tbl_payroll_request_leave.consume)) as remaining_leave'))
             ->groupBy('tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
			 ->where('tbl_payroll_leave_employee_v2.payroll_employee_id', $payroll_employee_id)
			 ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id', $leavevacation)
			 ->where('tbl_payroll_request_leave.payroll_leave_employee_id', $leavevacation)
			 ->where('tbl_payroll_request_leave.archived',0)->where('payroll_request_leave_status','approved')->get();


			$leaveemployeeid = Tbl_payroll_leave_employeev2::where('payroll_employee_id',$payroll_employee_id)->where('payroll_leave_temp_id',$leave_temp_id_sick)->value('payroll_leave_employee_id');

			$data["employee"] = Tbl_payroll_employee_basic::selemployee($parameter)->where('tbl_payroll_employee_basic.payroll_employee_id',$this->employee_info->payroll_employee_id)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->first();

			$data['department'] = $data["employee"]['payroll_department_name'];

			if(count($data['sickleave']) == 0)
			{
			 	$data['sickleave'] = $leavesick;
			}

			if(count($data['vacationleave']) == 0)
			{
				$data['vacationleave'] = $leavecation;
			}

			$data['leave_info'] = Tbl_payroll_request_leave::join('tbl_payroll_employee_basic','tbl_payroll_request_leave.payroll_employee_id','=','tbl_payroll_employee_basic.payroll_employee_id')->select('tbl_payroll_request_leave.*','tbl_payroll_employee_basic.payroll_employee_display_name')->where('tbl_payroll_request_leave.payroll_request_leave_id',$request_id)->get();

			$data['leave_reliever'] = Tbl_payroll_request_leave::join('tbl_payroll_employee_basic','tbl_payroll_request_leave.payroll_request_leave_id_reliever','=','tbl_payroll_employee_basic.payroll_employee_id')->select('tbl_payroll_employee_basic.payroll_employee_display_name')->where('tbl_payroll_request_leave.payroll_request_leave_id',$request_id)->get();

			$format["format"] = "A4";
			$format["default_font"] = "sans-serif";
			$pdf = PDF2::loadView('member.payroll2.employee_dashboard.request_leave_pdf',$data, [], $format);
			return $pdf->stream('document.pdf');
	}

	public static function get_group_approver_grouped_by_level($shop_id , $approver_group_id, $approver_group_type)
	{
		$_approver_group = collect(Tbl_payroll_approver_group::EmployeeApproverInfo($shop_id, $approver_group_id, $approver_group_type)->get())->groupBy('payroll_approver_group_level');

		return $_approver_group;
	}



}
