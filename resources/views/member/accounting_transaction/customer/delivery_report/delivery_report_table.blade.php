 <table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th >NAME</th>
            <th class="text-center">REFERENCE NUMBER</th>
            <th class="text-center">TRANSACTION DATE</th>
            <th class="text-center" width="200px">TOTAL PRICE</th>
            <th class="text-center" width="200px"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_delivery_report) > 0)
            @foreach($_delivery_report as $dr)
                <tr>
                    <td>
                        {{ucwords($dr->company)}} <br>
                        <small> {{ucwords($dr->first_name.' '.$dr->middle_name.' '.$dr->last_name)}} </small>
                    </td>
                    <td class="text-center">{{$dr->transaction_refnum != "" ? $dr->transaction_refnum : $dr->new_inv_id}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($dr->dr_date))}}</td>
                    <td class="text-center">{{currency('',$dr->inv_overall_price)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                <li><a href="/member/transaction/delivery_report/create?id={{$dr->inv_id}}">Edit Delivery report</a></li>
                                <li><a target="_blank" href="/member/transaction/delivery_report/print?id={{$dr->inv_id}}">Print</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>                                    
            @endforeach
        @else
            <tr><td colspan="5" class="text-center">NO TRANSACTION YET</td></tr>
        @endif
    </tbody>
</table>
<div class="pull-right">{!! $_delivery_report->render() !!}</div>