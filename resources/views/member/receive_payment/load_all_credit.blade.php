<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">All Available Credits</h4>
</div>
<form class="global-submit" action="/member/customer/receive_payment/apply_credit_submit" method="post">
<div class="modal-body clearfix">
	<div class="row">
        <div class="clearfix modal-body">
            <div class="form-horizontal">
                @if($customer_id)
                <input type="hidden" name="customer_id" value="{{$customer_id or ''}}">
                <div class="form-group">
                    <h4>Customer Name : {{isset($customer_data) ? ($customer_data->company != '' ? $customer_data->company  : $customer_data->first_name. ' '.$customer_data->last_name) : 'No Customer'}}</h4>
                </div>
                <div class="form-group">
                    @if(count($_credits) > 0)
                    <table class="table table-condensed table-bordered {{$total_credit = 0}}">
                        <thead>
                            <tr>
                                <th class="text-center"><input type="checkbox" class="check-all-credit" name=""></th>
                                <th>Credit Memo Number</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($_credits as $credit)
                        <tr class="{{$total_credit+= $credit->cm_amount}} tr-credit">
                            <td class="text-center">
                                <input type="checkbox" class="td-credit new-checkbox" value="{{$credit->cm_amount}}" name="apply_credit[{{$credit->cm_id}}]" data-content="{{$credit->cm_amount}}"  {{isset($_applied[$credit->cm_id]) ? 'checked' : ''}}>
                            </td>
                            <td>{{$credit->cm_id}}</td>
                            <td class="text-center">{{currency('PHP',$credit->cm_amount)}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="text-right"><b> Total Credit</b> </td>
                            <td class="text-center"><b> {{currency('PHP',$total_credit)}}</b></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right"><b style="color:green"> Total Amount to Apply Credit</b> </td>
                            <td class="text-center"><b class="total-apply-credit"> {{currency('PHP',0.00)}}</b></td>
                        </tr>
                        </tbody>
                    </table>
                    @else
                    <div class="form-group text-center"><h4>No Available Credits </h4></div>
                    @endif                   
                </div>
                @else
                <div class="form-group text-center"><h4>No Customer Selected </h4></div>
                @endif
            </div>
        </div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
	<button class="btn btn-primary btn-custom-primary" type="submit">Apply</button>
</div>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('body').on('click','.check-all-credit', function()
        {
            $('input:checkbox.new-checkbox').not(this).prop('checked', this.checked); 
            compute_credit();        
        });
        $('body').on('click','.td-credit', function()
        {
            $(this).prop('checked', this.checked); 
            compute_credit();        
        });

        compute_credit();
        function compute_credit()
        {
            var total_apply_credit = 0;
            $('.td-credit').each(function(a, b)
            {
                if($(b).is( ":checked" ))
                {
                    total_apply_credit += parseFloat($(b).attr('data-content'));
                }
            });
            $('.total-apply-credit').html('PHP '+total_apply_credit.toFixed(2));
        }   
    }); 
</script>