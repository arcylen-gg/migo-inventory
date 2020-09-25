<div class="report-container" style="overflow: hidden;">
	<div class="report-header clearfix">
		<div class="animated fadeInLeft left">
			<div class="icon">
				<img src="/themes/{{ $shop_theme }}/img/report-icon.png">
			</div>
    		<div class="text">
    				<div class="name">Reports</div>
    				<div class="sub">All reward logs are shown here. </div>
    		</div>
		</div>
		<div class="animated fadeInRight right">
			<div class="search">
				<select class="form-control">
					<option>All Slots</option>
				</select>
			</div>
		</div>
	</div>
	
	@if(count($_codes) > 0)
	<h3 class="animated slideInDown text-center">My Notifications</h3>
    @endif
	
	<div class="report-content">
        <div class="animated fadeInUp holder">
          	<div class="table-responsive">
          		<table class="table">
        	  		<thead>
        	  			<tr>
        	  				<th class="text-center" width="200px">DATE</th>
        	  				<th class="text-center" width="100px">SLOT</th>
        	  				<th class="text-left">DETAILS</th>
        	  				<th class="text-right" width="200px">AMOUNT</th>
        	  			</tr>
        	  		</thead>
        	  		<tbody>
        	  			@if(count($_rewards) > 0)
        		  			@foreach($_rewards as $reward)
        		  			<tr>
        		  				<td class="text-center">
        		  					<div><b>{{ $reward->display_date }}</b></div>
        		  					<div>{{ $reward->time_ago }}</div>
        		  				</td>
        		  				<td class="text-center">
        		  					<div>{{ $reward->slot_no }}</div>
        		  				</td>
        		  				<td class="text-left">{!! $reward->log !!}</td>
        		  				<td class="text-right"><b>{!! $reward->display_wallet_log_amount !!}</b></td>
        		  			</tr>
        		  			@endforeach
        		  		@else
        		  			<tr class="text-center" >
        		  				<td colspan="4">NO REWARD YET</td>
        		  			</tr>
        	  			@endif
        	  		</tbody>
        	  	</table>
          	</div>
          	<div class="clearfix">
        	  	<div class="pull-right">
        	  		{!! session('notification_paginate') !!}
        	  	</div>
          	</div>
        </div>
	</div>
	
	<hr>
	
	@if(count($_codes) > 0)
	<h3 class="text-center">Purchased Kits and Codes</h3>
	<div class="report-content">
		<div class="animated fadeInUp holder">
		  	<div class="table-responsive">
		  		<table class="table">
			  		<thead>
			  			<tr>
			  				<th class="text-left" width="200px">PIN</th>
			  				<th class="text-left" width="200px">ACTIVATION</th>
			  				<th class="text-center">STATUS</th>
			  				<th class="text-center">USED BY</th>
			  				<th></th>
			  			</tr>
			  		</thead>
			  		<tbody>
			  			@foreach($_codes as $code)
			  			<tr>
			  				<td class="text-left">{{ $code->mlm_pin }}</td>
			  				<td class="text-left">{{ $code->mlm_activation }}</td>
			  				<td class="text-center">{{ $code->item_in_use }}</td>
			  				<td class="text-center">{{ $code->used_by }}</td>
			  			</tr>
			  			@endforeach
			  		</tbody>
			  	</table>
		  	</div>
		</div>
	</div>
	@endif

	@if($shop_id == 47)
	<h3 class="text-center">Points Log</h3>
	<div class="report-content">
		<div class="animated fadeInUp holder">
		  	<div class="table-responsive">
		  		<table class="table">
        	  		<thead>
        	  			<tr>
        	  				<th class="text-center" width="200px">DATE</th>
        	  				<th class="text-center" width="100px">SLOT</th>
        	  				<th class="text-left">DETAILS</th>
        	  				<th class="text-right" width="200px">TYPE</th>
        	  				<th class="text-right" width="200px">AMOUNT</th>
        	  			</tr>
        	  		</thead>
        	  		<tbody>
        	  			@if(count($_rewards_points) > 0)
        		  			@foreach($_rewards_points as $reward)
        		  			<tr>
        		  				<td class="text-center">
        		  					<div><b>{{ $reward->display_date }}</b></div>
        		  					<div>{{ $reward->time_ago }}</div>
        		  				</td>
        		  				<td class="text-center">
        		  					<div>{{ $reward->slot_no }}</div>
        		  				</td>
        		  				<td class="text-left">{!! $reward->log !!}</td>
        		  				<td class="text-right">{{$reward->points_log_type}}</td>
        		  				<td class="text-right"><b>{!! $reward->log_amount !!}</b></td>
        		  			</tr>
        		  			@endforeach
        		  		@else
        		  			<tr class="text-center" >
        		  				<td colspan="4">NO REWARD YET</td>
        		  			</tr>
        	  			@endif
        	  		</tbody>
			  	</table>
		  	</div>
		</div>
	</div>
	@endif

</div>
