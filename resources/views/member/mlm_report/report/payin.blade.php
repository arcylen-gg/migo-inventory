@if(count($payin)>0)
    <table class="table table-bordered table-striped table-condensed">
        <thead style="text-transform: uppercase">
            <tr>
                <th class="text-center">Payin Date</th>
                <th class="text-center">Slot No</th>
                <th class="text-center">Customer</th>
                <th class="text-center" width="120px">Email</th>
                <th class="text-right" width="150px">Contact No</th>
                <th class="text-right" width="120px">Membership Type</th>
                <th class="text-right" width="130px">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payin as $p)
            <tr>
                <td class="text-center">{{ $p->slot_created_date }}</td>
                <td class="text-center">{{ $p->slot_no }}</td>
                <td class="text-center">{{ $p->first_name }} {{ $p->last_name }}</td>
                <td class="text-center">{{ $p->email }}</td>
                <td class="text-right">{{ $p->contact }}</td>
                <td class="text-right">{{ $p->membership_name }}</td>
                <td class="text-right"><b>{{ currency('Php',$p->membership_price) }}</b></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th class="text-right" width="150px">{{ $total_payin }}</th>
            </tr>
        </tfoot>
    </table>
    
@else
    <div class="text-center" style="padding: 100px 0;"><b>NO DATA</b></div>
@endif