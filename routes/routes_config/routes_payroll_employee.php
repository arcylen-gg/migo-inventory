<?php

Route::any('/employee_login', 'Login\EmployeeLoginController@employee_login');
Route::any('/employee_logout', 'Login\EmployeeLoginController@employee_logout');

Route::any('authorized_access', 'Member\PayrollEmployee\EmployeeController@authorized_access');
Route::any('employee', 'Member\PayrollEmployee\EmployeeController@employee');

Route::any('employee_profile', 'Member\PayrollEmployee\EmployeeController@employee_profile');
Route::any('edit_employee_profile', 'Member\PayrollEmployee\EmployeeController@edit_employee_profile');
Route::any('update_employee_profile', 'Member\PayrollEmployee\EmployeeController@update_employee_profile');

/*START LEAVE MANAGEMENT*/
Route::any('employee_leave_management', 'Member\PayrollEmployee\LeaveController@employee_leave_management');
Route::any('employee_leave_application', 'Member\PayrollEmployee\LeaveController@employee_leave_application');
Route::any('employee_summary_of_leave', 'Member\PayrollEmployee\LeaveController@employee_summary_of_leave');
Route::any('create_employee_leave', 'Member\PayrollEmployee\LeaveController@create_employee_leave');
Route::any('/leave/save_leave', 'Member\PayrollEmployee\LeaveController@save_leave');

Route::any('/leave/ajax_load_pending_leave', 'Member\PayrollEmployee\LeaveController@ajax_load_pending_leave');
Route::any('/leave/ajax_load_approved_leave', 'Member\PayrollEmployee\LeaveController@ajax_load_approved_leave');
Route::any('/leave/ajax_load_rejected_leave', 'Member\PayrollEmployee\LeaveController@ajax_load_rejected_leave');
Route::any('/leave/ajax_load_canceled_leave', 'Member\PayrollEmployee\LeaveController@ajax_load_canceled_leave');

Route::any('employee_request_leave_view/{request_id}', 'Member\PayrollEmployee\LeaveController@employee_request_leave_view');
Route::any('employee_request_leave_cancel/{request_id}', 'Member\PayrollEmployee\LeaveController@employee_request_leave_cancel');
Route::any('employee_request_leave_export_pdf/{request_id}', 'Member\PayrollEmployee\LeaveController@employee_request_leave_export_pdf');


Route::any('authorized_access_leave', 'Member\PayrollEmployee\LeaveController@authorized_access_leave');
Route::any('authorized_access_leave/view_leave_request/{request_id}', 'Member\PayrollEmployee\LeaveController@view_leave_request');
Route::any('authorized_access_leave/approve_leave_request/{request_id}', 'Member\PayrollEmployee\LeaveController@approve_leave_request');
Route::any('authorized_access_leave/reject_leave_request/{request_id}', 'Member\PayrollEmployee\LeaveController@reject_leave_request');

Route::any('/get_leave_hours', 'Member\PayrollEmployee\LeaveController@get_leave_hours');
/*END LEAVE MANAGEMENT*/

Route::any('employee_official_business', 'Member\PayrollEmployee\EmployeeController@employee_official_business');
Route::any('company_details', 'Member\PayrollEmployee\EmployeeController@company_details');

/*Start overtime request and management*/
Route::any('employee_overtime_management', 'Member\PayrollEmployee\EmployeeController@employee_overtime_management');
Route::any('employee_overtime_management_table', 'Member\PayrollEmployee\EmployeeController@employee_overtime_management_table');
Route::any('employee_overtime_application', 'Member\PayrollEmployee\EmployeeController@employee_overtime_application');
Route::any('employee_overtime_view_shift', 'Member\PayrollEmployee\EmployeeController@employee_overtime_view_shift');
Route::any('/get_group_approver_list', 'Member\PayrollEmployee\EmployeeController@get_group_approver_list');
Route::any('employee_request_overtime_save', 'Member\PayrollEmployee\EmployeeController@employee_request_overtime_save');
Route::any('employee_request_overtime_view/{request_id}', 'Member\PayrollEmployee\EmployeeController@employee_request_overtime_view');
Route::any('employee_request_overtime_cancel/{request_id}', 'Member\PayrollEmployee\EmployeeController@employee_request_overtime_cancel');

Route::any('authorized_access_over_time', 'Member\PayrollEmployee\EmployeeController@authorized_access_over_time');
Route::any('authorized_access_over_time/view_overtime_request/{request_id}', 'Member\PayrollEmployee\EmployeeController@view_overtime_request');
Route::any('authorized_access_over_time/approve_overtime_request/{request_id}', 'Member\PayrollEmployee\EmployeeController@approve_overtime_request');
Route::any('authorized_access_over_time/reject_overtime_request/{request_id}', 'Member\PayrollEmployee\EmployeeController@reject_overtime_request');

Route::any('employee_request_overtime_export_pdf/{request_id}', 'Member\PayrollEmployee\EmployeeController@employee_request_overtime_export_pdf');
/*End overtime request and management*/
Route::any('authorized_access_official_business', 'Member\PayrollEmployee\EmployeeController@authorized_access_official_business');


Route::any('authorized_access_approver', 'Member\PayrollEmployee\EmployeeController@authorized_access_approver');

/*Start Request for Payment*/
Route::any('/request_for_payment', 'Member\PayrollEmployee\RequestForPaymentController@request_for_payment');
Route::any('/request_for_payment_table', 'Member\PayrollEmployee\RequestForPaymentController@request_for_payment_table');
Route::any('/modal_rfp_application', 'Member\PayrollEmployee\RequestForPaymentController@modal_rfp_application');
Route::any('/modal_rfp_save', 'Member\PayrollEmployee\RequestForPaymentController@modal_rfp_save');
Route::any('/modal_rfp_save', 'Member\PayrollEmployee\RequestForPaymentController@modal_rfp_save');
Route::any('/rfp_application_view/{id}', 'Member\PayrollEmployee\RequestForPaymentController@rfp_application_view');
Route::any('/rfp_application_cancel/{id}', 'Member\PayrollEmployee\RequestForPaymentController@rfp_application_cancel');

Route::any('/authorized_access_request_for_refund', 'Member\PayrollEmployee\RequestForPaymentController@authorized_access_request_for_refund');
Route::any('/authorized_access_request_for_refund_table', 'Member\PayrollEmployee\RequestForPaymentController@authorized_access_request_for_refund_table');
Route::any('rfp_application_approve/{request_id}', 'Member\PayrollEmployee\RequestForPaymentController@rfp_application_approve');
Route::any('rfp_application_reject/{request_id}', 'Member\PayrollEmployee\RequestForPaymentController@rfp_application_reject');

Route::any('employee_request_rfp_export_pdf/{request_id}', 'Member\PayrollEmployee\RequestForPaymentController@employee_request_rfp_export_pdf');
/*End Request for Payment*/

Route::any('employee_official_business_management', 'Member\PayrollEmployee\EmployeeController@employee_official_business_management');
Route::any('employee_time_keeping', 'Member\PayrollEmployee\EmployeeController@employee_time_keeping');


Route::any('create_employee_overtime', 'Member\PayrollEmployee\EmployeeController@create_employee_overtime');
Route::any('create_employee_official_business', 'Member\PayrollEmployee\EmployeeController@create_employee_official_business');
Route::any('create_employee_approver', 'Member\PayrollEmployee\EmployeeController@create_employee_approver');


Route::any('employee_payslip_pdf/{payroll_period_id}','Member\PayrollEmployee\EmployeeController@employee_payslip_pdf');
Route::any('employee_timesheet/{payroll_period_id}','Member\PayrollEmployee\EmployeeController@employee_timesheet');
Route::any('employee_timesheet_pdf/{payroll_period_id}','Member\PayrollEmployee\EmployeeController@employee_timesheet_pdf');



Route::any('sample', 'Member\PayrollEmployee\EmployeeController@sample');
Route::any('updated_layout', 'Member\PayrollEmployee\EmployeeController@updated_layout');
