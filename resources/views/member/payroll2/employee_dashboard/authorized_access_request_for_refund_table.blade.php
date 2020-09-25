<div class="table-responsive">
  @if(count($_payment_request) > 0)
  <table class="table table-bordered" width="100%" cellspacing="0">
      <thead>
          <tr>
              <th class="text-center" >Date Requested</th>
              <th class="text-center" >Request Name</th>
              <th class="text-center" >Total Amount</th>
              <th class="text-center" >Status</th>
              <th class="text-center" >Status level</th>
              <th class="text-center" >Action</th>
          </tr>
      </thead>
      <tbody>
          @foreach($_payment_request as $payment_request)
          <tr>
             <td class="text-center">{{ date('F d, Y', strtotime($payment_request->payroll_request_payment_date)) }}</td>
             <td class="text-center">{{ $payment_request->payroll_request_payment_name }}</td>
             <td class="text-center">{{ $payment_request->payroll_request_payment_total_amount }}</td>
             <td class="text-center">{{ $payment_request->payroll_request_payment_status }}</td>
             <td class="text-center">{{ $payment_request->payroll_request_payment_status_level }}</td>
             <td class="text-center">
                 <div class="dropdown">
                   <button class="btn btn-link dropdown-toggle" type="button" id="menu-drop-down" data-toggle="dropdown">Action
                   <span class="caret"></span></button>
                   <ul class="dropdown-menu" role="menu" aria-labelledby="menu-drop-down">
                     <li style="padding-left: 10px;" role="presentation" class="popup" link='/rfp_application_view/{{ $payment_request->payroll_request_payment_id }}' size='lg'><a role="menuitem" tabindex="-1" href="#"><i class="fa fa-search" aria-hidden="true"></i> &nbsp; View</a></li>
                     <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="rfp_application_approve/{{$payment_request->payroll_request_payment_id}}" size="sm" href="#"><i class="fa fa-check" aria-hidden="true"></i> &nbsp; Approve</a></li>
                     <li style="padding-left: 10px;" role="presentation"><a role="menuitem" class="popup" link="rfp_application_reject/{{$payment_request->payroll_request_payment_id}}" size="sm" href="#"><i class="fa fa-ban" aria-hidden="true"></i> &nbsp; Reject</a></li>
                   </ul>
                 </div>
             </td>
          </tr>
          @endforeach
      </tbody>
  </table>
  @else
  <h2 style="margin: 50px; text-align: center;">No Data</h2>
  @endif
</div>