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
use Input;

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
use App\Models\Tbl_payroll_leave_type;
use App\Models\Tbl_payroll_leave_employeev2;
use App\Models\Tbl_payroll_leave_tempv2;
use App\Models\Tbl_payroll_leave_schedulev2;
use App\Models\Tbl_payroll_leave_history;
use App\Models\Tbl_payroll_leave_report;
use App\Models\Tbl_payroll_13th_month_basis;
use App\Models\Tbl_payroll_payslip_option;
use App\Models\Tbl_payroll_manpower_report;


use App\Globals\Payroll;
use App\Globals\PayrollJournalEntries;
use App\Globals\Utilities;
use App\Globals\Pdf_global;
use DateTime;
use App\Models\Tbl_payroll_shift_day;
use App\Models\Tbl_payroll_shift_time;
use App\Globals\AuditTrail;
use App\Models\Tbl_audit_trail;
use App\Models\Tbl_user;
use App\Globals\Accounting;

use App\Models\Tbl_payroll_time_keeping_approved;
use App\Models\Tbl_payroll_time_keeping_approved_breakdown;
use App\Models\Tbl_payroll_time_keeping_approved_performance;




class PayrollController extends Member
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
     
     //audit_trail_view_all
     public function modal_view_all_transaction($id,$uid)
     {
         
          $data['audit'] = Tbl_audit_trail::orderBy("tbl_audit_trail.created_at","DESC")->where('audit_trail_id',$id)->where("audit_shop_id",AuditTrail::getShopId())->first();

          $data['user_info'] = Tbl_user::where('user_id',$uid)->where("user_shop",AuditTrail::getShopId())->first();
           return view("member.payroll.modal.modal_view_all_transaction",$data);
     }
     //audit end


     // public function modal_view_all_transaction($id,$uid)
     // {
          
     //      $data['audit'] = Tbl_audit_trail::orderBy("tbl_audit_trail.created_at","DESC")->where('audit_trail_id',$id)->where("audit_shop_id",AuditTrail::getShopId())->first();
     //      $data['user_info'] = Tbl_user::where('user_id',$uid)->where("user_shop",AuditTrail::getShopId())->first();
     //       return view("member.payroll.modal.modal_view_all_transaction",$data);
     // }


     /* PAYROLL TIME KEEPING START */
     public function time_keeping()
     {
          $data["_company"]   = Tbl_payroll_company::where("shop_id", Self::shop_id())->where('payroll_parent_company_id', 0)->get();
          $data['_period']    = Tbl_payroll_period::sel(Self::shop_id())
                                                  ->where('payroll_parent_company_id', 0)
                                                  ->join('tbl_payroll_period_company','tbl_payroll_period_company.payroll_period_id','=','tbl_payroll_period.payroll_period_id')
                                                  ->join('tbl_payroll_company', 'tbl_payroll_company.payroll_company_id','=', 'tbl_payroll_period_company.payroll_company_id')
                                                  ->orderBy('tbl_payroll_period.payroll_period_start','asc')
                                                  ->get();
                                                  
          $data['access'] = Utilities::checkAccess('payroll-timekeeping','salary_rates');
         
          return view('member.payroll.payroll_timekeeping', $data);
     }


     public function time_keeping_load_table($payroll_company_id)
     {
          $mode = Request::input("mode");

          $query = Tbl_payroll_period::sel(Self::shop_id())
                                                  ->where('payroll_parent_company_id', 0)
                                                  ->join('tbl_payroll_period_company','tbl_payroll_period_company.payroll_period_id','=','tbl_payroll_period.payroll_period_id')
                                                  ->join('tbl_payroll_company', 'tbl_payroll_company.payroll_company_id','=', 'tbl_payroll_period_company.payroll_company_id')
                                                  ->orderBy('tbl_payroll_period.payroll_period_start','asc');

         

          if ($payroll_company_id != 0)
          {
               $query->where('tbl_payroll_company.payroll_company_id', $payroll_company_id);
          }

          $query->where("tbl_payroll_period_company.payroll_period_status", $mode);


          $data["_period"] = $query->get();
       
          switch ($mode)
          {
               case 'pending':
                    return view('member.payroll.payroll_timekeeping_table', $data);
               break;

               case 'processed':
                    return view('member.payroll.payroll_timekeeping_table_processed', $data);
               break; 

               case 'registered':
                    return view('member.payroll.payroll_timekeeping_table_registered', $data);
               break;  

               case 'posted':
                    return view('member.payroll.payroll_timekeeping_table_posted', $data);
               break;  

               case 'approved':
                    return view('member.payroll.payroll_timekeeping_table_approved', $data);
               break;  


               default:
                    return view('member.payroll.payroll_timekeeping_table', $data);
               break;
          }
          
     }


     public function payroll_process_module()
     {
          
          $data["_company"] = Tbl_payroll_company::where("shop_id", Self::shop_id())->where('payroll_parent_company_id', 0)->get();
          $data['_period'] = Tbl_payroll_period::sel(Self::shop_id())
                                                  ->where('payroll_parent_company_id', 0)
                                                  ->join('tbl_payroll_period_company','tbl_payroll_period_company.payroll_period_id','=','tbl_payroll_period.payroll_period_id')
                                                  ->join('tbl_payroll_company', 'tbl_payroll_company.payroll_company_id','=', 'tbl_payroll_period_company.payroll_company_id')
                                                  ->orderBy('tbl_payroll_period.payroll_period_start','asc')
                                                  ->get();
          
          $data['access']               = Utilities::checkAccess('payroll-timekeeping','salary_rates');
          $data['access_processed']     = Utilities::checkAccess('payroll-process','processed');
          $data['access_registered']    = Utilities::checkAccess('payroll-process','registered');
          $data['access_posted']        = Utilities::checkAccess('payroll-process','posted');
          $data['access_approved']      = Utilities::checkAccess('payroll-process','approved');

          return view('member.payroll.payroll_process_module', $data);
     }

     /* EMPLOYEE START */
     public function employee_list()
     {    
          $active_status[0]    = 1;
          $active_status[1]    = 2;
          $active_status[2]    = 3;
          $active_status[3]    = 4;
          $active_status[4]    = 5;
          $active_status[5]    = 6;
          $active_status[7]    = 7;

          $separated_status[0] = 8;
          $separated_status[1] = 9;

		$data['_active']	 = Tbl_payroll_employee_contract::employeefilter(0,0,0,date('Y-m-d'), Self::shop_id(), $active_status)->orderBy('tbl_payroll_employee_basic.payroll_employee_last_name')->paginate($this->paginate_count);
		
          // $data['_separated']					= Tbl_payroll_employee_contract::employeefilter(0,0,0,date('Y-m-d'), Self::shop_id(), $separated_status)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->paginate($this->paginate_count);

          $data['_separated'] = DB::select('select * from `tbl_payroll_employee_contract` inner join `tbl_payroll_employee_basic` 
          on `tbl_payroll_employee_basic`.`payroll_employee_id` = `tbl_payroll_employee_contract`.`payroll_employee_id` 
          left join `tbl_payroll_department` on `tbl_payroll_department`.`payroll_department_id` = `tbl_payroll_employee_contract`.`payroll_department_id` 
          left join `tbl_payroll_jobtitle` on `tbl_payroll_jobtitle`.`payroll_jobtitle_id` = `tbl_payroll_employee_contract`.`payroll_jobtitle_id` 
          left join `tbl_payroll_company` on `tbl_payroll_company`.`payroll_company_id` = `tbl_payroll_employee_basic`.`payroll_employee_company_id` 
          where (`tbl_payroll_employee_contract`.`payroll_employee_contract_date_end` >= '.date('Y-m-d').' 
          or `tbl_payroll_employee_contract`.`payroll_employee_contract_date_end` = 0000-00-00) 
          and `payroll_employee_contract_status` in (8, 9) and `tbl_payroll_employee_basic`.`shop_id` = '.Self::shop_id().' 
          and `tbl_payroll_employee_contract`.`payroll_employee_contract_archived` = 0 
          order by `tbl_payroll_employee_basic`.`payroll_employee_last_name` asc');



          /*ORIGINAL QUERY*/
          /*$data['_separated'] = DB::select('select * from `tbl_payroll_employee_contract` inner join `tbl_payroll_employee_basic` 
          on `tbl_payroll_employee_basic`.`payroll_employee_id` = `tbl_payroll_employee_contract`.`payroll_employee_id` 
          left join `tbl_payroll_department` on `tbl_payroll_department`.`payroll_department_id` = `tbl_payroll_employee_contract`.`payroll_department_id` 
          left join `tbl_payroll_jobtitle` on `tbl_payroll_jobtitle`.`payroll_jobtitle_id` = `tbl_payroll_employee_contract`.`payroll_jobtitle_id` 
          left join `tbl_payroll_company` on `tbl_payroll_company`.`payroll_company_id` = `tbl_payroll_employee_basic`.`payroll_employee_company_id` 
          where (`tbl_payroll_employee_contract`.`payroll_employee_contract_date_end` >= '.date('Y-m-d').' 
          or `tbl_payroll_employee_contract`.`payroll_employee_contract_date_end` = 0000-00-00) 
          and `payroll_employee_contract_status` in (8, 9) and `tbl_payroll_employee_basic`.`shop_id` = '.Self::shop_id().' 
          and `tbl_payroll_employee_contract`.`payroll_employee_contract_archived` = 0 
          order by `tbl_payroll_employee_basic`.`payroll_employee_first_name` asc');*/

          // dd($data['_separated']);
		
          $data['_company']                       = Payroll::company_heirarchy(Self::shop_id());
		$data['_status_active']				= Tbl_payroll_employment_status::whereIn('payroll_employment_status_id', $active_status)->orderBy('employment_status')->paginate($this->paginate_count);
          $data['_status_separated']              = Tbl_payroll_employment_status::whereIn('payroll_employment_status_id', $separated_status)->orderBy('employment_status')->paginate($this->paginate_count);
         
          return view('member.payroll.employeelist', $data);
     }   

     public function export_to_pdf_employee()
     {

          $active_status[0]    = 1;
          $active_status[1]    = 2;
          $active_status[2]    = 3;
          $active_status[3]    = 4;
          $active_status[4]    = 5;
          $active_status[5]    = 6;
          $active_status[7]    = 7;

          $separated_status[0] = 8;
          $separated_status[1] = 9;

          $data['_active']     = Tbl_payroll_employee_contract::employeefilter(0,0,0,date('Y-m-d'), Self::shop_id(), $active_status)->orderBy('tbl_payroll_employee_basic.payroll_employee_last_name')->get();
          
          $pdf = view('member.payroll.employeelist_pdf', $data);
          return Pdf_global::show_pdf($pdf, 'landscape');
     }


     /* IMPORT EMPLOYEE DATA FROM EXCEL  START*/
     public function modal_import_employee()
     {
          return view('member.payroll.modal.modal_import_employee');
     }

     public function get_201_template()
     {
          $excels['number_of_rows'] = Request::input('number_of_rows');

          $excels['data'] = ['Company*','Employee Number*','Title Name','First Name*','Middle Name*','Last Name*','Suffix Name*','ATM/Account Number','Gender (M/F)*','Birthdate','Civil Status*','Street*','City/Town*','State/Province','Country*','Zip Code', 'Contact','Email Address','Tax Status','Monthly Salary*','Daily Rate' ,'Taxable Salary','SSS Salary','HDMF Salary','PHIC Salary','Minimum Wage (Y/N)*','Department*','Position*','Start Date*','Employment Status*','SSS Number','Philhealth Number','Pagibig Number','TIN','BioData/Resume(Y/N)','Police Clearance(Y/N)','NBI(Y/N)','Health Certificate(Y/N)','School Credentials(Y/N)','Valid ID(Y/N)','Dependent Full Name(1)','Dependent Relationship(1)','Dependent Birthdate(1)','Dependent Full Name(2)','Dependent Relationship(2)','Dependent Birthdate(2)','Dependent Full Name(3)','Dependent Relationship(3)','Dependent Birthdate(3)','Dependent Full Name(4)','Dependent Relationship(4)','Dependent Birthdate(4)','Remarks'];
          // AuditTrail::record_logs("DOWNLOADED: 201 Files","Downloaded 201 FILES TEMPLATE",$this->shop_id(),"","");
          Excel::create('201 Template', function($excel) use ($excels) {

               $excel->sheet('template', function($sheet) use ($excels) {

                $data = $excels['data'];
                $number_of_rows = $excels['number_of_rows'];
                $sheet->fromArray($data, null, 'A1', false, false);
                $sheet->freezeFirstRow();

                for($row = 1, $rowcell = 2; $row <= $number_of_rows; $row++, $rowcell++)
                {

                    /* COMPANY/CLIENT ROW */
                    $client_cell = $sheet->getCell('A'.$rowcell)->getDataValidation();
                    $client_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $client_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $client_cell->setAllowBlank(false);
                    $client_cell->setShowInputMessage(true);
                    $client_cell->setShowErrorMessage(true);
                    $client_cell->setShowDropDown(true);
                    $client_cell->setErrorTitle('Input error');
                    $client_cell->setError('Value is not in list.');
                    $client_cell->setFormula1('client');


                    /* GENDER ROW */
                    $gender_cell = $sheet->getCell('I'.$rowcell)->getDataValidation();
                    $gender_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $gender_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $gender_cell->setAllowBlank(false);
                    $gender_cell->setShowInputMessage(true);
                    $gender_cell->setShowErrorMessage(true);
                    $gender_cell->setShowDropDown(true);
                    $gender_cell->setErrorTitle('Input error');
                    $gender_cell->setError('Value is not in list.');
                    $gender_cell->setFormula1('gender');


                    /* CIVIL STATUS ROW */
                    $civil_status_cell = $sheet->getCell('K'.$rowcell)->getDataValidation();
                    $civil_status_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $civil_status_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $civil_status_cell->setAllowBlank(false);
                    $civil_status_cell->setShowInputMessage(true);
                    $civil_status_cell->setShowErrorMessage(true);
                    $civil_status_cell->setShowDropDown(true);
                    $civil_status_cell->setErrorTitle('Input error');
                    $civil_status_cell->setError('Value is not in list.');
                    $civil_status_cell->setFormula1('civilstatus');

                    /* CIVIL STATUS ROW */
                    $civil_status_cell = $sheet->getCell('O'.$rowcell)->getDataValidation();
                    $civil_status_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $civil_status_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $civil_status_cell->setAllowBlank(false);
                    $civil_status_cell->setShowInputMessage(true);
                    $civil_status_cell->setShowErrorMessage(true);
                    $civil_status_cell->setShowDropDown(true);
                    $civil_status_cell->setErrorTitle('Input error');
                    $civil_status_cell->setError('Value is not in list.');
                    $civil_status_cell->setFormula1('country');


                    /* TAXT STATUS ROW */
                    $tax_status_cell = $sheet->getCell('S'.$rowcell)->getDataValidation();
                    $tax_status_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $tax_status_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $tax_status_cell->setAllowBlank(false);
                    $tax_status_cell->setShowInputMessage(true);
                    $tax_status_cell->setShowErrorMessage(true);
                    $tax_status_cell->setShowDropDown(true);
                    $tax_status_cell->setErrorTitle('Input error');
                    $tax_status_cell->setError('Value is not in list.');
                    $tax_status_cell->setFormula1('taxstatus');


                    /* MINIMUM WAGE ROW */
                    $minimum_wage_cell = $sheet->getCell('Z'.$rowcell)->getDataValidation();
                    $minimum_wage_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $minimum_wage_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $minimum_wage_cell->setAllowBlank(false);
                    $minimum_wage_cell->setShowInputMessage(true);
                    $minimum_wage_cell->setShowErrorMessage(true);
                    $minimum_wage_cell->setShowDropDown(true);
                    $minimum_wage_cell->setErrorTitle('Input error');
                    $minimum_wage_cell->setError('Value is not in list.');
                    $minimum_wage_cell->setFormula1('yesno');


                    /* DEPARTMENT ROW */
                    $department_cell = $sheet->getCell('AA'.$rowcell)->getDataValidation();
                    $department_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $department_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $department_cell->setAllowBlank(false);
                    $department_cell->setShowInputMessage(true);
                    $department_cell->setShowErrorMessage(true);
                    $department_cell->setShowDropDown(true);
                    $department_cell->setErrorTitle('Input error');
                    $department_cell->setError('Value is not in list.');
                    $department_cell->setFormula1('department');

                    /* POSITION ROW */
                    $position_cell = $sheet->getCell('AB'.$rowcell)->getDataValidation();
                    $position_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $position_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $position_cell->setAllowBlank(false);
                    $position_cell->setShowInputMessage(true);
                    $position_cell->setShowErrorMessage(true);
                    $position_cell->setShowDropDown(true);
                    $position_cell->setErrorTitle('Input error');
                    $position_cell->setError('Value is not in list.');
                    $position_cell->setFormula1('position');


                    /* EMPLOYMENT STATUS ROW */
                    $employement_status_cell = $sheet->getCell('AD'.$rowcell)->getDataValidation();
                    $employement_status_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $employement_status_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $employement_status_cell->setAllowBlank(false);
                    $employement_status_cell->setShowInputMessage(true);
                    $employement_status_cell->setShowErrorMessage(true);
                    $employement_status_cell->setShowDropDown(true);
                    $employement_status_cell->setErrorTitle('Input error');
                    $employement_status_cell->setError('Value is not in list.');
                    $employement_status_cell->setFormula1('status');


                    /* NBI ROW */
                    $nbiyesno_cell = $sheet->getCell('AI'.$rowcell)->getDataValidation();
                    $nbiyesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $nbiyesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $nbiyesno_cell->setAllowBlank(false);
                    $nbiyesno_cell->setShowInputMessage(true);
                    $nbiyesno_cell->setShowErrorMessage(true);
                    $nbiyesno_cell->setShowDropDown(true);
                    $nbiyesno_cell->setErrorTitle('Input error');
                    $nbiyesno_cell->setError('Value is not in list.');
                    $nbiyesno_cell->setFormula1('yesno');


                    /* HEALTH CERTIFICATE ROW */
                    $healthcert_yesno_cell = $sheet->getCell('AJ'.$rowcell)->getDataValidation();
                    $healthcert_yesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $healthcert_yesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $healthcert_yesno_cell->setAllowBlank(false);
                    $healthcert_yesno_cell->setShowInputMessage(true);
                    $healthcert_yesno_cell->setShowErrorMessage(true);
                    $healthcert_yesno_cell->setShowDropDown(true);
                    $healthcert_yesno_cell->setErrorTitle('Input error');
                    $healthcert_yesno_cell->setError('Value is not in list.');
                    $healthcert_yesno_cell->setFormula1('yesno');


                    /* BIODATA ROW */
                    $boidata_yesno_cell = $sheet->getCell('AK'.$rowcell)->getDataValidation();
                    $boidata_yesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $boidata_yesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $boidata_yesno_cell->setAllowBlank(false);
                    $boidata_yesno_cell->setShowInputMessage(true);
                    $boidata_yesno_cell->setShowErrorMessage(true);
                    $boidata_yesno_cell->setShowDropDown(true);
                    $boidata_yesno_cell->setErrorTitle('Input error');
                    $boidata_yesno_cell->setError('Value is not in list.');
                    $boidata_yesno_cell->setFormula1('yesno');

                     /* BIODATA ROW */
                    $boidata_yesno_cell = $sheet->getCell('AL'.$rowcell)->getDataValidation();
                    $boidata_yesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $boidata_yesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $boidata_yesno_cell->setAllowBlank(false);
                    $boidata_yesno_cell->setShowInputMessage(true);
                    $boidata_yesno_cell->setShowErrorMessage(true);
                    $boidata_yesno_cell->setShowDropDown(true);
                    $boidata_yesno_cell->setErrorTitle('Input error');
                    $boidata_yesno_cell->setError('Value is not in list.');
                    $boidata_yesno_cell->setFormula1('yesno');

                     /* BIODATA ROW */
                    $boidata_yesno_cell = $sheet->getCell('AM'.$rowcell)->getDataValidation();
                    $boidata_yesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $boidata_yesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $boidata_yesno_cell->setAllowBlank(false);
                    $boidata_yesno_cell->setShowInputMessage(true);
                    $boidata_yesno_cell->setShowErrorMessage(true);
                    $boidata_yesno_cell->setShowDropDown(true);
                    $boidata_yesno_cell->setErrorTitle('Input error');
                    $boidata_yesno_cell->setError('Value is not in list.');
                    $boidata_yesno_cell->setFormula1('yesno');

                     /* BIODATA ROW */
                    $boidata_yesno_cell = $sheet->getCell('AN'.$rowcell)->getDataValidation();
                    $boidata_yesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $boidata_yesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $boidata_yesno_cell->setAllowBlank(false);
                    $boidata_yesno_cell->setShowInputMessage(true);
                    $boidata_yesno_cell->setShowErrorMessage(true);
                    $boidata_yesno_cell->setShowDropDown(true);
                    $boidata_yesno_cell->setErrorTitle('Input error');
                    $boidata_yesno_cell->setError('Value is not in list.');
                    $boidata_yesno_cell->setFormula1('yesno');

                    /* DEPENDENT RELATIONSHIP 1 */
                    $boidata_yesno_cell = $sheet->getCell('AP'.$rowcell)->getDataValidation();
                    $boidata_yesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $boidata_yesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $boidata_yesno_cell->setAllowBlank(false);
                    $boidata_yesno_cell->setShowInputMessage(true);
                    $boidata_yesno_cell->setShowErrorMessage(true);
                    $boidata_yesno_cell->setShowDropDown(true);
                    $boidata_yesno_cell->setErrorTitle('Input error');
                    $boidata_yesno_cell->setError('Value is not in list.');
                    $boidata_yesno_cell->setFormula1('relotionship');

                    /* DEPENDENT RELATIONSHIP 2 */
                    $boidata_yesno_cell = $sheet->getCell('AS'.$rowcell)->getDataValidation();
                    $boidata_yesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $boidata_yesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $boidata_yesno_cell->setAllowBlank(false);
                    $boidata_yesno_cell->setShowInputMessage(true);
                    $boidata_yesno_cell->setShowErrorMessage(true);
                    $boidata_yesno_cell->setShowDropDown(true);
                    $boidata_yesno_cell->setErrorTitle('Input error');
                    $boidata_yesno_cell->setError('Value is not in list.');
                    $boidata_yesno_cell->setFormula1('relotionship');

                    /* DEPENDENT RELATIONSHIP 3 */
                    $boidata_yesno_cell = $sheet->getCell('AV'.$rowcell)->getDataValidation();
                    $boidata_yesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $boidata_yesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $boidata_yesno_cell->setAllowBlank(false);
                    $boidata_yesno_cell->setShowInputMessage(true);
                    $boidata_yesno_cell->setShowErrorMessage(true);
                    $boidata_yesno_cell->setShowDropDown(true);
                    $boidata_yesno_cell->setErrorTitle('Input error');
                    $boidata_yesno_cell->setError('Value is not in list.');
                    $boidata_yesno_cell->setFormula1('relotionship');

                    /* DEPENDENT RELATIONSHIP 4 */
                    $boidata_yesno_cell = $sheet->getCell('AY'.$rowcell)->getDataValidation();
                    $boidata_yesno_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $boidata_yesno_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $boidata_yesno_cell->setAllowBlank(false);
                    $boidata_yesno_cell->setShowInputMessage(true);
                    $boidata_yesno_cell->setShowErrorMessage(true);
                    $boidata_yesno_cell->setShowDropDown(true);
                    $boidata_yesno_cell->setErrorTitle('Input error');
                    $boidata_yesno_cell->setError('Value is not in list.');
                    $boidata_yesno_cell->setFormula1('relotionship');
               }

          });

            /* DATA VALIDATION (REFERENCE FOR DROPDOWN LIST) */
            $excel->sheet('reference', function($sheet) {

                $_company          = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('payroll_company_name')->get();

                $_status           = Tbl_payroll_employment_status::get();
                $_department       = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
                $_position         = Tbl_payroll_jobtitle::sel(Self::shop_id())->orderBy('payroll_jobtitle_name')->get();

                $_country          = Tbl_country::get();

                /* COMPANY/CLIENT REFERENCES */
                $sheet->SetCellValue("A1", "Client");
                $client_number = 2;
                foreach($_company as $company)
                {
                    $sheet->SetCellValue("A".$client_number, $company->payroll_company_name);
                    $client_number++;
                }
                $client_number--;

                /* EMPLOYMENT STATUS REFERENCES */
                $sheet->SetCellValue("B1", "Employment Status");
                $emp_status_number = 2;
                foreach($_status as $status)
                {
                    $sheet->SetCellValue("B".$emp_status_number, $status->employment_status);
                    $emp_status_number++;
                }
                $emp_status_number--;

                /* DEPARTMENT REFERENCE */
                $sheet->SetCellValue("G1", "Department");
                $department_number = 2;
                foreach($_department as $department)
                {
                    $sheet->SetCellValue("G".$department_number, $department->payroll_department_name);
                    $department_number++;
                }
                $department_number--;

                /* POSITION/JOB TITLE REFERENCE */
                $sheet->SetCellValue("H1", "Position");
                $position_number = 2;
                foreach($_position as $position)
                {
                    $sheet->SetCellValue("H".$position_number, $position->payroll_jobtitle_name);
                    $position_number++;
                }
                $position_number--;


                $sheet->SetCellValue('J1','Country');
                $country_number = 2;
                foreach($_country as $country)
                {
                    $sheet->SetCellValue("J".$country_number, $country->country_name);
                    $country_number++;
                }
                $country_number--;

                /* GENDER REFERENCE */
                $sheet->SetCellValue("C1", "Gender");
                $sheet->SetCellValue("C2", "male");
                $sheet->SetCellValue("C3", "female");

                /* YES OR NO REFERENCE */
                $sheet->SetCellValue("D1", "Yes or No");
                $sheet->SetCellValue("D2", "Y");
                $sheet->SetCellValue("D3", "N");

                /* TAX STATUS REFERENCE */
                $sheet->SetCellValue("E1", "Tax Status");
                $sheet->SetCellValue("E2", "Z");
                $sheet->SetCellValue("E3", "S/ME");
                $sheet->SetCellValue("E4", "S1/ME1");
                $sheet->SetCellValue("E5", "S2/ME2");
                $sheet->SetCellValue("E6", "S3/ME3");
                $sheet->SetCellValue("E7", "S4/ME4");

                /* CIVIL STATUS REFERENCE */
                $sheet->SetCellValue("F1", "Civil Status");
                $sheet->SetCellValue("F2", "Single");
                $sheet->SetCellValue("F3", "Married");
                $sheet->SetCellValue("F4", "Divorced");
                $sheet->SetCellValue("F5", "Separated");
                $sheet->SetCellValue("F6", "Widowed");

                /* RELATIONSHIP */
                $sheet->SetCellValue("I1", "Relationship");
                $sheet->SetCellValue("I2", "Father");
                $sheet->SetCellValue("I3", "Mother");
                $sheet->SetCellValue("I4", "Spouse");
                $sheet->SetCellValue("I5", "Child");


                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'client', $sheet, 'A2:A'.$client_number
                    )
                );

                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'status', $sheet, 'B2:B'.$emp_status_number
                    )
                );

                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'gender', $sheet, 'C2:C3'
                    )
                );

                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'yesno', $sheet, 'D2:D3'
                    )
                );

                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'taxstatus', $sheet, 'E2:E7'
                    )
                );

                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'civilstatus', $sheet, 'F2:F6'
                    )
                );

                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'department', $sheet, 'G2:G'.$department_number
                    )
                );

                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'position', $sheet, 'H2:H'.$position_number
                    )
                );

                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'relotionship', $sheet, 'I2:I5'
                    )
                );

                    $sheet->_parent->addNamedRange(
                         new \PHPExcel_NamedRange(
                    'country', $sheet, 'J2:J'.$country_number
                    )
                    );


            });


        })->download('xlsx');
     }

     public function import_201_template()
     {
          $file = Input::file('file');
          // die(var_dump(Request::all()));
          $_data = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->all();
          $first = $_data[0]; 
          /* check index exist */
          
          if(isset($first['company']) && isset($first['first_name']) && isset($first['department']) && isset($first['start_date']))
          {    
               $count = 0;

               foreach($_data as $data)
               {
                   
                    $count_employee = Tbl_payroll_employee_basic::where('payroll_employee_company_id',Self::getid($data['company'], 'company'))
                                                               ->where('payroll_employee_first_name',Self::nullableToString($data['first_name']))
                                                               ->where('payroll_employee_middle_name', Self::nullableToString($data['middle_name']))
                                                               ->where('payroll_employee_last_name',Self::nullableToString($data['last_name']))
                                                               ->where('shop_id',Self::shop_id())
                                                               ->count();
                    
                    if($count_employee == 0)
                    {
                         /* EMPLOYEE BASIC INSERT START */
                         $insert['shop_id']                           = Self::shop_id();
                         $insert['payroll_employee_company_id']       = Self::getid($data['company'], 'company');
                         $insert['payroll_employee_title_name']       = Self::nullableToString($data['title_name']);
                         $insert['payroll_employee_first_name']       = Self::nullableToString($data['first_name']);
                         $insert['payroll_employee_middle_name']      = Self::nullableToString($data['middle_name']);
                         $insert['payroll_employee_last_name']        = Self::nullableToString($data['last_name']);
                         $insert['payroll_employee_suffix_name']      = Self::nullableToString($data['suffix_name']);
                         $insert['payroll_employee_display_name']     = Self::nullableToString($data['title_name']).' '.Self::nullableToString($data['first_name']).' '.Self::nullableToString($data['middle_name']).' '.Self::nullableToString($data['last_name']).' '.Self::nullableToString($data['suffix_name']);

                         $insert['payroll_employee_contact']          = Self::nullableToString($data['contact']);
                         $insert['payroll_employee_email']            = Self::nullableToString($data['email_address']);
                         $insert['payroll_employee_birthdate']        = Self::nullableToString($data['birthdate']);
                         $insert['payroll_employee_gender']           = Self::nullableToString($data['gender_mf']);
                         $insert['payroll_employee_number']           = Self::nullableToString($data['employee_number']);
                         $insert['payroll_employee_atm_number']       = Self::nullableToString($data['atmaccount_number']);
                         $insert['payroll_employee_street']           = Self::nullableToString($data['street']);
                         $insert['payroll_employee_city']             = Self::nullableToString($data['citytown']);
                         $insert['payroll_employee_state']            = Self::nullableToString($data['stateprovince']);
                         $insert['payroll_employee_zipcode']          = Self::nullableToString($data['zip_code']);
                         $insert['payroll_employee_country']          = Self::getid($data['country'], 'country');
                         $insert['payroll_employee_tax_status']       = Self::nullableToString($data['tax_status']);
                         $insert['payroll_employee_tin']              = Self::nullableToString($data['tin']);
                         $insert['payroll_employee_sss']              = Self::nullableToString($data['sss_number']);
                         $insert['payroll_employee_pagibig']          = Self::nullableToString($data['pagibig_number']);
                         $insert['payroll_employee_philhealth']       = Self::nullableToString($data['philhealth_number']);
                         $insert['payroll_employee_remarks']          = Self::nullableToString($data['remarks']);


                         if(Self::getid($data['company'], 'company') != null && Self::checkemployee_exist($insert) == 0)
                         {
                              
                              // dd($insert);

                              $payroll_employee_id = Tbl_payroll_employee_basic::insertGetId($insert);
                              // $new_data = AuditTrail::get_table_data("tbl_payroll_employee_basic","payroll_employee_id",$payroll_employee_id);
                              // AuditTrail::record_logs("CREATED: Payroll Employee","Created Payroll Employee With Employee Name: ".Self::nullableToString($data['title_name']).' '.Self::nullableToString($data['first_name']).' '.Self::nullableToString($data['middle_name']).' '.Self::nullableToString($data['last_name']).' '.Self::nullableToString($data['suffix_name']),$payroll_employee_id,"",serialized($new_data));
                              /* EMPLOYEE BASIC INSERT END */

                              /*   EMPLOYEE CONTRACT START */
                              $insert_contract['payroll_employee_id']                          = $payroll_employee_id;
                              $insert_contract['payroll_department_id']                        = Self::getid($data['department'],'department');
                              $insert_contract['payroll_jobtitle_id']                          = Self::getid($data['position'],'jobtitle');
                              $insert_contract['payroll_employee_contract_date_hired']    = Self::nullableToString($data['start_date']);
                              $insert_contract['payroll_employee_contract_status']        = Self::getid($data['employment_status'],'employment_status');
                              
                              $payroll_contract_id=Tbl_payroll_employee_contract::insertGetId($insert_contract);
                              // $new_data = AuditTrail::get_table_data("tbl_payroll_employee_basic","payroll_employee_id",$payroll_employee_id);
                              // AuditTrail::record_logs("CREATED: Payroll Contract","Payroll Employee Contract Employee ID #".$payroll_employee_id,$payroll_contract_id,"",$new_data);

                              /*   EMPLOYEE CONTRACT END */


                              /* EMPLOYEE SALARY START */
                              $insert_salary['payroll_employee_id']                            = $payroll_employee_id;
                              $insert_salary['payroll_employee_salary_effective_date']    = Self::nullableToString($data['start_date']);
                              $insert_salary['payroll_employee_salary_minimum_wage']           = Self::yesNotoInt($data['minimum_wage_yn']);
                              $insert_salary['payroll_employee_salary_monthly']                = Self::nullableToString($data['monthly_salary'],'int');
                              $insert_salary['payroll_employee_salary_daily']             = Self::nullableToString($data['daily_rate'],'int');
                              $insert_salary['payroll_employee_salary_taxable']                = Self::nullableToString($data['taxable_salary'],'int');
                              $insert_salary['payroll_employee_salary_sss']                    = Self::nullableToString($data['sss_salary'],'int');
                              $insert_salary['payroll_employee_salary_pagibig']                = Self::nullableToString($data['hdmf_salary'],'int');
                              $insert_salary['payroll_employee_salary_philhealth']        = Self::nullableToString($data['phic_salary'],'int');
                              // AuditTrail::record_logs("ADDED: Payroll Employee Salary","Added Payroll Employee Salary with employee ID #".$payroll_employee_id,$payroll_employee_id,"","");
                              Tbl_payroll_employee_salary::insert($insert_salary);
                              
                              /* EMPLOYEE SALARY END */

                              /* EMPLOYEE  REQUIREMENTS START*/
               
                              $insert_requirement['payroll_employee_id']        = $payroll_employee_id;
                              $insert_requirement['has_resume']                 = Self::yesNotoInt($data['biodataresumeyn'],'int');
                              $insert_requirement['has_police_clearance']       = Self::yesNotoInt($data['police_clearanceyn'],'int');
                              $insert_requirement['has_nbi']                    = Self::yesNotoInt($data['nbiyn'],'int');
                              $insert_requirement['has_health_certificate']     = Self::yesNotoInt($data['health_certificateyn'],'int');
                              $insert_requirement['has_school_credentials']     = Self::yesNotoInt($data['school_credentialsyn'],'int');
                              $insert_requirement['has_valid_id']               = Self::yesNotoInt($data['valid_idyn'],'int');
                              // AuditTrail::record_logs("Adding Employee Requirements","Adding Payroll Employee Requirements with Employee ID #".$payroll_employee_id,$payroll_employee_id,"",serialize($new_data));
                              Tbl_payroll_employee_requirements::insert($insert_requirement);
                              /* EMPLOYEE  REQUIREMENTS END*/
                              
                              

                              /* EMPLOYEE DEPENDENT START */
                              $insert_dependent = array();
                              $temp = '';
                              for($i = 1; $i <= 4; $i++)
                              {
                                   if($data['dependent_full_name'.$i] != null || $data['dependent_full_name'.$i] != "")
                                   {
                                        $temp['payroll_employee_id']            = $payroll_employee_id;
                                        $temp['payroll_dependent_name']         = Self::nullableToString($data['dependent_full_name'.$i]);
                                        $temp['payroll_dependent_relationship'] = Self::nullableToString($data['dependent_relationship'.$i]);
                                        $temp['payroll_dependent_birthdate']    = Self::nullableToString($data['dependent_birthdate'.$i]);
                                        array_push($insert_dependent, $temp);
                                   }
                              }
                              
                              if(!empty($insert_dependent))
                              {
                                   Tbl_payroll_employee_dependent::insert($insert_dependent);
                                   // AuditTrail::record_logs("CREATED: Payroll Employee Dependent","Payroll Employee Dependent #".$paroll_employee_id,$paroll_employee_id,"","");
                              }
                              
                              $count++;
                         }
                         
                         /* EMPLOYEE DEPENDENT END */
                    }
                    
               }    

               $message = '<center><b><span class="color-green">'.$count.' Employee/s has been inserted.</span></b></center>';
               $return['status'] = 'success';
               if($count == 0)
               {
                    $message = '<center><b><span class="color-gray">There is nothing to insert</span></b></center>';
                    $return['status'] = 'none';
               }
               $return['message'] = $message;


               return $return;
          }
          else
          {
               $return['status']   = 'error';
               $return['message']  = '<center><b><span class="color-red">Wrong file Format</span></b></center>';
               return $return;
          }
     }

     public function getid($str_name = '', $str_param = '')
     {
          $id = 0;

          switch ($str_param) {
               case 'country':
                    $id = Tbl_country::where('country_name', $str_name)->value('country_id');
                    // return $id;
                    if($id == null)
                    {
                         $id = 420;
                    }
                    break;

               case 'company':
                    $id = Tbl_payroll_company::where('payroll_company_name', $str_name)->where('shop_id', Self::shop_id())->value('payroll_company_id');
                    // return $id;/

                    break;

               case 'department':
                    $id = Tbl_payroll_department::where('payroll_department_name', $str_name)->where('shop_id', Self::shop_id())->value('payroll_department_id');
                    // return $id;
                    break;

               case 'jobtitle':
                    $id = Tbl_payroll_jobtitle::where('payroll_jobtitle_name', $str_name)->where('shop_id', Self::shop_id())->value('payroll_jobtitle_id');
                    // return $id;
                    break;

               case 'employment_status':
                    $id = Tbl_payroll_employment_status::where('employment_status', $str_name)->value('payroll_employment_status_id');
                    // return $id;
                    break;
               
               default:
                    $id = 0;
                    // return $id;
                    break;

          }

          if($id == null)
          {    
               $id = 0;
          }

          return $id;
     }

     public function checkemployee_exist($data = array())
     {
          $check['payroll_employee_company_id'] = $data['payroll_employee_company_id'];
          $check['payroll_employee_title_name'] = $data['payroll_employee_title_name'];
          $check['payroll_employee_first_name'] = $data['payroll_employee_first_name'];
          $check['payroll_employee_middle_name'] = $data['payroll_employee_middle_name'];
          $check['payroll_employee_last_name'] = $data['payroll_employee_last_name'];
          $check['payroll_employee_suffix_name'] = $data['payroll_employee_suffix_name'];
          $check['payroll_employee_number'] = $data['payroll_employee_number'];
          
          return Tbl_payroll_employee_basic::checkexist($check)->count();
     }

     public function nullableToString($data = null, $output = 'string')
     {

          if($data == null && $output == 'string')
          {
               $data = '';
          }
          else if($data == null && $output == 'int')
          {
               $data = 0;
          }

          return $data;
     }

     public function yesNotoInt($stryn = 'Y')
     {
          $int = 0;
          $stryn = strtoupper($stryn);
          if($stryn == 'Y' || $stryn == 'YES' || $stryn == 'TRUE')
          {
               $int = 1;
          }
          return $int;
     }

     /* IMPORT EMPLOYEE DATA FROM EXCEL END */

     public function modal_create_employee()
     {
          // $data['_company'] = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_company']             = Payroll::company_heirarchy(Self::shop_id());
          $data['employement_status']   = Tbl_payroll_employment_status::get();
          $data['tax_status']           = Tbl_payroll_tax_status::get();
          $data['civil_status']         = Tbl_payroll_civil_status::get();
          $data['_country']             = Tbl_country::orderBy('country_name')->get();
          $data['_department']          = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          $data['_group']               = Tbl_payroll_group::sel(Self::shop_id())->orderBy('payroll_group_code')->get();
          $data['_allowance']           = Tbl_payroll_allowance::sel(Self::shop_id())->orderBy('payroll_allowance_name')->get();
          $data['_deduction']           = Tbl_payroll_deduction::seldeduction(Self::shop_id())->orderBy('payroll_deduction_name')->get();
          $data['_leave']               = Tbl_payroll_leave_temp::sel(Self::shop_id())->orderBy('payroll_leave_temp_name')->get();
          $data['_journal_tag']         = Tbl_payroll_journal_tag::gettag(Self::shop_id())->orderBy('tbl_chart_of_account.account_name')->get();
          $data['_branch']              = Tbl_payroll_branch_location::getdata(Self::shop_id())->orderBy('branch_location_name')->get();
          $data['_shift']               = Tbl_payroll_shift_code::getshift(Self::shop_id())->orderBy('shift_code_name')->get();
		return view("member.payroll.modal.modal_create_employee", $data);
	}

     public function shift_view()
     {
          $id = Request::input('id');
          $data = Self::shift_sorting($id);
          return view('member.payroll.misc.shift_view_multiple', $data);
     }

     public function employee_updload_requirements()
     {
          $file = Request::file('file');
          // dd($file);

          $requirement_original_name    = $file->getClientOriginalName();
          $requirement_extension_name   = $file->getClientOriginalExtension();
          $requirement_mime_type        = $file->getMimeType();

          $requirement_new_name         = value(function() use ($file){
               $filename = str_random(10). date('ymdhis') . '.' . $file->getClientOriginalExtension();
               return strtolower($filename);
             });

          $path = '/assets/payroll/employee_requirements';
          if (!file_exists($path)) {
              mkdir($path, 0777, true);
          }
          $upload_success = $file->move(public_path($path), $requirement_new_name);

          $data = array();
          $status = 'error';
          if($upload_success)
          {

               $insert['shop_id']                                = Self::shop_id();
               $insert['payroll_requirements_path']              = $path.'/'.$requirement_new_name;
               $insert['payroll_requirements_original_name']     = $requirement_original_name;
               $insert['payroll_requirements_extension_name']    = $requirement_extension_name;
               $insert['payroll_requirements_mime_type']         = $requirement_mime_type;
               $insert['payroll_requirements_date_upload']       = Carbon::now();

               $payroll_requirements_id = Tbl_payroll_requirements::insertGetId($insert);
               
               $data['path']                      = $path.'/'.$requirement_new_name;
               $data['original_name']             = $requirement_original_name;
               $data['extension']                 = $requirement_extension_name;
               $data['mime_type']                 = $requirement_mime_type;
               $data['payroll_requirements_id'] = $payroll_requirements_id;
               $status = 'success';
          }
        

          $return['status'] = $status;
          $return['data']        = $data;

          return json_encode($return);

	}


	public function remove_employee_requirement()
	{
		$payroll_requirements_id = Request::input("content");
		$path = Tbl_payroll_requirements::where('payroll_requirements_id',$payroll_requirements_id)->value('payroll_requirements_path');
		$re_del = Tbl_payroll_requirements::where('payroll_requirements_id',$payroll_requirements_id)->first();
          Tbl_payroll_requirements::where('payroll_requirements_id',$payroll_requirements_id)->delete();
          AuditTrail::record_logs("Delete Payroll Requirements","Payroll Requirements ID #".$payroll_requirements_id." and Name: ".$re_del->payroll_requirements_original_name,$payroll_requirements_id,"","");
	}

	public function modal_employee_save()
	{
		/* employee basic info */
		$insert['shop_id']						= Self::shop_id();
		$insert['payroll_employee_title_name'] 		= Request::input('payroll_employee_title_name');
		$insert['payroll_employee_first_name'] 		= Request::input('payroll_employee_first_name');
		$insert['payroll_employee_middle_name'] 	= Request::input('payroll_employee_middle_name');
		$insert['payroll_employee_last_name'] 		= Request::input('payroll_employee_last_name');
		$insert['payroll_employee_suffix_name'] 	= Request::input('payroll_employee_suffix_name');
		$insert['payroll_employee_number'] 	     = Request::input('payroll_employee_number');
          $insert['payroll_employee_biometric_number'] = Request::input('payroll_employee_biometric_number');
		$insert['payroll_employee_atm_number'] 		= Request::input('payroll_employee_atm_number');
		$insert['payroll_employee_company_id'] 		= Request::input('payroll_employee_company_id');
		$insert['payroll_employee_contact'] 		= Request::input('payroll_employee_contact');
		$insert['payroll_employee_email'] 			= Request::input('payroll_employee_email');
		$insert['payroll_employee_display_name'] 	= Request::input('payroll_employee_display_name');
		$insert['payroll_employee_gender'] 	     = Request::input('payroll_employee_gender');
		$insert['payroll_employee_birthdate'] 		= date('Y-m-d',strtotime(Request::input('payroll_employee_birthdate')));
		$insert['payroll_employee_street'] 	     = Request::input('payroll_employee_street');
		$insert['payroll_employee_city'] 			= Request::input('payroll_employee_city');
		$insert['payroll_employee_state'] 			= Request::input('payroll_employee_state');
		$insert['payroll_employee_zipcode'] 		= Request::input('payroll_employee_zipcode');
		$insert['payroll_employee_country'] 		= Request::input('payroll_employee_country');
		$insert['payroll_employee_tax_status'] 		= Request::input('payroll_employee_tax_status');
		$insert['payroll_employee_tin'] 			= Request::input('payroll_employee_tin');
		$insert['payroll_employee_sss'] 			= Request::input('payroll_employee_sss');
		$insert['payroll_employee_philhealth'] 		= Request::input('payroll_employee_philhealth');
		$insert['payroll_employee_pagibig'] 		= Request::input('payroll_employee_pagibig');
		$insert['payroll_employee_remarks'] 		= Request::input('payroll_employee_remarks');
          $insert['branch_location_id']                = Request::input('branch_location_id') != null ? Request::input('branch_location_id') : 0;
          $insert['shift_code_id']                     = Request::input('shift_code_id') != null ? Request::input('shift_code_id') : 0;
		
          if (Request::input('payroll_jobtitle_id') != null)
          {
               $payroll_employee_id = Tbl_payroll_employee_basic::insertGetId($insert);
          }
     
		/* employee contract */
		$insert_contract['payroll_employee_id']					= $payroll_employee_id;
		$insert_contract['payroll_department_id'] 				= Request::input("payroll_department_id");
		$insert_contract['payroll_jobtitle_id'] 				= Request::input("payroll_jobtitle_id");
		$insert_contract['payroll_employee_contract_date_hired'] 	= Request::input("payroll_employee_contract_date_hired");
		$insert_contract['payroll_employee_contract_date_end'] 	= Request::input("payroll_employee_contract_date_end");
		$insert_contract['payroll_employee_contract_status'] 		= Request::input("payroll_employee_contract_status");
		$insert_contract['payroll_group_id'] 					= Request::input("payroll_group_id");
          Tbl_payroll_employee_contract::insert($insert_contract);

          if(date('Y-m-d',strtotime($insert_contract['payroll_employee_contract_date_hired'])) >= date('Y-m-d'))
          {
               $status = Tbl_payroll_employment_status::where('payroll_employment_status_id',Request::input("payroll_employee_contract_status"))->value('employment_status');

               $insert_manpower_report['payroll_employee_id']         = $payroll_employee_id;
               $insert_manpower_report['shop_id']                     = Self::shop_id();
               $insert_manpower_report['date_filed']                  = date('Y-m-d');
               $insert_manpower_report['employment_status']           = $status;
               
               Tbl_payroll_manpower_report::insert($insert_manpower_report);
          }


		/* employee salary details */
		$insert_salary['payroll_employee_id'] 					= $payroll_employee_id;
		$insert_salary['payroll_employee_salary_effective_date'] 	= date('Y-m-d',strtotime(Request::input('payroll_employee_contract_date_hired')));
		$payroll_employee_salary_minimum_wage = 0;
		if(Request::has('payroll_employee_salary_minimum_wage'))
		{
			$payroll_employee_salary_minimum_wage 				= Request::input('payroll_employee_salary_minimum_wage');
		}


		$insert_salary['payroll_employee_salary_minimum_wage'] 	= $payroll_employee_salary_minimum_wage;
		$insert_salary['payroll_employee_salary_monthly'] 		= Request::input('payroll_employee_salary_monthly');
		$insert_salary['payroll_employee_salary_daily'] 			= Request::input('payroll_employee_salary_daily');
          $insert_salary['payroll_employee_salary_hourly']            = Request::input('payroll_employee_salary_hourly');
		$insert_salary['payroll_employee_salary_taxable'] 		= Request::input('payroll_employee_salary_taxable');
		$insert_salary['payroll_employee_salary_sss'] 			= Request::input('payroll_employee_salary_sss');
		$insert_salary['payroll_employee_salary_pagibig'] 		= Request::input('payroll_employee_salary_pagibig');
		$insert_salary['payroll_employee_salary_philhealth'] 		= Request::input('payroll_employee_salary_philhealth');
		$insert_salary['payroll_employee_salary_cola']			= Request::input('payroll_employee_salary_cola');
          $insert_salary['monthly_cola']                              = Request::has('monthly_cola') ? Request::input('monthly_cola') : 0;
          $insert_salary['tbl_payroll_employee_custom_compute']       = Request::has('tbl_payroll_employee_custom_compute') ? Request::input('tbl_payroll_employee_custom_compute') : 0;
          
          
          $is_deduct_tax_default        = 0;
          $deduct_tax_custom            = 0;
          $is_deduct_sss_default        = 0;
          $deduct_sss_custom            = Request::input('deduct_sss_custom');
          $is_deduct_philhealth_default = 0;
          $deduct_philhealth_custom     = Request::input('deduct_philhealth_custom');
          $is_deduct_pagibig_default    = 0;
          $deduct_pagibig_custom        = Request::input('deduct_pagibig_custom');

          if(Request::has('is_deduct_tax_default'))
          {
               $is_deduct_tax_default   = Request::input('is_deduct_tax_default');
               $deduct_tax_custom       = 0;
          }

          if(Request::has('is_deduct_sss_default'))
          {
               $is_deduct_sss_default   = Request::input('is_deduct_sss_default');
               $deduct_sss_custom       = 0;
          }

          if(Request::has('is_deduct_philhealth_default'))
          {
               $is_deduct_philhealth_default = Request::input('is_deduct_philhealth_default');
               $deduct_philhealth_custom     = 0;
          }

          if(Request::has('is_deduct_pagibig_default'))
          {
               $is_deduct_pagibig_default    = Request::input('is_deduct_pagibig_default');
               $deduct_pagibig_custom        = 0;
          }

          $insert_salary['is_deduct_tax_default']           = $is_deduct_tax_default;
          $insert_salary['deduct_tax_custom']               = $deduct_tax_custom;
          $insert_salary['is_deduct_sss_default']           = $is_deduct_sss_default;
          $insert_salary['deduct_sss_custom']               = $deduct_sss_custom;
          $insert_salary['is_deduct_philhealth_default']    = $is_deduct_philhealth_default;
          $insert_salary['deduct_philhealth_custom']        = $deduct_philhealth_custom;
          $insert_salary['is_deduct_pagibig_default']       = $is_deduct_pagibig_default;
          $insert_salary['deduct_pagibig_custom']           = $deduct_pagibig_custom;

          Tbl_payroll_employee_salary::insert($insert_salary);


          
          $has_resume = 0;
          if(Request::has('has_resume'))
          {
               $has_resume = Request::input('has_resume');
          }

          $has_police_clearance = 0;
          if(Request::has('has_police_clearance'))
          {
               $has_police_clearance = Request::input('has_police_clearance');
          }

          $has_nbi = 0;
          if(Request::has('has_nbi'))
          {
               $has_nbi = Request::input('has_nbi');
          }

          $has_health_certificate = 0;
          if(Request::has('has_health_certificate'))
          {
               $has_health_certificate = Request::input('has_health_certificate');
          }

          $has_school_credentials = 0;
          if(Request::has('has_school_credentials'))
          {
               $has_school_credentials = Request::input('has_school_credentials');
          }

          $has_valid_id = 0;
          if(Request::has('has_valid_id'))
          {
               $has_valid_id = Request::input('has_valid_id');
          }

          $insert_requirements['payroll_employee_id']                      = $payroll_employee_id;
          $insert_requirements['has_resume']                                    = $has_resume;
          $insert_requirements['resume_requirements_id']                   = Request::input('resume_requirements_id');
          $insert_requirements['has_police_clearance']                     = $has_police_clearance;
          $insert_requirements['police_clearance_requirements_id']    = Request::input('police_clearance_requirements_id');
          $insert_requirements['has_nbi']                                  = $has_nbi;
          $insert_requirements['nbi_payroll_requirements_id']         = Request::input('nbi_payroll_requirements_id');
          $insert_requirements['has_health_certificate']                   = $has_health_certificate;
          $insert_requirements['health_certificate_requirements_id']  = Request::input('health_certificate_requirements_id');
          $insert_requirements['has_school_credentials']                   = $has_school_credentials;
          $insert_requirements['school_credentials_requirements_id']  = Request::input('school_credentials_requirements_id');
          $insert_requirements['has_valid_id']                             = $has_valid_id;
          $insert_requirements['valid_id_requirements_id']            = Request::input('valid_id_requirements_id');
          
          Tbl_payroll_employee_requirements::insert($insert_requirements);
         


          $payroll_dependent_name       = Request::input('payroll_dependent_name');
          $payroll_dependent_birthdate  = Request::input('payroll_dependent_birthdate');
          $payroll_dependent_relationship = Request::input('payroll_dependent_relationship');


          $insert_dependent = array();

          $temp = "";
          foreach($payroll_dependent_name as $key => $dependent)
          {
               if($dependent != "")
               {
                    $temp['payroll_employee_id']            = $payroll_employee_id;
                    $temp['payroll_dependent_name']         = $dependent;

                    $birthdate = '';
                    if($payroll_dependent_birthdate[$key] != '')
                    {    
                         $birthdate                                   = date('Y-m-d',strtotime($payroll_dependent_birthdate[$key]));
                    }

                    $temp['payroll_dependent_birthdate']    = $birthdate;
                    $temp['payroll_dependent_relationship'] = $payroll_dependent_relationship[$key];

                    array_push($insert_dependent, $temp);
               }
          }

          Tbl_payroll_employee_dependent::insert($insert_dependent);


          /* INSERT ALLOWANCES */
          $_allowance = array();
          
          if(Request::has('allowance'))
          {
               $_allowance = Request::input('allowance');
          }

          $insert_allowance = array();
          $temp = "";
          foreach ($_allowance as $allowance) {
               $temp['payroll_allowance_id'] = $allowance;
               $temp['payroll_employee_id']  = $payroll_employee_id;
               array_push($insert_allowance, $temp);
          }
          if(!empty($insert_allowance))
          {
               Tbl_payroll_employee_allowance::insert($insert_allowance);
          }
          

          /* INSERT LEAVES */
          $_leave = array();
          if(Request::has('leave'))
          {
               $_leave = Request::input('leave');
          }

          $insert_leave = array();
          $temp = '';
          foreach($_leave as $leave)
          {
               $temp['payroll_leave_temp_id']     = $leave;
               $temp['payroll_employee_id']  = $payroll_employee_id;
               array_push($insert_leave, $temp);
          }

          if(!empty($insert_leave))
          {
               Tbl_payroll_leave_employee::insert($insert_leave);
          }

          /* INSERT DEDUCTION */
          $_deduction = array();
          if(Request::has('deduction'))
          {
               $_deduction = Request::input('deduction');
          }
          $insert_deduction = array();
          $temp = '';
          foreach($_deduction as $deduction)
          {
               $temp['payroll_deduction_id']   = $deduction;
               $temp['payroll_employee_id']    = $payroll_employee_id;
               array_push($insert_deduction, $temp);
          }
          if(!empty($insert_deduction))
          {
               Tbl_payroll_deduction_employee::insert($insert_deduction);
          }


          /* INSERT JOURNAL TAGS */
          if(Request::has('journal_tag'))
          {

               $insert_journal = array();
               foreach(Request::input('journal_tag') as $tag){
                    $temp_journal['payroll_employee_id']    = $payroll_employee_id;
                    $temp_journal['payroll_journal_tag_id'] = $tag;
                    array_push($insert_journal, $temp_journal);
               }

               if(!empty($insert_journal))
               {
                    Tbl_payroll_journal_tag_employee::insert($insert_journal);
               }
          }
          
          $record = Tbl_payroll_employee_basic::where('payroll_employee_id', $payroll_employee_id)->first();
          // AuditTrail::record_logs("Create Employee","Payroll Create Employee",$payroll_employee_id , $record, $record);

          $return['data'] = '';
          $return['status'] = 'success';
          $return['function_name'] = 'employeelist.reload_employee_list';

          return json_encode($return);

     }

     public function modal_employee_view($id)
     {

          $data["source"]               = Request::input("source_page");
          // $data['_company']               = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_company']             = Payroll::company_heirarchy(Self::shop_id());

          $data['employement_status']   = Tbl_payroll_employment_status::get();
          $data['tax_status']           = Tbl_payroll_tax_status::get();
          $data['civil_status']         = Tbl_payroll_civil_status::get();
          $data['_country']             = Tbl_country::orderBy('country_name')->get();
          $data['_department']          = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          $data['_jobtitle']            = Tbl_payroll_jobtitle::sel(Self::shop_id())->orderBy('payroll_jobtitle_name')->get();

          $data['employee']             = Tbl_payroll_employee_basic::where('payroll_employee_id',$id)->first();
         
          $data['contract']             = Tbl_payroll_employee_contract::selemployee($id)->first();

          $data['salary']               = Tbl_payroll_employee_salary::selemployee($id)->first();

          $data['requirement']          = Tbl_payroll_employee_requirements::selrequirements($id)->first();
          $data['_group']               = Tbl_payroll_group::sel(Self::shop_id())->orderBy('payroll_group_code')->get();
          $data['dependent']            = Tbl_payroll_employee_dependent::where('payroll_employee_id', $id)->get();

          $data['_allowance']           = Self::check_if_allowance_selected($id);
          $data['_deduction']           = Self::check_if_deduction_selected($id);
          $data['_leave']               = Self::check_if_leave_selected($id);

          $data['_branch']              = Tbl_payroll_branch_location::getdata(Self::shop_id())->orderBy('branch_location_name')->get();

          $_journal_tag                 = Tbl_payroll_journal_tag::gettag(Self::shop_id())->orderBy('tbl_chart_of_account.account_name')->get()->toArray();
          $data['_shift']               = Tbl_payroll_shift_code::getshift(Self::shop_id())->orderBy('shift_code_name')->get();
          
          $data['_journal_tag']         = array();

          $data['access_salary_detail'] = $access = Utilities::checkAccess('payroll-timekeeping','salary_detail');

          foreach($_journal_tag as $tag)
          {
               $count_tag = Tbl_payroll_journal_tag_employee::checkdata($id, $tag['payroll_journal_tag_id'])->count(); 
               $tag['status'] = '';
               if($count_tag == 1)
               {
                    $tag['status'] = 'checked';
               }

               array_push($data['_journal_tag'], $tag);
          }

         // dd($data);
          return view("member.payroll.modal.modal_view_employee", $data);
     }

     public function check_if_allowance_selected($employee_id = 0)
     {
          $data = array();
          $_allowance = Tbl_payroll_allowance::sel(Self::shop_id())->orderBy('payroll_allowance_name')->get()->toArray();
          foreach($_allowance as $allowance)
          {
               $allowance['status_checked'] = '';
               $check = Tbl_payroll_employee_allowance::checkallowance($employee_id, $allowance['payroll_allowance_id'])->where('payroll_employee_allowance_archived',0)->count();
               if($check == 1)
               {
                    $allowance['status_checked'] = 'checked';
               }
               array_push($data, $allowance);

          }
          return $data;
     }

     public function check_if_deduction_selected($employee_id = 0)
     {
          $data = array();
          $_deduction = Tbl_payroll_deduction::seldeduction(Self::shop_id())->orderBy('payroll_deduction_name')->get()->toArray();
          foreach($_deduction as $deduction)
          {
               $deduction['status_checked'] = '';
               $check = Tbl_payroll_deduction_employee::checkdeduction($employee_id, $deduction['payroll_deduction_id'])->where('payroll_deduction_employee_archived',0)->count();
               if($check == 1)
               {
                    $deduction['status_checked'] = 'checked';
               }
               array_push($data, $deduction);
          }
          return $data;
     }

     public function check_if_leave_selected($employee_id = 0)
     {
          $data = array();
          $_leave = Tbl_payroll_leave_temp::sel(Self::shop_id())->orderBy('payroll_leave_temp_name')->get()->toArray();
          foreach($_leave as $leave)
          {
               $leave['status_checked'] = '';
               $check = Tbl_payroll_leave_employee::checkleave($employee_id, $leave['payroll_leave_temp_id'])->where('payroll_leave_employee_is_archived',0)->count();
               if($check == 1)
               {
                    $leave['status_checked'] = 'checked';
               }
               array_push($data, $leave);
          }
          // dd($data);
          return $data;
     }

     public function modal_view_contract_list($id)
     {
          $data['_active']         = Tbl_payroll_employee_contract::contractlist($id)->get();
          $data['_archived']       = Tbl_payroll_employee_contract::contractlist($id, 1)->get();
          $data['employee_id']     = $id;
          return view('member.payroll.modal.modal_view_contract_list', $data);
     }

     public function modal_edit_contract($employee_id ,$id)
     {
          $data['employee_id']          = $employee_id;
          $data['contract']             = Tbl_payroll_employee_contract::where('payroll_employee_contract_id',$id)->first();
          $data['employement_status'] = Tbl_payroll_employment_status::get();
          $data['_department']          = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          $data['_jobtitle']            = Tbl_payroll_jobtitle::sel(Self::shop_id())->orderBy('payroll_jobtitle_name')->get();

          $data['_group']               = Tbl_payroll_group::sel(Self::shop_id())->orderBy('payroll_group_code')->get();

          return view('member.payroll.modal.modal_edit_contract', $data);
     }

     public function modal_update_contract()
     {
          $payroll_employee_contract_id                          = Request::input('payroll_employee_contract_id');
          $update['payroll_department_id']                  = Request::input('payroll_department_id');
          $update['payroll_jobtitle_id']                         = Request::input('payroll_jobtitle_id');
          $update['payroll_employee_contract_date_hired'] = date('Y-m-d',strtotime(Request::input('payroll_employee_contract_date_hired')));
          $payroll_employee_contract_date_end                    = '';

          if(Request::input('payroll_employee_contract_date_end') != '')
          {
               $payroll_employee_contract_date_end     = date('Y-m-d',strtotime(Request::input('payroll_employee_contract_date_end')));
          }
          $update['payroll_employee_contract_date_end']     = $payroll_employee_contract_date_end;
          $update['payroll_group_id']                       = Request::input('payroll_group_id');
          $update['payroll_employee_contract_status']  = Request::input('payroll_employee_contract_status');
          Tbl_payroll_employee_contract::where('payroll_employee_contract_id', $payroll_employee_contract_id)->update($update);

          $payroll_employee_id = Tbl_payroll_employee_contract::where('payroll_employee_contract_id', $payroll_employee_contract_id)->value('payroll_employee_id');
         
          $status = Tbl_payroll_employment_status::where('payroll_employment_status_id',Request::input('payroll_employee_contract_status'))->value('employment_status');
          if($status == 'Resigned')
          {
               $insert['payroll_employee_id'] = $payroll_employee_id;
               $insert['date_filed']          = date('Y-m-d');
               $insert['employment_status']   = $status;
               $insert['shop_id']             = Self::shop_id();
               Tbl_payroll_manpower_report::insert($insert);
          }
          else if(date('Y-m-d',strtotime(Request::input('payroll_employee_contract_date_hired'))) >= date('Y-m-d'))
          {
               $insert['payroll_employee_id'] = $payroll_employee_id;
               $insert['date_filed']          = date('Y-m-d');
               $insert['employment_status']   =  $status;
               $insert['shop_id']             = Self::shop_id();
               Tbl_payroll_manpower_report::insert($insert);
          }

          AuditTrail::record_logs("EDITED: Payroll Employee Contract","Updating employee Contract with Employee Contract ID #".$payroll_employee_contract_id,$payroll_employee_contract_id,"","");

          $return['status']             = 'success';
          $return['function_name']      = 'employeelist.reload_contract_list';

          return json_encode($return);
     }

     public function modal_archive_contract($archived, $payroll_employee_contract_id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $data['title']           = 'Do you really want to '.$statement.' this contract?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/employee_list/archive_contract';
          $data['id']         = $payroll_employee_contract_id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archive_contract()
     {
          $update['payroll_employee_contract_archived']     = Request::input('archived');
          $id                                          = Request::input('id');
          Tbl_payroll_employee_contract::where('payroll_employee_contract_id',$id)->update($update);
          AuditTrail::record_logs("DELETED: Employee Contract","Updating Employee Contract with ID #".$id,$id,"","");
          $return['status']             = 'success';
          $return['function_name']      = 'employeelist.reload_contract_list';
          return json_encode($return);
     }

     public function modal_create_contract($id)
     {
          $data['employee_id']          = $id;
          $data['employement_status']   = Tbl_payroll_employment_status::get();
          $data['_department']          = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          $data['_group']               = Tbl_payroll_group::sel(Self::shop_id())->orderBy('payroll_group_code')->get();
          return view('member.payroll.modal.modal_create_contract',$data);
     }

     public function modal_save_contract()
     {
          $getemployee = Tbl_payroll_employee_contract::selemployee(Request::input('payroll_employee_id'))->first();
          $update['payroll_employee_contract_archived'] = 1;
          Tbl_payroll_employee_contract::where('payroll_employee_contract_id',$getemployee->payroll_employee_contract_id)->update($update);

          $insert['payroll_employee_id']                    = Request::input('payroll_employee_id');
          $insert['payroll_department_id']                  = Request::input('payroll_department_id');
          $insert['payroll_jobtitle_id']                    = Request::input('payroll_jobtitle_id');
          $insert['payroll_employee_contract_date_hired']   = date('Y-m-d',strtotime(Request::input('payroll_employee_contract_date_hired')));

          if(Request::input('payroll_employee_contract_date_end') == '')
          {
               $insert['payroll_employee_contract_date_end']     = '';
          }
          else
          {
               $insert['payroll_employee_contract_date_end']     = date('Y-m-d',strtotime(Request::input('payroll_employee_contract_date_end')));
          }
          $insert['payroll_group_id']                       = Request::input('payroll_group_id');
          $insert['payroll_employee_contract_status']       = Request::input('payroll_employee_contract_status');
          Tbl_payroll_employee_contract::insert($insert);
          
          $response['call_function'] = 'reload';
          $response['status'] = 'success';
          
          return json_encode($response);
     }



     public function modal_salary_list($id)
     {    
          $data['_active'] = Tbl_payroll_employee_salary::salaylist($id)->get();
          $data['_archived'] = Tbl_payroll_employee_salary::salaylist($id, 1)->get();
          return view('member.payroll.modal.modal_salary_list', $data);
     }

     public function modal_create_salary_adjustment($id)
     {
          $data['employee_id'] = $id;
          return view('member.payroll.modal.modal_create_salary', $data);
     }

     public function modal_edit_salary_adjustment($id)
     {
          $data['salary'] = Tbl_payroll_employee_salary::where('payroll_employee_salary_id',$id)->first();
          return view('member.payroll.modal.modal_edit_salary', $data);
     }

     public function modal_update_salary()
     {
          $payroll_employee_salary_id                       = Request::input('payroll_employee_salary_id');
          $update['payroll_employee_salary_monthly']        = Request::input('payroll_employee_salary_monthly');
          $update['payroll_employee_salary_daily']          = Request::input('payroll_employee_salary_daily');
          $update['payroll_employee_salary_hourly']          = Request::input('payroll_employee_salary_hourly');
          $update['payroll_employee_salary_taxable']        = Request::input('payroll_employee_salary_taxable');
          $update['payroll_employee_salary_sss']            = Request::input('payroll_employee_salary_sss');
          $update['payroll_employee_salary_philhealth']     = Request::input('payroll_employee_salary_philhealth');
          $update['payroll_employee_salary_pagibig']        = Request::input('payroll_employee_salary_pagibig');
          $update['payroll_employee_salary_cola']           = Request::input('payroll_employee_salary_cola');
          $update['monthly_cola']                           = Request::input('payroll_employee_salary_monthly_cola');
          $update['tbl_payroll_employee_custom_compute']    = Request::has('tbl_payroll_employee_custom_compute') ? Request::input('tbl_payroll_employee_custom_compute') : 0;
          
          $payroll_employee_salary_effective_date           = '';
          if(Request::input('payroll_employee_salary_effective_date') != '')
          {
                $payroll_employee_salary_effective_date = date('Y-m-d',strtotime(Request::input('payroll_employee_salary_effective_date')));
          }
          
          $payroll_employee_salary_minimum_wage             = 0;
          if(Request::has('payroll_employee_salary_minimum_wage'))
          {
               $payroll_employee_salary_minimum_wage        = Request::has('payroll_employee_salary_minimum_wage');
          }
          $update['payroll_employee_salary_minimum_wage'] = $payroll_employee_salary_minimum_wage;
          $update['payroll_employee_salary_effective_date'] = $payroll_employee_salary_effective_date;

          $is_deduct_tax_default        = 0;
          $deduct_tax_custom            = 0;
          $is_deduct_sss_default        = 0;
          $deduct_sss_custom            = Request::input('deduct_sss_custom');
          $is_deduct_philhealth_default = 0;
          $deduct_philhealth_custom     = Request::input('deduct_philhealth_custom');
          $is_deduct_pagibig_default    = 0;
          $deduct_pagibig_custom        = Request::input('deduct_pagibig_custom');

          if(Request::has('is_deduct_tax_default'))
          {
               $is_deduct_tax_default   = Request::input('is_deduct_tax_default');
               $deduct_tax_custom       = 0;
          }

          if(Request::has('is_deduct_sss_default'))
          {
               $is_deduct_sss_default   = Request::input('is_deduct_sss_default');
               $deduct_sss_custom       = 0;
          }

          if(Request::has('is_deduct_philhealth_default'))
          {
               $is_deduct_philhealth_default   = Request::input('is_deduct_philhealth_default');
               $deduct_philhealth_custom       = 0;
          }

          if(Request::has('is_deduct_pagibig_default'))
          {
               $is_deduct_pagibig_default   = Request::input('is_deduct_pagibig_default');
               $deduct_pagibig_custom       = 0;
          }

          $update['is_deduct_tax_default']        =  $is_deduct_tax_default;
          $update['deduct_tax_custom']            =  $deduct_tax_custom;
          $update['is_deduct_sss_default']        =  $is_deduct_sss_default;
          $update['deduct_sss_custom']            =  $deduct_sss_custom;
          $update['is_deduct_philhealth_default'] =  $is_deduct_philhealth_default;
          $update['deduct_philhealth_custom']     =  $deduct_philhealth_custom;
          $update['is_deduct_pagibig_default']    =  $is_deduct_pagibig_default;
          $update['deduct_pagibig_custom']        =  $deduct_pagibig_custom;
          Tbl_payroll_employee_salary::where('payroll_employee_salary_id',$payroll_employee_salary_id)->update($update);
          // AuditTrail::record_logs("EDITED: Payroll Employee Salary","Updating Employee Salary with Salary ID #".$payroll_employee_salary_id,$payroll_employee_salary_id,"","");

          $return['function_name'] = 'employeelist.reload_salary_list';
          $return['status'] = 'success';
          return json_encode($return);
     }

     public function modal_archived_salary($archived, $id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $data['title']      = 'Do you really want to '.$statement.' this salary?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/employee_list/archived_salary';
          $data['id']         = $id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archived_salary()
     {
          $update['payroll_employee_salary_archived'] = Request::input('archived');
          $id                                               = Request::input('id');
          $new_data = AuditTrail::get_table_data("tbl_payroll_employee_salary","payroll_employee_salary_id",$id);
          Tbl_payroll_employee_salary::where('payroll_employee_salary_id',$id)->update($update);
          // AuditTrail::record_logs("DELETED: Payroll Employee Salary","Updating Payroll Employee with ID #".$id,$id,"",serialize($new_data));

          $return['status']             = 'success';
          $return['function_name']      = 'employeelist.reload_salary_list';
          return json_encode($return);
     }

     public function modal_save_salary()
     {
          $insert['payroll_employee_id']                    = Request::input('payroll_employee_id');
          $insert['payroll_employee_salary_monthly']        = Request::input('payroll_employee_salary_monthly');
          $insert['payroll_employee_salary_daily']          = Request::input('payroll_employee_salary_daily');
          $insert['payroll_employee_salary_taxable']        = Request::input('payroll_employee_salary_taxable');
          $insert['payroll_employee_salary_sss']            = Request::input('payroll_employee_salary_sss');
          $insert['payroll_employee_salary_philhealth']     = Request::input('payroll_employee_salary_philhealth');
          $insert['payroll_employee_salary_pagibig']        = Request::input('payroll_employee_salary_pagibig');
          $insert['monthly_cola']                           = Request::input('payroll_employee_salary_monthly_cola');

          $payroll_employee_salary_minimum_wage = 0;
          if(Request::has('payroll_employee_salary_minimum_wage'))
          {
               $payroll_employee_salary_minimum_wage = Request::input('payroll_employee_salary_minimum_wage');
          }

          $insert['payroll_employee_salary_minimum_wage'] = $payroll_employee_salary_minimum_wage;
          $insert['payroll_employee_salary_effective_date'] = date('Y-m-d',strtotime(Request::input('payroll_employee_salary_effective_date')));
          $insert['payroll_employee_salary_cola']           = Request::input('payroll_employee_salary_cola');
          $insert['monthly_cola']                           = Request::has('monthly_cola') ? Request::input('monthly_cola') : 0;
          $insert['tbl_payroll_employee_custom_compute']    = Request::has('tbl_payroll_employee_custom_compute') ? Request::input('tbl_payroll_employee_custom_compute') : 0;
          
          
          $is_deduct_tax_default        = 0;
          $deduct_tax_custom            = 0;
          $is_deduct_sss_default        = 0;
          $deduct_sss_custom            = Request::input('deduct_sss_custom');
          $is_deduct_philhealth_default = 0;
          $deduct_philhealth_custom     = Request::input('deduct_philhealth_custom');
          $is_deduct_pagibig_default    = 0;
          $deduct_pagibig_custom        = Request::input('deduct_pagibig_custom');

          if(Request::has('is_deduct_tax_default'))
          {
               $is_deduct_tax_default   = Request::input('is_deduct_tax_default');
               $deduct_tax_custom       = 0;
          }

          if(Request::has('is_deduct_sss_default'))
          {
               $is_deduct_sss_default   = Request::input('is_deduct_sss_default');
               $deduct_sss_custom       = 0;
          }

          if(Request::has('is_deduct_philhealth_default'))
          {
               $is_deduct_philhealth_default   = Request::input('is_deduct_philhealth_default');
               $deduct_philhealth_custom       = 0;
          }

          if(Request::has('is_deduct_pagibig_default'))
          {
               $is_deduct_pagibig_default   = Request::input('is_deduct_pagibig_default');
               $deduct_pagibig_custom       = 0;
          }


          $insert['is_deduct_tax_default']        = $is_deduct_tax_default;
          $insert['deduct_tax_custom']            = $deduct_tax_custom;
          $insert['is_deduct_sss_default']        = $is_deduct_sss_default;
          $insert['deduct_sss_custom']            = $deduct_sss_custom;
          $insert['is_deduct_philhealth_default'] = $is_deduct_philhealth_default;
          $insert['deduct_philhealth_custom']     = $deduct_philhealth_custom;
          $insert['is_deduct_pagibig_default']    = $is_deduct_pagibig_default;
          $insert['deduct_pagibig_custom']        = $deduct_pagibig_custom;
          $get_id = Tbl_payroll_employee_salary::insertGetId($insert);
          AuditTrail::record_logs("CREATED: Employee Salary","Inserting Employee Salary with employee ID #".$get_id,$get_id,"","");
          

          $response['call_function'] = 'reload';
          $response['status'] = 'success';
		
		return json_encode($response);
	}

	public function modal_employee_update()
	{
		$payroll_employee_id 						  = Request::input('payroll_employee_id');
		$update_basic['payroll_employee_title_name'] 	  = Request::input('payroll_employee_title_name');
		$update_basic['payroll_employee_first_name'] 	  = Request::input('payroll_employee_first_name');
		$update_basic['payroll_employee_middle_name'] 	  = Request::input('payroll_employee_middle_name');
		$update_basic['payroll_employee_last_name'] 	       = Request::input('payroll_employee_last_name');
		$update_basic['payroll_employee_suffix_name'] 	  = Request::input('payroll_employee_suffix_name');
          $update_basic['payroll_employee_number']            = Request::input('payroll_employee_number');
		$update_basic['payroll_employee_biometric_number']  = Request::input('payroll_employee_biometric_number');
		$update_basic['payroll_employee_atm_number'] 	  = Request::input('payroll_employee_atm_number');
		$update_basic['payroll_employee_company_id'] 	  = Request::input('payroll_employee_company_id');
		$update_basic['payroll_employee_contact'] 		  = Request::input('payroll_employee_contact');
		$update_basic['payroll_employee_email'] 		  = Request::input('payroll_employee_email');
		$update_basic['payroll_employee_display_name'] 	  = Request::input('payroll_employee_display_name');
  
          $update_basic['branch_location_id']                 = Request::input('branch_location_id') != null ? Request::input('branch_location_id') : 0;
          $update_basic['shift_code_id']                      = Request::input('shift_code_id') != null ? Request::input('shift_code_id') : 0;
  
		$update_basic['payroll_employee_gender'] 		  = Request::input('payroll_employee_gender');
		$update_basic['payroll_employee_street'] 		  = Request::input('payroll_employee_street');
		$update_basic['payroll_employee_city'] 			  = Request::input('payroll_employee_city');
		$update_basic['payroll_employee_state'] 		  = Request::input('payroll_employee_state');
		$update_basic['payroll_employee_zipcode'] 		  = Request::input('payroll_employee_zipcode');
		$update_basic['payroll_employee_country'] 		  = Request::input('payroll_employee_country');
		$update_basic['payroll_employee_tax_status'] 	  = Request::input('payroll_employee_tax_status');
		$update_basic['payroll_employee_tin'] 			  = Request::input('payroll_employee_tin');
		$update_basic['payroll_employee_sss'] 			  = Request::input('payroll_employee_sss');
		$update_basic['payroll_employee_philhealth'] 	  = Request::input('payroll_employee_philhealth');
		$update_basic['payroll_employee_pagibig'] 		  = Request::input('payroll_employee_pagibig');
		$update_basic['payroll_employee_remarks']		  = Request::input('payroll_employee_remarks');
          $update_basic['payroll_employee_birthdate']         = date('Y-m-d',strtotime(Request::input('payroll_employee_birthdate')));
          

          Tbl_payroll_employee_basic::where('payroll_employee_id',$payroll_employee_id)->update($update_basic);
          
          $payroll_dependent_name       = Request::input('payroll_dependent_name');
          $payroll_dependent_birthdate  = Request::input('payroll_dependent_birthdate');
          $payroll_dependent_relationship = Request::input('payroll_dependent_relationship');

          /* dependent insert */
          Tbl_payroll_employee_dependent::where('payroll_employee_id', $payroll_employee_id)->delete();
          $insert_dependent = array();

          $temp = "";
          foreach($payroll_dependent_name as $key => $dependent)
          {
               if($dependent != "")
               {
                    $temp['payroll_employee_id']            = $payroll_employee_id;
                    $temp['payroll_dependent_name']         = $dependent;

                    $birthdate = '';
                    if($payroll_dependent_birthdate[$key] != '')
                    {    
                         $birthdate                                   = date('Y-m-d',strtotime($payroll_dependent_birthdate[$key]));
                    }
                    
                    $temp['payroll_dependent_birthdate']    = $birthdate;
                    $temp['payroll_dependent_relationship'] = $payroll_dependent_relationship[$key];

                    array_push($insert_dependent, $temp);
               }
          }

          Tbl_payroll_employee_dependent::insert($insert_dependent);

          /* INSERT ALLOWANCES */
          Tbl_payroll_employee_allowance::where('payroll_employee_id',$payroll_employee_id)->delete();
          $insert_allowance = array();
          if(Request::has('allowance'))
          {
               foreach(Request::input('allowance') as $allowance)
               {
                    $insert_allowance_temp['payroll_allowance_id'] = $allowance;
                    $insert_allowance_temp['payroll_employee_id']  = $payroll_employee_id;
                    array_push($insert_allowance, $insert_allowance_temp);
               }

               if(!empty($insert_allowance))
               {
                    Tbl_payroll_employee_allowance::insert($insert_allowance);
               }
          }


          /* INSERT PAYROLL LEAVE */
          $update_leave['payroll_leave_employee_is_archived'] = 1;
          Tbl_payroll_leave_employee::where('payroll_employee_id',$payroll_employee_id)->update($update_leave);
          if(Request::has('leave'))
          {
               $leave_insert = array();
               foreach(Request::input("leave") as $leave)
               {
                    $count_leave = Tbl_payroll_leave_employee::where('payroll_employee_id',$payroll_employee_id)->where('payroll_leave_temp_id',$leave)->count();
                    $update_leave['payroll_leave_employee_is_archived'] = 0;

                    if($count_leave == 1)
                    {
                         Tbl_payroll_leave_employee::where('payroll_employee_id',$payroll_employee_id)->where('payroll_leave_temp_id',$leave)->update($update_leave);
                    }

                    else
                    {
                         $temp_leave['payroll_leave_temp_id']    = $leave;
                         $temp_leave['payroll_employee_id']      = $payroll_employee_id;
                         array_push($leave_insert, $temp_leave);
                    }


               }

               if(!empty($leave_insert))
               {
                    Tbl_payroll_leave_employee::insert($leave_insert);
               }
          }
          

          $update_deduction['payroll_deduction_employee_archived'] = 1;
          Tbl_payroll_deduction_employee::where('payroll_employee_id',$payroll_employee_id)->update($update_deduction);
          AuditTrail::record_logs("DELETED: Payroll Deduction Employee ","Updating Payroll Deduction Employee with Employee ID #".$payroll_employee_id." to archived=1",$payroll_employee_id,"","");
          if(Request::has('deduction'))
          {

               $insert_decution = array();
               $temp_deduction = array();
               foreach(Request::input("deduction") as $deduction)
               {
                    $count_deduction = Tbl_payroll_deduction_employee::where('payroll_deduction_id', $deduction)->where('payroll_employee_id', $payroll_employee_id)->count();

                    if($count_deduction == 1)
                    {
                         $update_deduction['payroll_deduction_employee_archived'] = 0;
                         Tbl_payroll_deduction_employee::where('payroll_deduction_id', $deduction)->where('payroll_employee_id', $payroll_employee_id)->update($update_deduction);
                         AuditTrail::record_logs("EDITED: Payroll Deduction Employee ","Updating Payroll Deduction Employee with Employee ID #".$payroll_employee_id." to archived=0",$payroll_employee_id,"","");
                    }

                    else
                    {
                         $temp_deduction['payroll_deduction_id'] = $deduction;
                         $temp_deduction['payroll_employee_id']  = $payroll_employee_id;

                         array_push($insert_decution, $temp_deduction);
                    }
               }

               if(!empty($insert_decution))
               {
                    Tbl_payroll_deduction_employee::insert($insert_decution);
               }
          }
         Tbl_payroll_journal_tag_employee::where('payroll_employee_id' ,$payroll_employee_id)->delete();
          /* INSERT JOURNAL TAGS */
          if(Request::has('journal_tag'))
          {

               $insert_journal = array();
               foreach(Request::input('journal_tag') as $tag){
                    $temp_journal['payroll_employee_id']    = $payroll_employee_id;
                    $temp_journal['payroll_journal_tag_id'] = $tag;
                    array_push($insert_journal, $temp_journal);
               }

               if(!empty($insert_journal))
               {
                    Tbl_payroll_journal_tag_employee::insert($insert_journal);
               }
          }

          // Self::update_tbl_search();
          
          if(Request::input("source") == "time_keeping")
          {
               $return['function_name'] = 'timesheet_employee_list.action_load_table';
          }
          else
          {
               $return['function_name'] = 'employeelist.reload_employee_list';
          }
          
          $return['status'] = 'success';
          return json_encode($return);
     }

     /* PREDICTIVE TEXT SEARCH*/
     public function search_employee_ahead()
     {
          $query = Request::input('query');
          $status = Request::input("status");
          // dd($status);
          $_return = Tbl_payroll_employee_search::search($query, $status, '0000-00-00', Self::shop_id())
                                                        ->select('tbl_payroll_employee_basic.payroll_employee_display_name as employee')
                                                        ->orderBy("tbl_payroll_employee_basic.payroll_employee_first_name")
                                                        ->groupBy('tbl_payroll_employee_basic.payroll_employee_id')
                                                        ->get();

          $data = array();
          
          foreach($_return as $return)
          {
               array_push($data, $return->employee);
          }

          return json_encode($data);
          // return $_return->toJson();
     }

     public function search_employee()
     {
          $trigger            = Request::input('trigger');
          $employee_search    = Request::input('employee_search');
          $data['_active']    = Tbl_payroll_employee_search::search($employee_search, $trigger,'0000-00-00' ,Self::shop_id())
                                                        ->orderBy("tbl_payroll_employee_basic.payroll_employee_first_name")
                                                        ->groupBy('tbl_payroll_employee_basic.payroll_employee_id')
                                                        ->get();

          return view('member.payroll.reload.employee_list_reload', $data);
     }

     public function update_tbl_search()
     {
          Tbl_payroll_employee_search::truncate();
          $_emp = tbl_payroll_employee_basic::get();

          $insert = array();
          foreach($_emp as $emp)
          {
               $temp['payroll_search_employee_id'] = $emp->payroll_employee_id;
               $temp['body']  =    $emp->payroll_employee_title_name.' '.$emp->payroll_employee_first_name.' '.$emp->payroll_employee_middle_name.' '.$emp->payroll_employee_last_name.' '.$emp->payroll_employee_suffix_name.' '.$emp->payroll_employee_display_name.' '.$emp->payroll_employee_email;
               array_push($insert, $temp);
          }
          if(!empty($insert))
          {
               Tbl_payroll_employee_search::insert($insert);
          }
     }

     public function reload_employee_list()
     {
          $company_id = 0;
          $employement_status = 0;
          
          if(Request::has('company_id'))
          {
               $company_id = Request::input('company_id');
          }

          if(Request::has('employement_status'))
          {
               $employement_status = Request::input('employement_status');
          }

          $parameter['date']                      = date('Y-m-d');
          $parameter['company_id']                = $company_id;
          $parameter['employement_status']        = $employement_status;
          $parameter['shop_id']                   = Self::shop_id();

          $data['_active'] = Tbl_payroll_employee_basic::selemployee($parameter)->orderBy('tbl_payroll_employee_basic.payroll_employee_last_name')->get();

          return view('member.payroll.reload.employee_list_reload', $data);
     }

     /* EMPLOYEE END */

     public function payroll_configuration()
     {

          $_access = Self::payroll_configuration_page();

          $link = array();

          foreach($_access as $access)
          {
               if(Utilities::checkAccess('payroll-configuration',str_replace(' ', '_', $access['access_name'])) == 1)
               {
                    array_push($link, $access);
               }
          }

          $data['_link'] = $link;
          return view('member.payroll.payrollconfiguration', $data);
     }

     /* payroll configuration access page */
     public function payroll_configuration_page()
     {
          $data[0]['access_name']  = 'Branch Location';
          $data[0]['link']         = '/member/payroll/branch_name';

          $data[1]['access_name']  = 'Department';
          $data[1]['link']         = '/member/payroll/departmentlist';

          $data[2]['access_name']  = 'Job Title';
          $data[2]['link']         = '/member/payroll/jobtitlelist';

          $data[3]['access_name']  = 'Holiday';
          $data[3]['link']         = '/member/payroll/holiday';

          $data[31]['access_name'] = 'Holiday V2';
          $data[31]['link']        = '/member/payroll/holiday/v2';

          $data[4]['access_name'] = 'Holiday Default';
          $data[4]['link']        = '/member/payroll/holiday_default';

          $data[5]['access_name']  = 'Allowances';
          $data[5]['link']         = '/member/payroll/allowance';

          $data[51]['access_name'] = 'Allowances V2';
          $data[51]['link']        = '/member/payroll/allowance/v2';

          $data[6]['access_name']  = 'Deductions';
          $data[6]['link']         = '/member/payroll/deduction';
          
          $data[61]['access_name'] = 'Deductions V2';
          $data[61]['link']        = '/member/payroll/deduction/v2';

          $data[7]['access_name']  = 'Leave';
          $data[7]['link']         = '/member/payroll/leave';

          $data[71]['access_name']  = 'Leave V2';
          $data[71]['link']         = '/member/payroll/leave/v2';

          $data[8]['access_name']  = 'Payroll Group';
          $data[8]['link']         = '/member/payroll/payroll_group';

          $data[9]['access_name']  = 'Shift Template';
          $data[9]['link']         = '/member/payroll/shift_template';

          $data[10]['access_name'] = 'Journal Tags';
          $data[10]['link']        = '/member/payroll/payroll_jouarnal';

          $data[11]['access_name'] = 'Payslip';
          $data[11]['link']        = '/member/payroll/custom_payslip';

          $data[12]['access_name'] = 'Tax Period';
          $data[12]['link']        = '/member/payroll/tax_period';

          $data[13]['access_name'] = 'Tax Table';
          $data[13]['link']        = '/member/payroll/tax_table_list';

          $data[14]['access_name'] = 'SSS Table';
          $data[14]['link']        = '/member/payroll/sss_table_list';

          $data[15]['access_name'] = 'Philhealth Table';
          $data[15]['link']        = '/member/payroll/philhealth_table_list';

          $data[16]['access_name'] = 'Pagibig/HDMF';
          $data[16]['link']        = '/member/payroll/pagibig_formula';

          $data[17]['access_name'] = 'Reset';
          $data[17]['link']        = '/member/payroll/reset_payroll';

          return $data;
     }


     /* payroll branch name */
     public function branch_name()
     {
          $data['_active'] = Tbl_payroll_branch_location::getdata(Self::shop_id())->orderBy('branch_location_name')->get();
          $data['_archive'] = Tbl_payroll_branch_location::getdata(Self::shop_id(), 1)->orderBy('branch_location_name')->get();
          return view('member.payroll.side_container.branch_name', $data);
     }

     public function modal_create_branch()
     {
          return view('member.payroll.modal.modal_create_branch');
     }

     public function modal_save_branch()
     {
          // Tbl_payroll_branch_location::
          $insert['branch_location_name'] = Request::input('branch_location_name');
          $insert['shop_id']              = Self::shop_id();
          AuditTrail::record_logs('ADDED: Payroll Branch Location', 'Payroll Branch Location: '.Request::input('branch_location_name'),"" ,"");
          Tbl_payroll_branch_location::insert($insert);

          $return['status']             = 'success';
          $return['data']               = '';
          $return['function_name']      = 'payrollconfiguration.reload_branch';
          return json_encode($return);
     }


     public function modal_edit_branch($id)
     {
          $data['branch'] = Tbl_payroll_branch_location::where('branch_location_id', $id)->first();
          return view('member.payroll.modal.modal_update_branch', $data);
     }

     public function modal_update_branch($id)
     {
          $update['branch_location_name'] = Request::input('branch_location_name');
          $branch_location_id           = $id;
          $databranch['info']=Tbl_payroll_branch_location::where('branch_location_id', $branch_location_id)->first();
          Tbl_payroll_branch_location::where('branch_location_id', $branch_location_id)->update($update);
          AuditTrail::record_logs("UPDATED: Payroll Branch Location","Payroll Branch Location Name : ".$databranch['info']->branch_location_name." to " .Request::input('branch_location_name'),$branch_location_id,"","");
          $return['status']             = 'success';
          $return['data']               = '';
          $return['function_name']      = 'payrollconfiguration.reload_branch';
          return json_encode($return);
     }


     public function modal_archive_branch($archive, $id)
     {
          $statement = 'archive';
          if($archive == 0)
          {
               $statement = 'restore';
          }

          $file_name          = Tbl_payroll_branch_location::where('branch_location_id', $id)->value('branch_location_name');

          $data['title']      = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/branch_name/archive_branch';
          $data['id']         = $id;
          $data['archived']   = $archive;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archive_branch()
     {

          $update['branch_location_archived'] = Request::input('archived');

          $branch_location_id           = Request::input('id');
          $databranch['info']=Tbl_payroll_branch_location::where('branch_location_id', $branch_location_id)->first();
          Tbl_payroll_branch_location::where('branch_location_id', $branch_location_id)->update($update);
          AuditTrail::record_logs("DELETED: Payroll Branch Location ","Payroll Branch Location  : ".$databranch['info']->branch_location_name,$branch_location_id,"","");
          $return['status']             = 'success';
          $return['data']               = '';
          $return['function_name']      = 'payrollconfiguration.reload_branch';
          return json_encode($return);
     }

     /* payroll reset start */
     public function reset_payroll()
     {
          return view('member.payroll.side_container.reset_payroll');
     }

     /* password for resetting payroll */

     public function reset_payroll_password()
     {
          return 'water123';
     }

     public function reset_time_sheet()
     {
          $data['_period'] = Tbl_payroll_period::sel(Self::shop_id())->orderBy('payroll_period_start')->get();
          return view('member.payroll.modal.reset_time_sheet', $data);
     }

     public function reset_time_sheet_select()
     {
          $period = Request::input('period');
          $company = Tbl_payroll_period_company::selperiod($period)->orderBy('payroll_company_name')->get();
          return $company->toJson();
     }

     public function reset_time_sheet_action()
     {
          $return['status'] = 'wrong password';
          if(Request::input('password') == Self::reset_payroll_password())
          {
               $count = 0;
               if(Request::has('period_company'))
               {
                    foreach(Request::input('period_company') as $key => $period)
                    {
                         $count += Tbl_payroll_time_sheet::getpercompany($period)->count();
                         $period_list = Tbl_payroll_time_sheet::getpercompany($period)->delete();
                         AuditTrail::record_logs("DELETED: Payroll Time Sheet","Payroll Timesheet  (not found)","","","");
                    }
               }

               $return['status']   = 'success';
               $return['affected'] = $count.' time sheet/s has been deleted';
          }

          return collect($return)->toJson();
     }
     /* payroll reset end */

     /* COMPANY START */
     public function company_list()
     {
          // $data['_page'] = Tbl_payroll_company::selcompany(Self::shop_id())->where('payroll_parent_company_id',0)->orderBy('tbl_payroll_company.payroll_company_name')->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_company::selcompany(Self::shop_id(),1)->orderBy('tbl_payroll_company.payroll_company_name')->paginate($this->paginate_count);

          $_active = array();
          $data['_parent'] = Tbl_payroll_company::selcompany(Self::shop_id())->where('payroll_parent_company_id',0)->orderBy('payroll_company_name')->paginate($this->paginate_count);

          foreach($data['_parent'] as $parent)
          {
               $temp['company'] = $parent;
               $temp['branch'] = Tbl_payroll_company::selcompany(Self::shop_id())->where('payroll_parent_company_id', $parent->payroll_company_id)->orderBy('payroll_company_name')->get();
               array_push($_active, $temp);
          }

          // $data['_active'] = Payroll::company_heirarchy(Self::shop_id(), $this->paginate_count);
          $data['_active'] = $_active;
          // dd($data['_active']);
          return view('member.payroll.companylist', $data);
     }


     // public function company_heirarchy

     public function modal_create_company()
     {
          $is_sub = Request::input('is_sub') == "true" ? true : false ;
          $data['is_sub'] = $is_sub;

          $checkbank = Tbl_payroll_bank_convertion::where('bank_name','Metro Bank Single')->get();
          if (count($checkbank) == 0) {
               $insert['bank_name'] = 'Metro Bank Single';
               Tbl_payroll_bank_convertion::insert($insert);
          }
          $company_logo = '';
          if(Session::has('company_logo'))
          {
               $company_logo = Session::get('company_logo');
          }
          $data['company_logo'] = $company_logo;
          $data['_rdo'] = Tbl_payroll_rdo::orderBy('rdo_code')->get();
          $data['_company'] = Tbl_payroll_company::selcompany(Self::shop_id())->where('payroll_parent_company_id',0)->orderBy('tbl_payroll_company.payroll_company_name')->get();
          $data['_bank'] = Tbl_payroll_bank_convertion::get();
          return view('member.payroll.modal.modal_create_company', $data);
     }

     public function upload_company_logo()
     {
          $file = Request::file('file');
          $ImagName = value(function() use ($file){
               $filename = str_random(10). date('ymdhis') . '.' . $file->getClientOriginalExtension();
               return strtolower($filename);
          });
          $path = 'assets/payroll/company_logo';
          if (!file_exists($path)) {
               File::makeDirectory(public_path($path), 0775, true, true);
              // mkdir($path, 0777, true);
          }
          $file->move(public_path($path), $ImagName);

          $session_logo = 'company_logo';
          if(Request::input('action') == 'update')
          {
               $session_logo = 'company_logo_update';
          }

          Session::put($session_logo,'/'.$path.'/'.$ImagName);

          return '/'.$path.'/'.$ImagName;    
     }

     public function modal_save_company()
     {
          $parent = Request::input('payroll_parent_company_id') != 0 || Request::input('payroll_parent_company_id') != null ? Request::input('payroll_parent_company_id') : 0;

          $insert['payroll_company_name']                   = Request::input('payroll_company_name');
          $insert['payroll_company_code']                   = Request::input('payroll_company_code');
          $insert['payroll_company_rdo']                    = Request::input('payroll_company_rdo');
          $insert['payroll_company_address']                = Request::input('payroll_company_address');
          $insert['payroll_company_contact']                = Request::input('payroll_company_contact');
          $insert['payroll_company_email']                  = Request::input('payroll_company_email');
          $insert['payroll_company_nature_of_business']     = Request::input('payroll_company_nature_of_business');
          $insert['payroll_company_date_started']           = date('Y-m-d', strtotime(Request::input('payroll_company_date_started')));
          $insert['payroll_company_tin']                    = Request::input('payroll_company_tin');
          $insert['payroll_company_sss']                    = Request::input('payroll_company_sss');
          $insert['payroll_company_philhealth']             = Request::input('payroll_company_philhealth');
          $insert['payroll_company_pagibig']                = Request::input('payroll_company_pagibig');
          $insert['shop_id']                                = Self::shop_id();
          $insert['payroll_parent_company_id']              = $parent;
          $insert['payroll_company_bank']                   = Request::input('payroll_company_bank');
          $insert['payroll_company_account_no']             = Request::input('payroll_company_account_no');

          $logo = '/assets/images/no-logo.png';
          if(Session::has('company_logo'))
          {
               $logo = Session::get('company_logo');
          }
          $insert['payroll_company_logo'] = $logo;
          AuditTrail::record_logs('CREATED: Payroll Company', 'Payroll Company Name: '.Request::input('payroll_company_name'),"", "" ,"");
          Tbl_payroll_company::insert($insert);
          Session::forget('company_logo');

          $return['function_name'] = 'companylist.save_company';
          $return['status'] = 'success';
          $return['data'] = '';

          return json_encode($return);
     }

     public function view_company_modal($id)
     {
          return Self::modal_company_operation($id);
     }

     public function edit_company_modal($id)
     {
          return Self::modal_company_operation($id, 'edit');
     }

     public function modal_company_operation($company_id = 0, $action = 'view')
     {
          $data['company'] = Tbl_payroll_company::where('payroll_company_id', $company_id)->first();
          $data['_rdo'] = Tbl_payroll_rdo::orderBy('rdo_code')->get();
          $data['action'] = $action;
          Session::put('company_logo_update', $data['company']->payroll_company_logo);
          $data['_company'] = Tbl_payroll_company::selcompany(Self::shop_id())->where('payroll_parent_company_id',0)->orderBy('tbl_payroll_company.payroll_company_name')->get();
          $checkbank = Tbl_payroll_bank_convertion::where('bank_name','Metro Bank Single')->get();
          if (count($checkbank) == 0) {
               $insert['bank_name'] = 'Metro Bank Single';
               Tbl_payroll_bank_convertion::insert($insert);
          }
          $data['_bank'] = Tbl_payroll_bank_convertion::get();
          return view('member.payroll.modal.modal_view_company', $data);
     }

     public function update_company()
     {
          $parent = Request::input('payroll_parent_company_id') != 0 || Request::input('payroll_parent_company_id') != null ? Request::input('payroll_parent_company_id') : 0;

          $payroll_company_id                               = Request::input('payroll_company_id');
          $update['payroll_company_name']                   = Request::input('payroll_company_name');
          $update['payroll_company_code']                   = Request::input('payroll_company_code');
          $update['payroll_company_rdo']                    = Request::input('payroll_company_rdo');
          $update['payroll_company_address']                = Request::input('payroll_company_address');
          $update['payroll_company_contact']                = Request::input('payroll_company_contact');
          $update['payroll_company_email']                  = Request::input('payroll_company_email');
          $update['payroll_company_nature_of_business']     = Request::input('payroll_company_nature_of_business');
          $update['payroll_company_date_started']           = date('Y-m-d', strtotime(Request::input('payroll_company_date_started')));
          $update['payroll_company_tin']                    = Request::input('payroll_company_tin');
          $update['payroll_company_sss']                    = Request::input('payroll_company_sss');
          $update['payroll_company_philhealth']             = Request::input('payroll_company_philhealth');
          $update['payroll_company_pagibig']                = Request::input('payroll_company_pagibig');
          $update['payroll_parent_company_id']              = $parent;
          $update['payroll_company_bank']                   = Request::input('payroll_company_bank');
          $update['payroll_company_account_no']             = Request::input('payroll_company_account_no');
          $logo = '/assets/images/no-logo.png';
          if(Session::has('company_logo_update'))
          {
               $logo = Session::get('company_logo_update');
               Session::forget('company_logo_update');
          }
          $update['payroll_company_logo']                   = $logo;
          $newdata = serialize($update);
          Tbl_payroll_company::where('payroll_company_id', $payroll_company_id)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Company', 'Payroll Company Name: '.Request::input('payroll_company_name'), $payroll_company_id, "" ,$newdata);

          $return['function_name'] = 'companylist.save_company';
          $return['status'] = 'success';
          $return['data'] = '';

          return json_encode($return);
     }

     public function reload_company()
     {
          $archived = Request::input('archived');
          $data['_active'] = Tbl_payroll_company::selcompany(Self::shop_id(), $archived)->orderBy('tbl_payroll_company.payroll_company_name')->get();
          return view('member.payroll.reload.companylist_reload',$data);
     }

     public function archived_company()
     {
          $archived      = Request::input('archived');
          $id            = Request::input('id');
          $company = Tbl_payroll_company::where('payroll_company_id', $id)->first();

          $status = 0;
          if($company)
          {
               $company_parent = Tbl_payroll_company::where('payroll_company_id', $company->payroll_parent_company_id)->first();
               if($company_parent)
               {
                    if($company_parent->payroll_company_archived == 1)
                    {
                         $status = 1;
                    }
               }
          }
          if($status == 0)
          {
               $update['payroll_company_archived'] = $archived;

               Tbl_payroll_company::where('payroll_company_id', $id)->update($update);
               AuditTrail::record_logs("ARCHIVED: Payroll Company","Payroll Company Parent  (not found)","","","");
               Tbl_payroll_company::where('payroll_parent_company_id', $id)->update($update); 
               AuditTrail::record_logs("ARCHIVED: Payroll Company","Payroll Company  (not found)","","","");   

               $return['function_name'] = 'companylist.save_company';
               $return['status'] = 'success';
               $return['data'] = '';
          }
          else
          {
               $return['message'] = 'error';
               $return['status_message'] = "Can't re-use sub company.";

          }
          return json_encode($return);
     }

     public function modal_archived_company($archived, $id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name          = Tbl_payroll_company::where('payroll_company_id', $id)->value('payroll_company_name');
          $data['title']      = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/company_list/archived_company';
          $data['id']         = $id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }

     /* COMPANY END */


     /* DEPARTMENT START */
     public function department_list()
     {
          $data['_active'] = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_department::sel(Self::shop_id(), 1)->orderBy('payroll_department_name')->paginate($this->paginate_count);
          return view('member.payroll.side_container.departmentlist', $data);
     }

     public function department_modal_create()
     {
          return view('member.payroll.modal.modal_create_department');
     }


     public function department_save()
     {
          $insert['payroll_department_name'] = Request::input('payroll_department_name');
          $insert['shop_id']                    = Self::shop_id();
          AuditTrail::record_logs('CREATED: Payroll Department', 'Payroll Department Name : '.Request::input('payroll_department_name'), "", "" ,"");
          $id = Tbl_payroll_department::insertGetId($insert);

          $data['_data']      = array();
          $data['selected']   = $id;

          $_department = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          foreach($_department as $deparmtent)
          {
               $temp['id']    = $deparmtent->payroll_department_id;
               $temp['name']  = $deparmtent->payroll_department_name;
               $temp['attr']  = '';
               array_push($data['_data'], $temp);
          }

          $view = view('member.payroll.misc.misc_option', $data)->render();

          $return['selected']           = $id;
          $return['view']               = $view;
          $return['status']             = 'success';
          $return['data']               = '';
          $return['function_name']      = 'payrollconfiguration.relaod_tbl_department';
          return json_encode($return);
     }
     public function department_reload()
     {
          $archived = Request::input('archived');
          $data['_active'] = Tbl_payroll_department::sel(Self::shop_id(), $archived)->orderBy('payroll_department_name')->get();
          return view('member.payroll.reload.departmentlist_reload', $data);
     }

     public function archived_department()
     {
          $id = Request::input('id');
          $update['payroll_department_archived'] = Request::input('archived');
          $data_department['department'] = Tbl_payroll_department::where('payroll_department_id', $id)->first();
          Tbl_payroll_department::where('payroll_department_id', $id)->update($update);
          AuditTrail::record_logs('DELETED: Payroll Department', 'Payroll Department Name : '.$data_department['department']->payroll_department_name, "", "" ,"");
          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_departmentlist';
          return json_encode($return);

     }

     public function modal_archived_department($archived, $department_id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name               = Tbl_payroll_department::where('payroll_department_id', $department_id)->value('payroll_department_name');
          $data['title']           = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/departmentlist/archived_department';
          $data['id']         = $department_id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }


     public function modal_view_department($id)
     {
          return Self::modal_department_operation($id);
     }

     public function modal_edit_department($id)
     {
          $action = 'edit';
          return Self::modal_department_operation($id, $action);
     }

     public function modal_department_operation($payroll_department_id = 0, $action = 'view')
     {
          $data['deparmtent'] = Tbl_payroll_department::where('payroll_department_id', $payroll_department_id)->first();
          $data['action'] = $action;
          return view('member.payroll.modal.modal_view_department',$data);
     }


     public function modal_update_department()
     {
          
          $payroll_department_id = Request::input('payroll_department_id');
          $payroll_department_name = Request::input('payroll_department_name');

          $update['payroll_department_name'] = $payroll_department_name;
          $data_department['department'] = Tbl_payroll_department::where('payroll_department_id', $payroll_department_id)->first();
          Tbl_payroll_department::where('payroll_department_id', $payroll_department_id)->update($update);
          AuditTrail::record_logs('UPDATED: Payroll Department', 'Payroll Department Name: '. $data_department['department']->payroll_department_name ." to ".Request::input('payroll_department_name'), $payroll_department_id, "" ,"");
          
          $return['status']             = 'success';
          $return['data']                    = '';
          $return['function_name']      = 'payrollconfiguration.relaod_tbl_department';
          return json_encode($return);
     }

     /* DEPARTMENT END */


     /* JOB TITLE START*/

     public function jobtitle_list()
     {
          $data['_active'] = Tbl_payroll_jobtitle::sel(Self::shop_id())->orderBy('payroll_jobtitle_name')->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_jobtitle::sel(Self::shop_id(), 1)->orderBy('payroll_jobtitle_name')->paginate($this->paginate_count);
          return view('member.payroll.side_container.jobtitlelist', $data);
     }


     public function modal_create_jobtitle()
     {
          $selected = 0;
          if(Request::has('selected'))
          {
               $selected = Request::input('selected');
          }
          $data['_department'] = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          $data['selected'] = $selected;
          return view('member.payroll.modal.modal_create_jobtitle', $data);
     }

     public function modal_save_jobtitle()
     {
          $payroll_jobtitle_department_id              = Request::input('payroll_jobtitle_department_id');

          $insert['payroll_jobtitle_department_id']    = $payroll_jobtitle_department_id;
          $insert['payroll_jobtitle_name']             = Request::input('payroll_jobtitle_name');
          $insert['shop_id']                           = Self::shop_id();
          AuditTrail::record_logs('CREATED: Payroll Job Title', 'Payroll Job Title  Name : '.Request::input('payroll_jobtitle_name'), "", "" ,"");
          $id = Tbl_payroll_jobtitle::insertGetId($insert);

          $data['_data']      = array();
          $data['selected']   = $id;
          $_jobtitle = Tbl_payroll_jobtitle::sel(Self::shop_id())->where('payroll_jobtitle_department_id',Request::input('payroll_jobtitle_department_id'))->orderBy('payroll_jobtitle_name')->get();
          foreach($_jobtitle as $job_title)
          {
               $temp['id']      = $job_title->payroll_jobtitle_id;
               $temp['name']    = $job_title->payroll_jobtitle_name;
               $temp['attr']    = '';
               array_push($data['_data'], $temp);
          }
          $view = view('member.payroll.misc.misc_option', $data)->render();

          $return['view']          = $view;
          $return['department_id'] = $payroll_jobtitle_department_id;
          $return['status']        = 'success';
          $return['data']          = $id;
          $return['function_name'] = 'payrollconfiguration.reload_jobtitlelist';
          
          return json_encode($return);
     }


     public function archived_jobtitle()
     {
          $id = Request::input('id');
          $update['payroll_jobtitle_archived'] = Request::input('archived');
          $job = Tbl_payroll_jobtitle::where('payroll_jobtitle_id', $id)->first();
          Tbl_payroll_jobtitle::where('payroll_jobtitle_id', $id)->update($update);
          AuditTrail::record_logs('DELETED: Payroll Job Title', 'Payroll Job Title Name : '.$job->payroll_jobtitle_name, $id, "","");

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_tbl_jobtitle';
          return json_encode($return);

     }

     public function modal_archived_jobtitle($archived, $jobtitle_id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name               = Tbl_payroll_jobtitle::where('payroll_jobtitle_id', $jobtitle_id)->value('payroll_jobtitle_name');
          $data['title']           = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/jobtitlelist/archived_jobtitle';
          $data['id']         = $jobtitle_id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }
     
     public function reload_tbl_jobtitle()
     {
          $archived = Request::input('archived');
          $data['_active'] = Tbl_payroll_jobtitle::sel(Self::shop_id(), $archived)->orderBy('payroll_jobtitle_name')->get();
          return view('member.payroll.reload.jobtitlelist_reload',$data);
     }

     public function modal_view_jobtitle($id)
     {

          return Self::moda_view_jobtitle_operation($id);
     }

     public function modal_edit_jobtitle($id)
     {
          return Self::moda_view_jobtitle_operation($id, 'edit');
     }

     public function moda_view_jobtitle_operation($payroll_jobtitle_id = 0, $action = "view")
     {
          $data['position'] = Tbl_payroll_jobtitle::where('payroll_jobtitle_id', $payroll_jobtitle_id)->first();
          $data['_department'] = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          $data['action'] = $action;
          return view('member.payroll.modal.modal_view_jobtitlelist',$data);
     }

     public function modal_update_jobtitle()
     {
          $payroll_jobtitle_id                         = Request::input('payroll_jobtitle_id');
          $update['payroll_jobtitle_department_id']    = Request::input('payroll_jobtitle_department_id');
          $update['payroll_jobtitle_name']             = Request::input('payroll_jobtitle_name');
          $job = Tbl_payroll_jobtitle::where('payroll_jobtitle_id', $payroll_jobtitle_id)->first();
          Tbl_payroll_jobtitle::where('payroll_jobtitle_id', $payroll_jobtitle_id)->update($update);
          AuditTrail::record_logs('EDITED:Payroll Job Title', 'Payroll JobTitle Name: '.$job->payroll_jobtitle_name ." to ".Request::input('payroll_jobtitle_name'), $payroll_jobtitle_id, "" ,"");
          $return['status']             = 'success';
          $return['data']                    = '';
          $return['function_name']      = 'payrollconfiguration.reload_tbl_jobtitle';
          return json_encode($return);
     }

     /* GET JOB TITLE BY DEPARTMENT */
     public function get_job_title_by_department()
     {
          $payroll_department_id = Request::input('payroll_department_id');
          // dd($payroll_department_id);
          $job_title = Tbl_payroll_jobtitle::where('payroll_jobtitle_department_id',$payroll_department_id)->where('payroll_jobtitle_archived',0)->where('shop_id', Self::shop_id())->get();
          return json_encode($job_title);
     }

     /* JOB TITLE END*/


     /* TAX PERIOD START */
     public function tax_period()
     {
          $data['_period'] = Tbl_payroll_tax_period::where('shop_id', Self::shop_id())->get();
          return view('member.payroll.side_container.tax_period', $data);
     }

     public function taxt_perid_change()
     {
          $update['is_use']        = Request::input('is_use');
          $payroll_tax_period_id   = Request::input('payroll_tax_period_id');
          $tax1=Tbl_payroll_tax_period::where('payroll_tax_period_id',$payroll_tax_period_id)->first();
          AuditTrail::record_logs('EDITED: Payroll Tax Period', 'Payroll Tax Period '.$tax1->payroll_tax_period,  $payroll_tax_period_id , "","" );
          Tbl_payroll_tax_period::where('payroll_tax_period_id',$payroll_tax_period_id)->update($update);
          
     }
     /* TAX PERIOD END */

     /* TAX TABLE START */
     public function tax_table_list()
     {
          $data['_period'] = Payroll::tax_break(Self::shop_id());
          // dd($data);
          return view('member.payroll.side_container.tax', $data);
     }

     public function tax_table_save()
     {
          $payroll_tax_status_id   = Request::input('payroll_tax_status_id');
          $tax_category            = Request::input('tax_category');
          $tax_first_range         = Request::input('tax_first_range');
          $tax_second_range        = Request::input('tax_second_range');
          $tax_third_range         = Request::input('tax_third_range');
          $tax_fourth_range        = Request::input('tax_fourth_range');
          $tax_fifth_range         = Request::input('tax_fifth_range');
          $taxt_sixth_range        = Request::input('taxt_sixth_range');
          $tax_seventh_range       = Request::input('tax_seventh_range');

          Tbl_payroll_tax_reference::where('shop_id', Self::shop_id())->where('payroll_tax_status_id',$payroll_tax_status_id)->delete();
          $insert = array();
          foreach($tax_category as $key => $category)
          {
               $insert[$key]['shop_id']                = Self::shop_id();
               $insert[$key]['payroll_tax_status_id']  = $payroll_tax_status_id;
               $insert[$key]['tax_category']                = $category;
               $insert[$key]['tax_first_range']        = $tax_first_range[$key];
               $insert[$key]['tax_second_range']       = $tax_second_range[$key];
               $insert[$key]['tax_third_range']        = $tax_third_range[$key];
               $insert[$key]['tax_fourth_range']       = $tax_fourth_range[$key];
               $insert[$key]['tax_fifth_range']        = $tax_fifth_range[$key];
               $insert[$key]['taxt_sixth_range']       = $taxt_sixth_range[$key];
               $insert[$key]['tax_seventh_range']           = $tax_seventh_range[$key];
          }
          $old_data = serialize($insert);
          AuditTrail::record_logs('EDITED: Payroll Tax', 'Payroll Tax Table(not found)', "",$old_data,"");
          Tbl_payroll_tax_reference::insert($insert);
          $return['status'] = 'success';
          $return['function_name'] = '';

          return json_encode($return);

     }


     /* FOR DEVELOPERS USE ONLY */
     public function tax_table_save_default()
     {
          $payroll_tax_status_id   = Request::input('payroll_tax_status_id');
          $tax_category            = Request::input('tax_category');
          $tax_first_range         = Request::input('tax_first_range');
          $tax_second_range        = Request::input('tax_second_range');
          $tax_third_range         = Request::input('tax_third_range');
          $tax_fourth_range        = Request::input('tax_fourth_range');
          $tax_fifth_range         = Request::input('tax_fifth_range');
          $taxt_sixth_range        = Request::input('taxt_sixth_range');
          $tax_seventh_range       = Request::input('tax_seventh_range');
          Tbl_payroll_tax_default::where('payroll_tax_status_id',$payroll_tax_status_id)->delete();
          $insert = array();
          foreach($tax_category as $key => $category)
          {
               $insert[$key]['payroll_tax_status_id']  = $payroll_tax_status_id;
               $insert[$key]['tax_category']           = $category;
               $insert[$key]['tax_first_range']        = $tax_first_range[$key];
               $insert[$key]['tax_second_range']       = $tax_second_range[$key];
               $insert[$key]['tax_third_range']        = $tax_third_range[$key];
               $insert[$key]['tax_fourth_range']       = $tax_fourth_range[$key];
               $insert[$key]['tax_fifth_range']        = $tax_fifth_range[$key];
               $insert[$key]['taxt_sixth_range']       = $taxt_sixth_range[$key];
               $insert[$key]['tax_seventh_range']      = $tax_seventh_range[$key];
          }
          $old_data = serialize($insert);
          AuditTrail::record_logs('EDITED: Payroll Tax Default', 'Payroll Tax Default Table(not found)', "", $old_data,"");
          Tbl_payroll_tax_default::insert($insert);
          
          $return['status'] = 'success';
          $return['function_name'] = '';
          return json_encode($return);
     }
     /* TAX TABLE END */


     /* SSS TABLE START */
     public function sss_table_list()
     {
          $data['_sss'] = Tbl_payroll_sss::where('shop_id', Self::shop_id())->orderBy('payroll_sss_min')->get();
          return view('member.payroll.side_container.ssslist', $data);
     }

     public function sss_table_save()
     {
          $payroll_sss_min              = Request::input('payroll_sss_min');
          $payroll_sss_max              = Request::input('payroll_sss_max');
          $payroll_sss_monthly_salary = Request::input('payroll_sss_monthly_salary');
          $payroll_sss_er               = Request::input('payroll_sss_er');
          $payroll_sss_ee               = Request::input('payroll_sss_ee');
          $payroll_sss_total            = Request::input('payroll_sss_total');
          $payroll_sss_eec              = Request::input('payroll_sss_eec');

          Tbl_payroll_sss::where('shop_id', Self::shop_id())->delete();
          $insert = array();
          foreach($payroll_sss_min as $key => $sss_min)
          {
               if($sss_min != '' && $sss_min != null)
               {    
                    $insert[$key]['shop_id']                          = Self::shop_id();
                    $insert[$key]['payroll_sss_min']             = $sss_min;
                    $insert[$key]['payroll_sss_max']             = $payroll_sss_max[$key];
                    $insert[$key]['payroll_sss_monthly_salary'] = $payroll_sss_monthly_salary[$key];
                    $insert[$key]['payroll_sss_er']              = $payroll_sss_er[$key];
                    $insert[$key]['payroll_sss_ee']              = $payroll_sss_ee[$key];
                    $insert[$key]['payroll_sss_total']                = $payroll_sss_total[$key];
                    $insert[$key]['payroll_sss_eec']             = $payroll_sss_eec[$key];
               }
          }
          $old_data = serialize($insert);
          AuditTrail::record_logs('EDITED: Payroll SSS', 'Payroll SSS Table(not found)', "", $old_data,"");
          Tbl_payroll_sss::insert($insert);
          $return['status'] = 'success';
          return json_encode($return);
     }



     /* SSS DEFAULT VALUE [DEVELOPER] */
     public function sss_table_save_default()
     {
          $payroll_sss_min              = Request::input('payroll_sss_min');
          $payroll_sss_max              = Request::input('payroll_sss_max');
          $payroll_sss_monthly_salary = Request::input('payroll_sss_monthly_salary');
          $payroll_sss_er               = Request::input('payroll_sss_er');
          $payroll_sss_ee               = Request::input('payroll_sss_ee');
          $payroll_sss_total            = Request::input('payroll_sss_total');
          $payroll_sss_eec              = Request::input('payroll_sss_eec');

          Tbl_payroll_sss_default::truncate();
          $insert = array();
          foreach($payroll_sss_min as $key => $sss_min)
          {
               $insert[$key]['payroll_sss_min'] = $sss_min;
               $insert[$key]['payroll_sss_max'] = $payroll_sss_max[$key];
               $insert[$key]['payroll_sss_monthly_salary'] = $payroll_sss_monthly_salary[$key];
               $insert[$key]['payroll_sss_er'] = $payroll_sss_er[$key];
               $insert[$key]['payroll_sss_ee'] = $payroll_sss_ee[$key];
               $insert[$key]['payroll_sss_total'] = $payroll_sss_total[$key];
               $insert[$key]['payroll_sss_eec'] = $payroll_sss_eec[$key];
          }
          $old_data = serialize($insert);
          AuditTrail::record_logs('EDITED: Payroll SSS Default', 'Payroll SSS Default Table(not found)', "", $old_data,"");
          Tbl_payroll_sss_default::insert($insert);

          $return['status'] = 'success';
          return json_encode($return);

     }
     /* SSS TABLE END */


     /* PHILHEALTH TABLE START */
     public function philhealth_table_list()
     {
          $data['_philhealth'] = Tbl_payroll_philhealth::where('shop_id', Self::shop_id())->orderBy('payroll_philhealth_min')->get();
          return view('member.payroll.side_container.philhealthlist', $data); 
     }

     public function philhealth_table_save()
     {
          $payroll_philhealth_min       = Request::input('payroll_philhealth_min');
          $payroll_philhealth_max       = Request::input('payroll_philhealth_max');
          $payroll_philhealth_base      = Request::input('payroll_philhealth_base');
          $payroll_philhealth_premium   = Request::input('payroll_philhealth_premium');
          $payroll_philhealth_ee_share  = Request::input('payroll_philhealth_ee_share');
          $payroll_philhealth_er_share  = Request::input('payroll_philhealth_er_share');
          Tbl_payroll_philhealth::where('shop_id', Self::shop_id())->delete();
          $insert = array();
          foreach($payroll_philhealth_min as $key => $min)
          {
               if($min != "" && $min != null)
               {
                    $insert[$key]['shop_id']                          = Self::shop_id();
                    $insert[$key]['payroll_philhealth_min']           = $min;
                    $insert[$key]['payroll_philhealth_max']           = $payroll_philhealth_max[$key];
                    $insert[$key]['payroll_philhealth_base']          = $payroll_philhealth_base[$key];
                    $insert[$key]['payroll_philhealth_premium']       = $payroll_philhealth_premium[$key];
                    $insert[$key]['payroll_philhealth_ee_share']      = $payroll_philhealth_ee_share[$key];
                    $insert[$key]['payroll_philhealth_er_share']      = $payroll_philhealth_er_share[$key];
               }
               
          }

          $old_data = serialize($insert);
          Tbl_payroll_philhealth::insert($insert);
          AuditTrail::record_logs('EDITED: Payroll Philhealth', 'Payroll Philhealth Table(not found)', "", $old_data,"");
          $return['status'] = 'success';
          return json_encode($return);
     }


     /* PHILHEALTH DEFAULT VALUE [DEVELOPER] */
     public function philhealth_table_save_default()
     {
          $payroll_philhealth_min       = Request::input('payroll_philhealth_min');
          $payroll_philhealth_max       = Request::input('payroll_philhealth_max');
          $payroll_philhealth_base      = Request::input('payroll_philhealth_base');
          $payroll_philhealth_premium   = Request::input('payroll_philhealth_premium');
          $payroll_philhealth_ee_share  = Request::input('payroll_philhealth_ee_share');
          $payroll_philhealth_er_share  = Request::input('payroll_philhealth_er_share');

          Tbl_payroll_philhealth_default::truncate();
          $insert = array();
          foreach($payroll_philhealth_min as $key => $min)
          {
               if($min != "" && $min != null)
               {
                    $insert[$key]['payroll_philhealth_min']           = $min;
                    $insert[$key]['payroll_philhealth_max']           = $payroll_philhealth_max[$key];
                    $insert[$key]['payroll_philhealth_base']          = $payroll_philhealth_base[$key];
                    $insert[$key]['payroll_philhealth_premium']  = $payroll_philhealth_premium[$key];
                    $insert[$key]['payroll_philhealth_ee_share']      = $payroll_philhealth_ee_share[$key];
                    $insert[$key]['payroll_philhealth_er_share']      = $payroll_philhealth_er_share[$key];
               }
               
          }
          $old_data = serialize($insert);
          AuditTrail::record_logs('EDITED: Payroll Philhealth Default', 'Payroll Philhealth Default Table(not found)', "",$old_data,"");
          Tbl_payroll_philhealth_default::insert($insert);

          $return['status'] = 'success';
          return json_encode($return);
     }


     /* PHILHEALTH TABLE END */
     public function pagibig_formula()
     {
          $data['pagibig'] = Tbl_payroll_pagibig::where('shop_id', Self::shop_id())->first();

          return view('member.payroll.side_container.pagibig', $data);
     }

     public function pagibig_formula_save()
     {
          Tbl_payroll_pagibig::where('shop_id', Self::shop_id())->delete();

          $insert['payroll_pagibig_percent']  = Request::input('payroll_pagibig_percent');
          $insert['payroll_pagibig_er_share']  = Request::input('payroll_pagibig_er_share');
          $insert['shop_id']                  = Self::shop_id();
          Tbl_payroll_pagibig::insert($insert);

          $return['status'] = 'success';
          return json_encode($return);
     }



     /* PAGIBIG DEFAULT VALUE [DEVELOPER] */
     public function pagibig_formula_save_default()
     {
          Tbl_payroll_pagibig_default::truncate();
          $insert['payroll_pagibig_percent'] = Request::input('payroll_pagibig_percent');
          $insert['payroll_pagibig_er_share']  = Request::input('payroll_pagibig_er_share');
          Tbl_payroll_pagibig_default::insert($insert);

          $return['status'] = 'success';
          return json_encode($return);
     }
     /* PAGIBIG TABLE START */


     /* DEDUCTION START */
     public function deduction()
     {
          $data['_active'] = Tbl_payroll_deduction::where('shop_id',Self::shop_id())
                              ->where('payroll_deduction_archived', 0)
                              ->orderBy('tbl_payroll_deduction.payroll_deduction_category','tbl_payroll_deduction.payroll_deduction_name')
                              ->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_deduction::where('shop_id',Self::shop_id())
                              ->where('payroll_deduction_archived', 1)
                              ->orderBy('tbl_payroll_deduction.payroll_deduction_category','tbl_payroll_deduction.payroll_deduction_name')
                              ->paginate($this->paginate_count);
          return view('member.payroll.side_container.deduction', $data);
     }

     public function modal_create_deduction()
     {
          $data["_expense"] = Accounting::getAllAccount('all',null,['Expense','Other Expense']);
          $data["default_expense"] = Tbl_chart_of_account::where("account_number", 66000)
                                             ->where("account_shop_id", Self::shop_id())->value("account_id");
          $array = array();
          Session::put('employee_deduction_tag',$array);

          return view('member.payroll.modal.modal_create_deduction', $data);
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
          AuditTrail::record_logs('Added Payroll Deduction Type', 'Added Payroll Deduction Type with ID #'.$id, "", "","");
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
           $new_data = AuditTrail::get_table_data("tbl_payroll_deduction_type","payroll_deduction_type_id",$content);
           AuditTrail::record_logs('Updating Payroll Deduction Type', 'Updating Payroll Deduction Type with Type ID #'.$content." with value:".$value,$content, "" ,$new_data);
          Tbl_payroll_deduction_type::where('payroll_deduction_type_id',$content)->update($update);

     }

     public function archive_deduction_type()
     {
          $content = Request::input('content');
          $update['payroll_deduction_archived'] = Request::input('archived');
          $new_data = AuditTrail::get_table_data("tbl_payroll_deduction_type","payroll_deduction_type_id",$content);
           AuditTrail::record_logs('Updating Payroll Deduction Type', 'Updating Payroll Deduction Type with Type ID #'.$content." with archived to:".Request::input('archived'),$content, "" ,$new_data);
          
          Tbl_payroll_deduction_type::where('payroll_deduction_type_id',$content)->update($update);
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
          $data['action']               =    '/member/payroll/deduction/set_employee_deduction_tag';

          return view('member.payroll.modal.modal_deduction_tag_employee', $data);
     }

     public function modal_save_deduction()
     {
          $insert['shop_id']                      = Self::shop_id();
          $insert['payroll_deduction_name']       = Request::input('payroll_deduction_name');
          $insert['payroll_deduction_amount']     = Request::input('payroll_deduction_amount');
          $insert['payroll_monthly_amortization'] = Request::input('payroll_monthly_amortization');
          $insert['payroll_periodal_deduction']   = Request::input('payroll_periodal_deduction');
          $insert['payroll_deduction_date_filed'] = date('Y-m-d',strtotime(Request::input('payroll_deduction_date_filed')));
          $insert['payroll_deduction_date_start'] = date('Y-m-d',strtotime(Request::input('payroll_deduction_date_start')));
          $insert['payroll_deduction_date_end']   = date('Y-m-d', strtotime(Request::input('payroll_deduction_date_end')));
          $insert['payroll_deduction_period']     = Request::input('payroll_deduction_period');
          $insert['payroll_deduction_remarks']    = Request::input('payroll_deduction_remarks');
          $insert['payroll_deduction_category']   = Request::input('payroll_deduction_category');

          $insert['expense_account_id']           = Request::input('expense_account_id');

          //$insert['payroll_deduction_type']       = Request::input('payroll_deduction_type');

          //dd($insert);
          $deduction_id = Tbl_payroll_deduction::insertGetId($insert);
          if(Session::has('employee_deduction_tag'))
          {
               $employee_tag = Session::get('employee_deduction_tag');
               $insert_employee = [];
               foreach($employee_tag as $tag)
               {
                    $insert_employee['payroll_deduction_id']         = $deduction_id;
                    $insert_employee['payroll_employee_id']          = $tag;
                    Tbl_payroll_deduction_employee::insert($insert_employee);
               }
          }

          $return['stataus'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_deduction';
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
               $count = Tbl_payroll_deduction_employee::where('payroll_deduction_id',$deduction_id)->where('payroll_employee_id', $tag)->count();

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
               Tbl_payroll_deduction_employee::insert($insert_tag);
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
          $data['emp'] = Payroll::getbalance(Self::shop_id(), $payroll_deduction_id);
          return view('member.payroll.reload.deduction_employee_tag_reload', $data);
     }


     public function modal_edit_deduction($id)
     {
          $data["_expense"] = Accounting::getAllAccount('all',null,['Expense','Other Expense']);
          $data['deduction'] = Tbl_payroll_deduction::where('payroll_deduction_id',$id)->first();
          $data['_type'] = Tbl_payroll_deduction_type::where('shop_id', Self::shop_id())->where('payroll_deduction_archived', 0)->orderBy('payroll_deduction_type_name')->get();
          $data['emp'] = Payroll::getbalance(Self::shop_id(), $id);
          //dd($data['deduction']);
          return view('member.payroll.modal.modal_edit_deduction', $data);
     }


     public function archive_deduction($archived, $id)
     {
          
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name               = Tbl_payroll_deduction::where('payroll_deduction_id', $id)->value('payroll_deduction_name');
          $data['title']           = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/deduction/archived_deduction_action';
          $data['id']         = $id;
          $data['archived']   = $archived;
          $data['payroll_deduction_type'] = 0;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archived_deduction_action()
     {
          $update['payroll_deduction_archived']   = Request::input('archived');
          $id                                          = Request::input('id');
          $old_data = AuditTrail::get_table_data("tbl_payroll_deduction","payroll_deduction_id",$id);
          Tbl_payroll_deduction::where('payroll_deduction_id',$id)->update($update);
          AuditTrail::record_logs('Added Payroll deduction', 'payroll Deduction with deduction ID #'.$id. " to archived value: ".Request::input('archived'), $id ,"", serialize($old_data));

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_deduction';
          return json_encode($return);
     }



     public function modal_update_deduction()
     {
          $payroll_deduction_id                   = Request::input('payroll_deduction_id');
          $update['payroll_deduction_name']       = Request::input('payroll_deduction_name');
          $update['payroll_deduction_amount']     = Request::input('payroll_deduction_amount');
          $update['payroll_monthly_amortization'] = Request::input('payroll_monthly_amortization');
          $update['payroll_periodal_deduction']   = Request::input('payroll_periodal_deduction');
          $update['payroll_deduction_date_filed'] = date('Y-m-d',strtotime(Request::input('payroll_deduction_date_filed')));
          $update['payroll_deduction_date_start'] = date('Y-m-d',strtotime(Request::input('payroll_deduction_date_start')));
          $update['payroll_deduction_date_end']   = date('Y-m-d', strtotime(Request::input('payroll_deduction_date_end')));
          $update['payroll_deduction_period']     = Request::input('payroll_deduction_period');
          $update['payroll_deduction_category']   = Request::input('payroll_deduction_category');
          $update['payroll_deduction_type']       = Request::input('payroll_deduction_type');
          $update['payroll_deduction_remarks']    = Request::input('payroll_deduction_remarks');

          $update['expense_account_id']           = Request::input('expense_account_id');
          $old_data = AuditTrail::get_table_data("tbl_payroll_deduction","payroll_deduction_id",$payroll_deduction_id);
          Tbl_payroll_deduction::where('payroll_deduction_id',$payroll_deduction_id)->update($update);
          AuditTrail::record_logs('Updating Payroll deduction', 'payroll Deduction with deduction ID #'.$id. " to archived value: ".Request::input('archived'), $payroll_deduction_id ,"", serialize($old_data));
          $return['status']                       = 'success';
          $return['function_name']                = 'payrollconfiguration.reload_deduction';
          return json_encode($return);
     }

     public function deduction_employee_tag($archive, $payroll_deduction_employee_id)
     {
          $statement = 'cancel';
          if($archive == 0)
          {
               $statement = 'restore';
          }
          $file_name                      = Tbl_payroll_deduction_employee::getemployee($payroll_deduction_employee_id)->value('payroll_employee_display_name');
          $data['title']                  = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']                   = '';
          $data['action']                 = '/member/payroll/deduction/deduction_employee_tag_archive';
          $data['id']                     = $payroll_deduction_employee_id;
          $data['archived']               = $archive;
          $data['payroll_deduction_type'] = "none";
          return view('member.modal.modal_confirm_archived', $data);
     }

     public function deduction_employee_tag_archive()
     {
          $id = Request::input('id');
          $update['payroll_deduction_employee_archived'] = Request::input('archived');

          $old_data = AuditTrail::get_table_data("tbl_payroll_deduction_employee","payroll_deduction_employee_id",$id);
          Tbl_payroll_deduction_employee::where('payroll_deduction_employee_id', $id)->update($update);
          AuditTrail::record_logs('Added', 'Updating Payroll Deduction Employee with ID #'.$id." to archive value:".Request::input('archived'), $id ,"", serialize($old_data));
          $return['status']             = 'success';
          $return['function_name']      = 'modal_create_deduction.reload_tag_employee';
          return json_encode($return);
     }

     /* DEDUCTION END */


     /* HOLIDAY START */
     public function holiday()
     {

          $data['_active'] = Tbl_payroll_holiday::getholiday(Self::shop_id())->orderBy('payroll_holiday_date','desc')->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_holiday::getholiday(Self::shop_id(), 1)->orderBy('payroll_holiday_date','desc')->paginate($this->paginate_count);

          $data['title']      = 'Holiday';
          $data['create']     = '/member/payroll/holiday/modal_create_holiday';
          $data['edit']       = '/member/payroll/holiday/modal_edit_holiday/';
          $data['archived']   = '/member/payroll/holiday/archive_holiday/';
          
          return view('member.payroll.side_container.holiday',$data);

     }

     

     public function modal_create_holiday()
     {
          $data['_company'] = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('payroll_company_name')->get();
          return view('member.payroll.modal.modal_create_holiday', $data);
     }

     public function modal_save_holiday()
     {
          
          $insert['shop_id']                 = Self::shop_id();
          $insert['payroll_holiday_name']    = Request::input('payroll_holiday_name');
          $insert['payroll_holiday_date']    = date('Y-m-d',strtotime(Request::input('payroll_holiday_date')));
          $insert['payroll_holiday_category']= Request::input('payroll_holiday_category');
          AuditTrail::record_logs('CREATED: Payroll Holiday', 'Payroll Holiday Name: '.Request::input('payroll_holiday_name'), "", "" ,"");
          $holiday_id = Tbl_payroll_holiday::insertGetId($insert);

          $_company                         = Request::input('company');

          $insert_company = array();

          foreach($_company as $company)
          {

               $temp['payroll_company_id'] = $company;
               $temp['payroll_holiday_id'] = $holiday_id;
               array_push($insert_company, $temp);
          }

          if(!empty($insert_company))
          {
               Tbl_payroll_holiday_company::insert($insert_company);
          }

          $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_holiday';
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
          $data['action']     = '/member/payroll/holiday/archive_holiday_action';
          $data['id']         = $id;
          $data['archived']   = $archive;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archive_holiday_action()
     {
          $id = Request::input('id');
          $update['payroll_holiday_archived'] = Request::input('archived');
          $holiday = Tbl_payroll_holiday::where('payroll_holiday_id', $id)->first();
          Tbl_payroll_holiday::where('payroll_holiday_id', $id)->update($update);
          AuditTrail::record_logs('DELETED: Payroll Holiday', 'Payroll Holiday Name: '.$holiday->payroll_holiday_name, $id, "" ,"");


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
               $temp['payroll_company_id']   = $company->payroll_company_id;
               $temp['payroll_company_name']      = $company->payroll_company_name;
               $temp['status']                         = $status;
               array_push($company_check, $temp);
          }

          $data['_company'] = $company_check;
          $data['holiday'] = Tbl_payroll_holiday::where('payroll_holiday_id',$id)->first();
          return view('member.payroll.modal.modal_edit_holiday', $data);
     }

     public function modal_update_holiday()
     {
          $payroll_holiday_id                     = Request::input('payroll_holiday_id');
          $update['payroll_holiday_name']    = Request::input('payroll_holiday_name');
          $update['payroll_holiday_date']    = date('Y-m-d',strtotime(Request::input('payroll_holiday_date')));
          $update['payroll_holiday_category'] = Request::input('payroll_holiday_category');
          $_company                                    = Request::input('company');
          $holiday = Tbl_payroll_holiday::where('payroll_holiday_id', $payroll_holiday_id)->first();
          Tbl_payroll_holiday::where('payroll_holiday_id',$payroll_holiday_id)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Holiday', 'Payroll Holiday Name: '.$holiday->payroll_holiday_name." to ".Request::input('payroll_holiday_name').", ".$holiday->payroll_holiday_date." to ".date('Y-m-d',strtotime(Request::input('payroll_holiday_date'))).", ".$holiday->payroll_holiday_category." to ".Request::input('payroll_holiday_category'), $payroll_holiday_id, "" ,"");

          Tbl_payroll_holiday_company::where('payroll_holiday_id',$payroll_holiday_id)->delete();

          $insert_company = array();
          foreach($_company as $company)
          {
               $temp['payroll_company_id'] = $company;
               $temp['payroll_holiday_id'] = $payroll_holiday_id;
               array_push($insert_company, $temp);
          }
          if(!empty($insert_company))
          {
               Tbl_payroll_holiday_company::insert($insert_company);
          }

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_holiday';
          return json_encode($return);

     }

     /* HOLIDAY END */

     /* ALLOWANCE START */
     public function allowance()
     {         
          $data['_active'] = Tbl_payroll_allowance::sel(Self::shop_id())->orderBy('payroll_allowance_name')->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_allowance::sel(Self::shop_id(), 1)->orderBy('payroll_allowance_name')->paginate($this->paginate_count);
          return view('member.payroll.side_container.allowance', $data);
     }

     public function modal_create_allowance()
     {
          $data["_expense"] = Accounting::getAllAccount('all',null,['Expense','Other Expense']);
          $data["default_expense"] = Tbl_chart_of_account::where("account_number", 66000)
                                             ->where("account_shop_id", Self::shop_id())->value("account_id");

          Session::put('allowance_employee_tag', array());
          return view('member.payroll.modal.modal_create_allowance', $data);
     }

     public function modal_allowance_tag_employee($allowance_id)
     {
          $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

          $data['deduction_id']    =    $allowance_id;
          $data['action']               =    '/member/payroll/allowance/set_employee_allowance_tag';

          return view('member.payroll.modal.modal_deduction_tag_employee', $data);
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
                    $count = Tbl_payroll_employee_allowance::where('payroll_allowance_id', $allowance_id)->where('payroll_employee_id',$tag)->count();
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
               Tbl_payroll_employee_allowance::insert($insert_tag);
               $new_data= serialize($insert_tag);
               $tag_me = Tbl_payroll_employee_basic::where('payroll_employee_id',$employee_tag)->first();
               AuditTrail::record_logs('ADDED: Payroll Employee Allowance', 'Payroll Employee Name Tag : '.$tag_me->payroll_employee_display_name, "", "" ,$new_data);
          
           }

          Session::put('allowance_employee_tag',$array);

          $return['status']             = 'success';
          $return['function_name']      = 'modal_create_allowance.load_employee_tag';
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
          $insert['payroll_allowance_amount']     = Request::input('payroll_allowance_amount');
          $insert['payroll_allowance_category']   = Request::input('payroll_allowance_category');
          $insert['payroll_allowance_add_period'] = Request::input('payroll_allowance_add_period');
          $insert['expense_account_id']           = Request::input('expense_account_id');
          $insert['payroll_allowance_type']       = Request::input('payroll_allowance_type');
          $insert['shop_id']                      = Self::shop_id();
          $allowance_id                           = Tbl_payroll_allowance::insertGetId($insert);

          AuditTrail::record_logs('CREATED: Payroll Allowance', 'Payroll Allowance Name: '.Request::input('payroll_allowance_name'),"", "" ,"");

          $insert_employee = array();
          if(Session::has('allowance_employee_tag'))
          {
               foreach(Session::get('allowance_employee_tag') as $tag)
               {    
                    $temp['payroll_allowance_id']      = $allowance_id;
                    $temp['payroll_employee_id']  = $tag;
                    array_push($insert_employee, $temp);
               }
               if(!empty($insert_employee))
               {
                    Tbl_payroll_employee_allowance::insert($insert_employee);
               }
          }

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_allowance';
          
          return json_encode($return);
     }

     public function modal_archived_allwance($archived, $allowance_id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name               = Tbl_payroll_allowance::where('payroll_allowance_id', $allowance_id)->value('payroll_allowance_name');
          $data['title']           = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/allowance/archived_allowance';
          $data['id']         = $allowance_id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archived_allowance()
     {
          $id = Request::input('id');
          $update['payroll_allowance_archived'] = Request::input('archived');
          $allowance = Tbl_payroll_allowance::where('payroll_allowance_id', $id)->first();
          Tbl_payroll_allowance::where('payroll_allowance_id', $id)->update($update);
           AuditTrail::record_logs('DELETED: Payroll Allowance', 'Payroll Allowance Name:'. $allowance->payroll_allowance_name,"", "" ,"");
          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_allowance';
          return json_encode($return);
     }

     public function modal_edit_allowance($id)
     {
          $data["_expense"] = Accounting::getAllAccount('all',null,['Expense','Other Expense']);
          $data['allowance'] = Tbl_payroll_allowance::where('payroll_allowance_id', $id)->first();
          $data['_active'] = Tbl_payroll_employee_allowance::getperallowance($id)->get();
          $data['_archived'] = Tbl_payroll_employee_allowance::getperallowance($id , 1)->get();
          // dd($data);
          return view('member.payroll.modal.modal_edit_allowance', $data);
     }

     public function modal_archived_llowance_employee($archived, $id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $_query             = Tbl_payroll_employee_allowance::employee($id)->first();
          // dd($_query);
          $file_name               = $_query->payroll_employee_title_name.' '.$_query->payroll_employee_first_name.' '.$_query->payroll_employee_middle_name.' '.$_query->payroll_employee_last_name.' '.$_query->payroll_employee_suffix_name;
          $data['title']           = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/allowance/archived_allowance_employee';
          $data['id']         = $id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archived_allowance_employee()
     {
          $id = Request::input('id');
          $update['payroll_employee_allowance_archived'] = Request::input('archived');

          Tbl_payroll_employee_allowance::where('payroll_employee_allowance_id', $id)->update($update);

          $tag_emp = Tbl_payroll_employee_allowance::where('payroll_employee_allowance_id', $id)->first();
          $tag_emp1 = Tbl_payroll_employee_basic::where('payroll_employee_id', $tag_emp->payroll_employee_id)->first();
          AuditTrail::record_logs('DELETED: Payroll Employee Tag', 'Payroll employee with ID #'.$id ." and Name:".$tag_emp1->payroll_employee_display_name, $id,"", "");
          $return['status']             = 'success';
          $return['function_name']      = 'modal_create_allowance.load_emoloyee_tag';
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
          Tbl_payroll_allowance::where('payroll_allowance_id', $payroll_allowance_id)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Allowance', 'Payroll Allowance ID #: '.$payroll_allowance_id." to allowance name:".Request::input('payroll_allowance_name'), $payroll_allowance_id, "" ,"");
          
          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_allowance';
          return json_encode($return);
     }

     public function reload_allowance_employee()
     {
          $payroll_allowance_id = Request::input('payroll_allowance_id');
          $data['_active'] = Tbl_payroll_employee_allowance::getperallowance($payroll_allowance_id)->get();
          $data['_archived'] = Tbl_payroll_employee_allowance::getperallowance($payroll_allowance_id , 1)->get();
          return view('member.payroll.reload.allowance_employee_reload', $data);
     }

     /* ALLOWANCE END */

     /* LEAVE START */
     public function leave()
     {
          $data['_active'] = Tbl_payroll_leave_temp::sel(Self::shop_id())->orderBy('payroll_leave_temp_name')->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_leave_temp::sel(Self::shop_id(), 1)->orderBy('payroll_leave_temp_name')->paginate($this->paginate_count);
          /*return view('member.payroll.side_container.leave', $data);*/
          return view('member.payroll.side_container.leave', $data)->with('data', $data);
     }

     /*Function to view modal to create leave_temp*/
     public function modal_create_leave_temp()
     {
          Session::put('leave_tag_employee', array());
          return view('member.payroll.modal.modal_create_leave_temp');
     }

     public function modal_leave_tag_employee($leave_temp_id)
     {
          $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

          $data['deduction_id']    =    $leave_temp_id;
          $data['action']               =    '/member/payroll/leave/set_leave_tag_employee';
          return view('member.payroll.modal.modal_deduction_tag_employee', $data);
     }

     public function get_leave_tag_employee()
     {
          $employee = [0 => 0];
          if(Session::has('leave_tag_employee'))
          {
               $employee = Session::get('leave_tag_employee');
          }
          $emp = Tbl_payroll_employee_basic::whereIn('payroll_employee_id',$employee)->get();

          $data['new_record'] = $emp;
          return json_encode($data);
     }

     public function set_leave_tag_employee()
     {
          $leave_temp_id = Request::input('deduction_id');
          $employee_tag = Request::input('employee_tag');

          $array = array();
          if(Session::has('leave_tag_employee'))
          {
               $array = Session::get('leave_tag_employee');
          }

          $insert_tag = array();

          if(isset($employee_tag)){
               foreach($employee_tag as $tag)
               {
                    array_push($array, $tag);
                    if($leave_temp_id != 0)
                    {
                         $count = Tbl_payroll_leave_employee::where('payroll_leave_temp_id', $leave_temp_id)->where('payroll_employee_id',$tag)->count();
                         if($count == 0)
                         {
                              $insert['payroll_leave_temp_id'] = $leave_temp_id;
                              $insert['payroll_employee_id']     = $tag;
                              array_push($insert_tag, $insert);
                         }
                    }
               }    
          }
               

          if($leave_temp_id != 0 && !empty($insert_tag))
          {
               Tbl_payroll_leave_employee::insert($insert_tag);
          }
          Session::put('leave_tag_employee',$array);

         
          $return['status']             = 'success';
          $return['function_name']      = 'modal_create_leave_temp.load_employee_tag';
          return json_encode($return);
     }

     public function remove_leave_tag_employee()
     {
          $content = Request::input('content');
          $array     = Session::get('leave_tag_employee');
          if(($key = array_search($content, $array)) !== false) {
              unset($array[$key]);
          }
          Session::put('leave_tag_employee',$array);
     }

     public function modal_save_leave_temp()
     {
          $insert['payroll_leave_temp_name']                     = Request::input('payroll_leave_temp_name');
          $insert['payroll_leave_temp_days_cap']            = Request::input('payroll_leave_temp_days_cap');
          $insert['payroll_leave_temp_with_pay']            = Request::input('payroll_leave_temp_with_pay');
          $insert['payroll_leave_temp_is_cummulative'] = Request::input('payroll_leave_temp_is_cummulative');
          $insert['shop_id']                                     = Self::shop_id();
          $leave_temp_id = Tbl_payroll_leave_temp::insertGetId($insert);

          $insert_employee = array();
          if(Session::has('leave_tag_employee'))
          {
               foreach(Session::get('leave_tag_employee') as $tag)
               {    
                    $temp['payroll_leave_temp_id']     = $leave_temp_id;
                    $temp['payroll_employee_id']  = $tag;
                    array_push($insert_employee, $temp);
               }
               if(!empty($insert_employee))
               {
                    Tbl_payroll_leave_employee::insert($insert_employee);
               }
          }

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_leave_temp';
          return json_encode($return);
     }

     public function modal_archived_leave_temp($archived, $leave_temp_id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name               = Tbl_payroll_leave_temp::where('payroll_leave_temp_id', $leave_temp_id)->value('payroll_leave_temp_name');
          $data['title']           = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/leave/archived_leave_temp';
          $data['id']         = $leave_temp_id;
          $data['archived']   = $archived;
          $data['payroll_deduction_type'] = "";

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archived_leave_temp()
     {
          $id = Request::input('id');
          $update['payroll_leave_temp_archived'] = Request::input('archived');
           $old_data = AuditTrail::get_table_data("tbl_payroll_leave_temp","payroll_leave_temp_id",$id);
          Tbl_payroll_leave_temp::where('payroll_leave_temp_id', $id)->update($update);
           AuditTrail::record_logs('Updating Payroll Leave temp', 'Updating Payroll Leave temp with ID #'.$id." To archive value=".Request::input('archived'), $id, "" ,serialize($old_data));
          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_leave_temp';
          return json_encode($return);

     }

     public function modal_edit_leave_temp($id)
     {
          $data['leave_temp'] = Tbl_payroll_leave_temp::where('payroll_leave_temp_id', $id)->first();
          $data['_active'] = Tbl_payroll_leave_employee::getperleave($id)->get();
          $data['_archived'] = Tbl_payroll_leave_employee::getperleave($id , 1)->get();
          // dd($data);
          return view('member.payroll.modal.modal_edit_leave_temp', $data);
     }

     public function update_leave_temp()
     {
          $payroll_leave_temp_id                       = Request::input('payroll_leave_temp_id');
          $update['payroll_leave_temp_name']           = Request::input('payroll_leave_temp_name');
          $update['payroll_leave_temp_days_cap']  = Request::input('payroll_leave_temp_days_cap');
          $update['payroll_leave_temp_with_pay']  = Request::input('payroll_leave_temp_with_pay');
          $update['payroll_leave_temp_is_cummulative']      = Request::input('payroll_leave_temp_is_cummulative');
          $old_data = AuditTrail::get_table_data("tbl_payroll_leave_temp","payroll_leave_temp_id",$payroll_leave_temp_id);
          Tbl_payroll_leave_temp::where('payroll_leave_temp_id', $payroll_leave_temp_id)->update($update);
          AuditTrail::record_logs('Updating Payroll Leave temp', 'Updating Payroll Leave temp with ID #'.$payroll_leave_temp_id." To Temp Name=".Request::input('payroll_leave_temp_name'), $payroll_leave_temp_id, "" ,serialize($old_data));
          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_leave_temp';
          return json_encode($return);
     }

     public function modal_archived_leave_employee($archived, $id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $_query             = Tbl_payroll_leave_employee::employee($id)->first();
          // dd($_query);
          $file_name               = $_query->payroll_employee_title_name.' '.$_query->payroll_employee_first_name.' '.$_query->payroll_employee_middle_name.' '.$_query->payroll_employee_last_name.' '.$_query->payroll_employee_suffix_name;
          $data['title']           = 'Do you really want to '.$statement.' '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/leave/archived_leave_employee';
          $data['id']         = $id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);

     }

     public function archived_leave_employee()
     {
          $id = Request::input('id');
          $update['payroll_leave_employee_is_archived'] = Request::input('archived');
          $old_data = AuditTrail::get_table_data("tbl_payroll_leave_employee","payroll_leave_employee_id",$id);
          Tbl_payroll_leave_employee::where('payroll_leave_employee_id', $id)->update($update);
          AuditTrail::record_logs('Updating Payroll Leave Employee', 'Updating Payroll Leave Employee with ID #'.$id." To archive value=".Request::input('archived'), $id, "" ,serialize($old_data));
          $return['status']             = 'success';
          $return['function_name']      = 'modal_create_leave_temp.load_employee_tag';
          return json_encode($return);
     }

     public function reload_leave_employee()
     {
          $payroll_leave_temp_id = Request::input('payroll_leave_temp_id');
          $data['_active'] = Tbl_payroll_leave_employee::getperleave($payroll_leave_temp_id)->get();
          $data['_archived'] = Tbl_payroll_leave_employee::getperleave($payroll_leave_temp_id , 1)->get();
          return view('member.payroll.reload.leave_employee_reload', $data);
     }

     /* LEAVE END */

     /* LEAVE V2 START */
     public function leaveV2()
     {
          $data['_active'] = Tbl_payroll_leave_tempv2::sel(Self::shop_id())->orderBy('payroll_leave_temp_name')->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_leave_tempv2::sel(Self::shop_id(), 1)->orderBy('payroll_leave_temp_name')->paginate($this->paginate_count);

          return view('member.payroll.side_container.leavev2', $data);
     }

     public function modal_create_leave_tempv2()
     {
          Session::put('leave_tag_employee', array());
          $selected = 0;
          
          if(Request::has('selected'))
          {
               $selected = Request::input('selected');
          }
          $data['_leave_type'] = Tbl_payroll_leave_type::sel(Self::shop_id())->orderBy('payroll_leave_type_name')->get();
          $data['selected'] = $selected;
   

          return view('member.payroll.modal.modal_create_leave_tempv2', $data);
     }

     public function modal_leave_tag_employeev2($leave_temp_id)
     {
          $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

          $data['deduction_id']    =    $leave_temp_id;

          $data['action']               =    '/member/payroll/leave/v2/set_leave_tag_employeev2';
          return view('member.payroll.modal.modal_deduction_tag_employee', $data);
     }

     public function set_leave_employee_tagv2()
     {
          $leave_temp_id = Request::input('deduction_id');
          $employee_tag = Request::input('employee_tag');


          $array = array();
          if(Session::has('leave_tag_employee'))
          {
               $array = Session::get('leave_tag_employee');
          }

          $insert_tag = array();

          if(isset($employee_tag))
          {
               foreach($employee_tag as $tag)
               {

                    array_push($array, $tag);
                    if($leave_temp_id != 0)
                    {
                         $count = Tbl_payroll_leave_employeev2::where('payroll_leave_temp_id', $leave_temp_id)->where('payroll_employee_id',$tag)->where('payroll_leave_employee_is_archived',0)->count();
                         if($count == 0)
                         {
                              $insert['payroll_leave_temp_id'] = $leave_temp_id;
                              $insert['payroll_employee_id']     = $tag;
                              array_push($insert_tag, $insert);
                         }

                    }
               }    
          }
               
          $returnforedit = 0;
          if($leave_temp_id != 0 && !empty($insert_tag))
          {
               Tbl_payroll_leave_employeev2::insert($insert_tag);
               $returnforedit = 1;
          }

          Session::put('leave_tag_employee',$array);

         
          $return['status']             = 'success';
          if($returnforedit == 0)
          {
               $return['function_name']      = 'modal_create_leave_tempv2.load_employee_tagv2';
          }
          else
          {
               $return['function_name']      = 'modal_create_leave_tempv2.load_for_edit_leave_temp';
          }
          
          return json_encode($return);
     }

     public function get_leave_tag_employeev2()
     {
          $employee = [0 => 0];
          if(Session::has('leave_tag_employee'))
          {
               $employee = Session::get('leave_tag_employee');
          }
          $emp = Tbl_payroll_employee_basic::whereIn('payroll_employee_id',$employee)->get();

          $data['new_record'] = $emp;

          return json_encode($data);
     }

     public function remove_leave_tag_employeev2()
     {
           $content = Request::input('content');
          $array     = Session::get('leave_tag_employee');
          if(($key = array_search($content, $array)) !== false) {
              unset($array[$key]);
          }
          Session::put('leave_tag_employee',$array);
     }

     public function reload_leave_employeev2()
     {
          $payroll_leave_temp_id = Request::input('payroll_leave_temp_id');
           $data['leave_temp'] = Tbl_payroll_leave_tempv2::where('payroll_leave_temp_id', $payroll_leave_temp_id)->first();
          $data['_active'] = Tbl_payroll_leave_employeev2::getperleave($payroll_leave_temp_id)->get();
          $data['_archived'] = Tbl_payroll_leave_employeev2::getperleave($payroll_leave_temp_id , 1)->get();

          return view('member.payroll.modal.modal_edit_leave_tempv2', $data);
     }

     public function modal_save_leave_temp_v2()
     {
          $insert['payroll_leave_temp_is_cummulative']      = Request::input('payroll_leave_temp_is_cummulative');
          $insert['payroll_leave_temp_name']                = Request::input('payroll_leave_temp_name');

          $insert['shop_id']                                = Self::shop_id();
          $insert['payroll_leave_temp_is_cummulative']      = Request::input('payroll_leave_temp_is_cummulative');

          $leave_temp_count = Tbl_payroll_leave_tempv2::where('payroll_leave_temp_name',Request::input('payroll_leave_temp_name'))->where('shop_id',Self::shop_id())->get();

          if(count($leave_temp_count) == 0)
          {
               $leave_temp_id = Tbl_payroll_leave_tempv2::insertGetId($insert);

               $insert_employee = array();

               if(Session::has('leave_tag_employee'))
               {
                    foreach(Session::get('leave_tag_employee') as $tag)
                    {    
                         $leave_hours = Request::input("leave_hours_".$tag);

                         $temp['payroll_leave_temp_id']           = $leave_temp_id;
                         $temp['payroll_employee_id']             = $tag;
                         $temp['payroll_leave_temp_hours']        = $leave_hours;

                         array_push($insert_employee, $temp);
                    }
                    if(!empty($insert_employee))
                    {
                         Tbl_payroll_leave_employeev2::insert($insert_employee);
                    }
               }
          }


          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_leavev2_temp';
          return json_encode($return);
     }

     public function modal_edit_leave_tempv2($payroll_leave_temp_id)
     {
          $data['leave_temp'] = Tbl_payroll_leave_tempv2::where('payroll_leave_temp_id', $payroll_leave_temp_id)->first();
          $data['_active'] = Tbl_payroll_leave_employeev2::getperleave($payroll_leave_temp_id)->get();
          $data['_archived'] = Tbl_payroll_leave_employeev2::getperleave($payroll_leave_temp_id , 1)->get();
          // dd($data);
          return view('member.payroll.modal.modal_edit_leave_tempv2', $data);
     }

     public function update_leave_tempv2()
     {
          $payroll_leave_temp_id                       = Request::input('payroll_leave_temp_id');
          $payroll_leave_employee_id                   = Request::input('payroll_leave_employee_id');
          $update['payroll_leave_temp_name']           = Request::input('payroll_leave_temp_name');
          $update['payroll_leave_temp_is_cummulative'] = Request::input('payroll_leave_temp_is_cummulative');

          Tbl_payroll_leave_tempv2::where('payroll_leave_temp_id', $payroll_leave_temp_id)->update($update);

          foreach(Request::input('employee_tag') as $tag)
          {
               foreach($payroll_leave_employee_id as $tags)
               {
                    $leave_hours = Request::input("leave_hours_".$tag);

                    $updates['payroll_leave_temp_hours']   = $leave_hours;

                    Tbl_payroll_leave_employeev2::where('payroll_employee_id', $tag)
                                                  ->where('payroll_leave_temp_id', $payroll_leave_temp_id)
                                                  ->where('payroll_leave_employee_id', $tags)
                                                  ->update($updates);
               }

          }


          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_leavev2_temp';
          return json_encode($return);
     }

     public function modal_view_leave_employee($payroll_leave_temp_id)
     {
         $payroll_employee_id = Tbl_payroll_leave_employeev2::select('payroll_employee_id','payroll_leave_employee_id')
                                                            ->join('tbl_payroll_leave_tempv2','tbl_payroll_leave_employee_v2.payroll_leave_temp_id','=','tbl_payroll_leave_tempv2.payroll_leave_temp_id')
                                                            ->where('tbl_payroll_leave_tempv2.payroll_leave_temp_id',$payroll_leave_temp_id)
                                                            ->get();
          $datas = array();                                               
          foreach($payroll_employee_id as $key => $emp_id)
          {
               // dd($emp_id['payroll_employee_id']);     
               $empdata = Tbl_payroll_leave_schedulev2::getviewleavedata2($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],Self::shop_id())->get();
    
               array_push($datas, $empdata); 
          }
   
          $data['emp'] = $datas;
          return view('member.payroll.modal.modal_view_leave_employee',$data);
     }

     public function modal_leave_scheduling()
     {
          Session::put('employee_leave_tag',array());
          $data['_leave_name'] = Tbl_payroll_leave_tempv2::sel(Self::shop_id())->orderBy('payroll_leave_temp_name')->get();
          return view('member.payroll.modal.modal_leave_scheduling',$data);

     }

     public function modal_leave_history()
     {
          $data['_active'] = Tbl_payroll_leave_history::sel(Self::shop_id())->orderBy('payroll_leave_date_created')->paginate($this->paginate_count);

          $data['_archived'] = Tbl_payroll_leave_history::sel(Self::shop_id(), 1)->orderBy('payroll_leave_date_created')->paginate($this->paginate_count);

          return view('member.payroll.modal.modal_leave_history',$data);
     }


     public function modal_save_leave_type()
     {
          $insert['payroll_leave_type_name']                = Request::input('payroll_leave_type_name');
          $insert['shop_id']                                = Self::shop_id();

          Tbl_payroll_leave_type::insert($insert);

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_leavev2_temp';

          return json_encode($return);
     }

     //leave v2 reporting
     public function leavev2_reports()
     {
          return view("member.payrollreport.leavev2_reports");
     }

     public function modal_monthly_leave_report() 
     {

          $date[0] = date("Y-m-d");
          $date[1] = date("Y-m-d");
          $data["_company"] = Payroll::company_heirarchy(Self::shop_id());
          $company = 0;

           $employee_id = Tbl_payroll_leave_employeev2::select('payroll_employee_id')->getemployeeid($date,Self::shop_id())->get();

          $remaining = array();      
          $leavedata = array();                                               
          foreach($employee_id as $key => $emp_id)
          {
 
               $empdata = Tbl_payroll_leave_schedulev2::getmonthleavereportfilter($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
               $empdataremaining = Tbl_payroll_leave_schedulev2::getremainings($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
    
               array_push($leavedata, $empdata); 
               array_push($remaining, $empdataremaining);

          }

          $data['remainings']   = $remaining;
          $data['leave_report'] = $leavedata;


          return view("member.payroll.modal.modal_monthly_leave_report", $data);

     }

     public function monthly_leave_report_excel($date_start,$date_end,$company)
     {

          $date[0] = date($date_start);
          $date[1] = date($date_end);

          $employee_id = Tbl_payroll_leave_employeev2::select('payroll_employee_id')->getemployeeid($date,Self::shop_id())->get();

          $datas = array(); 
          $remaining = array();                                               
          foreach($employee_id as $key => $emp_id)
          {
 
               $empdata = Tbl_payroll_leave_schedulev2::getmonthleavereportfilter($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
               $empdataremaining = Tbl_payroll_leave_schedulev2::getremainings($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
    
               array_push($datas, $empdata);
               array_push($remaining, $empdataremaining);

          }

          $data['date_start'] = $date[0];
          $data['date_end']   = $date[1];
          $data['leave_report'] = $datas;
          $data['remainings']   = $remaining;

          Excel::create("Montly Leave Action Report : ".$date[0].' - '.$date[1],function($excel) use ($data)
          {
               $excel->sheet('clients',function($sheet) use ($data)
               {
                    $sheet->loadView('member.payroll.modal.modal_monthly_leave_report_export_excel',$data);
               });
          })->download('xls');
     }

     public function monthly_leave_report_filter()
     {    
          $date_start = datepicker_input(Request::input('date_start'));
          $date_end = datepicker_input(Request::input('date_end'));
          $date[0] = date($date_start);
          $date[1] = date($date_end);
          $company = Request::input('company_id');

          $employee_id = Tbl_payroll_leave_employeev2::select('payroll_employee_id')->getemployeeid($date,Self::shop_id())->get();
          $category    = Request::input('category');

          $datas = array(); 
          $remaining = array();      
          $remwithpay    = array();
          $remwithoutpay = array();      
          if($category == 'monthly_leave')      
          {
                    foreach($employee_id as $key => $emp_id)
                    {
           
                        $empdata = Tbl_payroll_leave_schedulev2::getmonthleavereportfilter($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
                        $empdataremaining = Tbl_payroll_leave_schedulev2::getremainings($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();

                         array_push($datas, $empdata); 
                         array_push($remaining, $empdataremaining);
                    }

                    $data['leave_report'] = $datas;
                    $data['remainings']   = $remaining;
          }  
          else if($category == 'monthly_action')
          {
               if($company == 0)
               {
                    $data['leave_report'] = Tbl_payroll_leave_report::select('*')->where('shop_id',Self::shop_id())->whereBetween('payroll_report_date_created', $date)->get();
               }
               else
               {
                    $data['leave_report'] = Tbl_payroll_leave_report::join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_report.payroll_employee_id')->select('tbl_payroll_leave_report.*')->where('tbl_payroll_leave_report.shop_id',Self::shop_id())->where('tbl_payroll_employee_basic.payroll_employee_company_id',$company)->whereBetween('tbl_payroll_leave_report.payroll_report_date_created', $date)->get();
               }
             
          }          
          else if($category == 'monthly_without')
          {

               $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->get();
               $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

               foreach($payroll_employee_id as $key => $emp_id)
               {
                    $empdataremaining = Tbl_payroll_leave_schedulev2::getremainings($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
                    $empdata          = Tbl_payroll_leave_schedulev2::getleavewithoutpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();


                    array_push($datas, $empdata); 
                    array_push($remaining, $empdataremaining);
               }


                   $data['leave_report'] = $datas;
                   $data['remainings']   = $remaining;
          }
          else if($category == 'monthly_with')
          {
               $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->get();
               $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

               foreach($payroll_employee_id as $key => $emp_id)
               {
                    $empdataremaining  = Tbl_payroll_leave_schedulev2::getremainings($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
                    $empdata           = Tbl_payroll_leave_schedulev2::getleavewithpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();

                    array_push($datas, $empdata); 
                    array_push($remaining, $empdataremaining);
               }

                   $data['leave_report'] = $datas;
                   $data['remainings']   = $remaining;
          }
          else if($category == 'monthly_remaining')
          {
              
               $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->get();

               $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

               foreach($payroll_employee_id as $key => $emp_id)
               {    
                    $empdataremwithpay     = Tbl_payroll_leave_schedulev2::getleavewithpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();
                    $empdataremwithoutpay  = Tbl_payroll_leave_schedulev2::getleavewithoutpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();
                    $empdata               = Tbl_payroll_leave_schedulev2::getviewleavedata($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();

                    array_push($datas, $empdata); 
                    array_push($remwithpay, $empdataremwithpay); 
                    array_push($remwithoutpay, $empdataremwithoutpay); 
               } 

               $data['leave_report']    = $datas;
               $data['remwithpay']      = $remwithpay;
               $data['remwithoutpay']   = $remwithoutpay;   
          }
          else if($category == 'annual_leave')
          {
               $leave_annual = null;

               $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->where('shop_id',Self::shop_id())->get();

               $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

               $datas     = array();                                        
               foreach($payroll_employee_id as $key => $emp_id)
               {
          
                       for($month=1;$month<=12;$month++)
                       {
                              $empdata = Tbl_payroll_leave_schedulev2::getannualleave($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$month,$company)->get();

                              foreach($empdata as $key2 => $empdat)
                              {
                                  $leavetempname = $emp_id->payroll_employee_id.'-'.$empdat->payroll_leave_temp_name;
                                  if(!isset($leave_annual[$leavetempname]))
                                  {
                                    $leave_annual[$leavetempname]    = new stdClass();
                                  }

                                   $monthname = DateTime::createFromFormat('!m', $month)->format('F');

                                   $leave_annual[$leavetempname]->$monthname                    = $empdat->total_leave_consume;
                                   $leave_annual[$leavetempname]->payroll_employee_display_name = $empdat->payroll_employee_display_name;
                                   $leave_annual[$leavetempname]->payroll_employee_number       = $empdat->payroll_employee_id;
                                   $leave_annual[$leavetempname]->payroll_leave_name            = $empdat->payroll_leave_temp_name;
                              }

                              
                       }

               }
          
               $tempmonth = date("Y-m-d");
               $splitdate = explode("-", $tempmonth);
               $data['lastyear'] = $splitdate[0] - 1;
               $data['leave_report'] = $leave_annual;
          }

          $data['date_start']  = $date[0];
          $data['date_end']    = $date[1];
          $data['category']    = $category;
          $data['company']     = $company;

          return view('member.payroll.modal.modal_monthly_leave_report_filter', $data);


          
     }

     public function modal_remaining_leave_report()
     {

          $date[0] = date("Y-m-d");
          $date[1] = date("Y-m-d");
          $data["_company"] = Payroll::company_heirarchy(Self::shop_id());
          $company = 0;

          $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->get();

          $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

          $datas         = array(); 
          $remwithpay    = array();
          $remwithoutpay = array();                                           
          foreach($payroll_employee_id as $key => $emp_id)
          {    
               $empdataremwithpay     = Tbl_payroll_leave_schedulev2::getleavewithpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();
               $empdataremwithoutpay  = Tbl_payroll_leave_schedulev2::getleavewithoutpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();
               $empdata               = Tbl_payroll_leave_schedulev2::getviewleavedata($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();

               array_push($datas, $empdata); 
               array_push($remwithpay, $empdataremwithpay); 
               array_push($remwithoutpay, $empdataremwithoutpay); 
          }    


          $data['leave_report']    = $datas;
          $data['remwithpay']      = $remwithpay;
          $data['remwithoutpay']   = $remwithoutpay;
          
          // dd($remwithoutpay);

         return view("member.payroll.modal.modal_remaining_leave_report",$data);
     }

     public function remaining_leave_report_excel($date_start,$date_end,$company)
     {

          $date[0] = date($date_start);
          $date[1] = date($date_end);

           $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->get();

          $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

          $datas         = array(); 
          $remwithpay    = array();
          $remwithoutpay = array();                                              
          foreach($payroll_employee_id as $key => $emp_id)
          {    
               $empdataremwithpay     = Tbl_payroll_leave_schedulev2::getleavewithpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();
               $empdataremwithoutpay  = Tbl_payroll_leave_schedulev2::getleavewithoutpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();
               $empdata               = Tbl_payroll_leave_schedulev2::getviewleavedata($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();

               array_push($datas, $empdata); 
               array_push($remwithpay, $empdataremwithpay); 
               array_push($remwithoutpay, $empdataremwithoutpay); 
          }    
          
          $data['date_start'] = $date[0];
          $data['date_end']   = $date[1];
          $data['leave_report']    = $datas;
          $data['remwithpay']      = $remwithpay;
          $data['remwithoutpay']   = $remwithoutpay;
       
                    
         Excel::create("Remaining Leave Report : ".$date[0].' - '.$date[1],function($excel) use ($data)
          {
               $excel->sheet('clients',function($sheet) use ($data)
               {
                    $sheet->loadView('member.payroll.modal.modal_remaining_leave_report_export_excel',$data);
               });
          })->download('xls');
     }

     public function modal_pay_leave_report()
     {

          $date[0] = date("Y-m-d");
          $date[1] = date("Y-m-d");
          $data["_company"] = Payroll::company_heirarchy(Self::shop_id());
          $company = 0;

          $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->get();

          $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

          $datas     = array();  
          $remaining = array();                                              
          foreach($payroll_employee_id as $key => $emp_id)
          {
               $empdataremaining  = Tbl_payroll_leave_schedulev2::getremainings($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
               $empdata           = Tbl_payroll_leave_schedulev2::getleavewithpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();

               array_push($datas, $empdata); 
               array_push($remaining, $empdataremaining);
          }
               
         $data['leave_report'] = $datas;
         $data['remainings']   = $remaining;

          return view("member.payroll.modal.modal_pay_leave_report",$data);
     }

     public function pay_leave_report_excel($date_start,$date_end,$company)
     {

          $date[0] = date($date_start);
          $date[1] = date($date_end);

          
          $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->get();

          $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

          $datas     = array();  
          $remaining = array();                                              
          foreach($payroll_employee_id as $key => $emp_id)
          {
                $empdataremaining  = Tbl_payroll_leave_schedulev2::getremainings($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
               $empdata           = Tbl_payroll_leave_schedulev2::getleavewithpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();

               array_push($datas, $empdata); 
               array_push($remaining, $empdataremaining);
          }

          $data['date_start'] = $date[0];
          $data['date_end']   = $date[1];

          $data['leave_report'] = $datas;
          $data['remainings']   = $remaining;

          
          Excel::create("Leave With Pay Report : ".$date[0].' - '.$date[1],function($excel) use ($data)
          {
               $excel->sheet('clients',function($sheet) use ($data)
               {
                    $sheet->loadView('member.payroll.modal.modal_pay_leave_report_export_excel',$data);
               });
          })->download('xls');
     }

     public function modal_withoutpay_leave_report()
     {

          $date[0] = date("Y-m-d");
          $date[1] = date("Y-m-d");
          $data["_company"] = Payroll::company_heirarchy(Self::shop_id());
          $company = 0;

          $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->get();

          $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

          $datas     = array();   
          $remaining = array();                                        
          foreach($payroll_employee_id as $key => $emp_id)
          {
               $empdataremaining = Tbl_payroll_leave_schedulev2::getremainings($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
               $empdata          = Tbl_payroll_leave_schedulev2::getleavewithoutpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();


               array_push($datas, $empdata); 
               array_push($remaining, $empdataremaining);
          }
               
         $data['leave_report'] = $datas;
         $data['remainings']   = $remaining;

          return view("member.payroll.modal.modal_withoutpay_leave_report",$data);
     }

     public function withoutpay_leave_report_excel($date_start,$date_end,$company)
     {

          $date[0] = date($date_start);
          $date[1] = date($date_end);


          $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->get();

          $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

          $datas     = array();   
          $remaining = array();                                        
          foreach($payroll_employee_id as $key => $emp_id)
          {
               $empdataremaining = Tbl_payroll_leave_schedulev2::getremainings($emp_id['payroll_employee_id'],$date,$company,Self::shop_id())->get();
               $empdata          = Tbl_payroll_leave_schedulev2::getleavewithoutpay($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$date,$company,Self::shop_id())->get();

               array_push($datas, $empdata); 
               array_push($remaining, $empdataremaining);
          }

         $data['date_start'] = $date[0];
         $data['date_end']   = $date[1];
         $data['leave_report'] = $datas;
         $data['remainings']   = $remaining;

          Excel::create("Leave Withoutpay Report : ".$date[0].' - '.$date[1],function($excel) use ($data)
          {
               $excel->sheet('clients',function($sheet) use ($data)
               {
                    $sheet->loadView('member.payroll.modal.modal_withoutpay_leave_report_export_excel',$data);
               });
          })->download('xls');
     }

     public function modal_leave_action_report()
     {

          $date[0] = date("Y-m-d");
          $date[1] = date("Y-m-d");

          $data["_company"] = Payroll::company_heirarchy(Self::shop_id());
          $data['leave_report'] = Tbl_payroll_leave_report::select('*')->where('shop_id',Self::shop_id())->whereBetween('payroll_report_date_created', $date)->get();

          return view("member.payroll.modal.modal_leave_action_report",$data);
     }

     public function leave_action_report_excel($date_start,$date_end,$company)
     {

          $date[0] = date($date_start);
          $date[1] = date($date_end);

         if($company == 0)
          {
               $data['leave_report'] = Tbl_payroll_leave_report::select('*')->where('shop_id',Self::shop_id())->whereBetween('payroll_report_date_created', $date)->get();
          }
          else
          {
               $data['leave_report'] = Tbl_payroll_leave_report::join('tbl_payroll_employee_basic','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_leave_report.payroll_employee_id')->select('tbl_payroll_leave_report.*')->where('tbl_payroll_leave_report.shop_id',Self::shop_id())->where('tbl_payroll_employee_basic.payroll_employee_company_id',$company)->whereBetween('tbl_payroll_leave_report.payroll_report_date_created', $date)->get();
          }

          $data['date_start'] = $date[0];
          $data['date_end']   = $date[1];
          Excel::create("Leave Action Report : ".$date[0].' - '.$date[1],function($excel) use ($data)
          {
               $excel->sheet('clients',function($sheet) use ($data)
               {
                    $sheet->loadView('member.payroll.modal.modal_leave_action_report_export_excel',$data);
               });
          })->download('xls');
     }

     public function modal_leave_annual_report()
     {    
          $leave_annual = null;
          $data["_company"] = Payroll::company_heirarchy(Self::shop_id());
          $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->where('shop_id',Self::shop_id())->get();

          $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();
                                    
          foreach($payroll_employee_id as $key => $emp_id)
          {
     
                  for($month=1;$month<=12;$month++)
                  {
                         $empdata = Tbl_payroll_leave_schedulev2::getannualleave($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$month,0)->get();

                         foreach($empdata as $key2 => $empdat)
                         {
                             $leavetempname = $emp_id->payroll_employee_id.'-'.$empdat->payroll_leave_temp_name;
                             if(!isset($leave_annual[$leavetempname]))
                             {
                               $leave_annual[$leavetempname]    = new stdClass();
                             }

                              $monthname = DateTime::createFromFormat('!m', $month)->format('F');

                              $leave_annual[$leavetempname]->$monthname                    = $empdat->total_leave_consume;
                              $leave_annual[$leavetempname]->payroll_employee_display_name = $empdat->payroll_employee_display_name;
                              $leave_annual[$leavetempname]->payroll_employee_number       = $empdat->payroll_employee_id;
                              $leave_annual[$leavetempname]->payroll_leave_name            = $empdat->payroll_leave_temp_name;
                         }

                         
                  }

          }
     
          $tempmonth = date("Y-m-d");
          $splitdate = explode("-", $tempmonth);
          $data['lastyear'] = $splitdate[0] - 1;
          $data['leave_report'] = $leave_annual;
          return view("member.payroll.modal.modal_leave_annual_report",$data);
     }

     public function leave_annual_export($company)
     {
          $leave_annual = null;

          $payroll_leave_temp_id = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_id')->where('shop_id',Self::shop_id())->get();

          $payroll_employee_id   = Tbl_payroll_leave_employeev2::getemployeeidbytempid($payroll_leave_temp_id,Self::shop_id())->get();

          $datas     = array();                                        
          foreach($payroll_employee_id as $key => $emp_id)
          {
     
                  for($month=1;$month<=12;$month++)
                  {
                         $empdata = Tbl_payroll_leave_schedulev2::getannualleave($emp_id['payroll_employee_id'],$emp_id['payroll_leave_employee_id'],$month,$company)->get();

                         foreach($empdata as $key2 => $empdat)
                         {
                             $leavetempname = $emp_id->payroll_employee_id.'-'.$empdat->payroll_leave_temp_name;
                             if(!isset($leave_annual[$leavetempname]))
                             {
                               $leave_annual[$leavetempname]    = new stdClass();
                             }

                              $monthname = DateTime::createFromFormat('!m', $month)->format('F');

                              $leave_annual[$leavetempname]->$monthname                    = $empdat->total_leave_consume;
                              $leave_annual[$leavetempname]->payroll_employee_display_name = $empdat->payroll_employee_display_name;
                              $leave_annual[$leavetempname]->payroll_employee_number       = $empdat->payroll_employee_id;
                              $leave_annual[$leavetempname]->payroll_leave_name            = $empdat->payroll_leave_temp_name;
                         }

                         
                  }

          }
     
          $tempmonth = date("Y-m-d");
          $splitdate = explode("-", $tempmonth);
          $data['lastyear'] = $splitdate[0] - 1;
          $data['leave_report'] = $leave_annual;
          Excel::create("Leave Annual Report : ".$data['lastyear'],function($excel) use ($data)
          {
               $excel->sheet('clients',function($sheet) use ($data)
               {
                    $sheet->loadView('member.payroll.modal.modal_leave_annual_excel',$data);
               });
          })->download('xls');

     }
     //end reporting v2

     public function modal_leave_action($payroll_leave_employee_id,$action,$remaining_leave)
     {

          if($action == 'reset')
          {
               $data['title']      = 'Do you really want to '.$action.'?';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/reset_leave_schedulev2';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'resetandaccum')
          {
               $action = 'Reset and Accumulate';
               $data['title']      = 'Do you really want to '.$action.'?';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/reset_and_accumulate_leave_schedulev2';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'convert')
          {
               $action = 'Convert to Cash';
               $data['title']      = 'Do you really want to '.$action.'?';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/convert_to_cash_leave_schedulev2';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'resethistory')
          {
               $action = 'Reset History';
               $data['title']      = 'Do you really want to '.$action.'?';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/reset_leave_schedule_history';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'archived_temp')
          {
               $action = 'Archived temp';
               $data['title']      = 'Do you really want to '.$action.'?';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/archived_leave_tempv2';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'restore_temp')
          {
               $action = 'Restore temp';
               $data['title']      = 'Do you really want to '.$action.'?';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/restore_leave_tempv2';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'archived_history')
          {
               $action = 'Archived History';
               $data['title']      = 'Do you really want to '.$action.'?';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/archived_leave_history';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'archivedwholeleave')
          {
               $action = 'Archived whole temp';
               $data['title']      = 'Do you really want to '.$action.'?';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/archived_whole_leave_tempv2';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'restorewholeleave')
          {
               $action = 'Restore whole temp';
               $data['title']      = 'Do you really want to '.$action.'?';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/restore_whole_leave_tempv2';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'remove_payroll_tag')
          {
               $action = 'remove this employee from tagging?';
               $data['title']      = 'Do you really want to '.$action.'';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/remove_employee_payroll_group_tag';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;
          }
          else if($action == 'remove_shift_tag')
          {
               $action = 'remove this employee from tagging?';
               $data['title']      = 'Do you really want to '.$action.'';
               $data['html']       = '';
               $data['action']     = '/member/payroll/leave/v2/remove_employee_shift_group_tag';
               $data['id']         = $payroll_leave_employee_id;
               $data['remaining_leave'] = $remaining_leave;  
          }

          return view('member.payroll.modal.modal_leave_reset_confirm',$data);

     }

     public function reset_leave_schedulev2()
     {
          $id                                             = Request::input('id');
          $update['payroll_leave_schedule_archived']      = 1;

          $name = Tbl_payroll_leave_employeev2::employee($id)->first();
          $leavename = Tbl_payroll_leave_schedulev2::where('payroll_leave_employee_id',$id)->value('payroll_leave_temp_name');

          $insert['payroll_employee_display_name']        = $name->payroll_employee_display_name;
          $insert['payroll_leave_action']                 = "reset";
          $insert['shop_id']                              = Self::shop_id();
          $insert['payroll_leave_temp_hours']             = $name->payroll_leave_temp_hours;
          $insert['payroll_leave_temp_name']              = $leavename;
          $insert['payroll_employee_id']                  = $name->payroll_employee_id;
          $insert['payroll_report_date_created']          = date("Y-m-d");
          $insert['payroll_leave_employee_id']            = $id;
          $insert['payroll_leave_hours_remaining']        = Request::input('remaining_leave');
          
          Tbl_payroll_leave_report::insert($insert);
          Tbl_payroll_leave_schedulev2::where('payroll_leave_employee_id',$id)->update($update);

          
          $response['call_function'] = 'reload';
          $response['status'] = 'success';

          return $response;
     }

     public function reset_and_accumulate_leave_schedulev2()
     {
          $id = Request::input('id');
          $update['payroll_leave_schedule_archived'] = 1;

          $total_leave_hours = Tbl_payroll_leave_employeev2::where('payroll_leave_employee_id',$id)
                                                       ->value('payroll_leave_temp_hours');

          $updates['payroll_leave_temp_hours'] = $total_leave_hours + Request::input('remaining_leave');

          $name = Tbl_payroll_leave_employeev2::employee($id)->first();
          $leavename = Tbl_payroll_leave_schedulev2::where('payroll_leave_employee_id',$id)->value('payroll_leave_temp_name');

          $insert['payroll_employee_display_name']        = $name->payroll_employee_display_name;
          $insert['payroll_leave_action']                 = "accumulate";
          $insert['shop_id']                              = Self::shop_id();
          $insert['payroll_employee_id']                  = $name->payroll_employee_id;
          $insert['payroll_leave_temp_hours']             = $name->payroll_leave_temp_hours;
          $insert['payroll_leave_temp_name']              = $leavename;
          $insert['payroll_report_date_created']          = date("Y-m-d");
          $insert['payroll_leave_employee_id']            = $id;
          $insert['payroll_leave_hours_remaining']        = Request::input('remaining_leave');
          $insert['payroll_leave_hours_accumulated']      = Request::input('remaining_leave');


          Tbl_payroll_leave_report::insert($insert);
          Tbl_payroll_leave_employeev2::where('payroll_leave_employee_id',$id)->update($updates);
          Tbl_payroll_leave_schedulev2::where('payroll_leave_employee_id',$id)->update($update);

          $response['call_function'] = 'reload';
          $response['status'] = 'success';
          
          return $response;
     }

     public function convert_to_cash_leave_schedulev2()
     {
          $id                                             = Request::input('id');
          $update['payroll_leave_schedule_archived']      = 1;
          $name = Tbl_payroll_leave_employeev2::employee($id)->first();  
          $payroll_employee_id = $name['payroll_employee_id'];
          $cash_converted = Tbl_payroll_employee_salary::where('payroll_employee_id',$payroll_employee_id)
                                                       ->value('payroll_employee_salary_daily');
          $leavename = Tbl_payroll_leave_schedulev2::where('payroll_leave_employee_id',$id)->value('payroll_leave_temp_name');
          $divide = ($cash_converted / 8) * Request::input('remaining_leave');                                             
          $insert['payroll_employee_display_name']        = $name->payroll_employee_display_name;
          $insert['payroll_leave_action']                 = "convert_to_cash";
          $insert['shop_id']                              = Self::shop_id();
          $insert['payroll_employee_id']                  = $name->payroll_employee_id;
          $insert['payroll_leave_temp_hours']             = $name->payroll_leave_temp_hours;
          $insert['payroll_leave_temp_name']              = $leavename;
          $insert['payroll_report_date_created']          = date("Y-m-d");
          $insert['payroll_leave_employee_id']            = $id;
          $insert['payroll_leave_hours_remaining']        = Request::input('remaining_leave');
          $insert['payroll_leave_cash_converted']         = round($divide,2);

          Tbl_payroll_leave_report::insert($insert);
          Tbl_payroll_leave_schedulev2::where('payroll_leave_employee_id',$id)->update($update);

          $response['call_function'] = 'reload';
          $response['status'] = 'success';
          
          return $response;
 
          return json_encode($return);
     }

     public function reset_leave_schedule_history()
     {
          $id = Request::input('id');
          Tbl_payroll_leave_history::where('payroll_leave_employee_id',$id)->delete();

          $response['call_function'] = 'reload';
          $response['status'] = 'success';
          
          return $response;
     }

     public function archived_leave_tempv2()
     {
          $id = Request::input('id');
          $update['payroll_leave_employee_is_archived'] = 1;
          $updates['payroll_leave_schedule_archived']    = 1;

          Tbl_payroll_leave_employeev2::where('payroll_leave_employee_id', $id)->update($update);
          Tbl_payroll_leave_schedulev2::where('payroll_leave_employee_id', $id)->update($updates);

          $return['status']             = 'success';
          $return['function_name']      = 'modal_create_leave_tempv2.load_for_edit_leave_temp';
          return json_encode($return);
     }

     public function restore_leave_tempv2()
     {
          $id = Request::input('id');
          $update['payroll_leave_employee_is_archived'] = 0;

          Tbl_payroll_leave_employeev2::where('payroll_leave_employee_id', $id)->update($update);

          $return['status']             = 'success';
          $return['function_name']      = 'modal_create_leave_tempv2.load_for_edit_leave_temp';
          return json_encode($return);
     }

     public function archived_leave_history()
     {
          $id = Request::input('id');
          $update['payroll_leave_history_archived']    = 1;

          Tbl_payroll_leave_history::where('payroll_leave_history_id', $id)->update($update);

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_leavev2_temp';
          return json_encode($return);
     }

     public function archived_whole_leave_tempv2()
     {
          $id = Request::input('id');
          $update['payroll_leave_temp_archived']    = 1;

          Tbl_payroll_leave_tempv2::where('payroll_leave_temp_id', $id)->update($update);

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_leavev2_temp';
          return json_encode($return);
     }

     public function restore_whole_leave_tempv2()
     {
          $id = Request::input('id');
          $update['payroll_leave_temp_archived']    = 0;

          Tbl_payroll_leave_tempv2::where('payroll_leave_temp_id', $id)->update($update);

          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_leavev2_temp';
          return json_encode($return);
     }

// scheduling leave
     public function save_schedule_leave_tagv2()
     {
           // Tbl_payroll_leave_schedule
          $payroll_schedule_leave = datepicker_input(Request::input('payroll_schedule_leave'));

          $payroll_leave_date_created = datepicker_input(Request::input('payroll_leave_date_created'));

          if(Request::has('employee_tag'))
          {
               $insert = array();
               $inserthistory = array();
               $leave_reason = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_name')->where('payroll_leave_temp_id',Request::input("leave_reason"))->where('shop_id',$this->user_info["user_shop"])->first();
          
               foreach(Request::input('employee_tag') as $tag)
               {
                    $leave_hours = Request::input("leave_hours_".$tag);
                    $name = Tbl_payroll_leave_employeev2::employee($tag)->first();

                    if($leave_hours != 0)
                    {
                         if(Request::has('single_date_only'))
                         {
                              $temp['payroll_leave_employee_id']    = $temp2['payroll_leave_employee_id']      = $tag;

                              $temp['payroll_schedule_leave']       = $temp2['payroll_leave_date_applied']     = $payroll_schedule_leave;

                              $temp['payroll_leave_date_created']   = $temp2['payroll_leave_date_created']     = $payroll_leave_date_created;

                              $temp['payroll_leave_temp_with_pay']  = Request::input('payroll_leave_temp_with_pays');
                              $temp['payroll_leave_temp_name']      = $leave_reason["payroll_leave_temp_name"];

                              $temp['shop_id']                      = $temp2['shop_id']                                 = Self::shop_id();

                              $temp['leave_hours']                  = $leave_hours;
                              $temp['consume']                      = $temp2['consume']
                                                                    = Payroll::time_float($leave_hours);

                              $temp['notes']                        = "Used ".$leave_hours." hours in ".$leave_reason["payroll_leave_temp_name"];

                            $temp2['payroll_employee_display_name'] =  $name->payroll_employee_display_name;

                              array_push($insert, $temp);
                              array_push($inserthistory,$temp2);
                         }

                         else
                         {
                              $end = datepicker_input(Request::input('payroll_schedule_leave_end'));
                                        $payroll_schedule_leave = datepicker_input(Request::input('payroll_schedule_leave'));
                              while($payroll_schedule_leave <= $end)
                              {
                                   $temp['payroll_leave_employee_id']    = $temp2['payroll_leave_employee_id']      = $tag;

                                   $temp['payroll_schedule_leave']       = $temp2['payroll_leave_date_applied']     = $payroll_schedule_leave;

                                   $temp['payroll_leave_date_created']   = $temp2['payroll_leave_date_created']     = $payroll_leave_date_created;

                                   $temp['payroll_leave_temp_with_pay']  = Request::input('payroll_leave_temp_with_pays');
                                   $temp['payroll_leave_temp_name']      = $leave_reason["payroll_leave_temp_name"];

                                   $temp['shop_id']                      = $temp2['shop_id']                                 = Self::shop_id();

                                   $temp['leave_hours']                  = $leave_hours;
                                   $temp['consume']                      = $temp2['consume']
                                                                         = Payroll::time_float($leave_hours);

                                   $temp['notes']                        = "Used ".$leave_hours." hours in ".$leave_reason["payroll_leave_temp_name"];

                                 $temp2['payroll_employee_display_name'] =  $name->payroll_employee_display_name;

                                   array_push($insert, $temp);
                                   array_push($inserthistory,$temp2);

                                   $payroll_schedule_leave = Carbon::parse($payroll_schedule_leave)->addDay()->format("Y-m-d");
                              }
                         }
                    }
               }
               if(!empty($insert) && !empty($inserthistory)) 
               {  
                    Tbl_payroll_leave_schedulev2::insert($insert);
                    Tbl_payroll_leave_history::insert($inserthistory);
               }
          }    

          $data['status']         = 'success';
          $data['function_name']   = 'payrollconfiguration.reload_leavev2_temp';

          return collect($data)->toJson();
         
     }

     public function leave_schedule_tag_employeev2($leave_temp_id,$leave_pay)
     {

          $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();
          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          $data['leave_temp_id']        = $leave_temp_id;
          $data['payroll_leave_temp_with_pay'] = $leave_pay;
          $data['action']          = '/member/payroll/leave_schedule/v2/session_tag_leavev2';

          Session::put('employee_leave_tag', array());

     return view('member.payroll.modal.modal_schedule_employee_leavev2', $data);
     }

     public function ajax_schedule_leave_tag_employeev2()
     {

          $company                   = Request::input('company');
          $department                = Request::input('department');
          $jobtitle                  = Request::input('jobtitle');
          $leave_id                  = Request::input("leave_id");
          $payroll_leave_pay_value   = Request::input("payroll_leave_pay_value");

          if($payroll_leave_pay_value == 1)
          {
        
                    $emp = Tbl_payroll_employee_contract::employeefilter($company, $department, $jobtitle, date('Y-m-d'), Self::shop_id())
                    ->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_employee_id','=','tbl_payroll_employee_contract.payroll_employee_id')
                    ->join('tbl_payroll_leave_tempv2','tbl_payroll_leave_tempv2.payroll_leave_temp_id','=','tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
                    ->leftjoin('tbl_payroll_leave_schedulev2','tbl_payroll_leave_schedulev2.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
                    ->where('tbl_payroll_leave_tempv2.payroll_leave_temp_id',$leave_id)
                    ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_is_archived', 0)
                    ->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')
                    ->groupBy('tbl_payroll_employee_basic.payroll_employee_id')                                                                                           
                    ->select(DB::raw('*, tbl_payroll_leave_employee_v2.payroll_leave_employee_id as leave_employee_id, tbl_payroll_leave_employee_v2.payroll_leave_employee_id as payroll_leave_employee_id_2'))
                    ->get();
               

          }
          else
          {
               if($leave_id == 0)
               {
                     $emp = Tbl_payroll_employee_contract::employeefilter($company, $department, $jobtitle, date('Y-m-d'), Self::shop_id())
                    ->join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_employee_id','=','tbl_payroll_employee_contract.payroll_employee_id')
                    ->join('tbl_payroll_leave_tempv2','tbl_payroll_leave_tempv2.payroll_leave_temp_id','=','tbl_payroll_leave_employee_v2.payroll_leave_temp_id')
                    ->leftjoin('tbl_payroll_leave_schedulev2','tbl_payroll_leave_schedulev2.payroll_leave_employee_id','=','tbl_payroll_leave_employee_v2.payroll_leave_employee_id')
                    ->where('tbl_payroll_leave_tempv2.payroll_leave_temp_id',$leave_id)
                    ->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_is_archived', 0)
                    ->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')
                    ->groupBy('tbl_payroll_employee_basic.payroll_employee_id')                                                                                           
                    ->select(DB::raw('*, tbl_payroll_leave_employee_v2.payroll_leave_employee_id as leave_employee_id, tbl_payroll_leave_employee_v2.payroll_leave_employee_id as payroll_leave_employee_id_2'))
                    ->get();
               }
               else
               {
                    $emp = Tbl_payroll_employee_contract::employeefilter($company, $department, $jobtitle, date('Y-m-d'), Self::shop_id())->select(DB::raw('*, tbl_payroll_employee_basic.payroll_employee_id as payroll_leave_employee_id_3'))->get();
               }
  
          }

          return json_encode($emp);
     }

     public function session_tag_leavev2()
     {
          $employee_tag = array();
             $pay_value = Request::input('payroll_leave_temp_with_pay');

          if(Session::has('employee_leave_tag'))
          {
               $employee_tag = Session::get('employee_leave_tag');
          }

          if($pay_value == 0)
          {
               foreach(Request::input('employee_tags') as $tag)
               {
                    if(!in_array($tag, $employee_tag))
                    {
                         array_push($employee_tag, $tag);
                    }
               }
          }
          else
          {
               foreach(Request::input('employee_tag') as $tag)
               {
                    if(!in_array($tag, $employee_tag))
                    {
                         array_push($employee_tag, $tag);
                    }
               }
          }

           Session::put('employee_leave_tag', $employee_tag);
          


          $data['status'] = 'success';
          $data['function_name'] = 'employee_tag_schedule_leave.load_tagged_employee';

          return collect($data)->toJson();
     }

     public function unset_session_leave_tagv2()
     {
          $content = Request::input('content');
          $array     = Session::get('employee_leave_tag');
          if(($key = array_search($content, $array)) !== false) {
              unset($array[$key]);
          }
          Session::put('employee_leave_tag', $array);
     }

     public function get_session_leave_tagv2()
     {    
          $leavetypeid = Request::input('leavetempid');
          $leavetempname = Tbl_payroll_leave_tempv2::select('payroll_leave_temp_name')->where('payroll_leave_temp_id',$leavetypeid)->get();
   
          $leave_pay_value = Request::input('leave_pay_value');
          $insert = array();
          $leavedat = array();
          $leaveempid = array();

          $employee = [0 => 0];
          if(Session::has('employee_leave_tag'))
          {
               $employee = Session::get('employee_leave_tag');
          }

          if($leave_pay_value == 0)
          {
               foreach($employee as $emp)
               {
                    $checkemployee = Tbl_payroll_leave_employeev2::where('payroll_employee_id',$emp)->where('payroll_leave_temp_id',$leavetypeid)->get();

                    if(count($checkemployee) == 0)
                    {
                         $insert['payroll_leave_temp_id'] = $leavetypeid;
                         $insert['payroll_leave_temp_hours'] = 8;
                         $insert['payroll_employee_id'] = $emp;
                         Tbl_payroll_leave_employeev2::insert($insert);

                         $getlastleaveid = tbl_payroll_leave_employeev2::select('payroll_leave_employee_id')->orderBy('payroll_leave_employee_id','DESC')->first();
                         $payroll_leave_temp_id = $getlastleaveid['payroll_leave_employee_id'];
                         array_push($leaveempid,$payroll_leave_temp_id);
                    }
                    else
                    {
                              foreach($checkemployee as $key => $empid)
                              {
                                   $empdat = Tbl_payroll_leave_schedulev2::getallemployeeleavedata($empid['payroll_leave_employee_id'])->get();
                                   if(count($empdat) != 0)
                                   {

                                        array_push($leavedat,$empdat);
                                   }
                                   else
                                   {
                                              $empdata = Tbl_payroll_employee_basic::join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_employee_id','=','tbl_payroll_employee_basic.payroll_employee_id')->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id',$empid['payroll_leave_employee_id'])->get();

                                              array_push($leavedat,$empdata);
                                        
                                   }
                              }                    
                    }

               }
               if(!empty($leaveempid))
               {
                    foreach($leaveempid as $leaveid)
                    {

                               $empdata = Tbl_payroll_employee_basic::join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_employee_id','=','tbl_payroll_employee_basic.payroll_employee_id')->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id',$leaveid)->get();

                               array_push($leavedat,$empdata);
                    }
               }

          }
          else
          {    
                         foreach($employee as $emp)
                         {
                              $employee_id = Tbl_payroll_leave_employeev2::select('payroll_employee_id')
                                                                      ->join('tbl_payroll_leave_schedulev2','tbl_payroll_leave_employee_v2.payroll_leave_employee_id','=','tbl_payroll_leave_schedulev2.payroll_leave_employee_id')
                                                                      ->where('tbl_payroll_leave_schedulev2.payroll_leave_employee_id', $emp)
                                                                      ->distinct()
                                                                      ->get();
                                                  
                              if(count($employee_id) == 0)
                              {
                                   $empdat = Tbl_payroll_employee_basic::join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_employee_id','=','tbl_payroll_employee_basic.payroll_employee_id')->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id',$emp)->get();

                                   array_push($leavedat,$empdat);
                              }
                              else    
                              {
                                   $empdat = Tbl_payroll_leave_schedulev2::getallemployeeleavedata($emp)->get();
                                   if(count($empdat) != 0)
                                   {

                                        array_push($leavedat,$empdat);
                                   }
                                   else
                                   {
                                       
                                              $empdata = Tbl_payroll_employee_basic::join('tbl_payroll_leave_employee_v2','tbl_payroll_leave_employee_v2.payroll_employee_id','=','tbl_payroll_employee_basic.payroll_employee_id')->where('tbl_payroll_leave_employee_v2.payroll_leave_employee_id',$emp)->get();

                                              array_push($leavedat,$empdata);
                                        

                                   }
                               
                              }         

                         }
          }

          $data['new_record']  = $leavedat;

          return json_encode($data);
     }

     /* LEAVE V2 END */


     /* PAYROLL GROUP START */
     public function payroll_group()
     {
          // Tbl_payroll_overtime_rate
          $data['_active']   = Tbl_payroll_group::sel(Self::shop_id())->orderBy('payroll_group_code')->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_group::sel(Self::shop_id(), 1)->orderBy('payroll_group_code')->paginate($this->paginate_count);
          // dd($data);
          return view('member.payroll.side_container.payroll_group', $data);
     }

     public function modal_create_payroll_group()
     {
          Session::put('payroll_group_tag_employee', array());
          $data['_overtime_rate']  = Tbl_payroll_over_time_rate_default::get();
          $data['_day']            = Payroll::restday_checked(); 
          $data['_period']         = Tbl_payroll_tax_period::check(Self::shop_id())->get();
          $data['_shift_code']     = Tbl_payroll_shift_code::getshift(Self::shop_id())->orderBy('shift_code_name')->get();

          $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

          return view('member.payroll.modal.modal_create_payroll_group', $data);
     }

     public function modal_tag_payroll_group_employee()
     {
          $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

          $data['deduction_id']    =    0;

          $data['action']               ='/member/payroll/payroll_group/set_tag_payroll_group_employee';
          return view('member.payroll.modal.modal_deduction_tag_employee', $data);
     }

     public function modal_payroll_tag_edit()
     {
           $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

          $data['deduction_id']    =    0;

          $data['action']               ='/member/payroll/payroll_group/set_tag_payroll_group_employee_edit';
          return view('member.payroll.modal.modal_deduction_tag_employee', $data); 
     }

     public function set_tag_payroll_group_employee_edit()
     {
          $employee_tag = Request::input('employee_tag');

          $array = array();
          if(Session::has('payroll_edit_group_tag_employee'))
          {
               $array = Session::get('payroll_edit_group_tag_employee');
          }


          if(isset($employee_tag)){
               foreach($employee_tag as $tag)
               {
                    array_push($array, $tag);
               }    
          }

          Session::put('payroll_edit_group_tag_employee',$array);
          $return['status']             = 'success';
          $return['function_name']      = 'payroll_group.load_tag_employee_editv2';
          
          return json_encode($return);
     }

     public function get_payroll_tag_employee_edit()
     {
          $group_id = Request::input('group_id');
          $employee = [0 => 0];
          if(Session::has('payroll_edit_group_tag_employee'))
          {
               $employee = Session::get('payroll_edit_group_tag_employee');
          }

          $empsession = Tbl_payroll_employee_basic::whereIn('payroll_employee_id',$employee)->get();
          $empdata = Tbl_payroll_employee_basic::join('tbl_payroll_employee_contract','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_employee_contract.payroll_employee_id')->where('tbl_payroll_employee_contract.payroll_group_id',$group_id)->where('shop_id',Self::shop_id())->get();
          
          $emp = array();
          foreach($empsession as $empses)
          {
               $temp['payroll_employee_id']            = $empses->payroll_employee_id;
               $temp['payroll_employee_display_name']  = $empses->payroll_employee_display_name;

               $checkcontract = Tbl_payroll_employee_contract::selemployee($empses->payroll_employee_id)->first();
               if($checkcontract['payroll_group_id'] != $group_id)
               {
                    array_push($emp,$temp);
               }

               $update['payroll_group_id'] = $group_id;
               Tbl_payroll_employee_contract::where('payroll_employee_id',$empses->payroll_employee_id)->update($update);

          }

          foreach($empdata as $empdat)
          {
               $temp2['payroll_employee_id']            = $empdat->payroll_employee_id;
               $temp2['payroll_employee_display_name']  = $empdat->payroll_employee_display_name;
               array_push($emp,$temp2);
          }

          $data['group_id']   = $group_id;
          $data['new_record'] = $emp;

          return json_encode($data);
     }

     public function set_tag_payroll_group_employee()
     {
          $employee_tag = Request::input('employee_tag');

          $array = array();
          if(Session::has('payroll_group_tag_employee'))
          {
               $array = Session::get('payroll_group_tag_employee');
          }


          if(isset($employee_tag)){
               foreach($employee_tag as $tag)
               {
                    array_push($array, $tag);
               }    
          }

          Session::put('payroll_group_tag_employee',$array);
          $return['status']             = 'success';
          $return['function_name']      = 'payroll_group.load_tag_employee';
          
          return json_encode($return);
     }

     public function get_payroll_tag_employee()
     {
          $employee = [0 => 0];
          if(Session::has('payroll_group_tag_employee'))
          {
               $employee = Session::get('payroll_group_tag_employee');
          }
          $emp = Tbl_payroll_employee_basic::whereIn('payroll_employee_id',$employee)->get();

          $data['new_record'] = $emp;

          return json_encode($data);
     }

     public function remove_payroll_tag_employee()
     {
           $content = Request::input('content');
          $array     = Session::get('payroll_group_tag_employee');
          if(($key = array_search($content, $array)) !== false) {
              unset($array[$key]);
          }
          Session::put('payroll_group_tag_employee',$array);
     }

     public function remove_employee_payroll_group_tag()
     {
         $payroll_employee_id = Request::input('id');
         $payroll_group_id    = Request::input('remaining_leave');

         $update['payroll_group_id'] = null;
         Tbl_payroll_employee_contract::where('payroll_employee_id',$payroll_employee_id)->where('payroll_group_id',$payroll_group_id)->update($update);

          $return['status']             = 'success';
          $return['function_name']      = 'payroll_group.load_edit_tag_employee';
          
          return json_encode($return);

     }

     public function load_payroll_employee_tag()
     {
          $group_id = Request::input('group_id');
          $emp = Tbl_payroll_employee_basic::join('tbl_payroll_employee_contract','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_employee_contract.payroll_employee_id')->where('tbl_payroll_employee_contract.payroll_group_id',$group_id)->where('shop_id',Self::shop_id())->get();

          $data['new_record'] = $emp;

          return json_encode($data);
     }

     public function modal_save_payroll_group()
     {
          $insert['shop_id']                                = Self::shop_id();
          $insert['payroll_group_code']                     = Request::input('payroll_group_code');
          $insert['payroll_group_salary_computation']       = Request::input('payroll_group_salary_computation');
          $insert['payroll_group_period']                   = Request::input('payroll_group_period');
          $insert['payroll_group_13month_basis']            = Request::input('payroll_group_13month_basis');
          $insert['payroll_group_cola_basis']               = Request::input('payroll_group_cola_basis');

          if(Request::has('payroll_group_deduct_before_absences'))
          {
               $insert['payroll_group_deduct_before_absences'] = Request::input('payroll_group_deduct_before_absences');
          }

          $payroll_group_before_tax                         = 0;

          if(Request::has("payroll_group_before_tax"))
          {
               $payroll_group_before_tax = Request::input('payroll_group_before_tax');
          }

          $insert['display_monthly_rate']    = 0;
          $insert['display_daily_rate']      = 0;
          
          if(Request::has("display_monthly_rate"))
          {
               $insert['display_monthly_rate'] = 1;
          }

          if(Request::has("display_daily_rate"))
          {
               $insert['display_daily_rate'] = 1;
          }
          
          $insert['payroll_group_before_tax']               = $payroll_group_before_tax;
          $insert['payroll_group_tax']                      = Request::input('payroll_group_tax');
          $insert['payroll_group_sss']                      = Request::input('payroll_group_sss');
          $insert['payroll_group_philhealth']               = Request::input('payroll_group_philhealth');
          $insert['payroll_group_pagibig']                  = Request::input('payroll_group_pagibig');
          $insert['payroll_group_agency']                   = Request::input('payroll_group_agency');
          // $insert['payroll_group_target_hour']                = Request::input('payroll_group_target_hour');
          $insert['payroll_group_grace_time']               = Request::input('payroll_group_grace_time');
          $insert['payroll_group_agency_fee']               = Request::input('payroll_group_agency_fee');
          $insert['payroll_late_category']                  = Request::input('payroll_late_category');
          $insert['payroll_late_interval']                  = Request::input('payroll_late_interval');
          $insert['payroll_late_parameter']                 = Request::input('payroll_late_parameter');
          $insert['payroll_late_deduction']                 = Request::input('payroll_late_deduction');
          // if(Request::has('payroll_group_is_flexi_break'))
          // {
          //   $insert['payroll_group_is_flexi_break']      = Request::input('payroll_group_is_flexi_break');
          // }
          // $insert['payroll_group_break_start']                = date('H:i:s',strtotime(Request::input('payroll_group_break_start')));
          // $insert['payroll_group_break_end']                  = date('H:i:s',strtotime(Request::input('payroll_group_break_end')));
          // $insert['payroll_group_flexi_break']                = Request::input('payroll_group_flexi_break');
          
          // if(Request::has('payroll_group_is_flexi_time'))
          // {
          //   $insert['payroll_group_is_flexi_time']       = Request::input('payroll_group_is_flexi_time');
          // }
          
          $insert['payroll_group_working_day_month']        = Request::input('payroll_group_working_day_month');

          $payroll_group_target_hour_parameter              = 'Daily';

          // if(Request::has('payroll_group_target_hour_parameter'))
          // {
          //   $payroll_group_target_hour_parameter    = Request::input('payroll_group_target_hour_parameter');
          // }
          $insert['payroll_group_target_hour_parameter']    = $payroll_group_target_hour_parameter;
          // $insert['payroll_group_target_hour']                = Request::input('payroll_group_target_hour');
          // $insert['payroll_group_start']                 = date('H:i:s',strtotime(Request::input('payroll_group_start')));
          // $insert['payroll_group_end']                        = date('H:i:s',strtotime(Request::input('payroll_group_end')));

          // dd($insert);
          /* INSERT PAYROLL GROUP AND GET ID */ 

          $insert['payroll_under_time_category']  = Request::input("payroll_under_time_category");
          $insert['payroll_under_time_interval']  = Request::input("payroll_under_time_interval");
          $insert['payroll_under_time_parameter'] = Request::input("payroll_under_time_parameter");
          $insert['payroll_under_time_deduction'] = Request::input("payroll_under_time_deduction");
          $insert['payroll_break_category']       = Request::input("payroll_break_category");
          $insert['overtime_grace_time']          = Request::has('overtime_grace_time') ? date('H:i:s', strtotime(Request::input('overtime_grace_time'))) : '00:00:00';
          $insert['grace_time_rule_overtime']     = Request::has('grace_time_rule_overtime') ? Request::input('grace_time_rule_overtime') : 'accumulative';
          $insert['late_grace_time']              = Request::has('late_grace_time') ? date('H:i:s', strtotime(Request::input('late_grace_time'))) : '00:00:00';
          $insert['grace_time_rule_late']         = Request::has('grace_time_rule_late') ? Request::input("grace_time_rule_late") : 'first';
          $insert['tax_reference']                = Request::input('tax_reference');
          $insert['sss_reference']                = Request::input('sss_reference');
          $insert['philhealth_reference']         = Request::input('philhealth_reference');
          $insert['pagibig_reference']            = Request::input('pagibig_reference');
         
          $group_id = Tbl_payroll_group::insertGetId($insert);

          if(!empty(Request::input('employee_tag')))
          {
               foreach(Request::input('employee_tag') as $tag)
               {
                    $checkcontract = Tbl_payroll_employee_contract::selemployee($tag)->first();

                    if(count($checkcontract) > 0)
                    {
                         $update['payroll_group_id'] = $group_id;
                         Tbl_payroll_employee_contract::where('payroll_employee_id',$tag)->update($update);
                    }
               }
          }

          
          $insert_13th_month = array();

          $payroll_group_13th_month_addition    = Request::input('payroll_group_13th_month_addition');
          $insert_13th_month                    = array("payroll_group_13th_month_addition_allowance" => 0, "payroll_group_13th_month_addition_special_holiday" => 0, "payroll_group_13th_month_addition_regular_holiday" => 0, "payroll_group_13th_month_addition_cola" => 0, "payroll_group_13th_month_addition_late" => 0, "payroll_group_13th_month_addition_undertime" => 0, "payroll_group_13th_month_addition_absent" => 0);
          if ($payroll_group_13th_month_addition != null) 
          {
               foreach ($payroll_group_13th_month_addition as $key => $value) 
               {
                    $insert_13th_month[$value] = 1;
               }
          }
          

          $insert_13th_month['payroll_group_13month_basis']  = Request::input('payroll_group_13month_basis');
          $insert_13th_month['payroll_group_id']             =  $group_id;

          // Tbl_payroll_13th_month_basis::insert($insert_13th_month);

          $insert_rate = array();

          foreach(Request::input("payroll_overtime_name") as $key => $overtime)
          {
               $temp['payroll_group_id']                    = $group_id;
               $temp['payroll_overtime_name']               = Request::input("payroll_overtime_name")[$key];
               $temp['payroll_overtime_regular']            = Request::input("payroll_overtime_regular")[$key];
               $temp['payroll_overtime_overtime']           = Request::input("payroll_overtime_overtime")[$key];
               $temp['payroll_overtime_nigth_diff']         = Request::input("payroll_overtime_nigth_diff")[$key];
               $temp['payroll_overtime_rest_day']           = Request::input("payroll_overtime_rest_day")[$key];
               $temp['payroll_overtime_rest_overtime']      = Request::input("payroll_overtime_rest_overtime")[$key];
               $temp['payroll_overtime_rest_night']         = Request::input("payroll_overtime_rest_night")[$key];
               array_push($insert_rate, $temp);
          }
          
          
          /* INSERT PAYROLL OVERTIME NIGHT DIFFERENTIALS REST DAY HOLIDAY */
          Tbl_payroll_overtime_rate::insert($insert_rate);

          $insert_shift = array();
          if(Request::has('day'))
          {
               foreach(Request::input('day') as $key => $day)
               {
     
                    $temp_shift['payroll_group_id']    = $group_id;
                    $temp_shift['day']                 = $day;
                    $temp_shift['target_hours']        = Request::input('target_hours')[$key];
                    $temp_shift['work_start']          = date('H:i:s', strtotime(Request::input('work_start')[$key]));
                    $temp_shift['work_end']            = date('H:i:s', strtotime(Request::input('work_end')[$key]));
                    $temp_shift['break_start']         = date('H:i:s', strtotime(Request::input('break_start')[$key]));
                    $temp_shift['break_end']           = date('H:i:s', strtotime(Request::input('break_end')[$key]));
     
                    $flexi         = 0;
                    $rest_day      = 0;
                    $extra_day     = 0;
     
                    if(Request::has('flexi_'.$key))
                    {
                         $flexi = Request::input('flexi_'.$key);
                    }
     
                    if(Request::has('rest_day_'.$key))
                    {
                         $rest_day = Request::input('rest_day_'.$key);
                    }
     
                    if(Request::has('extra_day_'.$key))
                    {
                         $extra_day = Request::input('extra_day_'.$key);
                    }
     
                    $temp_shift['flexi']               = $flexi;
                    $temp_shift['rest_day']            = $rest_day;
                    $temp_shift['extra_day']           = $extra_day;
     
                    array_push($insert_shift, $temp_shift);
               }
     
               if(!empty($insert_shift))
               {
                    Tbl_payroll_shift::insert($insert_shift);
               }
          }
          


          // $_restday                                                = array();
          // $_extraday                                               = array();
          // if(Request::has('restday'))
          // {
          //   $_restday                                              = Request::input('restday');
          // }

          // if(Request::has('extraday'))
          // {
          //   $_extraday                                             = Request::input('extraday');
          // } 
          
          
          // $insert_rest_day = array();
          // $temp = "";
          // foreach($_restday as $restday)
          // {
          //   $temp['payroll_group_id']                         = $group_id;
          //   $temp['payroll_group_rest_day']                   = $restday;
          //   $temp['payroll_group_rest_day_category']     = 'rest day';

          //   array_push($insert_rest_day, $temp);
          // }

          // $insert_extra_day = array();
          // foreach($_extraday as $extra)
          // {
          //   $temp['payroll_group_id']                         = $group_id;
          //   $temp['payroll_group_rest_day']                   = $extra;
          //   $temp['payroll_group_rest_day_category']     = 'extra day';

          //   array_push($insert_extra_day, $temp);
          // }

          // if(!empty($insert_rest_day))
          // {
          //   Tbl_payroll_group_rest_day::insert($insert_rest_day);
          // }

          // if(!empty($insert_extra_day))
          // {
          //   Tbl_payroll_group_rest_day::insert($insert_extra_day);
          // }
          // $_selected_employee = Request::input('selected_employee');
          // foreach ($_selected_employee as $key => $selected_employee) 
          // {
        
          // }

          $data['_data']           = array();
          $data['selected']   = $group_id;
          $_group = Tbl_payroll_group::sel(Self::shop_id())->orderBy('payroll_group_code')->get();
          foreach($_group as $group)
          {
               $temp['id']         = $group->payroll_group_id;
               $temp['name']  = $group->payroll_group_code;
               $temp['attr']  = '';
               array_push($data['_data'], $temp);
          }

          $view = view('member.payroll.misc.misc_option', $data)->render();

          $return['view']                    = $view;
          $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_payroll_group';
          return json_encode($return);
     }

     public function modal_edit_payroll_group($id)
     {
          Session::put('payroll_edit_group_tag_employee', array());
          $data['group']           = Tbl_payroll_group::where('payroll_group_id',$id)->first();
          $data['payroll_13th_month_basis'] = tbl_payroll_13th_month_basis::where('payroll_group_id', $id)->first();
          $data['_overtime_rate']  = Tbl_payroll_overtime_rate::where('payroll_group_id',$id)->get();
          $data['_day']            = Payroll::restday_checked($id); 
          $data['_period']         = Tbl_payroll_tax_period::check(Self::shop_id())->get();
          $data['_shift_code']     = Tbl_payroll_shift_code::getshift(Self::shop_id())->orderBy('shift_code_name')->get();
          $data['employee']        = Tbl_payroll_employee_basic::join('tbl_payroll_employee_contract','tbl_payroll_employee_basic.payroll_employee_id','=','tbl_payroll_employee_contract.payroll_employee_id')->where('tbl_payroll_employee_contract.payroll_group_id',$id)->where('shop_id',Self::shop_id())->get();


         // dd($data['group']);
          return view('member.payroll.modal.modal_edit_payroll_group',$data);
     }

     public function modal_update_payroll_group()
     {

          $payroll_group_id = Request::input("payroll_group_id");

          $update['payroll_group_code']                     = Request::input('payroll_group_code');
          $update['payroll_group_salary_computation']       = Request::input('payroll_group_salary_computation');
          $update['payroll_group_period']                   = Request::input('payroll_group_period');
          $update['payroll_group_13month_basis']            = Request::input('payroll_group_13month_basis');
          $update['payroll_group_cola_basis']               = Request::input('payroll_group_cola_basis');

          $update['payroll_group_grace_time']               = Request::input('payroll_group_grace_time');
          // $update['payroll_group_break']                      = Request::input('payroll_group_break');
          $update['payroll_group_agency_fee']               = Request::input('payroll_group_agency_fee');

          // $update['shift_code_id']                          = Request::input('shift_code_id');
          
          $payroll_group_deduct_before_absences             = 0;

          if( Request::has('payroll_group_deduct_before_absences'))
          {
               $payroll_group_deduct_before_absences        = Request::input('payroll_group_deduct_before_absences');
          }

          $payroll_group_before_tax                         = 0;
          
          if(Request::has('payroll_group_before_tax'))
          {
               $payroll_group_before_tax = Request::has('payroll_group_before_tax');
          }

          $update['display_monthly_rate']    = 0;
          $update['display_daily_rate']      = 0;

          if(Request::has('display_monthly_rate'))
          {
               $update['display_monthly_rate']    = 1;
          }

          if(Request::has('display_daily_rate'))
          {
               $update['display_daily_rate']    = 1;
          }

          $update['payroll_group_before_tax']               = $payroll_group_before_tax;
          $update['payroll_group_deduct_before_absences']   = $payroll_group_deduct_before_absences;
          $update['payroll_group_tax']                      = Request::input('payroll_group_tax');
          $update['payroll_group_sss']                      = Request::input('payroll_group_sss');
          $update['payroll_group_philhealth']               = Request::input('payroll_group_philhealth');
          $update['payroll_group_pagibig']                  = Request::input('payroll_group_pagibig');
          $update['payroll_group_agency']                   = Request::input('payroll_group_agency');
          $update['payroll_group_agency_fee']               = Request::input('payroll_group_agency_fee');

          
          $update['tax_reference']                          = Request::input('tax_reference');
          $update['sss_reference']                          = Request::input('sss_reference');
          $update['philhealth_reference']                   = Request::input('philhealth_reference');
          $update['pagibig_reference']                      = Request::input('pagibig_reference');


          // $payroll_group_is_flexi_break                       = 0;
          // if(Request::has('payroll_group_is_flexi_break'))
          // {
          //   $payroll_group_is_flexi_break                     = Request::input('payroll_group_is_flexi_break');
          // }
          // $update['payroll_group_is_flexi_break']        = $payroll_group_is_flexi_break;
          // $update['payroll_group_flexi_break']                = Request::input('payroll_group_flexi_break');
          $update['payroll_late_category']                  = Request::input('payroll_late_category');
          $update['payroll_late_interval']                  = Request::input('payroll_late_interval');
          $update['payroll_late_parameter']                 = Request::input('payroll_late_parameter');
          $update['payroll_late_deduction']                 = Request::input('payroll_late_deduction');
          
          // $payroll_group_is_flexi_time                        = 0;
          // if(Request::has('payroll_group_is_flexi_time'))
          // {
          //   $payroll_group_is_flexi_time                 = Request::input('payroll_group_is_flexi_time');  
          // }

          // $update['payroll_group_is_flexi_time']              = $payroll_group_is_flexi_time;
          $update['payroll_group_working_day_month']        = Request::input('payroll_group_working_day_month');

          // $payroll_group_is_flexi_break = 0;
          // if(Request::has('payroll_group_is_flexi_break'))
          // {
          //   $payroll_group_is_flexi_break      = Request::input('payroll_group_is_flexi_break');

          // }
          // $update['payroll_group_break_start']                = date('H:i:s', strtotime(Request::input('payroll_group_break_start')));
          // $update['payroll_group_break_end']                  = date('H:i:s', strtotime(Request::input('payroll_group_break_end')));
          // $update['payroll_group_flexi_break']           = Request::input('payroll_group_flexi_break');
          // $update['payroll_group_is_flexi_break']             = $payroll_group_is_flexi_break;

          $payroll_group_target_hour_parameter              = 'Daily';
          if(Request::has('payroll_group_target_hour_parameter'))
          {    
               $payroll_group_target_hour_parameter         = Request::input('payroll_group_target_hour_parameter');
          }
          // $update['payroll_group_target_hour_parameter']      = $payroll_group_target_hour_parameter;
          // $update['payroll_group_target_hour']                = Request::input('payroll_group_target_hour');
          // $update['payroll_group_start']                      = date('H:i:s',strtotime(Request::input('payroll_group_start')));
          // $update['payroll_group_end']                        = date('H:i:s',strtotime(Request::input('payroll_group_end')));
          
          $update['payroll_under_time_category']  = Request::input("payroll_under_time_category");
          $update['payroll_under_time_interval']  = Request::input("payroll_under_time_interval");
          $update['payroll_under_time_parameter'] = Request::input("payroll_under_time_parameter");
          $update['payroll_under_time_deduction'] = Request::input("payroll_under_time_deduction");
          $update['payroll_break_category']       = Request::input("payroll_break_category");         
          $update['overtime_grace_time']          = Request::input('overtime_grace_time') == "" ? '00:00:00' : date('H:i:s', strtotime(Request::input('overtime_grace_time')));
          $update['grace_time_rule_overtime']     = Request::has('grace_time_rule_overtime') ? Request::input('grace_time_rule_overtime') : 'accumulative';
          $update['late_grace_time']              = Request::input('late_grace_time') == "" ? '00:00:00' : date('H:i:s', strtotime(Request::input('late_grace_time')));
          $update['grace_time_rule_late']         = Request::has('grace_time_rule_late') ? Request::input("grace_time_rule_late") : 'first';

          /* UPDATE PAYROLL GROUP*/ 
          Tbl_payroll_group::where('payroll_group_id',$payroll_group_id)->update($update);

          Tbl_payroll_13th_month_basis::where('payroll_group_id',$payroll_group_id)->delete();

          $insert_13th_month = array();

          $payroll_group_13th_month_addition    = Request::input('payroll_group_13th_month_addition');
          $insert_13th_month                    = array("payroll_group_13th_month_addition_allowance" => 0, "payroll_group_13th_month_addition_special_holiday" => 0, "payroll_group_13th_month_addition_regular_holiday" => 0, "payroll_group_13th_month_addition_cola" => 0, "payroll_group_13th_month_addition_late" => 0, "payroll_group_13th_month_addition_undertime" => 0, "payroll_group_13th_month_addition_absent" => 0);
          if ($payroll_group_13th_month_addition != null) 
          {
               foreach ($payroll_group_13th_month_addition as $key => $value) 
               {
                    $insert_13th_month[$value] = 1;
               }
          }

          $insert_13th_month['payroll_group_13month_basis']  = Request::input('payroll_group_13month_basis');
          $insert_13th_month['payroll_group_id']             =  $payroll_group_id;

          Tbl_payroll_13th_month_basis::insert($insert_13th_month);

          Tbl_payroll_overtime_rate::where('payroll_group_id',$payroll_group_id)->delete();
          $insert_rate = array();
          foreach(Request::input("payroll_overtime_name") as $key => $overtime)
          {
               $temp['payroll_group_id']                    = $payroll_group_id;
               $temp['payroll_overtime_name']               = Request::input("payroll_overtime_name")[$key];
               $temp['payroll_overtime_regular']             = Request::input("payroll_overtime_regular")[$key];
               $temp['payroll_overtime_overtime']           = Request::input("payroll_overtime_overtime")[$key];
               $temp['payroll_overtime_nigth_diff']             = Request::input("payroll_overtime_nigth_diff")[$key];
               $temp['payroll_overtime_rest_day']           = Request::input("payroll_overtime_rest_day")[$key];
               $temp['payroll_overtime_rest_overtime']      = Request::input("payroll_overtime_rest_overtime")[$key];
               $temp['payroll_overtime_rest_night']         = Request::input("payroll_overtime_rest_night")[$key];

               array_push($insert_rate, $temp);
          }
          
          /* INSERT PAYROLL OVERTIME NIGHT DIFFERENTIALS REST DAY HOLIDAY */
          Tbl_payroll_overtime_rate::insert($insert_rate);

          // $_restday                                                = array();
          // $_extraday                                               = array();
          // if(Request::has('restday'))
          // {
          //   $_restday                                              = Request::input('restday');
          // }

          // if(Request::has('extraday'))
          // {
          //   $_extraday                                             = Request::input('extraday');
          // } 
          
          // Tbl_payroll_group_rest_day::where('payroll_group_id',$payroll_group_id)->delete();
          // $insert_rest_day = array();
          // $temp = "";
          // foreach($_restday as $restday)
          // {
          //   $temp['payroll_group_id']                         = $payroll_group_id;
          //   $temp['payroll_group_rest_day']                   = $restday;
          //   $temp['payroll_group_rest_day_category']     = 'rest day';

          //   array_push($insert_rest_day, $temp);
          // }

          // $insert_extra_day = array();
          // foreach($_extraday as $extra)
          // {
          //   $temp['payroll_group_id']                         = $payroll_group_id;
          //   $temp['payroll_group_rest_day']                   = $extra;
          //   $temp['payroll_group_rest_day_category']     = 'extra day';

          //   array_push($insert_extra_day, $temp);
          // }

          // if(!empty($insert_rest_day))
          // {
          //   Tbl_payroll_group_rest_day::insert($insert_rest_day);
          // }

          // if(!empty($insert_extra_day))
          // {
          //   Tbl_payroll_group_rest_day::insert($insert_extra_day);
          // }

          Tbl_payroll_shift::where('payroll_group_id', $payroll_group_id)->delete();

          if(!empty($insert_shift))
          {
               Tbl_payroll_shift::insert($insert_shift);
          }

          $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_payroll_group';

          return json_encode($return);
     }



     public function confirm_archived_payroll_group($archived, $payroll_group_id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name               = Tbl_payroll_group::where('payroll_group_id',$payroll_group_id)->value('payroll_group_code');
          $data['title']           = 'Do you really want to '.$statement.' Payroll Group '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/payroll_group/archived_payroll_group';
          $data['id']         = $payroll_group_id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archived_payroll_group()
     {
          $payroll_group_id = Request::input('id');
          $update['payroll_group_archived'] = Request::input('archived');
          $old_data = AuditTrail::get_table_data("tbl_payroll_group","payroll_group_id",$payroll_group_id);
          Tbl_payroll_group::where('payroll_group_id',$payroll_group_id)->update($update);
          // AuditTrail::record_logs('Updating Payroll Group', 'Updating Payroll Group temp with ID #'.$payroll_group_id." To archive value=".Request::input('archived'), $id, "" ,serialize($old_data));
          $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_payroll_group';
          return json_encode($return);
     }

     /* PAYROLL GROUP END */


     /* PAYROLL JOURNAL START */
     public function payroll_jouarnal()
     {
          $data['_tag'] = Tbl_payroll_journal_tag::gettag(Self::shop_id())->orderBy('tbl_chart_of_account.account_name')->get();
          
          return view('member.payroll.side_container.journal', $data);
     }

     public function modal_create_journal_tag()
     {
          /* account_type_id = 13 = Expense */

          $account_type_id = [1,3,8,13];

          $data['_expense'] = Tbl_chart_of_account::getbytype(Self::shop_id(), $account_type_id)->orderBy('account_name')->get();

          $data['_entity']  = Self::setentity();
         
          return view('member.payroll.modal.modal_create_journal_tag', $data);
     }

     public function create_journal_tag()
     {    
          // Tbl_payroll_journal_tag
          // Tbl_payroll_journal_tag_entity
          // Tbl_payroll_journal_tag_employee
          $insert_tag['account_id']     = Request::input('account_id');
          $insert_tag['shop_id']        = Self::shop_id();

          $payroll_journal_tag_id = Tbl_payroll_journal_tag::insertGetId($insert_tag);
          AuditTrail::record_logs('CREATED: Payroll Journal Tag', 'Payroll Journal Tag Account ID: '.Request::input('account_id'),"", "" ,"");
          $insert_entity = array();

          if(Request::has('entity'))
          {
               foreach(Request::input('entity') as $entity)
               {
                    $temp['payroll_journal_tag_id'] = $payroll_journal_tag_id;
                    $temp['payroll_entity_id']      = $entity;

                    array_push($insert_entity, $temp);
               }
          }

          if(!empty($insert_entity))
          {
               Tbl_payroll_journal_tag_entity::insert($insert_entity);
          }    

          $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_journal_tags';

          return collect($return)->toJson();
          
     }   

     public function modal_edit_journal_tag($id)
     {

          $data['tag'] = Tbl_payroll_journal_tag::where('payroll_journal_tag_id', $id)->first();

          $account_type_id = [1,3,8,13];

          $data['_expense'] = Tbl_chart_of_account::getbytype(Self::shop_id(), $account_type_id)->orderBy('account_name')->get();

          $data['_entity'] = array();
          $_entity  = collect(Tbl_payroll_entity::orderBy('entity_name')->get()->toArray())->groupBy('entity_category');

          foreach($_entity as $key => $entity_data)
          {
               $data['_entity'][$key] = array();
               foreach($entity_data as $entity)
               {
                    $count = Tbl_payroll_journal_tag_entity::checkentity($id, $entity['payroll_entity_id'])->count();

                    $entity['status'] = '';
                    if($count == 1)
                    {
                         $entity['status'] = 'checked';
                    }
                    array_push($data['_entity'][$key], $entity);
               }
          }
          // dd($data['_entity']);

          return view('member.payroll.modal.modal_edit_journal_tag', $data);
     } 

     public function modal_confimr_del_journal_tag($id)
     {

          $data['title']      = 'Do you really want to remove this tag?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/payroll_jouarnal/del_journal_tag';
          $data['id']         = $id;
          $data['archived']   = 1;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function del_journal_tag()
     {
          $id = Request::input('id');
          $tag = Tbl_payroll_journal_tag::where('payroll_journal_tag_id',$id)->first();
          Tbl_payroll_journal_tag::where('payroll_journal_tag_id',$id)->delete();
          AuditTrail::record_logs('DELETED: Payroll Journal Tag', 'Payroll Journal Tag Account ID: '.$tag->account_id,"", "" ,"");
          
           $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_journal_tags';

          return collect($return)->toJson();
     }

     public function update_payroll_journal_tag()
     {
          $payroll_journal_tag_id = Request::input('payroll_journal_tag_id');
          $update['account_id'] = Request::input('account_id');
          $journal = Tbl_payroll_journal_tag::where('payroll_journal_tag_id', $payroll_journal_tag_id)->first();
          Tbl_payroll_journal_tag::where('payroll_journal_tag_id', $payroll_journal_tag_id)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Journal Tag', 'Payroll Journal Tag Account ID: '.$journal->account_id." to ".Request::input('account_id'), "", "" ,"");

          Tbl_payroll_journal_tag_entity::where('payroll_journal_tag_id', $payroll_journal_tag_id)->delete();

          $insert_entity = array();

          if(Request::has('entity'))
          {
               foreach(Request::input('entity') as $entity)
               {
                    $temp['payroll_journal_tag_id'] = $payroll_journal_tag_id;
                    $temp['payroll_entity_id']      = $entity;

                    array_push($insert_entity, $temp);
               }
          }

          if(!empty($insert_entity))
          {
               Tbl_payroll_journal_tag_entity::insert($insert_entity);
          }    


          $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_journal_tags';

          return collect($return)->toJson();
     }

     public function relaod_payroll_journal_sel()
     {
          $id = Request::input('id');
          $_expense = Tbl_chart_of_account::getbytype(Self::shop_id(), 13)->orderBy('account_name')->get()->toArray();

          $html = '';
          foreach($_expense as $expense)
          {
               $status = '';
               if($expense['account_id'] == $id)
               {
                    $status = 'selected="selected"';
               }
               $html.='<option value="'.$expense['account_id'].'" '.$status.'>'.$expense['account_number'].' . '.$expense['account_name'].'</option>';
          }
          return $html;
     }

     /* PAYROLL JOUARNAL END */ 


     /* PAYROLL CUSTOM PAYSLIP START */

     public function custom_payslip()
     {
          $data['_payslip'] = Tbl_payroll_payslip::getpayslip(Self::shop_id())->orderBy('payslip_code')->get();

          $data['_archived'] = Tbl_payroll_payslip::getpayslip(Self::shop_id(), 1)->orderBy('payslip_code')->get();

          return view('member.payroll.side_container.custom_payslip', $data);
     }

     public function custom_payslip_show($id)
     {
          $data['payslip'] = Tbl_payroll_payslip::where('payroll_payslip_id', $id)->first();
          return view('member.payroll.reload.payslip_show', $data);
     }

     public function custom_payslip_show_archived($id)
     {
          $data['payslip'] = Tbl_payroll_payslip::where('payroll_payslip_id', $id)->first();
          return view('member.payroll.reload.payslip_show_restore', $data);
     }

     public function modal_edit_payslip($id)
     {
          $data['_paper'] = Tbl_payroll_paper_sizes::getpaper(Self::shop_id())->orderBy('paper_size_name')->get();
          $data['payslip'] = Tbl_payroll_payslip::where('payroll_payslip_id', $id)->first();
          return view('member.payroll.modal.modal_edit_payslip', $data);
     }

     public function modal_create_payslip()
     {
          $data['_paper'] = Tbl_payroll_paper_sizes::getpaper(Self::shop_id())->orderBy('paper_size_name')->get();
          return view('member.payroll.modal.modal_create_payslip', $data);
     }

     public function modal_create_paper_size()
     {
          $data['_paper'] = Tbl_payroll_paper_sizes::getpaper(Self::shop_id())->orderBy('paper_size_name')->get();
          return view('member.payroll.modal.modal_create_paper_size', $data);
     }

     public function modal_view_payslip_option()
     {

          $data['option'] = Tbl_payroll_payslip_option::select('*')->where('shop_id',Self::shop_id())->get();
          if(count($data['option']) == 0)
          {
                    $insert['per_page']       = 0;           
                    $insert['shop_id']        = Self::shop_id();
                    Tbl_payroll_payslip_option::insert($insert);
          }
          $data['option'] = Tbl_payroll_payslip_option::select('*')->where('shop_id',Self::shop_id())->get();
          return view('member.payroll.modal.modal_view_payslip_option',$data);
     }

     public function save_payslip_options()
     {
          $update['per_page']   = Request::input('per_page');
          Tbl_payroll_payslip_option::where('shop_id', Self::shop_id())->update($update);  
            
          $return['status']   = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_custom_payslip';

          return collect($return)->toJson();         
     }

     public function modal_save_paper_size()
     {
          $insert['shop_id']            = Self::shop_id();
          $insert['paper_size_name']    = Request::input('paper_size_name');
          $insert['paper_size_width']   = Request::input('paper_size_width');
          $insert['paper_size_height']  = Request::input('paper_size_height');
          AuditTrail::record_logs('CREATED: Payroll Paper Sizes', 'Payroll Paper Name: '.Request::input('paper_size_name'), "", "" ,"");
          $id = Tbl_payroll_paper_sizes::insertGetId($insert);

          $return['status']        = 'success';
          $return['id']            = $id;
          $return['function_name'] = 'payrollconfiguration.reload_paper_size_d';
          return collect($return)->toJson();
     }

     public function save_custom_payslip()
     {
          $insert['shop_id']                      = Self::shop_id();
          $insert['payslip_code']                 = Request::input("payslip_code");
          $insert['payroll_paper_sizes_id']       = Request::input('payroll_paper_sizes_id');
          $insert['payslip_width']                = Request::input('payslip_width');
          $insert['payslip_copy']                 = Request::input('payslip_copy');
          

          $include_department                     = 0;
          $include_job_title                      = 0;
          $include_time_summary                   = 0;
          $include_company_logo                   = 0;

          if(Request::has('include_company_logo'))
          {
               $include_company_logo = Request::input('include_company_logo');
          }

          if(Request::has('include_department'))
          {
               $include_department = Request::input('include_department');
          }

          if(Request::has('include_job_title'))
          {
               $include_job_title = Request::input('include_job_title');
          }

          if(Request::has('include_time_summary'))
          {
               $include_time_summary = Request::input('include_time_summary');
          }

          $insert['include_department']           = $include_department;
          $insert['include_job_title']            = $include_job_title;
          $insert['include_time_summary']         = $include_time_summary;
          $insert['include_company_logo']         = $include_company_logo;
          $insert['company_position']             = Request::input('company_position');
          AuditTrail::record_logs('CREATED: Payroll Payslip', 'Payroll Payslip Code : '.Request::input('payslip_code'), "", "" ,"");
          $id = Tbl_payroll_payslip::insertGetId($insert);

          $return['status']   = 'success';
          $return['id']       = $id;
          $return['function_name'] = 'payrollconfiguration.reload_custom_payslip';

          return collect($return)->toJson();
     }

     public function modal_archive_payslip($archived, $id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name          = Tbl_payroll_payslip::where('payroll_payslip_id',$id)->value('payslip_code');
          $data['title']      = 'Do you really want to '.$statement.' Payslip '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/custom_payslip/archive_payslip';
          $data['id']         = $id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }


     public function modal_update_payslip()
     {
          $payroll_payslip_id                     = Request::input('payroll_payslip_id');

          $update['payslip_code']                 = Request::input("payslip_code");
          $update['payroll_paper_sizes_id']       = Request::input('payroll_paper_sizes_id');
          $update['payslip_width']                = Request::input('payslip_width');
          $update['payslip_copy']                 = Request::input('payslip_copy');
          

          $include_department                     = 0;
          $include_job_title                      = 0;
          $include_time_summary                   = 0;
          $include_company_logo                   = 0;

          if(Request::has('include_company_logo'))
          {
               $include_company_logo = Request::input('include_company_logo');
          }

          if(Request::has('include_department'))
          {
               $include_department = Request::input('include_department');
          }

          if(Request::has('include_job_title'))
          {
               $include_job_title = Request::input('include_job_title');
          }

          if(Request::has('include_time_summary'))
          {
               $include_time_summary = Request::input('include_time_summary');
          }

          $update['include_department']           = $include_department;
          $update['include_job_title']            = $include_job_title;
          $update['include_time_summary']         = $include_time_summary;
          $update['include_company_logo']         = $include_company_logo;
          $update['company_position']             = Request::input('company_position');
          $payme=Tbl_payroll_payslip::where('payroll_payslip_id', $payroll_payslip_id)->first();
          AuditTrail::record_logs('EDITED: Payroll Payslip', 'Payroll Payslip Code : '.$payme->payslip_code." to ".Request::input('payslip_code'), "", "" ,"");
          Tbl_payroll_payslip::where('payroll_payslip_id', $payroll_payslip_id)->update($update);

          $return['status']        = 'success';
          $return['id']            = $payroll_payslip_id;
          $return['function_name'] = 'payrollconfiguration.reload_custom_payslip';

          return collect($return)->toJson();
     }

     public function archive_payslip()
     {
          $id = Request::input('id');
          $update['payroll_payslip_archived'] = Request::input('archived');
          $del_pay = Tbl_payroll_payslip::where('payroll_payslip_id',$id)->first();
          AuditTrail::record_logs('DELETED: Payroll Payslip', 'Payroll Payslip Code : '.$del_pay->payslip_code, "", "" ,"");
          Tbl_payroll_payslip::where('payroll_payslip_id',$id)->update($update);

          $return['status']   = 'success';
          $return['id']       = $id;
          $return['function_name'] = 'payrollconfiguration.reload_custom_payslip';

          return collect($return)->toJson();
     }

     public function payslip_use_change()
     {
          $update['payslip_is_use'] = Request::input('is_checked');
          $id = Request::input('id');

          $update_second['payslip_is_use'] = 0;
          Tbl_payroll_payslip::where('shop_id', Self::shop_id())->update($update_second);

          Tbl_payroll_payslip::where('payroll_payslip_id', $id)->update($update);
     }

     /* PAYROLL CUSTOM PAYSLIP END */


     /* PAYROLL PERIOD START*/
     public function payroll_period_list()
     {    
          $data['_active'] = Tbl_payroll_period::sel(Self::shop_id())->orderBy('payroll_period_start','desc')->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_period::sel(Self::shop_id(), 1)->orderBy('payroll_period_start','desc')->paginate($this->paginate_count);

          return view('member.payroll.payroll_period_list', $data);
     }


     public function modal_create_payroll_period()
     {
          $data['_company']= Tbl_payroll_company::selcompany(Self::shop_id())->where("payroll_parent_company_id", 0)->get();
          $data['_tax'] = Tbl_payroll_tax_period::check(Self::shop_id())->get();
          $data['_month'] = Payroll::get_month();
          return view('member.payroll.modal.modal_create_payroll_period', $data);
     }
     public function modal_save_payroll_period()
     {
          $insert['shop_id']                      = Self::shop_id();
          $insert['payroll_period_start']         = date('Y-m-d',strtotime(Request::input('payroll_period_start')));
          $insert['payroll_period_end']           = date('Y-m-d',strtotime(Request::input('payroll_period_end')));
          $insert['payroll_period_category']      = Request::input('payroll_period_category');
          $insert['period_count']                 = Request::input('period_count');
          $insert['month_contribution']           = Request::input('month_contribution');
          $insert['year_contribution']            = Request::input('year_contribution');
          $insert['payroll_release_date']         = date('Y-m-d',strtotime(Request::input('payroll_release_date')));
          AuditTrail::record_logs('CREATED: Payroll Period', 'Payroll Period: '.date('M d, g:i A',strtotime(Request::input('payroll_period_start')))." - ".date('M d, g:i A',strtotime(Request::input('payroll_period_end'))),"", "" ,"");

          $payroll_period_id = Tbl_payroll_period::insertGetId($insert);
         
          $insert_company['payroll_period_id']        = $payroll_period_id;
          $insert_company['payroll_company_id']       = Request::input("payroll_company_id");
          $insert_company['payroll_period_status']    = 'generated';

          Tbl_payroll_period_company::insert($insert_company);

          $return['id']               = $payroll_period_id;
          $return['status']           = 'success';
          $return['function_name']    = 'payroll_period_list.reload_list';
          return json_encode($return);
     }

     public function modal_schedule_employee_shift()
     {
          $id = Request::input('id');

          $data['period']          = Tbl_payroll_period::where('payroll_period_id', $id)->first();
          $data['_company']        = Tbl_payroll_period_company::selperiod($id)->orderBy('tbl_payroll_company.payroll_company_name')->get();
          $data['_department']     = tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          $data['_jobtitle']       = Tbl_payroll_jobtitle::sel(Self::shop_id())->orderBy('tbl_payroll_jobtitle.payroll_jobtitle_name')->get();

          $data['id']              = $id;
          $data['_employee']       = Tbl_payroll_employee_contract::employeefilter(0,0,0, $data['period']->payroll_period_start, Self::shop_id())->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
                                   ->where('tbl_payroll_group.payroll_group_period', $data['period']->payroll_period_category)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->get();

          $data['_shift']          = Tbl_payroll_shift_code::getshift(Self::shop_id())
                                                            ->orderBy('shift_code_name')
                                                            ->get();
          return view('member.payroll.modal.modal_schedule_employee_shift', $data);
     }

     public function shift_template_refence()
     {
          $payroll_period_id  = Request::input('payroll_period_id');
          $employee_id        = Request::input('employee_id');
          $group              = Request::input('group');
          $shift_template_id  = Request::input("shift_template_id");


          $period = Tbl_payroll_period::where('payroll_period_id', $payroll_period_id)->first();

          $date_start = '0000-00-00';
          $date_end   = '0000-00-00';

          if($period != null)
          {
               $date_start = $period->payroll_period_start;
               $date_end   = $period->payroll_period_end;
          }


          $data['_day'] = array();

          while($date_start <= $date_end)
          {
               $day = date('D', strtotime($date_start));

               /* shift from payroll group */
               $group_shift = Tbl_payroll_shift::getshift($group, $day)->first();

               /* shift in new schedule */
               $new_schedule = Tbl_payroll_employee_schedule::getschedule($employee_id, $date_start)->first();

               $shift = $group_shift;

               if($new_schedule != null)
               {
                    $shift = $new_schedule;
               }
               if($shift_template_id != '' && $shift_template_id != 0)
               {
                    $shift = Tbl_payroll_shift_template::getshift($shift_template_id, $day)->first();
               }

               $temp['date']                 = $date_start;
               if($shift != null)
               {
                    $temp['target_hours']         = $shift->target_hours;
                    $temp['work_start']           = $shift->work_start;
                    $temp['work_end']             = $shift->work_end;
                    $temp['break_start']          = $shift->break_start;
                    $temp['break_end']            = $shift->break_end;
                    $temp['flexi']                = $shift->flexi;
                    $temp['rest_day']             = $shift->rest_day;
                    $temp['extra_day']            = $shift->extra_day;
                    $temp['night_shift']          = $shift->night_shift;
               }
               else
               {
                    $temp['target_hours']         = 0;
                    $temp['work_start']           = '00:00:00';
                    $temp['work_end']             = '00:00:00';
                    $temp['break_start']          = '00:00:00';
                    $temp['break_end']            = '00:00:00';
                    $temp['flexi']                = 0;
                    $temp['rest_day']             = 0;
                    $temp['extra_day']            = 0;
                    $temp['night_shift']          = 0;
               }
               
               

               array_push($data['_day'], $temp);

               $date_start = Carbon::parse($date_start)->addDay()->format("Y-m-d");
          }


          return view('member.payroll.misc.shift_template', $data);
     }


     public function save_shift_per_employee()
     {
          $payroll_period_id       = Request::input('payroll_period_id');
          $payroll_employee_id     = Request::input('payroll_employee_id');


          foreach(Request::input('day') as $key => $day)
          {
               $insert['payroll_employee_id']     = $payroll_employee_id;
               $insert['schedule_date']           = $day;
               $insert['target_hours']            = Request::input('target_hours')[$key];
               $insert['work_start']              = date('H:i:s a',strtotime(Request::input('work_start')[$key]));
               $insert['work_end']                = date('H:i:s a',strtotime(Request::input('work_end')[$key]));
               $insert['break_start']             = date('H:i:s a',strtotime(Request::input('break_start')[$key]));
               $insert['break_end']               = date('H:i:s a',strtotime(Request::input('break_end')[$key]));
               $insert['flexi']                   = 0;
               $insert['rest_day']                = 0;
               $insert['extra_day']               = 0;

               if(Request::has('flexi_'.$key))
               {
                    $insert['flexi']              = Request::input('flexi_'.$key);
               }

               if(Request::has('rest_day_'.$key))
               {
                    $insert['rest_day']           = Request::input('rest_day_'.$key);
               }

               if(Request::has('extra_day_'.$key))
               {
                    $insert['extra_day']          = Request::input('extra_day_'.$key);
               }
              
               Tbl_payroll_employee_schedule::getschedule($payroll_employee_id, $day)->delete();
               Tbl_payroll_employee_schedule::insert($insert);
               AuditTrail::record_logs('CREATED: Payroll Employee Schedule', 'Payroll Employee ID #'.$payroll_employee_id, $payroll_employee_id, "" ,"");
          }

          $return['status'] = 'success';
          $return['function_name'] =  '';
          return collect($return)->toJson();
     }

     public function modal_archive_period($archived, $payroll_period_id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $_query = Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->first();
          // dd($_query);
          $file_name          = date('M d, Y', strtotime($_query->payroll_period_start)).' to '.date('M d, Y', strtotime($_query->payroll_period_end));
          $data['title']      = 'Do you really want to '.$statement.' payroll period of '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/payroll_period_list/archive_period';
          $data['id']         = $payroll_period_id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archive_period()
     {
          $payroll_period_id = Request::input('id');
          $update['payroll_period_archived'] = Request::input('archived');
          $period = Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->first();
          Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->update($update);
          AuditTrail::record_logs('DELETED: Payroll Period', 'Payroll Period :'.$period->payroll_period_start." - ".$period->payroll_period_end, $payroll_period_id, "" ,"");
          $return['status'] = 'success';
          $return['function_name'] = 'payroll_period_list.reload_list';
          return json_encode($return);
     }

     public function modal_edit_period($payroll_period_id)
     {
          $data['period']     = Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->first();
          $data['_tax']       = Tbl_payroll_tax_period::check(Self::shop_id())->get();
          $data['_month']     = Payroll::get_month();

          return view('member.payroll.modal.modal_edit_period', $data);
     }

     public function modal_update_period()
     {
          $payroll_period_id                      = Request::input("payroll_period_id");
          $update['payroll_period_category']      = Request::input("payroll_period_category");
          $update['payroll_period_start']         = date('Y-m-d',strtotime(Request::input("payroll_period_start")));
          $update['payroll_period_end']           = date('Y-m-d',strtotime(Request::input("payroll_period_end")));
          $update['period_count']                 = Request::input('period_count');
          $update['month_contribution']           = Request::input('month_contribution');
          $update['year_contribution']            = Request::input('year_contribution');
          $update['payroll_release_date']         = date('Y-m-d',strtotime(Request::input("payroll_release_date")));
          $period_payroll = Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->first();
          Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Period', 'Payroll Period:'.$period_payroll->payroll_period_start." - ".$period_payroll->payroll_period_end ." TO ".date('Y-m-d',strtotime(Request::input("payroll_period_start")))." - ".date('Y-m-d',strtotime(Request::input("payroll_period_end"))), $payroll_period_id, "" ,"");
          $return['status'] = 'success';
          $return['function_name'] = 'payroll_period_list.reload_list';
          return json_encode($return);
     }
     /* PAYROLL PERIOD END */

     /*HOLIDAY DEFAULT START*/

     public function default_holiday()
     {
          $data['_active'] = Tbl_payroll_holiday_default::getholiday()->orderBy('payroll_holiday_date','desc')->select(DB::raw('*, payroll_holiday_default_id as payroll_holiday_id'))->paginate($this->paginate_count);
          $data['_archived'] = Tbl_payroll_holiday_default::getholiday(1)->orderBy('payroll_holiday_date','desc')->select(DB::raw('*, payroll_holiday_default_id as payroll_holiday_id'))->paginate($this->paginate_count);

          $data['title']      = 'Holiday Default';
          $data['create']     = '/member/payroll/holiday_default/modal_create_holiday_default';
          $data['edit']       = '/member/payroll/holiday_default/modal_edit_holiday_default/';
          $data['archived']   = '/member/payroll/holiday_default/modal_archive_holiday_default/';

          return view('member.payroll.side_container.holiday',$data);
     }    

     public function modal_create_holiday_default()
     {
          /*$data = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('payroll_company_name')->get();*/
          return view('member.payroll.modal.modal_create_holiday_default');
     }

     public function modal_save_holiday_default()
     {         
          $insert['payroll_holiday_name']    = Request::input('payroll_holiday_name');
          $insert['payroll_holiday_date']    = date('Y-m-d',strtotime(Request::input('payroll_holiday_date')));
          $insert['payroll_holiday_category'] = Request::input('payroll_holiday_category');
           AuditTrail::record_logs('CREATED: Payroll Holiday Defaults', 'Payroll Holiday Defaults Name: '.Request::input('payroll_holiday_name'), "", "" ,"");
          
          Tbl_payroll_holiday_default::insert($insert);
         $date_inserted = $insert['payroll_holiday_date'];
          $data['_active'] = Tbl_payroll_holiday::where('payroll_holiday_date', $date_inserted)->first();

          if (!$data['_active']) 
          {    
               $insert['shop_id']  = Self::shop_id();
               Tbl_payroll_holiday::insert($insert);             
          } 

          $return['status'] = 'success';
          $return['function_name'] = 'payrollconfiguration.reload_holiday_default';
          return json_encode($return);
     }



     public function modal_edit_holiday_default($id)
     {    
          $data['_active'] = Tbl_payroll_holiday_default::where('payroll_holiday_default_id', $id)->first();
          return view('member.payroll.modal.modal_edit_holiday_default', $data);
     }

     public function update_holiday_default()
     {
          $payroll_holiday_default_id             = Request::input('payroll_holiday_default_id');
          $update['payroll_holiday_name']         = Request::input('payroll_holiday_name');
          $update['payroll_holiday_date']         = date('Y-m-d',strtotime(Request::input('payroll_holiday_date')));
          $update['payroll_holiday_category']     = Request::input('payroll_holiday_category');
          $holiday1 = Tbl_payroll_holiday_default::where('payroll_holiday_default_id', $payroll_holiday_default_id)->first();
          Tbl_payroll_holiday_default::where('payroll_holiday_default_id', $payroll_holiday_default_id)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Holdiday Default', 'Payroll Holdiday Default Name: '.$holiday1->payroll_holiday_name." to ".Request::input('payroll_holiday_name').", ".$holiday1->payroll_holiday_category." to ".Request::input('payroll_holiday_category').", ".$holiday1->payroll_holiday_date." to ".date('Y-m-d',strtotime(Request::input('payroll_holiday_date'))), "", "" ,"");
          $return['status']             = 'success';
          $return['function_name']      = 'payrollconfiguration.reload_holiday';
          return json_encode($return);
     }

     public function modal_archive_holiday_default($archived, $id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $query = Tbl_payroll_holiday_default::where('payroll_holiday_default_id',$id)->first();
          // dd($_query);
          $data['title']      = 'Do you really want to '.$statement.' holiday '.$query->payroll_holiday_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/holiday_default/archive_holiday_default';
          $data['id']         = $id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }
     

     public function archive_holiday_default()
     {
          $update['payroll_holiday_archived'] = Request::input('id');

          $id = Request::input('id');
          $holiday1 = Tbl_payroll_holiday_default::where('payroll_holiday_default_id', $id)->first();
          Tbl_payroll_holiday_default::where('payroll_holiday_default_id', $id)->update($update);
          AuditTrail::record_logs('DELETED: Payroll Holiday Default', 'Payroll Holiday Default Name : '.$holiday1->payroll_holiday_name." To archive value=".Request::input('id'), $id, "" ,serialize($old_data));
          $return['status'] = 'success';
          $return['function_name'] = 'reload_holiday_default';
          return collect($return)->toJson();
     }
     /*HOLIDAY DEFAULT END*/


     /* SHIFT TEMPLATE START */
     // Tbl_payroll_shift_template
     // Tbl_payroll_shift_code
     public function shift_template()
     {
          $data['_active'] = Tbl_payroll_shift_code::getshift(Self::shop_id())->orderBy('shift_code_name')->get();
          $data['_archived'] = Tbl_payroll_shift_code::getshift(Self::shop_id(), 1)->orderBy('shift_code_name')->get();
          return view('member.payroll.side_container.shift_template', $data);
     }

     public function modal_create_shift_template()
     {
          $data['_day'] = Payroll::restday_checked();
          Session::put('payroll_add_shift_tag_employee', array());
          return view('member.payroll.modal.modal_create_shift_template', $data);
     }

     public function remove_employee_shift_group_tag()
     {
         $payroll_employee_id = Request::input('id');
         $shift_code_id       = Request::input('remaining_leave');
 
         $update['shift_code_id'] = null;
         Tbl_payroll_employee_basic::where('payroll_employee_id',$payroll_employee_id)->where('shift_code_id',$shift_code_id)->update($update);

          $return['status']             = 'success';
          $return['function_name']      = 'shifting.load_edit_tag_employee';
          
          return json_encode($return);

     }

     public function load_shift_employee_tag()
     {
          $shift_id = Request::input('shift_id');
          $emp = Tbl_payroll_employee_basic::where('shop_id',Self::shop_id())->where('shift_code_id',$shift_id)->get();
          
          $data['new_record'] = $emp;

          return json_encode($data);
     }

     public function modal_save_shift_template()
     {


          /* INSERT SHIFT CODE */
          $insert_code['shift_code_name']    = Request::input('shift_code_name');
          $insert_code['shop_id']            = Self::shop_id();

          $shift_code_id = Tbl_payroll_shift_code::insertGetId($insert_code);

          if(!empty(Request::input('employee_tag')))
          {
               foreach(Request::input('employee_tag') as $tag)
               {
                    
                    $update['shift_code_id'] = $shift_code_id;
                    Tbl_payroll_employee_basic::where('payroll_employee_id',$tag)->update($update);
                   
               }
          }

          AuditTrail::record_logs("CREATED: Shift Template","Shift Template Code Name : ".Request::input('shift_code_name'),"","","");
          $insert_shift = array();

          /* INSERT DAY */
          $key = 0;
          $tc = 0;
          // dd(Request::all());
          foreach(Request::input("day") as $day)
          {
               /* INSERT SHIFT DAY */
               $insert_day["shift_day"] = $day;
               $insert_day["shift_code_id"] = $shift_code_id;
               $insert_day["shift_target_hours"] = Request::input("target_hours")[$day];
               $insert_day["shift_break_hours"] = Request::input("break_hours")[$day];
               $insert_day["shift_flexi_time"] = Request::input("flexitime_" . $day) == 1 ? 1 : 0;
               $insert_day["shift_rest_day"] = Request::input("rest_day_" . $day) == 1 ? 1 : 0;
               $insert_day["shift_extra_day"] = Request::input("extra_day_" . $day) == 1 ? 1 : 0;
               
               $shift_day_id = Tbl_payroll_shift_day::insertGetId($insert_day);

               /* INSERT SHIFT TIME */
               foreach(Request::input("work_start")[$day] as $k => $time)
               {
                    if($time != "") //MAKE SURE TIME IS NOT BLANK
                    {
                         $insert_time[$tc]["shift_day_id"] = $shift_day_id;
                         $insert_time[$tc]["shift_work_start"] = DateTime::createFromFormat( 'H:i A', $time);
                         $insert_time[$tc]["shift_work_end"] = DateTime::createFromFormat( 'H:i A', Request::input("work_end")[$day][$k]);
                         $tc++;
                    }
               }

               if(isset($insert_time))
               {
                    Tbl_payroll_shift_time::insert($insert_time);
                    $insert_time = null;
               }   
          }

          $return['function_name'] = 'payrollconfiguration.reload_shift_template';
          $return['status']        = 'success';
          return collect($return)->toJson();
     }
     //START-SHIFT IMPORT TEMPLATE//

     public function modal_shift_import_template()
     {
          $data['_company'] = Payroll::company_heirarchy(Self::shop_id());
          return view('member.payroll.modal.modal_shift_import_template', $data);
     }
     public function company_template()
     {
          $template_name = Request::input('template_name');
          if($template_name == 'Template 1')
          {
               Self::template_1();
          }
     }
     public function import_modal_shift_global()
     {
          $file  = Request::file('file');
          $data = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->all();
          $_shift = null;

          $current_teacher = "";
          $teacher_key = -1;
          $subject_key = 0;

          foreach ($data as  $value)
          {
               /* CHANGE ARRAY PER TEACHER */
               if($current_teacher != $value->teacher_id)
               {
                    /* INITIALIZE DATA FOR EVERY TEACHER */
                    $subject_key = 0;
                    $teacher_key++;
                    $current_teacher = $value->teacher_id;  
                    $_shift[$teacher_key]["teacher_id"] = $value->teacher_id;
                    $_d["Sun"] = null;
                    $_d["Mon"] = null;
                    $_d["Tue"] = null;
                    $_d["Wed"] = null;
                    $_d["Thu"] = null;
                    $_d["Fri"] = null;
                    $_d["Sat"] = null;
                    $_shift[$teacher_key]["schedule"] = $_d;
               }

               /* GET DAYS */
               $_day = Self::get_days_based_on_string_day($value->days);

               foreach($_day as $day)
               {
                    $time["in"] = date("h:i A", strtotime($value->time_from));
                    $time["out"] = date("h:i A", strtotime($value->time_to));
                    $_shift[$teacher_key]["schedule"][$day][] = $time;
               }
          }

          /* SORT TIME PER DAY */
          foreach($_shift as $key => $shift)
          {
               /* EACH DAY */
               foreach($shift["schedule"] as $day => $_time)
               {
                    $temptime = null;

                    if($_time) //STORE TO ARRAY INT FORMAT
                    {
                         foreach($_time as $time)
                         {
                             $temptime[strtotime($time["in"])] = $time; 
                         }

                         ksort($temptime);
                    }

                    $_shift[$key]["schedule"][$day] = $temptime; //SAVE SORTED SCHEDULE TO NEW ARRAY
               }
          }

          /* IMPORT ARRAY TO DATABASE */
          $shop_id = $this->user_info->shop_id;

          foreach($_shift as $shift)
          {
               $payroll_employee_number = $shift["teacher_id"];
               $shift_code_name = "import_" . $payroll_employee_number;

               /* DELETE SHIFT OVERRIDE */
               Tbl_payroll_shift_code::where("shift_code_name", $shift_code_name)->where("shop_id", $shop_id)->delete();
       
               /* INSERT SHIFT CODE */
               $insert_code["shift_code_name"] = $shift_code_name;
               $insert_code["shift_archived"] = 0;
               $insert_code["shop_id"] = $shop_id;
               $shift_code_id = Tbl_payroll_shift_code::insertGetId($insert_code);

               /* INSERT SHIFT DAY */  
               foreach($shift["schedule"] as $day => $_time)
               {
                    $insert_day["shift_day"] = $day;
                    $insert_day["shift_target_hours"] = 0;
                    $insert_day["shift_rest_day"] = $_time == null ? 1 : 0;
                    $insert_day["shift_code_id"] = $shift_code_id;

                    $shift_day_id = Tbl_payroll_shift_day::insertGetId($insert_day);

                    /* INSERT SHIFT TIME */
                    if($_time)
                    {
                         foreach($_time as $time)
                         {
                              $insert_time["shift_day_id"] = $shift_day_id;
                              $insert_time["shift_work_start"] = date("H:i:s", strtotime($time["in"]));
                              $insert_time["shift_work_end"] = date("H:i:s", strtotime($time["out"]));

                              Tbl_payroll_shift_time::insert($insert_time);
                         }
                    }
               }

               /* UPDATE EMPLOYEE SHIFT CODE */
               $employee = Tbl_payroll_employee_basic::where("payroll_employee_number", $payroll_employee_number)->where("shop_id", $shop_id)->first();
          
               if($employee)
               {
                    $update_employee["shift_code_id"] = $shift_code_id;
                    Tbl_payroll_employee_basic::where("payroll_employee_id", $employee->payroll_employee_id)->update($update_employee);
                   
                    echo "<div style='color: green;'><b>" . $payroll_employee_number . "</b> shift has been updated.<div>";
               }
               else
               {
                    echo "<div style='color: red;'><b>" . $payroll_employee_number . "</b> can't be found.</div>";
               }
          }

          dd($_shift);
     }
     public function get_days_based_on_string_day($daystring)
     {
          $return = null;

          $_day["Thu"] = "TH";
          $_day["Mon"] = "M";
          $_day["Tue"] = "T";
          $_day["Wed"] = "W";
          $_day["Fri"] = "F";
          $_day["Sat"] = "S";

          foreach($_day as $key => $day)
          {
               if (strpos($daystring, $day) !== false)
               {
                    $return[] = $key;
                    $daystring = str_replace($day, "", $daystring);
               }
          } 

          return $return;
     }
     public function import_modal_shift_global_backup()
     {
          $file  = Request::file('file');
          $data = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->all();
          $key = 0;
          $tc = 0;
          $count=0;
          foreach ($data as  $value1) {
                    if($value1['employee_id']!=null && $value1['shift_code_name']!=null)
                         {
                              if($value1['employee_id']!=null && $value1['shift_code_name']!=null)
                              {
                              $count++;

                              $insert_code['shift_code_name']    = $value1['shift_code_name'];
                              $insert_code['shop_id']            = Self::shop_id();
                              $shift_code_id                     = Tbl_payroll_shift_code::insertGetId($insert_code);
                              
                              $update['shift_code_id']           = $shift_code_id;
                              $count_update =  Tbl_payroll_employee_basic::where('payroll_employee_number',$value1['employee_id'])->where("shop_id", Self::shop_id())->update($update);
                                                               
                                                                 

                              $insert_shift = array();
                              $shop=Self::shop_id();
                              }
                    
                              $insert_day["shift_day"] = ucfirst($value1['shift_day']);
                              $insert_day["shift_code_id"] = $shift_code_id;
                              $insert_day["shift_break_hours"] = number_format($value1['shift_break_hours'], 2, '.', '');
                              $insert_day["shift_target_hours"] = $value1['shift_target_hours'];
                              $insert_day["shift_flexi_time"] = $value1['shift_flexi_time']== 1 ? 1 : 0;
                              $insert_day["shift_rest_day"] = $value1['shift_rest_day']== 1 ? 1 : 0;
                              $insert_day["shift_extra_day"] = $value1['shift_extra_day']== 1 ? 1 : 0;
                              $shift_day_id = Tbl_payroll_shift_day::insertGetId($insert_day);
                              $insert_time[$tc]["shift_day_id"] = $shift_day_id;
                              $insert_time[$tc]["shift_work_start"] = date("H:i:s", strtotime($value1['shift_start_time'])) ;
                              $insert_time[$tc]["shift_work_end"] = date("H:i:s", strtotime($value1['shift_end_time'])) ;
                              Tbl_payroll_shift_time::insert($insert_time);
                              $insert_time = null;    
                         }
                    
                    }
                    $total_count = $count;
                    if($total_count!=null || $total_count!=0)
                    {
                       $message = '<center><span class="color-green">'.$total_count.' employees schedules are already updated.</span></center>';
                      
                    }
                    else
                    {
                       $message = '<center><span class="color-red">Nothing to insert Please Check your file.</span></center>';
                         
                    }
     return $message;
               
               
          // $return['function_name'] = 'payrollconfiguration.reload_shift_template';
          // $return['status']        = 'success';
          // return collect($return)->toJson();
               
     }

     public function get_template123()
     {
          $excels['number_of_rows'] = 15;

          $excels['data'] = ['employee_id','shift_code_name','shift_day','shift_target_hours','shift_break_hours','shift_start_time','shift_end_time','shift_flexi_time','shift_rest_day','shift_extra_day'];

          Excel::create('Shift Template', function($excel) use ($excels) {

               $excel->sheet('template', function($sheet) use ($excels) {

                $data = $excels['data'];
                $number_of_rows = $excels['number_of_rows'];
                $sheet->fromArray($data, null, 'A1', false, false);
                $sheet->freezeFirstRow();
                    

                for($row = 1, $rowcell = 2; $row <= 1; $row++, $rowcell++)
                {

                    /* EMPLOYEE ID */
                    $client_cell = $sheet->getCell('A'.$rowcell)->getDataValidation();
                    $client_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                    $client_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                    $client_cell->setAllowBlank(false);
                    $client_cell->setShowInputMessage(true);
                    $client_cell->setShowErrorMessage(true);
                    $client_cell->setShowDropDown(true);
                    $client_cell->setErrorTitle('Input error');
                    $client_cell->setError('Value is not in list.');
                    $client_cell->setFormula1('employee_id');
                    


                         for($row = 1, $rowcell = 2; $row <= $number_of_rows; $row++, $rowcell++)
                     {

                         /* Shift Day */
                         $gender_cell = $sheet->getCell('C'.$rowcell)->getDataValidation();
                         $gender_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                         $gender_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                         $gender_cell->setAllowBlank(false);
                         $gender_cell->setShowInputMessage(true);
                         $gender_cell->setShowErrorMessage(true);
                         $gender_cell->setShowDropDown(true);
                         $gender_cell->setErrorTitle('Input error');
                         $gender_cell->setError('Value is not in list.');
                         $gender_cell->setFormula1('shift_day');

                         /* Shift Day */
                         $gender_cell = $sheet->getCell('D'.$rowcell)->getDataValidation();
                         $gender_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                         $gender_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                         $gender_cell->setAllowBlank(false);
                         $gender_cell->setShowInputMessage(true);
                         $gender_cell->setShowErrorMessage(true);
                         $gender_cell->setShowDropDown(true);
                         $gender_cell->setErrorTitle('Input error');
                         $gender_cell->setError('Value is not in list.');
                         $gender_cell->setFormula1('shift_target_hours');
                         /* Shift Day */
                         $gender_cell = $sheet->getCell('E'.$rowcell)->getDataValidation();
                         $gender_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                         $gender_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                         $gender_cell->setAllowBlank(false);
                         $gender_cell->setShowInputMessage(true);
                         $gender_cell->setShowErrorMessage(true);
                         $gender_cell->setShowDropDown(true);
                         $gender_cell->setErrorTitle('Input error');
                         $gender_cell->setError('Value is not in list.');
                         $gender_cell->setFormula1('shift_target_hours');
                         /* Shift Day */
                         $gender_cell = $sheet->getCell('F'.$rowcell)->getDataValidation();
                         $gender_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                         $gender_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                         $gender_cell->setAllowBlank(false);
                         $gender_cell->setShowInputMessage(true);
                         $gender_cell->setShowErrorMessage(true);
                         $gender_cell->setShowDropDown(true);
                         $gender_cell->setErrorTitle('Input error');
                         $gender_cell->setError('Value is not in list.');
                         $gender_cell->setFormula1('shift_start_time');
                         /* Shift Day */
                         $gender_cell = $sheet->getCell('G'.$rowcell)->getDataValidation();
                         $gender_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                         $gender_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                         $gender_cell->setAllowBlank(false);
                         $gender_cell->setShowInputMessage(true);
                         $gender_cell->setShowErrorMessage(true);
                         $gender_cell->setShowDropDown(true);
                         $gender_cell->setErrorTitle('Input error');
                         $gender_cell->setError('Value is not in list.');
                         $gender_cell->setFormula1('shift_end_time');
                         /* Shift Day */
                         $gender_cell = $sheet->getCell('H'.$rowcell)->getDataValidation();
                         $gender_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                         $gender_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                         $gender_cell->setAllowBlank(false);
                         $gender_cell->setShowInputMessage(true);
                         $gender_cell->setShowErrorMessage(true);
                         $gender_cell->setShowDropDown(true);
                         $gender_cell->setErrorTitle('Input error');
                         $gender_cell->setError('Value is not in list.');
                         $gender_cell->setFormula1('shift_flexi_time');
                         /* Shift Day */
                         $gender_cell = $sheet->getCell('I'.$rowcell)->getDataValidation();
                         $gender_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                         $gender_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                         $gender_cell->setAllowBlank(false);
                         $gender_cell->setShowInputMessage(true);
                         $gender_cell->setShowErrorMessage(true);
                         $gender_cell->setShowDropDown(true);
                         $gender_cell->setErrorTitle('Input error');
                         $gender_cell->setError('Value is not in list.');
                         $gender_cell->setFormula1('shift_rest_day');
                         /* Shift Day */
                         $gender_cell = $sheet->getCell('J'.$rowcell)->getDataValidation();
                         $gender_cell->setType(\PHPExcel_Cell_DataValidation::TYPE_LIST);
                         $gender_cell->setErrorStyle(\PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
                         $gender_cell->setAllowBlank(false);
                         $gender_cell->setShowInputMessage(true);
                         $gender_cell->setShowErrorMessage(true);
                         $gender_cell->setShowDropDown(true);
                         $gender_cell->setErrorTitle('Input error');
                         $gender_cell->setError('Value is not in list.');
                         $gender_cell->setFormula1('shift_extra_day');
                    }
               }

          });

            /* DATA VALIDATION (REFERENCE FOR DROPDOWN LIST) */
            $excel->sheet('reference', function($sheet) {

                $_employee         = Tbl_payroll_employee_basic::where('shop_id',Self::shop_id())->orderBy('payroll_employee_number')->get();

                /* EMPLOYEE REFERENCES */
                $sheet->SetCellValue("A1", "employee_id");
                $client_number = 2;
                foreach($_employee as $employee)
                {
                    $sheet->SetCellValue("A".$client_number, $employee->payroll_employee_number);
                    $client_number++;
                }
                $client_number--;

                /* SHIFT DAY REFERENCES */
                $sheet->SetCellValue("C1", "shift_day");
                $sheet->SetCellValue("C2", "Mon");
                $sheet->SetCellValue("C3", "Tue");
                $sheet->SetCellValue("C4", "Wed");
                $sheet->SetCellValue("C5", "Thu");
                $sheet->SetCellValue("C6", "Fri");
                $sheet->SetCellValue("C7", "Sat");
                $sheet->SetCellValue("C8", "Sun");

                /* SHIFT DAY REFERENCES */
                $sheet->SetCellValue("D1", "shift_target_hours");
                $sheet->SetCellValue("D2", "0");
                $sheet->SetCellValue("D3", "1");
                $sheet->SetCellValue("D4", "2");
                $sheet->SetCellValue("D5", "3");
                $sheet->SetCellValue("D6", "4");
                $sheet->SetCellValue("D7", "5");
                $sheet->SetCellValue("D8", "6");
                $sheet->SetCellValue("D9", "7");
                $sheet->SetCellValue("D10", "8");
                $sheet->SetCellValue("D11", "9");
                /* SHIFT DAY REFERENCES */
                $sheet->SetCellValue("E1", "shift_break_hours");
                $sheet->SetCellValue("E2", "0");
                $sheet->SetCellValue("E3", "1");
                $sheet->SetCellValue("E4", "2");
                $sheet->SetCellValue("E5", "3");
                $sheet->SetCellValue("E6", "4");
                $sheet->SetCellValue("E7", "5");
                $sheet->SetCellValue("E8", "6");
                $sheet->SetCellValue("E9", "7");
                $sheet->SetCellValue("E10", "8");
                $sheet->SetCellValue("E11", "9");
                /* SHIFT DAY REFERENCES */
                $sheet->SetCellValue("F1", "shift_start_time");
                $sheet->SetCellValue("F2", "7:00");
                $sheet->SetCellValue("F3", "7:30");
                $sheet->SetCellValue("F4", "8:00");
                $sheet->SetCellValue("F5", "8:30");
                $sheet->SetCellValue("F6", "9:00");
                $sheet->SetCellValue("F7", "9:30");
                $sheet->SetCellValue("F8", "10:00");
                $sheet->SetCellValue("F9", "10:30");
                $sheet->SetCellValue("F10", "11:00");
                $sheet->SetCellValue("F11", "11:30");
                $sheet->SetCellValue("F12", "12:00");
                $sheet->SetCellValue("F13", "12:30");
                $sheet->SetCellValue("F14", "13:00");
                $sheet->SetCellValue("F15", "13:30");
                $sheet->SetCellValue("F16", "14:00");
                $sheet->SetCellValue("F17", "14:30");
                $sheet->SetCellValue("F18", "15:00");
                $sheet->SetCellValue("F19", "15:30");
                $sheet->SetCellValue("F20", "16:00");
                $sheet->SetCellValue("F21", "16:30");
                $sheet->SetCellValue("F22", "17:00");
                $sheet->SetCellValue("F23", "17:30");
                $sheet->SetCellValue("F24", "18:00");
                $sheet->SetCellValue("F25", "18:30");
                $sheet->SetCellValue("F26", "19:00");
                $sheet->SetCellValue("F27", "19:30");
                $sheet->SetCellValue("F28", "20:00");
                $sheet->SetCellValue("F29", "20:30");
                $sheet->SetCellValue("F30", "21:00");
                $sheet->SetCellValue("F31", "21:30");
                $sheet->SetCellValue("F32", "22:00");
                $sheet->SetCellValue("F33", "22:30");
                $sheet->SetCellValue("F34", "23:00");
                $sheet->SetCellValue("F35", "23:30");
                $sheet->SetCellValue("F36", "24:00");
                $sheet->SetCellValue("F37", "24:30");
                $sheet->SetCellValue("F38", "1:00");
                $sheet->SetCellValue("F39", "1:30");
                $sheet->SetCellValue("F40", "2:00");
                $sheet->SetCellValue("F41", "2:30");
                $sheet->SetCellValue("F42", "3:00");
                $sheet->SetCellValue("F43", "3:30");
                $sheet->SetCellValue("F44", "4:00");
                $sheet->SetCellValue("F45", "4:30");
                $sheet->SetCellValue("F46", "5:00");
                $sheet->SetCellValue("F47", "5:30");
                $sheet->SetCellValue("F48", "6:00");
                $sheet->SetCellValue("F49", "6:30");
                
                
                /* SHIFT DAY REFERENCES */
                $sheet->SetCellValue("G1", "shift_end_time");
                $sheet->SetCellValue("G2", "7:00");
                $sheet->SetCellValue("G3", "7:30");
                $sheet->SetCellValue("G4", "8:00");
                $sheet->SetCellValue("G5", "8:30");
                $sheet->SetCellValue("G6", "9:00");
                $sheet->SetCellValue("G7", "9:30");
                $sheet->SetCellValue("G8", "10:00");
                $sheet->SetCellValue("G9", "10:30");
                $sheet->SetCellValue("G10", "11:00");
                $sheet->SetCellValue("G11", "11:30");
                $sheet->SetCellValue("G12", "12:00");
                $sheet->SetCellValue("G13", "12:30");
                $sheet->SetCellValue("G14", "13:00");
                $sheet->SetCellValue("G15", "13:30");
                $sheet->SetCellValue("G16", "14:00");
                $sheet->SetCellValue("G17", "14:30");
                $sheet->SetCellValue("G18", "15:00");
                $sheet->SetCellValue("G19", "15:30");
                $sheet->SetCellValue("G20", "16:00");
                $sheet->SetCellValue("G21", "16:30");
                $sheet->SetCellValue("G22", "17:00");
                $sheet->SetCellValue("G23", "17:30");
                $sheet->SetCellValue("G24", "18:00");
                $sheet->SetCellValue("G25", "18:30");
                $sheet->SetCellValue("G26", "19:00");
                $sheet->SetCellValue("G27", "19:30");
                $sheet->SetCellValue("G28", "20:00");
                $sheet->SetCellValue("G29", "20:30");
                $sheet->SetCellValue("G30", "21:00");
                $sheet->SetCellValue("G31", "21:30");
                $sheet->SetCellValue("G32", "22:00");
                $sheet->SetCellValue("G33", "22:30");
                $sheet->SetCellValue("G34", "23:00");
                $sheet->SetCellValue("G35", "23:30");
                $sheet->SetCellValue("G36", "24:00");
                $sheet->SetCellValue("G37", "24:30");
                $sheet->SetCellValue("G38", "1:00");
                $sheet->SetCellValue("G39", "1:30");
                $sheet->SetCellValue("G40", "2:00");
                $sheet->SetCellValue("G41", "2:30");
                $sheet->SetCellValue("G42", "3:00");
                $sheet->SetCellValue("G43", "3:30");
                $sheet->SetCellValue("G44", "4:00");
                $sheet->SetCellValue("G45", "4:30");
                $sheet->SetCellValue("G46", "5:00");
                $sheet->SetCellValue("G47", "5:30");
                $sheet->SetCellValue("G48", "6:00");
                $sheet->SetCellValue("G49", "6:30");
                 /* EMPLOYMENT STATUS REFERENCES */
                $sheet->SetCellValue("H1", "shift_flexi_time");
                $sheet->SetCellValue("H2", "0");
                $sheet->SetCellValue("H3", "1");
                 /* EMPLOYMENT STATUS REFERENCES */
                $sheet->SetCellValue("I1", "shift_rest_day");
                $sheet->SetCellValue("I2", "0");
                $sheet->SetCellValue("I3", "1");
                  /* EMPLOYMENT STATUS REFERENCES */
                $sheet->SetCellValue("J1", "shift_extra_day");
                $sheet->SetCellValue("J2", "0");
                $sheet->SetCellValue("J3", "1");
                
               

                /* EMPLOYMENT STATUS REFERENCES */
                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'employee_id', $sheet, 'A2:A'.$client_number
                    )
                );
               /* EMPLOYMENT STATUS REFERENCES */
                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'shift_day', $sheet, 'C2:C8'
                    )
                );
                /* EMPLOYMENT STATUS REFERENCES */
                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'shift_target_hours', $sheet, 'D2:D11'
                    )
                );
                /* EMPLOYMENT STATUS REFERENCES */
                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'shift_break_hours', $sheet, 'E2:E11'
                    )
                );
                /* EMPLOYMENT STATUS REFERENCES */
                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'shift_start_time', $sheet, 'F2:F49'
                    )
                );
                /* EMPLOYMENT STATUS REFERENCES */
                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'shift_end_time', $sheet, 'G2:G49'
                    )
                );
                /* EMPLOYMENT STATUS REFERENCES */
                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'shift_flexi_time', $sheet, 'H2:H3'
                    )
                );
                /* EMPLOYMENT STATUS REFERENCES */
                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'shift_rest_day', $sheet, 'I2:I3'
                    )
                );
                /* EMPLOYMENT STATUS REFERENCES */
                $sheet->_parent->addNamedRange(
                    new \PHPExcel_NamedRange(
                    'shift_extra_day', $sheet, 'I2:I3'
                    )
                );
          });


        })->download('xlsx');
     }

     //LUMANG VERSION NG EXPORT SHIFT TEMPLATE
    //   public function template_1()
    // {
    //  $excels['data'][0] = ['employee_id','shift_code_name','day','target_hours','break_hours','start_time','end_time','flexi_time','rest_day','extra_day'];
    //     $excels['data'][1] = ['','', '','','','','','','',''];
    //     // dd($excels);
    //     return Excel::create('template_1', function($excel) use ($excels) {

    //         $data = $excels['data'];
    //         $date = 'template';
    //         $excel->setTitle('temp');
    //         $excel->setCreator('LARAVEL')->setCompany('DIGIMA');
    //         $excel->setDescription('payroll file');
    //         $excel->sheet($date, function($sheet) use ($data) {
    //             $sheet->fromArray($data, null, 'A1', false, false);
    //         });

    //     })->download('xlsx');
    // }


     //LUMANG VERSION NG IMPOTATION OF SHIFT TEMPLATE//
     //  public function import_modal_shift_global()
     // {
     //      $file  = Request::file('file');
     //       $data = Excel::selectSheetsByIndex(0)->load($file, function($reader){})->all();
     //      // $data = Excel::load($file)->get();

     //      $key = 0;
     //      $tc = 0;

     //      // dd($data->employee_id);
          
     //      foreach ($data as  $value) {

     //           dd($value['employee_id']);
     //           $insert_code['shift_code_name']    = $value->shift_code_name;
     //           $insert_code['shop_id']            = Self::shop_id();
     //           // dd($value->shift_code_name);
     //           $shift_code_id                     = Tbl_payroll_shift_code::insertGetId($insert_code);
     //           $update['shift_code_id']           = $shift_code_id;
     //                                                Tbl_payroll_employee_basic::where('payroll_employee_number',$value->employee_id)->where("shop_id", Self::shop_id())->update($update);
     //           $insert_shift = array();
     //           $shop=Self::shop_id();
               

     //          foreach ($data as  $value1) {
     //                /* INSERT SHIFT DAY */
     //                $insert_day["shift_day"] = ucfirst($value1->day);
     //                $insert_day["shift_code_id"] = $shift_code_id;
     //                $insert_day["shift_break_hours"] = number_format($value1->break_hours, 2, '.', '');
     //                $insert_day["shift_target_hours"] = $value1->target_hours;
     //                $insert_day["shift_flexi_time"] = $value1->flexi_time== 1 ? 1 : 0;
     //                $insert_day["shift_rest_day"] = $value1->rest_day== 1 ? 1 : 0;
     //                $insert_day["shift_extra_day"] = $value1->extra_day== 1 ? 1 : 0;
     //                // dd($insert_day);
     //                $shift_day_id = Tbl_payroll_shift_day::insertGetId($insert_day);
     //                $insert_time[$tc]["shift_day_id"] = $shift_day_id;
     //                $insert_time[$tc]["shift_work_start"] = date("H:i:s", strtotime($value1->start_time)) ;
     //                $insert_time[$tc]["shift_work_end"] = date("H:i:s", strtotime($value1->end_time)) ;
     //                Tbl_payroll_shift_time::insert($insert_time);
     //                $insert_time = null;
     //                }
     //       } 
          
     //      $return['function_name'] = 'payrollconfiguration.reload_shift_template';
     //      $return['status']        = '<center>success</center>';
     //      return collect($return)->toJson();
     // }

    //END-SHIFT IMPORT TEMPLATE//

     public function modal_view_shift_template($id)
     {
          Session::put('payroll_shift_tag_employee', array());
          $data = Self::shift_sorting($id);
          $data['employee']   = Tbl_payroll_employee_basic::where('shop_id',Self::shop_id())->where('shift_code_id',$id)->get();
          return view('member.payroll.modal.modal_view_shift_template', $data);
     }

     public function modal_tag_shift_employee()
     {
          $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

          $data['deduction_id']    =    0;

          $data['action']               ='/member/payroll/shift_template/set_tag_shift_employee';
          return view('member.payroll.modal.modal_deduction_tag_employee', $data);
     }

     public function modal_tag_add_shift_employee()
     {
           $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();

          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();

          $data['deduction_id']    =    0;

          $data['action']               ='/member/payroll/shift_template/set_add_tag_shift_employee';
          return view('member.payroll.modal.modal_deduction_tag_employee', $data);
     }

     public function set_add_tag_shift_employee()
     {
          $employee_tag = Request::input('employee_tag');

          $array = array();
          if(Session::has('payroll_add_shift_tag_employee'))
          {
               $array = Session::get('payroll_add_shift_tag_employee');
          }

          if(isset($employee_tag)){
               foreach($employee_tag as $tag)
               {
                    array_push($array, $tag);
               }    
          }

          Session::put('payroll_add_shift_tag_employee',$array);
          $return['status']             = 'success';
          $return['function_name']      = 'shifting.load_add_tag_employee';
          
          return json_encode($return);
     }

     public function set_tag_shift_employee()
     {

          $employee_tag = Request::input('employee_tag');

          $array = array();
          if(Session::has('payroll_shift_tag_employee'))
          {
               $array = Session::get('payroll_shift_tag_employee');
          }

          if(isset($employee_tag)){
               foreach($employee_tag as $tag)
               {
                    array_push($array, $tag);
               }    
          }

          Session::put('payroll_shift_tag_employee',$array);
          $return['status']             = 'success';
          $return['function_name']      = 'shifting.load_tag_employee';
          
          return json_encode($return);
     
     }

     public function get_shift_add_tag_employee()
     {
           $employee = [0 => 0];
          if(Session::has('payroll_add_shift_tag_employee'))
          {
               $employee = Session::get('payroll_add_shift_tag_employee');
          }
          $emp = Tbl_payroll_employee_basic::whereIn('payroll_employee_id',$employee)->get();

          $data['new_record'] = $emp;

          return json_encode($data);
     }

     public function get_shift_tag_employee()
     {
          $shift_id = Request::input('shift_id');

           $employee = [0 => 0];
          if(Session::has('payroll_shift_tag_employee'))
          {
               $employee = Session::get('payroll_shift_tag_employee');
          }
          $empsession = Tbl_payroll_employee_basic::whereIn('payroll_employee_id',$employee)->get();

          $empdata    = Tbl_payroll_employee_basic::where('shop_id',Self::shop_id())->where('shift_code_id',$shift_id)->get();
          
          $emp = array();
          foreach($empsession as $empses)
          {
               $temp['payroll_employee_id']            = $empses->payroll_employee_id;
               $temp['payroll_employee_display_name']  = $empses->payroll_employee_display_name;

               $checkemp = Tbl_payroll_employee_basic::where('payroll_employee_id',$empses->payroll_employee_id)->first();
               if($checkemp['shift_code_id'] != $shift_id)
               {
                    array_push($emp,$temp);
               }

               $update['shift_code_id'] = $shift_id;
               Tbl_payroll_employee_basic::where('payroll_employee_id',$empses->payroll_employee_id)->update($update);

          }

          foreach($empdata as $empdat)
          {
               $temp2['payroll_employee_id']            = $empdat->payroll_employee_id;
               $temp2['payroll_employee_display_name']  = $empdat->payroll_employee_display_name;
               array_push($emp,$temp2);
          }

          $data['shift_id']   = $shift_id;
          $data['new_record'] = $emp;


          return json_encode($data);
     }

     public function remove_shift_tag_employee()
     {
           $content = Request::input('content');
          $array     = Session::get('payroll_shift_tag_employee');
          if(($key = array_search($content, $array)) !== false) {
              unset($array[$key]);
          }
          Session::put('payroll_shift_tag_employee',$array);
     }

     public function shift_sorting($id = 0)
     {
          $data['shift_code'] = Tbl_payroll_shift_code::where('shift_code_id', $id)->first();
          $data['_day'] = Tbl_payroll_shift_day::where('shift_code_id', $id)->get();

          foreach($data["_day"] as $key => $day)
          {
               $data["_day"][$key] = $day;
               $data["_day"][$key]->time_shift = Tbl_payroll_shift_time::where("shift_day_id", $day->shift_day_id)->get();
          }
          
          return $data;
     }


     public function modal_update_shift_template()
     {
         /* CHECK EXIST */
          $shift_code_id = Request::input("shift_code_id");
          $shift_code = Tbl_payroll_shift_code::where("shop_id", Self::shop_id())->where("shift_code_id", $shift_code_id)->first();

          if($shift_code)
          {

               if(!empty(Request::input('employee_tag')))
               {
                    foreach(Request::input('employee_tag') as $tag)
                    {
                         $update['shift_code_id'] = $shift_code_id;
                         Tbl_payroll_employee_basic::where('payroll_employee_id',$tag)->update($update);
                    }
               }
               /* UPDATE SHIFT CODE */
               $update_code['shift_code_name']    = Request::input('shift_code_name');
               $old_data = AuditTrail::get_table_data("tbl_payroll_shift_code","shift_code_id",$shift_code_id);
               Tbl_payroll_shift_code::where("shop_id", Self::shop_id())->where("shift_code_id", $shift_code_id)->update($update_code);
               AuditTrail::record_logs('Updating Payroll Shift Code', 'Updating Payroll Shift Code with ID #'.$shift_code_id." To Shift Code Name value=".Request::input('shift_code_name'), $shift_code_id, "" ,serialize($old_data));
               $insert_shift = array();

               /* INSERT DAY */
               $key = 0;
               $tc = 0;

               Tbl_payroll_shift_day::where("shift_code_id", $shift_code_id)->delete();

               foreach(Request::input("day") as $day)
               {
                    /* INSERT SHIFT DAY */
                    $insert_day["shift_day"] = $day;
                    $insert_day["shift_code_id"] = $shift_code_id;
                    $insert_day["shift_target_hours"] = Request::input("target_hours")[$day];
                    $insert_day["shift_break_hours"] = Request::input("break_hours")[$day];
                    $insert_day["shift_rest_day"] = Request::input("rest_day_" . $day) == 1 ? 1 : 0;
                    $insert_day["shift_extra_day"] = Request::input("extra_day_" . $day) == 1 ? 1 : 0;
                    $insert_day["shift_flexi_time"] = Request::input("flexitime_day_" . $day) == 1 ? 1 : 0;

                    $key++;

                    $shift_day_id = Tbl_payroll_shift_day::insertGetId($insert_day);

                    /* INSERT SHIFT TIME */
                    foreach(Request::input("work_start")[$day] as $k => $time)
                    {
                         if($time != "") //MAKE SURE TIME IS NOT BLANK
                         {
                              $insert_time[$tc]["shift_day_id"] = $shift_day_id;
                              $insert_time[$tc]["shift_work_start"] = DateTime::createFromFormat( 'H:i A', $time);
                              $insert_time[$tc]["shift_work_end"] = DateTime::createFromFormat( 'H:i A', Request::input("work_end")[$day][$k]);
                              $tc++;
                         }
                    }

                    if(isset($insert_time))
                    {
                         Tbl_payroll_shift_time::insert($insert_time);
                         $insert_time = null;
                    }   
               }

          }

          $return['function_name'] = 'payrollconfiguration.reload_shift_template';
          $return['status']        = 'success';
          return collect($return)->toJson();
     }

     public function ajax_employee_schedule()
     {
          $payroll_period_id  = Request::input('payroll_period_id');
          $company            = Request::input('company');
          $department         = Request::input('department');
          $jobtitle           = Request::input('jobtitle');

          $period             = Tbl_payroll_period::where('payroll_period_id', $payroll_period_id)->first();
          $employee           = Tbl_payroll_employee_contract::employeefilter($company,$department,$jobtitle, $period->payroll_period_start, Self::shop_id())->join('tbl_payroll_group','tbl_payroll_group.payroll_group_id','=','tbl_payroll_employee_contract.payroll_group_id')
                                   ->where('tbl_payroll_group.payroll_group_period', $period->payroll_period_category)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->get()->toJson();


          return $employee;
     }


     public function modal_archive_shift_template($archived, $id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }
          $file_name          = Tbl_payroll_shift_code::where('shift_code_id', $id)->value('shift_code_name');
          $data['title']      = 'Do you really want to '.$statement.' Shift Template '.$file_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/shift_template/archive_shift_template';
          $data['id']         = $id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archive_shift_template()
     {
          $update['shift_archived'] = Request::input('archived');
          $id = Request::input('id');
          $archive = Tbl_payroll_shift_code::where('shift_code_id', $id)->first();
          Tbl_payroll_shift_code::where('shift_code_id', $id)->update($update);
          AuditTrail::record_logs('DELETED: Payroll Shift Code', 'Payroll Shift Code Name: '.$archive->shift_code_name, $id, "" ,"");

          $return['function_name'] = 'payrollconfiguration.reload_shift_template';
          $return['status']        = 'success';
          return collect($return)->toJson();
     }
     /* SHIFT TEMPLATE END */


     /* CALENDAR LEAVE START */
     public function leave_schedule()
     {
          $data['_upcoming'] = Self::leave_schedule_break();
          $data['_used'] = Self::leave_schedule_break('<');
          
          return view('member.payroll.leave_schedule', $data);
     }

     public function leave_schedule_break($param = '>=')
     {
          $_leave = collect(Tbl_payroll_leave_schedule::getlist(Self::shop_id(), date('Y-m-d'), $param)->orderBy('payroll_schedule_leave','desc')->get()->toArray())->groupBy('payroll_schedule_leave');
          return $_leave;
     }

     public function modal_create_leave_schedule()
     {
          Session::put('employee_leave_tag',array());
          $data['_leave'] = Tbl_payroll_leave_temp::sel(self::shop_id())->orderBy('payroll_leave_temp_name')->get();
          // dd($data);
          return view('member.payroll.modal.modal_create_leave_schedule', $data);
     }

     public function leave_schedule_tag_employee($id)
     {
          $data['_company']        = Tbl_payroll_company::selcompany(Self::shop_id())->orderBy('tbl_payroll_company.payroll_company_name')->get();
          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id())->orderBy('payroll_department_name')->get();
          $data['leave_id']        = $id;
          $data['action']          = '/member/payroll/leave_schedule/session_tag_leave';

          Session::put('employee_leave_tag', array());

          return view('member.payroll.modal.modal_schedule_employee_leave', $data);
     }

     public function ajax_shecdule_leave_tag_employee()
     {
          $company       = Request::input('company');
          $department    = Request::input('department');
          $jobtitle      = Request::input('jobtitle');
          $leave_id      = Request::input("leave_id");

          // dd($leave_id);
          $emp = Tbl_payroll_employee_contract::employeefilter($company, $department, $jobtitle, date('Y-m-d'), Self::shop_id())
                    ->join('tbl_payroll_leave_employee','tbl_payroll_leave_employee.payroll_employee_id','=','tbl_payroll_employee_contract.payroll_employee_id')
                    ->join('tbl_payroll_leave_temp','tbl_payroll_leave_temp.payroll_leave_temp_id','=','tbl_payroll_leave_employee.payroll_leave_temp_id')
                    ->leftjoin('tbl_payroll_leave_schedule','tbl_payroll_leave_schedule.payroll_leave_employee_id','=','tbl_payroll_leave_employee.payroll_leave_employee_id')
                    ->where('tbl_payroll_leave_employee.payroll_leave_temp_id',$leave_id)
                    ->where('tbl_payroll_leave_employee.payroll_leave_employee_is_archived', 0)
                    ->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')
                    ->groupBy('tbl_payroll_employee_basic.payroll_employee_id')                                                                                           
                    ->select(DB::raw('*, tbl_payroll_leave_employee.payroll_leave_employee_id as leave_employee_id, tbl_payroll_leave_temp.payroll_leave_temp_days_cap as leave_cap ,(tbl_payroll_leave_temp.payroll_leave_temp_days_cap - sum(tbl_payroll_leave_schedule.consume)) as remaining_leave ,(tbl_payroll_leave_temp.payroll_leave_temp_days_cap - (select count(tbl_payroll_leave_schedule.payroll_leave_employee_id) from tbl_payroll_leave_schedule where (tbl_payroll_leave_schedule.payroll_schedule_leave  BETWEEN "'.date('Y').'-01-01" and "'.date('Y').'-12-31") and tbl_payroll_leave_schedule.payroll_leave_employee_id = leave_employee_id)) as available_count, tbl_payroll_leave_employee.payroll_leave_employee_id as payroll_leave_employee_id_2'))
                    ->get();

          return json_encode($emp);
     }

     public function session_tag_leave()
     {
          // Tbl_payroll_leave_schedule
          $employee_tag = array();

          if(Session::has('employee_leave_tag'))
          {
               $employee_tag = Session::get('employee_leave_tag');
          }

          foreach(Request::input('employee_tag') as $tag)
          {
               if(!in_array($tag, $employee_tag))
               {
                    array_push($employee_tag, $tag);
               }
          }
          Session::put('employee_leave_tag', $employee_tag);

          $data['status'] = 'success';
          $data['function_name'] = 'employee_tag_schedule_leave.load_tagged_employee';

          return collect($data)->toJson();
     }


     public function get_session_leave_tag()
     {
          $employee = [0 => 0];
          if(Session::has('employee_leave_tag'))
          {
               $employee = Session::get('employee_leave_tag');
          }

          // dd($employee);

          $emp = Tbl_payroll_employee_basic::join('tbl_payroll_leave_employee','tbl_payroll_leave_employee.payroll_employee_id','=','tbl_payroll_employee_basic.payroll_employee_id')->whereIn('tbl_payroll_leave_employee.payroll_leave_employee_id',$employee)->get();
          $data['new_record'] = $emp;

          return json_encode($data);
     }


     public function save_schedule_leave_tag()
     {
          // Tbl_payroll_leave_schedule
          $payroll_schedule_leave = datepicker_input(Request::input('payroll_schedule_leave'));
          
          if(Request::has('employee_tag'))
          {
               $insert = array();
               $leave_reason = Tbl_payroll_leave_temp::where('payroll_leave_temp_id',Request::input("leave_reason"))->where('shop_id',$this->user_info["user_shop"])->first();
  
               foreach(Request::input('employee_tag') as $tag)
               {
                    $leave_hours = Request::input("leave_hours_".$tag);

                    if(Request::has('single_date_only'))
                    {
                         $temp['payroll_leave_employee_id']    = $tag;
                         $temp['payroll_schedule_leave']       = $payroll_schedule_leave;
                         $temp['shop_id']                      = Self::shop_id();
                         $temp['leave_hours']                  = $leave_hours;
                         $temp['consume']                      = Payroll::time_float($leave_hours);
                         $temp['notes']                        = "Used ".$leave_hours." hours in ".$leave_reason["payroll_leave_temp_name"];
                         array_push($insert, $temp);
                    }

                    else
                    {
                         $end = datepicker_input(Request::input('payroll_schedule_leave_end'));
                         while($payroll_schedule_leave <= $end)
                         {
                              $temp['payroll_leave_employee_id']    = $tag;
                              $temp['payroll_schedule_leave']       = $payroll_schedule_leave;
                              $temp['shop_id']                      = Self::shop_id();
                              $temp['leave_hours']                  = $leave_hours;
                              $temp['consume']                      = Payroll::time_float($leave_hours);
                              $temp['notes']                        = "Used ".$leave_hours." hours in ".$leave_reason["payroll_leave_temp_name"];
                              array_push($insert, $temp);
                              $payroll_schedule_leave = Carbon::parse($payroll_schedule_leave)->addDay()->format("Y-m-d");
                         }
                    }
               }
               if(!empty($insert))
               {  
                    Tbl_payroll_leave_schedule::insert($insert);
               }
          }    

          $response['call_function'] = 'reload';
          $response['status'] = 'success';

          return $response;

     }

     public function delete_confirm_schedule_leave($id)
     {
          // $leave              = Tbl_payroll_leave_schedule::specific($id)->first();
          $data['title']      = 'Do you really want to remove this schedule';
          $data['action']     = '/member/payroll/leave_schedule/delete_schedule_leave';
          $data['id']         = $id;
          $data['html']       = '';
          $data['payroll_deduction_type'] = 0;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function delete_schedule_leave()
     {
          $id = Request::input('id');
          Tbl_payroll_leave_schedule::where('payroll_leave_schedule_id', $id)->delete();
          AuditTrail::record_logs('DELETED: Payroll Leave Schedule', 'Payroll Leave Schedule with ID #'.$id, $id, "" ,"");

          $data['status'] = 'success';
          $data['function_name'] = '';

          return collect($data)->toJson();
     }

     public function unset_session_leave_tag()
     {
          $content = Request::input('content');
          $array     = Session::get('employee_leave_tag');
          if(($key = array_search($content, $array)) !== false) {
              unset($array[$key]);
          }
          Session::put('employee_leave_tag', $array);
     }

     /* CALDENDAR LEAVE END */



     public function modal_generate_period()
     {
          $data['_period'] = Tbl_payroll_period::sel(Self::shop_id())->where('payroll_period_status','pending')->orderBy('payroll_period_start','desc')->paginate(20);
          return view('member.payroll.modal.modal_generate_period', $data);
     }

     public function generate_period()
     {
          if(Request::has('payroll_period_id'))
          {
               $_payroll_period_id = Request::input('payroll_period_id');
               foreach($_payroll_period_id as $payroll_period_id)
               {
                    $update['payroll_period_status'] = 'generated';
                    $old_data = AuditTrail::get_table_data("tbl_payroll_period","payroll_period_id",$payroll_period_id);
                    Tbl_payroll_period::where('payroll_period_id',$payroll_period_id)->update($update);
                    AuditTrail::record_logs('EDITED: Payroll Period', ' Payroll Period with ID #'.$payroll_period_id." To Period Status value=GENERATED", $payroll_period_id, "" ,serialize($old_data));
               }
          }

          $return['message']       = 'success';
          $return['function_name'] = 'payroll_timekeeping.reload_timekeeping';
          return json_encode($return);
     }
     public function company_period_delete($id)
     {

          $select = Tbl_payroll_period_company::where("payroll_period_company_id", $id)->first();
          $payroll_period = Tbl_payroll_period::where("payroll_period_id", $select->payroll_period_id)->first();

          $_payroll_timesheet_ids = Tbl_payroll_period::
          select(DB::raw("tbl_payroll_time_sheet.payroll_time_sheet_id, tbl_payroll_employee_basic.payroll_employee_first_name"))
          ->where('tbl_payroll_period.shop_id',Self::shop_id())
          ->whereBetween('tbl_payroll_time_sheet.payroll_time_date', array($payroll_period["payroll_period_start"], $payroll_period["payroll_period_end"]))
          ->join('tbl_payroll_employee_basic','tbl_payroll_period.shop_id','=','tbl_payroll_employee_basic.shop_id')
          ->join('tbl_payroll_time_sheet','tbl_payroll_time_sheet.payroll_employee_id','=','tbl_payroll_employee_basic.payroll_employee_id')
          ->join('tbl_payroll_time_sheet_record_approved','tbl_payroll_time_sheet_record_approved.payroll_time_sheet_id','=','tbl_payroll_time_sheet.payroll_time_sheet_id')
          ->groupBy("tbl_payroll_time_sheet.payroll_time_sheet_id")
          ->get();
          // dd($_payroll_timesheet_ids);

          foreach ($_payroll_timesheet_ids as $payroll_timesheets_ids) 
          {
               Tbl_payroll_time_sheet::where("payroll_time_sheet_id",$payroll_timesheets_ids["payroll_time_sheet_id"])->delete();
          }
         AuditTrail::record_logs("DELETED: Payroll Period","Payroll Period : ".date("F j, Y",strtotime($payroll_period->payroll_period_start))." - ".date("F j, Y",strtotime($payroll_period->payroll_period_end)),$id,$payroll_period,"");
         
          Tbl_payroll_period_company::where("payroll_period_company_id", $id)->delete();
         
          return Redirect::to("/member/payroll/time_keeping");
     }
     public function company_period($id)
     {
          $param['payroll_period_id']   = $id;
          $param['shop_id']             = Self::shop_id();
          $count = Tbl_payroll_period::check($param)->count();

          if($count == 0)
          {
               return Redirect::to('/member/payroll/time_keeping')->send();
          }

          $data['_company']   = Self::company_badge($id);
          $data['period']     = Tbl_payroll_period::where('payroll_period_id', $id)->first();
          return view('member.payroll.payroll_timekeeping_company',$data);
     }

     public function company_badge($id)
     {
          $return = array();
          $_data = Tbl_payroll_period_company::selperiod($id)->orderBy('tbl_payroll_company.payroll_company_name')->select('tbl_payroll_period_company.*','tbl_payroll_company.payroll_company_name')->get();
          foreach($_data as $data)
          {
               $temp['payroll_period_company_id'] = $data->payroll_period_company_id;
               $temp['payroll_company_name']      = $data->payroll_company_name;
               $temp['payroll_period_status']     = $data->payroll_period_status;
               $badge                             = 'custom-badge-default';
               if($data->payroll_period_status == 'ready')
               {
                    $badge = 'custom-badge-primary';
               }
               else if($data->payroll_period_status == 'processed' || $data->payroll_period_status == 'posted' || $data->payroll_period_status == 'registered')
               {
                    $badge = 'custom-badge-warning';
               }
               else if($data->payroll_period_status == 'approved')
               {
                    $badge = 'custom-badge-success';
               }
               $temp['badge'] = $badge;
               array_push($return, $temp);
          }
          return $return;
     }

     public function no_records()
     {
          return  view('member.payroll.misc.no_records');
     }


     public function mark_ready_company()
     {
          $content = Request::input("content");
          $update['payroll_period_status'] = 'ready';
          // dd($content);
          Tbl_payroll_period_company::where('payroll_period_company_id',$content)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Period Company', 'Updating Payroll Period Company with ID #'.$content." to payroll status: READY",$content, "" , "");
          
          

          return 'success';
     }
     // public function 

     /* PAYROLL TIME KEEPING END */



     /* PAYROLL PROCESS START */
     public function payroll_process()
     {
          $data['_period'] = Payroll::process_compute(Self::shop_id(), 'processed');
          // dd($data);
          return view('member.payroll.payroll_process', $data);
     }

     public function modal_create_process()
     {
          $data['_period'] = Tbl_payroll_tax_period::check(Self::shop_id())->get();
          return view('member.payroll.modal.modal_create_payroll_process',$data);
     }

     public function ajax_load_payroll_period()
     {
          $tax = Request::input('tax');
          $data = Tbl_payroll_period_company::getperiod(Self::shop_id(), $tax)
                                             ->select(DB::raw("DATE_FORMAT(tbl_payroll_period.payroll_period_start, '%b %d, %y') as start,DATE_FORMAT(tbl_payroll_period.payroll_period_end, '%b %d, %y') as end, tbl_payroll_period.payroll_period_id"))
                                             ->orderBy('tbl_payroll_period.payroll_period_start')
                                             ->get();

          return $data->toJson();
     }

     public function ajax_payroll_company_period()
     {
          $period = Request::input('period');
          $data = Tbl_payroll_period_company::selperiod($period)
                                             ->where('tbl_payroll_period_company.payroll_period_status','ready')
                                             ->orderBy('tbl_payroll_company.payroll_company_name')
                                             ->groupBy('tbl_payroll_company.payroll_company_id')
                                             ->get()
                                             ->toJson();
          return $data;
     }

     public function process_payroll()
     {
          if(Request::has('company_period'))
          {
               $company_period = Request::input('company_period');
               foreach($company_period as $period)
               {
                    $update['payroll_period_status'] = 'processed';
                    Tbl_payroll_period_company::where('payroll_period_company_id', $period)->update($update);
                    AuditTrail::record_logs('Updating Payroll Period Company', 'Updating Payroll Period Company with ID#'.$period." With Company period Value=".$company_period,$period, "" , "");
                    
               }
          }

          $return['status']        = 'success';
          $return['function_name'] = 'reload_page';
          return json_encode($return);
          
     }

     public function modal_13_month($payroll_employee_id, $payroll_period_company_id)
     {
          $_13_month   = Tbl_payroll_record::get13month($payroll_employee_id)->get();

          $v_13_month  = Tbl_payroll_13_month_virtual::getperiod($payroll_employee_id, $payroll_period_company_id)->count();

          $period = Tbl_payroll_period_company::getcompanyperiod($payroll_period_company_id)->first();

          $compute = Payroll::compute_per_employee($payroll_employee_id, $period->payroll_period_start, $period->payroll_period_end, Self::shop_id(), $period->payroll_period_category, $payroll_period_company_id);
          
          $data['_13_month'] = array();

          foreach($_13_month as $m13)
          {
               $count = Tbl_payroll_13_month_compute::where('payroll_employee_id', $payroll_employee_id)->where('payroll_record_id', $m13->payroll_record_id)->count();
               $temp['status'] = '';

               if($count == 1)
               {
                    $temp['status'] = 'checked="checked"';
               }
               $temp['regular_salary']       = $m13->regular_salary;
               $temp['payroll_period_start'] = $m13->payroll_period_start;
               $temp['payroll_period_end']   = $m13->payroll_period_end;
               $temp['payroll_record_id']    = $m13->payroll_record_id;

               array_push($data['_13_month'], $temp);
          }


          $data['v_13_month_period_start']   = $period->payroll_period_start;
          $data['v_13_month_period_end']     = $period->payroll_period_end;
          $data['v_status']                  = '';
          $data['v_regular_salary']          = $compute['regular_salary'];
          if($v_13_month == 1)
          {
               $data['v_status']   = 'checked="checked"';
          }

          $data['payroll_employee_id']       = $payroll_employee_id;
          $data['payroll_period_company_id'] = $payroll_period_company_id;
          return view('member.payroll.modal.modal_13_month', $data);
     }

     public function modal_submit_13_month()
     {
          // 
          $payroll_employee_id          = Request::input('payroll_employee_id');
          $payroll_period_company_id    = Request::input('payroll_period_company_id');
          $payroll_record_id            = array();
          $new_old_data = AuditTrail::get_table_data("tbl_payroll_13_month_compute","payroll_employee_id",$payroll_employee_id);
          Tbl_payroll_13_month_compute::where('payroll_employee_id', $payroll_employee_id)->where('payroll_period_company_id', $payroll_period_company_id)->delete();
          AuditTrail::record_logs('DELETED', 'Payroll Process Leave with ID #'.$payroll_employee_id,$payroll_employee_id, $new_old_data , $new_old_data);

          Tbl_payroll_13_month_virtual::getperiod($payroll_employee_id, $payroll_period_company_id)->delete();
            AuditTrail::record_logs('DELETED', 'Payroll 13 Month Virtual with period Company ID #'.$payroll_period_company_id. " And Payroll Employee ID #".$payroll_employee_id, $payroll_employee_id, "" ,"");
         
          if(Request::has('payroll_record_id'))
          {
               $payroll_record_id  = Request::input('payroll_record_id');
               $insert             = array();
               foreach($payroll_record_id as $id)
               {
                    $temp['shop_id']                   = Self::shop_id();
                    $temp['payroll_employee_id']       = $payroll_employee_id;
                    $temp['payroll_period_company_id'] = $payroll_period_company_id;
                    $temp['payroll_record_id']         = $id;

                    array_push($insert, $temp);
               }



               if(!empty($insert))
               {
                    Tbl_payroll_13_month_compute::insert($insert);
               }

          }

          if(Request::has('payroll_period_company_id_v_13'))
          {
               $insert_v['payroll_employee_id'] = $payroll_employee_id;
               $insert_v['payroll_period_company_id'] = $payroll_period_company_id;
               Tbl_payroll_13_month_virtual::insert($insert_v);
          }    

          $data['status']                         = 'success';
          $data['payroll_employee_id']            = $payroll_employee_id;
          $data['payroll_period_company_id']      = $payroll_period_company_id;
          $data['function_name']                  = 'reload_break_down';

          return json_encode($data);
     }

     public function modal_unused_leave($payroll_employee_id, $payroll_period_company_id)
     {
          $date = Tbl_payroll_period_company::sel($payroll_period_company_id)->value('payroll_period_end');
          // $_leave = Tbl_payroll_leave_schedule::getremaining($payroll_employee_id, $date)->orderBy('tbl_payroll_leave_temp.payroll_leave_temp_name')->get();

          $payable_leave = array();

          $_leave = Tbl_payroll_leave_employee::getpayable_leave($payroll_employee_id)->orderBy('tbl_payroll_leave_temp.payroll_leave_temp_name')->get();

          foreach($_leave as $leave)
          {
               /* count used leave this current year */
               $count_use = Tbl_payroll_leave_schedule::getyearly($leave->payroll_leave_employee_id)->count();

               $temp['payroll_leave_employee_id']      = $leave->payroll_leave_employee_id;
               $temp['payroll_leave_temp_id']          = $leave->payroll_leave_temp_id;
               $temp['payroll_leave_temp_name']        = $leave->payroll_leave_temp_name;
               $temp['payroll_leave_temp_days_cap']    = $leave->payroll_leave_temp_days_cap;
               $temp['remaining']                      = $leave->payroll_leave_temp_days_cap - $count_use;
               $temp['status']                         = '';
               $temp['process_leave_quantity']         = 0;

               $leave_data = Tbl_payroll_process_leave::getleave($payroll_employee_id, $payroll_period_company_id)->where('payroll_leave_temp_id', $leave->payroll_leave_temp_id)->first();

               if($leave_data != null)
               {
                    $temp['status'] = 'checked="checked"';
                    $temp['process_leave_quantity'] = $leave_data->process_leave_quantity;
               }

               array_push($payable_leave, $temp);
          }

          $data['payable_leave']             = $payable_leave;
          $data['payroll_employee_id']       = $payroll_employee_id;
          $data['payroll_period_company_id'] = $payroll_period_company_id;

          return view('member.payroll.modal.modal_unused_leave', $data);
     }

     public function modal_save_process_leave()
     {
          $payroll_employee_id = Request::input('payroll_employee_id');
          $payroll_period_company_id = Request::input('payroll_period_company_id');

        
         Tbl_payroll_process_leave::getleave($payroll_employee_id, $payroll_period_company_id)->delete();
          AuditTrail::record_logs('DELETED', 'Payroll Process Leave', $id, "" ,"");
          
          
          if(Request::has('payroll_leave_temp_id'))
          {
               $payroll_leave_temp_id   = Request::input('payroll_leave_temp_id');
               $process_leave_quantity  = Request::input("process_leave_quantity");
               $payroll_leave_temp_name = Request::input('payroll_leave_temp_name');
               $insert = array();
               foreach($payroll_leave_temp_id as $key => $id)
               {
                    $temp['payroll_employee_id']       = $payroll_employee_id;
                    $temp['payroll_period_company_id'] = $payroll_period_company_id;
                    $temp['payroll_leave_temp_id']     = $id;
                    $temp['process_leave_quantity']    = $process_leave_quantity[$key];
                    $temp['payroll_leave_temp_name']   = $payroll_leave_temp_name[$key];
                    array_push($insert, $temp);
               }

               if(!empty($insert))
               {
                    Tbl_payroll_process_leave::insert($insert);
               }
          }

          $data['status']                         = 'success';
          $data['payroll_employee_id']            = $payroll_employee_id;
          $data['payroll_period_company_id']      = $payroll_period_company_id;
          $data['function_name']                  = 'reload_break_down';

          return json_encode($data);
     }

     /* PAYROLL COMPUTATION BREAKDOWN */
     public function payroll_compute_brk_unsaved($employee_id, $payroll_period_company_id)
     {
          $period = Tbl_payroll_period_company::sel($payroll_period_company_id)->first();

          $process = Payroll::compute_per_employee($employee_id, $period->payroll_period_start, $period->payroll_period_end, Self::shop_id(), $period->payroll_period_category, $payroll_period_company_id);

          $data['emp'] = Tbl_payroll_employee_basic::where('payroll_employee_id',$employee_id)->first();

          $data['_breakdown'] = Self::breakdown_uncompute($process);
          $data['payroll_period_company_id'] = $payroll_period_company_id;
          $data['status'] = 'process';

          return view('member.payroll.modal.modal_view_payroll_computation_unsaved',$data);

     }


     public function payroll_explain_computation($employee_id, $payroll_period_company_id)
     {
          $period = Tbl_payroll_period_company::sel($payroll_period_company_id)->first();

          $process = Payroll::compute_per_employee($employee_id, $period->payroll_period_start, $period->payroll_period_end, Self::shop_id(), $period->payroll_period_category, $payroll_period_company_id);

          $data['emp'] = Tbl_payroll_employee_basic::where('payroll_employee_id',$employee_id)->first();
          $data['period'] = date('F d, Y', strtotime($period->payroll_period_start)).' to '.date('F d, Y', strtotime($period->payroll_period_end));
          $data['_details'] = $process['_details'];

          $collect = collect($process['_details']);
          
          $data['total_regular_salary']      = $collect->sum('regular_salary');
          $data['total_late_deduction']      = $collect->sum('late_deduction');
          $data['total_under_time']          = $collect->sum('under_time');
          $data['total_absent_deduction']    = $collect->sum('absent_deduction');
          $data['total_early_ot']            = $collect->sum('total_early_ot');
          $data['total_reg_ot']              = $collect->sum('total_reg_ot');
          $data['total_rest_days']           = $collect->sum('total_rest_days');
          $data['total_extra_salary']        = $collect->sum('extra_salary');
          $data['total_night_differential']  = $collect->sum('total_night_differential');
          $data['total_sh_salary']           = $collect->sum('sh_salary');
          $data['total_rh_salary']           = $collect->sum('rh_salary');
          $data['total_cola']                = $collect->sum('cola');
          $data['total_leave']               = $collect->sum('leave');
          $data['total_break']               = $collect->sum('break');


          return view('member.payroll.modal.modal_view_computation_details', $data);
     }


     public function computation_details($details = array())
     {
          $data = array();
     }


     public function breakdown_uncompute($process = array(), $status = 'processed')
     {
          // dd($process);
          $data = array();

          $computation   = array();
          $time          = array();
          $day           = array();

          $salary        = array();
          $government    = array();
          $deduction     = array();

          
          /* RATE DECLARATION */
          $temp = array();
          $temp['name']       = '<b>Rate</b>';
          $temp['amount']     = '';
          $temp['sub']        = array();
          array_push($salary, $temp);

          if($process['display_monthly_rate'] == 1)
          {
               $temp = array();
               $temp['name']       = 'Monthly Rate';
               $temp['amount']     = number_format($process['salary_monthly'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }
          
          if($process['display_daily_rate'] == 1)
          {
               $temp = array();
               $temp['name']       = 'Daily Rate';
               $temp['amount']     = number_format($process['salary_daily'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }

          /* ALL POSITIVE */

          $temp = array();
          if($process['total_gross'] > 0)
          {    
               $temp['name']       = '<b>Salary</b>';
               $temp['amount']     = '';
               $temp['sub']        = array();
               array_push($salary, $temp);
          }    

          $temp = array();
          if($process['regular_salary'] > 0)
          {    
               $temp['name']       = 'Basic Salary';
               $temp['amount']     = number_format($process['regular_salary'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }    


          $regular_overtime = $process['regular_reg_overtime'] + $process['extra_reg_overtime'] + $process['rest_day_reg_overtime'] + $process['rest_day_sh_reg_overtime'] + $process['rest_day_rh_reg_overtime'] + $process['rh_reg_overtime'] + $process['sh_reg_overtime'];

          $temp = array();
          if($regular_overtime > 0)
          {    
               $temp['name']       = 'Regular OT';
               $temp['amount']     = number_format($regular_overtime, 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }

          $early_overtime = $process['regular_early_overtime'] + $process['extra_early_overtime'] + $process['rest_day_early_overtime'] + $process['rest_day_sh_early_overtime'] + $process['rest_day_rh_early_overtime'] + $process['rh_early_overtime'] + $process['sh_early_overtime'];

          $temp = array();
          if($early_overtime > 0)
          {    
               $temp['name']       = 'Early OT';
               $temp['amount']     = number_format($early_overtime, 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }

          $night_differentials = $process['regular_night_diff'] + $process['extra_night_diff'] + $process['rest_day_night_diff'] + $process['rest_day_sh_night_diff'] + $process['rest_day_rh_night_diff'] + $process['rh_night_diff'] + $process['sh_night_diff'];

          $temp = array();
          if($night_differentials > 0)
          {    
               $temp['name']       = 'Night Diff.';
               $temp['amount']     = number_format($night_differentials, 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }

          $temp = array();
          if($process['extra_salary'] > 0)
          {    
               $temp['name']       = 'Extra Day';
               $temp['amount']     = number_format($process['extra_salary'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }   

          $temp = array();
          if($process['rest_day_salary'] > 0)
          {    
               $temp['name']       = 'Rest Day';
               $temp['amount']     = number_format($process['rest_day_salary'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }   

          $temp = array();
          if($process['rest_day_sh'] > 0)
          {    
               $temp['name']       = 'Rest Day + Special Holiday';
               $temp['amount']     = number_format($process['rest_day_sh'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }    

          $temp = array();
          if($process['rest_day_rh'] > 0)
          {    
               $temp['name']       = 'Rest Day + Regular Holiday';
               $temp['amount']     = number_format($process['rest_day_rh'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }   

          $temp = array();
          if($process['rh_salary'] > 0)
          {    
               $temp['name']       = 'Regular Holiday';
               $temp['amount']     = number_format($process['rh_salary'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }   

          $temp = array();
          if($process['sh_salary'] > 0)
          {    
               $temp['name']       = 'Special Holiday';
               $temp['amount']     = number_format($process['sh_salary'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }    

          $temp = array();
          // $total_13_month = $process['13_month'] + $process['adjustment']['total_13_month'];

          if($process['13_month'] > 0)
          {    
               $temp['name']       = '13 month Pay';
               $temp['amount']     = number_format($process['13_month'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }  

          if($process['adjustment']['total_13_month'] > 0)
          {
               foreach($process['adjustment']['13_month'] as $n13month)
               {
                    $temp['name'] = $n13month->payroll_adjustment_name.' (13 month pay)';

                    if($status == 'processed')
                    {
                         $temp['name'].=Self::btn_adjustment($n13month->payroll_adjustment_id);
                    }

                    $temp['amount'] = number_format($n13month->payroll_adjustment_amount, 2);
                    $temp['sub']        = array();
                    array_push($salary, $temp);
               }
          }

          // dd($salary);


          $temp = array();
          if($process['payroll_cola'] > 0)
          {    
               $temp['name']       = 'COLA';
               $temp['amount']     = number_format($process['payroll_cola'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }   

          if($process['leave_amount'] > 0)
          {
               $temp = array();
               $temp['name']       = 'Leave';
               $temp['amount']     = number_format($process['leave_amount'], 2);
               $temp['sub']        = array();
               array_push($salary, $temp);
          }

          $temp = array();

          $total_allowance = $process['adjustment']['total_allowance'] + $process['total_allowance'];
          
          if($total_allowance > 0)
          {    
               $temp['name']       = '<b>Allowance</b>';
               $temp['amount']     = '';
               $temp['sub']        = array();
               foreach($process['allowance'] as $allowance)
               {
                    $temp_sub['name'] = $allowance['payroll_allowance_name'];
                    $temp_sub['amount'] = number_format($allowance['payroll_allowance_amount'], 2);
                    array_push($temp['sub'], $temp_sub);
               }

               foreach($process['adjustment']['allowance'] as $allowances)
               {
                    $temp_sub['name'] = $allowances->payroll_adjustment_name;
                    if($status == 'processed')
                    {
                         $temp_sub['name'].=Self::btn_adjustment($allowances->payroll_adjustment_id);
                    }
                    $temp_sub['amount'] = number_format($allowances->payroll_adjustment_amount, 2);
                    array_push($temp['sub'], $temp_sub);
               }

               array_push($salary, $temp);
          }  

          $temp = array();
          if($process['adjustment']['total_bonus'] > 0)
          {    
               $temp['name']       = '<b>Bonus</b>';
               $temp['amount']     = '';
               $temp['sub']        = array();
               foreach($process['adjustment']['bonus'] as $bonus)
               {
                    $temp_sub['name'] = $bonus->payroll_adjustment_name;
                    if($status == 'processed')
                    {
                         $temp_sub['name'].=Self::btn_adjustment($bonus->payroll_adjustment_id);
                    }
                    $temp_sub['amount'] = number_format($bonus->payroll_adjustment_amount, 2);
                    array_push($temp['sub'], $temp_sub);
               }
               array_push($salary, $temp);
          }  

          $temp = array();
          if($process['adjustment']['total_incentives'] > 0)
          {    
               $temp['name']       = '<b>Incentives</b>';
               $temp['amount']     = '';
               $temp['sub']        = array();
               foreach($process['adjustment']['incentives'] as $incentives)
               {
                    $temp_sub['name'] = $incentives->payroll_adjustment_name;
                    if($status == 'processed')
                    {
                         $temp_sub['name'].=Self::btn_adjustment($incentives->payroll_adjustment_id);
                    }
                    $temp_sub['amount'] = number_format($incentives->payroll_adjustment_amount, 2);
                    array_push($temp['sub'], $temp_sub);
               }
               array_push($salary, $temp);
          }  

          $temp = array();
          if($process['adjustment']['total_commission'] > 0)
          {    
               $temp['name']       = '<b>Commission</b>';
               $temp['amount']     = '';
               $temp['sub']        = array();
               foreach($process['adjustment']['commission'] as $commission)
               {
                    $temp_sub['name'] = $commission->payroll_adjustment_name;
                    if($status == 'processed')
                    {
                         $temp_sub['name'].=Self::btn_adjustment($commission->payroll_adjustment_id);
                    }
                    $temp_sub['amount'] = number_format($commission->payroll_adjustment_amount, 2);
                    array_push($temp['sub'], $temp_sub);
               }
               array_push($salary, $temp);
          }  

          
          if($process['_total_unused_leave'] > 0)
          {
               $temp = array();
               $temp['name']       = '<b>Leave to Cash</b>';
               $temp['amount']     = '';
               $temp['sub']        = array();
               array_push($salary, $temp);

               foreach($process['_unused_leave'] as $leave)
               {
                    $temp = '';
                    $temp['name']       = $leave['payroll_leave_temp_name'].' ('.$leave['process_leave_quantity'].')';
                    $temp['amount']     = number_format($leave['process_leave_amount'], 2);
                    $temp['sub']        = array();
                    array_push($salary, $temp);
               }
          }


          $temp = array();
          if($process['total_gross'] > 0)
          {    
               $temp['name']       = '<b>Gross</b>';
               $temp['amount']     = '<b>'.number_format($process['total_gross'], 2).'</b>';
               $temp['sub']        = array();
               array_push($salary, $temp);
          }  



          /* ALL GOVERMENT */
          $total_contribution = $process['tax_contribution'] + $process['sss_contribution_ee'] + $process['pagibig_contribution'] + $process['philhealth_contribution_ee'];

          $temp = array();
          if($total_contribution > 0)
          {    
               $temp['name']       = '<b>Goverment Contribution</b>';
               $temp['amount']     = '';
               $temp['sub']        = array();
               array_push($government, $temp);
          }  

          $temp = array();
          if($process['sss_contribution_ee'] > 0)
          {    
               $temp['name']       = 'SSS';
               $temp['amount']     = number_format($process['sss_contribution_ee'], 2);
               $temp['sub']        = array();
               array_push($government, $temp);
          }  


          $temp = array();
          if($process['philhealth_contribution_ee'] > 0)
          {    
               $temp['name']       = 'Philhealth';
               $temp['amount']     = number_format($process['philhealth_contribution_ee'], 2);
               $temp['sub']        = array();
               array_push($government, $temp);
          }  

          $temp = array();
          if($process['pagibig_contribution'] > 0)
          {    
               $temp['name']       = 'PAGIBIG';
               $temp['amount']     = number_format($process['pagibig_contribution'], 2);
               $temp['sub']        = array();
               array_push($government, $temp);
          }  


          $temp = array();
          if($process['tax_contribution'] > 0)
          {    
               $temp['name']       = 'Tax';
               $temp['amount']     = number_format($process['tax_contribution'], 2);
               $temp['sub']        = array();
               array_push($government, $temp);
          }  

          $temp = array();
          if($total_contribution > 0)
          {    
               $temp['name']       = '<b>Total Contribution</b>';
               $temp['amount']     = '<b>'.number_format($total_contribution, 2).'</b>';
               $temp['sub']        = array();
               array_push($government, $temp);
          }  


          // deduction
          /* OTHER DEDUCTIONS */
          $total_deduction = collect($process['deduction'])->sum('payroll_periodal_deduction') + $process['late_deduction'] + $process['adjustment']['total_deductions'] + $process['absent_deduction'] + $process['under_time'];

          $temp = array();
          if($total_deduction > 0)
          {    
               $temp['name']       = '<b>Deduction</b>';
               $temp['amount']     = '';
               $temp['sub']        = array();
               array_push($deduction, $temp);
          }  

          $temp = array();
          if($process['break_deduction'] > 0)
          {    
               $temp['name']       = 'Break Deduction';
               $temp['amount']     = number_format($process['break_deduction'], 2);
               $temp['sub']        = array();
               array_push($deduction, $temp);
          }  

          $temp = array();
          if($process['late_deduction'] > 0)
          {    
               $temp['name']       = 'Late';
               $temp['amount']     = number_format($process['late_deduction'], 2);
               $temp['sub']        = array();
               array_push($deduction, $temp);
          }  

          $temp = array();
          if($process['under_time'] > 0)
          {    
               $temp['name']       = 'Under Time';
               $temp['amount']     = number_format($process['under_time'], 2);
               $temp['sub']        = array();
               array_push($deduction, $temp);
          }  

          $temp = array();
          if($process['absent_deduction'] > 0)
          {    
               $temp['name']       = 'Absents';
               $temp['amount']     = number_format($process['absent_deduction'], 2);
               $temp['sub']        = array();
               array_push($deduction, $temp);
          }  

          $temp = array();
          if($process['adjustment']['total_deductions'] > 0)
          {    
               foreach($process['adjustment']['deductions'] as $deductions) 
               {
                    $temp['name']       = $deductions->payroll_adjustment_name;
                    if($status == 'processed')
                    {
                         $temp['name'].=Self::btn_adjustment($deductions->payroll_adjustment_id);
                    }
                    $temp['amount']     = number_format($deductions->payroll_adjustment_amount, 2);

                    $temp['sub']        = array();
                    array_push($deduction, $temp);
               }    

               
          }  

          $temp = array();
          if(!empty($process['deduction']))
          {    
               $_collect = collect($process['deduction'])->groupBy('deduction_category');

               foreach($_collect as $collect)
               {
                    $_deduction = collect($collect)->sortBy('deduction_name');

                    $temp['name']       = '<b>'.$_deduction[0]['deduction_category'].'</b>';
                    $temp['amount']     = '';
                    $temp['sub']        = array();
                    
                    foreach($_deduction as $deductionlist)
                    {
                         $temp_sub['name']       = $deductionlist['deduction_name'];
                         $temp_sub['amount']     = number_format($deductionlist['payroll_periodal_deduction'], 2);
    
                         array_push($temp['sub'], $temp_sub);
                    }

                    array_push($deduction, $temp);
               }

               
          }  

          
          // dd($total_deduction);

          $temp = array();
          if($total_deduction > 0)
          {    
               $temp['name']       = '<b>Total Deduction</b>';
               $temp['amount']     = '<b>'.number_format($total_deduction, 2).'</b>';
               $temp['sub']        = array();
               array_push($deduction, $temp);
          }  

          $total_net = array();
          $temp = array();
          // if($process['total_net'] > 0)
          // {    
               $temp['name']       = '<b>Net Salary</b>';
               $temp['amount']     = '<b>'.number_format($process['total_net'], 2).'</b>';
               $temp['sub']        = array();
               array_push($total_net, $temp);
          // }  

          array_push($computation, $salary);
          array_push($computation, $government);
          array_push($computation, $deduction);
          array_push($computation, $total_net);

          // dd($process);

          /* TIME */
          $temp = array();
          $temp['name']     = 'Regular Hours';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['regular_hours']));
          array_push($time, $temp);
          $temp = array();
          $temp['name']     = 'Regular Overtime';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['late_overtime']));
          array_push($time, $temp);
          $temp = array();
          $temp['name']     = 'Early Overtime';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['early_overtime']));
          array_push($time, $temp);
          $temp = array();
          $temp['name']     = 'Night Differential Hour';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['night_differential']));
          array_push($time, $temp);
          $temp = array();
          $temp['name']     = 'Rest Day Hour';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['rest_day_hours']));
          array_push($time, $temp);
          $temp = array();
          $temp['name']     = 'Extra Day Hour';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['extra_day_hours']));
          array_push($time, $temp);
          $temp = array();
          $temp['name']     = 'Special Holiday Hour';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['special_holiday_hours']));
          array_push($time, $temp);
          $temp = array();
          $temp['name']     = 'Regular Holiday Hour';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['regular_holiday_hours']));
          array_push($time, $temp);
          $temp = array();
          $temp['name']     = '';
          $temp['time']     = '';
          array_push($time, $temp);

          $temp = array();
          $temp['name']     = 'Total Break';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['break_time']));
          array_push($time, $temp);

          $temp = array();
          $temp['name']     = 'Total Late';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['late_hours']));
          array_push($time, $temp);

          

          $temp = array();
          $temp['name']     = 'Total Under Time';
          $temp['time']     = Payroll::if_zero_time(Payroll::float_time($process['under_time_hours']));
          array_push($time, $temp);

          $temp = array();
          $temp['name']     = '';
          $temp['time']     = '';
          array_push($time, $temp);

          $temp = array();
          $temp['name']     = 'Total Hour';
          $temp['time']     = '<b>'.Payroll::if_zero_time(Payroll::float_time($process['total_hours'])).'</b>';
          array_push($time, $temp);


          /* DAY */
          $temp = array();
          $temp['name']    = 'Regular Days';
          $temp['day']     = Payroll::if_zero($process['total_regular_days']);
          array_push($day, $temp);

          $temp = array();
          $temp['name']    = 'Rest Days';
          $temp['day']     = Payroll::if_zero($process['total_rest_days']);
          array_push($day, $temp);

          $temp = array();
          $temp['name']    = 'Extra Days';
          $temp['day']     = Payroll::if_zero($process['total_extra_days']);
          array_push($day, $temp);

          $temp = array();
          $temp['name']    = 'Special Holidays';
          $temp['day']     = Payroll::if_zero($process['total_sh']);
          array_push($day, $temp);

          $temp = array();
          $temp['name']    = 'Regular Holidays';
          $temp['day']     = Payroll::if_zero($process['total_rh']);
          array_push($day, $temp);

          $temp = array();
          $temp['name']     = 'Absents';
          $temp['day']     = Payroll::if_zero($process['absent_count']);
          array_push($day, $temp);

          if(isset($process['leave_count_w_pay']))
          {
               $temp = array();
               $temp['name']     = 'Leave (w/ pay)';
               $temp['day']     = Payroll::if_zero($process['leave_count_w_pay']);
               array_push($day, $temp);
          }
          

          if(isset($process['leave_count_wo_pay']))
          {
               $temp = array();
               $temp['name']     = 'Leave (w/o pay)';
               $temp['day']     = Payroll::if_zero($process['leave_count_wo_pay']);
               array_push($day, $temp);
          }
          

          $temp = array();
          $temp['name']    = 'Total Working Days';
          $temp['day']     = '<b>'.Payroll::if_zero($process['total_worked_days']).'</b>';
          array_push($day, $temp);


          $data['computation'] = $computation;
          $data['time']        = $time;
          $data['day']         = $day;
          return $data;
     }

     public function btn_adjustment($adjustment_id = 0)
     {
          $btn = '<button class="btn btn-xs btn-custom-red popup pull-right" size="sm" link="/member/payroll/payroll_process/confirm_remove_adjustment/'.$adjustment_id.'" type="button"><i class="fa fa-times"></i></button>';
          return $btn;
     }

     public function confirm_remove_adjustment($adjustment_id)
     {
          $adjustment = Tbl_payroll_adjustment::where('payroll_adjustment_id', $adjustment_id)->first();

          $data['title']      = 'Do you really want to remove '.$adjustment->payroll_adjustment_name;
          $data['action']     = '/member/payroll/payroll_process/remove_adjustment';
          $data['id']         = $adjustment->payroll_adjustment_id;
          $data['html']       = '<input type="hidden" value="'.$adjustment->payroll_employee_id.'" name="payroll_employee_id"><input type="hidden" value="'.$adjustment->payroll_period_company_id.'" name="payroll_period_company_id">';

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function remove_adjustment()
     {
          $id                           = Request::input('id');
          $payroll_employee_id          = Request::input('payroll_employee_id');
          $payroll_period_company_id    = Request::input('payroll_period_company_id');

          $data['status']                    = 'success';
          $data['payroll_employee_id']       = $payroll_employee_id;
          $data['payroll_period_company_id'] = $payroll_period_company_id;
          $data['function_name']             = 'reload_break_down';
          // dd($data);
          $adjust = Tbl_payroll_adjustment::where('payroll_adjustment_id', $id)->first();
          Tbl_payroll_adjustment::where('payroll_adjustment_id', $id)->delete();
          AuditTrail::record_logs('DELETED: Payroll Adjustment', 'Payroll Adjustment Name: '.$adjust->payroll_adjustment_name, $id, "" ,"");
          
          return json_encode($data);

     }


     public function confirm_action_payroll($action,$id)
     {
          $period = Tbl_payroll_period_company::sel($id)->first();

          // dd($period);
          $statement = '';
          $link = '/member/payroll/payroll_process/action_payroll';
          switch ($action) {
               case 'registered':
                   $statement = 'register';
                    break;

               case 'pending':
                   $statement = 'unprocess';
                    break;

               case 'unregister':
                   $statement = 'unregister';
                   $action    = 'processed';
                    break;

               case 'posted':
                   $statement = 'post';
                    break;

               case 'unpost':
                   $statement = 'unpost';
                   $action    = 'registered';
                    break; 

               case 'approved':
                   $statement = 'approve';
                   $link = '/member/payroll/payroll_approved_view/approve_payroll';
                    break;   

               default:
                    $statement = '';
                    break;
          }

        
          $data['title']      = 'Do you really want to <span class="color-red">'.$statement.'</span> '.$period->payroll_company_name.' ('.date('M d Y', strtotime($period->payroll_period_start)).' to '.date('M d Y', strtotime($period->payroll_period_end)).')';
          $data['action']     = $link;
          $data['id']         = $period->payroll_period_company_id;
          $data['html']       = '<input type="hidden" value="'.$action.'" name="payroll_action">';

          return view('member.modal.modal_confirm_archived', $data);
     }

     

     public function action_payroll()
     {
          $id = Request::input('id');
          $payroll_action = Request::input('payroll_action');
          $update['payroll_period_status'] = $payroll_action;

          Tbl_payroll_period_company::where('payroll_period_company_id', $id)->update($update);
          AuditTrail::record_logs("UPDATED: Payroll Period Company","Payroll Period Company With ID #".$id." with action Value=".$payroll_action,$id,"","");
          

          $data['status'] = 'success';
          $data['function_name'] = 'reload_page';
          return json_encode($data);
     }

     /* PAYROLL PROCESS END */


     public function journal_entry()
     {
          // dd(Request::input());
          $data['date_start'] = date('m/d/Y', strtotime('first day of this month'));
          $data['date_end']   = date('m/d/Y', strtotime('last day of this month'));

          if(Request::input())
          {
               $data['date_start'] = Request::input('start');
               $data['date_end']   = Request::input('end');
          }
          
          $data['_record'] = PayrollJournalEntries::payroll_summary($data['date_start'], $data['date_end']);

          // dd(collect($record)->toArray());

          return view('member.payroll.payroll_journal_entries', $data);
     }


     /* PAYROLL REGISTERED START */
     public function payroll_register()
     {
          // breakdown_uncompute
          $data['_period'] = Payroll::process_compute(Self::shop_id(), 'registered');
          return view('member.payroll.payroll_register', $data);
     }

     public function breakdown_uncompute_static($employee_id, $payroll_period_company_id)
     {
          $period = Tbl_payroll_period_company::sel($payroll_period_company_id)->first();

          $process = Payroll::compute_per_employee($employee_id, $period->payroll_period_start, $period->payroll_period_end, Self::shop_id(), $period->payroll_period_category, $payroll_period_company_id);

          $data['emp'] = Tbl_payroll_employee_basic::where('payroll_employee_id',$employee_id)->first();

          $data['_breakdown'] = Self::breakdown_uncompute($process,'registered');
          $data['payroll_period_company_id'] = $payroll_period_company_id;
          $data['status'] = 'register';
          // dd($data);
          return view('member.payroll.modal.modal_view_payroll_computation_unsaved',$data);
     }
     /* PAYROLL REGISTERED END */


     /* PAYROLL POSTED START */
     public function payroll_post()
     {
          $data['_period'] = Payroll::process_compute(Self::shop_id(), 'posted');
          return view('member.payroll.payroll_post', $data);
     }
     /* PAYROLL POSTED END */


     /* PAYROLL ADJUSTMENT START */
     public function modal_create_payroll_adjustment($payroll_employee_id, $payroll_period_company_id)
     {
          $data['payroll_employee_id']  = $payroll_employee_id;
          $data['company_period']       = $payroll_period_company_id;
          return view('member.payroll.modal.modal_create_adjustment', $data);
     }

     public function create_payroll_adjustment()
     {
          // Tbl_payroll_adjustment
          $insert['payroll_employee_id']          = Request::input('payroll_employee_id');
          $insert['payroll_period_company_id']    = Request::input('company_period');
          $insert['payroll_adjustment_name']      = Request::input('payroll_adjustment_name');
          $insert['payroll_adjustment_amount']    = Request::input('payroll_adjustment_amount');
          $insert['payroll_adjustment_category']  = Request::input('payroll_adjustment_category');

          Tbl_payroll_adjustment::insert($insert);
          AuditTrail::record_logs("CREATED: Payroll Adjustment","Payroll Adjustment Name: ".Request::input('payroll_adjustment_name'),"","","");
          

          $data['status']                         = 'success';
          $data['payroll_employee_id']            = Request::input('payroll_employee_id');
          $data['payroll_period_company_id']      = Request::input('company_period');
          $data['function_name']                  = 'reload_break_down';

          return json_encode($data);
     }
     /* PAYROLL ADJUSTMENT END */


     /* PAYROLL APPROVED START */
     public function payroll_approved_view()
    {
          $data['_period'] = array();
          $_period = Tbl_payroll_period_company::period(Self::shop_id(), 'approved')->select('tbl_payroll_period.*')->distinct('payroll_period_id')->orderBy('payroll_period_start','desc')->get();

          foreach($_period as $period)
          {
               $temp['period']     = $period;
               $temp['_company']   = Tbl_payroll_period_company::selperiod($period->payroll_period_id)
                                                            ->where('tbl_payroll_period_company.payroll_period_status','approved')
                                                            ->orderBy('tbl_payroll_company.payroll_company_name')
                                                            ->get();
               array_push($data['_period'], $temp);
          }

          return view('member.payroll.payroll_approved', $data);
     }


     /* payslip start */
     public function genereate_payslip($id)
     {          
          $payslip  = Tbl_payroll_payslip::payslip(Self::shop_id())->first();
          if(empty($payslip))
          {
               $payslip  = Tbl_payroll_payslip::payslip(Self::shop_id(), 0)->first();
          }
          //dd($payslip);

          $data['logo_position']   = '';
          $data['logo']            = false;
          $data['colspan']         = 1;

          if($payslip->company_position == '.company-logo-center' || $payslip->company_position == '.company-center')
          {
               $data['logo_position'] = 'text-center';
          }

          if($payslip->company_position == '.company-logo-left' || $payslip->company_position == '.company-left')
          {
               $data['logo_position'] = 'text-left';
          }

          if($payslip->company_position == '.company-logo-right' || $payslip->company_position == '.company-right')
          {
               $data['logo_position'] = 'text-right';
          }

          if($payslip->company_position == '.company-logo-center' || $payslip->company_position == '.company-logo-left' || $payslip->company_position == '.company-logo-right')
          {
               $data['logo']          = true;
          }

          

          if($payslip->include_time_summary == 1)
          {
               $data['colspan']         = 2;
          }

          

          $data['payslip']  = $payslip;
          $data['_record']  = array();
          $period = Tbl_payroll_period_company::getcompanyperiod($id)->first();

          $_record = Tbl_payroll_record::getcompanyrecord($id)
                                        ->join('tbl_payroll_company','tbl_payroll_company.payroll_company_id','=','tbl_payroll_employee_basic.payroll_employee_company_id')
                                        ->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')
                                        ->get();
          //dd($_record);
          // dd($period);
          foreach($_record as $record)
          {

               $compute = Payroll::getrecord_breakdown($record);
               $temp['period'] = date('M d, Y', strtotime($period->payroll_period_start)).' to '.date('M d, Y', strtotime($period->payroll_period_end));
               $temp['break'] = Self::breakdown_uncompute($compute,'approved');
               $temp['display_name'] = $record->payroll_employee_display_name;
               $temp['company_name'] = $record->payroll_company_name;
               $temp['company_address'] = $record->payroll_company_address;
               $temp['company_logo'] = $record->payroll_company_logo;
               $temp['emp']   = Tbl_payroll_employee_contract::selemployee($record->payroll_employee_id, $period->payroll_period_start)
                                                            ->leftjoin('tbl_payroll_department','tbl_payroll_department.payroll_department_id','=','tbl_payroll_employee_contract.payroll_department_id')
                                                            ->leftjoin('tbl_payroll_jobtitle','tbl_payroll_jobtitle.payroll_jobtitle_id','=','tbl_payroll_employee_contract.payroll_jobtitle_id')
                                                            ->first();
               array_push($data['_record'], $temp);
          }

          //dd($data['_record']);
          //return view('member.payroll.payroll_payslip', $data);
          
          return view('member.payroll.payroll_payslipv1', $data);


          /*$page_width    = $data['payslip']->paper_size_width * 10;
          $page_height   = $data['payslip']->paper_size_height * 10; 

          $view = 'member.payroll.payroll_payslipv1';             
          $pdf = PDF::loadView($view, $data);
               $pdf->setOption('margin-right', 5);
               $pdf->setOption('margin-left', 5); 
               $pdf->setOption('margin-top', 5);
               $pdf->setOption('margin-bottom', 5);
               $pdf->setOption('page-width', $page_width);
               $pdf->setOption('page-height', $page_height);
          return $pdf->stream('Paycheque.pdf');
*/

          /*$view = 'member.payroll.payroll_payslipv1';             
          $pdf = PDF::loadView($view, $data);
               $pdf->setOption('margin-right',5);
               $pdf->setOption('margin-left',5);
          return $pdf->stream('Paycheque.pdf');*/


         /* $view = 'member.reports.'.$blade;             
          $pdf = PDF::loadView($view,$data);
          return $pdf->stream('Paycheque.pdf');*/
     }



     /* payslip end */
     public function approve_payroll()
     {
          $id       = Request::input('id');
          $period   = Tbl_payroll_period_company::getcompanyperiod($id)->first();
          $_data    = Payroll::company_period($period, Self::shop_id());

          foreach($_data['_list'] as $key => $data)
          {
               // dd($data);
               $payroll_record_id = Tbl_payroll_record::insertGetId(Self::filter_insert_record($data['compute']));

               $insert_deduction = array();
               $insert_allowance = array();
               foreach($data['compute']['allowance'] as $allowance)
               {
                    $temp_allowance['payroll_record_id']                   = $payroll_record_id;
                    $temp_allowance['payroll_employee_id']                 = $data['payroll_employee_id'];
                    $temp_allowance['payroll_allowance_id']                = $allowance['payroll_allowance_id'];
                    $temp_allowance['payroll_record_allowance_amount']     = $allowance['payroll_allowance_amount'];
                    $temp_allowance['payroll_allowance_name']              = $allowance['payroll_allowance_name'];
                    array_push($insert_allowance, $temp_allowance);
               }

               foreach($data['compute']['deduction'] as $deduction)
               {
                    $temp_deduction['payroll_deduction_id']      = $deduction['payroll_deduction_id'];
                    $temp_deduction['payroll_employee_id']       = $data['payroll_employee_id'];
                    $temp_deduction['payroll_record_id']         = $payroll_record_id;
                    $temp_deduction['payroll_payment_amount']    = $deduction['payroll_periodal_deduction'];
                    $temp_deduction['deduction_name']            = $deduction['deduction_name'];
                    $temp_deduction['deduction_category']        = $deduction['deduction_category'];
                    array_push($insert_deduction, $temp_deduction);
               }

               if(!empty($insert_allowance))
               {
                    Tbl_payroll_allowance_record::insert($insert_allowance);
               }

               if(!empty($insert_deduction))
               {
                    Tbl_payroll_deduction_payment::insert($insert_deduction);
               }
               
          }

          $update['payroll_period_status'] = 'approved';
          $pprc = Tbl_payroll_period_company::where('payroll_period_company_id', $id)->first();
          Tbl_payroll_period_company::where('payroll_period_company_id', $id)->update($update);
          AuditTrail::record_logs('EDITED: Payroll Period Company', 'Payroll Period Company ID : '.$pprc->payroll_company_id ." to Status Approved",$id, "" ,"");

          $return['status']        = 'success';
          $return['function_name'] = 'reload_page';

          return json_encode($return);

     }

     public function filter_insert_record($data = array())
     {
          // dd($data);
          $temp['shop_id']                        = Self::shop_id();
          $temp['payroll_employee_id']            = $data['payroll_employee_id'];
          $temp['payroll_period_company_id']      = $data['payroll_period_company_id'];
          $temp['salary_monthly']                 = $data['salary_monthly'];
          $temp['salary_daily']                   = $data['salary_daily'];
          $temp['regular_salary']                 = $data['regular_salary'];
          $temp['regular_early_overtime']         = $data['regular_early_overtime'];
          $temp['regular_reg_overtime']           = $data['regular_reg_overtime'];
          $temp['regular_night_diff']             = $data['regular_night_diff'];
          $temp['extra_salary']                   = $data['extra_salary'];
          $temp['extra_early_overtime']           = $data['extra_early_overtime'];
          $temp['extra_reg_overtime']             = $data['extra_reg_overtime'];
          $temp['extra_night_diff']               = $data['extra_night_diff'];
          $temp['rest_day_salary']                = $data['rest_day_salary'];
          $temp['rest_day_early_overtime']        = $data['rest_day_early_overtime'];
          $temp['rest_day_reg_overtime']          = $data['rest_day_reg_overtime'];
          $temp['rest_day_night_diff']            = $data['rest_day_night_diff'];
          $temp['rest_day_sh']                    = $data['rest_day_sh'];
          $temp['rest_day_sh_early_overtime']     = $data['rest_day_sh_early_overtime'];
          $temp['rest_day_sh_reg_overtime']       = $data['rest_day_sh_reg_overtime'];
          $temp['rest_day_sh_night_diff']         = $data['rest_day_sh_night_diff'];
          $temp['rest_day_rh']                    = $data['rest_day_rh'];
          $temp['rest_day_rh_early_overtime']     = $data['rest_day_rh_early_overtime'];
          $temp['rest_day_rh_reg_overtime']       = $data['rest_day_rh_reg_overtime'];
          $temp['rest_day_rh_night_diff']         = $data['rest_day_rh_night_diff'];
          $temp['rh_salary']                      = $data['rh_salary'];
          $temp['rh_early_overtime']              = $data['rh_early_overtime'];
          $temp['rh_reg_overtime']                = $data['rh_reg_overtime'];
          $temp['rh_night_diff']                  = $data['rh_night_diff'];
          $temp['sh_salary']                      = $data['sh_salary'];
          $temp['sh_early_overtime']              = $data['sh_early_overtime'];
          $temp['sh_reg_overtime']                = $data['sh_reg_overtime'];
          $temp['sh_night_diff']                  = $data['sh_night_diff'];
          $temp['13_month']                       = $data['13_month'];
          $temp['13_month_computed']              = $data['13_month_computed'];
          $temp['minimum_wage']                   = $data['minimum_wage'];
          $temp['tax_status']                     = $data['tax_status'];
          $temp['salary_taxable']                 = $data['salary_taxable'];
          $temp['salary_sss']                     = $data['salary_sss'];
          $temp['salary_pagibig']                 = $data['salary_pagibig'];
          $temp['salary_philhealth']              = $data['salary_philhealth'];
          $temp['tax_contribution']               = $data['tax_contribution'];
          $temp['sss_contribution_ee']            = $data['sss_contribution_ee'];
          $temp['sss_contribution_er']            = $data['sss_contribution_er'];
          $temp['sss_contribution_ec']            = $data['sss_contribution_ec'];
          $temp['philhealth_contribution_ee']     = $data['philhealth_contribution_ee'];
          $temp['philhealth_contribution_er']     = $data['philhealth_contribution_er'];
          $temp['pagibig_contribution']           = $data['pagibig_contribution'];
          $temp['late_deduction']                 = $data['late_deduction'];
          $temp['under_time']                     = $data['under_time'];
          $temp['agency_deduction']               = $data['agency_deduction'];
          $temp['payroll_cola']                   = $data['payroll_cola'];
          $temp['regular_hours']                  = $data['regular_hours'];
          $temp['late_overtime']                  = $data['late_overtime'];
          $temp['early_overtime']                 = $data['early_overtime'];
          $temp['late_hours']                     = $data['under_time_hours'];
          $temp['under_time_hours']               = $data['under_time_hours'];
          $temp['rest_day_hours']                 = $data['rest_day_hours'];
          $temp['extra_day_hours']                = $data['extra_day_hours'];
          $temp['total_hours']                    = $data['total_hours'];
          $temp['night_differential']             = $data['night_differential'];
          $temp['special_holiday_hours']          = $data['special_holiday_hours'];
          $temp['regular_holiday_hours']          = $data['regular_holiday_hours'];
          $temp['total_regular_days']             = $data['total_regular_days'];
          $temp['total_rest_days']                = $data['total_rest_days'];
          $temp['total_extra_days']               = $data['total_extra_days'];
          $temp['total_rh']                       = $data['total_rh'];
          $temp['total_sh']                       = $data['total_sh'];
          $temp['total_worked_days']              = $data['total_worked_days'];
          $temp['leave_amount']                   = $data['leave_amount'];
          $temp['absent_deduction']               = $data['absent_deduction'];
          $temp['absent_count']                   = $data['absent_count'];
          $temp['break_deduction']                = $data['break_deduction'];
          $temp['break_time']                     = $data['break_time'];
          $temp['branch_location_id']             = $data['branch_location_id'];

          if(!empty($data['13_month_id']))
          {
               $update_13['13_month_computed'] = 1;
               $id_now=1;
               Tbl_payroll_record::whereIn('payroll_record_id', $data['13_month_id'])->update($update_13);
               
          }

          return $temp;
     }

     public function payroll_approved_company($id)
     {
          $check = Tbl_payroll_period_company::sel($id)
                                             ->where('tbl_payroll_period.shop_id',Self::shop_id())
                                             ->where('tbl_payroll_period_company.payroll_period_status','approved')
                                             ->count();
          if($check == 0)
          {
               return Redirect::to('/member/payroll/payroll_approved_view')->send();
          }

          $data['period']          = Tbl_payroll_period_company::sel($id)->first();
          $data['_record']         = array();
          $data['total_gross']     = 0;
          $data['total_deduction'] = 0;
          $data['total_net']       = 0;

          $_record = Tbl_payroll_record::getcompanyrecord($id)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->get();
          foreach($_record as $record)
          {
               $compute = Payroll::getrecord_breakdown($record);
               array_push($data['_record'], $compute);
               // dd($record);
               $data['total_gross']     += $compute['total_gross'];
               $data['total_deduction'] += $compute['total_deduction'];
               $data['total_net']       += $compute['total_net'];
          }
          
          // dd($data);
          return view('member.payroll.payroll_approved_company', $data);
     }

     public function payroll_record_by_id($id)
     {
          $record = Tbl_payroll_record::getrecord($id)->first();
          // dd($record);
          $compute = Payroll::getrecord_breakdown($record);

          $data['emp'] = Tbl_payroll_employee_basic::where('payroll_employee_id',$record->payroll_employee_id)->first();

          $data['_breakdown'] = Self::breakdown_uncompute($compute,'approved');
          $data['payroll_period_company_id'] = $record->payroll_period_company_id;
          $data['status'] = 'approved';
          // dd($data);
          return view('member.payroll.modal.modal_view_payroll_computation_unsaved',$data);
     }
     /* PAYRLL APPROVED END */

     public function modal_payroll_notes($payroll_period_company_id)
     {
          
          return view('member.payroll.modal.modal_payroll_notes');
     }


     /* PAYROLL REPORTS START */
     public function payroll_reports()
     {
          $data['_active'] = Tbl_payroll_reports::getdata(Self::shop_id())->orderBy('payroll_reports_name')->get();
          $data['_archived'] = Tbl_payroll_reports::getdata(Self::shop_id(), 1)->orderBy('payroll_reports_name')->get();
          return view('member.payroll.payroll_reports', $data);
     }

     public function modal_create_reports()
     {
          $data['_entity']  = Self::setentity();
          return view('member.payroll.modal.modal_create_reports', $data);
     }

     public function save_custom_reports()
     {
          $insert['shop_id']              = Self::shop_id();
          $insert['payroll_reports_name'] = Request::input("payroll_reports_name");

          $is_by_company                = 0;
          $is_by_department             = 0;
          $is_by_employee               = 0;

          if(Request::has('is_by_company'))
          {
               $is_by_company = Request::input('is_by_company');
          }

          if(Request::has('is_by_department'))
          {
               $is_by_department = Request::input('is_by_department');
          }

          if(Request::has('is_by_employee'))
          {
               $is_by_employee = Request::input('is_by_employee');
          }

          $insert['is_by_company']      = $is_by_company;
          $insert['is_by_department']   = $is_by_department;
          $insert['is_by_employee']     = $is_by_employee;
          $payroll_reports_id =  Tbl_payroll_reports::insertGetId($insert);
          AuditTrail::record_logs('CREATED: Payroll Reports', 'Payroll Reports Name: '.Request::input("payroll_reports_name"),"", "" ,"");
          


          $_entity = array();
          $_sub_entity = array();

          if(Request::has('entity'))
          {
               $_entity = Request::input('entity');
          }
          if(Request::has('sub_entity'))
          {
               $_sub_entity = Request::input('sub_entity');
          }

          $insert_column = array();

          /* for default entity tag */
          foreach($_entity as $entity)
          {
               $temp['payroll_reports_id']   = $payroll_reports_id;
               $temp['column_entity_id']     = $entity;
               $temp['column_origin']        = 'payroll entity';
               array_push($insert_column, $temp);
          }    

          /* for dynamic loan */
          foreach($_sub_entity as $entity)
          {
               $temp['payroll_reports_id']   = $payroll_reports_id;
               $temp['column_entity_id']     = $entity;
               $temp['column_origin']        = 'loan';
               array_push($insert_column, $temp);
          }    


          if(!empty($insert_column))
          {
               Tbl_payroll_reports_column::insert($insert_column);
          }


          $return['status'] = 'success';
          $return['function_name'] = 'payroll_reports.reload_data';

          return collect($return)->toJson();
     }


     public function modal_archive_reports($archived, $id)
     {
          $statement = 'archive';
          if($archived == 0)
          {
               $statement = 'restore';
          }

          $payroll_reports_name = Tbl_payroll_reports::where('payroll_reports_id', $id)->value('payroll_reports_name');

          $data['title']      = 'Do you really want to '.$statement.' '.$payroll_reports_name.'?';
          $data['html']       = '';
          $data['action']     = '/member/payroll/payroll_reports/archive_report';
          $data['id']         = $id;
          $data['archived']   = $archived;

          return view('member.modal.modal_confirm_archived', $data);
     }

     public function archive_report()
     {
          $id = Request::input('id');
          $update['payroll_reports_archived'] = Request::input('archived');
          $reports = Tbl_payroll_reports::where('payroll_reports_id', $id)->first();
          Tbl_payroll_reports::where('payroll_reports_id', $id)->update($update);
          AuditTrail::record_logs("DELETED: Payroll Reports","Payroll Reports Name: ".$reports->payroll_reports_name,$id,"","");
          

          $return['status'] = 'success';
          $return['function_name'] = 'payroll_reports.reload_data';

          return collect($return)->toJson();
     }

     public function modal_edit_payroll_reports($id)
     {

          $data['_entity'] = array();
          $data['report'] = Tbl_payroll_reports::where('payroll_reports_id', $id)->first();
          $_entity  = collect(Tbl_payroll_entity::orderBy('entity_name')->get()->toArray())->groupBy('entity_category');


          foreach($_entity as $key => $data_entity)
          {
               $data['_entity'][$key] = array();
               foreach($data_entity as $entity)
               {
                    $temp = $entity;
                    $temp['status'] = '';

                    $count_entity = Tbl_payroll_reports_column::getcolumn($id, $entity['payroll_entity_id'])->count();

                    if($count_entity == 1)
                    {
                         $temp['status'] = 'checked="checked"';
                    }

                    $temp['sub'] = array();

                    if($entity['entity_name'] == 'Loans')
                    {
                         $_sub = Tbl_payroll_deduction_type::seltype(Self::shop_id(), 'Loans')->get()->toArray();
                         foreach($_sub as $sub)
                         {
                              $temp_column = $sub;
                              $temp_column['status'] = '';

                              $count_column = Tbl_payroll_reports_column::getcolumn($id, $sub['payroll_deduction_type_id'], 'loan')->count();

                              if($count_column == 1)
                              {
                                   $temp_column['status'] = 'checked="checked"';
                              }

                              array_push($temp['sub'], $temp_column);
                         }
                    }

                    array_push($data['_entity'][$key], $temp);
               }
          }

          // dd($data);
          return view('member.payroll.modal.modal_edit_payroll_reports', $data);
     }

     public function update_payroll_reports()
     {

          $is_by_company      = 0;
          $is_by_department   = 0;
          $is_by_employee     = 0;

          if(Request::has('is_by_company'))
          {
               $is_by_company      = Request::input('is_by_company');
          }

          if(Request::has('is_by_department'))
          {
               $is_by_department   = Request::input('is_by_department');
          }

          if(Request::has('is_by_employee'))
          {
               $is_by_employee     = Request::input('is_by_employee');
          }

          $payroll_reports_id = Request::input('payroll_reports_id');

          $update['payroll_reports_name'] = Request::input('payroll_reports_name');
          $update['is_by_company'] = $is_by_company;
          $update['is_by_department'] = $is_by_department;
          $update['is_by_employee'] = $is_by_employee;
          $reports = Tbl_payroll_reports::where('payroll_reports_id', $payroll_reports_id)->first();
          Tbl_payroll_reports::where('payroll_reports_id', $payroll_reports_id)->update($update);
          AuditTrail::record_logs("EDITED: Payroll Reports","Payroll Report Name: ".$reports->payroll_reports_name." to ".Request::input('payroll_reports_name'),$payroll_reports_id,"","");

          Tbl_payroll_reports_column::where('payroll_reports_id', $payroll_reports_id)->delete();
          $_entity = array();
          $_sub_entity = array();

          if(Request::has('entity'))
          {
               $_entity = Request::input('entity');
          }
          if(Request::has('sub_entity'))
          {
               $_sub_entity = Request::input('sub_entity');
          }

          $insert_column = array();

          /* for default entity tag */
          foreach($_entity as $entity)
          {
               $temp['payroll_reports_id']   = $payroll_reports_id;
               $temp['column_entity_id']     = $entity;
               $temp['column_origin']        = 'payroll entity';
               array_push($insert_column, $temp);
          }    

          /* for dynamic loan */
          foreach($_sub_entity as $entity)
          {
               $temp['payroll_reports_id']   = $payroll_reports_id;
               $temp['column_entity_id']     = $entity;
               $temp['column_origin']        = 'loan';
               array_push($insert_column, $temp);
          }    


          if(!empty($insert_column))
          {
               Tbl_payroll_reports_column::insert($insert_column);
          }


          $return['status'] = 'success';
          $return['function_name'] = 'payroll_reports.reload_data';

          return collect($return)->toJson();
     }

     public function view_report($id)
     {
          
          $date[0] = date('Y-m-d');
          $date[1] = date('Y-m-d');

          $data = Self::generate_custom_report($id, $date);

          // dd($data);
          return view('member.payroll.payroll_view_report', $data);
     }


     public function date_change_report()
     {
          $date[0]            = datepicker_input(Request::input('start'));
          $date[1]            = datepicker_input(Request::input('end'));
          $payroll_reports_id = Request::input('payroll_reports_id');

          $data = Self::generate_custom_report($payroll_reports_id, $date);

          return view('member.payroll.reload.reload_report_table', $data);
     }

     public function download_excel_report()
     {
          $payroll_reports_id = Request::input('payroll_reports_id');
          $date[0]            = datepicker_input(Request::input('start'));
          $date[1]            = datepicker_input(Request::input('end'));

          $record     = Self::generate_custom_report($payroll_reports_id, $date);

          $emp = $record['data']['_emp'];
          $columns = $record['data']['_columns'];

          // dd($record);
          $data = array();

          $columnn_array = array();
          array_push($columnn_array, '');
          foreach($columns as $column)
          {
               array_push($columnn_array, $column);
          }

          array_push($data, $columnn_array);

          foreach($record['data']['_emp'] as $emp)
          {
               $temp = array();
               array_push($temp, $emp['raw_name']);

               foreach($emp['_raw'] as $raw)
               {
                    $raw = n2z($raw);
                    if(!is_string($raw))
                    {
                         $raw = round($raw, 2);
                         // if($raw == 0)
                         // {
                         //      $raw = number_format($raw, 2);
                         // }
                    }

                    array_push($temp, $raw);
               }
               array_push($data, $temp);
          }

          $total_array = array();
          array_push($total_array, 'Total');

          foreach($record['data']['_total'] as $total)
          {
               $total = round(n2z($total), 2);
               array_push($total_array, round(n2z($total), 2));
          }
          array_push($data, $total_array);

      
          $title    = Tbl_payroll_reports::where('payroll_reports_id', $payroll_reports_id)->value('payroll_reports_name');


          $data_export['data'] = $data;
          $data_export['header'] = $record['header'];
          AuditTrail::record_logs("DOWNLOADED","EXCEL  REPORT",$this->shop_id(),"","");
          return Excel::create($title, function($excel) use ($data_export) {

               $date = 'reports';
               $excel->setTitle('Payroll');
               $excel->setCreator('Laravel')->setCompany('DIGIMA');
               $excel->setDescription('payroll file');

               $excel->sheet($date, function($sheet) use ($data_export) {

                    /* column in excel */
                    $columns = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI'];

                    $new_range = 0;
                    $old_range = 1;

                    $column_range = array();
                    foreach($data_export['header'] as $header)
                    {
                         // name
                         // count
                         if($new_range > 0)
                         {
                              $old_range = $new_range;
                              $old_range++;
                         }
                        

                         $new_range += $header['count'];
                         $name = $header['name'];
                         $sheet->mergeCells($columns[$old_range - 1].'1:'.$columns[$new_range - 1].'1', function($cell) use($name){
                              $cells->setValue($name);
                         });
                         // if($header['count'] > 1)
                         // {
                         //      // $sheet->mergeCells($columns[$old_range - 1].'1:'.$columns[$new_range - 1].'1');
                         // }

                         // array_push($column_range, $columns[$old_range - 1].'1:'.$columns[$new_range - 1].'1');
                         // dd($columns[$old_range - 1]);
                         
                         // $sheet->cells($columns[$old_range - 1].'1:'.$columns[$new_range - 1].'1', function($cells) use ($name) {
                         // //     // manipulate the range of cells
                         //      $cells->setValue($name);

                         // });
                    }

                    // dd($column_range);
                    

                    $sheet->fromArray($data_export['data'], null, 'A2', true, false);
                    $sheet->setColumnFormat(array(
                         'B4:'.$columns[count($data_export['data'][1]) - 1].(count($data_export['data']) + 1) => '#,##0.00',
                         ));
               });

          })->download('xlsx');

     }

     public function generate_custom_report($id, $date = array())
     {
          $report             = Tbl_payroll_reports::where('payroll_reports_id', $id)->first();
          $data['report']     = $report;
          $columns            = array();
          $items              = array();
          $column_space       = array();
          

          $_entity = collect(Tbl_payroll_entity::orderBy('entity_category')->orderBy('entity_name')->get()->toArray())->groupBy('entity_category');

          $group = ['','Basic','Deductions','Deminimis','Goverment',''];

          $header  = array();

          $temp_header['name']     = '';
          $temp_header['count']    = 1;

          array_push($header, $temp_header);

          /* for column start */
          foreach($_entity as $key => $data_entity)
          {
               
               foreach($data_entity as $entity)
               {

                    $count_entity = Tbl_payroll_reports_column::getcolumn($id, $entity['payroll_entity_id'])->count();

                    if($count_entity == 1)
                    {
                         array_push($columns, $entity['entity_name']);
                         array_push($column_space, '');

                         /* check if column header exists */
                         $count_exist = 0;
                         $key_exist = 0;
                         foreach($header as $key => $head)
                         {
                              if($head['name'] == ucfirst($entity['entity_category']))
                              {
                                   $count_exist++;
                                   $key_exist = $key;
                              }
                         }
                         if($count_exist > 0)
                         {
                              $header[$key_exist]['count']++;
                         }
                         else
                         {
                              $temp_header['name']     = ucfirst($entity['entity_category']);;
                              $temp_header['count']    = 1;
                              array_push($header, $temp_header);
                         }

                    }


                    if($entity['entity_name'] == 'Loans')
                    {
                         $_sub = Tbl_payroll_deduction_type::seltype(Self::shop_id(), 'Loans')->get()->toArray();
                         foreach($_sub as $sub)
                         {

                              $count_column = Tbl_payroll_reports_column::getcolumn($id, $sub['payroll_deduction_type_id'], 'loan')->count();

                              if($count_column == 1)
                              {
                                   array_push($columns, $sub['payroll_deduction_type_name']);
                                   array_push($column_space, '');
                              }
                         }
                    }

               }
          }


          /* for column end */

          $date[0] = date_create($date[0]);
          // $date[1] = date('Y-m-d');

          date_sub($date[0],date_interval_create_from_date_string("40 days"));

          $date[0] = date_format($date[0],'Y-m-d');

          $data['start'] = date('m/d/Y', strtotime($date[0]));
          $data['end']   = date('m/d/Y', strtotime($date[1]));

          $data['_emp']  = array();
          $emp_array     = array();

          /* for item start */
          $_emp = Tbl_payroll_employee_contract::employeefilter(0,0,0,$date[0], Self::shop_id())->get()->toArray();
          // dd($_emp);

          $_total = array();


          foreach($_emp as $emp)
          {
               $_record = Self::getrecord_report($id, $_entity, $emp['payroll_employee_id'], $date);

               $temp_emp['name'] = $emp['payroll_employee_title_name'].' '.$emp['payroll_employee_first_name'].' '.$emp['payroll_employee_middle_name'].' '.$emp['payroll_employee_last_name'].' '.$emp['payroll_employee_suffix_name'];
               $temp_emp['raw_name'] = $emp['payroll_employee_title_name'].' '.$emp['payroll_employee_first_name'].' '.$emp['payroll_employee_middle_name'].' '.$emp['payroll_employee_last_name'].' '.$emp['payroll_employee_suffix_name'];

               $temp_emp['payroll_department_name'] = $emp['payroll_department_name'];
               $temp_emp['payroll_company_name']    = $emp['payroll_company_name'];

               $temp_emp['_record'] = array();
               $temp_emp['_raw']    = $_record;

               if(empty($_total))
               {
                    $_total = $_record;
               }
               else
               {
                    foreach($_record as $key => $total)
                    {
                         $_total[$key] += $total;
                    }
               }

               foreach($_record as $record)
               {
                    array_push($temp_emp['_record'], number_format($record, 2));

               }

               array_push($emp_array, $temp_emp);
          }   

          $is_by_employee     = $report->is_by_employee;
          $is_by_department   = $report->is_by_department;
          $is_by_company      = $report->is_by_company;

          // $column_space
          if($is_by_employee == 1 && $is_by_department == 1 && $is_by_company == 1)
          {
               $_comp_array   = collect($emp_array)->groupBy('payroll_company_name');
               $temp_data     = array();
               $temp_item     = array();

               foreach($_comp_array as $key => $comp)
               {
                    $temp_item['name'] = '<b>'.$key.'</b>';
                    $temp_item['raw_name'] = $key;
                    $temp_item['payroll_department_name'] = '';
                    $temp_item['payroll_company_name']    = '';
                    $temp_item['_record'] = $column_space;
                    $temp_item['_raw']    = $column_space;

                    array_push($temp_data, $temp_item);

                    $_department = collect($comp)->groupBy('payroll_department_name');
                    foreach($_department as $kd => $_dep)
                    {
                         $temp_item['name'] = '<b>'.$kd.'</b>';
                         $temp_item['raw_name'] = $kd;
                         $temp_item['payroll_department_name'] = '';
                         $temp_item['payroll_company_name']    = '';
                         $temp_item['_record'] = $column_space;
                         $temp_item['_raw']    = $column_space;
                         array_push($temp_data, $temp_item);
                         foreach($_dep as $department)
                         {
                              array_push($temp_data, $department);
                              
                         }
                         // dd(Self::getsubtotal_report($_dep));
                         // array_push($temp_data, Self::getsubtotal_report($_dep));
                    }
                    
               }

               $data['_emp'] = $temp_data;
          }

          if($is_by_employee == 0 && $is_by_department == 1 && $is_by_company == 1)
          {

               $_comp_array   = collect($emp_array)->groupBy('payroll_company_name');
               $temp_data     = array();
               $temp_item     = array();

               foreach($_comp_array as $key => $comp)
               {
                    $temp_item['name'] = '<b>'.$key.'</b>';
                    $temp_item['raw_name'] = $key;
                    $temp_item['payroll_department_name'] = '';
                    $temp_item['payroll_company_name']    = '';
                    $temp_item['_record'] = $column_space;
                    $temp_item['_raw']    = $column_space;

                    array_push($temp_data, $temp_item);

                    $_department = collect($comp)->groupBy('payroll_department_name');
                    foreach($_department as $kd => $_dep)
                    {
                         $temp_item['name'] = $kd;
                         $temp_item['raw_name'] = $kd;
                         $temp_item['payroll_department_name'] = '';
                         $temp_item['payroll_company_name']    = '';
                         $temp_item['_record'] = array();
                         $temp_item['_raw']    = array();
                         
                         foreach($_dep as $department)
                         {
                             if(empty($temp_item['_raw']))
                             {
                                   $temp_item['_raw'] = $department['_raw'];
                             }
                             else
                             {
                                   foreach($department['_raw'] as $key => $raw)
                                   {
                                        $temp_item['_raw'][$key] += $raw;
                                   }
                             }
                         }

                         foreach($temp_item['_raw'] as $key => $raw)
                         {
                              array_push($temp_item['_record'], number_format($raw, 2));
                         }

                         array_push($temp_data, $temp_item);
                    }
               }

               $data['_emp'] = $temp_data;
          }

          if($is_by_employee == 0 && $is_by_department == 1 && $is_by_company == 0)
          {
               $_department = collect($emp_array)->groupBy('payroll_department_name');
               $temp_data   = array();
               foreach($_department as $kd => $_dep)
               {
                    $temp_item['name'] = $kd;
                    $temp_item['raw_name'] = $kd;
                    $temp_item['payroll_department_name'] = '';
                    $temp_item['payroll_company_name']    = '';
                    $temp_item['_record'] = array();
                    $temp_item['_raw']    = array();
                    
                    foreach($_dep as $department)
                    {
                        if(empty($temp_item['_raw']))
                        {
                              $temp_item['_raw'] = $department['_raw'];
                        }
                        else
                        {
                              foreach($department['_raw'] as $key => $raw)
                              {
                                   $temp_item['_raw'][$key] += $raw;
                              }
                        }
                    }

                    foreach($temp_item['_raw'] as $key => $raw)
                    {
                         array_push($temp_item['_record'], number_format($raw, 2));
                    }

                    array_push($temp_data, $temp_item);
               }
               $data['_emp'] = $temp_data;
          }

          if($is_by_employee == 0 && $is_by_department == 0 && $is_by_company == 1)
          {
               $_department = collect($emp_array)->groupBy('payroll_company_name');
               $temp_data   = array();
               foreach($_department as $kd => $_dep)
               {
                    $temp_item['name'] = $kd;
                    $temp_item['raw_name'] = $kd;
                    $temp_item['payroll_department_name'] = '';
                    $temp_item['payroll_company_name']    = '';
                    $temp_item['_record'] = array();
                    $temp_item['_raw']    = array();
                    
                    foreach($_dep as $department)
                    {
                        if(empty($temp_item['_raw']))
                        {
                              $temp_item['_raw'] = $department['_raw'];
                        }
                        else
                        {
                              foreach($department['_raw'] as $key => $raw)
                              {
                                   $temp_item['_raw'][$key] += $raw;
                              }
                        }
                    }

                    foreach($temp_item['_raw'] as $key => $raw)
                    {
                         array_push($temp_item['_record'], number_format($raw, 2));
                    }

                    array_push($temp_data, $temp_item);
               }
               $data['_emp'] = $temp_data;
          }


          if($is_by_employee == 1 && $is_by_department == 1 && $is_by_company == 0)
          {

               $_department   = collect($emp_array)->groupBy('payroll_department_name');

               $temp_data     = array();

               foreach($_department as $kd => $_dep)
               {
                    $temp_item['name'] = '<b>'.$kd.'</b>';
                    $temp_item['raw_name'] = $kd;
                    $temp_item['payroll_department_name'] = '';
                    $temp_item['payroll_company_name']    = '';
                    $temp_item['_record'] = $column_space;
                    $temp_item['_raw']    = $column_space;
                    array_push($temp_data, $temp_item);
                    foreach($_dep as $department)
                    {
                         array_push($temp_data, $department);
                         
                    }
                    // dd(Self::getsubtotal_report($_dep));
                    // array_push($temp_data, Self::getsubtotal_report($_dep));
               }
                    

               $data['_emp'] = $temp_data;
          }

          if($is_by_employee == 1 && $is_by_department == 0 && $is_by_company == 1)
          {
               $_department   = collect($emp_array)->groupBy('payroll_company_name');

               $temp_data     = array();

               foreach($_department as $kd => $_dep)
               {
                    $temp_item['name'] = '<b>'.$kd.'</b>';
                    $temp_item['raw_name'] = $kd;
                    $temp_item['payroll_department_name'] = '';
                    $temp_item['payroll_company_name']    = '';
                    $temp_item['_record'] = $column_space;
                    $temp_item['_raw']    = $column_space;
                    array_push($temp_data, $temp_item);
                    foreach($_dep as $department)
                    {
                         array_push($temp_data, $department);
                         
                    }
                    // dd(Self::getsubtotal_report($_dep));
                    // array_push($temp_data, Self::getsubtotal_report($_dep));
               }
                    

               $data['_emp'] = $temp_data;
          }

          if($is_by_employee == 1 && $is_by_department == 0 && $is_by_company == 0)
          {
               $data['_emp'] = $emp_array;
          }

          // dd($data['_emp']);

          /* for item end */

          $data['_total']     = $_total;
          $data['_columns']   = $columns;

          $return['data']     = $data;
          $return['header']   = $header;

          return $return;
     }

     public function getsubtotal_report($_item = array())
     {

          $data['name']                      = '<b>sub total</b>';
          $data['raw_name']                  = 'sub total';
          $data['payroll_department_name']   = '';
          $data['payroll_company_name']      = '';
          $data['_record']    = array();
          $data['_raw']       = array();

          $temp_array         = array();
          $temp_record        = array();

          // dd($_item);

          foreach($_item as $data_item)
          {    

               if(empty($temp_array))
               {
                    $temp_array = $data_item['_raw'];
               }
               else
               {
                    // dd($data_item);
                    foreach($temp_array as $key => $amount)
                    {
                         $temp_array[$key] += $amount;
                    }
               }
          }

          foreach($temp_array as $key => $arr)
          {
               array_push($temp_record, '<b>'.number_format($arr, 2).'</b>');
          }

          $data['_record'] = $temp_record;
          $data['_raw']    = $temp_array;


          return $data;
     }

     public function getrecord_report($id = 0,$_entity = array(), $employee_id = 0, $date = ['0000-00-00', '0000-00-00'])
     {

          $data = array();

          foreach($_entity as $key => $data_entity)
          {
               
               foreach($data_entity as $entity)
               {

                    $count_entity = Tbl_payroll_reports_column::getcolumn($id, $entity['payroll_entity_id'])->count();

                    $query = Tbl_payroll_record::getdate(Self::shop_id(), $date)->where('tbl_payroll_record.payroll_employee_id', $employee_id);


                    $temp_query = $query;
                    $payroll_record_id_list = $temp_query->pluck('payroll_record_id');
                    $temp_query = $query;
                    $payroll_period_company_id_list = $temp_query->select('tbl_payroll_record.payroll_period_company_id as company_record_id')->pluck('company_record_id');
                    
                    // dd($payroll_period_company_id_list);
                    $positive_value = 0;
                    $negative_value = 0;

                    if($count_entity == 1)
                    {
                         if($entity['entity_name'] == '13 Month Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('13_month')->sum('13_month');

                              $amount += Tbl_payroll_adjustment::getrecord($employee_id, $payroll_period_company_id_list, '13 month pay')->select('payroll_adjustment_amount')->sum('payroll_adjustment_amount');
                              
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Basic Salary Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('regular_salary')->sum('regular_salary');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Early Over Time Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('regular_early_overtime')->sum('regular_early_overtime');
                              $amount += $temp_query->select('extra_early_overtime')->sum('extra_early_overtime');
                              $amount += $temp_query->select('rest_day_rh_early_overtime')->sum('rest_day_rh_early_overtime');
                              $amount += $temp_query->select('sh_early_overtime')->sum('rest_day_sh_early_overtime');
                              $amount += $temp_query->select('rh_early_overtime')->sum('rh_early_overtime');
                              $amount += $temp_query->select('sh_early_overtime')->sum('sh_early_overtime');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Extra Day Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('extra_salary')->sum('extra_salary');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Leave With Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('leave_amount')->sum('leave_amount');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Night Differential Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('regular_night_diff')->sum('regular_night_diff');
                              $amount += $temp_query->select('extra_night_diff')->sum('extra_night_diff');
                              $amount += $temp_query->select('rest_day_night_diff')->sum('rest_day_night_diff');
                              $amount += $temp_query->select('rest_day_sh_night_diff')->sum('rest_day_sh_night_diff');
                              $amount += $temp_query->select('rest_day_rh_night_diff')->sum('rest_day_rh_night_diff');
                              $amount += $temp_query->select('sh_night_diff')->sum('sh_night_diff');
                              $amount += $temp_query->select('rh_early_overtime')->sum('rh_early_overtime');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Regular Holiday Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('rh_salary')->sum('rh_salary');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Regular Over Time Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('regular_reg_overtime')->sum('regular_reg_overtime');
                              $amount += $temp_query->select('extra_reg_overtime')->sum('extra_reg_overtime');
                              $amount += $temp_query->select('rest_day_reg_overtime')->sum('rest_day_reg_overtime');
                              $amount += $temp_query->select('rest_day_sh_reg_overtime')->sum('rest_day_sh_reg_overtime');
                              $amount += $temp_query->select('rest_day_rh_reg_overtime')->sum('rest_day_rh_reg_overtime');
                              $amount += $temp_query->select('rh_reg_overtime')->sum('rh_reg_overtime');
                              $amount += $temp_query->select('sh_reg_overtime')->sum('sh_reg_overtime');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Rest Day Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('rest_day_salary')->sum('rest_day_salary');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'COLA')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('payroll_cola')->sum('payroll_cola');
                              $positive_value += $amount;
                              array_push($data, $amount);
                              // array_push($data, '150');
                         }

                         if($entity['entity_name'] == 'Special Holiday Pay')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('sh_salary')->sum('sh_salary');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Allowance Pay')
                         {
                              $amount = Tbl_payroll_allowance_record::getbyrecord($payroll_record_id_list)->select('payroll_record_allowance_amount')->sum('payroll_record_allowance_amount');

                              $amount += Tbl_payroll_adjustment::getrecord($employee_id, $payroll_period_company_id_list, 'Allowance')->select('payroll_adjustment_amount')->sum('payroll_adjustment_amount');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Bonus Pay')
                         {
                              $amount = Tbl_payroll_adjustment::getrecord($employee_id, $payroll_period_company_id_list, 'Bonus')->select('payroll_adjustment_amount')->sum('payroll_adjustment_amount');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Commission Pay')
                         {
                              $amount = Tbl_payroll_adjustment::getrecord($employee_id, $payroll_period_company_id_list, 'Commissions')->select('payroll_adjustment_amount')->sum('payroll_adjustment_amount');
                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Incentive Pay')
                         {
                              $amount = Tbl_payroll_adjustment::getrecord($employee_id, $payroll_period_company_id_list, 'Incentives')->select('payroll_adjustment_amount')->sum('payroll_adjustment_amount');

                              $positive_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Pagibig')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('pagibig_contribution')->sum('pagibig_contribution');
                              $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Philhealth EE')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('philhealth_contribution_ee')->sum('philhealth_contribution_ee');
                              $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Philhealth ER')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('philhealth_contribution_er')->sum('philhealth_contribution_er');
                              // $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'SSS EC')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('sss_contribution_ec')->sum('sss_contribution_ec');
                              // $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'SSS EE')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('sss_contribution_ee')->sum('sss_contribution_ee');
                              $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'SSS ER')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('sss_contribution_er')->sum('sss_contribution_er');

                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Tax')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('tax_contribution')->sum('tax_contribution');
                              $negative_value += $amount;
                              array_push($data, $amount);
                              // array_push($data, 500);
                              // dd($amount);
                         }

                         if($entity['entity_name'] == 'Cash Advance')
                         {
                              $amount = Tbl_payroll_deduction_payment::getrecord($payroll_record_id_list , 'Cash Advance')->select('tbl_payroll_deduction_payment.payroll_payment_amount')->sum('tbl_payroll_deduction_payment.payroll_payment_amount');
                              $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Late')
                         {
                              $amount = $temp_query->select('late_deduction')->sum('late_deduction');
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Cash Bond')
                         {
                              $amount = Tbl_payroll_deduction_payment::getrecord($payroll_record_id_list , 'Cash Bond')->select('tbl_payroll_deduction_payment.payroll_payment_amount')->sum('tbl_payroll_deduction_payment.payroll_payment_amount');
                              $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Loans')
                         {
                              $amount = Tbl_payroll_deduction_payment::getrecord($payroll_record_id_list , 'Loans')->select('tbl_payroll_deduction_payment.payroll_payment_amount')->sum('tbl_payroll_deduction_payment.payroll_payment_amount');
                              $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Other Deduction')
                         {
                              $amount = Tbl_payroll_adjustment::getrecord($employee_id, $payroll_period_company_id_list, 'Deductions')->select('payroll_adjustment_amount')->sum('payroll_adjustment_amount');

                              $amount += Tbl_payroll_deduction_payment::getrecord($payroll_record_id_list , 'Other Deduction')->select('tbl_payroll_deduction_payment.payroll_payment_amount')->sum('tbl_payroll_deduction_payment.payroll_payment_amount');
                              $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         
                         if($entity['entity_name'] == 'Absent')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('absent_deduction')->sum('absent_deduction');
                              $negative_value += $amount;
                              array_push($data, $amount);
                         }

                         if($entity['entity_name'] == 'Under Time')
                         {
                              $temp_query = $query;
                              $amount = $temp_query->select('under_time')->sum('under_time');
                              $negative_value += $amount;
                              array_push($data, $amount);
                         }

                    }


                    if($entity['entity_name'] == 'Loans')
                    {
                         $_sub = Tbl_payroll_deduction_type::seltype(Self::shop_id(), 'Loans')->get()->toArray();
                         foreach($_sub as $sub)
                         {

                              $count_column = Tbl_payroll_reports_column::getcolumn($id, $sub['payroll_deduction_type_id'], 'loan')->count();

                              if($count_column == 1)
                              {

                                   $payroll_deduction_type_id = $sub['payroll_deduction_type_id'];
                                   $amount = Tbl_payroll_deduction_payment::getpayment($employee_id, $payroll_record_id_list, $payroll_deduction_type_id)->select('payroll_payment_amount')->sum('payroll_payment_amount');
                                   $negative_value += $amount;
                                   array_push($data, $amount);
                              }
                         }
                    }

                    $net = $positive_value - $negative_value;
               }
          }


          return $data;
     }

     public function setentity()
     {
          $_entity  = collect(Tbl_payroll_entity::orderBy('entity_name')->get()->toArray())->groupBy('entity_category');

          $data = array();

          foreach($_entity as $key => $data_entity)
          {
               $data[$key] = array();
               foreach($data_entity as $entity)
               {
                    $temp = $entity;
                    $temp['sub'] = array();

                    if($entity['entity_name'] == 'Loans')
                    {
                         $temp['sub'] = Tbl_payroll_deduction_type::seltype(Self::shop_id(), 'Loans')->get()->toArray();
                    }

                    array_push($data[$key], $temp);
               }
          }

          return $data;
     }

     /* PAYROLL REPORTS END */

     /* BANKING START */

     public function modal_generate_bank($id)
     {
          // $data['_bank']      = Tbl_payroll_bank_convertion::orderBy('bank_name')->get();
          // $data['id']         = $id;
          // $data['company']    = Tbl_payroll_company::getbyperiod($id)->first();

          // return view('member.payroll.modal.modal_bank', $data);
          $query = Tbl_payroll_period_company::getcompanydetails($id)->first();

          $_record = Tbl_payroll_record::getcompanyrecord($id)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->get();

          // $fileText = '';
          // dd($query);

          $bank_name  = $query->bank_name;

          $data = array();

          foreach($_record as $record)
          {
               $compute = Payroll::getrecord_breakdown($record);

               $temp_array = array();

               $temp_array['payroll_employee_title_name']   = $compute['payroll_employee_title_name'];
               $temp_array['payroll_employee_first_name']   = $compute['payroll_employee_first_name'];
               $temp_array['payroll_employee_middle_name']  = $compute['payroll_employee_middle_name'];
               $temp_array['payroll_employee_last_name']    = $compute['payroll_employee_last_name'];
               $temp_array['payroll_employee_suffix_name']  = $compute['payroll_employee_suffix_name'];
               $temp_array['payroll_employee_display_name'] = $compute['payroll_employee_display_name'];
               $temp_array['payroll_employee_atm_number']   = $compute['payroll_employee_atm_number'];
               $temp_array['total_net']                     = $compute['total_net'];

               array_push($data, $temp_array);
         
          }

          $title = $query->payroll_company_name.' - '.$bank_name.' ('.date('M d, Y', strtotime($query->payroll_period_start)).' to '.date('M d, Y', strtotime($query->payroll_period_end)).')';


          if($bank_name == 'BDO')
          {
               return Self::bdo_bank_template($data, $title);
          }

          else if($bank_name == 'Metro Bank')
          {
               return Self::metro_bank_template($data, $title);
          }

          else if($bank_name == 'Equicom')
          {
               return Self::equicom_bank_template($data, $title);
          }
          else
          {
               return Self::bdo_bank_template($data, $title);
          }
     }

     public function generate_bank()
     {

          $company_period_id  = Request::input('company_period_id');
          $bank_name          = Request::input('bank_name');
          $upload_date        = date('mdy',strtotime(Request::input('upload_date')));
          $batch_no           = Request::input('batch_no');
          $company_code       = Request::input('company_code');

          if($batch_no <= 9)
          {
               $batch_no = '0'.$batch_no;
          }

          $_record = Tbl_payroll_record::getcompanyrecord($company_period_id)->orderBy('tbl_payroll_employee_basic.payroll_employee_first_name')->get();

          // $fileText = '';

          $data = array();

          foreach($_record as $record)
          {
               $compute = Payroll::getrecord_breakdown($record);

               $temp_array = array();

               $temp_array['payroll_employee_title_name']   = $compute['payroll_employee_title_name'];
               $temp_array['payroll_employee_first_name']   = $compute['payroll_employee_first_name'];
               $temp_array['payroll_employee_middle_name']  = $compute['payroll_employee_middle_name'];
               $temp_array['payroll_employee_last_name']    = $compute['payroll_employee_last_name'];
               $temp_array['payroll_employee_suffix_name']  = $compute['payroll_employee_suffix_name'];
               $temp_array['payroll_employee_display_name'] = $compute['payroll_employee_display_name'];
               $temp_array['payroll_employee_atm_number']   = $compute['payroll_employee_atm_number'];
               $temp_array['total_net']                     = $compute['total_net'];

               array_push($data, $temp_array);
               
               // $fileText .= $compute['payroll_employee_atm_number']."\t".number_format($compute['total_net'], 2,'.','')."\r\n";
          }


          if($bank_name == 'BDO')
          {
               return Self::bdo_bank_template($data);
          }

          else if($bank_name == 'Metro Bank')
          {
               return Self::metro_bank_template($data);
          }

          else if($bank_name == 'Equicom')
          {
               return Self::equicom_bank_template($data);
          }

          /* use bdo as default */
          else
          {
               return Self::bdo_bank_template($data);
          }

          // $myName = $company_code.$upload_date.$batch_no;

          // $headers = ['Content-type'=>'text/plain', 'test'=>'YoYo', 'Content-Disposition'=>sprintf('attachment; filename="%s"', $myName.".txt"),'X-BooYAH'=>'WorkyWorky','Content-Length'=>sizeof($fileText)];

          // return Response::make($fileText, 200, $headers);
     }


     public function bdo_bank_template($data = array(), $title = '')
     {
          $column = array();

          $temp = array();
          $temp['account_number']  = 'ACCOUNT NUMBER';
          $temp['amount']          = 'AMOUNT';
          $temp['name']            = 'NAME';

          array_push($column, $temp);
          $total = 0;

          $data = collect($data)->sortBy('payroll_employee_first_name');

          foreach($data as $bdo)
          {
               $temp = array();
               $temp['account_number'] = $bdo['payroll_employee_atm_number'];
               $temp['amount'] = $bdo['total_net'];
               $temp['name'] = $bdo['payroll_employee_title_name'].' '.$bdo['payroll_employee_first_name'].' '.$bdo['payroll_employee_middle_name'].' '.$bdo['payroll_employee_last_name'].' '.$bdo['payroll_employee_suffix_name'];

               array_push($column, $temp);
               $total += $bdo['total_net'];
          }

          $temp = array();
          $temp['account_number'] = '';
          $temp['amount'] = $total;
          $temp['name'] = '';

          array_push($column, $temp);
          AuditTrail::record_logs("DOWNLOAD","BDO Bank Template",$this->shop_id(),"","");

          return Excel::create($title, function($excel) use ($column) {

               $date = 'BDO';
               $excel->setTitle('Payroll');
               $excel->setCreator('Laravel')->setCompany('DIGIMA');
               $excel->setDescription('payroll file');

               $excel->sheet($date, function($sheet) use ($column) {
                    $sheet->fromArray($column, null, 'A1', true, false);
               });

          })->download('xlsx');

     }

     public function metro_bank_template($data = array(), $title = '')
     {
          $column = array();

          $temp = array();
          $temp['name']            = 'Employee Name';
          $temp['account_number']  = 'ATM No.';
          $temp['amount']          = 'Salary';

          array_push($column, $temp);
          $total = 0;

          $data = collect($data)->sortBy('payroll_employee_first_name');

          foreach($data as $bdo)
          {
               $temp = array();
               
               $temp['name'] = $bdo['payroll_employee_title_name'].' '.$bdo['payroll_employee_first_name'].' '.$bdo['payroll_employee_middle_name'].' '.$bdo['payroll_employee_last_name'].' '.$bdo['payroll_employee_suffix_name'];
               $temp['account_number'] = $bdo['payroll_employee_atm_number'];
               $temp['amount'] = $bdo['total_net'];

               array_push($column, $temp);
               $total += $bdo['total_net'];
          }

          $temp = array();

          $temp['name']            = '';
          $temp['account_number']  = '';
          $temp['amount']          = $total;
          

          array_push($column, $temp);
          AuditTrail::record_logs("DOWNLOAD","Metro Bank Template",$this->shop_id(),"","");
          return Excel::create($title, function($excel) use ($column) {

               $date = 'BDO';
               $excel->setTitle('Payroll');
               $excel->setCreator('Laravel')->setCompany('DIGIMA');
               $excel->setDescription('payroll file');

               $excel->sheet($date, function($sheet) use ($column) {
                    $sheet->fromArray($column, null, 'A1', true, false);
               });

          })->download('xlsx');
     }

     public function equicom_bank_template($data = array(), $title = '')
     {
          $column = array();

          $temp = array();
          $temp['last_name']       = 'LAST NAME';
          $temp['first_name']      = 'FIRST NAME';
          $temp['account_number']  = 'ACCOUNT NUMBER';
          $temp['amount']          = 'Salary';

          array_push($column, $temp);
          $total = 0;

          $data = collect($data)->sortBy('payroll_employee_last_name');

          foreach($data as $bdo)
          {
               $temp = array();
          
               $temp['last_name']       = $bdo['payroll_employee_last_name'];
               $temp['first_name']      = $bdo['payroll_employee_first_name'];
               $temp['account_number']  = $bdo['payroll_employee_atm_number'];
               $temp['amount']          = $bdo['total_net'];

               array_push($column, $temp);
               $total += $bdo['total_net'];
          }

          $temp = array();

          $temp['last_name']       = '';
          $temp['first_name']      = '';
          $temp['account_number']  = '';
          $temp['amount']          = $total;
          

          array_push($column, $temp);
          AuditTrail::record_logs("DOWNLOAD","BANK TEMPLATE",$this->shop_id(),"","");
          return Excel::create($title, function($excel) use ($column) {

               $date = 'BDO';
               $excel->setTitle('Payroll');
               $excel->setCreator('Laravel')->setCompany('DIGIMA');
               $excel->setDescription('payroll file');

               $excel->sheet($date, function($sheet) use ($column) {
                    $sheet->fromArray($column, null, 'A1', true, false);
               });

          })->download('xlsx');
     }

     /* BANKING END */



     /* SHIFT START */
     public function shift_group()
     {
          return view('member.payroll.payroll_shift_group');
     }
     /* SHIFT END */

     /*13th month pay report START*/
     public function report_13th_month_pay()
     {
          //dd(Self::shop_id());
          $data['start_date']      = NULL;
          $data['end_date']        = NULL;
          $data['company_id']      = 0;
          $data['department_id']   = 0;
          $data['emp_id']          = 0;

          if(Request::has('start_date') && Request::has('end_date'))
          {
               $data['start_date']      = date('Y-m-d', strtotime(Request::input('start_date')));
               $data['end_date']        = date('Y-m-d', strtotime(Request::input('end_date')));
               //dd(Request::all());
          }  

          $data['company_id']      = Request::input('company_id');
          $data['department_id']   = Request::input('department_id');
          $data['emp_id']          = Request::input('emp_id');       
          
          //dd(Request::all());
          $data['_company']        = Payroll::company_heirarchy(Self::shop_id());
          $data['_department']     = Tbl_payroll_department::sel(Self::shop_id(), 0)->get();
          $data['_active']         = Self::report_13th_month_pay_table($data['start_date'], $data['end_date'], $data['company_id'], $data['department_id'], $data['emp_id']);
          
          return view('member.payroll.report_13th_month_pay', $data);
     }

     public function report_13th_month_pay_table($start_date = '0000-00-00', $end_date = '0000-00-00', $company_id=0 ,$department_id = 0, $emp_id = 0)
     {
          $arr_record    = array();
          $count         = 0;
          $tot           = 0;  

          if($start_date==NULL)
          {
               $start_date = date('Y-m-d');
          }  

          $query_emp =  Tbl_payroll_employee_contract::employeefilter($company_id,$department_id,0,$start_date, Self::shop_id())->orderBy('payroll_employee_last_name');

          if($emp_id != 0)
          {
               $query_emp->where('tbl_payroll_employee_basic.payroll_employee_id', $emp_id);
          }
        
          $_emp = $query_emp->get();

          $arr_record = array();

          foreach ($_emp as $key_emp => $emp_value) 
          {
               //dd($_emp);
               $query_pay_rec = $payroll_record = Tbl_payroll_record::get13month($emp_value->payroll_employee_id);                                        
               if($start_date != NULL && $end_date != NULL)
               {
                    $query_pay_rec->where('tbl_payroll_period.payroll_period_start', '>=', $start_date)->where('tbl_payroll_period.payroll_period_end'  , '<=', $end_date);
               } 
               //dd($query_pay_rec);     
               $_pay_rec = $query_pay_rec->get();                       
               $sub_tot = 0;

               if(count($_pay_rec) !=0 )
               {
                    foreach ($_pay_rec as $key_pay_rec => $pay_rec_value) 
                    {
                         //dd($_pay_rec);                   
                         $n_13_month = $pay_rec_value->salary_monthly/12;
                         $tot += $n_13_month;
                         $sub_tot += $n_13_month;

                         if ($key_pay_rec == 0) 
                         {
                              $arr_record[$key_emp][$key_pay_rec]['name']           = $emp_value->payroll_employee_first_name. ' ' .$emp_value->payroll_employee_last_name; 
                              $arr_record[$key_emp][$key_pay_rec]['department']     = $emp_value->payroll_department_name;
                              $arr_record[$key_emp][$key_pay_rec]['job_title']      = $emp_value->payroll_jobtitle_name;      
                         } else {
                              $arr_record[$key_emp][$key_pay_rec]['name']           = ''; 
                              $arr_record[$key_emp][$key_pay_rec]['department']     = '';
                              $arr_record[$key_emp][$key_pay_rec]['job_title']      = '';    
                         }

                         $arr_record[$key_emp][$key_pay_rec]['period']               = date('M d, Y', strtotime($pay_rec_value->payroll_period_start)). ' - ' 
                                                                                .date('M d, Y', strtotime($pay_rec_value->payroll_period_end));
                         $arr_record[$key_emp][$key_pay_rec]['basic_salary']         = number_format($pay_rec_value->salary_monthly, 2);
                         
                         $arr_record[$key_emp][$key_pay_rec]['amount_of_13']         = number_format($n_13_month, 2);                           
                         if($key_pay_rec == count($_pay_rec)-1)
                         {
                              $arr_record[$key_emp][$key_pay_rec]['sub_total']       = number_format($sub_tot, 2);
                         } else {
                              $arr_record[$key_emp][$key_pay_rec]['sub_total']       = '';   

                         } 

                         $arr_record[$key_emp][$key_pay_rec]['employee_id']         = $emp_value->payroll_employee_id;
                    }      
               }                       
          }

          if($arr_record!=NULL)
          {
               $arr_record[$key_emp+1][$key_pay_rec]['name']           = ''; 
               $arr_record[$key_emp+1][$key_pay_rec]['department']     = '';
               $arr_record[$key_emp+1][$key_pay_rec]['job_title']      = '';    
               $arr_record[$key_emp+1][$key_pay_rec]['period']         = '';
               $arr_record[$key_emp+1][$key_pay_rec]['basic_salary']   = '';
               $arr_record[$key_emp+1][$key_pay_rec]['amount_of_13']   = 'TOTAL';  
               $arr_record[$key_emp+1][$key_pay_rec]['sub_total']      = number_format($tot, 2); 
               $arr_record[$key_emp+1][$key_pay_rec]['employee_id']      = '';
               return $arr_record;                   
          } else{
               return FALSE;
          }

     }   

     public function report_13th_month_pay_excel_export()
     {
          //dd('pasok');
          $start_date         = NULL;
          $end_date           = NULL;
          $company_id         = 0;
          $department_id      = 0;
          $emp_id             = 0;

          $date_range = Carbon::now()->format('M d, y');

          if(Request::has('start_date') && Request::has('end_date'))
          {
               $start_date    = date('Y-m-d', strtotime(Request::input('start_date')));
               $end_date      = date('Y-m-d', strtotime(Request::input('end_date')));
               $date_range    = date('M d, y', strtotime(Request::input('start_date'))). ' - ' .date('M d, y', strtotime(Request::input('end_date')));;
          }  

          $company_id      = Request::input('company_id');
          $department_id   = Request::input('department_id');
          $emp_id          = Request::input('emp_id');     

          $_record = Self::report_13th_month_pay_table($start_date, $end_date, $company_id, $department_id, $emp_id);
          
          $data = array();
          $record = array();
          $header = ['Employee Name', 'Department', 'Job Title', 'Payroll Period', 'Basic Salary', '13 Month', 'Sub Total'];
          /*$record = ['name', 'depart', 'job', 'period', 'alary', '13', 'sub'];*/
          array_push($data, $header);
         
          foreach($_record as $active){
               foreach($active as $a){
                     $record = [ 
                         $a['name'],
                         $a['department'],
                         $a['job_title'],
                         $a['period'],
                         $a['basic_salary'],
                         $a['amount_of_13'],
                         $a['sub_total'],
                         ];
                    array_push($data, $record);                                                         
               }
          }     

          $title = '13th Month pay Report ('.$date_range.')';
          //dd($title);
          AuditTrail::record_logs("DOWNLOAD","13 MONTH PAY REPORT",$this->shop_id(),"","");
          return Excel::create($title, function($excel) use ($data) {

               $date = 'reports';
               $excel->setTitle('Payroll');
               $excel->setCreator('Laravel')->setCompany('DIGIMA');
               $excel->setDescription('payroll file');

               $excel->sheet($date, function($sheet) use ($data) {
                    $sheet->fromArray($data, null, 'A1', true, false);
                    $sheet->setColumnFormat(array(
                         'B:BZ' => '0.00'
                         ));
               });

          })->download('xlsx');  
     }       
     /* end 13th month pay report*/

}
