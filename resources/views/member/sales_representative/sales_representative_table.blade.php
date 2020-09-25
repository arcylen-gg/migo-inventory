<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        <tr>
            <td class="text-center">#</td>
            <td class="text-center">Employee Number</td>
            <td class="text-center">Name</td>
            <td class="text-center">Contact Number</td>
            <td class="text-center">Address</td>
            <td class="text-center"></td>
        </tr>
    </thead>
    <tbody>
        @if(count($_sales_rep) > 0)
            @foreach($_sales_rep as $key => $salesrep)
                <tr>
                    <td class="text-center">{{$key+1}}</td>
                    <td class="text-center">{{$salesrep->sales_rep_employee_number}}</td>
                    <td class="text-center">{{$salesrep->sales_rep_first_name.' '.$salesrep->sales_rep_middle_name.' '.$salesrep->sales_rep_last_name}}</td>
                    <td class="text-center">{{$salesrep->sales_rep_contact_no}}</td>
                    <td class="text-center">{{$salesrep->sales_rep_address}}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-custom">
                                <li><a class="popup" link="/member/utilities/create-sales-rep?sales_rep_id={{$salesrep->sales_rep_id}}">Edit Sales Representative</a></li>
                                @if($salesrep->sales_rep_archived == 0)
                                <li><a class="popup" link="/member/utilities/sales-rep-archive?action=archive&sales_rep_id={{$salesrep->sales_rep_id}}">Archive</a></li>
                                @else
                                <li><a class="popup" link="/member/utilities/sales-rep-archive?action=restore&sales_rep_id={{$salesrep->sales_rep_id}}">Restore</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    </td>
                </tr>
            @endforeach
        @else
        <tr><td  colspan="6" class="text-center"> NO SALES REPRESENTATIVE</td></tr>
        @endif
    </tbody>
</table>

<div class="pull-right">{!! $_sales_rep->render() !!}</div>