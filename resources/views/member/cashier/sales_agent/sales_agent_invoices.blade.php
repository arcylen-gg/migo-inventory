<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">{{$commission->item_name or ''}} - Invoices</h4>
</div>
<div class="modal-body clearfix">
   	<div class="clearfix">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th class="text-center" width="10px">#</th>
                            <th class="text-center" width="250px">DESCRIPTION</th>
                            <th class="text-center" >PAYMENT DATE</th>
                            <th class="text-center" >AMOUNT</th>
                            <th class="text-center" >COMMISSION</th>
                            <th class="text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="{{$total_inv_amount= 0}}{{$total_comm_amount= 0}}{{$total_pending_comm= 0}}">
                        @if(count($_invoices) > 0)
                            @foreach($_invoices as $key => $invoices)
                                <tr>
                                    <td class="text-center">{{$key+1}}</td>
                                    <td class="text-center">Invoice # {{$invoices->new_inv_id}} ({{$invoices->commission_type}})</td>
                                    <td class="text-center">{{date('m/d/Y',strtotime($invoices->inv_date))}}</td>
                                    <td class="text-center {{$total_inv_amount += $invoices->payment_amount }}">{{currency('P ',$invoices->payment_amount,2)}}</td>
                                    <td class="text-center {{$total_comm_amount += $invoices->commission_amount}}">{{currency('P ',$invoices->commission_amount,2)}}</td>
                                    <td class="text-center">
                                        <a href="javascript:">
                                            @if($invoices->invoice_is_paid == 1)
                                            PAID
                                            @else
                                            <label class="{{$total_pending_comm+= $invoices->commission_amount}}">PENDING</label>
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                        <tr><td colspan="7" class="text-center">NO AGENT YET</td></tr>
                        @endif
                    </tbody>
                    <tfoot>
                    	<tr>
                    		<td colspan="3" class="text-right"><b>TOTAL</b></td>
                    		<td class="text-center"><strong>{{currency('P ',$total_inv_amount)}}</strong></td>
                    		<td class="text-center"><strong>{{currency('P ',$total_comm_amount)}}</strong></td>
                    		<td class="text-center"></td>
                    	</tr>
                        <tr>
                            <td colspan="3" class="text-right"><strong style="color:green">TOTAL PENDING COMMISSION</strong></td>
                            <td></td>
                            <td class="text-center"><strong>{{currency('P ',$total_pending_comm)}}</strong></td>
                            <td></td>
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