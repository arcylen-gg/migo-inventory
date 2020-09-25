<div data-page="report" class="page">
    <div class="navbar">
        <div class="navbar-inner">
            <div class="left"><a href="index.html" class="back link icon-only"><i class="icon icon-back"></i></a></div>
            <div class="center">Reports</div>
            <div class="right"><a href="#" class="open-panel link icon-only"><i class="icon icon-bars"></i></a></div>
        </div>
    </div>
    <div class="page-content">
        <div class="report-view">
            <div class="select-holder">
                <div class="row">
                    <div class="col-50">
                        <div class="desc">All rewards logs are shown here.</div>
                    </div>
                    <div class="col-50">
                        <select>
                            <option>23</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="data-table card">
                <table>
                    <thead>
                        <tr>
                            <th>SLOT</th>
                            <th class="label-cell">DETAILS</th>
                            <th class="numeric-cell">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_rewards as $reward)
                        <tr>
                            <td>{{ $reward->slot_no }}</td>
                            <td class="label-cell">
                                <div class="rewards-log">{!! $reward->log !!}</div>
                            </td>
                            <td class="numeric-cell"><a href="javascript:"><b>{!! $reward->display_wallet_log_amount !!}</b></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>