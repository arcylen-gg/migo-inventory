<div class="report-container">
  <div class="panel panel-default panel-block panel-title-block panel-report">
      <div class="panel-heading">
         @include('member.reports.report_header');
         <div class="table-reponsive">
         		<table class="table table-condensed collaptable">
         		<!-- <tr>
              <th class="text-center">Account</th>
         			<th class="text-center">Date</th>
         			<th>Transaction Type</th>
         			<th>Num</th>
         			<th>Name</th>
         			<th class="text-center">Memo/Discription</th>
         			<th class="text-center">Amount</th>
         		</tr> -->
            <tr>
              <th class="text-center">Account</th>
                @foreach($report_field as $key => $value)
                  <th class="text-center">{{$value->report_field_label}}</th>
                @endforeach
              <th class="text-center">Balance</th>
            </tr>  
         		<tbody>
         			<!-- date_a
              je_reference_module
              je_reference_id
              c_full_name
              jline_description
              amount -->
     				@foreach($chart_of_account_data as $key2 => $value2)
     				  <tr data-id="{{$key2}}" data-parent="" >
         				<td colspan="{{count($report_field)+1}}"><b>{{$chart_of_account[$key2]}}</b></td>
                <td class="text-right"><text class="total-report">{{currency('PHP', collect($value2)->sum('amount'))}}</text></td>
         			</tr>
         				<?php $balance = 0;?>
     					@foreach($value2 as $key3 => $value3)
  						<tr data-id="{{$key3 }}" data-parent="{{$key2}}">
                <td></td>
                  @if($value3->jline_name_reference == 'vendor')
                    <?php 
                      $value3->c_full_name = $value3->v_full_name;
                    ?>
                  @endif
                  <?php 
                    $value3->amount2 = currency('PHP', $value3->amount);
                  ?>
                @foreach($report_field as $key4 => $value4)
                  <td>
                      {{$value3->$key4}}
                  </td>
                @endforeach
                <?php $balance += $value3->amount; ?>
                <td class="text-right">{{currency('PHP', $balance)}}</td>
              </tr>
    					@endforeach
      				<tr data-id="total-{{$key2}}" data-parent="{{$key2}}">
      					<td colspan="{{count($report_field) + 1}}"><b>Total for {{$chart_of_account[$key2]}}</b></td>
      					<td class="text-right">{{currency('PHP', $balance)}}</td>
      				</tr>	
       			@endforeach
         		</tbody>
         		</table>
         	</div>
      </div>
      <h5 class="text-center">---- {{$now or ''}} ----</h5>
  </div>
</div>