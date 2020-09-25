<?php
namespace App\Http\Controllers\Member\PayrollEmployee;

use App\Http\Controllers\Controller;
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


class EmployeeController extends PayrollMember
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

	public static function approver_access($employee_id)
	{
		$data['approver_rfp'] 	= Tbl_payroll_approver_employee::where('payroll_employee_id',$employee_id)->where('payroll_approver_employee_type','rfp')->first();
		$data['approver_ot'] 	= Tbl_payroll_approver_employee::where('payroll_employee_id',$employee_id)->where('payroll_approver_employee_type','overtime')->first();
		$data['approver_leave'] = Tbl_payroll_approver_employee::where('payroll_employee_id',$employee_id)->where('payroll_approver_employee_type','leave')->first();
		$data['approver_ob'] 	= Tbl_payroll_approver_employee::where('payroll_employee_id',$employee_id)->where('payroll_approver_employee_type','ob')->first();
		
		if ($data['approver_rfp'] != null || $data['approver_ot'] != null || $data['approver_leave'] != null || $data['approver_ob'] != null) 
		{
			echo view('member.payroll2.employee_dashboard.authorized_access_menu', $data);
		}
	} 

	public static function authorized_access($employee_id)
	{
		$shop_id = Tbl_payroll_employee_basic::select('shop_id')->where('payroll_employee_id',$employee_id)->first();

		if($shop_id['shop_id'] == 21)
		{
			echo view('member.payroll2.employee_dashboard.authorized_yangming_access');
		}
		else if($shop_id['shop_id'] == 76)
		{
			echo view('member.payroll2.employee_dashboard.authorized_reprisk_access');
		}
		else
		{
			echo view('member.payroll2.employee_dashboard.authorized_all_access_menu');
		}
	}

	public function employee()
	{
		$data['page']	= 'Dashboard';
		
		return view('member.payroll2.employee_dashboard.employee',$data);
	}

	public function company_details()
	{
		$data['page']	= 'Company Details';

		$shop_id = $this->employee_info->shop_id;

		$data['company'] = Tbl_payroll_company::companydetails($shop_id)->where('payroll_parent_company_id',0)->first();
		//dd($data['company']);

		return view('member.payroll2.employee_dashboard.company_details',$data);
	}

	public function employee_profile()
	{
		$data['page']	= 'Profile';
		$parameter['date']					= date('Y-m-d');
		$parameter['company_id']			= 0;
		$parameter['employement_status']	= 0;
		$parameter['shop_id'] 				= $this->employee_info->shop_id;
		$data["employee"] = Tbl_payroll_employee_basic::selemployee($parameter)->where('tbl_payroll_employee_basic.payroll_employee_id',$this->employee_info->payroll_employee_id)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->first();
		$data["company"] = Tbl_payroll_company::where("tbl_payroll_company.payroll_company_id", $this->employee_info->payroll_employee_company_id)->first();
		$data["startdate"] = Tbl_payroll_employee_contract::where("tbl_payroll_employee_contract.payroll_employee_id", $this->employee_info->payroll_employee_id)->first();
		
		return view('member.payroll2.employee_dashboard.employee_profile',$data);
	}

	public function edit_employee_profile()
	{
		$data['page']	= 'Profile';
		$parameter['date']					= date('Y-m-d');
		$parameter['company_id']			= 0;
		$parameter['employement_status']	= 0;
		$parameter['shop_id'] 				= $this->employee_info->shop_id;
		$data["employee"] = Tbl_payroll_employee_basic::selemployee($parameter)->where('tbl_payroll_employee_basic.payroll_employee_id',$this->employee_info->payroll_employee_id)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->first();

		return view('member.payroll2.employee_dashboard.edit_employee_profile',$data);
	}

	public function update_employee_profile(Request $request)
	{
		$data['page']	= 'Profile';

		$update['payroll_employee_display_name'] = $request->name;
		$update['payroll_employee_gender'] = $request->gender;        
		$update['payroll_employee_birthdate'] = $request->birthdate;
        $update['payroll_employee_street'] = $request->street;
        $update['payroll_employee_city'] = $request->city;
        $update['payroll_employee_state'] = $request->state;
        $update['payroll_employee_contact'] = $request->number;
        //$update['payroll_employee_email'] = $request->email;
        //$update['payroll_employee_tin'] = $request->tin;
        $update['payroll_employee_pagibig'] = $request->pagibig;
        $update['payroll_employee_philhealth'] = $request->philhealth;
        $update['payroll_employee_sss'] = $request->sss;


      	$rules['payroll_employee_display_name'] = 'required';
        $rules['payroll_employee_gender'] = 'required';
        $rules['payroll_employee_birthdate'] = 'required|date';
        $rules['payroll_employee_street'] = 'required';
        $rules['payroll_employee_city'] = 'required';
        $rules['payroll_employee_state'] = 'required';
        //$rules['email'] = 'email';
        $rules['payroll_employee_contact'] = 'required|numeric';

        $validator = validator::make($update, $rules);

        $return_message = '';
        if($validator->fails())
        {
            foreach ($validator->messages()->all('<li style=`list-style:none`>:message</li>')as $keys => $message)
            {
                $return_message .= $message;
            }
            return Redirect::to('/edit_employee_profile')->with('error', $return_message);
        }
        else
        {
            Tbl_payroll_employee_basic::where('payroll_employee_id', $request->payroll_employee_id)->update($update);
            return Redirect::to('/employee_profile')->with('warning', 'testing');
        }
	}

	public function employee_official_business()
	{
		$data['page']	= 'Official Business Form';
		return view('member.payroll2.employee_dashboard.employee_official_business',$data);
	}

	

	public function employee_overtime_view_shift()
	{
		$data['page']	= 'Shift Schedule';

		$data['employee'] = Tbl_payroll_employee_basic::EmployeeShift($this->employee_info->payroll_employee_id)->first();

		$data['shift'] = tbl_payroll_shift_day::where("shift_code_id", $data['employee']->shift_code_id)->get();

		foreach ($data['shift'] as $value) 
		{	
			$data['shift_day_id'] = $value->shift_day_id;
			$data['shift_time'] = tbl_payroll_shift_time::where('shift_day_id', $data['shift_day_id'])->get();
		}

		return view('member.payroll2.employee_dashboard.employee_overtime_view_shift',$data);
	}

	/*Start Overtime Management*/

	public function employee_overtime_management()
	{
		$data['page']	= 'Over Time Management';
		
		return view('member.payroll2.employee_dashboard.employee_overtime_management',$data);
	}


	public function employee_overtime_management_table()
	{
		$status = Request::input('status');
		$data['_overtime_request'] = Tbl_payroll_request_overtime::where('payroll_employee_id', $this->employee_info->payroll_employee_id)->where('payroll_request_overtime_status', $status)->get();
		
	return view('member.payroll2.employee_dashboard.employee_overtime_management_table',$data);
	}

	public function employee_overtime_application()
	{
		$data['page']	= 'Overtime Application';
		$data['_group_approver'] = Tbl_payroll_approver_group::where('tbl_payroll_approver_group.shop_id', Self::employee_shop_id())->where('payroll_approver_group_type','overtime')->where('archived', 0)->get();
		
		return view('member.payroll2.employee_dashboard.employee_overtime_application',$data);
	}

	public function employee_request_overtime_save()
	{
		$insert['payroll_employee_id'] 						= $this->employee_info->payroll_employee_id;
		$insert['payroll_approver_group_id'] 				= Request::input('approver_group');
		$insert['payroll_request_overtime_date'] 			= Request::input('overtime_date');
		$insert['payroll_request_overtime_type'] 			= Request::input('overtime_type');
		$insert['payroll_request_overtime_remark'] 			= Request::input('remark');
		$insert['payroll_request_overtime_time_in'] 		= Request::input('overtime_time_in');
		$insert['payroll_request_overtime_time_out'] 		= Request::input('overtime_time_out');
		$insert['payroll_request_regular_time_in'] 			= Request::input('regular_time_in');
		$insert['payroll_request_regular_time_out'] 		= Request::input('regular_time_out');
		$insert['payroll_request_overtime_total_hours'] 	= Payroll::time_diff(Request::input('overtime_time_in'), Request::input('overtime_time_out'));
		$insert['payroll_request_overtime_status'] 			= 'pending';
		$insert['payroll_request_overtime_status_level'] 	= 1;
		$insert['archived'] 								= 0;
		
		Tbl_payroll_request_overtime::insert($insert);

		$response['status'] = 'success';
		$response['call_function'] = 'reload_overtime_management';

		return $response;
	}

	public function employee_request_overtime_view($request_id)
	{
		
		$data['request_info'] = Tbl_payroll_request_overtime::where('payroll_request_overtime_id',$request_id)->EmployeeInfo()->first();
		$data['approver_group_info'] = Tbl_payroll_approver_group::where('payroll_approver_group_id',$data['request_info']['payroll_approver_group_id'])->first();
		$data['page'] = 'View Overtime Request';
		$data['_group_approver'] = Self::get_group_approver_grouped_by_level(Self::employee_shop_id(),$data['request_info']['payroll_approver_group_id'], 'overtime');
		
		return view('member.payroll2.employee_dashboard.modal.modal_view_overtime_request',$data);
	}

	public function employee_request_overtime_export_pdf($request_id)
	{
		$parameter['date']					= date('Y-m-d');
		$parameter['company_id']			= 0;
		$parameter['employement_status']	= 0;
		$parameter['shop_id'] 				= $this->employee_info->shop_id;

		$data['request_info'] = Tbl_payroll_request_overtime::where('payroll_request_overtime_id',$request_id)->EmployeeInfo()->first();
		$data['approver_group_info'] = Tbl_payroll_approver_group::where('payroll_approver_group_id',$data['request_info']['payroll_approver_group_id'])->first();
		$data['_group_approver'] = Self::get_group_approver_grouped_by_level(Self::employee_shop_id(),$data['request_info']['payroll_approver_group_id'], 'overtime');

		$data["employee"] = Tbl_payroll_employee_basic::selemployee($parameter)->where('tbl_payroll_employee_basic.payroll_employee_id',$this->employee_info->payroll_employee_id)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->first();

		$approver_info = array();
		foreach($data['_group_approver'] as $key => $approver)
		{
			$data["approver_info"] = Tbl_payroll_employee_basic::selemployee($parameter)->where('tbl_payroll_employee_basic.payroll_employee_id',$approver[0]->payroll_employee_id)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->first();
			$temp['payroll_employee_display_name'] = $data["approver_info"]["payroll_employee_display_name"];
			$temp['payroll_jobtitle_name'] = $data["approver_info"]["payroll_jobtitle_name"];
			array_push($approver_info,$temp);
		}

		$data["approver_info"]	= $approver_info;
		$format["format"] = "A4";
		$format["default_font"] = "sans-serif";
		$pdf = PDF2::loadView('member.payroll2.employee_dashboard.request_overtime_pdf',$data, [], $format);
		return $pdf->stream('document.pdf');
	}

	public function employee_request_overtime_cancel($request_id)
	{
		if (Request::method() == 'POST') 
		{
			$update['payroll_request_overtime_status'] = "canceled";
			Tbl_payroll_request_overtime::where('payroll_request_overtime_id',$request_id)->update($update);
			
			$response['status'] = 'success';
			$response['call_function'] = 'reload';

			return $response;
		}
		else
		{
			
			$data['id'] 	 = $request_id;
			$data['action']  = 'employee_request_overtime_cancel/'.$request_id;
			$data['message'] = 'Warning: you cannot restore canceled request, Do you really want to cancel this request?';
			$data['btn']	 = '<label><button type="submit" class="btn btn-custom-white">Confirm</label>';
			
			return view('member.payroll2.employee_dashboard.modal.modal_confirm',$data);
		}
		
	}

	public function authorized_access_over_time()
	{

		$data['page']  = 'Authorized Access Over Time';
		$data['employee_approver_info']	= Tbl_payroll_approver_employee::GetApproverInfoByType($this->employee_info->payroll_employee_id, 'overtime')->first();
		
		$level_approver 		= $data['employee_approver_info']['payroll_approver_employee_level'];
		$approver_employee_id 	= $data['employee_approver_info']['payroll_approver_employee_id'];

		$data['_request_pending']  = Tbl_payroll_request_overtime::GetAllRequest($approver_employee_id, $level_approver, 'pending')->paginate(15);
		$data['_request_approved'] = Tbl_payroll_request_overtime::GetAllRequest($approver_employee_id, $level_approver, 'approved')->paginate(15);
		$data['_request_rejected'] = Tbl_payroll_request_overtime::GetAllRequest($approver_employee_id, $level_approver, 'rejected')->paginate(15);
		$data['_request_canceled'] = Tbl_payroll_request_overtime::GetAllRequest($approver_employee_id, $level_approver, 'canceled')->paginate(15);
		
		return view('member.payroll2.employee_dashboard.authorized_access_over_time',$data);
	}

	public function view_overtime_request($request_id)
	{
		$data['request_info'] = Tbl_payroll_request_overtime::where('payroll_request_overtime_id',$request_id)->EmployeeInfo()->first();
		$data['page'] = 'View Overtime Request';
		$data['_group_approver'] = Self::get_group_approver_grouped_by_level(Self::employee_shop_id(),$data['request_info']['payroll_approver_group_id'],'overtime');
		$data['approver_group_info'] = tbl_payroll_approver_group::where('payroll_approver_group_id',$data['request_info']['payroll_approver_group_id'])->first();

		return view('member.payroll2.employee_dashboard.modal.modal_view_overtime_request',$data);
	}

	public function approve_overtime_request($request_id)
	{
		if (Request::method() == 'POST') 
		{
			$request_info = Tbl_payroll_request_overtime::where('payroll_request_overtime_id',$request_id)->EmployeeInfo()->first();
			
			$_approver_group = collect(Tbl_payroll_approver_group::where('tbl_payroll_approver_group.payroll_approver_group_id', $request_info['payroll_approver_group_id'])
										->EmployeeApproverInfo()->get())
										->groupBy('payroll_approver_group_level');
			
			$count_approvers = count($_approver_group);
			
			/*check if approve or go to next level of approval*/
			if($count_approvers == $request_info['payroll_request_overtime_status_level'])
			{
				$update['payroll_request_overtime_status'] = "approved";
				Tbl_payroll_request_overtime::where('payroll_request_overtime_id',$request_id)->update($update);
			}
			else
			{
				$update['payroll_request_overtime_status_level'] = $request_info['payroll_request_overtime_status_level'] + 1;
				Tbl_payroll_request_overtime::where('payroll_request_overtime_id',$request_id)->update($update);
			}
			$response['status'] = 'success';
			$response['call_function'] = 'reload';

			return $response;
		}
		else
		{
			$data['id'] 	 = $request_id;
			$data['action']  = '/authorized_access_over_time/approve_overtime_request/'.$request_id;
			$data['message'] = 'Do you really want to approve this request?';
			$data['btn']	 = '<label><button type="submit" class="btn btn-custom-white">Confirm</label>';
			
			return view('member.payroll2.employee_dashboard.modal.modal_confirm',$data);
		}
	}

	public function reject_overtime_request($request_id)
	{
		if (Request::method() == 'POST') 
		{
			$update['payroll_request_overtime_status'] = "rejected";
			Tbl_payroll_request_overtime::where('payroll_request_overtime_id',$request_id)->update($update);
			
			$response['status'] = 'success';
			$response['call_function'] = 'reload';

			return $response;
		}
		else
		{
			$data['id'] 	 = $request_id;
			$data['action']  = '/authorized_access_over_time/reject_overtime_request/'.$request_id;
			$data['message'] = 'Do you really want to reject this request?';
			$data['btn']	 = '<label><button type="submit" class="btn btn-custom-white">Confirm</label>';
			
			return view('member.payroll2.employee_dashboard.modal.modal_confirm',$data);
		}

	}
	/*End Overtime Management*/



	public function authorized_access_official_business()
	{
		$data['page']	= 'Authorized Access Official Business';
		return view('member.payroll2.employee_dashboard.authorized_access_official_business',$data);
	}
	
	public function authorized_access_approver()
	{
		$data['page']	= 'Authorized Access Approver';
		return view('member.payroll2.employee_dashboard.authorized_access_approver',$data);
	}

	public function employee_official_business_management()
	{
		$data['page']	= 'Official Business Management';
		return view('member.payroll2.employee_dashboard.employee_official_business_management',$data);
	}

	public function employee_time_keeping()
	{

		$data['page']				= 'Time Keeping';
		$data['period_record'] 		= Tbl_payroll_time_keeping_approved::employeePeriod($this->employee_info->payroll_employee_id)->get();

		$data['access_timekeeping'] = Self::employee_shop_id();

		// dd($data['period_record']);
		return view('member.payroll2.employee_dashboard.employee_time_keeping',$data);
	}

	public function employee_payslip_pdf($payroll_period_id)
    { 
    	$data['page']				= 'Employee Payslip';
    	$data["employee_company"] 	= Tbl_payroll_company::where("tbl_payroll_company.payroll_company_id", $this->employee_info->payroll_employee_company_id)->first();
    	$data['period_record'] 		= Tbl_payroll_time_keeping_approved::employeePeriod($this->employee_info->payroll_employee_id)->where('tbl_payroll_period.payroll_period_id',$payroll_period_id)->first();

    	$data["period_record_start"]		= date("F d, Y", strtotime($data["period_record"]->payroll_period_start));
		$data["period_record_end"]			= date("F d, Y", strtotime($data["period_record"]->payroll_period_end));
		$data["period_record_release_date"]	= date("F d, Y", strtotime($data["period_record"]->payroll_release_date));

		//dd(unserialize($data["period_record"]->cutoff_input));
		$data["period_record"]->cutoff_breakdown =  unserialize($data["period_record"]->cutoff_breakdown);
		

		$other_deductions = 0;

		foreach($data["period_record"]->cutoff_breakdown->_breakdown as $breakdown)	
		{
			if($breakdown["deduct.net_pay"] == true)
			{
				$other_deductions += $breakdown["amount"];
			}
		}

		$data["period_record"]->other_deduction = $other_deductions;
		$data["period_record"]->total_deduction = $data["period_record"]->philhealth_ee + $data["period_record"]->sss_ee + $data["period_record"]->pagibig_ee  + $other_deductions; // + $employee->tax_ee;
		
		//YEAR-END SUMMARY - PAYROLL PERIOD
		$period_year = date("Y", strtotime($data["period_record"]->payroll_period_end));
		$period_start = $period_year.'-01-01';
		$data['total_period_record'] = Tbl_payroll_time_keeping_approved::employeePeriod($this->employee_info->payroll_employee_id)
		->whereBetween('tbl_payroll_period.payroll_period_end',[$period_start, $data['period_record']->payroll_period_end])->get();

		$data['total_net_pay'] = 0 ;
		$data['total_tax_ee'] = 0 ;
		$data['total_sss_ee'] = 0 ;
		$data['total_philhealth_ee'] = 0 ;
		$data['total_pagibig_ee'] = 0 ;


		foreach($data['total_period_record'] as $total)	
		{
			$data['total_net_pay'] += $total->net_pay;
			$data['total_tax_ee'] += $total->tax_ee;
			$data['total_sss_ee'] += $total->sss_ee;
			$data['total_philhealth_ee'] += $total->philhealth_ee;
			$data['total_pagibig_ee'] += $total->pagibig_ee;
		}

		//return view('member.payroll2.employee_dashboard.employee_payslip', $data);
		$pdf = view('member.payroll2.employee_dashboard.employee_payslip_pdf', $data);
        return Pdf_global::show_pdf($pdf);
        
    }

	public function employee_timesheet($payroll_period_id)
	{
		$data["page"] 	= "Employee Timesheet";

		$data['period_record'] 			= Tbl_payroll_time_keeping_approved::employeePeriod($this->employee_info->payroll_employee_id)->where('tbl_payroll_period.payroll_period_id',$payroll_period_id)->first();

		$data["period_record_start"]		= date('M d, Y',strtotime($data["period_record"]->payroll_period_start));
		$data["period_record_end"]			= date('M d, Y',strtotime($data["period_record"]->payroll_period_end));

		$data["_timesheet"] 			= Payroll2::timesheet_info($data["period_record"], $this->employee_info->payroll_employee_id);
		$data["access_salary_rates"]	= $access = Utilities::checkAccess('payroll-timekeeping','salary_rates');

		$data["period_record"]->cutoff_breakdown =  unserialize($data["period_record"]->cutoff_breakdown);

		$employee_contract = Tbl_payroll_employee_contract::EmployeePayrollGroup($this->employee_info->payroll_employee_id)->first();

		if ($employee_contract->payroll_group_code == 'Flate Rate')
		{
			echo "<div style='padding: 100px; text-align: center;'>FLAT RATE COMPUTATION DOES'T HAVE TIMESHEET</div>";
		}
		else
		{
			return view('member.payroll2.employee_dashboard.employee_timesheet', $data);
		}
	}   

	public function employee_timesheet_pdf($payroll_period_id)
	{
		$data["page"] 	= "Employee Timesheet";

		$data['period_record'] 			= Tbl_payroll_time_keeping_approved::employeePeriod($this->employee_info->payroll_employee_id)->where('tbl_payroll_period.payroll_period_id',$payroll_period_id)->first();

		$data["period_record_start"]		= date('M d, Y',strtotime($data["period_record"]->payroll_period_start));
		$data["period_record_end"]			= date('M d, Y',strtotime($data["period_record"]->payroll_period_end));

		$data["_timesheet"] 			= Payroll2::timesheet_info($data["period_record"], $this->employee_info->payroll_employee_id);

		$data["access_salary_rates"]	= $access = Utilities::checkAccess('payroll-timekeeping','salary_rates');

		$data["period_record"]->cutoff_breakdown =  unserialize($data["period_record"]->cutoff_breakdown);

		$employee_contract = Tbl_payroll_employee_contract::EmployeePayrollGroup($this->employee_info->payroll_employee_id)->first();

		if ($employee_contract->payroll_group_code == 'Flate Rate')
		{
			echo "<div style='padding: 100px; text-align: center;'>FLAT RATE COMPUTATION DOES'T HAVE TIMESHEET</div>";
		}
		else
		{
			$pdf = view('member.payroll2.employee_dashboard.employee_timesheet_pdf', $data);
	        return Pdf_global::show_pdf($pdf);
	    }
	}   
	public function sample()
	{
		$data['page']	= 'Official Business Management';
		return view('member.payroll2.employee_dashboard.sample',$data);
	}

    public function create_employee_overtime()
    {
		$data['page']	= 'Create Employee Overtime';
    	return view('member.payroll2.employee_dashboard.create_employee_overtime',$data);
    }
    public function create_employee_official_business()
    {
		$data['page']	= 'Create Employee OB';
    	return view('member.payroll2.employee_dashboard.create_employee_official_business',$data);
    }
    public function create_employee_approver()
    {
		$data['page']	= 'Create Employee Approver';
    	return view('member.payroll2.employee_dashboard.create_employee_approver',$data);
    }
    public function updated_layout()
    {
		$data['page']		= 'Create Employee Approver';
		$data['employee'] 	= Self::employee_profile();
    	return view('member.payroll2.employee_dashboard.updated_layout',$data);
    }

    public static function get_group_approver_grouped_by_level($shop_id , $approver_group_id, $approver_group_type)
	{
		$_approver_group = collect(Tbl_payroll_approver_group::EmployeeApproverInfo($shop_id, $approver_group_id, $approver_group_type)->get())->groupBy('payroll_approver_group_level');

		return $_approver_group;
	}


	public function get_group_approver_list()
	{
		$approver_group_id = Request::get('approver_group_id');
		$approver_group_type = Request::get('approver_group_type');
		$_approver_group = EmployeeController::get_group_approver_grouped_by_level(Self::employee_shop_id(), $approver_group_id, $approver_group_type);

		return json_encode($_approver_group);
	}
}