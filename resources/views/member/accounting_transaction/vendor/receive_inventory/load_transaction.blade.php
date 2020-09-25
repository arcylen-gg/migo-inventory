<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Open Transaction - {{ $vendor->vendor_company }}</h4>
</div>
<form class="global-submit" action="/member/transaction/receive_inventory/apply-transaction" method="post">
    <div class="modal-body">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-group po-div">
                    <div class="col-md-12">
                        <h4> <i class="fa fa-caret-down"></i> Purchase Order </h4>
                    </div>
                    <div class="col-md-12">
                        @if(count($_po) > 0)
                        <table class="table table-condensed table-bordered {{$total_po_amount = 0}}">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">Reference Number</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_po as $po)
                                    @if($po->po_balance != 0)
                                    <tr class="{{ $total_po_amount += $po->po_balance}}">
                                        <td class="text-center">
                                            <input type="checkbox" name="_apply_transaction[{{$po->po_id}}]" class="td-check-received-po td-po-amount" value="purchase_order" data-content="{{$po->po_balance}}" {{isset($_applied[$po->po_id]) ? 'checked' : ''}}></td>
                                        <td class="text-center" value="{{$po->transaction_refnum != '' ? $po->transaction_refnum : $po->po_id}}">{{$po->transaction_refnum != "" ? $po->transaction_refnum : $po->po_id}}</td>
                                        <td class="text-right" value="{{currency('PHP',$po->po_balance)}}">{{currency('PHP',$po->po_balance)}}</td>
                                    </tr>
                                    @endif
                                @endforeach
                                <tr>
                                    <td colspan="2" class="text-right"><b> Total PO Amount</b> </td>
                                    <td class="text-right"><b> {{currency('PHP',$total_po_amount)}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right"><b style="color:green"> Total Amount to Received</b> </td>
                                    <td class="text-right"><b class="total-po-amount-received"> {{currency('PHP',0.00)}}</b></td>
                                </tr>
                            </tbody>                        
                        </table>
                        @else
                        <label class="text-center form-control">No Transaction</label>
                        @endif
                    </div>
                </div>
                <div class="form-group dm-div">
                    <div class="col-md-12">
                        <h4> <i class="fa fa-caret-down"></i> Debit Memo</h4>
                    </div> 
                    <div class="col-md-12">
                        @if(count($_dm) > 0)
                        <table class="table table-condensed table-bordered {{$total_dm_amount = 0}}">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-center">Reference Number</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_dm as $dm)
                                <tr class="{{$total_dm_amount += $dm->db_amount}}">
                                    <td class="text-center">
                                        <input type="checkbox" name="_apply_transaction[{{$dm->db_id}}]" class="td-check-received-dm td-dm-amount" value="debit_memo" data-content="{{$dm->db_amount}}" {{isset($_applied[$dm->db_id]) ? 'checked' : ''}}></td>
                                    <td class="text-center">{{$dm->transaction_refnum != "" ? $dm->transaction_refnum : $dm->db_id}}</td>
                                    <td class="text-right">{{currency('PHP',$dm->db_amount)}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" class="text-right"><b> Total DM Amount</b> </td>
                                    <td class="text-right"><b> {{currency('PHP',$total_dm_amount)}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right"><b style="color:green"> Total Amount to Received </b> </td>
                                    <td class="text-right"><b class="total-dm-amount-received"> {{currency('PHP',0.00)}}</b></td>
                                </tr>
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
        $('body').on('click','.td-check-received-po', function()
        {
            $(this).prop('checked', this.checked); 
            
            var count = $('input:checkbox:checked').length;

            if(count > 0)
            {
                $('.dm-div').hide();
                compute_received();
            }
            else
            {
                $('.dm-div').show();
                compute_received();
            }
        });

        $('body').on('click','.td-check-received-dm', function()
        {
            $(this).prop('checked', this.checked); 
            
            var count = $('input:checkbox:checked').length;

            if(count > 0)
            {
                $('.po-div').hide();
                compute_received();
            }
            else
            {
                $('.po-div').show();
                compute_received();
            }
        });

        compute_received();
        function compute_received()
        {
            var total_po_amount_received = 0;
            $('.td-po-amount').each(function(a, b)
            {
                if($(b).is( ":checked" ))
                {
                    total_po_amount_received += parseFloat($(b).attr('data-content'));
                }
            });
            var total_dm_amount_received = 0;
            $('.td-dm-amount').each(function(a, b)
            {
                if($(b).is( ":checked" ))
                {
                    total_dm_amount_received += parseFloat($(b).attr('data-content'));
                }
            });
            $('.total-po-amount-received').html('PHP '+total_po_amount_received.toFixed(2));
            $('.total-dm-amount-received').html('PHP '+total_dm_amount_received.toFixed(2));
        }   
    }); 
</script>
