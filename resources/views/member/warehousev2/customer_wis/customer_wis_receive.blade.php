@extends('member.layout')
@section('content')

<form class="global-submit" method="post" action="/member/customer/wis/receive-inventory-submit">
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="panel panel-default panel-block panel-title-block" id="top">
    <div class="panel-heading">
        <div>
            <i class="fa fa-cubes"></i>
            <h1>
                <span class="page-title">Receiving Inventory</span>
            </h1>
            <div class="text-right">
                <a class="btn btn-custom-white panel-buttons" href="/member/item/warehouse/rr">Cancel</a>
                <button type="submit" class="btn btn-primary panel-buttons">Receive</button>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default panel-block panel-title-block">
    <div class="panel-body form-horizontal">
        <div class="form-group tab-content panel-body">
            <div class="col-md-6">
                <label>DR#</label>
                <input type="text" class="form-control" name="rr_number">
            </div>
        </div>
        <div class="form-group tab-content panel-body">
            <div class="col-md-12">
                <label>Remarks</label>
                <textarea class="form-control" name="rr_remarks"></textarea>
            </div>
        </div>
        <div class="form-group tab-content panel-body warehouse-container">
            <div id="all" class="tab-pane fade in active">
                <div class="form-group order-tags"></div>
                <div class="table-responsive">
                     <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th class="text-center">ITEM SKU</th>
                                <th class="text-center">ISSUED QTY</th>
                                <th class="text-center">RECEIVED QTY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($cust_wis_item) > 0)
                                @foreach($cust_wis_item as $item)
                                <tr>
                                    <td>{{$item->item_sku}}</td>
                                    <td>{{$item->wis_item_quantity}} pc(s)</td>
                                    <td>
                                        <input type="hidden" name="wis_item_quantity[{{$item->item_id}}]" value="{{$item->wis_item_quantity}}">
                                        <input type="text" class="form-control text-right" name="rr_item_quantity[{{$item->item_id}}]" value="0">
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
</form>
@endsection
@section('script')
<script type="text/javascript">
    function success_rr(data)
    {
        if(data.status == 'success')
        {
            toastr.success('Success receiving items');
            setInterval(function()
            {
                location.href = '/member/customer/wis';
            },2000);
        }
    }
</script>
@endsection