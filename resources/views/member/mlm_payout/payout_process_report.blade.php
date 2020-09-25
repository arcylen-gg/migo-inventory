@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Payout Report</span>
            <small>
            Payout report as of <b>{{ $cutoff_date }}</b>
            </small>
            </h1>
            <div class="dropdown pull-right">
                <form method="post">
                    <button type="submit"  class="btn btn-primary"><i class="fa fa-file-excel-o"></i> CREATE PAYOUT TEMPLATE</button>
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block panel-gray "  style="margin-bottom: -10px;">
    <div class="tab-content codes_container" style="min-height: 300px;">
        <div id="all" class="tab-pane fade in active">
            <div class="form-group order-tags"></div>
            <div class="clearfix">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-condensed">
                            <thead style="text-transform: uppercase">
                                <tr>
                                    <th class="text-center">Slot No</th>
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-right" width="140px">Current<br>Wallet</th>
                                    <th class="text-right" width="140px">Gross Pay</th>
                                    <th class="text-right" width="140px">Balance<br>After Payout</th>
                                    <th class="text-right" width="140px">Service Charge</th>
                                    <th class="text-right" width="140px">Other Charge</th>
                                    <th class="text-right" width="140px">Tax</th>
                                    <th class="text-right" width="140px">Net Pay<br>(Take Home Pay)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_slot as $slot)
                                <tr>
                                    <td class="text-center">{{ $slot->slot_no }}</td>
                                    <td class="text-center">{{ $slot->first_name }} {{ $slot->last_name }}</td>
                                    <td class="text-right">{{ $slot->display_wallet }}</td>
                                    <td class="text-right"><b>{{ $slot->display_encash }}</b></td>
                                    <td class="text-right">{{ ($slot->display_remaining == "PHP 0.00" ? "-" : $slot->display_remaining) }}</td>
                                    <td class="text-right">{{ $slot->display_service }}</td>
                                    <td class="text-right">{{ $slot->display_other }}</td>
                                    <td class="text-right">{{ $slot->display_tax }}</td>
                                    <td class="text-right"><b>{{ $slot->display_net }}</b></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center"></th>
                                    <th class="text-center"></th>
                                    <th class="text-right" width="140px"></th>
                                    <th class="text-right" width="140px">{{ $total_payout }}</th>
                                    <th class="text-right" width="140px"></th>
                                    <th class="text-right" width="140px"></th>
                                    <th class="text-right" width="140px"></th>
                                    <th class="text-right" width="140px"></th>
                                    <th class="text-right" width="140px">{{ $total_net }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection