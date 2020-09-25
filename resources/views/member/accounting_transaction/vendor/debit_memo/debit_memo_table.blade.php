<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <th width="10px">NO</th> 
            <th >NAME</th>
            <th class="text-center">REFERENCE NUMBER</th>
            <th class="text-center">TRANSACTION DATE</th>
            <th class="text-center" width="200px">TOTAL PRICE</th>
            <th class="text-center" width="200px"></th>
        </tr>
    </thead>
    <tbody>
        @if(count($_debit_memo) > 0)
            @foreach($_debit_memo as $key => $dm)
                <tr>
                    <td class="text-center">{{ $page == 1 ? $key + 1 : $number + $key + 1 }}</td> 
                    <td>
                        {{ucwords($dm->vendor_company)}} <br>
                        <small> {{ucwords($dm->vendor_title_name.' '.$dm->vendor_first_name.' '.$dm->vendor_middle_name.' '.$dm->vendor_last_name.' '.$dm->vendor_suffix_name)}} </small>
                    </td>
                    <td class="text-center">{{$dm->transaction_refnum == "" ? $dm->db_id : $dm->transaction_refnum}}</td>
                    <td class="text-center">{{date('F d, Y',strtotime($dm->db_date))}}</td>
                    <td class="text-center">{{currency('PHP',$dm->db_amount)}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                @if($dm->archived == 0)
                                <li><a href="/member/transaction/debit_memo/create?id={{$dm->db_id}}">Edit</a></li>
                                @endif
                                <li><a target="_blank" href="/member/transaction/debit_memo/print?id={{$dm->db_id}}">Print</a></li>
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
<div class="pull-right">{!! $_debit_memo->render() !!}</div>