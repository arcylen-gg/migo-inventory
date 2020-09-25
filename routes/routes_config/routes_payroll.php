<?php

Route::group(array('prefix' => '/member/payroll'), function()
{
	//audit_trail_transaction
	Route::any('/employee_list/modal_view_all_transaction/{id}/{uid}','Member\PayrollController@modal_view_all_transaction');
	//audit_trail_transaction
	Route::any('/payroll_api','Member\PayrollApiController@index');

	Route::any('/reports/government_forms','Member\PayrollReportController@government_forms');
	Route::any('/reports/government_forms_year_filter','Member\PayrollReportController@government_forms_year_filter');
	Route::any('/reports/government_forms_sss/{id}/{year}','Member\PayrollReportController@government_forms_sss');
	Route::any('/reports/government_forms_philhealth/{id}/{year}','Member\PayrollReportController@government_forms_philhealth');
	Route::any('/reports/government_forms_hdmf/{id}/{year}','Member\PayrollReportController@government_forms_hdmf');
	Route::any('/reports/government_forms_hdmf_iframe/{id}/{id2}/{year}','Member\PayrollReportController@government_forms_hdmf_iframe');
	Route::any('/reports/government_forms_hdmf_filter','Member\PayrollReportController@government_forms_hdmf_filter');
	Route::any('/reports/government_forms_sss_filter','Member\PayrollReportController@government_forms_sss_filter');
	Route::any('/reports/government_forms_philhealth_filter','Member\PayrollReportController@government_forms_philhealth_filter');
	Route::any('/reports/government_forms_hdmf_export_excel/{id}/{id2}/{year}','Member\PayrollReportController@government_forms_hdmf_export_excel');
	Route::any('/reports/government_forms_sss_export_excel/{id}/{id2}/{year}','Member\PayrollReportController@government_forms_sss_export_excel');
	Route::any('/reports/government_forms_philhealth_export_excel/{id}/{id2}/{year}','Member\PayrollReportController@government_forms_philhealth_export_excel');

	/*manpowereport*/
	Route::any('/reports/manpower_report',"Member\PayrollReportController@manpower_report");
	Route::any('/reports/manpower_report_filter',"Member\PayrollReportController@manpower_report_filter");
	Route::any('/reports/manpower_report_export_excel/{company_id}',"Member\PayrollReportController@manpower_report_export_excel");
	/*endmanpower*/

	/* BIR REPORT */
	Route::any('/reports/bir_forms','Member\PayrollReportController@bir_form');
	Route::any('/reports/view_bir_forms/{year}/{month}/{company}','Member\PayrollReportController@view_bir_forms');
	Route::any('/reports/bir_forms_filter','Member\PayrollReportController@bir_forms_filter');
	Route::any('/reports/bir_export_excel/{year}/{month}/{company}','Member\PayrollReportController@bir_export_excel');
	/* END BIR REPORT */
	
	/*START loan summary report*/
	Route::any('/reports/loan_summary','Member\PayrollReportController@loan_summary');
	Route::any('/reports/table_loan_summary/{deduction_type}/{company}','Member\PayrollReportController@table_loan_summary');
	Route::any('/reports/modal_loan_summary_report/{employee_id}/{payroll_deduction_id}','Member\PayrollReportController@modal_loan_summary');
	Route::any('/reports/export_loan_summary_report_to_excel/{employee_id}/{payroll_deduction_id}','Member\PayrollReportController@export_loan_summary_report_to_excel');
	Route::any('/reports/table_company_loan_summary','Member\PayrollReportController@table_company_loan_summary');

	Route::any('/reports/loan_summary/loan_summary_report_excel/{company_id}/{deduction_type}','Member\PayrollReportController@loan_summary_report_excel');

	/*END loan summary report*/

	/*START payroll ledger*/
	Route::any('/reports/payroll_ledger','Member\PayrollLedger@index');
	Route::any('/reports/payroll_ledger/{employee_id}','Member\PayrollLedger@modal_ledger');
	/*END payroll ledger/

	/*START PAYROLL REGISTER REPORT*/
	Route::any('/reports/payroll_register_report','Member\PayrollReportController@payroll_register_report');
	Route::any('/reports/modal_create_register_report/{id}','Member\PayrollReportController@modal_create_register_report');
	Route::any('/reports/payroll_register_report_period/{id}','Member\PayrollReportController@payroll_register_report_period');
	Route::any('/reports/payroll_register_report_table','Member\PayrollReportController@payroll_register_report_table');
	
	Route::any('/reports/payroll_register_report_period/export_excel/{period_company_id}/{payroll_company_id}/{payroll_department_id}','Member\PayrollReportController@payroll_register_report_export_excel');
	Route::any('/reports/payroll_register_report_period/export_excel_filter/{id}/{uid}','Member\PayrollReportController@payroll_register_report_export_excel_filter');

	Route::any('/reports/modal_filter_register_columns/{period_company_id}','Member\PayrollReportController@modal_filter_register_columns');
	Route::any('/reports/save_payroll_register_selected_columns','Member\PayrollReportController@save_payroll_register_selected_columns');
	/*END PAYROLL REGISTER REPORT*/

	/*START PAYROLL BRANCH TAGGING REPORT*/
	Route::any('/reports/branch_tagging_report','Member\PayrollBranchTaggingReportController@payroll_branch_tagging_report');
	Route::any('/reports/branch_tagging_report_period_table','Member\PayrollBranchTaggingReportController@payroll_branch_tagging_report_table');
	/*END PAYROLL BRANCH TAGGING REPORT*/

	/*EMPLOYEE SUMMARY REPORT*/
	Route::any('/reports/employee_summary_report','Member\PayrollReportController@employee_summary_report');

	/*START 13th month pay*/
	Route::any('/reports/13th_month_pay','Member\Payroll13thMonthPayController@index');
	Route::any('/reports/employees_13th_month_pay_table','Member\Payroll13thMonthPayController@employees_13th_month_pay_table');
	Route::any('/reports/modal_employee_13_month_pay_report','Member\Payroll13thMonthPayController@modal_employee_13_month_pay_report');

	Route::any('/reports/employee_13_month_pay_report/{employee_id}','Member\Payroll13thMonthPayController@employee_13_month_pay_report');
	Route::any('/reports/modal_employee_13_month_pay_report/{employee_id}','Member\Payroll13thMonthPayController@modal_employee_13_month_pay_report');
	Route::any('/reports/employee_13_month_pay_basis_submit','Member\Payroll13thMonthPayController@employee_13_month_pay_basis_submit');
	Route::any('/reports/employee_13_month_pay_report_table','Member\Payroll13thMonthPayController@employee_13_month_pay_report_table');
	/*END 13th month pay*/


	/* COMPANY START */
	Route::any('/company_list','Member\PayrollController@company_list');
	Route::any('/company_list/modal_create_company','Member\PayrollController@modal_create_company');
	Route::any('/company_list/upload_company_logo','Member\PayrollController@upload_company_logo');
	Route::any('/company_list/modal_save_company','Member\PayrollController@modal_save_company');
	Route::any('/company_list/view_company_modal/{id}','Member\PayrollController@view_company_modal');
	Route::any('/company_list/edit_company_modal/{id}','Member\PayrollController@edit_company_modal');
	Route::any('/company_list/reload_company','Member\PayrollController@reload_company');
	Route::any('/company_list/archived_company','Member\PayrollController@archived_company');
	Route::any('/company_list/update_company','Member\PayrollController@update_company');
	Route::any('/company_list/modal_archived_company/{archived}/{id}','Member\PayrollController@modal_archived_company');
	/* COMPANY END */

	/* EMPLOYEE START */
	Route::any('/employee_list','Member\PayrollController@employee_list');

	/* export to pdf */
	Route::any('/employee_list/export_to_pdf_employee','Member\PayrollController@export_to_pdf_employee');
	/* import from excel start */
	Route::any('/employee_list/modal_import_employee','Member\PayrollController@modal_import_employee');
	Route::any('/employee_list/modal_import_employee/get_201_template','Member\PayrollController@get_201_template');
	Route::any('/employee_list/modal_import_employee/import_201_template','Member\PayrollController@import_201_template');
	/* import from excel end */

	Route::any('/employee_list/modal_create_employee','Member\PayrollController@modal_create_employee');
	Route::any('/employee_list/employee_updload_requirements','Member\PayrollController@employee_updload_requirements');
	Route::any('/employee_list/remove_employee_requirement','Member\PayrollController@remove_employee_requirement');
	Route::any('/employee_list/modal_employee_save','Member\PayrollController@modal_employee_save');
	Route::any('/employee_list/modal_employee_view/{id}','Member\PayrollController@modal_employee_view');
	Route::any('/employee_list/modal_view_contract_list/{id}','Member\PayrollController@modal_view_contract_list');
	Route::any('/employee_list/modal_create_contract/{id}','Member\PayrollController@modal_create_contract');
	Route::any('/employee_list/modal_save_contract','Member\PayrollController@modal_save_contract');
	Route::any('/employee_list/modal_edit_contract/{employee_id}/{id}','Member\PayrollController@modal_edit_contract');
	Route::any('/employee_list/modal_update_contract','Member\PayrollController@modal_update_contract');
	Route::any('/employee_list/modal_archive_contract/{archived}/{id}','Member\PayrollController@modal_archive_contract');
	Route::any('/employee_list/modal_salary_list/{id}','Member\PayrollController@modal_salary_list');
	Route::any('/employee_list/modal_edit_salary_adjustment/{id}','Member\PayrollController@modal_edit_salary_adjustment');
	Route::any('/employee_list/modal_update_salary','Member\PayrollController@modal_update_salary');
	Route::any('/employee_list/modal_archived_salary/{archived}/{id}','Member\PayrollController@modal_archived_salary');
	Route::any('/employee_list/archived_salary','Member\PayrollController@archived_salary');
	Route::any('/employee_list/archive_contract','Member\PayrollController@archive_contract');
	Route::any('/employee_list/modal_create_salary_adjustment/{id}','Member\PayrollController@modal_create_salary_adjustment');
	Route::any('/employee_list/modal_save_salary','Member\PayrollController@modal_save_salary');
	Route::any('/employee_list/modal_employee_update','Member\PayrollController@modal_employee_update');
	Route::any('/employee_list/reload_employee_list','Member\PayrollController@reload_employee_list');
	Route::any('/employee_list/shift_view','Member\PayrollController@shift_view');

	/* EMPLOYEE SEARCH */
	Route::any('/employee_list/search_employee_ahead','Member\PayrollController@search_employee_ahead');
	Route::any('/employee_list/search_employee','Member\PayrollController@search_employee');
	/* EMPLOYEE END */
	
	Route::any('/payroll_configuration','Member\PayrollController@payroll_configuration');
	
	//company_register_report/
	/* TIMESHEET START */
	Route::any('/employee_timesheet','Member\PayrollTimeSheetController@index');
	Route::any('/company_timesheet/{id}','Member\PayrollTimeSheetController@company_timesheet');
	Route::any('/company_timesheet2/income_summary/{period_id}/{employee_id}','Member\PayrollTimeSheet2Controller@income_summary');
	Route::any('/company_timesheet2/unapprove/{period_id}/{employee_id}','Member\PayrollTimeSheet2Controller@unapprove');
	Route::any('/company_timesheet2/change/{period_id}/{employee_id}','Member\PayrollTimeSheet2Controller@time_change');
	Route::any('/company_timesheet2/remarks_change/{period_id}/{employee_id}', 'Member\PayrollTimeSheet2Controller@remarks_change');
	Route::any('/company_timesheet2/make_adjustment/{period_id}/{employee_id}','Member\PayrollTimeSheet2Controller@make_adjustment');
	Route::any('/company_timesheet2/delete_adjustment/{period_id}/{employee_id}/{adjustment_id}','Member\PayrollTimeSheet2Controller@delete_adjustment');	
	Route::any('/company_timesheet_approve/approve_timesheet','Member\PayrollTimeSheet2Controller@approve_timesheets');
	Route::any('/company_timesheet2/{company_id}','Member\PayrollTimeSheet2Controller@index');
	Route::any('/company_timesheet2/table/{company_id}','Member\PayrollTimeSheet2Controller@index_table');
	Route::any('/company_timesheet2/{company_id}/{employee_id}','Member\PayrollTimeSheet2Controller@timesheet');
	Route::any('/company_timesheet2_pdf/{company_id}/{employee_id}','Member\PayrollTimeSheet2Controller@timesheet_pdf');
	Route::any('/company_timesheet_day_summary/info/{time_sheet_id}','Member\PayrollTimeSheet2Controller@day_summary_info');
	Route::post('/company_timesheet_day_summary/change','Member\PayrollTimeSheet2Controller@day_summary_change');
	Route::any('/company_timesheet_day_summary/{time_sheet_id}','Member\PayrollTimeSheet2Controller@day_summary');
	/* CUSTOM SHIFT */
	
	Route::any('/company_timesheet_custom_shift','Member\PayrollTimeSheet2Controller@custom_shift');
	Route::post('/company_timesheet_custom_shift_update','Member\PayrollTimeSheet2Controller@custom_shift_update');
	

	Route::any('/employee_timesheet/timesheet/{id}/{period_id}','Member\PayrollTimeSheetController@timesheet');
	Route::any('/employee_timesheet/timesheet_pdf/{id}/{period_id}','Member\PayrollTimeSheetController@timesheet_pdf');
	Route::any('/employee_timesheet/json_process_time','Member\PayrollTimeSheetController@json_process_time');
	Route::any('/employee_timesheet/save_time_record','Member\PayrollTimeSheetController@save_time_record');
	Route::any('/employee_timesheet/new_time_tr','Member\PayrollTimeSheetController@new_time_tr');

	Route::any('/employee_timesheet/remove_time_record','Member\PayrollTimeSheetController@remove_time_record');
	Route::any('/employee_timesheet/json_process_time_single/{date}/{employee_id}','Member\PayrollTimeSheetController@json_process_time_single');
	Route::any('/employee_timesheet/adjustment_form','Member\PayrollTimeSheetController@adjustment_form');
	Route::post('/employee_timesheet/adjustment_form_approve','Member\PayrollTimeSheetController@adjustment_form_approve');
	Route::any('/employee_timesheet/show_holiday/{id}/{date}','Member\PayrollTimeSheetController@show_holiday');
	Route::any('/timesheet/mark_ready_company','Member\PayrollController@mark_ready_company');
	Route::any('/timesheet/show_summary/{summary}/{period_id}','Member\PayrollTimeSheetController@show_summary');

	Route::any('/timesheet/send_reminder','Member\PayrollTimeSheetController@send_reminder');
	Route::any('/timesheet/modal_timesheet_comment/{id}','Member\PayrollTimeSheetController@modal_timesheet_comment');
	Route::any('/timesheet/modal_choose_company/{id}','Member\PayrollTimeSheetController@modal_choose_company');
	Route::any('/timesheet/choose_company_save','Member\PayrollTimeSheetController@choose_company_save');
	Route::any('/timesheet/time_sheet_comment_save','Member\PayrollTimeSheetController@time_sheet_comment_save');
	/* TIMESHEET END */


	/* BRANCH NAME START */
	Route::any('/branch_name','Member\PayrollController@branch_name');
	Route::any('/branch_name/modal_create_branch','Member\PayrollController@modal_create_branch');
	Route::any('/branch_name/modal_save_branch','Member\PayrollController@modal_save_branch');
	Route::any('/branch_name/modal_edit_branch/{id}','Member\PayrollController@modal_edit_branch');
	Route::any('/branch_name/modal_update_branch/{id}','Member\PayrollController@modal_update_branch');
	Route::any('/branch_name/modal_archive_branch/{archived}/{id}','Member\PayrollController@modal_archive_branch');
	Route::any('/branch_name/archive_branch','Member\PayrollController@archive_branch');
	/* BRANCH NAME END */
	
	/* DEPARTMENT START */
	Route::any('/departmentlist','Member\PayrollController@department_list');
	Route::any('/departmentlist/department_modal_create','Member\PayrollController@department_modal_create');
	Route::any('/departmentlist/department_save','Member\PayrollController@department_save');
	Route::any('/departmentlist/modal_archived_department/{archived}/{department_id}',"Member\PayrollController@modal_archived_department");
	Route::any('/departmentlist/archived_department','Member\PayrollController@archived_department');
	Route::any('/departmentlist/department_reload','Member\PayrollController@department_reload');
	Route::any('/departmentlist/modal_view_department/{id}','Member\PayrollController@modal_view_department');
	Route::any('/departmentlist/modal_edit_department/{id}','Member\PayrollController@modal_edit_department');
	Route::any('/departmentlist/modal_update_department','Member\PayrollController@modal_update_department');
	/* DEPARTMENT END */

	/* JOB TITLE START */
	Route::any("/jobtitlelist","Member\PayrollController@jobtitle_list");
	Route::any("/jobtitlelist/modal_create_jobtitle","Member\PayrollController@modal_create_jobtitle");
	Route::any("/jobtitlelist/modal_save_department","Member\PayrollController@modal_save_department");
	Route::any("/jobtitlelist/reload_tbl_jobtitle","Member\PayrollController@reload_tbl_jobtitle");
	Route::any("/jobtitlelist/get_job_title_by_department","Member\PayrollController@get_job_title_by_department");
	Route::any("/jobtitlelist/modal_view_jobtitle/{id}","Member\PayrollController@modal_view_jobtitle");
	Route::any("/jobtitlelist/modal_update_jobtitle","Member\PayrollController@modal_update_jobtitle");
	Route::any("/jobtitlelist/modal_edit_jobtitle/{id}","Member\PayrollController@modal_edit_jobtitle");
	Route::any("/jobtitlelist/modal_save_jobtitle","Member\PayrollController@modal_save_jobtitle");
	Route::any('/jobtitlelist/modal_archived_jobtitle/{archived}/{jobtitle_id}',"Member\PayrollController@modal_archived_jobtitle");
	Route::any('/jobtitlelist/archived_jobtitle','Member\PayrollController@archived_jobtitle');
	/* JOB TITLE END */


	/* TAX PERIOD START */
	Route::any('/tax_period',"Member\PayrollController@tax_period");
	Route::any('/tax_period/taxt_perid_change',"Member\PayrollController@taxt_perid_change");
	/* TAX PERIOD END */

	/* TAX TABLE START */
	Route::any('/tax_table_list',"Member\PayrollController@tax_table_list");
	Route::any('/tax_table_list/tax_table_save',"Member\PayrollController@tax_table_save");

	/* saving default data for developer */
	Route::any('/tax_table_list/tax_table_save_default',"Member\PayrollController@tax_table_save_default");
	
	/* TAX TABLE END */

	/* SSS TABLE START */
	Route::any('/sss_table_list',"Member\PayrollController@sss_table_list");
	Route::any('/sss_table_list/sss_table_save',"Member\PayrollController@sss_table_save");

	/* saving default data for developer */
	Route::any('/sss_table_list/sss_table_save_default',"Member\PayrollController@sss_table_save_default");
	/* SSS TABLE END */

	/* PHILHEALTH TABLE START */
	Route::any('/philhealth_table_list',"Member\PayrollController@philhealth_table_list");
	Route::any('/philhealth_table_list/philhealth_table_save',"Member\PayrollController@philhealth_table_save");
	/* saving default data for developer */
	Route::any('/philhealth_table_list/philhealth_table_save_default',"Member\PayrollController@philhealth_table_save_default");
	/* PHILHEALTH TABLE END */


	/* PAGIBIG START */
	Route::any('/pagibig_formula',"Member\PayrollController@pagibig_formula");
	Route::any('/pagibig_formula/pagibig_formula_save',"Member\PayrollController@pagibig_formula_save");

	/* saving default data for developer */
	Route::any('/pagibig_formula/pagibig_formula_save_default',"Member\PayrollController@pagibig_formula_save_default");
	/* PAGIBIG END */


	/* reset payroll start */
	Route::any('/reset_payroll','Member\PayrollController@reset_payroll');
	Route::any('/reset_payroll/reset_time_sheet','Member\PayrollController@reset_time_sheet');
	Route::any('/reset_payroll/reset_time_sheet/reset_time_sheet_select','Member\PayrollController@reset_time_sheet_select');
	Route::any('/reset_payroll/reset_time_sheet/reset_time_sheet_action','Member\PayrollController@reset_time_sheet_action');
	/* reset payroll end */


	/* DEDUCTION START */
	Route::any('/deduction',"Member\PayrollController@deduction");
	Route::any('/deduction/modal_create_deduction',"Member\PayrollController@modal_create_deduction");
	Route::any('/deduction/modal_create_deduction_type/{type}',"Member\PayrollController@modal_create_deduction_type");
	Route::any('/deduction/modal_save_deduction_type',"Member\PayrollController@modal_save_deduction_type");
	Route::any('/deduction/update_deduction_type',"Member\PayrollController@update_deduction_type");
	Route::any('/deduction/reload_deduction_type',"Member\PayrollController@reload_deduction_type");
	Route::any('/deduction/archive_deduction_type',"Member\PayrollController@archive_deduction_type");
	Route::any('/deduction/ajax_deduction_type',"Member\PayrollController@ajax_deduction_type");
	Route::any('/deduction/modal_deduction_tag_employee/{id}',"Member\PayrollController@modal_deduction_tag_employee");
	Route::any('/deduction/ajax_deduction_tag_employee',"Member\PayrollController@ajax_deduction_tag_employee");
	Route::any('/deduction/set_employee_deduction_tag',"Member\PayrollController@set_employee_deduction_tag");
	Route::any('/deduction/get_employee_deduction_tag',"Member\PayrollController@get_employee_deduction_tag");
	Route::any('/deduction/remove_from_tag_session',"Member\PayrollController@remove_from_tag_session");
	Route::any('/deduction/modal_save_deduction',"Member\PayrollController@modal_save_deduction");
	Route::any('/deduction/modal_edit_deduction/{id}',"Member\PayrollController@modal_edit_deduction");
	Route::any('/deduction/archive_deduction/{archived}/{id}',"Member\PayrollController@archive_deduction");
	Route::any('/deduction/archived_deduction_action',"Member\PayrollController@archived_deduction_action");
	Route::any('/deduction/modal_update_deduction',"Member\PayrollController@modal_update_deduction");
	Route::any('/deduction/reload_deduction_employee_tag',"Member\PayrollController@reload_deduction_employee_tag");
	Route::any('/deduction/deduction_employee_tag/{archived}/{payroll_deduction_employee_id}',"Member\PayrollController@deduction_employee_tag");
	Route::any('/deduction/deduction_employee_tag_archive',"Member\PayrollController@deduction_employee_tag_archive");
	/* DEDUCTION END */


	/* DEDUCTION V2 START */
	Route::any('/deduction/v2',"Member\PayrollDeductionController@index");
	Route::any('/deduction/v2/modal_view_deduction_employee',"Member\PayrollDeductionController@modal_view_deduction_employee");
	Route::any('/deduction/v2/modal_view_deduction_employee_config',"Member\PayrollDeductionController@modal_view_deduction_employee_config");
	Route::any('/deduction/v2/modal_create_deduction',"Member\PayrollDeductionController@modal_create_deduction");
	Route::any('/deduction/v2/modal_create_deduction_type/{type}',"Member\PayrollDeductionController@modal_create_deduction_type");
	Route::any('/deduction/v2/modal_save_deduction_type',"Member\PayrollDeductionController@modal_save_deduction_type");
	Route::any('/deduction/v2/update_deduction_type',"Member\PayrollDeductionController@update_deduction_type");
	Route::any('/deduction/v2/reload_deduction_type',"Member\PayrollDeductionController@reload_deduction_type");
	Route::any('/deduction/v2/archive_deduction_type',"Member\PayrollDeductionController@archive_deduction_type");
	Route::any('/deduction/v2/ajax_deduction_type',"Member\PayrollDeductionController@ajax_deduction_type");
	Route::any('/deduction/v2/modal_deduction_tag_employee/{id}',"Member\PayrollDeductionController@modal_deduction_tag_employee");
	Route::any('/deduction/v2/ajax_deduction_tag_employee',"Member\PayrollDeductionController@ajax_deduction_tag_employee");
	Route::any('/deduction/v2/set_employee_deduction_tag',"Member\PayrollDeductionController@set_employee_deduction_tag");
	Route::any('/deduction/v2/get_employee_deduction_tag',"Member\PayrollDeductionController@get_employee_deduction_tag");
	Route::any('/deduction/v2/remove_from_tag_session',"Member\PayrollDeductionController@remove_from_tag_session");
	Route::any('/deduction/v2/modal_save_deduction',"Member\PayrollDeductionController@modal_save_deduction");
	Route::any('/deduction/v2/modal_edit_deduction/{id}',"Member\PayrollDeductionController@modal_edit_deduction");
	Route::any('/deduction/v2/archive_deduction/{archived}/{id}',"Member\PayrollDeductionController@archive_deduction");
	Route::any('/deduction/v2/archived_deduction_action',"Member\PayrollDeductionController@archived_deduction_action");
	Route::any('/deduction/v2/modal_update_deduction',"Member\PayrollDeductionController@modal_update_deduction");
	Route::any('/deduction/v2/reload_deduction_employee_tag',"Member\PayrollDeductionController@reload_deduction_employee_tag");
	Route::any('/deduction/v2/deduction_employee_tag/{archived}/{payroll_deduction_employee_id}',"Member\PayrollDeductionController@deduction_employee_tag");
	Route::any('/deduction/v2/deduction_employee_tag_archive',"Member\PayrollDeductionController@deduction_employee_tag_archive");
	/* DEDUCTION V2 END */


	/* HOLIDAY START */
	Route::any('/holiday',"Member\PayrollController@holiday");
	Route::any('/holiday/modal_create_holiday',"Member\PayrollController@modal_create_holiday");
	Route::any('/holiday/modal_save_holiday',"Member\PayrollController@modal_save_holiday");
	Route::any('/holiday/archive_holiday/{archived}/{id}',"Member\PayrollController@archive_holiday");
	Route::any('/holiday/archive_holiday_action',"Member\PayrollController@archive_holiday_action");
	Route::any('/holiday/modal_edit_holiday/{id}',"Member\PayrollController@modal_edit_holiday");
	Route::any('/holiday/modal_update_holiday',"Member\PayrollController@modal_update_holiday");
	/* HOLIDAY END */

	/* HOLIDAY START V2 */
	Route::any('/holiday/v2',"Member\PayrollHolidayController@holiday");
	Route::any('/holiday/modal_create_holiday/v2',"Member\PayrollHolidayController@modal_create_holiday");
	Route::any('/holiday/modal_save_holiday/v2',"Member\PayrollHolidayController@modal_save_holiday");
	Route::any('/holiday/archive_holiday/v2/{archived}/{id}',"Member\PayrollHolidayController@archive_holiday");
	Route::any('/holiday/archive_holiday_action/v2',"Member\PayrollHolidayController@archive_holiday_action");
	Route::any('/holiday/modal_edit_holiday/v2/{id}',"Member\PayrollHolidayController@modal_edit_holiday");
	Route::any('/holiday/modal_update_holiday/v2',"Member\PayrollHolidayController@modal_update_holiday");
	Route::any('/holiday/modal_tag_employee/{company_id}','Member\PayrollHolidayController@tag_employee');
	Route::any('/holiday/tag_employee/submit','Member\PayrollHolidayController@submit_eployee');
	/* HOLIDAY END v2 */

	/* HOLIDAY DEFAULT START */
	Route::any('/holiday_default',"Member\PayrollController@default_holiday");
	/* HOLIDAY DEFAULT END */

	/* ALLOWANCE START */
	Route::any('/allowance',"Member\PayrollController@allowance");
	Route::any('/allowance/modal_create_allowance',"Member\PayrollController@modal_create_allowance");
	Route::any('/allowance/modal_allowance_tag_employee/{allowance_id}',"Member\PayrollController@modal_allowance_tag_employee");
	Route::any('/allowance/set_employee_allowance_tag',"Member\PayrollController@set_employee_allowance_tag");
	Route::any('/allowance/get_employee_allowance_tag',"Member\PayrollController@get_employee_allowance_tag");
	Route::any('/allowance/remove_allowance_tabe_employee',"Member\PayrollController@remove_allowance_tabe_employee");
	Route::any('/allowance/modal_save_allowances',"Member\PayrollController@modal_save_allowances");
	Route::any('/allowance/modal_archived_allwance/{archived}/{allowance_id}',"Member\PayrollController@modal_archived_allwance");
	Route::any('/allowance/archived_allowance',"Member\PayrollController@archived_allowance");
	Route::any('/allowance/modal_edit_allowance/{id}',"Member\PayrollController@modal_edit_allowance");
	Route::any('/allowance/modal_archived_llowance_employee/{archived}/{id}',"Member\PayrollController@modal_archived_llowance_employee");
	Route::any('/allowance/reload_allowance_employee',"Member\PayrollController@reload_allowance_employee");
	Route::any('/allowance/archived_allowance_employee',"Member\PayrollController@archived_allowance_employee");
	Route::any('/allowance/update_allowance',"Member\PayrollController@update_allowance");
	/* ALLOWANCE END */

	/* ALLOWANCE START V2*/
	Route::any('/allowance/v2',"Member\PayrollAllowanceController@allowance");
	Route::any('/allowance/v2/modal_create_allowance',"Member\PayrollAllowanceController@modal_create_allowance");
	Route::any('/allowance/v2/modal_allowance_tag_employee/{allowance_id}',"Member\PayrollAllowanceController@modal_allowance_tag_employee");
	Route::any('/allowance/v2/set_employee_allowance_tag',"Member\PayrollAllowanceController@set_employee_allowance_tag");
	Route::any('/allowance/v2/get_employee_allowance_tag',"Member\PayrollAllowanceController@get_employee_allowance_tag");
	Route::any('/allowance/v2/remove_allowance_tabe_employee',"Member\PayrollAllowanceController@remove_allowance_tabe_employee");
	Route::any('/allowance/v2/modal_save_allowances',"Member\PayrollAllowanceController@modal_save_allowances");
	Route::any('/allowance/v2/modal_archived_allwance/{archived}/{allowance_id}',"Member\PayrollAllowanceController@modal_archived_allwance");
	Route::any('/allowance/v2/archived_allowance',"Member\PayrollAllowanceController@archived_allowance");
	Route::any('/allowance/v2/modal_edit_allowance/{id}',"Member\PayrollAllowanceController@modal_edit_allowance");
	Route::any('/allowance/v2/modal_archived_llowance_employee/{archived}/{id}',"Member\PayrollAllowanceController@modal_archived_llowance_employee");
	Route::any('/allowance/v2/reload_allowance_employee',"Member\PayrollAllowanceController@reload_allowance_employee");
	Route::any('/allowance/v2/archived_allowance_employee',"Member\PayrollAllowanceController@archived_allowance_employee");
	Route::any('/allowance/v2/update_allowance',"Member\PayrollAllowanceController@update_allowance");
	/* ALLOWANCE V2 END */

	/* LEAVE START */
	Route::any('/leave',"Member\PayrollController@leave");

	/*Link to Modal Create leave_temp*/
	Route::any('/leave/modal_create_leave_temp',"Member\PayrollController@modal_create_leave_temp");
	/*Another Modal for tagging employee*/

	Route::any('/leave/modal_leave_tag_employee/{leave_temp_id}',"Member\PayrollController@modal_leave_tag_employee");
	Route::any('/leave/set_leave_employee_tag',"Member\PayrollController@set_leave_employee_tag");
	Route::any('/leave/get_leave_tag_employee',"Member\PayrollController@get_leave_tag_employee");
	Route::any('/leave/remove_leave_tag_employee',"Member\PayrollController@remove_leave_tag_employee");
	Route::any('/leave/modal_save_leave_temp',"Member\PayrollController@modal_save_leave_temp");
	Route::any('/leave/modal_archived_leave_temp/{archived}/{leave_temp_id}',"Member\PayrollController@modal_archived_leave_temp");
	Route::any('/leave/archived_leave_temp',"Member\PayrollController@archived_leave_temp");
	
	Route::any('/leave/reload_leave_employee',"Member\PayrollController@reload_leave_employee");

	Route::any('/leave/modal_edit_leave_temp/{id}',"Member\PayrollController@modal_edit_leave_temp");
	Route::any('/leave/set_leave_tag_employee',"Member\PayrollController@set_leave_tag_employee");

	Route::any('/leave/archived_leave_employee',"Member\PayrollController@archived_leave_employee");

	Route::any('/leave/modal_archived_leave_employee/{archived}/{id}',"Member\PayrollController@modal_archived_leave_employee");
	/*Update leave temp*/
	Route::any('/leave/update_leave_temp',"Member\PayrollController@update_leave_temp");
	/* LEAVE END */

	/* LEAVE V2 START */
	Route::any('/leave/v2',"Member\PayrollController@leaveV2");
	Route::any('/leave/v2/modal_create_leave_type',"Member\PayrollController@modal_create_leave_type");
	Route::any('/leave/v2/modal_create_leave_tempv2',"Member\PayrollController@modal_create_leave_tempv2");
	Route::any('/leave/v2/modal_leave_tag_employeev2/{leave_temp_id}',"Member\PayrollController@modal_leave_tag_employeev2");
	Route::any('/leave/v2/set_leave_tag_employeev2',"Member\PayrollController@set_leave_employee_tagv2");
	Route::any('/leave/v2/remove_leave_tag_employeev2',"Member\PayrollController@remove_leave_tag_employeev2");
	Route::any('/leave/v2/get_leave_tag_employeev2',"Member\PayrollController@get_leave_tag_employeev2");
	Route::any('/leave/v2/reload_leave_employeev2',"Member\PayrollController@reload_leave_employeev2");
	Route::any('/leave/v2/modal_save_leave_temp_v2',"Member\PayrollController@modal_save_leave_temp_v2");
	//scheduling leave
	Route::any('/leave_schedule/v2/save_schedule_leave_tagv2','Member\PayrollController@save_schedule_leave_tagv2');
	Route::any('/leave_schedule/v2/leave_schedule_tag_employeev2/{leave_temp_id}/{leave_pay}','Member\PayrollController@leave_schedule_tag_employeev2');
	Route::any('/leave_schedule/v2/session_tag_leavev2','Member\PayrollController@session_tag_leavev2');
	Route::any('/leave_schedule/v2/ajax_schedule_leave_tag_employeev2','Member\PayrollController@ajax_schedule_leave_tag_employeev2');	
	Route::any('/leave_schedule/v2/unset_session_leave_tagv2','Member\PayrollController@unset_session_leave_tagv2')
	;
	Route::any('/leave_schedule/v2/get_session_leave_tagv2','Member\PayrollController@get_session_leave_tagv2');
	Route::any('/leave_schedule/v2/modal_leave_scheduling',"Member\PayrollController@modal_leave_scheduling");
	Route::any('/leave/v2/modal_leave_action/{payroll_leave_employee_id}/{action}/{remaining_leave}',"Member\PayrollController@modal_leave_action");
	Route::any('/leave/v2/reset_leave_schedulev2',"Member\PayrollController@reset_leave_schedulev2");
	Route::any('/leave/v2/reset_and_accumulate_leave_schedulev2',"Member\PayrollController@reset_and_accumulate_leave_schedulev2");
	Route::any('/leave/v2/convert_to_cash_leave_schedulev2',
		"Member\PayrollController@convert_to_cash_leave_schedulev2");
	Route::any('/leave/v2/reset_leave_schedule_history',"Member\PayrollController@reset_leave_schedule_history");
	Route::any('/leave/v2/archived_leave_tempv2',
		"Member\PayrollController@archived_leave_tempv2");
	Route::any('/leave/v2/restore_leave_tempv2',
		"Member\PayrollController@restore_leave_tempv2");
	Route::any('/leave/v2/archived_leave_history',
		"Member\PayrollController@archived_leave_history");
	Route::any('/leave/v2/archived_whole_leave_tempv2',
		"Member\PayrollController@archived_whole_leave_tempv2");
	Route::any('/leave/v2/restore_whole_leave_tempv2',
		"Member\PayrollController@restore_whole_leave_tempv2");
	//end schedule
	//reporting leave
	Route::any('/reports/leavev2_reports',
		"Member\PayrollController@leavev2_reports");
	Route::any('/leave/v2/modal_monthly_leave_report',
		"Member\PayrollController@modal_monthly_leave_report");
	Route::any('/leave/v2/monthly_leave_report_excel/{date_start}/{date_end}/{company}',
		"Member\PayrollController@monthly_leave_report_excel");
	Route::any('/leave/v2/monthly_leave_report_filter',
		"Member\PayrollController@monthly_leave_report_filter");
	Route::any('/leave/v2/modal_remaining_leave_report',
		"Member\PayrollController@modal_remaining_leave_report");
	Route::any('/leave/v2/remaining_leave_report_excel/{date_start}/{date_end}/{company}',
		"Member\PayrollController@remaining_leave_report_excel");
	Route::any('/leave/v2/modal_pay_leave_report',
		"Member\PayrollController@modal_pay_leave_report");
	Route::any('/leave/v2/pay_leave_report_excel/{date_start}/{date_end}/{company}',
		"Member\PayrollController@pay_leave_report_excel");
	Route::any('/leave/v2/modal_withoutpay_leave_report',
		"Member\PayrollController@modal_withoutpay_leave_report");
	Route::any('/leave/v2/withoutpay_leave_report_excel/{date_start}/{date_end}/{company}',
		"Member\PayrollController@withoutpay_leave_report_excel");
	Route::any('/leave/v2/modal_leave_action_report',
		"Member\PayrollController@modal_leave_action_report");
	Route::any('/leave/v2/leave_action_report_excel/{date_start}/{date_end}/{company}',
		"Member\PayrollController@leave_action_report_excel");
	Route::any('/leave/v2/modal_leave_annual_report',
		"Member\PayrollController@modal_leave_annual_report");
	Route::any('/leave/v2/leave_annual_export/{company}',
		"Member\PayrollController@leave_annual_export");
	
	//end reporting

	Route::any('/leave/v2/modal_view_leave_employee/{payroll_leave_temp_id}/',"Member\PayrollController@modal_view_leave_employee");
	Route::any('/leave/v2/modal_edit_leave_tempv2/{payroll_leave_temp_id}',
		"Member\PayrollController@modal_edit_leave_tempv2");
	Route::any('/leave/v2/update_leave_tempv2',"Member\PayrollController@update_leave_tempv2");
	Route::any('/leave/v2/modal_leave_history',
		"Member\PayrollController@modal_leave_history");
	Route::any('/leave/v2/modal_save_leave_type',
		"Member\PayrollController@modal_save_leave_type");

	/* LEAVE V2 END */

	/* PAYROLL GROUP START */
	Route::any('/payroll_group',"Member\PayrollController@payroll_group");
	Route::any('/payroll_group/modal_create_payroll_group',"Member\PayrollController@modal_create_payroll_group");
	Route::any('/payroll_group/modal_save_payroll_group',"Member\PayrollController@modal_save_payroll_group");
	Route::any('/payroll_group/modal_edit_payroll_group/{id}',"Member\PayrollController@modal_edit_payroll_group");
	Route::any('/payroll_group/modal_update_payroll_group',"Member\PayrollController@modal_update_payroll_group");
	Route::any('/payroll_group/confirm_archived_payroll_group/{archived}/{group_id}',"Member\PayrollController@confirm_archived_payroll_group");
	Route::any('/payroll_group/archived_payroll_group',"Member\PayrollController@archived_payroll_group");

    Route::any('/payroll_group/modal_tag_payroll_group_employee',"Member\PayrollController@modal_tag_payroll_group_employee");
    Route::any('/payroll_group/get_payroll_tag_employee',"Member\PayrollController@get_payroll_tag_employee");
    Route::any('/payroll_group/set_tag_payroll_group_employee',"Member\PayrollController@set_tag_payroll_group_employee");
    Route::any('/payroll_group/remove_payroll_tag_employee',"Member\PayrollController@remove_payroll_tag_employee");

	Route::any('/leave/v2/remove_employee_payroll_group_tag',"Member\PayrollController@remove_employee_payroll_group_tag");
	Route::any('/payroll_group/load_payroll_employee_tag',"Member\PayrollController@load_payroll_employee_tag");
	Route::any('/payroll_group/modal_payroll_tag_edit',"Member\PayrollController@modal_payroll_tag_edit");
	Route::any('/payroll_group/set_tag_payroll_group_employee_edit',"Member\PayrollController@set_tag_payroll_group_employee_edit");
	Route::any('/payroll_group/get_payroll_tag_employee_edit',"Member\PayrollController@get_payroll_tag_employee_edit");

	
	/* PAYROLL GROUP END */

	/* PAYROLL JOURNAL SETTINGS START */
	Route::any('/payroll_jouarnal',"Member\PayrollController@payroll_jouarnal");
	Route::any('/payroll_jouarnal/modal_create_journal_tag',"Member\PayrollController@modal_create_journal_tag");
	Route::any('/payroll_jouarnal/create_journal_tag',"Member\PayrollController@create_journal_tag");
	Route::any('/payroll_jouarnal/modal_edit_journal_tag/{id}',"Member\PayrollController@modal_edit_journal_tag");
	Route::any('/payroll_jouarnal/modal_confimr_del_journal_tag/{id}',"Member\PayrollController@modal_confimr_del_journal_tag");
	Route::any('/payroll_jouarnal/update_payroll_journal_tag',"Member\PayrollController@update_payroll_journal_tag");
	Route::any('/payroll_jouarnal/del_journal_tag',"Member\PayrollController@del_journal_tag");
	Route::any('/payroll_jouarnal/relaod_payroll_journal_sel',"Member\PayrollController@relaod_payroll_journal_sel");
	/* PAYROLL JOURNAL SETTINGS END */


	/* PAYROLL CUSTOM PAYSLIP START */
	Route::any('/custom_payslip',"Member\PayrollController@custom_payslip");
	Route::any('/custom_payslip/modal_create_payslip',"Member\PayrollController@modal_create_payslip");
	Route::any('/custom_payslip/modal_create_paper_size',"Member\PayrollController@modal_create_paper_size");
	Route::any('/custom_payslip/modal_save_paper_size',"Member\PayrollController@modal_save_paper_size");
	Route::any('/custom_payslip/save_custom_payslip',"Member\PayrollController@save_custom_payslip");
	Route::any('/custom_payslip/custom_payslip_show/{id}',"Member\PayrollController@custom_payslip_show");
	Route::any('/custom_payslip/custom_payslip_show_archived/{id}',"Member\PayrollController@custom_payslip_show_archived");
	Route::any('/custom_payslip/modal_edit_payslip/{id}',"Member\PayrollController@modal_edit_payslip");
	Route::any('/custom_payslip/modal_archive_payslip/{archived}/{id}',"Member\PayrollController@modal_archive_payslip");
	Route::any('/custom_payslip/archive_payslip',"Member\PayrollController@archive_payslip");
	Route::any('/custom_payslip/modal_update_payslip',"Member\PayrollController@modal_update_payslip");
	Route::any('/custom_payslip/payslip_use_change',"Member\PayrollController@payslip_use_change");
	Route::any('/custom_payslip/modal_view_payslip_option',"Member\PayrollController@modal_view_payslip_option");
	Route::any('/custom_payslip/save_payslip_options',"Member\PayrollController@save_payslip_options");
	/* PAYROLL CUSTOM PAYSLIP END */

	/* PAYROLL PERIOD START */
	Route::any('/payroll_period_list','Member\PayrollController@payroll_period_list');
	Route::any('/payroll_period_list/modal_create_payroll_period','Member\PayrollController@modal_create_payroll_period');
	Route::any('/payroll_period_list/modal_save_payroll_period','Member\PayrollController@modal_save_payroll_period');

	Route::any('/payroll_period_list/modal_schedule_employee_shift','Member\PayrollController@modal_schedule_employee_shift');
	
	Route::any('/payroll_period_list/shift_template_refence','Member\PayrollController@shift_template_refence');
	Route::any('/payroll_period_list/ajax_employee_schedule','Member\PayrollController@ajax_employee_schedule');
	Route::any('/payroll_period_list/save_shift_per_employee','Member\PayrollController@save_shift_per_employee');

	Route::any('/payroll_period_list/modal_archive_period/{archived}/{id}','Member\PayrollController@modal_archive_period');
	Route::any('/payroll_period_list/archive_period','Member\PayrollController@archive_period');
	Route::any('/payroll_period_list/modal_edit_period/{id}','Member\PayrollController@modal_edit_period');
	Route::any('/payroll_period_list/modal_update_period','Member\PayrollController@modal_update_period');
	/* PAYROLL PERIOD END */

	/* HOLIDAY DEFAULT START */
	Route::any('/holiday_default/modal_create_holiday_default',"Member\PayrollController@modal_create_holiday_default");
	Route::any('/holiday_default/modal_save_holiday_default',"Member\PayrollController@modal_save_holiday_default");	
	Route::any('/holiday_default/modal_edit_holiday_default/{id}',"Member\PayrollController@modal_edit_holiday_default");
	Route::any('/holiday_default/modal_archive_holiday_default/{archived}/{id}',"Member\PayrollController@modal_archive_holiday_default");
	Route::any('/holiday_default/archive_holiday_default',"Member\PayrollController@archive_holiday_default");
	Route::any('/holiday_default/update_holiday_default',"Member\PayrollController@update_holiday_default");

	/* HOLIDAY END */



	/* SHIFT TEMPLATE START */
     Route::any('/shift_template',"Member\PayrollController@shift_template");
     Route::any('/shift_template/modal_create_shift_template',"Member\PayrollController@modal_create_shift_template");
     Route::any('/shift_template/modal_update_shift_template',"Member\PayrollController@modal_update_shift_template");
     Route::any('/shift_template/modal_save_shift_template',"Member\PayrollController@modal_save_shift_template");
     Route::any('/shift_template/modal_view_shift_template/{id}',"Member\PayrollController@modal_view_shift_template");
     Route::any('/shift_template/modal_update_shift_template',"Member\PayrollController@modal_update_shift_template");
     Route::any('/shift_template/modal_archive_shift_template/{archived}/{id}',"Member\PayrollController@modal_archive_shift_template");
     Route::any('/shift_template/archive_shift_template',"Member\PayrollController@archive_shift_template");



	Route::any('/shift_template/modal_tag_add_shift_employee',"Member\PayrollController@modal_tag_add_shift_employee");
	Route::any('/shift_template/set_add_tag_shift_employee',"Member\PayrollController@set_add_tag_shift_employee");
	Route::any('/shift_template/get_shift_add_tag_employee',"Member\PayrollController@get_shift_add_tag_employee");

	Route::any('/shift_template/modal_tag_shift_employee',"Member\PayrollController@modal_tag_shift_employee");
    Route::any('/shift_template/load_shift_employee_tag',"Member\PayrollController@load_shift_employee_tag");
    Route::any('/leave/v2/remove_employee_shift_group_tag',"Member\PayrollController@remove_employee_shift_group_tag");

    Route::any('/shift_template/modal_tag_shift_employee',"Member\PayrollController@modal_tag_shift_employee");
    Route::any('/shift_template/get_shift_tag_employee',"Member\PayrollController@get_shift_tag_employee");
    Route::any('/shift_template/set_tag_shift_employee',"Member\PayrollController@set_tag_shift_employee");
    Route::any('/shift_template/remove_shift_tag_employee',"Member\PayrollController@remove_shift_tag_employee");
     /* SHIFT TEMPLATE END */

     /* SHIFT TEMPLATE IMPORT START */
	Route::any('/shift_template/modal_shift_import_template','Member\PayrollController@modal_shift_import_template');
	Route::any('/shift_template/company_template','Member\PayrollController@get_template123');
	Route::any('/shift_template/import_modal_shift_global','Member\PayrollController@import_modal_shift_global');
    


	/* BIO METRICS IMPORT START */
	Route::any('/import_bio/modal_biometrics','Member\Payroll_BioImportController@modal_biometrics');

		
	/* dmsph start */
	Route::any('/import_bio/import_global','Member\Payroll_BioImportController@import_global');
	Route::any('/import_bio/template_global','Member\Payroll_BioImportController@template_global');
	/* dmsph end */

	/* BIO METRICS IMPORT END */

	/* CALENDAR LEAVE START */
	Route::any('/leave_schedule','Member\PayrollController@leave_schedule');	
	Route::any('/leave_schedule/modal_create_leave_schedule','Member\PayrollController@modal_create_leave_schedule');	
	Route::any('/leave_schedule/leave_schedule_tag_employee/{id}','Member\PayrollController@leave_schedule_tag_employee');
	Route::any('/leave_schedule/ajax_shecdule_leave_tag_employee','Member\PayrollController@ajax_shecdule_leave_tag_employee');	
	Route::any('/leave_schedule/session_tag_leave','Member\PayrollController@session_tag_leave');	
	Route::any('/leave_schedule/get_session_leave_tag','Member\PayrollController@get_session_leave_tag');
	Route::any('/leave_schedule/unset_session_leave_tag','Member\PayrollController@unset_session_leave_tag');
	Route::any('/leave_schedule/save_schedule_leave_tag','Member\PayrollController@save_schedule_leave_tag');

	Route::any('/leave_schedule/delete_confirm_schedule_leave/{id}','Member\PayrollController@delete_confirm_schedule_leave')
	;
	Route::any('/leave_schedule/delete_schedule_leave','Member\PayrollController@delete_schedule_leave')
	;
	/* CALDENDAR LEAVE END */


	/* PAYORLL TIME KEEPING START */
	Route::any('/time_keeping','Member\PayrollController@time_keeping');
	Route::any('/time_keeping/table/{payroll_company_id}','Member\PayrollController@time_keeping_load_table');
	Route::any('/time_keeping/modal_generate_period','Member\PayrollController@modal_generate_period');
	Route::any('/time_keeping/generate_period','Member\PayrollController@generate_period');
	Route::any('/time_keeping/company_period/{id}','Member\PayrollController@company_period');
	Route::any('/time_keeping/company_period/delete/{id}','Member\PayrollController@company_period_delete');
	/* PAYROLL TIME KEEPING END */


	/* NO RECORDS FOUND */
	Route::any('/no_records','Member\PayrollController@no_records');
	


	/* PAYROLL PROCESS START */
	Route::any('/process_payroll/{period_company_id}','Member\PayrollProcessController@index');
	Route::any('/unprocess_payroll/{period_company_id}','Member\PayrollProcessController@unprocess');
	Route::any('/process_payroll/table/{period_company_id}','Member\PayrollProcessController@index_table');
	Route::any('/process_payroll/modal_view_summary/{period_company_id}','Member\PayrollProcessController@modal_view_summary');
	Route::any('/process_payroll/income_summary/timesheet/{period_id}/{employee_id}','Member\PayrollProcessController@income_summary_timesheet_v3');
	Route::any('/process_payroll/income_summary/timesheet_view_pdf/{period_id}/{employee_id}','Member\PayrollProcessController@income_summary_timesheet_v3_view_pdf');
	
	Route::any('/process_payroll/modal_approved_summary/{period_company_id}','Member\PayrollProcessController@modal_approved_summary');


	Route::any('/payroll_process_module','Member\PayrollController@payroll_process_module');


	// Route::any('/process_payroll/table/{period_company_id}','Member\PayrollProcessController@view_');
	// Route::any('/payroll_process','Member\PayrollController@payroll_process');
	// Route::any('/payroll_process/modal_create_process','Member\PayrollController@modal_create_process');
	// Route::any('/payroll_process/ajax_load_payroll_period','Member\PayrollController@ajax_load_payroll_period');
	// Route::any('/payroll_process/ajax_payroll_company_period','Member\PayrollController@ajax_payroll_company_period');
	// Route::any('/payroll_process/process_payroll','Member\PayrollController@process_payroll');
	// Route::any('/payroll_process/payroll_compute_brk_unsaved/{employee_id}/{period_company_id}','Member\PayrollController@payroll_compute_brk_unsaved');

	// Route::any('/payroll_process/payroll_explain_computation/{employee_id}/{period_company_id}','Member\PayrollController@payroll_explain_computation');
	
	// Route::any('/payroll_process/modal_create_payroll_adjustment/{payroll_employee_id}/{payroll_period_company_id}','Member\PayrollController@modal_create_payroll_adjustment');
	// Route::any('/payroll_process/create_payroll_adjustment','Member\PayrollController@create_payroll_adjustment');
	// Route::any('/payroll_process/confirm_remove_adjustment/{id}','Member\PayrollController@confirm_remove_adjustment');
	// Route::any('/payroll_process/remove_adjustment','Member\PayrollController@remove_adjustment');
	// Route::any('/payroll_process/confirm_action_payroll/{action}/{id}','Member\PayrollController@confirm_action_payroll');
	// Route::any('/payroll_process/action_payroll','Member\PayrollController@action_payroll');
	// Route::any('/payroll_process/confirm_cancel_payroll/{action}/{id}','Member\PayrollController@confirm_cancel_payroll');

	// Route::any('/payroll_process/modal_13_month/{id}/{period_id}','Member\PayrollController@modal_13_month');
	// Route::any('/payroll_process/modal_unused_leave/{id}/{period_id}','Member\PayrollController@modal_unused_leave');
	// Route::any('/payroll_process/modal_save_process_leave','Member\PayrollController@modal_save_process_leave');
	// Route::any('/payroll_process/modal_submit_13_month','Member\PayrollController@modal_submit_13_month');
	/* PAYROLL PROCESS END */


	/* PAYROLL SUMMARY JOURNAL ENTRIES */
	Route::get('/journal_entry','Member\PayrollController@journal_entry');

	/* END */

	/* PAYROLL REGISTER START */
	Route::any('/payroll_register','Member\PayrollController@payroll_register');
	Route::any('/payroll_register/breakdown_uncompute_static/{employee_id}/{period_company_id}','Member\PayrollController@breakdown_uncompute_static');
	/* PAYROLL REGISTER END */


	/* PAYROLL POSTED START */
	Route::any('/payroll_post','Member\PayrollController@payroll_post');
	/* PAYROLL POSTED END */

	/* PAYROLL APPROVED START */
	Route::any('/payroll_approved_view','Member\PayrollController@payroll_approved_view');
	Route::any('/payroll_approved_view/approve_payroll','Member\PayrollController@approve_payroll');
	Route::any('/payroll_approved_view/payroll_approved_company/{id}','Member\PayrollController@payroll_approved_company');
	Route::any('/payroll_approved_view/payroll_record_by_id/{id}','Member\PayrollController@payroll_record_by_id');
	Route::any('/payroll_approved_view/genereate_payslip/{id}','Member\PayrollController@genereate_payslip');

	Route::any('/payroll_approved_view/generate_payslip_v2/{id}','Member\PayrollPayslipController@index');



	/* PAYROLLL APPROVED END */

	/* PAYROLL NOTES  START*/
	Route::any('/modal_payroll_notes/{id}','Member\PayrollController@modal_payroll_notes');
	/* PAYROLL NOTES END */

	/* PAYROLL REPORTS START */
	Route::any('/payroll_reports','Member\PayrollController@payroll_reports');
	Route::any('/payroll_reports/modal_create_reports','Member\PayrollController@modal_create_reports');
	Route::any('/payroll_reports/save_custom_reports','Member\PayrollController@save_custom_reports');
	Route::any('/payroll_reports/modal_archive_reports/{archived}/{id}','Member\PayrollController@modal_archive_reports');
	Route::any('/payroll_reports/archive_report','Member\PayrollController@archive_report');
	Route::any('/payroll_reports/modal_edit_reports/{id}','Member\PayrollController@modal_edit_payroll_reports');
	Route::any('/payroll_reports/update_payroll_reports','Member\PayrollController@update_payroll_reports');
	Route::any('/payroll_reports/view_report/{id}','Member\PayrollController@view_report');
	Route::any('/payroll_reports/download_excel_report','Member\PayrollController@download_excel_report');
	Route::any('/payroll_reports/date_change_report','Member\PayrollController@date_change_report');
	/* PAYROLL REPORTS END */


	/* GENERATE BANK UPLOAD START */
	Route::any('/generate_bank','Member\PayrollController@generate_bank');
	Route::any('/modal_generate_bank/{id}','Member\PayrollController@modal_generate_bank');
	/* GENERATE BANK UPLOAD END */

	Route::any('/banking/{period_company_id}','Member\PayrollBankingController@index');
	Route::any('/banking/{period_company_id}/download','Member\PayrollBankingController@download');


	/* SHIFT START */
	Route::any('/shift_group','Member\PayrollController@shift_group');
	/* SHIFT END */

	/* PAYROLL 13TH MONTH PAY REPORT */
	Route::get('/report_13th_month_pay','Member\PayrollController@report_13th_month_pay');
	Route::get('/report_13th_month_pay/excel_export','Member\PayrollController@report_13th_month_pay_excel_export');

	/*START payroll biometrics*/
	
	//system controllers
	Route::any('/payroll_biometric','Member\PayrollBiometricSystemController@index');
	Route::any('/payroll_biometric/biometric_record_table','Member\PayrollBiometricSystemController@biometric_record_table');
	Route::any('/payroll_biometric/biometric_import_record','Member\PayrollBiometricSystemController@biometric_import_record');
	
	Route::any('/payroll_biometric/modal_import_biometric','Member\PayrollBiometricSystemController@modal_import_biometric');
	Route::any('/payroll_biometric/biometric_import_record','Member\PayrollBiometricSystemController@biometric_import_record');
	//software controllers
	Route::post('/biometrics/save_data','Member\PayrollBiometricsController@save_data');
	Route::any('/biometrics/sample','Member\PayrollBiometricsController@sample');
	/*END payroll biometrics*/

	/*START payroll approve database manipulation*/
	Route::any('/payroll_time_keeping_approve_manipulation/time_breakdown/{period_company_id}/{employee_id}','Member\PayrollTimeKeepingApproveManipulation@time_breakdown');
	/*END payroll approve database manipulation*/

	/*START Admin dashboard*/
	Route::any('/payroll_admin_dashboard/employee_approver','Member\PayrollAdminDashboard@employee_approver');
	Route::any('/payroll_admin_dashboard/create_approver','Member\PayrollAdminDashboard@create_approver');
	Route::any('/payroll_admin_dashboard/create_approver_table','Member\PayrollAdminDashboard@create_approver_table');
	Route::any('/payroll_admin_dashboard/save_approver','Member\PayrollAdminDashboard@save_approver');
	Route::any('/payroll_admin_dashboard/edit_approver/{approver_id}','Member\PayrollAdminDashboard@modal_edit_approver');
	Route::any('/payroll_admin_dashboard/save_edit_approver','Member\PayrollAdminDashboard@save_edit_approver');
	Route::any('/payroll_admin_dashboard/delete_approver/{approver_id}','Member\PayrollAdminDashboard@modal_delete_approver');
	
	Route::any('/payroll_admin_dashboard/group_approver','Member\PayrollAdminDashboard@group_approver');
	Route::any('/payroll_admin_dashboard/modal_create_group_approver','Member\PayrollAdminDashboard@modal_create_group_approver');
	Route::any('/payroll_admin_dashboard/get_employee_approver_by_level','Member\PayrollAdminDashboard@get_employee_approver_by_level');
	Route::any('/payroll_admin_dashboard/save_approver_group','Member\PayrollAdminDashboard@save_approver_group');
	Route::any('/payroll_admin_dashboard/modal_edit_group_approver/{approver_group_id}','Member\PayrollAdminDashboard@modal_edit_group_approver');
	Route::any('payroll_admin_dashboard/save_edit_group_approver','Member\PayrollAdminDashboard@save_edit_group_approver');
	Route::any('payroll_admin_dashboard/modal_archive_group_approver/{approver_group_id}','Member\PayrollAdminDashboard@modal_archive_group_approver');

	//access
	Route::any('payroll_admin_dashboard/access_level','Member\PayrollAdminDashboard@access_level');
	Route::any('payroll_admin_dashboard/add_access_group','Member\PayrollAdminDashboard@add_access_group');
	Route::any('payroll_admin_dashboard/save_access_group','Member\PayrollAdminDashboard@save_access_group');
	/*END Admin dashboard*/
	
});