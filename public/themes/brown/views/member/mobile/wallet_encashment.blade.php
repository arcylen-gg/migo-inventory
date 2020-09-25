<div data-page="report" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="/members" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Wallet Encashment</div>
            {{-- <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div> --}}
        </div>
    </div>
    <div class="page-content">
        <div class="report-view">
            <div class="select-holder">
                <div class="row">
                    <div class="col-100">
                        <div class="desc">In this tab you can request/view encashment history.</div>
                    </div>
                </div>
            </div>
            <div class="title">Enchashment History</div>
            <div class="data-table card">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-left" width="200px">Date</th>
                            <th class="text-center">SLOT</th>
                            <th class="text-center" width="100px">Method</th>
                            <th class="text-center" width="200px">Status</th>
                            <th class="text-right" width="180px">Amount</th>
                            <th class="text-right" width="150px">Tax</th>
                            <th class="text-right" width="150px">Fee</th>
                            <th class="text-right" width="180px">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($_encashment) > 0)
                            @foreach($_encashment as $encashment)
                            <tr>
                                <td class="text-left">{{ $encashment->display_date }}</td>
                                <td class="text-center">
                                    <div>{{ $encashment->slot_no }}</div>
                                </td>
                                <td class="text-center">{!! $encashment->log !!}</td>
                                <td class="text-center"><b>{{ $encashment->wallet_log_payout_status }}</b></td>
                                <td class="text-right"><b>{!! $encashment->display_wallet_log_request !!}</b></td>
                                <td class="text-right">{!! $encashment->display_wallet_log_tax !!}</td>
                                <td class="text-right">{!! $encashment->display_wallet_log_service_charge !!}</td>
                                <td class="text-right"><a href='javascript:'><b>{!! $encashment->display_wallet_log_amount !!}</b></a></td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" style="padding: 50px;" colspan="8">NO PAYOUT RELEASE YET</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot style="background-color: #f3f3f3; font-size: 15px;">
                        <tr>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-center"></td>
                            <td class="text-center"><b></b></td>
                            <td class="text-right"><b>{{ $total_payout }}</b></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>