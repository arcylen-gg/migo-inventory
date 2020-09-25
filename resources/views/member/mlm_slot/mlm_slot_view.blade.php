<form class="global-submit form-horizontal" role="form" action="link_submit_here" method="post">
	<div class="modal-header">
		<button type="button" class="close close_masda" data-dismiss="modal">×</button>
		<h4 class="modal-title">View Slot</h4>
	</div>
	<div class="modal-body add_new_package_modal_body clearfix">
		<div class="panel panel-default panel-block panel-title-block panel-gray">
		@if($slot != null)
		
			<ul class="nav nav-tabs">
			  <li class="active"><a data-toggle="tab" href="#slot_info">Slot Info</a></li>
			  <li><a data-toggle="tab" href="#wallet">Wallet</a></li>
			  <li><a href="javascript:" class="btn btn-primary popup" link="/member/customer/customeredit/{{$slot->customer_id}}" size="lg" onClick="close_thisss()">Customer Details</a></li>
			  <li><a data-toggle="tab" href="#referral">Referrals</a></li></li>
			  <!-- <li><a data-toggle="tab" href="#genealogy">Sponsor Genealogy</a></li> -->
			  <li><a data-toggle="tab" href="#tree">Tree</a></li>
			</ul>
			<div class="col-md-12">
				<div class="tab-content">
				  <div id="slot_info" class="tab-pane fade in active">
				    <h3>{{$slot->slot_no}}</h3>
				    <h4>Membership: {{$slot->membership_name}}</h4>
				    <p style="color:gray">Slot Creation Date: {{$slot->slot_created_date}}</p>
				    <p style="color:gray">Membership Type: {{$slot->slot_status}}</p>
				    <p style="color:gray">Sponsor: @if($slot_sponsor != null){{$slot_sponsor->slot_no}} @endif</p>
				    <p style="color:gray" class="active_slot">Status: @if($slot->slot_active == 0) Active (<a href="javascript:" onClick="setactive({{$slot->slot_id}})">Set as Inactive</a>) @else Inactive (<a href="javascript:" onClick="setactive({{$slot->slot_id}})">Set as Active</a>) @endif  </p>
				  </div>
				  <div id="wallet" class="tab-pane fade">
				    <h3>Wallet</h3>
				    <p style="color:gray">Logs</p>
				    <div class="table-reponsive">
				    	<table class="table table-condensed">
				    		<thead>
				    			<th>Amount</th>
				    			<th>Complan</th>
				    		</thead>
				    		<tbody>
				    			@if(count($plan_settings) != 0)
				    				@foreach($plan_settings as $key => $value)
				    				<tr>
				    					<td>{{$value->marketing_plan_label}}</td>
				    					<td>{{$plan_ernings[$key]}}</td>
				    				</tr>
				    				@endforeach
				    			@else
				    				<tr>
				    					<td colspan="4"><center>No Wallet Logs</center></td>
				    				</tr>
				    			@endif
				    		</tbody>
				    	</table>
				    </div>
				  </div>
				  <div id="referral" class="tab-pane fade">
				    <h3>Referral</h3>
				    <div class="table-responsive">
				    	<table class="table table-condensed">
				    		<thead>
				    			<th>Slot</th>
				    			<th>Slot Creation Date</th>
				    			<th>Name</th>
				    			<th>Membership</th>
				    			<th>Membership Type</th>
				    		</thead>
				    		<tbody>
				    			@if(count($slot_refferals) != 0)
				    				@foreach($slot_refferals as $key => $value)
				    					<tr>
				    						<td>{{$value->slot_no}}</td>
				    						<td>{{$value->slot_created_date}}</td>
				    						<td>{{$value->first_name}} {{$value->middle_name}} {{$value->last_name}}</td>
				    						<td>{{$value->membership_name}}</td>
				    						<td>{{$value->slot_status}}</td>
				    					</tr>
				    				@endforeach
				    			@else
				    				<tr>
				    					<td colspan="6"><center>No Referrals</center></td>
				    				</tr>		
				    			@endif
				    		</tbody>
				    	</table>
				    </div>
				  </div>
				  <div id="genealogy" class="tab-pane fade">
				  <style type="text/css">

				  </style>
				  	<h3>Sponsor Genealogy</h3>
				  	<p style="color:gray;">Tree</p>
				  	<iframe src="/member/mlm/slot/genealogy?id={{$slot->slot_id}}&mode=sponsor" frameborder="0" style="overflow:hidden; height:500px; width:100%"></iframe>
				  </div>
				  <div id="tree" class="tab-pane fade">
					<h3>Tree</h3>
				  	<p style="color:gray;">Level</p>

				  	@if($tree_per_level != null)
				  		<ul class="nav nav-tabs">
					  		@foreach($tree_per_level as $key => $value)
					  			<li @if($key == 1) class="active" @endif>
					  				<a data-toggle="tab" href="#{{$key}}">Level {{$key}}</a>
					  			</li>
					  		@endforeach
				  		</ul>
				  		<div class="tab-content">
					  		@foreach($tree_per_level as $key => $value)
					  			<div id="{{$key}}" class="tab-pane fade  {{$key == 1 ? 'in active' : ''}}">
					  			<table class="table table-condensed">
						    		<thead>
						    			<th>Slot</th>
						    			<th>Slot Creation Date</th>
						    			<th>Name</th>
						    			<th>Membership</th>
						    			<th>Membership Type</th>
						    		</thead>
						    		<tbody>
						    			@foreach($value as $key2 => $value2)
						  					<tr>
					    						<td>{{$value2->slot_no}}</td>
					    						<td>{{$value2->slot_created_date}}</td>
					    						<td>{{$value2->first_name}} {{$value2->middle_name}} {{$value2->last_name}}</td>
					    						<td>{{$value2->membership_name}}</td>
					    						<td>{{$value2->slot_status}}</td>
					    					</tr>
						  				@endforeach
						    		</tbody>
						    	</table>	
					  			</div>
					  		@endforeach
					  	</div>	
				  	@else
				  	<center> No Active Sponsor Tree</center>
				  	@endif				  	
				  </div>
				  
				</div>
			</div>
		@else
		<center>INVALID SLOT.</center>	
		@endif	
		</div>
	</div>
</form>
<script>
	function setactive (slot) {
		// body...
		$('.active_slot').html('<center><div class="loader-16-gray"></div></center>');
		$('.active_slot').load('/member/mlm/slot/set/inactive/'+ slot);
		
		if (typeof ajax_load_membership == 'function')
        {
            ajax_load_membership();
        }
	}
	function close_thisss()
	{
		$('.close_masda').click();
	}
</script>