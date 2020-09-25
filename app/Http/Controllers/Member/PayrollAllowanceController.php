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

use App\Models\Tbl_payroll_allowance_v2;
use App\Models\Tbl_payroll_employee_allowance_v2;

use App\Globals\Payroll;
use App\Globals\PayrollJournalEntries;
use App\Globals\Utilities;
use DateTime;
use App\Models\Tbl_payroll_shift_day;
use App\Models\Tbl_payroll_shift_time;

use App\Globals\Accounting;

use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_time_keeping_approved_breakdown;
use App\Models\Tbl_payroll_time_keeping_approved_performance;

use App\Globals\AuditTrail;
use App\Models\Tbl_audit_trail;

class PayrollAllowanceController extends Member
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
   
    /* ALLOWANCE START */
    public function allowance()
    {         
         $data['_active'] = Tbl_payroll_allowance_v2::sel(Self::shop_id())->orderBy('payroll_allowance_name')->paginate($this->paginate_count);
         $data['_archived'] = Tbl_payroll_allowance_v2::sel(Self::shop_id(), 1)->orderBy('payroll_allowance_name')->paginate($this->paginate_count);
         return view('member.payroll.side_container.allowancev2', $data);
    }

    public function modal_create_allowance()
    {
         $data["_expense"]        = Accounting::getAllAccount('all',null,['Expense','Other Expense']);
         $data["default_expense"] = Tbl_chart_of_account::where("account_number", 66000)
                                            ->where("account_shop_id", Self::shop_id())->value("account_id");

         Session::put('allowance_employee_tag', array());
         return view('member.payroll.payroll_allowance.create_allowance', $data);
    }

    public function modal_allowance_tag_employee($allowance_id)
    {
         $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

         $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

         $data['deduction_id']    =    $allowance_id;
         $data['action']          =    '/member/payroll/allowance/v2/set_employee_allowance_tag';

         return view('member.payroll.payroll_allowance.deduction_tag_employee', $data);
    }

    public function set_employee_allowance_tag()
    {
         $allowance_id = Request::input('deduction_id');
         $employee_tag = Request::input('employee_tag');

         $array = array();
         if(Session::has('allowance_employee_tag'))
         {
              $array = Session::get('allowance_employee_tag');
         }

         $insert_tag = array();

         foreach($employee_tag as $tag)
         {
              array_push($array, $tag);
              if($allowance_id != 0)
              {
                   $count = Tbl_payroll_employee_allowance_v2::where('payroll_allowance_id', $allowance_id)->where('payroll_employee_id',$tag)->count();
                   if($count == 0)
                   {
                        $insert['payroll_allowance_id'] = $allowance_id;
                        $insert['payroll_employee_id']     = $tag;
                        array_push($insert_tag, $insert);
                   }
              }
         }

         if($allowance_id != 0 && !empty($insert_tag))
         {
            Tbl_payroll_employee_allowance_v2::insert($insert_tag);
            $new_data= serialize($insert_tag);
            $tag_me = Tbl_payroll_employee_basic::where('payroll_employee_id',$employee_tag)->first();
            AuditTrail::record_logs('ADDED: Payroll Employee Allowance V2', 'Payroll Employee Name Tagged : '.$tag_me->payroll_employee_display_name, "", "" ,$new_data);
          
         }

         Session::put('allowance_employee_tag',$array);

         $return['status']             = 'success';
         $return['function_name']      = 'create_allowance.load_employee_tag';
         return json_encode($return);
    }

    public function get_employee_allowance_tag()
    {
         $employee = [0 => 0];
         if(Session::has('allowance_employee_tag'))
         {
              $employee = Session::get('allowance_employee_tag');
         }
         $emp = Tbl_payroll_employee_basic::whereIn('payroll_employee_id',$employee)->get();


         $data['new_record'] = $emp;
         return json_encode($data);
    }

    public function remove_allowance_tabe_employee()
    {
         $content = Request::input('content');
         $array     = Session::get('allowance_employee_tag');
         if(($key = array_search($content, $array)) !== false) {
             unset($array[$key]);
         }
         Session::put('allowance_employee_tag',$array);
    }
    
    public function modal_save_allowances()
    {
         $insert['payroll_allowance_name']       = Request::input('payroll_allowance_name');
         // $insert['payroll_allowance_amount']     = Request::input('payroll_allowance_amount');
         $insert['payroll_allowance_category']   = Request::input('payroll_allowance_category');
         $insert['payroll_allowance_add_period'] = Request::input('payroll_allowance_add_period');
         $insert['expense_account_id']           = Request::input('expense_account_id');
         $insert['payroll_allowance_type']   = Request::input('payroll_allowance_type');
         $insert['shop_id']                           = Self::shop_id();
         $actual_gross_pay = Request::input('actual_gross_pay');
         if ($actual_gross_pay!=null) 
         {
             foreach ($actual_gross_pay as $key => $value) 
             {
                $insert[$value] = 1;
             }
         }
         

         $allowance_id = Tbl_payroll_allowance_v2::insertGetId($insert);
          AuditTrail::record_logs('CREATED: Payroll Allowance V2', 'Payroll Allowance Name: '.Request::input('payroll_allowance_name'),"", "" ,"");
         $per_employee_amount = Request::input('allowance_amount');

         $total_amount = 0;
         $insert_employee = array();
         if(Session::has('allowance_employee_tag'))
         {
              foreach(Session::get('allowance_employee_tag') as $tag)
              {    
                   $temp['payroll_allowance_id']      = $allowance_id;
                   $temp['payroll_employee_id']  = $tag;
                   $temp['payroll_employee_allowance_amount']  = str_replace(',', '', $per_employee_amount[$tag]);
                   $total_amount += str_replace(',', '', $per_employee_amount[$tag]);
                   array_push($insert_employee, $temp);
              }
              if(!empty($insert_employee))
              {
                   Tbl_payroll_employee_allowance_v2::insert($insert_employee);
              }
         }
         $update['payroll_allowance_amount'] = $total_amount;
         Tbl_payroll_allowance_v2::where('payroll_allowance_id',$allowance_id)->update($update);

         $return['status']             = 'success';
         $return['function_name']      = 'payrollconfiguration.reload_allowancev2';
         return json_encode($return);
    }

    public function modal_archived_allwance($archived, $allowance_id)
    {
         $statement = 'archive';
         if($archived == 0)
         {
              $statement = 'restore';
         }
         $file_name               = Tbl_payroll_allowance_v2::where('payroll_allowance_id', $allowance_id)->value('payroll_allowance_name');
         $data['title']           = ucfirst($statement);
         $data['html']       =  'Do you really want to '.$statement.' '.$file_name.'?';
         $data['action']     = '/member/payroll/allowance/v2/archived_allowance';
         $data['id']         = $allowance_id;
         $data['archived']   = $archived;

         return view('member.payroll.payroll_allowance.allowance_confirm_archived', $data);
    }

    public function archived_allowance()
    {
         $id = Request::input('id');
         $update['payroll_allowance_archived'] = Request::input('archived');

        $allowance = Tbl_payroll_allowance_v2::where('payroll_allowance_id', $id)->first();

         // $update['payroll_employee_allowance_archived'] = Request::input('archived');


         Tbl_payroll_allowance_v2::where('payroll_allowance_id', $id)->update($update);
        AuditTrail::record_logs('DELETED: Payroll Allowance V2', 'Payroll Allowance Name:'. $allowance->payroll_allowance_name,"", "" ,"");
          
         $return['status']             = 'success';
         $return['function_name']      = 'payrollconfiguration.reload_allowancev2';

         return json_encode($return);
    }


    public function modal_edit_allowance($id)
    {
         $data["_expense"] = Accounting::getAllAccount('all',null,['Expense','Other Expense']);
         $data['allowance'] = Tbl_payroll_allowance_v2::where('payroll_allowance_id', $id)->first();
         $data['_active'] = Tbl_payroll_employee_allowance_v2::getperallowance($id)->get();
         $data['_archived'] = Tbl_payroll_employee_allowance_v2::getperallowance($id , 1)->get();
         // dd($data['_archived']);
         return view('member.payroll.payroll_allowance.update_allowance', $data);
    }

    public function modal_archived_llowance_employee($archived, $id)
    {
         $statement = 'archive';
         if($archived == 0)
         {
              $statement = 'restore';
         }
         $_query             = Tbl_payroll_employee_allowance_v2::employee($id)->first();
         // dd($_query);
         $file_name               = $_query->payroll_employee_title_name.' '.$_query->payroll_employee_first_name.' '.$_query->payroll_employee_middle_name.' '.$_query->payroll_employee_last_name.' '.$_query->payroll_employee_suffix_name;
         $data['title']           = 'Do you really want to '.$statement.' '.$file_name.'?';
         $data['html']       = '';
         $data['action']     = '/member/payroll/allowance/v2/archived_allowance_employee';
         $data['id']         = $id;
         $data['archived']   = $archived;
         $data['payroll_deduction_type'] = "";

         return view('member.modal.modal_confirm_archived', $data);
    }

    public function archived_allowance_employee()
    {
         $id = Request::input('id');
         $update['payroll_employee_allowance_archived'] = Request::input('archived');
         Tbl_payroll_employee_allowance_v2::where('payroll_employee_allowance_id', $id)->update($update);
         $return['status']             = 'success';
         $return['function_name']      = 'create_allowance.load_employee_tag';
         return json_encode($return);
    }

    public function update_allowance()
    {
         $payroll_allowance_id                   = Request::input('payroll_allowance_id');
         $update['payroll_allowance_name']       = Request::input('payroll_allowance_name');
         $update['payroll_allowance_amount']     = Request::input('payroll_allowance_amount');
         $update['payroll_allowance_category']   = Request::input('payroll_allowance_category');
         $update['payroll_allowance_add_period'] = Request::input('payroll_allowance_add_period');
         $update['payroll_allowance_type']       = Request::input('payroll_allowance_type');
         $update['expense_account_id']           = Request::input('expense_account_id');
         $update['basic_pay']                    = 0;
         $update['cola']                         = 0;
         $update['over_time_pay']                = 0;
         $update['regular_holiday_pay']          = 0;
         $update['special_holiday_pay']          = 0;
         $update['leave_pay']                    = 0;

         $actual_gross_pay = Request::input('actual_gross_pay');

         if ($actual_gross_pay!=null) 
         {
             foreach ($actual_gross_pay as $key => $value) 
             {
                $update[$value] = 1;
             }
         }
        $new_data=serialize($update);
        Tbl_payroll_allowance_v2::where('payroll_allowance_id', $payroll_allowance_id)->update($update);
        AuditTrail::record_logs('EDITED: Payroll Allowance V2', 'Payroll Allowance ID #: '.$payroll_allowance_id." From allowance name:".Request::input('payroll_allowance_name'), $payroll_allowance_id, "" ,$new_data);
          
         Tbl_payroll_employee_allowance_v2::where('payroll_allowance_id',$payroll_allowance_id)->delete();

         $per_employee_amount = Request::input('allowance_amount');
         $employee_tag = Request::input('employee_id');
         $insert_employee = array();
         $total_amount = 0;
         if(count($employee_tag) > 0)
         {
              foreach($employee_tag as $tag)
              {    
                   $temp['payroll_allowance_id']      = $payroll_allowance_id;
                   $temp['payroll_employee_id']  = $tag;
                   $temp['payroll_employee_allowance_amount']  = str_replace(',', '', $per_employee_amount[$tag]);
                   $total_amount += str_replace(',', '', $per_employee_amount[$tag]);
                   array_push($insert_employee, $temp);
              }
              if(!empty($insert_employee))
              {
                   Tbl_payroll_employee_allowance_v2::insert($insert_employee);
              }
         }
         $update['payroll_allowance_amount'] = $total_amount;
         Tbl_payroll_allowance_v2::where('payroll_allowance_id',$payroll_allowance_id)->update($update);

         $return['status']             = 'success';
         $return['function_name']      = 'payrollconfiguration.reload_allowancev2';
         return json_encode($return);
    }

    public function reload_allowance_employee()
    {
         $payroll_allowance_id = Request::input('payroll_allowance_id');
         $data['_active'] = Tbl_payroll_employee_allowance_v2::getperallowance($payroll_allowance_id)->get();
         $data['_archived'] = Tbl_payroll_employee_allowance_v2::getperallowance($payroll_allowance_id , 1)->get();
         return view('member.payroll.reload.allowance_employee_reload', $data);
    }

    /* ALLOWANCE END */
}
