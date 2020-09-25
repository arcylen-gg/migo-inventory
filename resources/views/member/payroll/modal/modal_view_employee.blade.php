<form class="global-submit" role="form" action="/member/payroll/employee_list/modal_employee_update" method="POST">
  <input type="hidden" name="source" value="{{ $source }}"/>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title">Employee details</h4>
    <input type="hidden" name="payroll_employee_id" class="payroll_employee_id" value="{{$employee->payroll_employee_id}}">
  </div>
  <div class="modal-body modallarge-body-layout background-white ">
    <div class="modal-body-employee-details">
      <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
      <div class="form-horizontal">
        <div class="form-group">
          <div class="col-md-6">
            <div class="col-md-2 padding-lr-1">
              <small>Title</small>
              <input type="text" name="payroll_employee_title_name" class="form-control auto-name title margin-l-0" value="{{$employee->payroll_employee_title_name}}">
            </div>
            <div class="col-md-3 padding-lr-1">
              <small>First name</small>
              <input type="text" name="payroll_employee_first_name" class="form-control auto-name first_name" value="{{$employee->payroll_employee_first_name}}" required/>
            </div>
            <div class="col-md-3 padding-lr-1">
              <small>Middle name</small>
              <input type="text" name="payroll_employee_middle_name" class="form-control auto-name middle_name" value="{{$employee->payroll_employee_middle_name}}">
            </div>
            <div class="col-md-3 padding-lr-1">
              <small>Last name</small>
              <input type="text" name="payroll_employee_last_name" class="form-control auto-name last_name" required value="{{$employee->payroll_employee_last_name}}">
            </div>
            <div class="col-md-1 padding-lr-1">
              <small>Suffix</small>
              <input type="text" name="payroll_employee_suffix_name" class="form-control auto-name suffix" value="{{$employee->payroll_employee_suffix_name}}">
            </div>
          </div>
          <div class="col-md-6">
            <div class="col-md-4 padding-lr-1">
              <small>Employee number</small>
              <input type="text" name="payroll_employee_number" class="form-control" required value="{{$employee->payroll_employee_number}}">
            </div>
            <div class="col-md-4 padding-lr-1">
              <small>Biometric number</small>
              <input type="text" name="payroll_employee_biometric_number" class="form-control" value="{{$employee->payroll_employee_biometric_number}}">
            </div>
            <div class="col-md-4 padding-lr-1">
              <small>ATM No.</small>
              <input type="text" name="payroll_employee_atm_number" class="form-control" value="{{$employee->payroll_employee_atm_number}}">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-6">
            <small>Company</small>
            <select class="form-control" name="payroll_employee_company_id" required>
              <option value="">Select Company</option>
              @foreach($_company as $company)
              <option value="{{$company['company']->payroll_company_id}}" {{$company['company']->payroll_company_id == $employee->payroll_employee_company_id ? 'selected="selected"':''}}>{{$company['company']->payroll_company_name}}</option> 
                @foreach($company['branch'] as $branch)
                <option value="{{$branch->payroll_company_id}}" {{$branch->payroll_company_id == $employee->payroll_employee_company_id ? 'selected="selected"':''}}>&nbsp;&nbsp;• {{$branch->payroll_company_name}}</option>
                @endforeach
              @endforeach
             
            </select>
          </div>
          <div class="col-md-6">
            <div class="col-md-6 padding-lr-1">
              <small>Contact</small>
              <input type="text" name="payroll_employee_contact" class="form-control" value="{{$employee->payroll_employee_contact}}">
            </div>
            <div class="col-md-6 padding-lr-1">
              <small>Email</small>
              <input type="text" name="payroll_employee_email" class="form-control" value="{{$employee->payroll_employee_email}}">
            </div>
            
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-6">
            <small>Branch Location</small>
            <select class="form-control" name="branch_location_id">
              <option value="0">Select Branch</option>
              @foreach($_branch as $branch)
              <option value="{{$branch->branch_location_id}}" {{$employee->branch_location_id == $branch->branch_location_id ? 'selected="selected"':''}}>{{$branch->branch_location_name}}</option>
              @endforeach
            </select>
          </div>
          
          <div class="col-md-6">
            <div class="col-md-6 padding-lr-1">
              <small>Gender</small>
              <select class="form-control" name="payroll_employee_gender">
                <option value="Male" {{$employee->payroll_employee_gender == 'Male' ? 'selected="selected"':''}}>Male</option>
                <option value="Female" {{$employee->payroll_employee_gender == 'Female' ? 'selected="selected"':''}}>Female</option>
              </select>
            </div>
            <div class="col-md-6 padding-lr-1">
              <small>Birthdate</small>
              <input type="text" name="payroll_employee_birthdate" class="form-control indent-13 datepicker" value="{{date('m/d/Y', strtotime($employee->payroll_employee_birthdate))}}">
              <i class="fa fa-calendar pos-absolute top-30 margin-left-6 color-dark-gray" aria-hidden="true"></i>
            </div>
            
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-6">
            <small><b>Print on as check as</b></small>&nbsp;
            <div class="checkbox display-inline-block"><label><input type="checkbox" name="chck_print_on_as" class="checkbox-toggle-rev check-print-name-as" data-target=".display-name-check" checked>Use display name</label></div>
            <input type="text" name="payroll_employee_display_name" class="form-control display-name-check" value="{{$employee->payroll_employee_display_name}}">
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            <ul class="nav nav-tabs nav-tabs-custom">
              <li class="active"><a data-toggle="tab" href="#address">Address</a></li>
              <li><a data-toggle="tab" href="#company-details">Company Details</a></li>
              <li><a data-toggle="tab" href="#government-contribution">Government</a></li>
              @if($access_salary_detail==1)
              <li><a data-toggle="tab" href="#salary-details">Salary Details</a></li>
              @endif
              <li><a data-toggle="tab" href="#requirements">Requirements</a></li>
              <li><a data-toggle="tab" href="#dependents">Dependents</a></li>
              <li><a data-toggle="tab" href="#shift-schedule">Shift</a></li>
              <li><a data-toggle="tab" href="#other">Other</a></li>
            </ul>
            
            <div class="tab-content tab-content-custom">
              <div id="address" class="tab-pane fade in active">
               
                <div class="form-horizontal">
                  <div class="form-group">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="col-md-12">
                          <small>Street</small>
                          <textarea name="payroll_employee_street" rows="2" class="form-control textarea-expand" placeholder="Street">{{$employee->payroll_employee_street}}</textarea>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <small>City/Town</small>
                          <input type="text" name="payroll_employee_city" class="form-control" placeholder="City/Town" value="{{$employee->payroll_employee_city}}">
                        </div>
                        <div class="col-md-6">
                          <small>State</small>
                          <input type="text" name="payroll_employee_state" class="form-control" placeholder="State" value="{{$employee->payroll_employee_state}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <small>Zip Code</small>
                          <input type="text" name="payroll_employee_zipcode" class="form-control" placeholder="Zip code" value="{{$employee->payroll_employee_zipcode}}">
                        </div>
                        <div class="col-md-6">
                          <small>Country</small>
                          <select class="form-control" name="payroll_employee_country">
                            @foreach($_country as $country)
                            <option value="{{$country->country_id}}" {{$country->country_id == $employee->payroll_employee_country ? 'selected="selected"' : ''}}>{{$country->country_name}}</option>
                            @endforeach
                          </select>
                         
                        </div>
                      </div>
                    </div>
                   
                  </div>
                </div>
              </div>
              <div id="company-details" class="tab-pane fade ">
                <input type= "hidden" name="payroll_employee_contract_id" value="{{isset($contract->payroll_employee_contract_id) ? $contract->payroll_employee_contract_id : ''}}">
                <div class="form-horizontal">
                  <div class="form-group">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                          <small>Department</small>
                          <select class="form-control" required name="payroll_department_id" disabled>
                            <option value="">Select Department</option>
                            @foreach($_department as $department)
                            <option value="{{$department->payroll_department_id}}" {{isset($contract->payroll_department_id)  ? ($contract->payroll_department_id == $department->payroll_department_id ? 'selected="selected"':'') : ''}}>{{$department->payroll_department_name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-12">
                          <small>Job Title</small>
                          <select class="form-control" required name="payroll_jobtitle_id" disabled>
                            <option value="">Select Job Title</option>
                            @foreach($_jobtitle as $jobtitle)
                            <option value="{{$jobtitle->payroll_jobtitle_id}}" {{isset($contract->payroll_jobtitle_id) ? ($jobtitle->payroll_jobtitle_id == $contract->payroll_jobtitle_id ? 'selected="selected"':'') : ''}}>{{$jobtitle->payroll_jobtitle_name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-6 padding-r-1">
                          <small>Date Hired</small>
                          <input type="text" name="payroll_employee_contract_date_hired" class="form-control indent-13 datepicker" value="{{isset($contract->payroll_employee_contract_date_hired) ? ($contract->payroll_employee_contract_date_hired != '0000-00-00' ?date('m/d/Y', strtotime($contract->payroll_employee_contract_date_hired)) : '') : ''}}" disabled>
                          <i class="fa fa-calendar pos-absolute top-30 margin-left-6 color-dark-gray" aria-hidden="true"></i>
                        </div>
                        <div class="col-md-6 padding-l-1">
                          <small>Date End</small>
                          <input type="text" name="payroll_employee_contract_date_end" class="form-control indent-13 datepicker" value="{{isset($contract->payroll_employee_contract_date_end) ? ($contract->payroll_employee_contract_date_end != '0000-00-00' ? date('m/d/Y', strtotime($contract->payroll_employee_contract_date_end)) : '') : ''}}" disabled>
                          <i class="fa fa-calendar pos-absolute top-30 margin-left-6 color-dark-gray" aria-hidden="true"></i>
                        </div>
                        
                      </div>
                      <div class="form-group">
                        <div class="col-md-6 padding-r-1">
                          <small>Payroll Group</small>
                          <select class="form-control" name="payroll_group_id" required disabled>
                            <option value="">Select Group</option>
                            @foreach($_group as $group)
                            <option value="{{$group->payroll_group_id}}" {{isset($contract->payroll_group_id) ? ($contract->payroll_group_id == $group->payroll_group_id ? 'selected="selected"':'') : ''}}>{{$group->payroll_group_code}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-6 padding-l-1">
                          <small>Employment Status</small>
                          <select class="form-control" name="payroll_employee_contract_status" disabled>
                            <option value="">Select Status</option>
                            @foreach($employement_status as $employment)
                            <option value="{{$employment->payroll_employment_status_id}}" {{isset($contract->payroll_employee_contract_status) ? ($employment->payroll_employment_status_id == $contract->payroll_employee_contract_status ? 'selected="selected"':'') : ''}}>{{$employment->employment_status}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-12">
                          
                          <button class="btn btn-primary pull-right margin-lr-5 popup" link="/member/payroll/employee_list/modal_view_contract_list/{{$employee->payroll_employee_id}}" type="button" size="lg">Company detail List</button>

                          <button class="btn pull-right btn-primary popup" type="button" link="/member/payroll/employee_list/modal_create_contract/{{$employee->payroll_employee_id}}">New Company details</button>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="government-contribution" class="tab-pane fade ">
                <div class="form-horizontal">
                  <div class="form-group">
                    <div class="col-md-6">
                        
                        <div class="form-group">
                          <div class="col-md-12">
                            <small>Tax Status</small>
                            <select class="form-control" name="payroll_employee_tax_status">
                              @foreach($tax_status as $tax)
                              <option value="{{$tax->payroll_tax_status_name}}" {{$tax->payroll_tax_status_name == $employee->payroll_employee_tax_status ? 'selected="selected"':''}}>{{$tax->payroll_tax_status_name}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <small>Tax Identification Number</small>
                            <input type="text" name="payroll_employee_tin" class="form-control" value="{{$employee->payroll_employee_tin}}">
                          </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="col-md-12">
                          <small>SSS Number</small>
                          <input type="text" name="payroll_employee_sss" class="form-control" value="{{$employee->payroll_employee_sss}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-12">
                          <small>Philhealth Number</small>
                          <input type="text" name="payroll_employee_philhealth" class="form-control" value="{{$employee->payroll_employee_philhealth}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-12">
                          <small>Pagibig/HDMF Number</small>
                          <input type="text" name="payroll_employee_pagibig" class="form-control" value="{{$employee->payroll_employee_pagibig}}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="salary-details" class="tab-pane fade">
                
                <div class="form-horizontal">
                  <div class="form-group">
                    <div class="col-md-12">
                      <div class="checkbox">
                        <label><input type="checkbox" name="payroll_employee_salary_minimum_wage" value="1" disabled {{isset($salary->payroll_employee_salary_minimum_wage) ? ($salary->payroll_employee_salary_minimum_wage == 1 ? 'checked="checked"' : '') : ''}}>Minimum wage earner</label>
                      </div>
                      <div class="checkbox">
                        <label><input type="checkbox" name="tbl_payroll_employee_custom_compute" value="1" class="custom-compute-chck" disabled {{isset($salary->tbl_payroll_employee_custom_compute) ? ($salary->tbl_payroll_employee_custom_compute == 1 ? 'checked="checked"' : '') : ''}}>Declare Salary for SSS, Philhealth and Tax?</label>
                      </div>
                    </div>
                  </div>
              
                  
                  <div class="form-group">
                    <div class="col-md-6">
                      
                      <div class="form-group">
                        <div class="col-md-12">
                          <small>Monthly Rate</small>
                          <input type="number" step="any" name="payroll_employee_salary_monthly" class="form-control text-right" value="{{isset($salary->payroll_employee_salary_monthly) ? $salary->payroll_employee_salary_monthly : ''}}" readonly>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-md-12">
                          <small>Daily Rate</small>
                          <input type="number" step="any" name="payroll_employee_salary_daily" class="form-control text-right" value="{{isset($salary->payroll_employee_salary_daily) ? $salary->payroll_employee_salary_daily : ''}}" readonly>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-md-12">
                          <small>Hourly Rate</small>
                          <input type="number" step="any" name="payroll_employee_salary_hourly" class="form-control text-right" value="{{isset($salary->payroll_employee_salary_hourly) ? $salary->payroll_employee_salary_hourly : ''}}" readonly>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <div class="col-md-12">
                          <small>COLA (Monthly)</small>
                          <input type="number" step="any" name="monthly_cola" class="form-control text-right" value="{{isset($salary->monthly_cola) ? $salary->monthly_cola : ''}}" readonly>
                        </div>
                      </div>
                      
                       <div class="form-group">
                        <div class="col-md-12">
                          <small>COLA (Daily)</small>
                          <input type="number" step="any" name="payroll_employee_salary_cola" class="form-control text-right" value="{{isset($salary->payroll_employee_salary_cola) ? $salary->payroll_employee_salary_cola : ''}}" readonly>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-md-12">
                          <small>PAGIBIG/HDMF Contribution</small>
                          <input type="number" name="payroll_employee_salary_pagibig" class="form-control text-right" value="{{isset($salary->payroll_employee_salary_pagibig) ? $salary->payroll_employee_salary_pagibig : ''}}" readonly>
                        </div>
                      </div>

                      
                    </div>
                    <div class="col-md-6 declared-salaries {{isset($salary->tbl_payroll_employee_custom_compute) ? ($salary->tbl_payroll_employee_custom_compute == 1 ? '' : 'hidden') : ''}}">
                      <div class="custom-compute-obj">
                        <div class="form-group">
                          <div class="col-md-12">
                            <small>Taxable Salary</small>
                            <input type="number" name="payroll_employee_salary_taxable" class="form-control text-right" value="{{isset($salary->payroll_employee_salary_taxable) ? $salary->payroll_employee_salary_taxable : ''}}" readonly>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-12">
                            <small>SSS Salary</small>
                            <input type="number" name="payroll_employee_salary_sss" class="form-control text-right" value="{{isset($salary->payroll_employee_salary_sss) ? $salary->payroll_employee_salary_sss : ''}}" readonly>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-md-12">
                            <small>COLA (Monthly)</small>
                            <input type="number" name="payroll_employee_salary_sss" class="form-control text-right" value="{{isset($salary->monthly_cola) ? $salary->monthly_cola : ''}}" readonly>
                          </div>
                        </div>

                        <div class="form-group">
                          <div class="col-md-12">
                            <small>PHILHEALTH Salary</small>
                            <input type="number" name="payroll_employee_salary_philhealth" class="form-control text-right" value="{{isset($salary->payroll_employee_salary_philhealth) ? $salary->payroll_employee_salary_philhealth : ''}}" readonly>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="custom-compute-obj">
                    <hr>
                    <div class="form-group hidden">
                      <div class="col-md-12">
                        <label>Mode of Deduction</label>
                      </div>
                    </div>
                    
                    <div class="form-group hidden">
                      <div class="col-md-6">
                        <div class="checkbox">
                          <label><input type="checkbox" name="is_deduct_sss_default" disabled {{isset($salary->is_deduct_sss_default) ? ($salary->is_deduct_sss_default == 1 ? 'checked="checked"':'') : ''}}>Compute SSS base on default</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <small>SSS contribution period</small>
                        <input type="number" name="deduct_sss_custom" class="form-control text-right" placeholder="0.00" step="any" id="sss-deduction-period" value="{{isset($salary->deduct_sss_custom) ? $salary->deduct_sss_custom : ''}}" disabled>
                      </div>
                    </div>
  
                    <div class="form-group hidden">
                      <div class="col-md-6">
                        <div class="checkbox">
                          <label><input type="checkbox" name="is_deduct_philhealth_default"   disabled {{isset($salary->is_deduct_philhealth_default) ? ($salary->is_deduct_philhealth_default == 1 ? 'checked="checked"':'') : ''}}>Compute PHILHEALTH base on default</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <small>PHILHEALTH contribution per period</small>
                        <input type="number" name="deduct_philhealth_custom" class="form-control text-right" placeholder="0.00" step="any" id="philhealth-deduction-period" value="{{isset($salary->deduct_philhealth_custom) ? $salary->deduct_philhealth_custom : ''}}" disabled>
                      </div>
                    </div>
  
                    <div class="form-group hidden">
                      <div class="col-md-6">
                        <div class="checkbox">
                          <label><input type="checkbox" name="is_deduct_pagibig_default" disabled {{isset($salary->is_deduct_pagibig_default)  ? ($salary->is_deduct_pagibig_default == 1 ? 'checked="checked"':'') : ''}}>Compute PAGIBIG base on default</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <small>PAGIBIG contribution per period</small>
                        <input type="number" name="deduct_pagibig_custom" class="form-control text-right" placeholder="0.00" step="any" id="pagibig-deduction-period" value="{{isset($salary->deduct_pagibig_custom) ? $salary->deduct_pagibig_custom : ''}}" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="form-group"></div>
                  <div class="form-group">
                    <div class="col-md-12">
                      <button class="btn pull-right margin-lr-5 btn-primary popup" link="/member/payroll/employee_list/modal_salary_list/{{$employee->payroll_employee_id}}" size="lg" type="button">Salary List</button>
                      <button class="btn pull-right btn-primary popup" type="button" link="/member/payroll/employee_list/modal_create_salary_adjustment/{{$employee->payroll_employee_id}}">Create salary adjustment</button>
                    </div>
                  </div>
                </div>
              </div>
              <div id="requirements" class="tab-pane fade">
                {{-- <div class="form-horizontal">
                  <div class="form-group">
                    <div class="col-md-12">
                      <table class="table table-condensed">
                        <tr>
                          <td>Resume/CV/Bio-Data</td>
                          <td class="text-center">
                            <input type="checkbox" name="has_resume" value="1" {{$requirement->has_resume == 1 ? 'checked':''}}>
                          </td>
                          <td width="35%" class="text-center">
                            <a href="{{$requirement->resume != null ? $requirement->resume :'#'}}" class="a-file-name {{$requirement->resume != null ? '' :'display-none'}}">{{$requirement->resume != null ? $requirement->resume_name :'file name here'}}</a><br>
                            <div class="custom-progress-container-100 display-none">
                              <div class="custom-progress"></div>
                            </div>
                            <label class="lbl-a-href {{$requirement->resume != null ? 'display-none' :''}}"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                            <input type="hidden" class="requirement-input" name="resume_requirements_id" value="{{$requirement->resume != null ? '$requirement->resume_requirements_id' :'0'}}">
                          </td>
                          <td class="text-center" width="10">
                            <a href="#" class="{{$requirement->resume != null ? '' :'display-none'}} remove-requirements" data-content="{{$requirement->resume != null ? '$requirement->resume_requirements_id' :'0'}}" data-name="resume"><i class="fa fa-times"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <td>Police Clearance</td>
                          <td class="text-center">
                            <input type="checkbox" name="has_police_clearance" value="1" {{$requirement->has_police_clearance == 1 ? 'checked':''}}>
                          </td>
                          <td width="35%" class="text-center">
                            <a href="{{$requirement->police_clearance != null ? $requirement->police_clearance :'#'}}" class="a-file-name {{$requirement->police_clearance != null ? '' :'display-none'}}">{{$requirement->police_clearance != null ? $requirement->police_clearance_name :'file name here'}}</a><br>
                            <div class="custom-progress-container-100 display-none">
                              <div class="custom-progress"></div>
                            </div>
                            <label class="lbl-a-href {{$requirement->police_clearance != null ? 'display-none' :''}}"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                            <input type="hidden" class="requirement-input" name="police_clearance_requirements_id" value="{{$requirement->police_clearance_requirements_id}}">
                          </td>
                          <td class="text-center" width="10">
                            <a href="#" class="{{$requirement->police_clearance != null ? '' :'display-none'}} remove-requirements" data-content="{{$requirement->police_clearance_requirements_id}}"><i class="fa fa-times"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <td>NBI</td>
                          <td class="text-center">
                            <input type="checkbox" name="has_nbi" value="1" {{$requirement->has_nbi == 1 ? 'checked':''}}>
                          </td>
                          <td width="35%" class="text-center">
                            <a href="{{$requirement->nbi_clearance != null ? $requirement->nbi_clearance :'#'}}" class="a-file-name {{$requirement->nbi_clearance != null ? $requirement->nbi_clearance :'display-none'}}">{{$requirement->nbi_clearance_name != null ? $requirement->nbi_clearance_name :'file name here'}}</a><br>
                            <div class="custom-progress-container-100 display-none">
                              <div class="custom-progress"></div>
                            </div>
                            <label class="lbl-a-href {{$requirement->nbi_clearance != null ? 'display-none' :''}}"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                            <input type="hidden" class="requirement-input" name="nbi_payroll_requirements_id" value="{{$requirement->nbi_payroll_requirements_id}}">
                          </td>
                          <td class="text-center" width="10">
                            <a href="#" class="{{$requirement->nbi_clearance != null ? '' :'display-none'}} remove-requirements" data-content="{{$requirement->nbi_payroll_requirements_id}}"><i class="fa fa-times"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <td>Health Certificate</td>
                          <td class="text-center">
                            <input type="checkbox" name="has_health_certificate" value="1" {{$requirement->has_health_certificate == 1 ? 'checked':''}}>
                          </td>
                          <td width="35%" class="text-center">
                            <a href="{{$requirement->heatlh_certificate != null ? $requirement->heatlh_certificate :'#'}}" class="a-file-name {{$requirement->heatlh_certificate != null ? '' :'display-none'}}">{{$requirement->heatlh_certificate_name != null ? $requirement->heatlh_certificate_name :'file name here'}}</a><br>
                            <div class="custom-progress-container-100 display-none">
                              <div class="custom-progress"></div>
                            </div>
                            <label class="lbl-a-href {{$requirement->heatlh_certificate != null ? 'display-none' :''}}"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                            <input type="hidden" class="requirement-input" name="health_certificate_requirements_id" value="{{$requirement->health_certificate_requirements_id}}">
                          </td>
                          <td class="text-center" width="10">
                            <a href="#" class="{{$requirement->heatlh_certificate != null ? '' :'display-none'}} remove-requirements" data-content="{{$requirement->health_certificate_requirements_id}}"><i class="fa fa-times"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <td>School Credentials</td>
                          <td class="text-center">
                            <input type="checkbox" name="has_school_credentials" value="1" {{$requirement->has_school_credentials == 1 ? 'checked':''}}>
                          </td>
                          <td width="35%" class="text-center">
                            <a href="{{$requirement->school_credentials != null ? $requirement->school_credentials :'#'}}" class="a-file-name {{$requirement->school_credentials != null ? '' :'display-none'}}">{{$requirement->school_credentials_name != null ? $requirement->school_credentials_name :'file name here'}}</a><br>
                            <div class="custom-progress-container-100 display-none">
                              <div class="custom-progress"></div>
                            </div>
                            <label class="lbl-a-href {{$requirement->school_credentials != null ? 'display-none' :''}}"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                            <input type="hidden" class="requirement-input" name="school_credentials_requirements_id" value="{{$requirement->school_credentials_requirements_id}}">
                          </td>
                          <td class="text-center" width="10">
                            <a href="#" class="{{$requirement->school_credentials != null ? '' :'display-none'}} remove-requirements" data-content="{{$requirement->school_credentials_requirements_id}}"><i class="fa fa-times"></i></a>
                          </td>
                        </tr>
                        <tr>
                          <td>Valid ID</td>
                          <td class="text-center">
                            <input type="checkbox" name="has_valid_id" value="1" {{$requirement->has_valid_id == 1 ? 'checked':''}}>
                          </td>
                          <td width="35%" class="text-center">
                            <a href="{{$requirement->valid_id != null ? $requirement->valid_id :'#'}}" class="a-file-name {{$requirement->valid_id != null ? '' :'display-none'}}">{{$requirement->valid_id_name != null ? $requirement->valid_id_name :'file name here'}}</a><br>
                            <div class="custom-progress-container-100 display-none">
                              <div class="custom-progress"></div>
                            </div>
                            <label class="lbl-a-href {{$requirement->valid_id != null ? 'display-none' :''}}"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                            <input type="hidden" class="requirement-input" name="valid_id_requirements_id" value="{{$requirement->valid_id_requirements_id}}">
                          </td>
                          <td class="text-center" width="10">
                            <a href="#" class="{{$requirement->valid_id != null ? '' :'display-none'}} remove-requirements" data-content="{{$requirement->valid_id_requirements_id}}"><i class="fa fa-times"></i></a>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div> --}}
              </div>
              <div id="dependents" class="tab-pane fade form-horizontal">
                <div class="form-group">
                  <div class="col-md-6">
                    <div class="form-horizontal">
                      <div class="form-group">
                        <div  class="col-md-12">
                          <small>Dependent Full Name</small>
                          <input type="text" name="payroll_dependent_name[]" class="form-control" value="{{isset($dependent[0]) ? $dependent[0]->payroll_dependent_name : ''}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <small>Birth Date</small>
                          <input type="text" name="payroll_dependent_birthdate[]" class="form-control datepicker" value="{{isset($dependent[0]) ? date('Y-m-d',strtotime($dependent[0]->payroll_dependent_birthdate)) : ''}}">
                        </div>
                        <div class="col-md-6">
                          <small>Relationship</small>
                          <select class="form-control" name="payroll_dependent_relationship[]">
                            <option value="">Select relationship</option>
                            <option value="Father" {{isset($dependent[0]) ? ($dependent[0]->payroll_dependent_relationship == 'Father' ? 'selected="selected"' : '') : ''}}>Father</option>
                            <option value="Mother" {{isset($dependent[0]) ? ($dependent[0]->payroll_dependent_relationship == 'Mother' ? 'selected="selected"' : '') : ''}}>Mother</option>
                            <option value="Spouse" {{isset($dependent[0]) ? ($dependent[0]->payroll_dependent_relationship == 'Spouse' ? 'selected="selected"' : '') : ''}}>Spouse</option>
                            <option value="Child" {{isset($dependent[0]) ? ($dependent[0]->payroll_dependent_relationship == 'Child' ? 'selected="selected"' : '') : ''}}>Child</option>
                          </select>
                        </div>
                      </div>
                      <hr>
                      <div class="form-group">
                        <div  class="col-md-12">
                          <small>Dependent Full Name</small>
                          <input type="text" name="payroll_dependent_name[]" class="form-control" value="{{isset($dependent[1]) ? $dependent[1]->payroll_dependent_name : ''}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <small>Birth Date</small>
                          <input type="text" name="payroll_dependent_birthdate[]" class="form-control datepicker" value="{{isset($dependent[1]) ? date('Y-m-d',strtotime($dependent[1]->payroll_dependent_birthdate)) : ''}}">
                        </div>
                        <div class="col-md-6">
                          <small>Relationship</small>
                          <select class="form-control" name="payroll_dependent_relationship[]">
                            <option value="">Select relationship</option>
                            <option value="Father" {{isset($dependent[1]) ? ($dependent[1]->payroll_dependent_relationship == 'Father' ? 'selected="selected"' : '') : ''}}>Father</option>
                            <option value="Mother" {{isset($dependent[1]) ? ($dependent[1]->payroll_dependent_relationship == 'Mother' ? 'selected="selected"' : '') : ''}}>Mother</option>
                            <option value="Spouse" {{isset($dependent[1]) ? ($dependent[1]->payroll_dependent_relationship == 'Spouse' ? 'selected="selected"' : '') : ''}}>Spouse</option>
                            <option value="Child" {{isset($dependent[1]) ? ($dependent[1]->payroll_dependent_relationship == 'Child' ? 'selected="selected"' : '') : ''}}>Child</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-horizontal">
                      <div class="form-group">
                        <div  class="col-md-12">
                          <small>Dependent Full Name</small>
                          <input type="text" name="payroll_dependent_name[]" class="form-control" value="{{isset($dependent[2]) ? $dependent[2]->payroll_dependent_name : ''}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <small>Birth Date</small>
                          <input type="text" name="payroll_dependent_birthdate[]" class="form-control datepicker" value="{{isset($dependent[2]) ? date('Y-m-d',strtotime($dependent[2]->payroll_dependent_birthdate)) : ''}}">
                        </div>
                        <div class="col-md-6">
                          <small>Relationship</small>
                          <select class="form-control" name="payroll_dependent_relationship[]">
                            <option value="">Select relationship</option>
                            <option value="Father" {{isset($dependent[2]) ? ($dependent[2]->payroll_dependent_relationship == 'Father' ? 'selected="selected"' : '') : ''}}>Father</option>
                            <option value="Mother" {{isset($dependent[2]) ? ($dependent[2]->payroll_dependent_relationship == 'Mother' ? 'selected="selected"' : '') : ''}}>Mother</option>
                            <option value="Spouse" {{isset($dependent[2]) ? ($dependent[2]->payroll_dependent_relationship == 'Spouse' ? 'selected="selected"' : '') : ''}}>Spouse</option>
                            <option value="Child" {{isset($dependent[2]) ? ($dependent[2]->payroll_dependent_relationship == 'Child' ? 'selected="selected"' : '') : ''}}>Child</option>
                          </select>
                        </div>
                      </div>
                      <hr>
                      <div class="form-group">
                        <div  class="col-md-12">
                          <small>Dependent Full Name</small>
                          <input type="text" name="payroll_dependent_name[]" class="form-control" value="{{isset($dependent[3]) ? $dependent[3]->payroll_dependent_name : ''}}">
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-6">
                          <small>Birth Date</small>
                          <input type="text" name="payroll_dependent_birthdate[]" class="form-control datepicker" value="{{isset($dependent[3]) ? date('Y-m-d',strtotime($dependent[3]->payroll_dependent_birthdate)) : ''}}">
                        </div>
                        <div class="col-md-6">
                          <small>Relationship</small>
                          <select class="form-control" name="payroll_dependent_relationship[]">
                            <option value="">Select relationship</option>
                            <option value="Father" {{isset($dependent[3]) ? ($dependent[3]->payroll_dependent_relationship == 'Father' ? 'selected="selected"' : '') : ''}}>Father</option>
                            <option value="Mother" {{isset($dependent[3]) ? ($dependent[3]->payroll_dependent_relationship == 'Mother' ? 'selected="selected"' : '') : ''}}>Mother</option>
                            <option value="Spouse" {{isset($dependent[3]) ? ($dependent[3]->payroll_dependent_relationship == 'Spouse' ? 'selected="selected"' : '') : ''}}>Spouse</option>
                            <option value="Child" {{isset($dependent[3]) ? ($dependent[3]->payroll_dependent_relationship == 'Child' ? 'selected="selected"' : '') : ''}}>Child</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>        
              </div>
              <div id="shift-schedule" class="tab-pane fade">
                    <div class="form-horizontal">
                      <div class="form-group">
                        <div class="col-md-6">
                          <small>Choose Shift Template</small>
                          <select class="form-control shift-template-select" name="shift_code_id">
                            <option value="0">Select Template</option>
                            <option value="-1">Create Template</option>
                            @foreach($_shift as $shift)
                            <option value="{{$shift->shift_code_id}}" {{$employee->shift_code_id == $shift->shift_code_id ? 'selected="selected"':''}}>{{$shift->shift_code_name}}</option> 
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-12 shift-template table-responsive">
                          
                        </div>
                      </div>
                    </div>
              </div>
              <div id="other" class="tab-pane fade">
                <ul class="nav nav-tabs nav-tabs-custom">
                  <li class="active"><a data-toggle="tab" href="#allowance">Allowance</a></li>
                  <li><a data-toggle="tab" href="#leave">Leave</a></li>
                  <li><a data-toggle="tab" href="#deduction">Deduction</a></li>
                  <li><a data-toggle="tab" href="#jouarnal">Journal</a></li>
                  <li><a data-toggle="tab" href="#remarks">Remarks</a></li>
                </ul>
                <div class="tab-content tab-content-custom">
                  <div id="allowance" class="tab-pane fade in active">
                    @foreach($_allowance as $allowance)
                    <div class="checkbox">
                      <label><input type="checkbox" name="allowance[]" value="{{$allowance['payroll_allowance_id']}}" {{$allowance['status_checked']}}>{{$allowance['payroll_allowance_name']}}</label>
                    </div>
                    @endforeach
                  </div>
                  <div id="leave" class="tab-pane fade">
                    @foreach($_leave as $leave)
                    <div class="checkbox">
                      <label><input type="checkbox" name="leave[]" value="{{$leave['payroll_leave_temp_id']}}" {{$leave['status_checked']}}>{{$leave['payroll_leave_temp_name']}}</label>
                    </div>
                    @endforeach
                  </div>
                  <div id="deduction" class="tab-pane fade">
                    @foreach($_deduction as $deduction)
                    <div class="checkbox">
                      <label><input type="checkbox" name="deduction[]" value="{{$deduction['payroll_deduction_id']}}" {{$deduction['status_checked']}}>{{$deduction['payroll_deduction_name']}}</label>
                    </div>
                    @endforeach
                  </div>
                  <div id="jouarnal" class="tab-pane fade">
                    @foreach($_journal_tag as $tag)
                    <div class="checkbox">
                      <label><input type="checkbox" name="journal_tag[]" value="{{$tag['payroll_journal_tag_id']}}" {{$tag['status']}}>{{$tag['account_number'].' • '.$tag['account_name']}}</label>
                    </div>
                    @endforeach
                  </div>
                  <div id="remarks" class="tab-pane fade">
                    <br>
                    <div class="form-horizontal">
                      <div class="form-group">
                        <div class="col-md-12">
                          <label for=""><b>Remarks</b></label>
                          <textarea class="form-control textarea-expand"  name="payroll_employee_remarks">{{$employee->payroll_employee_remarks}}</textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <div class="error-modal text-center">
      Error
    </div>
    
    <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
    <!--<button type="button" class="btn btn-custom-red-white btn-del-modallarge" data-url="" data-value="">Delete</button>-->
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Update</button>
  </div>
</form>

<script type="text/javascript" src="/assets/member/js/customer.js"></script>
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_employee.js"></script>