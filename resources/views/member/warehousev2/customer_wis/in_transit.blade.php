<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">In-Transit - {{strtoupper($item->item_name)}}</h4>
</div>

<input type="hidden" name="_token" value="{{csrf_token()}}">
<div class="modal-body">
	<div class="row">
        <div class="clearfix modal-body">
            <div class="form-group">
                <div class="col-md-12">
                	<div class="table-responsive" style="width: 100%">
		                <table class="table table-condensed table-bordered">
		                    <thead>
		                        <tr>
		                            <th>Transaction #</th>
		                            <th>Receiver</th>
		                            <th>Truck</th>
		                            <th>QTY</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@if(count($_intransit_breakdown) > 0)
		                    		@foreach($_intransit_breakdown as $intransit)
			                    	<tr>
			                    		<td>{{$intransit['trans_num']}}</td>
			                    		<td>{{$intransit['receiver']}}</td>
			                    		<td>{{$intransit['truck']}}</td>
			                    		<td>{{$intransit['qty']}}</td>
			                    	</tr>
			                    	@endforeach
		                    	@else
		                    	<tr>
		                    		<td colspan="4" class="text-center">NO TRANSACTION YET</td>
		                    	</tr>
		                    	@endif
		                    </tbody>
		                </table>
                	</div>
        		</div>
        	</div>
       	</div>
    </div>
 </div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>