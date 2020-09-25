<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title">Open Transaction - {{ $vendor->vendor_company}}</h4>
</div>
<form class="global-submit" action="/member/transaction/write_check/apply-transaction" method="post">
    <div class="modal-body">
        <div class="row">
            <div class="clearfix modal-body"> 
                <div class="form-group">
                    <div class="col-md-12">
                        <h4> <i class="fa fa-caret-down"></i> Puchase Order</h4>
                    </div> 
                    <div class="col-md-12">
                        @if(count($_po) > 0)
                        <table class="table table-condensed table-bordered {{ $total_amount = 0 }}">
                            <thead>
                                <tr >
                                    <th></th>
                                    <th class="text-center">Reference Number</th>
                                    <th class="text-center">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_po as $po)
                                <tr class="{{$total_amount += $po->po_balance}}">
                                    <td class="text-center"><input type="checkbox" name="_apply_transaction[{{$po->po_id}}]" class="td-check-po" value="purchase_order" data-content="{{$po->po_balance}}" {{isset($_applied[$po->po_id]) ? 'checked' : ''}}></td>
                                    <td class="text-center">{{$po->transaction_refnum != "" ? $po->transaction_refnum : $po->po_id}}</td>
                                    <td class="text-right">{{currency('PHP',$po->po_balance)}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td colspan="2" class="text-right"><b> Total PO Amount</b> </td>
                                    <td class="text-right"><b> {{currency('PHP',$total_amount)}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text-right"><b style="color:green"> Total Amount to Bill </b> </td>
                                    <td class="text-right"><b class="total-apply-bill"> {{currency('PHP',0.00)}}</b></td>
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
        $('body').on('click','.td-check-po', function()
        {
            $(this).prop('checked', this.checked); 
            compute();
        });

        compute();
        function compute()
        {
            var total_po_amount = 0;
            $('.td-check-po').each(function(a, b)
            {
                if($(b).is( ":checked" ))
                {
                    total_po_amount += parseFloat($(b).attr('data-content'));
                }
            });
            $('.total-apply-bill').html('PHP '+total_po_amount.toFixed(2));
        }   
    }); 
</script>
