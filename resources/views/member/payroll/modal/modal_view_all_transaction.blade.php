<form class="global-submit form-horizontal" role="form" action="{link_submit_here}" method="post">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Transaction</h4>
  </div>
  <div class="modal-body clearfix" id="overflow">
   <table class="table table-bordered">
    <thead>
      <tr>
        <th>Name</th>
        <th>Source</th>
        <th>Remarks</th>
        <th>Updated at</th>
        <th>New Data</th>
        <th>Old Data</th>
      </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{$user_info->user_first_name}} {{$user_info->user_last_name}}</td>
        <td>{{$audit->source}}</td>
        <td>{{$audit->remarks}}</td>
        <td>{{date('M d, g:i A',strtotime($audit->created_at))}}</td>
        <td>{{$audit->new_data}}</td>
        <td>{{$audit->old_data}}</td>
      </tr>
    </tbody>
  </table>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
  </div>
</form>
<style>
#overflow {
  overflow-x:scroll;
}
</style>