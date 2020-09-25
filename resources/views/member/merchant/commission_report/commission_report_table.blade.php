<table class="table table-bordered table-striped table-condensed">
    <thead style="text-transform: uppercase">
        @if(count($table)>0)
        <tr>
            <th class="text-center" width="200px">Pin</th>
            <th class="text-center">Activation</th>
            <th class="text-center" width="300px">Date Used</th>
            <th class="text-center" >Amount</th>
            <th class="text-center" >Commission Percentage</th>
            <th class="text-center" >Receivable Amount</th>
            <!-- <th class="text-center" ></th> -->
        </tr>
        @else
        <th class="text-center">No Data</th>
        @endif
    </thead>
    <tbody>
        @foreach($table as $t)
        <tr>
            <td class="text-center">{{$t->mlm_pin}}</td>
            <td class="text-center">{{$t->mlm_activation}}</td>
            <td class="text-center">{{date("m/d/Y", strtotime($t->record_log_date_updated))}}</td>
            <td class="text-left" style="text-indent: 40px;">{{currency('Php',$t->item_price)}}</td>
            <td class="text-center">{{$t->merchant_commission_percentage}}%</td>
            <td class="text-left" style="text-indent: 40px;">{{currency('Php',$t->item_price*($t->merchant_commission_percentage/100))}}</td>
            <!-- <td class="text-center"><a href="#">Action</a></td> -->
        </tr>
        @endforeach
        @if(count($table)>0)
        <tr>
            <td colspan="4"></td>
            <th class="text-center">Total Commission </th>
            <td class="text-left" style="text-indent: 40px;">{{currency('Php',$totalcommission)}}</td>
            <!-- <td></td> -->
        </tr>
        @endif
    </tbody>
</table>

<div class="clearfix">
    <div class="pull-right">
        {!! $table->render() !!}
    </div>
</div>