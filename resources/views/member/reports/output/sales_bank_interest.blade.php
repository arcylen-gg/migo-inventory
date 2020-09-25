<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header')
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
              <thead>
             		<tr>
                  <th class="text-center" class="text-center">Date</th>
             			<th class="text-center">Receipt Number</th>
             			<!-- <th class="text-center">Date</th> -->
             			<th class="text-center">Customer Name</th>
                  <th class="text-center">Sales Amount</th>
                  <th class="text-center">Bank Interest</th>
                  <th class="text-center">Amount</th>
                  <th class="text-center">Interest Amount</th>
                  <th class="text-center">Bank Name</th>
                  <th class="text-center">Months</th>
             			<th class="text-center">Note</th>
             		</tr>
              </thead>
              <tbody>
                @if(count($_sales_bank_interest) > 0)
                  @foreach($_sales_bank_interest as $bank_interest)
                  <tr>
                    <td>{{date('m/d/y',strtotime($bank_interest->inv_date))}}</td>
                    <td>{{$bank_interest->transaction_refnum}}</td>
                    <td>
                      <strong><label>{{$bank_interest->company}}</label></strong>
                      <label>{{$bank_interest->first_name." ".$bank_interest->middle_name." ".$bank_interest->last_name}}</label>
                    </td>
                    <td class="text-right">{{currency('P ',$bank_interest->inv_overall_price)}}</td>
                    <td class="text-center">{{$bank_interest->bank_interest}}</td>
                    <td class="text-right">{{$bank_interest->bank_amount}}</td>
                    <td class="text-right">{{currency('P ',$bank_interest->bank_interest_amount)}}</td>
                    <td class="text-center">{{$bank_interest->bank_name}}</td>
                    <td>{{$bank_interest->bank_months}}</td>
                    <td>{{$bank_interest->bank_remarks}}</td>
                  </tr>
                  @endforeach
                @else
                <tr>
                  <td colspan="20" class="text-center">NO TRANSACTION YET</td>
                </tr>
                @endif
              </tbody>         	
         		</table>
         	</div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>