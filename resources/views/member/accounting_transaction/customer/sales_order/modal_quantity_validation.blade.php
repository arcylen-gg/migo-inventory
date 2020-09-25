<div class="#global_modal">
<div class="modal-content">
<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">×</button>
	<h4 class="modal-title" style="color:green">Warning!</h4>
</div>

<form class="global-submit" action="{{$action or ''}}" method="post">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<input type="hidden" name="button_action" value="{{$button_action}}">
<input type="hidden" name="sales_order_id" value="{{$so_id}}">
<input type="hidden" name="already_validate" value="1">
@if($insert)
@foreach($insert as $key_insert => $value_insert)
<input type="hidden" name="{{$key_insert}}" value="{{$value_insert}}">
@endforeach
@endif

@if($insert_item)
@foreach($insert_item as $key_insert_item => $value_insert_item)  
    @if($value_insert_item)
    @foreach($value_insert_item as $key_value_insert_item => $value_value_insert_item)
        <input type="hidden" name="{{$key_value_insert_item}}[]" value="{{$value_value_insert_item}}">
    @endforeach
    @endif
@endforeach
@endif
    <div class="modal-body">
    	<div class="row">
            <div class="clearfix modal-body">
                <div class="form-group">
                    <div class="col-md-12" align="center">
                        <h4>Item 
                            @if($item_name)
                            @foreach($item_name as $key => $item)
                            @if(count($item_name) > 1)
                            <b>{{($item)}},</b>
                            @else
                            <b>{{($item)}}</b>
                            @endif
                            @endforeach
                            @endif
                            is not enough qty to consume, Would you like to continue ?</h4>
                    </div> 
                </div>
            </div>
    	</div>
    </div>
    <div class="modal-footer">
    	<button type="button" class="btn btn-def-white btn-custom-white" data-dismiss="modal">No</button>
    	<button class="btn btn-primary btn-custom-primary" type="submit">Yes</button>
    </div>
</form>
</div>
</div>
@section('script')
<script type="text/javascript" src="/assets/member/js/accounting_transaction/customer/sales_order.js"></script>
@endsection