
<div class="col-md-12 col-sm-6 col-xs-12">
  <div class="info-box">
    <span class="info-box-icon bg-primary"><i class="fa fa-user"></i></span>
    <div class="info-box-content">
      <span class="info-box-text">Register Accounts</span>
      <span class="info-box-number">Per Day</span>
    </div>
  </div>
</div> 
<br>
<br>
<br>
<br>
<br>
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
        	<div style="overflow-x:auto;">
        		<table class="table table-condensed table-bordered">
        			<thead>
        				<thead>   
                            <tr>
                                <th>Day</th>
                                <th>Name</th>
                                <th>Username </th>
                                <th>Slot count</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@foreach($customer_per_day as $key => $value)
                        		@foreach($value as $key2 => $value2)
		                        	<tr>
		                        		<td> @if($key != null) {{$key}} @else Imported @endif</td>
		                        		@if(isset($customer[$key2]))
										<td>{{name_format_from_customer_info($customer[$key2])}}</td>
		                        		<td>{{$customer[$key2]->mlm_username}}</td>
		                        		@else
		                        		<td></td>
		                        		<td></td>
		                        		@endif
		                        		<td>{{$customer[$key2]->count_slot}}</td>
		                        	</tr>
                        		@endforeach
                        	@endforeach
                        </tbody>
        			</thead>
        			<tbody>
        			</tbody>
        		</table>
        	</div>
        </div>
    </div>
</div>    