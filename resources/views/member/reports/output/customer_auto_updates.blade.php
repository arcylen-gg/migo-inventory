<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report load-data">
      <div class="panel-heading load-content">
         @include('member.reports.report_header');
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
         		<tr>
              <th class="text-center">#</th>
              <th class="text-center">Client Name</th>
              <th class="text-center">Company</th>
              <th class="text-center">Phone No</th>
         			<th class="text-center">Mobile No</th>
         			<th class="text-center">Address</th>
         			<th class="text-center">City</th>
         			<th class="text-center">Type</th>
         		<!--	<th class="text-center">Category</th>-->
            <!--  <th class="text-center">Date</th> -->
         		</tr>
         		<tbody {{$no = 0}}>
         		@if(count($_customer) > 0)
     				@foreach($_customer as $key=>$customer)
     				<tr data-id="customer-{{$key}}" {{$no+=1}} data-parent="">
                <td class="text-center">{{$no}}</td>
         				<td class="text-left"><b>{{ $customer->first_name." ".$customer->last_name}}</b></td>
                <td class="text-center"><b>{{ $customer->company }}</b></td>
                <td class="text-center">{{ $customer->customer_phone }}</td>
                <td class="text-center">{{ $customer->customer_mobile }}</td>
                <td class="text-center">{{ $customer->customer_street }}</td>
                <td class="text-center">{{ $customer->customer_city }}</td>
                <td class="text-center">{{ strtoupper($customer->customer_category_type) }}</td>
              <!--  <td class="text-center">{{ ucwords($customer->customer_category) }}</td>-->
         			</tr>              
              <!--
              @if(count($customer->updates_history_category) > 0)
                @foreach($customer->updates_history_category as $key2 => $history)
                <tr data-id="customer2-{{$key}}" data-parent="customer-{{$key}}">
                  <td nowrap colspan="7"></td>
                  <td nowrap>{{ucwords($history['category'])}}</td>
                  <td nowrap>{{$history['date']}}</td>
                </tr>
                @endforeach
              @endif
              -->
     				@endforeach
            @else
            <tr><td colspan="9" class="text-center">No Data</td></tr>
            @endif
         		</tbody>
         		</table>
         	</div>
          <h5 class="text-center">---- {{$now or ''}} ----</h5>
      </div>
  </div>
</div>