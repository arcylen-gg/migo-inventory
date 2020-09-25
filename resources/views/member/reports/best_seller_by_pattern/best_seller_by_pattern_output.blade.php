<div class="report-container">
    <div class="panel panel-default panel-block panel-title-block panel-report load-data">
        <div class="panel-heading load-content">
            @include('member.reports.report_header')
            <div class="table-reponsive">
                <table class="table table-condensed collaptable">
                    <thead style="text-transform: uppercase">
                        <tr>
                            <th class="text-center">Item Name</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($_sales) > 0)
                            @foreach($_sales as $sale)
                                <tr>
                                    <td class="text-center">{{$sale->item_name}}</td>
                                    <td class="text-center">{{$sale->item_qty}}</td>
                                    <td class="text-center">{{currency('PHP ',$sale->item_amount)}}</td>
                                </tr>
                            @endforeach
                        @else
                        <tr><td colspan="6" class="text-center"> NO SALES YET</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>