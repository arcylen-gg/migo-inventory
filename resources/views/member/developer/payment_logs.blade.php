@extends('member.layout')
@section('content')
<div class="panel panel-default panel-block panel-title-block">
    <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
    <div class="panel-heading">
        <div>
            <i class="fa fa-calendar"></i>
            <h1>
            <span class="page-title">Payment Logs</span>
            <small>
            These are for developers to check list of Payment Logs
            </small>
            </h1>
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
                                    <th class="text-center">ID</th>
                                    <th class="text-center">ORDER ID</th>
                                    <th class="text-center">TYPE</th>
                                    <th class="text-center">METHOD</th>
                                    <th class="text-center">DATE</th>
                                    <th class="text-center">URL</th>
                                    <th class="text-center">IP ADDRESS</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_payment_logs as $log)
                                <tr>
                                    <td class="text-center">{{ $log->payment_log_id }}</td>
                                    <td class="text-center">{!! $log->display_transaction_list_id !!}</td>
                                    <td class="text-center">{{ $log->payment_log_type }}</td>
                                    <td class="text-center">{{ $log->payment_log_method }}</td>
                                    <td class="text-center">{{ $log->display_date }}</td>
                                    <td class="text-center">{{ $log->payment_log_url }}</td>
                                    <td class="text-center">{{ $log->payment_log_ip_address }}</td>
                                    <td class="text-center"><a onclick="action_load_link_to_modal('/member/developer/payment_logs/{{ $log->payment_log_id }}', 'lg')" href='javascript:'>VIEW DETAILS</a></td>
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