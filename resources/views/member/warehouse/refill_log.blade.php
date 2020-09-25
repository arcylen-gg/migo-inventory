@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-building"></i>
            <h1>
                <span class="page-title">Refill Logs</span>
            </h1>
            <div class="text-right">
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group tab-content panel-body inventory-log-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center">Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_slip != null)
                            @foreach($_slip as $slip)
                                <tr>
                                    <td>{{$slip->inventory_slip_id}}</td>
                                    <td class="text-center">{{date("M d, Y a",strtotime($slip->inventory_slip_date))}}</td>
                                    <td class="text-center">
                                        <a class="popup" size="lg" link="/member/item/warehouse/view_pdf/{{$slip->inventory_slip_id}}">View Report</a>
                                    </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection