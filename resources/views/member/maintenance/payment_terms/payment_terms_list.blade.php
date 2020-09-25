@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-paypal"></i>
            <h1>
                <span class="page-title">Payment Method</span>
                <small>
                    List of Payment Method.
                </small>
            </h1>
            <div class="text-right">
            <a class="btn btn-primary panel-buttons popup" link="/member/maintenance/payment_method/add" size="md" data-toggle="modal" data-target="#global_modal">Add Payment Method</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active Payment Method</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#archived"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived Payment Method</a></li>
                </ul>
            </div>
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="search" name="" class="form-control srch-warehouse-txt" placeholder="Start typing warehouse">
                </div>
            </div>
        </div>

        <div class="form-group tab-content panel-body payment-method-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Payment Method</th>
                                <th>Default</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_payment_method != null)
                            @foreach($_payment_method as $payment_method)
                            <tr>
                                <td>{{$payment_method->payment_method_id}}</td>
                                <td>{{$payment_method->payment_name}}</td>
                                <td><input type="radio" onclick="selectDefault({{$payment_method->payment_method_id}})" name="isDefault" {{$payment_method->isDefault == 1 ? 'checked' : ''}}></td>
                                <td class="text-center">
                                    <a link="/member/maintenance/payment_method/add?id={{$payment_method->payment_method_id}}" size="md" class="popup">Edit</a> |
                                    <a link="/member/maintenance/payment_method/archived/{{$payment_method->payment_method_id}}/archived" size="md" class="popup">Archived</a> 
                                </td>
                            </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="archived" class="tab-pane fade in">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                         <thead>
                            <tr>
                                <th>#</th>
                                <th>Payment Method</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_payment_method_archived != null)
                            @foreach($_payment_method_archived as $payment_method_archived)
                            <tr>
                                <td>{{$payment_method_archived->payment_method_id}}</td>
                                <td>{{$payment_method_archived->payment_name}}</td>
                                <td class="text-center">
                                    <a link="/member/maintenance/payment_method/archived/{{$payment_method_archived->payment_method_id}}/restore" size="md" class="popup">Restore</a> 
                                </td>
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
@section("script")
<script type="text/javascript" src="/assets/member/js/payment_method.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->
@endsection