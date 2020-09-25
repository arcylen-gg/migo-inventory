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

use App\Models\Tbl_payroll_deduction_v2;
use App\Models\Tbl_payroll_deduction_employee_v2;
use App\Models\Tbl_payroll_deduction_payment_v2;

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

class PayrollDeductionController extends Member
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

    /*Payroll Deduction Start*/
    public function index()
    {
     
          $data['_all_deductions_by_category'] = Tbl_payroll_deduction_v2::selalldeductionbycategory(Self::shop_id(),0)->paginate($this->paginate_count);
          $data['_all_deductions_by_category_archive'] = Tbl_payroll_deduction_v2::selalldeductionbycategory(Self::shop_id(),1)->paginate($this->paginate_count);
          Self::compute_deduction_total_and_balance($data);
          return view('member.payroll.side_container.deduction_menu_v2', $data);
    }

    /*old index*/
    /*public function index()
    {
       $data['_active'] = Tbl_payroll_deduction_v2::where('shop_id',Self::shop_id())
                              ->where('payroll_deduction_archived', 0)
                              ->orderBy('tbl_payroll_deduction_v2.payroll_deduction_name')
                              ->paginate($this->paginate_count);
       $data['_archived'] = Tbl_payroll_deduction_v2::where('shop_id',Self::shop_id())
                              ->where('payroll_deduction_archived', 1)
                              ->orderBy('tbl_payroll_deduction_v2.payroll_deduction_name')
                              ->paginate($this->paginate_count);

       return view('member.payroll.side_container.deductionv2', $data);
    }
    */


    public function modal_view_deduction_employee()
    {
          $payroll_deduction_type =   str_replace('_', ' ', Request::get('deduction_category'));
          $data['_loan_data'] = $this->get_deduction_by_type($this->shop_id(),$payroll_deduction_type);;
          return view("member.payrollreport.loan_summary_table", $data);
    }


    public function modal_view_deduction_employee_config()
    {
          $payroll_deduction_type  =   str_replace('_', ' ', Request::get('deduction_category'));
          $data['_loan_data']      = $this->get_deduction_by_type_config($this->shop_id(),$payroll_deduction_type,0);
          $data['_loan_data_archive'] = $this->get_deduction_by_type_config($this->shop_id(),$payroll_deduction_type,1);
          return view("member.payrollreport.loan_summary_table_config", $data);
    }

    public function modal_create_deduction()
     {
          $data["_expense"] = Accounting::getAllAccount('all',null,['Expense','Other Expense']);
          $data["default_expense"] = Tbl_chart_of_account::where("account_number", 66000)
                                   ->where("account_shop_id", Self::shop_id())->value("account_id");
          $array = array();
          Session::put('employee_deduction_tag',$array);

          return view('member.payroll.modal.modal_create_deduction_v2', $data);
     }

     public function modal_create_deduction_type($type)
     {
          $type                    = str_replace('_', ' ', $type);
          $data['_active']    = Tbl_payroll_deduction_type::seltype(Self::shop_id(),$type)->get();
          $data['_archived']  = Tbl_payroll_deduction_type::seltype(Self::shop_id(),$type, 1)->get();
          $data['type']       = $type;
          return view('member.payroll.modal.modal_deduction_type',$data);
     }

     public function modal_save_deduction_type()
     {
          $insert['payroll_deduction_category']   = Request::input('payroll_deduction_category');
          $insert['payroll_deduction_type_name']  = Request::input('payroll_deduction_type_name');
          $insert['shop_id']                           = Self::shop_id();

          $id = Tbl_payroll_deduction_type::insertGetId($insert);
          AuditTrail::record_logs('CREATED: Payroll Deduction Type', 'Payroll Deduction Type Name: '.Request::input('payroll_deduction_type_name'), "", "" ,"");

          $type = Request::input('payroll_deduction_category');

          $_data = Tbl_payroll_deduction_type::seltype(Self::shop_id(),$type)->get();
          $html = '<option value="">Select Type</option>';
          foreach($_data as $data)
          {
               $html .= '<option value="'.$data->payroll_deduction_type_id.'" ';
               if($data->payroll_deduction_type_id == $id)
               {
                    $html .= 'selected="selected"';
               }
               $html.= '>'.$data->payroll_deduction_type_name.'</option>';
          }
          return $html;
     }

     public function reload_deduction_type()
     {
          $payroll_deduction_category = Request::input('payroll_deduction_category');
          $archived                          = Request::input('archived');
          $data['_active']              = Tbl_payroll_deduction_type::seltype(Self::shop_id(),$payroll_deduction_category, $archived)->get();
          return view('member.payroll.reload.deduction_list_reload',$data);
     }

     public function update_deduction_type()
     {
          $value         = Request::input('value');
          $content  = Request::input('content');
          
          $update['payroll_deduction_type_name'] = $value;
          $deduct = Tbl_payroll_deduction_type::where('payroll_deduction_type_id',$content)->first();
          
          Tbl_payroll_deduction_type::where('payroll_deduction_type_id',$content)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Deduction Type', 'Payroll Deduction Type Name: '.$deduct->payroll_deduction_type_name. " to Value ".Request::input('payroll_holiday_name'), "", "" ,"");


     }

     public function archive_deduction_type()
     {
          $content = Request::input('content');
          $update['payroll_deduction_archived'] = Request::input('archived');
           $deduct = Tbl_payroll_deduction_type::where('payroll_deduction_type_id',$content)->first();
          
          Tbl_payroll_deduction_type::where('payroll_deduction_type_id',$content)->update($update);
         AuditTrail::record_logs('DELETED: Payroll Deduction Type', 'Payroll Deduction Type Name: '.$deduct->payroll_deduction_type_name, "", "" ,"");

     }

     public function ajax_deduction_type()
     {
          $category = Request::input('category');
          $data = Tbl_payroll_deduction_type::seltype(Self::shop_id(),$category)->get();
          return json_encode($data);
     }

     public function modal_deduction_tag_employee($deduction_id)
     {
          $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

          $data['deduction_id']    =    $deduction_id;
          $data['action']               =    '/member/payroll/deduction/v2/set_employee_deduction_tag';

          return view('member.payroll.modal.modal_deduction_tag_employee', $data);
     }

     public function modal_save_deduction()
     {
          $insert['shop_id']                      = Self::shop_id();
          $insert['payroll_deduction_type']       = Request::input('payroll_deduction_type');
          $insert['payroll_deduction_name']       = Request::input('payroll_deduction_name');
          $insert['payroll_deduction_amount']     = Request::input('payroll_deduction_amount');
          $insert['payroll_monthly_amortization'] = Request::input('payroll_monthly_amortization');
          $insert['payroll_periodal_deduction']   = Request::input('payroll_periodal_deduction');
          $insert['payroll_deduction_date_filed'] = date('Y-m-d',strtotime(Request::input('payroll_deduction_date_filed')));
          $insert['payroll_deduction_date_start'] = date('Y-m-d',strtotime(Request::input('payroll_deduction_date_start')));
          $insert['payroll_deduction_period']     = Request::input('payroll_deduction_period');
          $insert['payroll_deduction_category']   = Request::input('payroll_deduction_category');
          $insert['payroll_deduction_terms']      = Request::input('payroll_deduction_terms');
          $insert['payroll_deduction_number_of_payments']   = Request::input('payroll_deduction_number_of_payments');
         
         //payroll_deduction_category

          //dd($insert);
          $deduction_id = Tbl_payroll_deduction_v2::insertGetId($insert);
          AuditTrail::record_logs('CREATED: Payroll Deduction V2', 'Payroll Deduction Type Name: '.Request::input('payroll_deduction_name')." Deduction Type: ".Request::input('payroll_deduction_type'), "", "" ,"");

          if(Session::has('employee_deduction_tag'))
          {
               $employee_tag = Session::get('employee_deduction_tag');
               $insert_employee = [];
               foreach($employee_tag as $tag)
               {
                    $insert_employee['payroll_deduction_id']         = $deduction_id;
                    $insert_employee['payroll_employee_id']          = $tag;
                    Tbl_payroll_deduction_employee_v2::insert($insert_employee);
                    $tagged = Tbl_payroll_employee_basic::where('payroll_employee_id',$employee_tag)->first();
                    AuditTrail::record_logs('Added: Payroll Deduction V2 Tagged ', 'Payroll Deduction Employee Name: '.$tagged->payroll_employee_display_name, "", "" ,"");

               }
          }

          $return['stataus'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_deductionv2';
          return json_encode($return);

     }

     public function ajax_deduction_tag_employee()
     {
          $company  = Request::input('company');
          $department = Request::input('department');
          $jobtitle      = Request::input('jobtitle');


          $emp = Tbl_payroll_employee_contract::employeefilter($company, $department, $jobtitle, date('Y-m-d'), Self::shop_id())->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->groupBy('tbl_payroll_employee_basic.payroll_employee_id')->get();
          // dd($emp);
          return json_encode($emp);
     }

     public function set_employee_deduction_tag()
     {
          $employee_tag = Request::input('employee_tag');
          $deduction_id = Request::input('deduction_id');
          // dd($deduction_id);
          $array = array();
          if(Session::has('employee_deduction_tag'))
          {
               $array = Session::get('employee_deduction_tag');
          }
          // dd($array);

          $insert_tag = array();

          foreach($employee_tag as $tag)
          {
               if(!in_array($tag, $array) && $deduction_id == 0)
               {
                    array_push($array, $tag);
               }
               $count = Tbl_payroll_deduction_employee_v2::where('payroll_deduction_id',$deduction_id)->where('payroll_employee_id', $tag)->count();

               if($count == 0)
               {
                    $insert['payroll_deduction_id']    = $deduction_id;
                    $insert['payroll_employee_id']          = $tag;
                    array_push($insert_tag, $insert);
               }
               
          }
          // dd($insert_tag);
          if($deduction_id != 0 && $insert_tag != '')
          {
               Tbl_payroll_deduction_employee_v2::insert($insert_tag);
               $tagged = Tbl_payroll_employee_basic::where('payroll_employee_id',$employee_tag)->first();
               AuditTrail::record_logs('Added: Payroll Deduction V2 Tagged ', 'Payroll Deduction Employee Name: '.$tagged->payroll_employee_display_name, "", "" ,"");

          }
          else
          {
               Session::put('employee_deduction_tag', $array);
          }
          

          $return['status'] = 'success';
          $return['function_name'] = 'modal_create_deduction.load_tagged_employee';
          return json_encode($return);
     }

     public function get_employee_deduction_tag()
     {    
          $employee = [0 => 0];
          if(Session::has('employee_deduction_tag'))
          {
               $employee = Session::get('employee_deduction_tag');
          }
          $emp = Tbl_payroll_employee_basic::whereIn('payroll_employee_id',$employee)->get();

          $data['new_record'] = $emp;
          return json_encode($data);
     }

     public function remove_from_tag_session()
     {
          $content = Request::input('content');
          $array     = Session::get('employee_deduction_tag');
          if(($key = array_search($content, $array)) !== false) {
              unset($array[$key]);
          }
          Session::put('employee_deduction_tag', $array);
     }

     public function reload_deduction_employee_tag()
     {
          $payroll_deduction_id = Request::input('payroll_deduction_id');
          $data['emp'] = Payroll::getbalancev2(Self::shop_id(), $payroll_deduction_id);
          return view('member.payroll.reload.deduction_employee_tag_reload', $data);
     }


     public function modal_edit_deduction($id)
     {
          $data["_expense"] = Accounting::getAllAccount('all',null,['Expense','Other Expense']);
          $data['deduction'] = Tbl_payroll_deduction_v2::where('payroll_deduction_id',$id)->first();
          $data['_type'] = Tbl_payroll_deduction_type::where('shop_id', Self::shop_id())->where('payroll_deduction_archived', 0)->orderBy('payroll_deduction_type_name')->get();
          $data['emp'] = Payroll::getbalancev2(Self::shop_id(), $id);
          // dd($data["deduction"]->payroll_deduction_period);
          return view('member.payroll.modal.modal_edit_deductionv2', $data);
     }


     public function archive_deduction($archived, $id)
     {
          
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name          = Tbl_payroll_deduction_v2::where('payroll_deduction_id', $id)->value('payroll_deduction_name');
          $data['title']      = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/deduction/v2/archived_deduction_action';
          $data['id']         = $id;
          $data['archived']   = $archived;
          $data['payroll_deduction_type']  = Request::input('payroll_deduction_type');

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archived_deduction_action()
     {
          $update['payroll_deduction_archived']   = Request::input('archived');
          $id                                     = Request::input('id');
          Tbl_payroll_deduction_v2::where('payroll_deduction_id',$id)->update($update);
          $tagged = Tbl_payroll_deduction_v2::where('payroll_deduction_id',$id)->first();
          AuditTrail::record_logs('DELETED: Payroll Deduction V2', 'Payroll Deduction Type Name: '.$tagged->payroll_deduction_name, "", "" ,"");
          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_deductionv2';
          $return['from']               = 'archive-deduction';
          return json_encode($return);
     }



     public function modal_update_deduction()
     {
          $payroll_deduction_id                   = Request::input('payroll_deduction_id');

          $update['payroll_deduction_type']       = Request::input('payroll_deduction_type');
          $update['payroll_deduction_name']       = Request::input('payroll_deduction_name');
          $update['payroll_deduction_amount']     = Request::input('payroll_deduction_amount');
          $update['payroll_monthly_amortization'] = Request::input('payroll_monthly_amortization');
          $update['payroll_periodal_deduction']   = Request::input('payroll_periodal_deduction');
          $update['payroll_deduction_date_filed'] = date('Y-m-d',strtotime(Request::input('payroll_deduction_date_filed')));
          $update['payroll_deduction_date_start'] = date('Y-m-d',strtotime(Request::input('payroll_deduction_date_start')));
          $update['payroll_deduction_period']     = Request::input('payroll_deduction_period');
          $update['payroll_deduction_category']     = Request::input('payroll_deduction_category');
          $update['payroll_deduction_terms']    = Request::input('payroll_deduction_terms');
          $update['payroll_deduction_number_of_payments']   = Request::input('payroll_deduction_number_of_payments');

          Tbl_payroll_deduction_v2::where('payroll_deduction_id',$payroll_deduction_id)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Deduction V2', 'Payroll Deduction Name: '.Request::input('payroll_deduction_name'), "", "" ,"");

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_deductionv2';

          return json_encode($return);
     }

     public function deduction_employee_tag($archive, $payroll_deduction_employee_id)
     {
          $statement = 'cancel';
          if($archive == 0)
          {
               $statement = 'restore';
          }
          $file_name          = Tbl_payroll_deduction_employee_v2::getemployee($payroll_deduction_employee_id)->value('payroll_employee_display_name');
          $data['title']      = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/deduction/v2/deduction_employee_tag_archive';
          $data['id']         = $payroll_deduction_employee_id;
          $data['archived']   = $archive;
          $data['payroll_deduction_type']  = Request::input('payroll_deduction_type');

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function deduction_employee_tag_archive()
     {
          $id = Request::input('id');
          $update['payroll_deduction_employee_archived'] = Request::input('archived');
          Tbl_payroll_deduction_employee_v2::where('payroll_deduction_employee_id', $id)->update($update);
          $pdev = Tbl_payroll_deduction_employee_v2::where('payroll_deduction_employee_id', $id)->first();
          $pdev1 = Tbl_payroll_employee_basic::where('payroll_employee_id',$pdev->payroll_employee_id)->first();
          AuditTrail::record_logs('DELETED: Payroll Deduction Employee V2', 'Payroll Deduction Employee V2 with employee ID #'.$id ." and Employee Name: ".$pdev1 ->payroll_employee_display_name, "", "" ,"");

          $return['status']             = 'success';
          $return['function_name']      = 'modal_create_deduction.reload_tag_employeev2';
          return json_encode($return);
     }

     /* DEDUCTION END */



     public static function approve_deduction_payment($payroll_company_period_id = 0, $employee_id = 0, $payroll_period_id = 0)
     {
          $period_info = Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->first();

          /*original*/
          // $_deduction = Payroll::getdeductionv2($employee_id, $period_info['payroll_period_start'], $period_info['period_category'], $period_info['period_category'], $period_info['shop_id']);
          // dd($employee_id, $period_info['payroll_period_start'], $period_info['period_category'], $period_info['period_category'], $period_info['shop_id']);
          

          
          if ($period_info['period_count'] == "last_period") 
          {
              $period_info['period_count'] = "Last Period";
          }
          if ($period_info['period_count'] == "first_period")  
          {
               $period_info['period_count'] = "First Period";
          }
          if ($period_info['period_count'] == "middle_period") 
          {
              $period_info['period_count'] == "Middle Period";
          }
          
          // dd($employee_id, $period_info['payroll_period_end'], $period_info['period_count'], $period_info['payroll_period_category'], $period_info['shop_id']);
          $_deduction = Payroll::getdeductionv2($employee_id, $period_info['payroll_period_end'], $period_info['period_count'], $period_info['payroll_period_category'], $period_info['shop_id']);
         
          foreach ($_deduction['deduction'] as $deduction) 
          {

                /*changes 2nd statement of query $deduction_employee   ->where('tbl_payroll_deduction_v2.payroll_deduction_name',$deduction['deduction_name'])*/
                $deduction_employee = Tbl_payroll_deduction_employee_v2::where('tbl_payroll_deduction_employee_v2.payroll_employee_id',$employee_id)
               ->where('tbl_payroll_deduction_v2.payroll_deduction_id',$deduction['payroll_deduction_id'])
               ->where('tbl_payroll_deduction_v2.payroll_deduction_archived','0')
               ->join('tbl_payroll_deduction_v2','tbl_payroll_deduction_v2.payroll_deduction_id','=','tbl_payroll_deduction_employee_v2.payroll_deduction_id')
               ->first();
               
               /*changes 2nd statement of query $deduction_payment   ->where('payroll_deduction_name',$deduction['payroll_deduction_name']))*/
               $deduction_payment = Tbl_payroll_deduction_payment_v2::where('payroll_deduction_id',$deduction_employee['payroll_deduction_id'])
               ->where('payroll_deduction_id',$deduction['payroll_deduction_id'])
               ->first();
              
               // dd($deduction_employee);
               if ($deduction_payment == null) 
               {
                    // die(var_dump($deduction_payment));
                    $insert['payroll_deduction_id']         = $deduction_employee['payroll_deduction_id'];
                    $insert['payroll_employee_id']          = $employee_id;
                    $insert['payroll_record_id']            = 0;
                    $insert['payroll_period_company_id']    = $payroll_company_period_id;
                    $insert['deduction_name']               = $deduction_employee['payroll_deduction_name'];
                    $insert['deduction_category']           = $deduction_employee['payroll_deduction_category'];
                    $insert['payroll_payment_period']       = $period_info['payroll_period_end'];
                    $insert['payroll_beginning_balance']    = $deduction_employee['payroll_deduction_amount']; 
                    $insert['payroll_payment_amount']       = $deduction['payroll_periodal_deduction'];  
                    $insert['payroll_total_payment_amount'] = $deduction['payroll_periodal_deduction']; 
                    $insert['payroll_remaining_balance']    = $deduction_employee['payroll_deduction_amount'] - $deduction['payroll_periodal_deduction']; 
                    Tbl_payroll_deduction_payment_v2::insert($insert);
                   
               }
               else
               {
                    $payroll_total_payment_amount = Tbl_payroll_deduction_payment_v2::where('payroll_deduction_id',$deduction_employee['payroll_deduction_id'])
                    ->where('deduction_name',$deduction['deduction_name'])
                    ->select(DB::raw('sum(payroll_payment_amount) as total_payment'))
                    ->first();
                    
                    $insert['payroll_deduction_id']              = $deduction_employee['payroll_deduction_id'];
                    $insert['payroll_employee_id']               = $employee_id;
                    $insert['payroll_record_id']                 = 0;
                    $insert['payroll_period_company_id']         = $payroll_company_period_id;
                    $insert['deduction_name']                    = $deduction_employee['payroll_deduction_name'];
                    $insert['deduction_category']                = $deduction_employee['payroll_deduction_category'];
                    $insert['payroll_payment_period']            = $period_info['payroll_period_end'];
                    $insert['payroll_beginning_balance']         = $balance = $deduction_employee["payroll_deduction_amount"] - $payroll_total_payment_amount["total_payment"]; 
                    $insert['payroll_payment_amount']            = $deduction['payroll_periodal_deduction'];  
                    $insert['payroll_total_payment_amount']      = $payroll_total_payment_amount["total_payment"] + $deduction['payroll_periodal_deduction']; 
                    $insert['payroll_remaining_balance']         = $balance - $deduction['payroll_periodal_deduction']; 
                    Tbl_payroll_deduction_payment_v2::insert($insert);

               }
          }
     }

     public static function get_deduction($shop_id = 0, $employee_id = 0, $deduction_id = 0 , $deduction_category = '')
     {
          $data = Tbl_payroll_deduction_payment_v2::getallinfo($shop_id,0,0,$deduction_category)->get();
          return $data;
     }

     public static function get_deduction_by_type($shop_id = 0, $deduction_type='',$company,$branchcompany)
     {

          $query = Tbl_payroll_deduction_payment_v2::getallinfo($shop_id,$company,0,$branchcompany);

          if ($deduction_type != '0') 
          {
               $query->where('tbl_payroll_deduction_v2.payroll_deduction_type',$deduction_type);
          }

          $data = $query->get();
          return $data;
     }

     public static function get_deduction_by_type_config($shop_id = 0, $deduction_type='',$archive = 0)
     {

          $query = Tbl_payroll_deduction_v2::deductionbytype($shop_id,$deduction_type,$archive);
          $data = $query->get();
          
          // dd($data);
          return $data;
     }


     // public static function get_deduction_by_type($shop_id = 0, $deduction_type='')
     // {

     //      $query = Tbl_payroll_deduction_v2::getallinfo($shop_id,$deduction_type);

     //      // if ($deduction_type!='0') {
     //      //      $query->where('tbl_payroll_deduction_v2.payroll_deduction_type',$deduction_type);
     //      // }

     //      $data = $query->get();
     //      return $data;
     // }


     public static function get_deduction_payment($shop_id = 0, $employee_id = 0, $deduction_id = 0)
     {
          $data = Tbl_payroll_deduction_payment_v2::getpayment($employee_id,$deduction_id)->get();
          return $data;
     }

     public function compute_deduction_total_and_balance($data)
     {
          if (isset($data['_all_deductions_by_category'])) 
          {
               foreach ($data['_all_deductions_by_category'] as $key => $deduction) 
               {
                    $total_payment = 0;

                    $_payment = Tbl_payroll_deduction_payment_v2::where('tbl_payroll_deduction_v2.shop_id',Self::shop_id())
                    ->where('tbl_payroll_deduction_v2.payroll_deduction_type',$deduction->payroll_deduction_type)
                    ->where('.tbl_payroll_deduction_v2.payroll_deduction_archived',0)
                    ->join('tbl_payroll_deduction_v2','tbl_payroll_deduction_v2.payroll_deduction_id','=','tbl_payroll_deduction_payment_v2.payroll_deduction_id')
                    ->get();

                    foreach ($_payment as $lbl => $payment) 
                    {
                        $total_payment += $payment->payroll_payment_amount;
                    }

                    $data['_all_deductions_by_category'][$key]->total_payment = $total_payment;
                    $data['_all_deductions_by_category'][$key]->balance       = $data['_all_deductions_by_category'][$key]->total_amount - $total_payment;
               }
          }

          if (isset($data['_all_deductions_by_category_archive'])) 
          {
               foreach ($data['_all_deductions_by_category_archive'] as $key => $deduction) 
               {
                    $total_payment = 0;

                    $_payment = Tbl_payroll_deduction_payment_v2::where('tbl_payroll_deduction_v2.shop_id',Self::shop_id())
                    ->where('tbl_payroll_deduction_v2.payroll_deduction_type',$deduction->payroll_deduction_type)
                    ->where('.tbl_payroll_deduction_v2.payroll_deduction_archived',1)
                    ->join('tbl_payroll_deduction_v2','tbl_payroll_deduction_v2.payroll_deduction_id','=','tbl_payroll_deduction_payment_v2.payroll_deduction_id')
                    ->get();

                    foreach ($_payment as $lbl => $payment) 
                    {
                        $total_payment += $payment->payroll_payment_amount;
                    }

                    $data['_all_deductions_by_category_archive'][$key]->total_payment = $total_payment;
                    $data['_all_deductions_by_category_archive'][$key]->balance       = $data['_all_deductions_by_category_archive'][$key]->total_amount - $total_payment;
               }
          }
     }
}
