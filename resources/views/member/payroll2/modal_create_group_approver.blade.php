
<form class='global-submit' action="/member/payroll/payroll_admin_dashboard/save_approver_group" method="post">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Create Approver Group</h4>
      <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    </div>

    <div class="modal-body">
      <div class="form-group">
        <label for="approver_group_name">Approver Group Name:</label>
        <input type="text" class="form-control" id="approver_group_name" placeholder="Enter Group Name" name="approver_group_name" required>
      </div>

      <div class="form-group row">
        <div class="col-md-6">
          <label for="approver_type">Select Approver Type:</label>
          <select id="approver_type" class="form-control approver_type" name="approver_type" required>
              <option value=""> Select Type </option>
              <option value="overtime"> Overtime Request </option>
              <option value="rfp"> Request For Payment </option>
              <option value="leave"> Leave Requesst </option>
          </select>
        </div>
        <div class="col-md-6">
          <label for="approver_level_count">Select Up to Level:</label>
          <select id="approver_level_count" class="form-control approver_level_count" name="approver_level_count" required>
              <option value=""> Select Level </option>
              <option value="1"> 1 </option>
              <option value="2"> 2 </option>
              <option value="3"> 3 </option>
              <option value="4"> 4 </option>
              <option value="5"> 5 </option>
          </select>
        </div>
      </div>
      <div class="approver-container">
        
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </div>

</form>

<script type="text/javascript" src="/assets/js/modal_payroll_group_approver.js"></script>
<script type="text/javascript">
  function reload_page(data)
  {
    data.element.modal("hide");
    window.location.href = "/member/payroll/payroll_admin_dashboard/group_approver";
  }
</script>

  



