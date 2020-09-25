<form class="global-submit" role="form" action="{{$action}}" method="POST">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Tag Employee</h4>
    <input type="hidden" name="_token" value="{{csrf_token()}}">
  </div>
  <div class="modal-body form-horizontal">
    <div class="form-group">
      <div class="col-md-12">
        <small>Select Company</small>
        <select class="form-control change-filter change-filter-company">
          <option value="0">Select Company</option>
          @foreach($_company as $company)
          <option value="{{$company->payroll_company_id}}">{{$company->payroll_company_name}}</option>
          @endforeach
        </select> 
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-6">
        <small>Select Department</small>
        <select class="form-control change-filter change-filter-department">
          <option value="0">Select Department</option>
          @foreach($_department as $department)
          <option value="{{$department->payroll_department_id}}">{{$department->payroll_department_name}}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <small>Select Job Title</small>
        <select class="form-control change-filter change-filter-job-title">
          <option value="0">Select Job Title</option>
        </select>
      </div>
    </div>
    <div class="form-group">
      <div class="col-md-12">
        <ul class="list-group employee-tag-list">
          
        </ul>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-custom-white" data-dismiss="modal">Close</button>
    <button class="btn btn-custom-primary btn-submit" type="submit">Tag</button>   
  </div>
</form>

<script type="text/javascript" src="/assets/member/js/payroll/employee_approver_tag.js"></script>