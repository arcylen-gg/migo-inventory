<?php

namespace App\Http\Controllers\Member;
use Request;
use stdClass;
use Redirect;
use Excel;
use DB;


use App\Models\Tbl_payroll_deduction_v2;
use App\Models\Tbl_payroll_deduction_employee_v2;
use App\Models\Tbl_payroll_deduction_payment_v2;
use App\Models\Tbl_payroll_company;
use App\Models\Tbl_payroll_period;
use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_employment_status;
use App\Models\Tbl_payroll_employee_salary;
use App\Models\Tbl_payroll_department;
use App\Models\Tbl_payroll_approver_employee;
use App\Models\Tbl_payroll_approver_group;
use App\Models\Tbl_payroll_approver_group_employee;
use App\Models\Tbl_payroll_employee_basic;

class PayrollAdminDashboard extends Member
{

	public function shop_id($return = 'shop_id')
	{
	     switch ($return) 
	     {
	          case 'shop_id':
	               return $shop_id = $this->user_info->user_shop;
	               break;

	          case 'user_id':
	               return $shop_id = $this->user_info->user_id;
	               break;
	          
	          default:
	               # code...
	               break;
	     }
	}

	public function employee_approver()
	{
		$data['overtime_approver'] 	= Tbl_payroll_approver_employee::where('tbl_payroll_approver_employee.shop_id', $this->user_info->shop_id)->where('payroll_approver_employee_type','overtime')->where('tbl_payroll_approver_employee.archived', 0)->EmployeeInfo()->get();
		$data['rfp_approver'] 		= Tbl_payroll_approver_employee::where('tbl_payroll_approver_employee.shop_id', $this->user_info->shop_id)->where('payroll_approver_employee_type','rfp')->where('tbl_payroll_approver_employee.archived', 0)->EmployeeInfo()->get();
		$data['leave_approver'] 	= Tbl_payroll_approver_employee::where('tbl_payroll_approver_employee.shop_id', $this->user_info->shop_id)->where('payroll_approver_employee_type','leave')->where('tbl_payroll_approver_employee.archived', 0)->EmployeeInfo()->get();
		
		return view('member.payroll2.employees_approver', $data);
	}

	public function modal_create_approver()
	{
		return view('member.payroll2.modal_create_approver');
	}

	public function create_approver()
	{
		$data['_company']        = Tbl_payroll_company::selcompany($this->user_info->shop_id)->orderBy('tbl_payroll_company.payroll_company_name')->get();

		$data['_department']     = Tbl_payroll_department::sel($this->user_info->shop_id)->orderBy('payroll_department_name')->get();

		return view('member.payroll2.create_approver', $data);
	}

	public function create_approver_table()
	{
		$company  				= Request::input('company');
		$department 			= Request::input('department');
		$jobtitle      			= Request::input('jobtitle');

		$data['_employee'] 		= Tbl_payroll_employee_contract::employeefilter($company, $department, $jobtitle, date('Y-m-d'), $this->user_info->shop_id)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->groupBy('tbl_payroll_employee_basic.payroll_employee_id')->get();

		return view('member.payroll2.create_approver_table', $data);
	}

	public function save_approver()
	{
		// die(var_dump(Request::all()));

		$_approver_type 	= Request::input('approver_type');
		$_selected_employee = Request::input('selected_employee');
		$approver_level 	= Request::input('approver_level');
		$insert = array();

		foreach ($_approver_type as $key => $approver_type) 
		{
			foreach ($_selected_employee as $key => $selected_employee) 
			{
				$approver_employee = Tbl_payroll_approver_employee::where('payroll_employee_id',$selected_employee)->where('payroll_approver_employee_type',$approver_type)->first();
				
				//if not exist
				if (!$approver_employee) 
				{
					$temp = null;
					$temp['payroll_employee_id'] = $selected_employee;
					$temp['shop_id'] = $this->user_info->shop_id;
					$temp['payroll_approver_employee_type'] = $approver_type;
					$temp['payroll_approver_employee_level'] = $approver_level;
					$temp['archived'] = 0;
					array_push($insert, $temp);
				}
			}
		}

		Tbl_payroll_approver_employee::insert($insert);

		$response["response_status"] 	= "success";
		$response['call_function'] 		= 'success_saving_import';

		return $response;
	}

	public function modal_edit_approver($approver_id)
	{
		$data['approver_type'] = Request::input('approver_type');
		$data['approver_info'] = Tbl_payroll_approver_employee::where('payroll_approver_employee_id',$approver_id)->EmployeeInfo()->first();
		$data['approver_id']   = $approver_id;
		
		return view('member.modal.modal_edit_approver', $data);
	}

	public function save_edit_approver()
	{
		$update['payroll_approver_employee_level'] = Request::input('approver_level');
		Tbl_payroll_approver_employee::where('payroll_approver_employee_id',Request::input('approver_id'))->update($update);
		$response['response_status']  	= 'success';
		$response['call_function']		= 'reload_page';
		$response['function_name'] 		= 'payroll_employee_approver.reload';
		
		return json_encode($response);
 	}

	public function modal_delete_approver($approver_id)
	{
		if (Request::isMethod('post')) 
		{
			$response['response_status']  	= 'success';
			$response['call_function']		= 'submit_done';
			$response['function_name'] 		= 'payroll_employee_approver.reload';
			$update['archived']				= 1;
			Tbl_payroll_approver_employee::where('payroll_approver_employee_id',$approver_id)->update($update);

			return json_encode($response);
		}
		else
		{
			$data['approver_type'] 	= Request::input('approver_type');
			$data['approver_info'] 	= Tbl_payroll_approver_employee::where('payroll_approver_employee_id',$approver_id)->EmployeeInfo()->first();
			$data['action'] 		= '/member/payroll/payroll_admin_dashboard/delete_approver/'.$approver_id;
			$data['id']   			= $approver_id;
			$data['message'] 		= 'do you really want to delete approver employee approver '. $data['approver_info']['payroll_employee_display_name'] . '?';
			$data['btn'] 			= ' <button type="submit" class="btn btn-custom-white">confirm</button>';

			return view('member.modal.confirm', $data);
		}
	}

	public function create_approver_tag_employee()
	{
	     $data['_company']        = Tbl_payroll_company::selcompany($this->user_info->shop_id)->orderBy('tbl_payroll_company.payroll_company_name')->get();
	     $data['_department']     = Tbl_payroll_department::sel($this->user_info->shop_id)->orderBy('payroll_department_name')->get();
	     $data['action']          =  '/member/payroll/payroll_admin_dashboard/set_employee_approver_tag';

	     $company  		= Request::input('company');
	     $department 	= Request::input('department');
	     $jobtitle      = Request::input('jobtitle');
	     $emp 			= Tbl_payroll_employee_contract::employeefilter($company, $department, $jobtitle, date('Y-m-d'), $this->user_info->shop_id)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->groupBy('tbl_payroll_employee_basic.payroll_employee_id')->get();

	     return view('member.payroll.modal.modal_approver_tag_employee', $data);
	}

	public function modal_approver_tag_employee()
	{
	     $data['_company']        = Tbl_payroll_company::selcompany($this->user_info->shop_id)->orderBy('tbl_payroll_company.payroll_company_name')->get();

	     $data['_department']     = Tbl_payroll_department::sel($this->user_info->shop_id)->orderBy('payroll_department_name')->get();

	     $data['action']          =  '/member/payroll/payroll_admin_dashboard/set_employee_approver_tag';

	     return view('member.payroll.modal.modal_approver_tag_employee', $data);
	}

	public function ajax_deduction_tag_employee()
	{
	     $company  		= Request::input('company');
	     $department 	= Request::input('department');
	     $jobtitle      = Request::input('jobtitle');
	     $emp 			= Tbl_payroll_employee_contract::employeefilter($company, $department, $jobtitle, date('Y-m-d'), $this->user_info->shop_id)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->groupBy('tbl_payroll_employee_basic.payroll_employee_id')->get();
	     
	     return json_encode($emp);
	}

	public function set_employee_approver_tag()
	{
	
		return json_encode('');
	}

	/*start group approver*/
	public function group_approver()
	{
		$data['_group_approver'] = Tbl_payroll_approver_group::where('tbl_payroll_approver_group.shop_id', $this->user_info->shop_id)->where('archived', 0)->get();
		$data['_archived_group_approver'] = Tbl_payroll_approver_group::where('tbl_payroll_approver_group.shop_id', $this->user_info->shop_id)->where('archived', 1)->get();
		
		return view('member.payroll2.group_approver',$data);
	}

	public function modal_create_group_approver()
	{
		return view('member.payroll2.modal_create_group_approver');
	}

	public function get_employee_approver_by_level()
	{
		$data['level'] = Request::input('level');
		$data['type'] = Request::input('type');
		$data['approver_by_level'] = array();
		$data['_approver_group_by_level'] = null;
		
		for ($i = 1; $i <= $data['level']; $i++) 
		{ 
			$_approver = Tbl_payroll_approver_employee::GetApproverByLevelAndType($this->user_info->shop_id, $i, $data['type'])->EmployeeInfo()->get();
			/*Check if level has approver*/
			if (count($_approver) > 0)
			{
				$data['approver_by_level'][$i] = array();
				foreach ($_approver as $key => $approver) 
				{
					array_push($data['approver_by_level'][$i], $approver);
				}
			}
		}
		/*for group edit modal*/
		if (Request::has('approver_group_id')) 
		{
			$_approver_group = collect(Tbl_payroll_approver_group::where('tbl_payroll_approver_group.shop_id',Self::shop_id())
											->where('tbl_payroll_approver_group.payroll_approver_group_id',Request::get('approver_group_id'))
											->join('tbl_payroll_approver_group_employee','tbl_payroll_approver_group_employee.payroll_approver_group_id','=','tbl_payroll_approver_group.payroll_approver_group_id')->get())
											->groupBy('payroll_approver_group_level');

			$_approver_group_by_level = array();

			foreach ($_approver_group as $key => $approver_group) 
			{
				$_approver_group_by_level[$key] = array();
				foreach($approver_group as $key2 => $approver_by_level)
				{
					array_push($_approver_group_by_level[$key], $approver_by_level['payroll_approver_employee_id']);
				}
			}

			$data['_approver_group_by_level'] = $_approver_group_by_level;
		}
		
		// die(var_dump($data['_approver_group_by_level'][1]));
		return view('member.payroll2.get_employee_approver_by_level', $data);
	}


	public function save_approver_group()
	{
		$approver_group_name  = Request::input('approver_group_name');
		$approver_type 		  = Request::input('approver_type');
		$approver_level_count = Request::input('approver_level_count');

		$_approver_by_level   = Request::input('approver_level');
		$approver_group_level = Request::input('approver_level_count');

		$insert_group['shop_id'] = Self::shop_id();
		$insert_group['payroll_approver_group_name']  = $approver_group_name;
		$insert_group['payroll_approver_group_type']  = $approver_type;
		$insert_group['payroll_approver_group_level'] = $approver_group_level;
		$insert_group['archived'] = 0;

		$payroll_approver_group_id = Tbl_payroll_approver_group::insertGetId($insert_group);

		for ($level = 1; $level <= $approver_level_count; $level++) 
		{ 
			foreach ($_approver_by_level[$level] as $key => $approver) 
			{
				$insert_group_employee['payroll_approver_group_id']    =  $payroll_approver_group_id;
				$insert_group_employee['payroll_approver_employee_id'] = $approver;
				$insert_group_employee['payroll_approver_group_level'] = $level;

				Tbl_payroll_approver_group_employee::insert($insert_group_employee);

				$insert_group_employee = null;
			}
		}

		$response['success'] 		= 'success';
		$response['call_function'] 	= 'reload_page';

		return $response;
	}


	public function modal_edit_group_approver($approver_group_id)
	{
		$data['approver_group_info'] = Tbl_payroll_approver_group::where('tbl_payroll_approver_group.shop_id',Self::shop_id())
										->where('tbl_payroll_approver_group.payroll_approver_group_id',$approver_group_id)->first();
		
		$data['_approver_group'] = collect(Tbl_payroll_approver_group::where('tbl_payroll_approver_group.shop_id',Self::shop_id())
										->where('tbl_payroll_approver_group.payroll_approver_group_id',$approver_group_id)->EmployeeApproverInfo()->get())->groupBy('payroll_approver_group_level');

		return view('member.payroll2.modal_edit_group_approver', $data);
	}

	public function save_edit_group_approver()
	{
		$approver_group_id = Request::input('approver_group_id');

		$update_group['payroll_approver_group_name'] = Request::input('approver_group_name');
		$update_group['payroll_approver_group_type'] = Request::input('approver_type');
		$update_group['payroll_approver_group_level'] = Request::input('approver_level_count');

		Tbl_payroll_approver_group::where('payroll_approver_group_id', $approver_group_id)->update($update_group);

		$approver_group_by_level = Request::input('approver_level'); 

		Tbl_payroll_approver_group_employee::where('payroll_approver_group_id', $approver_group_id)->delete();

		foreach ($approver_group_by_level as $level => $group_approver) 
		{
			foreach ($group_approver as $key => $employee_approver) 
			{
				$insert_group_employee = null;
				$insert_group_employee['payroll_approver_group_id']		= $approver_group_id;
				$insert_group_employee['payroll_approver_employee_id']	= $employee_approver;
				$insert_group_employee['payroll_approver_group_level']	= $level;

				Tbl_payroll_approver_group_employee::insert($insert_group_employee);
			}
		}

		$response['response_status'] = 'success';
		$response['call_function'] = 'reload_group_approver';
		
		return $response;
	}


	public function modal_archive_group_approver($approver_group_id)
	{
		
		
		if (Request::isMethod('post')) 
		{

			$response['response_status']  	= 'success';
			$response['call_function']		= 'reload_group_approver';
			$archive 						= Request::input('data_id');

			$update['archived'] = $archive;

			Tbl_payroll_approver_group::where('payroll_approver_group_id', $approver_group_id)->update($update);
			
			// die(var_dump(Request::all(),$approver_group_id,$archive));
			return json_encode($response);
		}
		else
		{
			$group_name = str_replace('_',' ',Request::input('group_name'));
			$archive = Request::input('archive');

			$data['approver_type'] 	= Request::input('approver_type');
			$data['action'] 		= '/member/payroll/payroll_admin_dashboard/modal_archive_group_approver/'.$approver_group_id;
			$data['id']   			= $archive;
			$data['message'] 		= 'Do you really want to '. ($archive == 1 ? 'archive ':'restore ') . $group_name . '?';
			$data['btn'] 			= ' <button type="submit" class="btn btn-custom-white">confirm</button>';
			
			return view('member.modal.confirm', $data);
		}
	}

	//access

	public function access_level()
	{

		$data['employee_info']	= Tbl_payroll_employee_basic::select('payroll_employee_email','payroll_employee_tin')->where('shop_id',Self::shop_id())->get();
			
		return view('member.payroll2.access_level',$data);
	}

	public function add_access_group()
	{

		$data['_company']        = Tbl_payroll_company::selcompany($this->user_info->shop_id)->orderBy('tbl_payroll_company.payroll_company_name')->get();

		$data['_department']     = Tbl_payroll_department::sel($this->user_info->shop_id)->orderBy('payroll_department_name')->get();

		return view('member.payroll2.create_access_group',$data);
	}

	public function save_access_group()
	{
		return view('member.payroll2.create_access_group');
	}

}
