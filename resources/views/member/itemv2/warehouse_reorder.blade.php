@extends('member.layout')
@section('content')
<form class="global-submit" role="form" action="{{ $action or ''}}" method="POST" >
    <div class="panel panel-default panel-block panel-title-block">
        <input type="hidden" name="_token" id="_token" value="{{csrf_token()}}"/>
        <div class="panel-heading">
            <div>
                <i class="fa fa-cart-plus"></i>
                <h1>
                <span class="page-title">{{$page}}</span>
                <small>
                     Set re-oreder point per Warehouse
                </small>
                </h1>
                <div class="dropdown pull-right">
                    <div>
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block panel-gray">
    <div class="data-container" >
        <div class="tab-content">
            <div class="row">  
                <div class="col-md-12 text-center" style="padding: 30px;padding-bottom: 5px">
                   <input type="text" class="hidden warehouse-id" readonly="true" name="warehouse_id" value="{{$warehouse ? $warehouse->warehouse_id : ''}}">
                   <h3>{{$warehouse ? $warehouse->warehouse_name : 'NO WAREHOUSE FOUND!' }}</h3>
                </div>
            </div>
            <div class="row clearfix draggable-container" style="padding: 30px;padding-top: 5px">
                <div class="table-responsive">
                    <div class="col-sm-12">
                        <table class="digima-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th>Reorder Point</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($_item as $key => $item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td><label>{{$item->item_name}}</label></td>
                                    <td><input type="text" name="reorder[{{$item->item_id}}]" value="{{$item->warehouse_reorder or '0'}}"></td>
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
<div class="modal fade edit-bulk-item" value="" role="dialog"></div>
</form>
@endsection

@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/warehouse_reorder/warehouse_reorder.js"></script>
@endsection