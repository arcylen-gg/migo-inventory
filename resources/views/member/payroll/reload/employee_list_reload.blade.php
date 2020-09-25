<table class="table table-condensed table-bordered">
  <thead>
    <tr>
      <th>Employee No</th>
      <th>Employee Name</th>
      <th>Employee Company</th>
      <th>Department</th>
      <th>Position</th>
      <th class="text-center">Action</th>
    </tr>
  </thead>
  @foreach($_active as $active)
  <tr>
    <td>
      {{$active->payroll_employee_number}}
    </td>
    <td>
      {{$active->payroll_employee_last_name}}, {{$active->payroll_employee_first_name}} {{ substr($active->payroll_employee_middle_name, 0, -(strlen($active->payroll_employee_middle_name))+1) }}.
    </td>
    <td>
      {{$active->payroll_company_name}}
    </td>
    <td>
      {{$active->payroll_department_name}}
    </td>
    <td>
      {{$active->payroll_jobtitle_name}}
    </td>
    <td class="text-center">
      <div class="dropdown">
        <button class="btn btn-custom-white dropdown-toggle btn-xs" type="button" data-toggle="dropdown">Action
        <span class="caret"></span></button>
        <ul class="dropdown-menu dropdown-menu-custom">
          <li>
            <a href="#" class="popup" link="/member/payroll/employee_list/modal_employee_view/{{$active->payroll_employee_id}}" size="lg"><i class="fa fa-search"></i>&nbsp;View</a>
          </li>
        </ul>
      </div>
    </td>
  </tr>
  @endforeach
</table>