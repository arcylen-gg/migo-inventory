@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Finalize GC Maintenance Process</span>
            <small>
            Are you sure you want to process the following?
            </small>
            </h1>
            <form method="post">
                {{ csrf_field() }}
                <div class="dropdown pull-right">
                    <button onclick="location.href='/member/mlm/gcmaintenance'" class="btn btn-def-white btn-custom-white"><i class="fa fa-close"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Proceed GC Maintenance Process</button>
                </div>
            </form>
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
                                    <th class="text-center">Customer Name</th>
                                    <th class="text-center" width="120px">Slot Owned</th>
                                    <th class="text-center" width="120px">Current Wallet</th>
                                    <th class="text-center" width="100px">Maintenance</th>
                                    <th class="text-right" width="200px">Wallet After Maintenance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_customer as $customer)
                                <tr>
                                    <td class="text-center">{{ $customer->first_name }} {{ $customer->last_name }}</td>
                                    <td class="text-center">{{ $customer->slot_count }} SLOT(S)</td>
                                    <td class="text-center">{{ $customer->customer_wallet }}</td>
                                    <td class="text-center">{{ $customer->maintenance }}</td>
                                    <td class="text-right">{{ $customer->display_wallet_after_maintenance }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection