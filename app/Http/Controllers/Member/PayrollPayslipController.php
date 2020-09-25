<?php

namespace App\Http\Controllers\Member;
use Request;
use stdClass;
use Redirect;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_time_keeping_approved_breakdown;
use App\Models\Tbl_payroll_time_keeping_approved_performance;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_payslip;
use App\Models\Tbl_payroll_record;
use App\Models\Tbl_payroll_payslip_option;
use PDF2;
use App\Globals\Pdf_global;


class PayrollPayslipController extends Member
{
     public function shop_id()
     {
     	return $this->user_info->shop_id;
     }
     public function index($period_company_id)
     { 
		$data["company"] = Tbl_payroll_period_company::where("payroll_period_company_id", $period_company_id)->company()->companyperiod()->first();
		$data["_employee"] = Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_company_id)->basic()->get();
		$data["show_period_start"]	= date("F d, Y", strtotime($data["company"]->payroll_period_start));
		$data["show_period_end"]	= date("F d, Y", strtotime($data["company"]->payroll_period_end));
		
		if (($data["company"]->payroll_release_date) != "0000-00-00") 
		{
			$data["show_release_date"]    = date("F d, Y", strtotime($data["company"]->payroll_release_date));
		}
		else
		{
			$data["show_release_date"] = "not specified";
		}
		foreach($data["_employee"] as $key => $employee)
		{
			if($data["_employee"][$key]->cutoff_input == "")
			{
				$employee_id = $employee->payroll_employee_id;
				$period_id = $period_company_id;
				app('App\Http\Controllers\Member\PayrollTimeSheet2Controller')->approve_timesheets($period_id, $employee_id);
				app('App\Http\Controllers\Member\PayrollTimeSheet2Controller')->approve_timesheets($period_id, $employee_id);
			}
		}

		$data["_employee"] = Tbl_payroll_time_keeping_approved::where("payroll_period_company_id", $period_company_id)->basic()->get();

		foreach($data["_employee"] as $key => $employee)
		{
			$data["_employee"][$key] = $employee;
			$data["_employee"][$key]->cutoff_compute = unserialize($employee->cutoff_compute);
			$data["_employee"][$key]->cutoff_input =  unserialize($employee->cutoff_input);
			$data["_employee"][$key]->cutoff_breakdown =  unserialize($employee->cutoff_breakdown);

			$other_deductions = 0;

			foreach($data["_employee"][$key]->cutoff_breakdown->_breakdown as $breakdown)
			{
				if($breakdown["deduct.net_pay"] == true)
				{
					$other_deductions += $breakdown["amount"];
				}
			}

			$data["_employee"][$key]->other_deduction = $other_deductions;
			$data["_employee"][$key]->total_deduction = $employee->philhealth_ee + $employee->sss_ee + $employee->pagibig_ee  + $other_deductions; // + $employee->tax_ee;
		}

		// $pdf = PDF2::loadView('member.payroll.payroll_payslipv1', $data);
		// return $pdf->stream('document.pdf');
		
		// return view('member.payroll.payroll_payslipv1', $data);
		// $data['new_employee'] = loop_content_divide($data["_employee"]->toArray(), 2);
		
		// $data["new_employee"] = array_chunk($data["_employee"]->toArray(), ceil(count($data["_employee"]->toArray()) / 2));
		// foreach ($data['new_employee'] as $key => $value) 
		// {
		// 	foreach ($value as $keys => $values) 
		// 	{
		// 		$data['new_employee'][$key][$keys] = (object)$values;
		// 	}

		if (Self::shop_id() == 38)
		{
			$pdf = view('member.payroll.payroll_ntc_payslip', $data);
        	return Pdf_global::show_pdf($pdf, 'landscape');
		}
		else
		{

			$data['option'] = Tbl_payroll_payslip_option::select('*')->where('shop_id',Self::shop_id())->get();
	          if(count($data['option']) == 0)
	          {
	                    $insert['per_page']       = 0;           
	                    $insert['shop_id']        = Self::shop_id();
	                    Tbl_payroll_payslip_option::insert($insert);
	          }
             $data['option'] = Tbl_payroll_payslip_option::select('*')->where('shop_id',Self::shop_id())->get();

             if($data['option'][0]['per_page'] == 1)
             {
				$format["title"] = "A4";
				$format["format"] = "A4";
				$format["default_font"] = "sans-serif";

				$pdf = PDF2::loadView('member.payroll.payroll_payslipv1', $data, [], $format);
				return $pdf->stream('document.pdf');
             }
             else
             {
					$pdf = view('member.payroll.payroll_payslipv1', $data);
			        return Pdf_global::show_pdf($pdf);
             }
    	}
    }
}