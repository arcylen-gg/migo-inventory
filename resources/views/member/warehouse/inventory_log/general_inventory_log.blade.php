@extends('member.layout')
@section('content')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-building"></i>
            <h1>
                <span class="page-title">All Inventory Logs</span>
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
                        <tbody>
                            <tr>

                                <td class="hide">
                                    <form method="get" action="/member/item/inventory_log">
                                    {!! csrf_field() !!}
                                        <div class="col-md-4">
                                            <label><small style="color: gray">Search</small></label>
                                            <input type="text" class="form-control" placeholder="Search by item name or sku" name="item">
                                        </div>
                                        <div class="col-md-4">
                                            <label><small style="color: gray">Filter</small></label>
                                            <select class="form-control" name="filter">
                                                @foreach($filter_inventory as $key => $value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label><small style="color: gray">Submit</small></label><br>
                                            <button class="btn btn-primary">Search</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Transaction</th>
                                <th class="text-center">Date</th>
                                <th>Item</th>
                                <th>Quantity Involved</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="table-warehouse">
                        
                        @if($_slip != null)
                            @foreach($_slip as $slip)
                                <tr>
                                    <td>{{$slip->inventory_slip_id}}</td>
                                    <td>{{strtoupper($slip->inventory_reason)}}</td>
                                    <td class="text-center">{{date("M d, Y h:i a",strtotime($slip->inventory_slip_date))}}</td>
                                    <td>{{$slip->item_name}}
                                    <?php 
                                    if(isset($item_sum_inv[$slip->item_name]))
                                    {
                                        $item_sum_inv[$slip->item_name] +=$slip->inventory_count;
                                    }
                                    else
                                    {
                                        $item_sum_inv[$slip->item_name] = $slip->inventory_count;
                                    }
                                    
                                    ?>
                                    </td>
                                    <td>{{$slip->inventory_count}}</td>
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