@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-cubes"></i>
            <h1>
                <span class="page-title">Receiving Report</span>
            </h1>
            <div class="text-right">
                <a class="btn btn-primary panel-buttons popup" link="/member/transaction/receiving_report/receive-code" size="md"> <i class="fa fa-icon fa-star"></i> Receiving Report</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Received</a></li>
                </ul>
            </div>
        </div>

        <div class="form-group tab-content panel-body warehouse-container">
            <div id="all" class="tab-pane fade in active">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>RR Number</th>
                                <th class="hidden">Total Receive Items</th>
                                <th>From</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($_rr) > 0)
                                @foreach($_rr as $key => $rr)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$rr->rr_number}}</td>
                                    <td  class="hidden"> pc(s)</td>
                                    <td>{{$rr->wis_number}}</td>
                                    <td>{{date('F d, Y',strtotime($rr->rr_date_received))}}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Action <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-custom">
                                                <li> <a target="_blank" href="/member/transaction/receiving_report/print?id={{$rr->rr_id}}">Print</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-center">No Item Receive Yet</td>
                            </tr>
                            @endif                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/warehouse/rr.js"></script>
@endsection
