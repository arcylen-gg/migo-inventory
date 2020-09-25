<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
            <table class="table table-condensed collaptable">
            <tr>
              <th class="text-center" colspan="2">Name</th>
              <th class="text-center">Reference number</th>
              <th class="text-center">Status</th>
              <th class="text-center">Date</th>
              <th class="text-center">Retain Credit From</th>
              <th class="text-center">Amount</th>
            </tr>
            <tbody>
              
            @foreach($_customer_credit as $key=> $customer)
            <tr data-id="customer-{{$key}}" data-parent="">
                <td><b>{{ $customer->company != '' ? $customer->company : $customer->first_name." ".$customer->last_name }}</b> <br>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>{{$customer->customer_address}}</small>
                </td>
                <td colspan="5"></td>
                <td class="text-right"><text class="total-report">{{currency('PHP', $customer->total_retain_credit)}}</text></td>
              </tr>
              @foreach($customer->retain_credit as $key2=> $r_credit)
              <tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
                <td nowrap></td>
                <td nowrap></td>
                <td nowrap>{{$r_credit->cm_ref_num}}</td>
                <td nowrap>{{$r_credit->cm_status == 1 ? 'CLOSED' : 'OPEN'}}</td>
                <td nowrap>{{date('F d, Y',strtotime($r_credit->cm_date))}}</td>
                <td class="text-center" nowrap>{{$r_credit->rp_ref_num == '' ? 'NONE' : $r_credit->rp_ref_num}}</td>
                <td class="text-right" nowrap>{{currency('PHP', $r_credit->cm_amount)}}</td>
              </tr>
              @endforeach
              <tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
                <td colspan="6"><b>Total {{ $customer->company != '' ? $customer->company : $customer->first_name." ".$customer->last_name }}</b></td>
                <td class="text-right"><b>{{currency('PHP', $customer->total_retain_credit)}}</b></td>
              </tr> 
            @endforeach
            </tbody>
            </table>
          </div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>