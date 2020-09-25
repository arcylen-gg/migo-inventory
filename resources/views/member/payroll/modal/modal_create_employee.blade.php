<form class="global-submit" role="form" action="/member/payroll/employee_list/modal_employee_save" method="POST">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title layout-modallarge-title">Create new employee</h4>
  </div>
  <div class="modal-body modallarge-body-layout background-white">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">
    <div class="form-horizontal">
      <div class="form-group">
        <div class="col-md-6">
          <div class="col-md-2 padding-lr-1">
            <small>Title</small>
            <input type="text" name="payroll_employee_title_name" class="form-control auto-name title margin-l-0"/>
          </div>
          <div class="col-md-3 padding-lr-1">
            <small>First name</small>
            <input type="text" name="payroll_employee_first_name" class="form-control auto-name first_name" value="{{$value or ''}}" required/>
          </div>
          <div class="col-md-3 padding-lr-1">
            <small>Middle name</small>
            <input type="text" name="payroll_employee_middle_name" class="form-control auto-name middle_name"/>
          </div>
          <div class="col-md-3 padding-lr-1">
            <small>Last name</small>
            <input type="text" name="payroll_employee_last_name" class="form-control auto-name last_name" required/>
          </div>
          <div class="col-md-1 padding-lr-1">
            <small>Suffix</small>
            <input type="text" name="payroll_employee_suffix_name" class="form-control auto-name suffix"/>
          </div>
        </div>
        <div class="col-md-6">
          <div class="col-md-4 padding-lr-1">
            <small>Employee number</small>
            <input type="text" name="payroll_employee_number" class="form-control" required/>
          </div>
          <div class="col-md-4 padding-lr-1">
            <small>Biometric number</small>
            <input type="text" name="payroll_employee_biometric_number" class="form-control" required/>
          </div>
          <div class="col-md-4 padding-lr-1">
            <small>ATM No.</small>
            <input type="text" name="payroll_employee_atm_number" class="form-control"/>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-6">
          <small>Company</small>
          <select class="form-control" name="payroll_employee_company_id" required>
            <option value="">Select Company</option>
              @foreach($_company as $company)
              <option value="{{$company['company']->payroll_company_id}}">{{$company['company']->payroll_company_name}}</option> 
                @foreach($company['branch'] as $branch)
                <option value="{{$branch->payroll_company_id}}">&nbsp;&nbsp;• {{$branch->payroll_company_name}}</option>
                @endforeach
              @endforeach
          </select>
        </div>
        <div class="col-md-6">
          <div class="col-md-6 padding-lr-1">
            <small>Contact</small>
            <input type="text" name="payroll_employee_contact" class="form-control"/>
          </div>
          <div class="col-md-6 padding-lr-1">
            <small>Email</small>
            <input type="text" name="payroll_employee_email" class="form-control"/>
          </div>
          
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-6">
          <small>Branch Location</small>
          <select class="form-control" name="branch_location_id">
            <option value="0">Select Branch</option>
            @foreach($_branch as $branch)
            <option value="{{$branch->branch_location_id}}">{{$branch->branch_location_name}}</option>
            @endforeach
          </select>
        </div>
        
        <div class="col-md-6">
          <div class="col-md-6 padding-lr-1">
            <small>Gender</small>
            <select class="form-control" name="payroll_employee_gender">
              <option value="Male">Male</option>
              <option value="Female">Female</option>
            </select>
          </div>
          <div class="col-md-6 padding-lr-1">
            <small>Birthdate</small>
            <input type="text" name="payroll_employee_birthdate" class="form-control indent-13 datepicker">
            <i class="fa fa-calendar pos-absolute top-30 margin-left-6 color-dark-gray" aria-hidden="true"></i>
          </div>
          
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-6">
          <small><b>Print on as check as</b></small>&nbsp;
          <div class="checkbox display-inline-block"><small for=""><input type="checkbox" name="chck_print_on_as" class="checkbox-toggle-rev check-print-name-as" data-target=".display-name-check" checked="true"/>Use display name</small></div>
          <input type="text" name="payroll_employee_display_name" class="form-control display-name-check"/>
        </div>
      </div>
      <div class="form-group">
        <div class="col-md-12">
          <ul class="nav nav-tabs nav-tabs-custom">
            <li class="active"><a data-toggle="tab" href="#address">Address</a></li>
            <li><a data-toggle="tab" href="#company-details">Company Details</a></li>
            <li><a data-toggle="tab" href="#government-contribution">Government</a></li>
            <li><a data-toggle="tab" href="#salary-details">Salary Details</a></li>
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
                        <textarea name="payroll_employee_street" rows="2" class="form-control textarea-expand" placeholder="Street"></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-6">
                        <small>City/Town</small>
                        <input type="text" name="payroll_employee_city" class="form-control" placeholder="City/Town">
                      </div>
                      <div class="col-md-6">
                        <small>State</small>
                        <input type="text" name="payroll_employee_state" class="form-control" placeholder="State">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-6">
                        <small>Zip Code</small>
                        <input type="text" name="payroll_employee_zipcode" class="form-control" placeholder="Zip code">
                      </div>
                      <div class="col-md-6">
                        <small>Country</small>
                        <select class="form-control" name="payroll_employee_country">
                          @foreach($_country as $country)
                          <option value="{{$country->country_id}}">{{$country->country_name}}</option>
                          @endforeach
                        </select>
                       
                      </div>
                    </div>
                  </div>
                 
                </div>
              </div>
            </div>
            <div id="company-details" class="tab-pane fade ">
              <div class="form-horizontal">
                <div class="form-group">
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>Department</small>
                        <select class="form-control department-select" required name="payroll_department_id">
                          <option value="">Select Department</option>
                          @foreach($_department as $department)
                          <option value="{{$department->payroll_department_id}}">{{$department->payroll_department_name}}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>Job Title</small>
                        <select class="form-control jobtitle-select" name="payroll_jobtitle_id" required>
                          <option value="">Select Job Title</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="col-md-6 padding-r-1">
                        <small>Date Hired</small>
                        <input type="text" name="payroll_employee_contract_date_hired" class="form-control indent-13 datepicker">
                        <i class="fa fa-calendar pos-absolute top-30 margin-left-6 color-dark-gray" aria-hidden="true"></i>
                      </div>
                      <div class="col-md-6 padding-l-1">
                        <small>Date End</small>
                        <input type="text" name="payroll_employee_contract_date_end" class="form-control indent-13 datepicker">
                        <i class="fa fa-calendar pos-absolute top-30 margin-left-6 color-dark-gray" aria-hidden="true"></i>
                      </div>
                      
                    </div>
                    <div class="form-group">
                      <div class="col-md-6">
                        <small>Payroll Group</small>
                        <select class="form-control payroll-group-select" name="payroll_group_id" required>
                          <option value="">Select Group</option>
                          @foreach($_group as $group)
                          <option value="{{$group->payroll_group_id}}">{{$group->payroll_group_code}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="col-md-6">
                        <small>Employment Status</small>
                        <select class="form-control" name="payroll_employee_contract_status">
                          <option value="">Select Status</option>
                          @foreach($employement_status as $employment)
                          <option value="{{$employment->payroll_employment_status_id}}">{{$employment->employment_status}}</option>
                          @endforeach
                        </select>
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
                            <option value="{{$tax->payroll_tax_status_name}}">{{$tax->payroll_tax_status_name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-12">
                          <small>Tax Identification Number</small>
                          <input type="text" name="payroll_employee_tin" class="form-control">
                        </div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>SSS Number</small>
                        <input type="text" name="payroll_employee_sss" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>Philhealth Number</small>
                        <input type="text" name="payroll_employee_philhealth" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>Pagibig/HDMF Number</small>
                        <input type="text" name="payroll_employee_pagibig" class="form-control">
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
                      <label><input type="checkbox" name="payroll_employee_salary_minimum_wage" value="1">Minimum wage earner</label>
                    </div>
                    <div class="checkbox">
                      <label><input type="checkbox" name="tbl_payroll_employee_custom_compute" class="custom-compute-chck" value="1">Declare Salary for SSS, Philhealth and Tax?</label>
                    </div>
                    <!--
                    <div class="checkbox hidden">
                      <label><input type="checkbox" name="tbl_payroll_employee_custom_compute" class="custom-compute-chck" value="1">Custom Computation</label>
                    </div>
                    -->
                  </div>
                </div>
                        
                <div class="form-group">
                  <div class="col-md-6"> 
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>Monthly Rate</small>
                        <input type="number" step="any" name="payroll_employee_salary_monthly" class="form-control text-right" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>Daily Rate</small>
                        <input type="number" step="any" name="payroll_employee_salary_daily" class="form-control text-right">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>Hourly Rate</small>
                        <input type="number" step="any" name="payroll_employee_salary_hourly" class="form-control text-right">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>COLA (Monthly)</small>
                        <input type="number" step="any" name="monthly_cola" class="form-control text-right">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>COLA (Daily)</small>
                        <input type="number" step="any" name="payroll_employee_salary_cola" class="form-control text-right">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>PAGIBIG/HDMF Contribution</small>
                        <input type="number" step="any" name="payroll_employee_salary_pagibig" class="form-control text-right">
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6 declared-salaries hidden">
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>Taxable Salary (optional)</small>
                        <input type="number" step="any" name="payroll_employee_salary_taxable" class="form-control text-right">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>SSS Salary (optional)</small>
                        <input type="number" step="any" name="payroll_employee_salary_sss" class="form-control text-right">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-12">
                        <small>PHILHEALTH Salary (optional)</small>
                        <input type="number" step="any" name="payroll_employee_salary_philhealth" class="form-control text-right">
                      </div>
                    </div>

                  </div>
                </div>
                <div class="custom-compute-obj">
                <hr>

                <div class="form-group hidden">
                  <div class="col-md-12">
                    <label>Mode of Deduction (for fixed value)</label>
                  </div>
                </div>
                <div class="form-group hidden">
                  <div class="col-md-6">
                    <div class="checkbox">
                      <label><input type="checkbox" name="is_deduct_sss_default" class="deduction-check-period" data-target="#sss-deduction-period" checked="true" value="1">Compute SSS base on default</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <small>SSS contribution per period</small>
                    <input type="number" name="deduct_sss_custom" class="form-control text-right" placeholder="0.00" step="any" id="sss-deduction-period">
                  </div>
                </div>
                <div class="form-group hidden">
                  <div class="col-md-6">
                    <div class="checkbox">
                      <label><input type="checkbox" name="is_deduct_philhealth_default" class="deduction-check-period" data-target="#philhealth-deduction-period" checked="true" value="1">Compute PHILHEALTH base on default</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <small>PHILHEALTH contribution per period</small>
                    <input type="number" name="deduct_philhealth_custom" class="form-control text-right" placeholder="0.00" step="any" id="philhealth-deduction-period">
                  </div>
                </div>
                <div class="form-group hidden">
                  <div class="col-md-6">
                    <div class="checkbox">
                      <label><input type="checkbox" name="is_deduct_pagibig_default" class="deduction-check-period" data-target="#pagibig-deduction-period" checked="true" value="1">Compute PAGIBIG base on default</label>
                    </div>
                  </div>
  
                  <div class="form-group">
                    <div class="col-md-6">
                      <div class="checkbox">
                        <label><input type="checkbox" name="is_deduct_pagibig_default" class="deduction-check-period" data-target="#pagibig-deduction-period" checked="true" value="1">Compute PAGIBIG base on default</label>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <small>PAGIBIG contribution per period</small>
                      <input type="number" name="deduct_pagibig_custom" class="form-control text-right" placeholder="0.00" step="any" id="pagibig-deduction-period">
                    </div>
                  </div>
                </div>
                
              </div>
            </div>
            </div>
            <div id="requirements" class="tab-pane fade">
              <div class="form-horizontal">
                <div class="form-group">
                  <div class="col-md-12">
                    <table class="table table-condensed">
                      <tr>
                        <td>Resume/CV/Bio-Data</td>
                        <td class="text-center">
                          <input type="checkbox" name="has_resume" value="1">
                        </td>
                        <td width="35%" class="text-center">
                          <a href="#" class="a-file-name display-none">file name here</a><br>
                          <div class="custom-progress-container-100 display-none">
                            <div class="custom-progress"></div>
                          </div>
                          <label class="lbl-a-href"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                          <input type="hidden" class="requirement-input" name="resume_requirements_id">
                        </td>
                        <td class="text-center" width="10">
                          <a href="#" class="display-none remove-requirements" data-requirement=""><i class="fa fa-times"></i></a>
                        </td>
                      </tr>
                      <tr>
                        <td>Police Clearance</td>
                        <td class="text-center">
                          <input type="checkbox" name="has_police_clearance" value="1">
                        </td>
                        <td width="35%" class="text-center">
                          <a href="#" class="a-file-name display-none">file name here</a><br>
                          <div class="custom-progress-container-100 display-none">
                            <div class="custom-progress"></div>
                          </div>
                          <label class="lbl-a-href"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                          <input type="hidden" class="requirement-input" name="police_clearance_requirements_id">
                        </td>
                        <td class="text-center" width="10">
                          <a href="#" class="display-none remove-requirements" data-requirement=""><i class="fa fa-times"></i></a>
                        </td>
                      </tr>
                      <tr>
                        <td>NBI</td>
                        <td class="text-center">
                          <input type="checkbox" name="has_nbi" value="1">
                        </td>
                        <td width="35%" class="text-center">
                          <a href="#" class="a-file-name display-none">file name here</a><br>
                          <div class="custom-progress-container-100 display-none">
                            <div class="custom-progress"></div>
                          </div>
                          <label class="lbl-a-href"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                          <input type="hidden" class="requirement-input" name="nbi_payroll_requirements_id">
                        </td>
                        <td class="text-center" width="10">
                          <a href="#" class="display-none remove-requirements" data-requirement=""><i class="fa fa-times"></i></a>
                        </td>
                      </tr>
                      <tr>
                        <td>Health Certificate</td>
                        <td class="text-center">
                          <input type="checkbox" name="has_health_certificate" value="1">
                        </td>
                        <td width="35%" class="text-center">
                          <a href="#" class="a-file-name display-none">file name here</a><br>
                          <div class="custom-progress-container-100 display-none">
                            <div class="custom-progress"></div>
                          </div>
                          <label class="lbl-a-href"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                          <input type="hidden" class="requirement-input" name="health_certificate_requirements_id">
                        </td>
                        <td class="text-center" width="10">
                          <a href="#" class="display-none remove-requirements" data-requirement=""><i class="fa fa-times"></i></a>
                        </td>
                      </tr>
                      <tr>
                        <td>School Credentials</td>
                        <td class="text-center">
                          <input type="checkbox" name="has_school_credentials" value="1">
                        </td>
                        <td width="35%" class="text-center">
                          <a href="#" class="a-file-name display-none">file name here</a><br>
                          <div class="custom-progress-container-100 display-none">
                            <div class="custom-progress"></div>
                          </div>
                          <label class="lbl-a-href"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                          <input type="hidden" class="requirement-input" name="school_credentials_requirements_id">
                        </td>
                        <td class="text-center" width="10">
                          <a href="#" class="display-none remove-requirements" data-content=""><i class="fa fa-times"></i></a>
                        </td>
                      </tr>
                      <tr>
                        <td>Valid ID</td>
                        <td class="text-center">
                          <input type="checkbox" name="has_valid_id" value="1">
                        </td>
                        <td width="35%" class="text-center">
                          <a href="#" class="a-file-name display-none">file name here</a><br>
                          <div class="custom-progress-container-100 display-none">
                            <div class="custom-progress"></div>
                          </div>
                          <label class="lbl-a-href"><input type="file" name="" class="hide file-requirements"><i class="fa fa-cloud-upload lbl-a-href" aria-hidden="true"></i>&nbsp;Upload requirement</label>
                          <input type="hidden" class="requirement-input" name="valid_id_requirements_id">
                        </td>
                        <td class="text-center" width="10">
                          <a href="#" class="display-none remove-requirements" data-content=""><i class="fa fa-times"></i></a>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div id="dependents" class="tab-pane fade form-horizontal">
              <div class="form-group">
                <div class="col-md-6">
                  <div class="form-horizontal">
                    <div class="form-group">
                      <div  class="col-md-12">
                        <small>Dependent Full Name</small>
                        <input type="text" name="payroll_dependent_name[]" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-6">
                        <small>Birth Date</small>
                        <input type="text" name="payroll_dependent_birthdate[]" class="form-control datepicker">
                      </div>
                      <div class="col-md-6">
                        <small>Relationship</small>
                        <select class="form-control" name="payroll_dependent_relationship[]">
                          <option value="">Select relationship</option>
                          <option value="Father">Father</option>
                          <option value="Mother">Mother</option>
                          <option value="Spouse">Spouse</option>
                          <option value="Child">Child</option>
                        </select>
                      </div>
                    </div>
                    <hr>
                    <div class="form-group">
                      <div  class="col-md-12">
                        <small>Dependent Full Name</small>
                        <input type="text" name="payroll_dependent_name[]" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-6">
                        <small>Birth Date</small>
                        <input type="text" name="payroll_dependent_birthdate[]" class="form-control datepicker">
                      </div>
                      <div class="col-md-6">
                        <small>Relationship</small>
                        <select class="form-control" name="payroll_dependent_relationship[]">
                          <option value="">Select relationship</option>
                          <option value="Father">Father</option>
                          <option value="Mother">Mother</option>
                          <option value="Spouse">Spouse</option>
                          <option value="Child">Child</option>
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
                        <input type="text" name="payroll_dependent_name[]" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-6">
                        <small>Birth Date</small>
                        <input type="text" name="payroll_dependent_birthdate[]" class="form-control datepicker">
                      </div>
                      <div class="col-md-6">
                        <small>Relationship</small>
                        <select class="form-control" name="payroll_dependent_relationship[]">
                          <option value="">Select relationship</option>
                          <option value="Father">Father</option>
                          <option value="Mother">Mother</option>
                          <option value="Spouse">Spouse</option>
                          <option value="Child">Child</option>
                        </select>
                      </div>
                    </div>
                    <hr>
                    <div class="form-group">
                      <div  class="col-md-12">
                        <small>Dependent Full Name</small>
                        <input type="text" name="payroll_dependent_name[]" class="form-control">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-md-6">
                        <small>Birth Date</small>
                        <input type="text" name="payroll_dependent_birthdate[]" class="form-control datepicker">
                      </div>
                      <div class="col-md-6">
                        <small>Relationship</small>
                        <select class="form-control" name="payroll_dependent_relationship[]">
                          <option value="">Select relationship</option>
                          <option value="Father">Father</option>
                          <option value="Mother">Mother</option>
                          <option value="Spouse">Spouse</option>
                          <option value="Child">Child</option>
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
                      @foreach($_shift as $shift)
                      <option value="{{$shift->shift_code_id}}">{{$shift->shift_code_name}}</option> 
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
                    <label><input type="checkbox" name="allowance[]" value="{{$allowance->payroll_allowance_id}}">{{$allowance->payroll_allowance_name}}</label>
                  </div>
                  @endforeach
                </div>
                <div id="leave" class="tab-pane fade">
                  @foreach($_leave as $leave)
                  <div class="checkbox">
                    <label><input type="checkbox" name="leave[]" value="{{$leave->tbl_payroll_leave_temp_id}}">{{$leave->payroll_leave_temp_name}}</label>
                  </div>
                  @endforeach
                </div>
                <div id="deduction" class="tab-pane fade">
                  @foreach($_deduction as $deduction)
                  <div class="checkbox">
                    <label><input type="checkbox" name="deduction[]" value="{{$deduction->payroll_deduction_id}}">{{$deduction->payroll_deduction_name}}</label>
                  </div>
                  @endforeach
                </div>
                <div id="jouarnal" class="tab-pane fade">
                  @foreach($_journal_tag as $tag)
                  <div class="checkbox">
                    <label><input type="checkbox" name="journal_tag[]" value="{{$tag->payroll_journal_tag_id}}">{{$tag->account_number.' • '.$tag->account_name}}</label>
                  </div>
                  @endforeach
                </div>
                <div id="remarks" class="tab-pane fade">
                  <br>
                  <div class="form-horizontal">
                    <div class="form-group">
                      <div class="col-md-12">
                        <label for=""><b>Remarks</b></label>
                        <textarea class="form-control textarea-expand"  name="payroll_employee_remarks"></textarea>
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
    <button type="button" class="btn btn-custom-white " data-dismiss="modal">Cancel</button>
    <button class="btn btn-custom-primary btn-save-modallarge" type="submit" data-url="">Save customer</button>
  </div>
</form>
<!-- <script type="text/javascript" src="/assets/member/js/customer.js"></script> -->
<script type="text/javascript" src="/assets/member/js/textExpand.js"></script>
<script type="text/javascript" src="/assets/member/js/payroll/modal_create_employee.js"></script>