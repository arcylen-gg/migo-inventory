<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Request;
use Redirect;
use Session;
use Excel;
use DB;
use Response;
use PDF;
use stdClass;

use App\Models\Tbl_payroll_company;
use App\Models\Tbl_payroll_rdo;
use App\Models\Tbl_payroll_department;
use App\Models\Tbl_payroll_jobtitle;
use App\Models\Tbl_payroll_employment_status;
use App\Models\Tbl_payroll_civil_status;
use App\Models\Tbl_country;
use App\Models\Tbl_payroll_requirements;
use App\Models\Tbl_payroll_employee_basic;
use App\Models\Tbl_payroll_employee_contract;
use App\Models\Tbl_payroll_employee_salary;
use App\Models\Tbl_payroll_employee_requirements;
use App\Models\Tbl_payroll_tax_status;
use App\Models\Tbl_payroll_tax_reference;
use App\Models\Tbl_payroll_tax_period;
use App\Models\Tbl_payroll_tax_default;
use App\Models\Tbl_payroll_sss_default;
use App\Models\Tbl_payroll_sss;
use App\Models\Tbl_payroll_philhealth_default;
use App\Models\Tbl_payroll_philhealth;
use App\Models\Tbl_payroll_pagibig_default;
use App\Models\Tbl_payroll_pagibig;
use App\Models\Tbl_payroll_deduction_type;
use App\Models\Tbl_payroll_deduction;
use App\Models\Tbl_payroll_deduction_employee;
use App\Models\Tbl_payroll_deduction_payment;
use App\Models\Tbl_payroll_allowance;
use App\Models\Tbl_payroll_employee_allowance;
use App\Models\Tbl_payroll_leave_temp;
use App\Models\Tbl_payroll_leave_employee;
use App\Models\Tbl_payroll_holiday;
use App\Models\Tbl_payroll_holiday_default;
use App\Models\Tbl_payroll_holiday_company;
use App\Models\Tbl_payroll_overtime_rate;
use App\Models\Tbl_payroll_over_time_rate_default;
use App\Models\Tbl_payroll_group;
use App\Models\Tbl_payroll_group_rest_day;
use App\Models\Tbl_payroll_time_sheet_record;
use App\Models\Tbl_payroll_record;
use App\Models\Tbl_payroll_period_company;
use App\Models\Tbl_payroll_period;
use App\Models\Tbl_payroll_bank_convertion;
use App\Models\Tbl_payroll_employee_dependent;
use App\Models\Tbl_payroll_employee_search;
use App\Models\Tbl_payroll_adjustment;
use App\Models\Tbl_payroll_allowance_record;
use App\Models\Tbl_chart_of_account;
use App\Models\Tbl_payroll_entity;
use App\Models\Tbl_payroll_journal_tag;
use App\Models\Tbl_payroll_journal_tag_entity;
use App\Models\Tbl_payroll_journal_tag_employee;
use App\Models\Tbl_payroll_leave_schedule;
use App\Models\Tbl_payroll_paper_sizes;
use App\Models\Tbl_payroll_payslip;
use App\Models\Tbl_payroll_reports;
use App\Models\Tbl_payroll_reports_column;
use App\Models\Tbl_payroll_13_month_compute;
use App\Models\Tbl_payroll_13_month_virtual;
use App\Models\Tbl_payroll_process_leave;
use App\Models\Tbl_payroll_shift;
use App\Models\Tbl_payroll_shift_template;
use App\Models\Tbl_payroll_shift_code;
use App\Models\Tbl_payroll_employee_shift;
use App\Models\Tbl_payroll_employee_schedule;
use App\Models\Tbl_payroll_time_sheet;
use App\Models\Tbl_payroll_branch_location;
use App\Models\Tbl_payroll_holiday_employee;

use App\Globals\Payroll;
use App\Globals\PayrollJournalEntries;
use App\Globals\Utilities;
use DateTime;
use App\Models\Tbl_payroll_shift_day;
use App\Models\Tbl_payroll_shift_time;
use App\Globals\AuditTrail;
use App\Globals\Accounting;

use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_time_keeping_approved_breakdown;
use App\Models\Tbl_payroll_time_keeping_approved_performance;



class PayrollHolidayController extends Member
{
     /*Set data per page for pagination*/
     protected $paginate_count = 10;

     public function shop_id($return = 'shop_id')
     {
          switch ($return) {
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

     public function holiday()
     {

        $data['_active'] = Tbl_payroll_holiday::getholiday(Self::shop_id())->orderBy('payroll_holiday_date','desc')->paginate($this->paginate_count);
        $data['_archived'] = Tbl_payroll_holiday::getholiday(Self::shop_id(), 1)->orderBy('payroll_holiday_date','desc')->paginate($this->paginate_count);

        $data['title']      = 'Holiday V2';
        $data['create']     = '/member/payroll/holiday/modal_create_holiday/v2';
        $data['edit']       = '/member/payroll/holiday/modal_edit_holiday/v2/';
        $data['archived']   = '/member/payroll/holiday/archive_holiday/v2/';

        Session::forget('employee_tag');

        return view('member.payroll.payroll_holiday.holiday',$data);
     }

     public function modal_create_holiday()
     {
          Session::forget('employee_tag');
          $data['_company'] = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('payroll_company_name')->get();
          return view('member.payroll.payroll_holiday.modal_create_holiday', $data);
     }

     public function modal_save_holiday()
     {
          $insert['shop_id']                 = Self::shop_id();
          $insert['payroll_holiday_name']    = Request::input('payroll_holiday_name');
          $insert['payroll_holiday_date']    = date('Y-m-d',strtotime(Request::input('payroll_holiday_date')));
          $insert['payroll_holiday_category'] = Request::input('payroll_holiday_category');

          $holiday_id = Tbl_payroll_holiday::insertGetId($insert);

          $_company                                    = Request::input('company');

          $insert_company = array();

          foreach($_company as $company)
          {

               $temp['payroll_company_id'] = $company;
               $temp['payroll_holiday_id'] = $holiday_id;
               array_push($insert_company, $temp);

               $get_all_employee = Session::get('employee_tag');
               if(count($get_all_employee) > 0)
               {
                 foreach ($get_all_employee as $key => $value)
                 {
                    if($company == $key)
                    {
                        $all_id = $value;
                        foreach ($all_id as $keyid => $valueid)
                        {
                            $ins_employee['payroll_company_id'] = $company;
                            $ins_employee['payroll_employee_id'] = $valueid;
                            $ins_employee['holiday_company_id'] = $holiday_id;

                            if(!empty($ins_employee))
                            {
                               Tbl_payroll_holiday_employee::insert($ins_employee);
                            }
                        }
                    }
                 }
               }  
              else
              {
                $employee_tag = Tbl_payroll_employee_basic::where('payroll_employee_company_id',$company)->get();
                foreach ($employee_tag as $key_employee => $value_employee) 
                {
                      $ins_employee['payroll_company_id'] = $company;
                      $ins_employee['payroll_employee_id'] = $value_employee->payroll_employee_id;
                      $ins_employee['holiday_company_id'] = $holiday_id;

                      if(!empty($ins_employee))
                      {
                         Tbl_payroll_holiday_employee::insert($ins_employee);
                      }
                }
              }
          }


          if(!empty($insert_company))
          {
               Tbl_payroll_holiday_company::insert($insert_company);
          }

          $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_holiday_v2';
          Session::forget('employee_tag');
          
          return json_encode($return);
     }
     public function tag_employee($company_id = 0)
     {
        $parameter['date']                  = date('Y-m-d');
        $parameter['company_id']            = $company_id;
        $parameter['employement_status']    = 0;
        $parameter['shop_id']               = Self::shop_id();

        $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->where('payroll_company_id',$company_id)->first();

        $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

        $data['_employee'] = Tbl_payroll_employee_basic::selemployee($parameter)->get();
        $data['action']          = '/member/payroll/holiday/tag_employee/submit';

        $get_all = Session::get('employee_tag');
        // dd($get_all);
        foreach ($data['_employee'] as $key => $value) 
        {
            $data['_employee'][$key]->checked = '';
            if(count($get_all) > 0 && Request::input('id') != "")
            {
                foreach ($get_all as $keys => $values)
                {
                    if($company_id == $keys)
                    {   
                        $id = $values;
                        foreach ($id as $keyid => $valueid)
                        {
                            if($valueid == $value->payroll_employee_id)
                            {
                                $data['_employee'][$key]->checked = 'checked';
                            }
                        }                    
                    }
                }                
            }
            else if (Request::input('id'))
            { 
                $selected_employee = Tbl_payroll_holiday_employee::where('holiday_company_id',Request::input('id'))->get();
                if(count($selected_employee) > 0)
                {
                    foreach ($selected_employee as $keys => $values)
                    {
                        if($values->payroll_employee_id ==  $value->payroll_employee_id)
                        {   
                            $data['_employee'][$key]->checked = 'checked';
                        }
                    }                
                }
            }
        }

        return view('member.payroll.payroll_holiday.holiday_tag_employee', $data);
     }
     public function submit_eployee()
     {
        $company_id = Request::input('company_id');
        $all_employee = Request::input('employee_tag');

        $return['company_id'] = 0;

        if(count($all_employee) > 0)
        {
            $data = Session::get("employee_tag");
            $data[$company_id] = [];

            foreach ($all_employee as $key => $value) 
            {
                $data[$company_id][$key] = $value;
            }

            Session::put('employee_tag',$data);

            $return['status'] = "success";
            $return['company_id'] = $company_id;
        }
        else
        {
            $return['status'] = "error";
            $return['status_message'] = "Please Select atleast one Employee";
        }

        return json_encode($return);
     }
     public function archive_holiday($archive, $id)
     {
          $statement = 'archive';
          if($archive == 0)
          {
               $statement = 'restore';
          }
          $file_name               = Tbl_payroll_holiday::where('payroll_holiday_id', $id)->value('payroll_holiday_name');
          $data['title']           = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/holiday/archive_holiday_action/v2';
          $data['id']         = $id;
          $data['archived']   = $archive;

          return view('member.payroll.payroll_holiday.modal_confirm_archived', $data);
     }

     public function archive_holiday_action()
     {
          $id = Request::input('id');
          $update['payroll_holiday_archived'] = Request::input('archived');
          Tbl_payroll_holiday::where('payroll_holiday_id', $id)->update($update);


          $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_holiday';
          return json_encode($return);
     }

     public function modal_edit_holiday($id)
     {
          // $data['']

          $_company = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('payroll_company_name')->get();
          $company_check = array();

          foreach($_company as $company)
          {
               $count = Tbl_payroll_holiday_company::company($company->payroll_company_id, $id)->count();
               $status = '';
               if($count != 0)
               {
                    $status = 'checked';
               }
               $temp['payroll_company_id']             = $company->payroll_company_id;
               $temp['payroll_company_name']           = $company->payroll_company_name;
               $temp['status']                         = $status;

               array_push($company_check, $temp);
          }

          $data['holiday_id'] = $id;
          $data['_company'] = $company_check;
          $data['holiday'] = Tbl_payroll_holiday::where('payroll_holiday_id',$id)->first();

          return view('member.payroll.payroll_holiday.modal_edit_holiday', $data);
     }

     public function modal_update_holiday()
     {
          $payroll_holiday_id                     = Request::input('payroll_holiday_id');
          $update['payroll_holiday_name']         = Request::input('payroll_holiday_name');
          $update['payroll_holiday_date']         = date('Y-m-d',strtotime(Request::input('payroll_holiday_date')));
          $update['payroll_holiday_category']     = Request::input('payroll_holiday_category');
          $_company                               = Request::input('company');
          
          Tbl_payroll_holiday::where('payroll_holiday_id',$payroll_holiday_id)->update($update);

          Tbl_payroll_holiday_company::where('payroll_holiday_id',$payroll_holiday_id)->delete();
          Tbl_payroll_holiday_employee::where('holiday_company_id',$payroll_holiday_id)->delete();

          $insert_company = array();
          foreach($_company as $company)
          {
               $temp['payroll_company_id'] = $company;
               $temp['payroll_holiday_id'] = $payroll_holiday_id;
               array_push($insert_company, $temp);

               $get_all_employee = Session::get('employee_tag');

               if(count($get_all_employee) > 0)
               {
                 foreach ($get_all_employee as $key => $value)
                 {
                      if($company == $key)
                      {
                          Tbl_payroll_holiday_employee::where('holiday_company_id',$payroll_holiday_id)->where('payroll_company_id',$key)->delete();
                          $all_id = $value;
                          foreach ($all_id as $keyid => $valueid)
                          {
                              $ctr = Tbl_payroll_holiday_employee::where('holiday_company_id',$payroll_holiday_id)->where('payroll_employee_id',$valueid)->count();
                              if($ctr <= 0)
                              {
                                  $ins_employee['payroll_company_id'] = $company;
                                  $ins_employee['payroll_employee_id'] = $valueid;
                                  $ins_employee['holiday_company_id'] = $payroll_holiday_id;
                              }

                              if(!empty($ins_employee))
                              {
                                 Tbl_payroll_holiday_employee::insert($ins_employee);
                              }
                          }
                      }  
                 }
               }                
                else
                {
                  $employee_tag = Tbl_payroll_employee_basic::where('payroll_employee_company_id',$company)->get();
                  foreach ($employee_tag as $key_employee => $value_employee) 
                  {
                        $ins_employee['payroll_company_id'] = $company;
                        $ins_employee['payroll_employee_id'] = $value_employee->payroll_employee_id;
                        $ins_employee['holiday_company_id'] = $payroll_holiday_id;

                        if(!empty($ins_employee))
                        {
                           Tbl_payroll_holiday_employee::insert($ins_employee);
                        }
                  }
                }
          }
          if(!empty($insert_company))
          {
               Tbl_payroll_holiday_company::insert($insert_company);
          }

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_holiday_v2';
          Session::forget('employee_tag');
          return json_encode($return);

     }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
