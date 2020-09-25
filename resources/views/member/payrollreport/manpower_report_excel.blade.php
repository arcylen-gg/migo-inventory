  <h3><strong>{{$company}}</strong></h3>
  <h5><strong>Manpower Report</strong></h5>
  <br>
  <div class="modal-body clearfix">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                         <thead>
                            <tr>
                                <th>Date Hired</th>
                                <th>Date End</th>
                                <th>Employee Name</th>
                                <th>Position</th>
                                <th>Department</th>
                                <th>Employment Status</th>
                            </tr>
                        </thead>
                          <tbody>
                             @foreach($manpower_info as $manpower)
                            <tr>
                                <td class="text-center">{{$manpower->payroll_employee_contract_date_hired}}</td>
                                <td class="text-center">{{$manpower->payroll_employee_contract_date_end}}</td>
                                <td class="text-center">{{$manpower->payroll_employee_display_name}}</td>
                                <td class="text-center">{{$manpower->payroll_jobtitle_name}}</td>
                                <td class="text-center">{{$manpower->payroll_department_name}}</td>
                                <td class="text-center">{{$manpower->employment_status}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
    </div>