<?php
namespace App\Http\Controllers\Member\PayrollEmployee;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Member\PayrollEmployee\EmployeeController;
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


class RequestForPaymentController extends PayrollMember
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

	public function request_for_payment()
	{
		$data['page'] = 'Request For Payment';

		return view('member.payroll2.employee_dashboard.request_for_payment',$data);
	}
	
	public function request_for_payment_table()
	{
		$status = Request::input('status');
		$data['_payment_request'] = Tbl_payroll_request_payment::where('payroll_employee_id', $this->employee_info->payroll_employee_id)->where('payroll_request_payment_status', $status)->get();
		

		return view('member.payroll2.employee_dashboard.request_for_payment_table',$data);
	}

	public function modal_rfp_application()
	{
		$data['_group_approver'] = Tbl_payroll_approver_group::where('tbl_payroll_approver_group.shop_id', Self::employee_shop_id())->where('payroll_approver_group_type','rfp')->where('archived', 0)->get();
		
		return view('member.payroll2.employee_dashboard.modal.modal_rfp_application',$data);
	}

	public function modal_rfp_save()
	{
		
		$insert['payroll_employee_id'] 					= Self::employee_id();
		$insert['payroll_approver_group_id'] 			= Request::input('approver_group');
		$insert['payroll_request_payment_name'] 		= Request::input('request_payment_name');
		$insert['payroll_request_payment_date'] 		= Request::input('request_payment_date');
		$insert['payroll_request_payment_remark'] 		= Request::input('request_payment_remark');
		$insert['payroll_request_payment_total_amount'] = Request::input('request_payment_total_amount');
		$insert['payroll_request_payment_status'] 		= 'pending';
		$insert['payroll_request_payment_status_level'] = 1;
		$insert['archived'] = 0;
		
		$request_payment_id = Tbl_payroll_request_payment::insertGetId($insert);

		$_request_payment_description = Request::input('request_payment_description');
		$_request_payment_amount 	  = Request::input('request_payment_amount');

		foreach ($_request_payment_description as $key => $request_payment_description) 
		{
			$insert_sub = null;
			$insert_sub['payroll_request_payment_id'] 				= $request_payment_id;
			$insert_sub['payroll_request_payment_sub_description'] 	= $request_payment_description;
			$insert_sub['payroll_request_payment_sub_amount'] 		= $_request_payment_amount[$key];
			Tbl_payroll_request_payment_sub::insert($insert_sub);
		}

		$response['call_function'] = 'reload';
		$response['status'] = 'success';

		return $response;
	}

	public function rfp_application_view($request_id)
	{
		$data['request_payment_info'] 		= Tbl_payroll_request_payment::where('payroll_request_payment_id',$request_id)->first();
		$data['_request_payment_sub_info'] 	= Tbl_payroll_request_payment_sub::where('payroll_request_payment_id',$request_id)->get();
		$data['_group_approver'] 			= EmployeeController::get_group_approver_grouped_by_level(Self::employee_shop_id() ,$data['request_payment_info']['payroll_approver_group_id'],'rfp');
		$data['approver_group_info'] 		= tbl_payroll_approver_group::where('payroll_approver_group_id',$data['request_payment_info']['payroll_approver_group_id'])->first();
		
		return view('member.payroll2.employee_dashboard.modal.rfp_application_view', $data);
	}

	public function rfp_application_cancel($request_id)
	{
		if (Request::method() == 'POST') 
		{
			$update['payroll_request_payment_status'] = "canceled";
			Tbl_payroll_request_payment::where('payroll_request_payment_id',$request_id)->update($update);
			
			$response['status'] 		= 'success';
			$response['call_function'] 	= 'reload';

			return $response;
		}
		else
		{
			$data['id'] 	 = $request_id;
			$data['action']  = 'rfp_application_cancel/'.$request_id;
			$data['message'] = 'Warning: you cannot restore canceled request, Do you really want to cancel this request?';
			$data['btn']	 = '<label><button type="submit" class="btn btn-custom-white">Confirm</label>';
			
			return view('member.payroll2.employee_dashboard.modal.modal_confirm',$data);
		}
	}


	public function authorized_access_request_for_refund()
	{
		return view('member.payroll2.employee_dashboard.authorized_access_request_for_refund');
	}

	public function authorized_access_request_for_refund_table()
	{
		$status = Request::input('status');
		$data['employee_approver_info']	= Tbl_payroll_approver_employee::GetApproverInfoByType($this->employee_info->payroll_employee_id, 'rfp')->first();
		
		$level_approver 		= $data['employee_approver_info']['payroll_approver_employee_level'];
		$approver_employee_id 	= $data['employee_approver_info']['payroll_approver_employee_id'];
		
		$data['_payment_request']  = Tbl_payroll_request_payment::GetAllRequest($approver_employee_id, $level_approver, $status)->get();

		return view('member.payroll2.employee_dashboard.authorized_access_request_for_refund_table', $data);
	}

	public function rfp_application_approve($request_id)
	{
		if (Request::method() == 'POST') 
		{
			$request_info = Tbl_payroll_request_payment::where('payroll_request_payment_id',$request_id)->EmployeeInfo()->first();
			
			$_approver_group = collect(Tbl_payroll_approver_group::where('tbl_payroll_approver_group.payroll_approver_group_id', $request_info['payroll_approver_group_id'])
										->EmployeeApproverInfo()->get())
										->groupBy('payroll_approver_group_level');
			
			$count_approvers = count($_approver_group);
			
			/*check if approve or go to next level of approval*/
			if($count_approvers == $request_info['payroll_request_payment_status_level'])
			{
				$update['payroll_request_payment_status'] = "approved";
				Tbl_payroll_request_payment::where('payroll_request_payment_id',$request_id)->update($update);
			}
			else
			{
				$update['payroll_request_payment_status_level'] = $request_info['payroll_request_payment_status_level'] + 1;
				Tbl_payroll_request_payment::where('payroll_request_payment_id',$request_id)->update($update);
			}

			$response['status'] = 'success';
			$response['call_function'] = 'reload';

			return $response;
		}
		else
		{
			$data['id'] 	 = $request_id;
			$data['action']  = "rfp_application_approve/".$request_id;
			$data['message'] = 'Do you really want to approve this request?';
			$data['btn']	 = '<label><button type="submit" class="btn btn-custom-white">Confirm</label>';
			
			return view('member.payroll2.employee_dashboard.modal.modal_confirm',$data);
		}
	}

	public function rfp_application_reject($request_id)
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
			$data['action']  = 'rfp_application_reject/'.$request_id;
			$data['message'] = 'Do you really want to reject this request?';
			$data['btn']	 = '<label><button type="submit" class="btn btn-custom-white">Confirm</label>';
			
			return view('member.payroll2.employee_dashboard.modal.modal_confirm',$data);
		}
	}

	public function employee_request_rfp_export_pdf($request_id)
	{

		$parameter['date']					= date('Y-m-d');
		$parameter['company_id']			= 0;
		$parameter['employement_status']	= 0;
		$parameter['shop_id'] 				= $this->employee_info->shop_id;

		$data['request_payment_info'] 		= Tbl_payroll_request_payment::where('payroll_request_payment_id',$request_id)->first();
		$data['_request_payment_sub_info'] 	= Tbl_payroll_request_payment_sub::where('payroll_request_payment_id',$request_id)->orderby('payroll_request_payment_sub_id')->get();
		$data['_group_approver'] 			= EmployeeController::get_group_approver_grouped_by_level(Self::employee_shop_id() ,$data['request_payment_info']['payroll_approver_group_id'],'rfp');
		$data['approver_group_info'] 		= tbl_payroll_approver_group::where('payroll_approver_group_id',$data['request_payment_info']['payroll_approver_group_id'])->first();

		$data['total_in_words'] = Payroll2::number_to_word($data['request_payment_info']["payroll_request_payment_total_amount" ]);


		$approver_info = array();
		foreach($data['_group_approver'] as $key => $approver)
		{
			$data["approver_info"] = Tbl_payroll_employee_basic::selemployee($parameter)->where('tbl_payroll_employee_basic.payroll_employee_id',$approver[0]->payroll_employee_id)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->first();
			$temp['payroll_employee_display_name'] = $data["approver_info"]["payroll_employee_display_name"];
			$temp['payroll_jobtitle_name'] = $data["approver_info"]["payroll_jobtitle_name"];
			array_push($approver_info,$temp);
		}

		$data["approver_info"]	= $approver_info;

		$data["employee"] = Tbl_payroll_employee_basic::selemployee($parameter)->where('tbl_payroll_employee_basic.payroll_employee_id',$this->employee_info->payroll_employee_id)->orderby("tbl_payroll_employee_basic.payroll_employee_number")->first();

			$format["format"] = "A4";
			$format["default_font"] = "sans-serif";	
			$pdf = PDF2::loadView('member.payroll2.employee_dashboard.request_for_payment_pdf', $data, [], $format);
			return $pdf->stream('document.pdf');
	}

}