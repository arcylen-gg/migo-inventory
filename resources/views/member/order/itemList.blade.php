<ul class="list-group">
<?php $num = 1; ?>
@foreach($_item as $key => $item)
	<li class="list-group-item product-list c-pointer">
		<div class="checkbox">
			<label><input type="checkbox" name="main_check_box_{{$item['product_id']}}" class="main-check-box" id="main-check-box-{{$item['product_id']}}" data-content="{{$item['product_id']}}" value="{{$item['product_id']}}">&nbsp;<img class="img30x30" src="{!!$item['img']!!}">&nbsp;{!!$item['main_item']!!}</label>
		</div>
	</li>
	@if($item['variant'] != '')
	@foreach($item['variant'] as $k => $var)
	<li class="list-group-item product-list c-pointer">
		<div class="checkbox pull-right-20">
			</span><label><input type="checkbox" name="child_check_box_{{$num}}" class="child-check-box child-check-box-{{$item['product_id']}}" data-main="{{$item['product_id']}}" data-value="{{$var['variant_id']}}" value="{{$var['variant_id']}}">{!!$var['variant']!!}</label>
			<span class="pull-right pull-left-10px">₱&nbsp;{!!$var['price']!!}</span>
			<span class="pull-right pull-left-20px">{{$var['inventory']}}</span>
			<input type="hidden" name="def_price_{{$num}}" value="{{$var['price_def']}}">
		</div>
	</li>
	<?php $num++; ?>
	@endforeach
	@endif
@endforeach	
</ul>
<input type="hidden" name="item_count" value="{{$num}}">
<input type="hidden" name="_token" value="{{csrf_token()}}">