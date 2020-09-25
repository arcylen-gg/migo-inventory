@extends('member.layout')
@section('content')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-truck"></i>
            <h1>
                <span class="page-title">Trucks</span>
                <small>
                    List of truck.
                </small>
            </h1>
            <div class="text-right">
            <a class="btn btn-primary panel-buttons popup" link="/member/pis/truck_list/add" size="md" data-toggle="modal" data-target="#global_modal">Add Truck</a>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group">
            <div class="col-md-6">
                <ul class="nav nav-tabs">
                  <li id="all-list" class="active"><a data-toggle="tab" href="#all"><i class="fa fa-star" aria-hidden="true"></i>&nbsp;Active Truck</a></li>
                  <li id="archived-list"><a data-toggle="tab" href="#archived"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;Archived Truck</a></li>
                </ul>
            </div>
            <div class="col-md-4 pull-right">
                <div class="input-group">
                    <span style="background-color: #fff; cursor: pointer;" class="input-group-addon" id="basic-addon1"><i class="fa fa-search"></i></span>
                    <input type="search" name="" class="form-control srch-warehouse-txt" placeholder="Start typing warehouse">
                </div>
            </div>
        </div>

        <div class="form-group tab-content panel-body truck-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>Truck Plate Number</th>
                                <th>Warehouse</th>
                                <th>Truck Info</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_truck != null)
                            @foreach($_truck as $truck)
                            <tr>
                                <td >{{strtoupper($truck->plate_number)}}</td>
                                <td>{{$truck->warehouse_name}}</td>
                                <td>{{$truck->truck_model}} ({{$truck->truck_kilogram}} kg.)</td>
                                <td class="text-center">
                                    <a link="/member/pis/truck_list/edit/{{$truck->truck_id}}" size="md" class="popup">Edit</a> |
                                    <a link="/member/pis/truck_list/archived/{{$truck->truck_id}}/archived" size="md" class="popup">Archived</a> 
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
                                <th>Truck Info</th>
                                <th>Truck Plate Number</th>
                                <th class="text-center">Warehouse</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_truck_archived != null)
                            @foreach($_truck_archived as $truck_archived)
                            <tr>
                                <td>{{$truck_archived->truck_model}} ({{$truck_archived->truck_kilogram}} kg.)</td>
                                <td >{{strtoupper($truck_archived->plate_number)}}</td>
                                <td>{{$truck_archived->warehouse_name}}</td>
                                <td class="text-center">
                                    <a link="/member/pis/truck_list/archived/{{$truck_archived->truck_id}}/restore" size="md" class="popup">Restore</a> 
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
<script type="text/javascript" src="/assets/member/js/truck.js"></script>
<!-- <script type="text/javascript" src="/assets/member/js/warehouse.js"></script> -->
@endsection