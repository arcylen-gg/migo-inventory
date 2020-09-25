<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">{{ucwords($agent->first_name.' '.$agent->middle_name.' '.$agent->last_name)}} - Transaction</h4>
</div>
<div class="modal-body clearfix">
   	<div class="clearfix">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th class="text-center" width="10px">#</th>
                            <th class="text-center" width="250px">CUSTOMER NAME</th>
                            <th class="text-center" >TOTAL COMMISSION</th>
                            <th class="text-center" >RELEASED</th>
                            <th class="text-center" >PENDING</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody class="{{$total_overall=0}}{{$total_released=0}}{{$total_pending=0}}">
                        @if(count($_transaction) > 0)
                            @foreach($_transaction as $key => $transaction)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">{{ucwords($transaction->first_name.' '.$transaction->middle_name.' '.$transaction->last_name)}}</td>
                                    <td class="text-center {{$total_overall+= $transaction->orverall_comm}}">{{currency('P ',$transaction->orverall_comm,2)}}</td>
                                    <td class="text-center {{$total_released+= $transaction->released_comm}}">{{currency('P ',$transaction->released_comm,2)}}</td>
                                    <td class="text-center {{$total_pending+= $transaction->pending_comm}}">{{currency('P ',$transaction->pending_comm,2)}}</td>
                                    <td class="text-center">
                                        <a href="javascript:" class="popup" link="/member/cashier/sales_agent/invoices/{{$transaction->commission_id}}" size="lg">View Invoices</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <tr><td colspan="7" class="text-center">NO TRANSACTION YET</td></tr>
                        @endif
                    </tbody>
                    <tfoot>
                    	<tr>
                    		<td colspan="2" class="text-right"><b>TOTAL</b></td>
                    		<td class="text-center"><strong>{{currency('P ',$total_overall)}}</strong></td>
                    		<td class="text-center"><strong>{{currency('P ',$total_released)}}</strong></td>
                    		<td class="text-center"><strong>{{currency('P ',$total_pending)}}</strong></td>
                    		<td class="text-center"></td>
                    	</tr>
                    </tfoot>
                </table>
            </div>
        </div>
   	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
</div>