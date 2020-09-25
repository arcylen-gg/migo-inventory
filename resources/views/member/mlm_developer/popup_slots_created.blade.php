<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">DOWNLINE OF SLOT#{{$owner->slot_no}}</h4>
</div>
<div class="modal-body clearfix">
	<div class="form-group">
		<h3>{{ strtoupper($owner->last_name.', '.$owner->first_name) }}</h3>
	</div>
	<div class="form-group">
	    <table class="table table-bordered table-striped table-condensed">
	        <thead style="text-transform: uppercase">
	            <tr> 
	            	<th class="text-center" width="15px">#</th>
	                <th class="text-center" width="250px">LEVEL</th>
	                <th class="text-center" width="200px">SLOT NO</th>
	                <th class="text-center" width="200px">NAME</th>
	            </tr>
	        </thead>
	        <tbody>
	        	@if(count($_slot) > 0)
	            @foreach($_slot as $key => $slot)
	            <tr>
	                <td class="text-center">{{$key+1}}</td>
	                <td class="text-center">LEVEL {{ $slot->sponsor_tree_level }}</td>
	                <td class="text-center">SLOT# {{ $slot->slot_no }}</td>
	                <td class="text-center">{{ ucwords($slot->first_name.' '.$slot->middle_name.' '.$slot->last_name) }}</td>
	            </tr>
	            @endforeach
	            @else
	            <tr>
	            	<td class="text-center" colspan="4">NO DOWNLINE YET</td>
	            </tr>
	            @endif
	        </tbody>
	    </table>		
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>