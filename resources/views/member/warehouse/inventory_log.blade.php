@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-building"></i>
            <h1>
                <span class="page-title">Inventory Logs</span>
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
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Warehouse Name</th>
                                <th>Inventory Count</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        @if($_inventory_log != null)
                            @foreach($_inventory_log as $inventory_log)
                               @if($inventory_log->serial_id == null)
                                <tr>
                                    <td>{{$inventory_log->inventory_id}}</td>
                                    <td class="text-center">{{$inventory_log->item_name}}</td>
                                    <td>{{$inventory_log->warehouse_name}}</td>
                                    <td>{{$inventory_log->inventory_count}}</td>
                                    <td>{{date("M d, Y g:i a",strtotime($inventory_log->inventory_created))}}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                          <button type="button" class="btn btn-sm btn-custom-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Action <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-menu-custom">
                                                <li><a size="lg" link="/member/item/add_serial?inventory_id={{$inventory_log->inventory_id}}" href="javascript:" class="popup">Add Serial to this Item</a></li>
                                          </ul>
                                        </div>
                                        </td>
                                    </tr>
                                @endif
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