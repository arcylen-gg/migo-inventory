@if($_slot)
    <table class="table table-bordered table-condensed custom-column-table">
        <thead>
            <tr>
                @foreach($_slot[0] as $column)
                <th class="text-center">{{ $column["label"] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody> 
                @foreach($_slot as $slot)
                    <tr>
                        @foreach($slot as $column)
                        <td class="text-center">{!! $column["data"] !!}</td>
                        @endforeach
                    </tr>
                @endforeach
        </tbody>
    </table>
    <div class="pull-left" style="margin: 10px;">
        <div style="display: inline-block; padding: 10px; font-weight: bold; width: 200px; text-align: center; color: #999; border: 1px solid #ddd; background-color: #fff;">TOTAL EARNINGS <br><span style="color: #333;">{{ $total_slot_earnings }}</span></div>
        <div style="display: inline-block; padding: 10px; font-weight: bold; width: 200px; text-align: center; color: #999; border: 1px solid #ddd; background-color: #fff;"">TOTAL WALLET <br><span style="color: #333;">{{ $total_slot_wallet }}</span></div>
        <div style="display: inline-block; padding: 10px; font-weight: bold; width: 200px; text-align: center; color: #999; border: 1px solid #ddd; background-color: #fff;"">TOTAL PAYOUT <br><span style="color: #333;">{{ $total_payout }}</span></div>
    </div>
    <div class="pull-right paginat">{!! $_slot_page->render() !!}</div>
@else
    <div class="text-center" style="padding: 200px 0">NO RESULT FOUND</div>
@endif