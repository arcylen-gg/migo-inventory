@extends('member.layout')
@section('content')

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-heading">
        <div>
            <i class="fa fa-user-secret"></i>
            <h1>
                <span class="page-title">SMS &raquo; Logs</span>
                <small>
                </small>
            </h1>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    
    <ul class="nav nav-tabs">
        <li class="cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#sms-system"><i class="fa fa-star"></i> Sms System Logs</a></li>
        <li class="active cursor-pointer"><a class="cursor-pointer" data-toggle="tab" href="#sms-api"><i class="fa fa-trash"></i> Sms Api Logs (Last 48 hours)</a></li>
    </ul>
    
    <!-- <div class="search-filter-box">
        <div class="col-md-4 col-md-offset-8" style="padding: 10px">
            <div class="input-group">
                <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search by Vendor Name" aria-describedby="basic-addon1">
            </div>
        </div>  
    </div> -->
    
    <div class="tab-content">
        <div id="sms-system" class="tab-pane fade in">
            <div class="load-data" target="sms-system-logs">
                <div id="sms-system-logs">
                    <div class="panel-body position-container">
                        <table style="table-layout: fixed;" class="table table-hover table-condensed table-bordered table-sale-month">
                            <thead>
                                <tr>
                                    <th class="text-left" width="10%">Date</th>
                                    <th class="text-left" width="22%">Key</th>
                                    <th class="text-left" width="8%">Status</th>
                                    <th class="text-left" width="10%">Recipient</th>
                                    <th class="text-left" width="48%">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_sms_system_logs as $system_logs)
                                <tr class="cursor-pointer">
                                    <td class="text-left">{{$system_logs->created_at}}</td>
                                    <td class="text-left">{{$system_logs->sms_logs_key}}</td>
                                    <td class="text-left">{{$system_logs->sms_logs_status}}</td>
                                    <td class="text-left">{{$system_logs->sms_logs_recipient}}</td>
                                    <td class="text-left">{{$system_logs->sms_logs_remarks}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         <div id="sms-api" class="tab-pane fade in active">
            <div class="panel-body position-container">
                <table style="table-layout: fixed;" class="table table-hover table-condensed table-bordered table-sale-month">
                    <thead>
                        <tr>
                            <th class="text-left">Date Sent</th>
                            <th class="text-left">Date Delivered</th>
                            <th class="text-left">Status</th>
                            <th class="text-left">Recipient</th>
                            <th class="text-left">Description</th>
                            <th class="text-left">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($_sms_api_logs as $system_logs)
                        <tr class="cursor-pointer">
                            <td class="text-left">{{$system_logs->sentAt}}</td>
                            <td class="text-left">{{$system_logs->doneAt}}</td>
                            <td class="text-left" style="color: {{$system_logs->status->groupName == 'DELIVERED' ? 'green' : 'red'}}">{{$system_logs->status->groupName}}</td>
                            <td class="text-left">{{$system_logs->to}}</td>
                            <td class="text-left">{{$system_logs->status->description}}</td>
                            <td class="text-left">{{$system_logs->text}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script type="text/javascript" src="/assets/member/js/paginate_ajax_multiple.js"></script>
@endsection