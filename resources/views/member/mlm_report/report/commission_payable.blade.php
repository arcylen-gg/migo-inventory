@if(count($slot_no)>0)
    <table class="table table-bordered table-striped table-condensed">
        <thead style="text-transform: uppercase">
            <tr>
                <th class="text-center">Slot No</th>
                <th class="text-center">Customer</th>
                <th class="text-right" width="150px">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($slot_no as $p)
            <tr>
                <td class="text-center">{{ $p->slot_no }}</td>
                <td class="text-center">{{ $name[$p->slot_no] }}</td>
                <td class="text-right"><b>{{ currency('PHP',$amount[$p->slot_no]) }}</b></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <th class="text-right" width="150px">{{ $total_payout }}</th>
            </tr>
        </tfoot>
    </table>
    
@else
    <div class="text-center" style="padding: 100px 0;"><b>NO DATA</b></div>
@endif