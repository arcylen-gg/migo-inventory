<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title">Open Transaction</h4>
</div><form class="global-submit" action="/member/transaction/purchase_order/apply-transaction" method="post">
    <div class="modal-body">
    	<div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-group so-div">
                    <div class="col-md-12">
                        <h4> <i class="fa fa-caret-down"></i> Sales Order</h4>
                    </div> 
                    <div class="col-md-12">
                        @if(count($_so) > 0)
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">Reference Number</th>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_so as $so)
                                <tr>
                                    <td class="text-center"><input type="checkbox" name="_apply_transaction[{{$so->est_id}}]" class="td-check-received-so" value="sales_order" {{isset($_applied[$so->est_id]) ? 'checked' : ''}}></td>
                                    <td class="text-center">{{$so->transaction_refnum != "" ? $so->transaction_refnum : $so->est_id}}</td>
                                    <td class="text-center">{{$so->title_name.' '.$so->first_name.' '.$so->middle_name.' '.$so->last_name}}</td>
                                    <td class="text-right">{{currency('PHP',$so->est_overall_price)}}</td>
                                </tr>
                                @endforeach
                            </tbody>                        
                        </table>
                        @else
                        <label class="text-center form-control">No Transaction</label>
                        @endif
                    </div>
                </div>
                <div class="form-group si-div">
                    <div class="col-md-12">
                        <h4> <i class="fa fa-caret-down"></i> Sales Invoice</h4>
                    </div> 
                    <div class="col-md-12">
                        @if(count($_si) > 0)
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">Reference Number</th>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_si as $si)
                                <tr>
                                    <td class="text-center"><input type="checkbox" name="_apply_transaction[{{$si->inv_id}}]" class="td-check-received-si" value="invoice" {{isset($_applied[$si->inv_id]) ? 'checked' : '' }}></td>
                                    <td class="text-center">{{$si->transaction_refnum != "" ? $si->transaction_refnum : $si->inv_id}}</td>
                                    <td class="text-center">{{ucfirst($si->company != "" ? $si->company : $si->title_name." ".$si->first_name." ".$si->middle_name." ".$si->last_name." ".$si->suffix_name)}}</td>
                                    <td class="text-center">{{currency('PHP',$si->inv_overall_price)}}</td>
                                </tr>
                                @endforeach
                            </tbody>                        
                        </table>
                        @else
                        <label class="text-center form-control">No Transaction</label>
                        @endif
                    </div>
                </div>

                <div class="form-group sr-div">
                    <div class="col-md-12">
                        <h4> <i class="fa fa-caret-down"></i> Sales Receipt</h4>
                    </div> 
                    <div class="col-md-12">
                        @if(count($_sr) > 0)
                        <table class="table table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">Reference Number</th>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_sr as $sr)
                                <tr>
                                    <td class="text-center"><input type="checkbox" name="_apply_transaction[{{$sr->inv_id}}]" class="td-check-received-sr" value="invoice" {{isset($_applied[$sr->inv_id]) ? 'checked' : '' }}></td>
                                    <td class="text-center">{{$sr->transaction_refnum != "" ? $sr->transaction_refnum : $sr->inv_id}}</td>
                                    <td class="text-center">{{ucfirst($si->company != "" ? $si->company : $si->title_name." ".$si->first_name." ".$si->middle_name." ".$si->last_name." ".$si->suffix_name)}}</td>
                                    <td class="text-center">{{currency('PHP',$sr->inv_overall_price)}}</td>
                                </tr>
                                @endforeach
                            </tbody>                        
                        </table>
                        @else
                        <label class="text-center form-control">No Transaction</label>
                        @endif
                    </div>
                </div>
            </div>
    	</div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">Close</button>
    	<button class="btn btn-primary btn-custom-primary" type="submit">Add</button>
    </div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
</form>
<script type="text/javascript">
    $(document).ready(function()
    {
        $('body').on('click','.td-check-received-so', function()
        {
            $(this).prop('checked', this.checked); 
            
            var count = $('input:checkbox:checked').length;
            if(count > 0)
            {
                $('.si-div').hide();
                $('.sr-div').hide();
            }
            else
            {
                $('.so-div').show();
                $('.si-div').show();
                $('.sr-div').show();
            }
        });

        $('body').on('click','.td-check-received-si', function()
        {
            $(this).prop('checked', this.checked); 
            
            var count = $('input:checkbox:checked').length;

            if(count > 0)
            {
                $('.so-div').hide();
                $('.sr-div').hide();
            }
            else
            {
                $('.so-div').show();
                $('.si-div').show();
                $('.sr-div').show();
            }
        });
        $('body').on('click','.td-check-received-sr', function()
        {
            $(this).prop('checked', this.checked); 
            
            var count = $('input:checkbox:checked').length;

            if(count > 0)
            {
                $('.so-div').hide();
                $('.si-div').hide();
            }
            else
            {
                $('.so-div').show();
                $('.si-div').show();
                $('.sr-div').show();
            }
        });
    }); 
</script>